<?php

namespace App\Exports;

use App\Models\CemtextModel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

class ResultSameTech777Export extends DefaultValueBinder implements FromCollection, WithHeadings, WithColumnFormatting, WithCustomValueBinder
{
    public function map($data): array
    {
        return [
            $data->nominal
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => '@',
            'C' => '@',
            'D' => '@',
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
            'L' => '@',
            'N' => '@',
            'O' => '@',
            'P' => '@',
            'Q' => '@',
            'R' => '@',
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
        $data = CemtextModel::getDataCemtext777($this->userId);

        foreach ($data as $d) {
            $export[] = array( 
                'SEQ NO' => $d->seq_no,
                'REKENING' => $d->rekening,
                'TRAN' => $d->tranx,
                'JRNL' => $d->jrnl,
                'NOMINAL' => $d->nominal,
                'TANGGAL' => $d->tanggal,
                'SYS' => $d->sys,
                'CHEQUE' => $d->cheque,
                'ERR' => $d->err,
                'MESSAGE' => $d->messages,
                'SUSPENSE 1' => $d->suspense_1,
                'SUSPENSE 2' => $d->suspense_2,
                'SUSPENSE 3' => $d->suspense_3,
                'SUSPENSE 4' => $d->suspense_4,
                'SUSPENSE 5' => $d->suspense_5,
                'SUSPENSE 6' => $d->suspense_6,
                'SUSPENSE 7' => $d->suspense_7,
                'SUSPENSE 8' => $d->suspense_8,
            );
        }
        return collect($export);
    }

    public function headings(): array
    {
        return [
            'SEQ NO',
            'REKENING',
            'TRAN',
            'JRNL',
            'NOMINAL',
            'TANGGAL',
            'SYS',
            'CHEQUE',
            'ERR',
            'MESSAGE',
            'SUSPENSE 1',
            'SUSPENSE 2',
            'SUSPENSE 3',
            'SUSPENSE 4',
            'SUSPENSE 5',
            'SUSPENSE 6',
            'SUSPENSE 7',
            'SUSPENSE 8',
        ];
    }

    public function bindValue(Cell $cell, $value)
    {
        if ($cell->getColumn() == 'B' || $cell->getColumn() == 'C' || $cell->getColumn() == 'D' || $cell->getColumn() == 'G' || $cell->getColumn() == 'J' || $cell->getColumn() == 'L' || $cell->getColumn() == 'N' || $cell->getColumn() == 'O' || $cell->getColumn() == 'P' || $cell->getColumn() == 'Q' || $cell->getColumn() == 'R') {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);

            return true;
        }

        // else return default behavior
        return parent::bindValue($cell, $value);
    }
}
