<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserauthController;
use App\Http\Controllers\Api\AccountingController;
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
use App\Http\Controllers\Api\PaymentfrequencyController;
use App\Http\Controllers\Api\SalaryController;
use App\Http\Controllers\Api\WithdrewController;
use App\Http\Controllers\Api\PayPalController;
use App\Http\Controllers\Api\VattexController;
use App\Http\Controllers\Api\BankController;
use App\Http\Controllers\Api\EstimatequoteController;
use App\Http\Controllers\Api\TermscategoryController;
use App\Http\Controllers\Api\EstimatesettingController;
use App\Http\Controllers\Api\TermsconditionController;
use App\Http\Controllers\Api\AssetController;
use App\Http\Controllers\Api\MoneytransferController;
use App\Http\Controllers\Api\WarehouseController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\StockController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\FileController;
use App\Http\Controllers\Api\SaleController;

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

    //banks
    Route::resource('banks', BankController::class);
    Route::post('bank/update/{id}', [BankController::class, 'update']);
    Route::get('all/bank', [BankController::class, 'getCompanyUserBank']);
    //vat tax
    Route::resource('vattaxs', VattexController::class);
    Route::post('vattax/update', [VattexController::class, 'update']);

    // paypal payment
    Route::post('paypal-payment', [PayPalController::class,'payment'])->name('payment');
    Route::get('paypal-cancel', [PayPalController::class,'paymentCancel'])->name('cancel.payment');
    Route::post('paypal-success',[PayPalController::class, 'paymentSuccess'])->name('success.payment');

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

    // payment frequency
    Route::resource('paymentfrequencys', PaymentfrequencyController::class);
    Route::post('paymentfrequency/update/{id}', [PaymentfrequencyController::class,'update']);
    // salarys
    Route::resource('salaries', SalaryController::class);
    Route::post('salary/update/{id}', [SalaryController::class,'update']);
    Route::get('my/salary', [SalaryController::class,'getMySalary']);
    // payment reports
    Route::resource('withdrews', WithdrewController::class);
    Route::post('withdrew/update/{id}', [WithdrewController::class,'update']);
    Route::get('my/withdrew', [WithdrewController::class,'getMywithdrew']);

    // estimate & quote

    // termscategory
    Route::resource('termscategories', TermscategoryController::class);
    Route::post('termscategory/update/{id}', [TermscategoryController::class,'update']);
    //terms & condition
    Route::get('termscondition/getdata', [TermsconditionController::class,'getdata']);
    Route::resource('termsconditions', TermsconditionController::class);
    Route::post('termscondition/update/{id}', [TermsconditionController::class,'update']);
    //estimatesetting
    Route::resource('estimatesettings', EstimatesettingController::class);
    Route::post('estimatesetting/update/{id}', [EstimatesettingController::class,'update']);
    //estimatequote
    Route::resource('estimatequotes', EstimatequoteController::class);
    Route::post('estimatequote/update/{id}', [EstimatequoteController::class,'update']);

    // assets
    Route::resource('assets', AssetController::class);
    Route::post('asset/update/{id}', [AssetController::class,'update']);

    // moneytransfer
    Route::resource('moneytransfers', MoneytransferController::class);
    Route::post('moneytransfer/update/{id}', [MoneytransferController::class,'update']);

    // warehouses
    Route::resource('warehouses', WarehouseController::class);
    Route::post('warehouse/update/{id}', [WarehouseController::class,'update']);
    // products
    Route::resource('products', ProductController::class);
    Route::post('product/update/{id}', [ProductController::class,'update']);
    // stocks
    Route::resource('stocks', StockController::class);
    Route::post('stock/update/{id}', [StockController::class,'update']);
    Route::get('getproducts', [StockController::class, 'getproducts']);

    //project
    Route::resource('projects', ProjectController::class);
    Route::post('project/update/{id}', [ProjectController::class,'update']);
    //customer
    Route::resource('customers', CustomerController::class);
    Route::post('customer/update/{id}', [CustomerController::class,'update']);
    Route::get('get-customer', [CustomerController::class,'getcustomer']);
    //files
    Route::resource('files', FileController::class);
    Route::post('file/update/{id}', [FileController::class,'update']);
    //sales
    Route::resource('sales', SaleController::class);
    Route::post('sale/update/{id}', [SaleController::class,'update']);
    Route::get('sale/data', [SaleController::class,'saledata']);

    // all report api
    Route::post('get/metting-data', [AccountingController::class,'getmettings']);
    Route::post('get/task-data', [AccountingController::class,'gettasks']);



    // excel data report
        // sales
    Route::post('sale/export', [SaleController::class,'fileExport']);
    Route::get('sale/export/list', [SaleController::class,'saleslist']);
        //user
    Route::post('export', [UserauthController::class,'fileExport']);
    Route::get('export/list', [UserauthController::class,'userslist']);
        //user
    Route::post('product/export', [ProductController::class,'fileExport']);
    Route::get('product/export/list', [ProductController::class,'productlist']);
        //expense
    Route::post('expense/export', [ExpenseController::class,'fileExport']);
    Route::get('expense/export/list', [ExpenseController::class,'expenselist']);

        // all excel exporter
    Route::post('excel/export', [CustomerController::class,'fileExport']);

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
