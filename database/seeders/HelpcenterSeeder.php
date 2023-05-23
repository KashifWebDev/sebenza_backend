<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Helpcenter;

class HelpcenterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $helpcenter=new Helpcenter();
        $helpcenter->title='Test News And Updates Title';
        $helpcenter->text='Test News And Updates all description';
        $helpcenter->image='public/test.jpg';
        $helpcenter->image_two='public/test.jpg';
        $helpcenter->save();
    }
}
