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

class ReportRekeningKoranExport extends DefaultValueBinder implements FromCollection, WithHeadings, WithCustomValueBinder, WithColumnFormatting
{
    public function map($data): array
    {
        return [
            $data->nominal,
            $data->saldo
        ];
    }

    public function columnFormats(): array
    {
        return [
            'C' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'M' => '[Blue]#,##0.00_);[Red](#,##0.00)',
            'O' => '[Blue]#,##0.00_);[Red](#,##0.00)'
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
        $data = CemtextModel::getDataRekeningKoran($this->userId);

        foreach ($data as $d) {
            $export[] = array( 
                'NO. ACCOUNT' => $d->no_rek,
                'NAMA ACCOUNT' => $d->nama_rek,
                'TANGGAL' => $d->tanggal,
                'NO. JURNAL' => $d->no_jurnal,
                'ENTITY' => $d->entity,
                'USER' => $d->user,
                'TC' => $d->tc,
                'KET 1' => $d->keterangan_1,
                'KET 2' => $d->keterangan_2,
                'KET 3' => $d->keterangan_3,
                'KET 4' => $d->keterangan_4,
                'KET 5' => $d->keterangan_5,
                'NOMINAL' => $d->nominal,
                'POS' => $d->pos,
                'SALDO' => $d->saldo,
                'POS SALDO' => $d->pos_saldo,
            );
        }
        return collect($export);
    }
    
    public function headings(): array
    {
        return [
            'NO. ACCOUNT',
            'NAMA ACCOUNT',
            'TANGGAL',
            'NO. JURNAL',
            'ENTITY',
            'USER',
            'TC',
            'KET 1',
            'KET 2',
            'KET 3',
            'KET 4',
            'KET 5',
            'NOMINAL',
            'POS',
            'SALDO',
            'POS SALDO',
        ];
    }

    public function bindValue(Cell $cell, $value)
    {
        if ($cell->getColumn() == 'A' || $cell->getColumn() == 'D' || $cell->getColumn() == 'E' || $cell->getColumn() == 'F' || $cell->getColumn() == 'G') {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);

            return true;
        }

        // else return default behavior
        return parent::bindValue($cell, $value);
    }
}
