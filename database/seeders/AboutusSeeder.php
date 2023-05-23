<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Aboutus;

class AboutusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $aboutus=new Aboutus();
        $aboutus->title='Test News And Updates Title';
        $aboutus->text='Test News And Updates all description';
        $aboutus->image='public/test.jpg';
        $aboutus->banner_image='public/test.jpg';
        $aboutus->short_description='Test News And Updates all description';
        $aboutus->short_title='Test News And Updates all description';
        $aboutus->m_title='Test News';
        $aboutus->title_one='Test News';
        $aboutus->title_two='Test News';
        $aboutus->title_three='Test News';
        $aboutus->title_four='Test News';
        $aboutus->save();
    }
}

