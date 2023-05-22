<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Newsupdate;

class NewsupdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $news=new Newsupdate();
        $news->title='Test News And Updates Title';
        $news->news='Test News And Updates all description';
        $news->postImage='public/test.jpg';
        $news->slug='test-news-slug';
        $news->slug='Active';
        $news->save();
    }
}
