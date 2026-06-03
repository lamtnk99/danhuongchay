<?php

namespace App\Exports;

use App\Models\Reservation;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;

use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ReservationsExport implements FromCollection, WithHeadings, WithMapping, WithEvents, ShouldAutoSize
{
    public function __construct(private readonly Collection $reservations)
    {
    }

    public function collection(): Collection
    {
        return $this->reservations;
    }

    public function headings(): array
    {
        return [
            'Ngày đặt',
            'Giờ đặt',
            'Tên khách',
            'Điện thoại',
            'Email',
            'Cơ sở',
            'Số khách',
            'Ghi chú khách',
            'Ghi chú admin',
            'Trạng thái',
            'Ngày tạo',
        ];
    }

    public function map($reservation): array
    {
        /** @var Reservation $reservation */
        return [
            optional($reservation->reservation_date)->format('d/m/Y'),
            substr((string) $reservation->reservation_time, 0, 5),
            $reservation->name,
            $reservation->phone,
            $reservation->email,
            $reservation->branch?->name ?? '',
            $reservation->guests,
            $reservation->note,
            $reservation->admin_note,
            $reservation->statusLabel(),
            optional($reservation->created_at)->format('d/m/Y H:i'),
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event): void {
                $sheet = $event->sheet->getDelegate();

                $sheet->insertNewRowBefore(1, 2);
                $sheet->setCellValue('A1', 'Danh sách đặt bàn');
                $sheet->setCellValue('A2', 'Xuất từ hệ thống quản trị đặt bàn.');

                $highestColumn = $sheet->getHighestColumn();
                $highestRow = $sheet->getHighestRow();

                $sheet->mergeCells('A1:' . $highestColumn . '1');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 16,
                        'color' => ['rgb' => '1E293B'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(28);

                $sheet->mergeCells('A2:' . $highestColumn . '2');
                $sheet->getStyle('A2')->applyFromArray([
                    'font' => [
                        'italic' => true,
                        'size' => 11,
                        'color' => ['rgb' => '64748B'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $sheet->getRowDimension(2)->setRowHeight(22);

                $sheet->getStyle("A3:{$highestColumn}3")->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '667132'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'D1D5DB'],
                        ],
                    ],
                ]);
                $sheet->getRowDimension(3)->setRowHeight(24);

                $sheet->getStyle("A4:{$highestColumn}{$highestRow}")->applyFromArray([
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_TOP,
                        'wrapText' => true,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['rgb' => 'E5E7EB'],
                        ],
                    ],
                ]);

                $sheet->freezePane('A4');
                $sheet->setAutoFilter("A3:{$highestColumn}{$highestRow}");

                foreach (['A' => 'dd/mm/yyyy', 'B' => '@', 'D' => '@', 'E' => '@', 'F' => '@', 'G' => '0', 'K' => 'dd/mm/yyyy hh:mm'] as $column => $format) {
                    $sheet->getStyle("{$column}4:{$column}{$highestRow}")
                        ->getNumberFormat()
                        ->setFormatCode($format);
                }
            },
        ];
    }
}
