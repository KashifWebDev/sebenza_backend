<?php

namespace App\Exports;

use App\Models\Expense;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExpenseExport implements FromQuery,WithHeadings,WithMapping
{


    use Exportable;
    private $startDate;
    private $endDate;
    private $user;

    public function __construct($startDate,$endDate,$user)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->user = $user;
    }


    public function map($expense): array
    {
        return [
            $expense->created_at->format('Y-m-d'),
            $expense->membership_code,
            $expense->expensetypes->expence_type,
            $expense->amount,
            $expense->notes,
        ];
    }

    public function query()
    {
        $startDate=$this->startDate;
        $endDate=$this->endDate;
        $user=$this->user;
        if(isset($user->membership_code)){
            return Expense::with('expensetypes')->where('membership_code',$user->membership_code)->whereBetween('created_at', [$startDate, $endDate]);
        }else{
            return Expense::with('expensetypes')->where('membership_code',$user->member_by)->whereBetween('created_at', [$startDate, $endDate]);
        }
    }


    public function headings(): array
    {
        return ["Date","Membership Code", "Expense Type", "Amount", "Message"];
    }



}
