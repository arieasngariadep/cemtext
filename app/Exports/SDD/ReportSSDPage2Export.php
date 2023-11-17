<?php

namespace App\Exports\SDD;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\CemtextModel;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

class ReportSSDPage2Export extends DefaultValueBinder implements FromCollection, WithHeadings, WithCustomValueBinder
{
    /**
    * @return \Illuminate\Support\Collection
    */

    function __construct($userId)
    {
        $this->userId = $userId;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data = CemtextModel::getDataReportSDDPage2Export($this->userId);

        foreach ($data as $d) {
            $export[] = array( 
                'SEQ-NO' => $d->seq_no,
                'ACCOUNT' => $d->account,
                'TRAN-DATE' => $d->tran_date,
                'POST-DATE' => $d->post_date,
                'JRNL-NO' => $d->jrnl_no,
                'BRANCH-NO' => $d->branch_no,
                'DESC-1' => $d->descr_1,
                'DESC-2' => $d->descr_2,
                'DESC-3' => $d->descr_3,
                'DESC-4' => $d->descr_4,
                'DESC-5' => $d->descr_5,
                'AMOUNT' => $d->amount,
                'D/K' => $d->dr_cr,
                'BALANCE' => $d->balance,
                'DESC-36' => $d->descr_36,
                'DESC-38' => $d->descr_38,
                'DESC-39' => $d->descr_39,
                'DESC-20-1' => $d->descr_20_1,
                'DESC-20-2' => $d->descr_20_2,
                'DESC-20-3' => $d->descr_20_3,
                'POST-TIME' => $d->post_time,
                'BEGIN-BAL' => $d->begin_bal,
                'CCY' => $d->currency,
                'TELLER-ID' => $d->tell_id,
                'TRANCODE' => $d->tran_code,
                'GL-CLASS' => $d->gl_glass_code,
                'BRANCH' => $d->home_branch,
            );
        }
        return collect($export);
    }
    
    public function headings(): array
    {
        return [
            'SEQ-NO',
            'ACCOUNT',
            'TRAN-DATE',
            'POST-DATE',
            'JRNL-NO',
            'BRANCH-NO',
            'DESC-1',
            'DESC-2',
            'DESC-3',
            'DESC-4',
            'DESC-5',
            'AMOUNT',
            'D/K',
            'BALANCE',
            'DESC-36',
            'DESC-38',
            'DESC-39',
            'DESC-20-1',
            'DESC-20-2',
            'DESC-20-3',
            'POST-TIME',
            'BEGIN-BAL',
            'CCY',
            'TELLER-ID',
            'TRANCODE',
            'GL-CLASS',
            'BRANCH',
        ];
    }

    public function bindValue(Cell $cell, $value)
    {
        if ($cell->getColumn() == 'A' || $cell->getColumn() == 'B' || $cell->getColumn() == 'C' || $cell->getColumn() == 'D' || $cell->getColumn() == 'E' || $cell->getColumn() == 'F' || $cell->getColumn() == 'G' || $cell->getColumn() == 'H' || $cell->getColumn() == 'I' || $cell->getColumn() == 'J' || $cell->getColumn() == 'K' || $cell->getColumn() == 'L' || $cell->getColumn() == 'M' || $cell->getColumn() == 'N' || $cell->getColumn() == 'O' || $cell->getColumn() == 'P' || $cell->getColumn() == 'Q' || $cell->getColumn() == 'R' || $cell->getColumn() == 'S' || $cell->getColumn() == 'T' || $cell->getColumn() == 'U' || $cell->getColumn() == 'V' || $cell->getColumn() == 'W' || $cell->getColumn() == 'X' || $cell->getColumn() == 'Y' || $cell->getColumn() == 'Z' || $cell->getColumn() == 'AA') {
            $cell->setValueExplicit($value, DataType::TYPE_STRING);

            return true;
        }

        // else return default behavior
        return parent::bindValue($cell, $value);
    }
}
