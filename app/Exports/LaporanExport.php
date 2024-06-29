<?php

namespace App\Exports;

use App\Models\Laporan;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;

class LaporanExport implements FromCollection, WithHeadings, WithStyles, WithMapping
{
    protected $status;
    protected $startDate;
    protected $endDate;
    public function __construct($status = null, $startDate = null, $endDate = null)
    {
        $this->status = $status;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Laporan::query();

        if ($this->status) {
            $query->where('status_laporan', $this->status);
        }

        if ($this->startDate) {
            $query->where('created_at', '>=', $this->startDate);
        }

        if ($this->endDate) {
            $query->where('created_at', '<=', $this->endDate);
        }

        return $query->get(['judul_laporan', 'alamat_laporan', 'status_laporan', 'created_at', 'tanggal_teratasi']);
    }

    public function headings(): array
    {
        return [
            'No',
            'Judul Laporan',
            'Tempat Kejadian',
            'Status',
            'Tanggal Pelaporan',
            'Tanggal Teratasi'
        ];
    }

    public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]]
        ];
    }

    public function map($laporan): array
    {
        static $row = 0;
        $row++;

        return [
            $row,
            $laporan->judul_laporan,
            $laporan->alamat_laporan,
            $laporan->status_laporan,
            Carbon::parse($laporan->created_at)->translatedFormat('d F Y'),
            $laporan->tanggal_teratasi ? $laporan->tanggal_teratasi : "Belum Teratasi",
            // $laporan->tanggal_teratasi ? $laporan->tanggal_teratasi->format('d F Y') : "Belum Teratasi"
        ];
    }
}
