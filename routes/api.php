<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserauthController;
use App\Http\Controllers\Backend\Api\AdminauthController;
use App\Http\Controllers\Backend\Api\AccounttypeController;
use App\Http\Controllers\Backend\Api\AccountpackageController;
use App\Http\Controllers\Backend\Api\UserRolesController;
use App\Http\Controllers\Backend\Api\UserController;
use App\Http\Controllers\Backend\Api\BasicinfoController;
use App\Http\Controllers\Backend\Api\NewsupdateController;
use App\Http\Controllers\Backend\Api\AboutusController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Backend\Api\HelpcenterController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// user register api
Route::post('register', [UserauthController::class, 'userstore']);
Route::post('login', [UserauthController::class, 'userlogin']);
Route::post('logout', [UserauthController::class, 'userlogout']);
Route::get('gettypes', [AccounttypeController::class, 'gettype']);
Route::get('getpackages', [AccountpackageController::class, 'getpackage']);
Route::get('newsupdates', [NewsController::class,'getpubnews']);
Route::get('newsupdate/{slug}', [NewsController::class,'getpubnewsbyid']);

Route::get('aboutus', [AboutusController::class,'getaboutinfo']);

Route::group(['prefix'=>'user','middleware' => ['auth:sanctum']], function () {

    Route::get('/details/{id}', [UserauthController::class,'userdetails']);
    Route::get('newsupdates/{id}', [NewsController::class,'getnews']);
    Route::post('newsupdate/view/{slug}', [NewsController::class,'getnewsbyid']);
    // createuser
    Route::post('import', [UserauthController::class, 'userImport']);
    Route::post('add-by/{slug}', [UserauthController::class, 'usercreate']);

});

// admin login api
Route::post('admin/register', [AdminauthController::class, 'c']);
Route::post('admin/login', [AdminauthController::class, 'adminlogin']);
Route::post('admin/logout', [AdminauthController::class, 'adminlogout']);


Route::group(['prefix'=>'admin','middleware' => ['auth:sanctum']], function () {

    Route::get('/details/{id}', [AdminauthController::class,'admindetails']);
    Route::get('getadminroles', [AdminauthController::class,'getroles']);

    Route::resource('accounttypes', AccounttypeController::class,);
    Route::post('accounttype/update', [AccounttypeController::class, 'update']);

    Route::resource('accountpackages', AccountpackageController::class,);
    Route::post('accountpackage/update', [AccountpackageController::class, 'update']);

    Route::resource('userroles', UserRolesController::class);
    Route::post('userrole/update/{id}',[UserRolesController::class,'update'] );
    Route::get('getpermissions', [UserRolesController::class,'getpermissions']);

    // users
    Route::resource('users', UserController::class,);
    Route::post('user/update/{id}', [UserController::class,'update']);
    Route::get('getroles', [UserController::class,'getuserroles']);

    // basic infos
    Route::resource('basicinfos', BasicinfoController::class);
    Route::post('basicinfo/update', [BasicinfoController::class, 'update']);
    Route::post('pixel/analytics', [BasicinfoController::class, 'pixelanalytics']);
    Route::post('social/links', [BasicinfoController::class, 'sociallink']);
    Route::post('seo/meta', [BasicinfoController::class, 'seometa']);
    // news and updates
    Route::resource('newsupdates', NewsupdateController::class);
    Route::post('newsupdate/update/{id}', [NewsupdateController::class,'update']);

    Route::resource('aboutus', AboutusController::class);
    Route::post('aboutus/update', [AboutusController::class,'update']);

    Route::resource('helpcenters', HelpcenterController::class);
    Route::post('helpcenter/update', [HelpcenterController::class,'update']);


});