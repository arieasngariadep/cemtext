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

class ResultSameTech767Export extends DefaultValueBinder implements FromCollection, WithHeadings, WithColumnFormatting, WithCustomValueBinder
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
            'E' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2,
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
        $data = CemtextModel::getDataCemtext767($this->userId);

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
                'SUSPENSE / TRF DTL' => $d->suspense,
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
            'SUSPENSE/TRF DTL',
        ];
    }

    public function bindValue(Cell $cell, $value)
    {
        if ($cell->getColumn() == 'B' || $cell->getColumn() == 'C' || $cell->getColumn() == 'D' || $cell->getColumn() == 'J') {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);

            return true;
        }

        // else return default behavior
        return parent::bindValue($cell, $value);
    }
}
