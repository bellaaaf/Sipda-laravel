<?php

namespace App\Exports;

use App\Models\LaporanMasyarakat;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporanExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct(private Request $request) {}

    public function query()
    {
        $query = LaporanMasyarakat::with('user')->orderByDesc('created_at');

        if ($this->request->filled('status')) {
            $query->where('status', $this->request->status);
        }
        if ($this->request->filled('dari')) {
            $query->whereDate('created_at', '>=', $this->request->dari);
        }
        if ($this->request->filled('sampai')) {
            $query->whereDate('created_at', '<=', $this->request->sampai);
        }

        return $query;
    }

    public function headings(): array
    {
        return ['No', 'Nama Pelapor', 'Email', 'Telepon', 'Jenis Bencana', 'Lokasi', 'Keparahan', 'Korban Jiwa', 'Korban Luka', 'Rumah Rusak', 'Status', 'Tanggal Lapor'];
    }

    public function map($row): array
    {
        static $i = 0;
        return [
            ++$i,
            $row->nama_pelapor,
            $row->email_pelapor,
            $row->telepon ?? '-',
            $row->jenis_bencana,
            $row->lokasi_kejadian,
            $row->tingkat_keparahan,
            $row->korban_jiwa,
            $row->korban_luka,
            $row->rumah_rusak,
            $row->status,
            $row->created_at->format('d/m/Y H:i'),
        ];
    }
}
