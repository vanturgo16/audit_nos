<?php

use App\Http\Controllers\AjaxMappingRegional;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MstAssessorChecklistController;
use App\Http\Controllers\MstAssignChecklistController;
use App\Http\Controllers\MstJaringanController;
use App\Http\Controllers\MstChecklistController;
use App\Http\Controllers\MstDepartmentController;
use App\Http\Controllers\MstDropdownController;
use App\Http\Controllers\MstEmployeeController;
use App\Http\Controllers\MstFormChecklistController;
use App\Http\Controllers\MstGradingController;
use App\Http\Controllers\MstMapChecklistController;
use App\Http\Controllers\MstPeriodChecklistController;
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
    Route::post('user/check_email_employee', [UserController::class, 'check_email'])->name('user.check_email_employee');
    
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

    //Jaringan
    Route::get('/jaringan', [MstJaringanController::class, 'index'])->name('jaringan.index');
    Route::post('jaringan/create', [MstJaringanController::class, 'store'])->name('jaringan.store');
    Route::post('jaringan/update/{id}', [MstJaringanController::class, 'update'])->name('jaringan.update');

    //Employee
    Route::get('/employee', [MstEmployeeController::class, 'index'])->name('employee.index');
    Route::post('employee/create', [MstEmployeeController::class, 'store'])->name('employee.store');
    Route::post('employee/update/{id}', [MstEmployeeController::class, 'update'])->name('employee.update');
    
    //Checklist
    Route::get('/checklist', [MstChecklistController::class, 'index'])->name('checklist.index');
    Route::post('checklist/create', [MstChecklistController::class, 'store'])->name('checklist.store');
    Route::post('checklist/update/{id}', [MstChecklistController::class, 'update'])->name('checklist.update');
    Route::get('/checklist/mark/{id}', [MstChecklistController::class, 'mark'])->name('checklist.mark');
    Route::post('checklist/createmark/{id}', [MstChecklistController::class, 'markstore'])->name('checklist.markstore');
    Route::get('checklist/deletemark/{id}', [MstChecklistController::class, 'markdelete'])->name('checklist.markdelete');

    //Checklist
    Route::get('/mapchecklist', [MstMapChecklistController::class, 'index'])->name('mapchecklist.index');
    Route::get('/mapchecklist/type/{type}', [MstMapChecklistController::class, 'type'])->name('mapchecklist.type');
    Route::post('/mapchecklist/add/{type}', [MstMapChecklistController::class, 'addtype'])->name('mapchecklist.addtype');
    Route::post('/mapchecklist/delete/{type}', [MstMapChecklistController::class, 'deletetype'])->name('mapchecklist.deletetype');
    Route::get('/mapchecklist/detail/{type}/{typecheck}', [MstMapChecklistController::class, 'detail'])->name('mapchecklist.detail');
    Route::post('/mapchecklist/deleteparent/{id}', [MstMapChecklistController::class, 'deleteparent'])->name('mapchecklist.deleteparent');
    Route::post('/mapchecklist/addparent/{type}', [MstMapChecklistController::class, 'addparent'])->name('mapchecklist.addparent');


    //Grading
    Route::get('/grading', [MstGradingController::class, 'index'])->name('grading.index');

    
    //Period Checklist
    Route::get('/periodchecklist', [MstPeriodChecklistController::class, 'index'])->name('periodchecklist.index');
    Route::post('periodchecklist/create', [MstPeriodChecklistController::class, 'store'])->name('periodchecklist.store');
    Route::post('periodchecklist/update/{id}', [MstPeriodChecklistController::class, 'update'])->name('periodchecklist.update');
    Route::post('periodchecklist/activate/{id}', [MstPeriodChecklistController::class, 'activate'])->name('periodchecklist.activate');
    Route::post('periodchecklist/deactivate/{id}', [MstPeriodChecklistController::class, 'deactivate'])->name('periodchecklist.deactivate');
    
    //Assign Checklist
    Route::get('/assignchecklist/{id}', [MstAssignChecklistController::class, 'index'])->name('assignchecklist.index');
    Route::post('assignchecklist/create/{id}', [MstAssignChecklistController::class, 'store'])->name('assignchecklist.store');
    Route::post('assignchecklist/delete/{id}', [MstAssignChecklistController::class, 'delete'])->name('assignchecklist.delete');
    Route::get('/searchchecklist/{id}', [MstAssignChecklistController::class, 'searchchecklist'])->name('searchchecklist');
    Route::post('/assignchecklist/submit/{id}', [MstAssignChecklistController::class, 'submit'])->name('assignchecklist.submit');
    Route::get('/assignchecklist/type/{id}/{type}', [MstAssignChecklistController::class, 'type'])->name('assignchecklist.type');

    //Form Checklist 
    Route::get('/form', [MstFormChecklistController::class, 'form'])->name('formchecklist.form');
    Route::get('/formchecklist', [MstFormChecklistController::class, 'index'])->name('formchecklist.index');
    Route::get('/formchecklist/periode/{id}', [MstFormChecklistController::class, 'periode_jaringan'])->name('formchecklist.periode');
    Route::get('/formchecklist/periode/typechecklist/{id}', [MstFormChecklistController::class, 'typechecklist'])->name('formchecklist.typechecklist');
    Route::get('/formchecklist/periode/typechecklist/start/{id}', [MstFormChecklistController::class, 'startchecklist'])->name('formchecklist.start');
    Route::get('/formchecklist/periode/typechecklist/checklistform/{id}', [MstFormChecklistController::class, 'checklistform'])->name('formchecklist.checklistform');
    Route::post('/formchecklist/periode/typechecklist/checklistform/store/{id}', [MstFormChecklistController::class, 'store'])->name('formchecklist.store');
    Route::post('/formchecklist/periode/typechecklist/submitchecklist/{id}', [MstFormChecklistController::class, 'submitchecklist'])->name('formchecklist.submitchecklist');

    
    
    // Checklist Assessor
    Route::get('/assessor/jaringan', [MstAssessorChecklistController::class, 'listjaringan'])->name('assessor.listjaringan');
    Route::get('assessor/period/{id}', [MstAssessorChecklistController::class, 'listperiod'])->name('assessor.listperiod');
    Route::get('assessor/typechecklist/{id}', [MstAssessorChecklistController::class, 'typechecklist'])->name('assessor.typechecklist');
    Route::get('assessor/review/{id}', [MstAssessorChecklistController::class, 'review'])->name('assessor.review');
    Route::post('assessor/submitreview/{id}', [MstAssessorChecklistController::class, 'submitreview'])->name('assessor.submitreview');
    Route::post('assessor/finishreview/{id}', [MstAssessorChecklistController::class, 'finishreview'])->name('assessor.finishreview');
    Route::get('assessor/history/{id}', [MstAssessorChecklistController::class, 'history'])->name('assessor.history');


    //Audit Log
    Route::get('/auditlog', [AuditLogController::class, 'index'])->name('auditlog');

    // API Regional
    Route::get('/area/ajax/mappingCity/{province_id}', [AjaxMappingRegional::class, 'selectCity'])->name('mappingCity');
    Route::get('/area/ajax/mappingDistrict/{city_id}', [AjaxMappingRegional::class, 'selectDistrict'])->name('mappingDistrict');
    Route::get('/area/ajax/mappingSubDistrict/{district_id}', [AjaxMappingRegional::class, 'selectSubDistrict'])->name('mappingSubDistrict');
    Route::get('/area/ajax/mappingPostalCode/{subdistrict_id}', [AjaxMappingRegional::class, 'selectPostalCode'])->name('mappingPostalCode');

    //Mapping Json
    Route::get('/json_position/{id}', [MstPositionController::class, 'json_position'])->name('json_position');
    Route::post('/check_email_employee', [MstEmployeeController::class, 'check_email'])->name('check_email_employee');
});