<?php

use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MstDealerController;
use App\Http\Controllers\MstDepartmentController;
use App\Http\Controllers\MstDropdownController;
use App\Http\Controllers\MstEmployeeController;
use App\Http\Controllers\MstPositionController;
use App\Http\Controllers\MstRuleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


//Route Login
Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('auth/login', [AuthController::class, 'postlogin'])->name('postlogin')->middleware("throttle:5,2");

//Route Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    //Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    //User
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::post('user/create', [UserController::class, 'store'])->name('user.store');
    Route::post('user/update/{id}', [UserController::class, 'update'])->name('user.update');
    Route::post('user/activate/{id}', [UserController::class, 'activate'])->name('user.activate');
    Route::post('user/deactivate/{id}', [UserController::class, 'deactivate'])->name('user.deactivate');
    Route::post('user/delete/{id}', [UserController::class, 'delete'])->name('user.delete');
    
    //Dropdown
    Route::get('/dropdown', [MstDropdownController::class, 'index'])->name('dropdown.index');
    Route::post('dropdown/create', [MstDropdownController::class, 'store'])->name('dropdown.store');
    Route::post('dropdown/update/{id}', [MstDropdownController::class, 'update'])->name('dropdown.update');
    Route::post('dropdown/activate/{id}', [MstDropdownController::class, 'activate'])->name('dropdown.activate');
    Route::post('dropdown/deactivate/{id}', [MstDropdownController::class, 'deactivate'])->name('dropdown.deactivate');
    
    //Rule
    Route::get('/rule', [MstRuleController::class, 'index'])->name('rule.index');
    Route::post('rule/create', [MstRuleController::class, 'store'])->name('rule.store');
    Route::post('rule/update/{id}', [MstRuleController::class, 'update'])->name('rule.update');
    Route::post('rule/activate/{id}', [MstRuleController::class, 'activate'])->name('rule.activate');
    Route::post('rule/deactivate/{id}', [MstRuleController::class, 'deactivate'])->name('rule.deactivate');
    
    //Department
    Route::get('/department', [MstDepartmentController::class, 'index'])->name('department.index');
    Route::post('department/create', [MstDepartmentController::class, 'store'])->name('department.store');
    Route::post('department/update/{id}', [MstDepartmentController::class, 'update'])->name('department.update');
    Route::post('department/activate/{id}', [MstDepartmentController::class, 'activate'])->name('department.activate');
    Route::post('department/deactivate/{id}', [MstDepartmentController::class, 'deactivate'])->name('department.deactivate');
    
    //Position
    Route::get('/position', [MstPositionController::class, 'index'])->name('position.index');
    Route::post('position/create', [MstPositionController::class, 'store'])->name('position.store');
    Route::post('position/update/{id}', [MstPositionController::class, 'update'])->name('position.update');
    Route::post('position/activate/{id}', [MstPositionController::class, 'activate'])->name('position.activate');
    Route::post('position/deactivate/{id}', [MstPositionController::class, 'deactivate'])->name('position.deactivate');

    //Dealer
    Route::get('/dealer', [MstDealerController::class, 'index'])->name('dealer.index');
    Route::post('dealer/create', [MstDealerController::class, 'store'])->name('dealer.store');
    Route::post('dealer/update/{id}', [MstDealerController::class, 'update'])->name('dealer.update');

    //Employee
    Route::get('/employee', [MstEmployeeController::class, 'index'])->name('employee.index');
    Route::post('employee/create', [MstEmployeeController::class, 'store'])->name('employee.store');
    Route::post('employee/update/{id}', [MstEmployeeController::class, 'update'])->name('employee.update');
  

    //Audit Log
    Route::get('/auditlog', [AuditLogController::class, 'index'])->name('auditlog');

    //Mapping Json
    Route::get('/json_position/{id}', [MstPositionController::class, 'json_position'])->name('json_position');
    Route::post('/check_email_employee', [MstEmployeeController::class, 'check_email'])->name('check_email_employee');
});