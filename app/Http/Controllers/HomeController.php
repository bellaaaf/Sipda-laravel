<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use App\Models\DataBencana;
use App\Models\LaporanMasyarakat;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    public function index()
    {
        $bencanaAktif = DataBencana::with('jenis')
            ->whereIn('tingkat_status', ['Darurat', 'Siaga', 'Waspada'])
            ->orderByRaw("FIELD(tingkat_status,'Darurat','Siaga','Waspada')")
            ->orderByDesc('tanggal_kejadian')
            ->take(6)
            ->get();

        $beritaTerbaru = Berita::with(['bencana.jenis', 'admin'])
            ->where('status', 'published')
            ->orderByDesc('created_at')
            ->take(6)
            ->get();

        $statistik = [
            'total_bencana'   => DataBencana::count(),
            'bencana_darurat' => DataBencana::where('tingkat_status', 'Darurat')->count(),
            'total_laporan'   => LaporanMasyarakat::count(),
            'laporan_pending' => LaporanMasyarakat::where('status', 'pending')->count(),
        ];

        $weatherData = $this->fetchWeatherJabar();

        return view('home', compact('bencanaAktif', 'beritaTerbaru', 'statistik', 'weatherData'));
    }

    public function layanan()
    {
        return view('layanan');
    }

    private function fetchWeatherJabar(): array
    {
        $apiKey = config('services.openweather.key');

        if (!$apiKey || $apiKey === 'your_openweather_api_key_here') {
            return [];
        }

        return Cache::remember('weather_jabar_home', 1800, function () use ($apiKey) {
            $cities = [
                'Bandung,ID',
                'Bogor,ID',
                'Bekasi,ID',
                'Cirebon,ID',
                'Sukabumi,ID',
                'Tasikmalaya,ID',
            ];

            $results = [];
            foreach ($cities as $city) {
                try {
                    $res = Http::timeout(5)->get('https://api.openweathermap.org/data/2.5/weather', [
                        'q'     => $city,
                        'appid' => $apiKey,
                        'units' => 'metric',
                        'lang'  => 'id',
                    ]);

                    if ($res->ok()) {
                        $d = $res->json();
                        $results[] = [
                            'kota'       => $d['name'],
                            'suhu'       => round($d['main']['temp']),
                            'terasa'     => round($d['main']['feels_like']),
                            'kelembaban' => $d['main']['humidity'],
                            'angin'      => round($d['wind']['speed'] * 3.6, 1),
                            'deskripsi'  => ucfirst($d['weather'][0]['description']),
                            'ikon_kode'  => $d['weather'][0]['icon'],
                            'cuaca_id'   => $d['weather'][0]['id'],
                        ];
                    }
                } catch (\Exception) {
                    // lewati kota yang gagal di-fetch
                }
            }

            return $results;
        });
    }
}