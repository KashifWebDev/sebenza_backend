<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\RolesController;
use App\Http\Controllers\Backend\AdminController;
use App\Http\Controllers\Backend\UserController;
use App\Http\Controllers\Backend\UserRolesController;
use App\Http\Controllers\Backend\BasicinfoController;
use App\Http\Controllers\Backend\Auth\AuthenticatedSessionController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/admin/dashboard', function () {
    return view('backend.content.maincontent');
})->middleware(['auth.admin:admin'])->name('admin.dashboard');

Route::group(['prefix'=>'admin',], function () {
    // login
    Route::get('login', [AuthenticatedSessionController::class,'create'])->name('admin.loginview');
    Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('admin.login');

    // logout
    Route::get('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('admin.logout');
    // reset password

});

Route::group(['prefix'=>'admin','middleware' => ['auth.admin:admin']], function () {
    // role & permission
    Route::resource('roles', RolesController::class,['names'=>'admin.roles']);
    Route::resource('userroles', UserRolesController::class,['names'=>'admin.userroles']);
    Route::resource('admins', AdminController::class,['names'=>'admin.admins']);
    Route::resource('users', UserController::class,['names'=>'admin.users']);
    // basic info
    Route::resource('basicinfos', BasicinfoController::class,['names'=>'admin.basicinfos']);
    Route::post('/pixel/analytics/{id}', [BasicinfoController::class, 'pixelanalytics']);
    Route::post('/basicinfo/update/{id}', [BasicinfoController::class, 'sociallink']);


});