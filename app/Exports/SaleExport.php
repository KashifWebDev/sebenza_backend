<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SaleExport implements FromQuery
{

    use Exportable;

    public function query($startDate,$endDate)
    {
        return Sale::with(['saleitems'])->whereBetween('created_at', [$startDate, $endDate])->get();
    }


}
