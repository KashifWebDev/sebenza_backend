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
use App\Http\Controllers\Backend\Api\TeammemberController;
use App\Http\Controllers\Backend\Api\TicketController;
use App\Http\Controllers\Backend\Api\WhatsappController;
use App\Http\Controllers\Backend\Api\PromocodeController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\MetingController;
use App\Http\Controllers\Api\CalenderController;
use App\Http\Controllers\Api\ExpenseController;
use App\Http\Controllers\Api\ExpensetypeController;

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

Route::post('update/subuser/{slug}', [UserauthController::class, 'updatesubuser']);

Route::get('aboutus', [AboutusController::class,'getaboutinfo']);
Route::get('helpcenter', [HelpcenterController::class,'gethelpcenterinfo']);
Route::get('teammembers', [TeammemberController::class,'getteammembersinfo']);
Route::get('whatsapps',[WhatsappController::class,'getwhatsappinfo']);


Route::group(['prefix'=>'user','middleware' => ['auth:sanctum']], function () {
    Route::get('get-list', [UserController::class,'index']);

    Route::get('/view-profile', [UserauthController::class,'userprofile']);
    Route::post('/update-profile', [UserauthController::class,'userprofileupdate']);
    Route::get('/details/{id}', [UserauthController::class,'userdetails']);
    Route::get('newsupdates/{id}', [NewsController::class,'getnews']);
    Route::post('newsupdate/view/{slug}', [NewsController::class,'getnewsbyid']);
    // createuser
    Route::post('import', [UserauthController::class, 'userImport']);
    Route::post('add-by', [UserauthController::class, 'usercreate']);
    // supporttikits
    Route::resource('supporttickets', TicketController::class);
    Route::post('replay/ticket/{id}', [TicketController::class, 'replay']);
    // tasks
    Route::resource('tasks', TaskController::class);
    Route::post('task/update/{id}', [TaskController::class,'update']);
    Route::post('task/{id}/addnote', [TaskController::class,'tasknote']);
    // meting
    Route::resource('metings', MetingController::class);
    Route::post('meting/update/{id}', [MetingController::class,'update']);
    Route::post('meting/{id}/addnote', [MetingController::class,'metingnote']);
    // calender
    Route::resource('calenders', CalenderController::class);
    Route::post('calender/update/{id}', [CalenderController::class,'update']);
    // expence type
    Route::resource('expensetypes', ExpensetypeController::class);
    Route::post('expensetype/update/{id}', [ExpensetypeController::class,'update']);
    // expece
    Route::get('getexpensetype', [ExpensetypeController::class,'getexpencetype']);
    Route::resource('expenses', ExpenseController::class);
    Route::post('expense/update/{id}', [ExpenseController::class,'update']);

    // orders & invoices
    Route::resource('orders', App\Http\Controllers\Api\OrderController::class);
    Route::post('order/update/{id}', [App\Http\Controllers\Api\OrderController::class,'update']);
    Route::resource('invoices', App\Http\Controllers\Api\InvoiceController::class);
    Route::post('order/update-payment', [App\Http\Controllers\Api\InvoiceController::class,'updatepayment']);
    Route::post('order/use-promo', [App\Http\Controllers\Api\OrderController::class,'usepromo']);

});

// admin login api
Route::post('admin/register', [AdminauthController::class, 'adminstore']);
Route::post('admin/login', [AdminauthController::class, 'adminlogin']);
Route::post('admin/logout', [AdminauthController::class, 'adminlogout']);


Route::group(['prefix'=>'admin','middleware' => ['auth:sanctum']], function () {

    Route::get('/view-profile', [AdminauthController::class,'adminprofile']);
    Route::post('/update-profile', [AdminauthController::class,'adminprofileupdate']);
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

    // About us
    Route::resource('aboutus', AboutusController::class);
    Route::post('aboutus/update', [AboutusController::class,'update']);
    // Helpcenter
    Route::resource('helpcenters', HelpcenterController::class);
    Route::post('helpcenter/update', [HelpcenterController::class,'update']);
    // teammember
    Route::resource('teammembers', TeammemberController::class);
    Route::post('teammember/update/{id}', [TeammemberController::class,'update']);
    // whatsapp
    Route::resource('whatsapps', WhatsappController::class);
    Route::post('whatsapp/update/{id}', [WhatsappController::class,'update']);

    // supportticket
    Route::get('supporttickets', [TicketController::class, 'admindex']);
    Route::get('supportticket/edit/{id}', [TicketController::class, 'edit']);
    Route::post('supportticket/update/{id}', [TicketController::class, 'update']);
    Route::post('replay/ticket/{id}', [TicketController::class, 'replay']);
    // promocodes
    Route::resource('promocodes', PromocodeController::class);
    Route::post('promocode/update/{id}', [PromocodeController::class,'update']);

    // orders & invoices
    Route::resource('orders', App\Http\Controllers\Backend\Api\OrderController::class);
    Route::post('order/update/{id}', [App\Http\Controllers\Backend\Api\OrderController::class,'update']);
    Route::resource('invoices', App\Http\Controllers\Backend\Api\InvoiceController::class);
    Route::post('invoice/update/{id}', [App\Http\Controllers\Backend\Api\OrderController::class,'update']);




});
