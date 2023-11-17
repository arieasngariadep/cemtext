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

class RekapExport extends DefaultValueBinder implements FromCollection, WithHeadings, WithColumnFormatting, WithCustomValueBinder
{
    public function map($data): array
    {
        return [
            $data->debit_amount
        ];
    }

    public function columnFormats(): array
    {
        return [
            'N' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED2
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
        $data = CemtextModel::getReportRekap($this->userId);

        foreach ($data as $d) {
            $export[] = array( 
                'description' => $d->description,
                'post_date' => $d->post_date,
                'txn_date' => $d->txn_date,
                'to_Act' => $d->to_Act,
                'mid_no' => $d->mid_no,
                'merchant_name' => $d->merchant_name,
                'shift' => $d->shift,
                'Credit_date' => $d->credit_date,
                'Credit_month' => $d->credit_month,
                'Credit_year' => $d->credit_year,
                'Txn_day' => $d->txn_day,
                'Txn_month' => $d->txn_month,
                'Txn_year' => $d->txn_year,
                'debit_amt' => $d->debit_amount,
            );
        }
        return collect($export);
    }

    public function headings(): array
    {
        return [
            'description',
            'post_date',
            'txn_date',
            'to_Act',
            'mid_no',
            'merchant_name',
            'shift',
            'Credit_date',
            'Credit_month',
            'Credit_year',
            'Txn_day',
            'Txn_month',
            'Txn_year',
            'debit_amt',
        ];
    }
}
