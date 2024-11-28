<?php

use App\Exports\VoucherSalesExport;
use App\Http\Controllers\ChartController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppsController;
use App\Http\Controllers\FormController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UtilityController;
use App\Http\Controllers\WidgetsController;
use App\Http\Controllers\SetLocaleController;
use App\Http\Controllers\ComponentsController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\DatabaseBackupController;
use App\Http\Controllers\GeneralSettingController;
use App\Http\Controllers\PackageVoucherController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\RedeemController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\VoucherDetailController;
use App\Http\Controllers\VoucherHeaderController;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\EmailController;

require __DIR__ . '/auth.php';

Route::get('/', function () {
    return to_route('login');
});

Route::get('evoucher', [VoucherHeaderController::class, 'testVoucher'])->name('evoucher');

Route::group(['middleware' => ['auth', 'verified']], function () {

    Route::get('/send-email', [EmailController::class, 'redirectToAuthUrl']);
    Route::get('/redirect-to-auth-url', [EmailController::class, 'redirectToAuthUrl']);
    Route::get('/callback', [EmailController::class, 'handleCallback']);
    
    // Route::get('/evoucher', VoucherHeaderController::class, 'eVoucher');
    // Dashboards
    Route::get('dashboard', [HomeController::class, 'index'])->name('dashboard.index');
    // Locale
    Route::get('setlocale/{locale}', SetLocaleController::class)->name('setlocale');

    // User
    Route::resource('users', UserController::class);
    // Permission
    Route::resource('permissions', PermissionController::class)->except(['show']);
    // Roles
    Route::resource('roles', RoleController::class);
    // Profiles
    Route::resource('profiles', ProfileController::class)->only(['index', 'update'])->parameter('profiles', 'user');
    // Env
    Route::singleton('general-settings', GeneralSettingController::class);
    Route::post('general-settings-logo', [GeneralSettingController::class, 'logoUpdate'])->name('general-settings.logo');

    // Database Backup
    Route::resource('database-backups', DatabaseBackupController::class);
    Route::get('database-backups-download/{fileName}', [DatabaseBackupController::class, 'databaseBackupDownload'])->name('database-backups.download');


    // Package Voucher
    Route::resource('packages', PackageVoucherController::class);

    // Voucher Header
    Route::resource('vouchers', VoucherHeaderController::class);
    // Route::post('voucher-headers', [VoucherHeaderController::class, 'create'])->name('voucher-headers.create');
    Route::post('/vouchers/create', [VoucherHeaderController::class, 'create'])->name('vouchers.create');
    Route::post('/voucher-headers/validate', [VoucherHeaderController::class, 'validateVoucher'])->name('vouchers.validate');
    Route::post('/vouchers/confirm', [VoucherHeaderController::class, 'confirm'])->name('vouchers.confirm');
    Route::match(['get', 'post'], 'vouchers-filter', [VoucherHeaderController::class, 'index'])->name('vouchers-filter.index');

    Route::post('/vouchers/export-receipt', [VoucherHeaderController::class, 'exportReceiptPdf'])->name('vouchers.export-receipt');

    // Voucher Detail
    Route::resource('voucher-details', VoucherDetailController::class);

    // Vouchers
    // Route::resource('vouchers', VoucherController::class);
    // Route::post('/vouchers/create', [VoucherController::class, 'create'])->name('vouchers.create');
    // Route::post('/vouchers/validate', [VoucherController::class, 'validateVoucher'])->name('vouchers.validate');




    // Patient
    Route::resource('patient', PatientController::class);
    Route::post('search-patient', [PatientController::class, 'searchPatient'])->name('search.patient');

    // Redeem
    Route::resource('redeem', RedeemController::class);
    Route::post('/redeem/create', [RedeemController::class, 'create'])->name('redeem.create');

    Route::resource('reports', ReportController::class);

    Route::get('export-vouchers', function() {
        return Excel::download(new VoucherSalesExport, 'voucher_sales.xlsx');
    })->name('export.vouchers');

    Route::get('vouchers/assign/{paketVoucherId}', [VoucherController::class, 'assign'])->name('vouchers.assign');
});

