<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Teammember;

class TeammemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $teammember=new Teammember();
        $teammember->name='Kasif Ali';
        $teammember->title='Software Developer';
        $teammember->image='public/test.jpg';
        $teammember->status='Active';
        $teammember->save();
    }
}
