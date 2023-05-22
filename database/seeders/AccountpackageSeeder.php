<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Accountpackage;

class AccountpackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $basic = new Accountpackage();
        $basic->account_package= '2 to 4 Person';
        $basic->max_user= 4;
        $basic->status= 'Active';
        $basic->save();
    }
}
