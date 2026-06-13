<?php

namespace App\Exports;

use App\Models\AllowanceType;
use Maatwebsite\Excel\Concerns\FromCollection;

class AllowanceTypeExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return AllowanceType::all();
    }
}
