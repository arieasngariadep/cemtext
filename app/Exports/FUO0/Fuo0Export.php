<?php

namespace App\Exports\FUO0;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\FUO0\ResultFuo0Export;
use App\Exports\FUO0\SyariahFuo0Export;

class Fuo0Export implements WithMultipleSheets
{
    function __construct($userId)
    {
        $this->userId = $userId;
    }

    public function sheets(): array
    {
        $sheets = [];
        $sheets[] = new ResultFuo0Export($this->userId);
        $sheets[] = new SyariahFuo0Export($this->userId);
        return $sheets;
    }
}
