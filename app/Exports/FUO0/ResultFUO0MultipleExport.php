<?php

namespace App\Exports\FUO0;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use App\Models\CemtextModel;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Illuminate\Support\Facades\DB;

class ResultFUO0MultipleExport extends DefaultValueBinder implements FromCollection, WithTitle, WithHeadings, WithColumnFormatting, WithCustomValueBinder
{
    private $no_file;
    private $userId;

    public function __construct(int $no_file, int $userId)
    {
        $this->no_file = $no_file;
        $this->userId  = $userId;
    }

    public function columnFormats(): array
    {
        return [
            'B' => '@',
            'C' => '@',
            'D' => '@',
            'E' => '@',
            'F' => '@',
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2
        ];
    }
    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data = CemtextModel::getDataFuo0MultipleExport($this->no_file, $this->userId);

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
        $tanggal = CemtextModel::getTanggalReportFUO0Multiple($this->no_file, $this->userId);
        return 'FOU0 ' . $tanggal->tanggal_report;
    }
}
