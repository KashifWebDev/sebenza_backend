<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Whatsapp;
class WhatsappSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $helpcenter=new Whatsapp();
        $helpcenter->user_name='Kasif Ali';
        $helpcenter->whatsapp_number='8801647368141';
        $helpcenter->status='Active';
        $helpcenter->save();
    }
}