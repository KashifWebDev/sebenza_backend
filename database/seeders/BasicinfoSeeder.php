<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Basicinfo;

class BasicinfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $basic = new Basicinfo();
        $basic->contact= '8801647368141';
        $basic->email= 'support@crm.com';
        $basic->address= 'House:22,Road:4,Block:D';
        $basic->logo= 'public/webview/assets/images/logo.png';
        $basic->save();
    }
}