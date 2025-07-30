<?php

namespace App\Exports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\{
    FromCollection,
    WithHeadings,
    WithStyles,
    WithEvents,
    ShouldAutoSize
};
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Events\AfterSheet;

class RekapAbsensiBulananExport implements FromCollection, WithHeadings, WithStyles, WithEvents, ShouldAutoSize
{
    protected $bulan;
    protected $tahun;

    protected $totalHadir = 0;
    protected $totalSakit = 0;
    protected $totalIjin = 0;
    protected $totalAlfa = 0;

    public function __construct($bulan, $tahun)
    {
        $this->bulan = $bulan;
        $this->tahun = $tahun;
    }

    public function collection()
    {
        $data = Siswa::with(['absensi' => function ($query) {
            $query->whereMonth('tanggal', $this->bulan)
                  ->whereYear('tanggal', $this->tahun);
        }])->get()->map(function ($siswa) {
            $hadir = $siswa->absensi->where('keterangan', 'Hadir')->count();
            $sakit = $siswa->absensi->where('keterangan', 'Sakit')->count();
            $ijin  = $siswa->absensi->where('keterangan', 'Ijin')->count();
            $alfa  = $siswa->absensi->where('keterangan', 'Alfa')->count();
            $total = $hadir + $sakit + $ijin + $alfa;

            // Simpan total keseluruhan untuk baris terakhir
            $this->totalHadir += $hadir;
            $this->totalSakit += $sakit;
            $this->totalIjin  += $ijin;
            $this->totalAlfa  += $alfa;

            return [
                $siswa->nama,
                $hadir,
                $sakit,
                $ijin,
                $alfa,
                $total
            ];
        });

        // Tambah baris total keseluruhan
        $totalKeseluruhan = $this->totalHadir + $this->totalSakit + $this->totalIjin + $this->totalAlfa;
        $data->push([
            'Total',
            $this->totalHadir,
            $this->totalSakit,
            $this->totalIjin,
            $this->totalAlfa,
            $totalKeseluruhan
        ]);

        return $data;
    }

    public function headings(): array
    {
        return [
            'Nama Siswa',
            'Hadir',
            'Sakit',
            'Ijin',
            'Alfa',
            'Total Absen',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], // Header tebal
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet;
                $highestRow = $sheet->getDelegate()->getHighestRow();
                $highestColumn = $sheet->getDelegate()->getHighestColumn();

                // Format angka
                $sheet->getStyle("B2:F" . $highestRow)
                      ->getNumberFormat()
                      ->setFormatCode(NumberFormat::FORMAT_NUMBER);

                // Border semua cell
                $sheet->getStyle("A1:" . $highestColumn . $highestRow)
                      ->applyFromArray([
                          'borders' => [
                              'allBorders' => [
                                  'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                              ],
                          ],
                      ]);

                // Bold baris total
                $sheet->getStyle("A" . $highestRow . ":F" . $highestRow)
                      ->applyFromArray([
                          'font' => ['bold' => true],
                      ]);

                // Rata tengah semua data (kecuali kolom nama)
                $sheet->getStyle("B2:F" . $highestRow)
                      ->getAlignment()
                      ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
        ];
    }
}
