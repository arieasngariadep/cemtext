<?php

namespace App\Exports\FUO0;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\FUO0\ResultFUO0MultipleExport;
use App\Exports\FUO0\SyariahFUO0MultipleExport;

class FUO0MultipleExport implements WithMultipleSheets
{
    use Exportable;

    protected $userId;
    
    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];

        if(\Schema::hasTable('result_fuo0_3_'.$this->userId.'')){
            for ($no_file = 1; $no_file < 4; $no_file++) {
                $sheets[] = new ResultFUO0MultipleExport($no_file, $this->userId);
                $sheets[] = new SyariahFUO0MultipleExport($no_file, $this->userId);
            }
        }else{
            for ($no_file = 1; $no_file < 3; $no_file++) {
                $sheets[] = new ResultFUO0MultipleExport($no_file, $this->userId);
                $sheets[] = new SyariahFUO0MultipleExport($no_file, $this->userId);
            }
        }

        return $sheets;
    }
}
