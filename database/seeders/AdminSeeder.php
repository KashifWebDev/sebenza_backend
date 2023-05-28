<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $checkadmin =Admin::where('email','md.muraiem@gmail.com')->first();
        if(is_null($checkadmin)){
            $user = new Admin();
            $user->first_name= 'Sebenza';
            $user->last_name= 'System';
            $user->phone= '+32i4298234';
            $user->email= 'sebenza@gmail.com';
            $user->status= 'Active';
            $user->password= Hash::make('password');
            $user->assignRole(1);
            $user->save();
        }
    }
}