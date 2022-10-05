<?php

use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\CommonController;
use App\Http\Controllers\Admin\HotelController;
use App\Http\Controllers\Admin\InquiryController;
use App\Http\Controllers\Admin\LandmarkController;
use App\Http\Controllers\Admin\OperatorController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

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

Auth::routes(['verify' => true]);

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    } else {
        return view('auth.login');
    }
})->name('index');
Route::get('/list/cities', [CommonController::class, 'allCities']);
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
Route::group(['middleware' => ['auth']], function () {
    Route::resources(['/landmarks' => LandmarkController::class]);
    Route::resources(['/operators' => OperatorController::class]);
    Route::resources(['/inquiries' => InquiryController::class]);
    Route::resources(['/cities' => CityController::class]);
    Route::resources(['/hotels' => HotelController::class]);

    Route::resources(['roles' => RoleController::class]);
    Route::resources(['permissions' => PermissionController::class]);
    Route::resources(['staffs' => UserController::class]);
    Route::get('/staff-profile/{id}', [UserController::class, 'edit'])->name('staff-profile');
    Route::get('/roles-permission-assignment-list', [UserController::class, 'userRolesPermissionList'])->name('roles-permission-assignment-list');
    Route::get('edit-with-role-permissions/{id}', [UserController::class, 'editUserRolesPermissions'])->name('edit-with-role-permissions');
    Route::post('assign-role-permissions/{id}', [UserController::class, 'updateUserRolesPermissions'])->name('assign-role-permissions');
});
