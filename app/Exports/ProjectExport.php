<?php

namespace App\Exports;

use App\Models\Project;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProjectExport implements FromQuery,WithHeadings,WithMapping
{


    use Exportable;
    private $user;
    private $user_id;

    public function __construct($user,$user_id)
    {
        $this->user = $user;
        $this->user_id = $user_id;
    }


    public function map($sale): array
    {
        return [
            $sale->created_at->format('Y-m-d'),
            $sale->membership_code,
            $sale->projectID,
            $sale->customer_name,
            $sale->project_title,
            $sale->description,
            $sale->budget,
            $sale->startDate,
            $sale->endDate,
            $sale->progress,
            $sale->priority,
            $sale->status,
        ];
    }

    public function query()
    {
        $user=$this->user;
        $user_id=$this->user_id;

        if($user_id->name=='user'){
            $user=$this->user;
            if(isset($user->membership_code)){
                return Project::where('membership_code',$user->membership_code);
            }else{
                return Project::where('membership_code',$user->member_by);
            }
        }else{
            return Project::whereIn('status',['0','1']);
        }

    }


    public function headings(): array
    {
        return ["Date","Membership Code", "Project ID", "Customer Name", "Title", "Description", "Price", "Start Date", "End Date" , "Progress","Priority","Status"];
    }



}
