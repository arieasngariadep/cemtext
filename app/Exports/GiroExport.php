<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\CemtextModel;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class GiroExport extends DefaultValueBinder implements FromCollection, WithHeadings, WithCustomValueBinder, WithColumnFormatting
{
    public function columnFormats(): array
    {
        return [
            'L' => '[Blue]#,##0.00_);[Red](#,##0.00)',
            'N' => '[Blue]#,##0.00_);[Red](#,##0.00)',
            'E' => '@',
            'I' => '@'
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
        $data = CemtextModel::getDataGiro($this->userId);

        foreach ($data as $d) {
            $export[] = array( 
                'NO. ACCOUNT' => $d->no_account,
                'NAMA ACCOUNT' => $d->nama_account,
                'TGL. TRANS' => $d->tgl_trans,
                'TGL. VALUTA' => $d->tgl_valuta,
                'NO. DOKUMEN' => $d->no_dokumen,
                'URAIAN 1' => $d->uraian_mutasi_1,
                'URAIAN 2' => $d->uraian_mutasi_2,
                'URAIAN 3' => $d->uraian_mutasi_3,
                'URAIAN 4' => $d->uraian_mutasi_4,
                'URAIAN 5' => $d->uraian_mutasi_5,
                'URAIAN 6' => $d->uraian_mutasi_6,
                'NOMINAL' => $d->mutasi,
                'POS' => $d->pos,
                'SALDO' => $d->saldo
            );
        }
        return collect($export);
    }
    
    public function headings(): array
    {
        return [
            'NO. ACCOUNT',
            'NAMA ACCOUNT',
            'TGL. TRANS',
            'TGL. VALUTA',
            'NO. DOKUMEN',
            'URAIAN 1',
            'URAIAN 2',
            'URAIAN 3',
            'URAIAN 4',
            'URAIAN 5',
            'URAIAN 6',
            'NOMINAL',
            'POS',
            'SALDO'
        ];
    }

    public function bindValue(Cell $cell, $value)
    {
        if ($cell->getColumn() == 'A' || $cell->getColumn() == 'F' || $cell->getColumn() == 'G' || $cell->getColumn() == 'H' || $cell->getColumn() == 'J') {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);

            return true;
        }

        // else return default behavior
        return parent::bindValue($cell, $value);
    }
}
