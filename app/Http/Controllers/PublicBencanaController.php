<?php

namespace App\Http\Controllers;

use App\Models\DataBencana;
use App\Models\JenisBencana;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class PublicBencanaController extends Controller
{
    public function index()
    {
        $bencana = DataBencana::with('jenis')
            ->orderByRaw("FIELD(tingkat_status,'Darurat','Siaga','Waspada','Aman')")
            ->orderByDesc('tanggal_kejadian')
            ->paginate(12);

        $jenis = JenisBencana::all();

        $mapData = DataBencana::with('jenis')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get(['id', 'jenis_id', 'lokasi', 'tingkat_status', 'tanggal_kejadian', 'latitude', 'longitude']);

        $weatherData = $this->fetchWeatherJabar();

        return view('bencana.index', compact('bencana', 'jenis', 'mapData', 'weatherData'));
    }

    public function show(DataBencana $bencana)
    {
        $bencana->load(['jenis', 'berita' => fn($q) => $q->where('status', 'published'), 'updates']);
        return view('bencana.show', compact('bencana'));
    }

    private function fetchWeatherJabar(): array
    {
        $apiKey = config('services.openweather.key');

        if (!$apiKey || $apiKey === 'your_openweather_api_key_here') {
            return [];
        }

        return Cache::remember('weather_jabar', 1800, function () use ($apiKey) {
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
