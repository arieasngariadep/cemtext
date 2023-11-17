<?php

namespace App\Exports\FUO0;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\CemtextModel;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class SyariahFuo0Export extends DefaultValueBinder implements FromCollection, WithHeadings, WithCustomValueBinder, WithColumnFormatting, WithTitle
{
    public function columnFormats(): array
    {
        return [
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2
        ];
    }

    function __construct($userId)
    {
        $this->userId = $userId;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data = CemtextModel::getDataSyariahFuo0Export($this->userId);

        foreach ($data as $d) {
            $export[] = array( 
                'FREC' => $d->frec,
                'Tanggal' => $d->tanggal,
                'Nomor Kartu' => $d->nomor_kartu,
                'ID 1300' => $d->id_1300,
                'ID ATM' => $d->id_atm,
                'Kode MC 2' => $d->kode_mc2,
                'Amount' => $d->amount,
            );
        }
        return collect($export);
    }
    
    public function headings(): array
    {
        return [
            'FREC',
            'Tanggal',
            'Nomor Kartu',
            'ID 1300',
            'ID ATM',
            'Kode MC 2',
            'Amount',
        ];
    }

    public function bindValue(Cell $cell, $value)
    {
        if ($cell->getColumn() == 'A' || $cell->getColumn() == 'B' || $cell->getColumn() == 'C' || $cell->getColumn() == 'D' || $cell->getColumn() == 'E' || $cell->getColumn() == 'F') {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);

            return true;
        }

        // else return default behavior
        return parent::bindValue($cell, $value);
    }
    
    /**
     * @return string
     */
    public function title(): string
    {
        $tanggal = CemtextModel::getTanggalReportSyariahFUO0($this->userId);
        return 'TOTAL BIC CC ' . $tanggal->tanggal_report;
    }
}