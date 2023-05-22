<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Accounttype;

class AccounttypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $basic = new Accounttype();
        $basic->account_type= 'General Account';
        $basic->status= 'Active';
        $basic->save();
    }
}
