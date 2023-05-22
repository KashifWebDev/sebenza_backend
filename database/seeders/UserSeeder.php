<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $checkuser =User::where('email','user@gmail.com')->first();
        if(is_null($checkuser)){
            $user = new User();
            $user->first_name= 'User';
            $user->last_name= 'New';
            $user->membership_code= 'SEBENZA001';
            $user->email= 'user@gmail.com';
            $user->phone= '01647368141';
            $user->password= Hash::make('password');
            $user->save();
        }
    }
}