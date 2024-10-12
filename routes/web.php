<?php

use App\Http\Controllers\AjaxMappingRegional;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MstAssessorChecklistController;
use App\Http\Controllers\MstAssignChecklistController;
use App\Http\Controllers\MstJaringanController;
use App\Http\Controllers\MstParentChecklistController;
use App\Http\Controllers\MstChecklistController;
use App\Http\Controllers\MstDepartmentController;
use App\Http\Controllers\MstDropdownController;
use App\Http\Controllers\MstEmployeeController;
use App\Http\Controllers\MstFormChecklistController;
use App\Http\Controllers\MstGradingController;
use App\Http\Controllers\MstMapChecklistController;
use App\Http\Controllers\MstPeriodNameController;
use App\Http\Controllers\MstPeriodChecklistController;
use App\Http\Controllers\MstPositionController;
use App\Http\Controllers\MstRuleController;
use App\Http\Controllers\UserController;
use App\Models\MstPeriodName;
use Illuminate\Support\Facades\Route;


//Route Login
Route::get('/', [AuthController::class, 'login'])->name('login');
Route::post('auth/login', [AuthController::class, 'postlogin'])->name('postlogin')->middleware("throttle:5,2");

//Route Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    //Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/detailresult/{id}', [DashboardController::class, 'detailresult'])->name('dashboard.detailresult');

    //User
    Route::get('/user', [UserController::class, 'index'])->name('user.index')->middleware('role:Super Admin,Admin');
    Route::post('user/create', [UserController::class, 'store'])->name('user.store')->middleware('role:Super Admin,Admin');
    Route::post('user/update/{id}', [UserController::class, 'update'])->name('user.update')->middleware('role:Super Admin,Admin');
    Route::post('user/activate/{id}', [UserController::class, 'activate'])->name('user.activate')->middleware('role:Super Admin,Admin');
    Route::post('user/reset/{id}', [UserController::class, 'reset'])->name('user.reset')->middleware('role:Super Admin,Admin');
    Route::post('user/deactivate/{id}', [UserController::class, 'deactivate'])->name('user.deactivate')->middleware('role:Super Admin,Admin');
    Route::post('user/delete/{id}', [UserController::class, 'delete'])->name('user.delete')->middleware('role:Super Admin,Admin');
    Route::post('user/check_email_employee', [UserController::class, 'check_email'])->name('user.check_email_employee')->middleware('role:Super Admin,Admin');
    
    //Dropdown
    Route::get('/dropdown', [MstDropdownController::class, 'index'])->name('dropdown.index')->middleware('role:Super Admin,Admin,Assessor Main Dealer');
    Route::post('dropdown/create', [MstDropdownController::class, 'store'])->name('dropdown.store')->middleware('role:Super Admin,Admin,Assessor Main Dealer');
    Route::post('dropdown/update/{id}', [MstDropdownController::class, 'update'])->name('dropdown.update')->middleware('role:Super Admin,Admin,Assessor Main Dealer');
    Route::post('dropdown/activate/{id}', [MstDropdownController::class, 'activate'])->name('dropdown.activate')->middleware('role:Super Admin,Admin,Assessor Main Dealer');
    Route::post('dropdown/deactivate/{id}', [MstDropdownController::class, 'deactivate'])->name('dropdown.deactivate')->middleware('role:Super Admin,Admin,Assessor Main Dealer');
    
    //Rule
    Route::get('/rule', [MstRuleController::class, 'index'])->name('rule.index')->middleware('role:Super Admin');
    Route::post('rule/create', [MstRuleController::class, 'store'])->name('rule.store')->middleware('role:Super Admin');
    Route::post('rule/update/{id}', [MstRuleController::class, 'update'])->name('rule.update')->middleware('role:Super Admin');
    Route::post('rule/activate/{id}', [MstRuleController::class, 'activate'])->name('rule.activate')->middleware('role:Super Admin');
    Route::post('rule/deactivate/{id}', [MstRuleController::class, 'deactivate'])->name('rule.deactivate')->middleware('role:Super Admin');
    
    //Department
    Route::get('/department', [MstDepartmentController::class, 'index'])->name('department.index')->middleware('role:Super Admin,Admin');
    Route::post('department/create', [MstDepartmentController::class, 'store'])->name('department.store')->middleware('role:Super Admin,Admin');
    Route::post('department/update/{id}', [MstDepartmentController::class, 'update'])->name('department.update')->middleware('role:Super Admin,Admin');
    Route::post('department/activate/{id}', [MstDepartmentController::class, 'activate'])->name('department.activate')->middleware('role:Super Admin,Admin');
    Route::post('department/deactivate/{id}', [MstDepartmentController::class, 'deactivate'])->name('department.deactivate')->middleware('role:Super Admin,Admin');
    
    //Position
    Route::get('/position', [MstPositionController::class, 'index'])->name('position.index')->middleware('role:Super Admin,Admin');
    Route::post('position/create', [MstPositionController::class, 'store'])->name('position.store')->middleware('role:Super Admin,Admin');
    Route::post('position/update/{id}', [MstPositionController::class, 'update'])->name('position.update')->middleware('role:Super Admin,Admin');
    Route::post('position/activate/{id}', [MstPositionController::class, 'activate'])->name('position.activate')->middleware('role:Super Admin,Admin');
    Route::post('position/deactivate/{id}', [MstPositionController::class, 'deactivate'])->name('position.deactivate')->middleware('role:Super Admin,Admin');

    //Jaringan
    Route::get('/jaringan', [MstJaringanController::class, 'index'])->name('jaringan.index')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC Dealers');
    Route::post('jaringan/create', [MstJaringanController::class, 'store'])->name('jaringan.store')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC Dealers');
    Route::post('jaringan/update/{id}', [MstJaringanController::class, 'update'])->name('jaringan.update')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC Dealers');

    //Employee
    Route::get('/employee', [MstEmployeeController::class, 'index'])->name('employee.index')->middleware('role:Super Admin,Admin');
    Route::post('employee/create', [MstEmployeeController::class, 'store'])->name('employee.store')->middleware('role:Super Admin,Admin');
    Route::post('employee/update/{id}', [MstEmployeeController::class, 'update'])->name('employee.update')->middleware('role:Super Admin,Admin');

    //Parent Checklist
    Route::get('/parentchecklist', [MstParentChecklistController::class, 'typechecklist'])->name('parentchecklist.typechecklist')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC Dealers');
    Route::get('/parentchecklist/{type}', [MstParentChecklistController::class, 'index'])->name('parentchecklist.index')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC Dealers');
    Route::post('parentchecklist/create', [MstParentChecklistController::class, 'store'])->name('parentchecklist.store')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC Dealers');
    Route::get('/parentchecklist/info/{id}', [MstParentChecklistController::class, 'info'])->name('parentchecklist.info')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC Dealers');
    Route::get('/parentchecklist/edit/{id}', [MstParentChecklistController::class, 'edit'])->name('parentchecklist.edit')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC Dealers');
    Route::post('parentchecklist/update/{id}', [MstParentChecklistController::class, 'update'])->name('parentchecklist.update')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC Dealers');
    Route::get('/parentchecklist/order-no/{type_checklist}', [MstParentChecklistController::class, 'mappingOrderNo'])->name('mappingOrderNo')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC Dealers'); //untuk ajax orderno

    //Checklist
    Route::get('/checklist', [MstChecklistController::class, 'typechecklist'])->name('checklist.typechecklist')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC Dealers');
    Route::get('/checklist/{type}', [MstChecklistController::class, 'index'])->name('checklist.index')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC Dealers');
    Route::get('/checklist/mappingparent/{name}', [MstChecklistController::class, 'mappingparent'])->name('mappingParent')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC Dealers');
    Route::get('/checklist/info/{id}', [MstChecklistController::class, 'info'])->name('checklist.info')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC Dealers');
    Route::get('/checklist/edit/{id}', [MstChecklistController::class, 'edit'])->name('checklist.edit')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC Dealers');
    Route::post('checklist/create', [MstChecklistController::class, 'store'])->name('checklist.store')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC Dealers');
    Route::post('checklist/update/{id}', [MstChecklistController::class, 'update'])->name('checklist.update')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC Dealers');
    Route::get('/checklist/mark/{id}', [MstChecklistController::class, 'mark'])->name('checklist.mark')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC Dealers');
    Route::post('checklist/createmark/{id}', [MstChecklistController::class, 'markstore'])->name('checklist.markstore')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC Dealers');
    Route::post('checklist/deletemark/{id}', [MstChecklistController::class, 'markdelete'])->name('checklist.markdelete')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC Dealers');
    Route::get('checklist/exc-order/{id}', [MstChecklistController::class, 'exchangeOrder'])->name('checklist.exchange')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC Dealers');
    Route::post('checklist/exc-order/update/{id}', [MstChecklistController::class, 'exchangeOrderUpdate'])->name('checklist.exc_order.update')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC Dealers');
    Route::get('/checklist/order-no/{parentPoint}/{typeChecklist}', [MstChecklistController::class, 'mappingOrderNo'])->name('mappingOrderNoChecklist')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC Dealers'); //untuk ajax orderno

    //Mapping Checklist
    Route::get('/mapchecklist', [MstMapChecklistController::class, 'index'])->name('mapchecklist.index')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC Dealers');
    Route::get('/mapchecklist/type/{type}', [MstMapChecklistController::class, 'type'])->name('mapchecklist.type')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC Dealers');
    Route::post('/mapchecklist/add/{type}', [MstMapChecklistController::class, 'addtype'])->name('mapchecklist.addtype')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC Dealers');
    Route::post('/mapchecklist/delete/{type}', [MstMapChecklistController::class, 'deletetype'])->name('mapchecklist.deletetype')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC Dealers');
    Route::get('/mapchecklist/detail/{type}/{typecheck}', [MstMapChecklistController::class, 'detail'])->name('mapchecklist.detail')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC Dealers');
    Route::post('/mapchecklist/deleteparent/{id}', [MstMapChecklistController::class, 'deleteparent'])->name('mapchecklist.deleteparent')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC Dealers');
    Route::post('/mapchecklist/addparent/{type}', [MstMapChecklistController::class, 'addparent'])->name('mapchecklist.addparent')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC Dealers');

    //Grading
    Route::get('/grading', [MstGradingController::class, 'index'])->name('grading.index')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC Dealers');
    
    //Naming Period
    Route::get('/periodname', [MstPeriodNameController::class, 'index'])->name('periodname.index')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC Dealers');
    Route::post('periodname/create', [MstPeriodNameController::class, 'store'])->name('periodname.store')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC Dealers');
    Route::post('periodname/update/{id}', [MstPeriodNameController::class, 'update'])->name('periodname.update')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC Dealers');
    Route::post('periodname/activate/{id}', [MstPeriodNameController::class, 'activate'])->name('periodname.activate')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC Dealers');
    Route::post('periodname/deactivate/{id}', [MstPeriodNameController::class, 'deactivate'])->name('periodname.deactivate')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC Dealers');

    //Period Checklist
    Route::get('/periodchecklist', [MstPeriodChecklistController::class, 'index'])->name('periodchecklist.index')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC Dealers');
    Route::post('periodchecklist/create', [MstPeriodChecklistController::class, 'store'])->name('periodchecklist.store')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC Dealers');
    Route::post('periodchecklist/update/{id}', [MstPeriodChecklistController::class, 'update'])->name('periodchecklist.update')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC Dealers');
    Route::post('periodchecklist/updateexpired/{id}', [MstPeriodChecklistController::class, 'updateexpired'])->name('periodchecklist.updateexpired')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC Dealers');
    Route::post('periodchecklist/activate/{id}', [MstPeriodChecklistController::class, 'activate'])->name('periodchecklist.activate')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC Dealers');
    Route::post('periodchecklist/deactivate/{id}', [MstPeriodChecklistController::class, 'deactivate'])->name('periodchecklist.deactivate')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC Dealers');
    
    //Assign Checklist
    Route::get('/assignchecklist/{id}', [MstAssignChecklistController::class, 'index'])->name('assignchecklist.index')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC Dealers');
    Route::post('assignchecklist/create/{id}', [MstAssignChecklistController::class, 'store'])->name('assignchecklist.store')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC Dealers');
    Route::post('assignchecklist/delete/{id}', [MstAssignChecklistController::class, 'delete'])->name('assignchecklist.delete')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC Dealers');
    Route::get('/searchchecklist/{id}', [MstAssignChecklistController::class, 'searchchecklist'])->name('searchchecklist')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC Dealers');
    Route::post('/assignchecklist/submit/{id}', [MstAssignChecklistController::class, 'submit'])->name('assignchecklist.submit')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC Dealers');
    Route::get('/assignchecklist/type/{id}/{type}', [MstAssignChecklistController::class, 'type'])->name('assignchecklist.type')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC Dealers');

    //Form Checklist 
    Route::post('/checklist/save', [MstFormChecklistController::class, 'saveResponses'])->name('checklist.saveResponses');

    Route::get('/form', [MstFormChecklistController::class, 'form'])->name('formchecklist.form')->middleware('role:Super Admin,Admin');
    // Route::get('/formchecklist', [MstFormChecklistController::class, 'index'])->name('formchecklist.index')->middleware('role:Super Admin,Admin,Internal Auditor Dealer');
    Route::get('/formchecklist/auditor', [MstFormChecklistController::class, 'auditor'])->name('formchecklist.auditor')->middleware('role:Super Admin,Admin,Internal Auditor Dealer');
    // Route::get('/formchecklist/periode/{id}', [MstFormChecklistController::class, 'periode_jaringan'])->name('formchecklist.periode')->middleware('role:Super Admin,Admin,Internal Auditor Dealer');
    // Route::get('/formchecklist/periode/typechecklist/{id}', [MstFormChecklistController::class, 'typechecklist'])->name('formchecklist.typechecklist')->middleware('role:Super Admin,Admin,Internal Auditor Dealer');
    // Route::post('/formchecklist/periode/typechecklist/start/{id}', [MstFormChecklistController::class, 'startchecklist'])->name('formchecklist.start')->middleware('role:Super Admin,Admin,Internal Auditor Dealer');
    // Route::get('/formchecklist/periode/typechecklist/checklistform/{id}', [MstFormChecklistController::class, 'checklistform'])->name('formchecklist.checklistform')->middleware('role:Super Admin,Admin,Internal Auditor Dealer');
    Route::post('/formchecklist/periode/typechecklist/checklistform/store/{id}', [MstFormChecklistController::class, 'store'])->name('formchecklist.store')->middleware('role:Super Admin,Admin,Internal Auditor Dealer');
    Route::post('/formchecklist/periode/typechecklist/submitchecklist/{id}', [MstFormChecklistController::class, 'submitchecklist'])->name('formchecklist.submitchecklist')->middleware('role:Super Admin,Admin,Internal Auditor Dealer');
    Route::get('/checklistform/detail/{id}', [MstAssessorChecklistController::class, 'review'])->name('checklistform.detail');

    Route::controller(MstFormChecklistController::class)->group(function () {
        Route::prefix('form')->group(function () {
            Route::get('/', 'form')->name('formchecklist.form');
            Route::get('/jaringan-list', 'jaringanList')->name('formchecklist.jaringanList');
            Route::get('/period-list/{id}', 'periodList')->name('formchecklist.periodList');
            Route::get('/type-checklist-list/{id}', 'typeChecklistList')->name('formchecklist.typeChecklistList');
            Route::post('/start-checklist/{id}', 'startChecklist')->name('formchecklist.start');
            Route::get('/checklist/{id}', 'checklistForm')->name('formchecklist.checklistform');

            Route::get('/get-checklist/{id}', 'getChecklistForm')->name('formchecklist.getChecklistForm');
            Route::post('/store-checklist-file', 'storeChecklistFile')->name('formchecklist.storeChecklistFile');
            Route::post('/finish-checklist', 'finishChecklist')->name('formchecklist.finishChecklist');
            
            Route::get('/get-checklist-h1p/{id}', 'getChecklistFormH1P')->name('formchecklist.getChecklistFormH1P');
            Route::post('/store-checklist-file-h1p', 'storeChecklistFileH1P')->name('formchecklist.storeChecklistFileH1P');
            Route::post('/finish-checklist-h1p', 'finishChecklistH1P')->name('formchecklist.finishChecklistH1P');
        });
    })->middleware('role:Super Admin,Admin,Internal Auditor Dealer');
    
    // Checklist Assessor
    Route::get('/assessor/jaringan', [MstAssessorChecklistController::class, 'listjaringan'])->name('assessor.listjaringan')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC NOS MD');
    Route::get('assessor/period/{id}', [MstAssessorChecklistController::class, 'listperiod'])->name('assessor.listperiod')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC NOS MD');
    Route::get('assessor/typechecklist/{id}', [MstAssessorChecklistController::class, 'typechecklist'])->name('assessor.typechecklist')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC NOS MD');
    Route::get('assessor/review/{id}', [MstAssessorChecklistController::class, 'review'])->name('assessor.review')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC NOS MD,Admin,Internal Auditor Dealer');
    Route::post('assessor/submitreview/{id}', [MstAssessorChecklistController::class, 'submitreview'])->name('assessor.submitreview')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC NOS MD');
    Route::post('assessor/finishreview/{id}', [MstAssessorChecklistController::class, 'finishreview'])->name('assessor.finishreview')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC NOS MD');
    Route::post('assessor/closedapproved/{id}', [MstAssessorChecklistController::class, 'closedapproved'])->name('assessor.closedapproved')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC NOS MD');
    Route::get('assessor/history/{id}', [MstAssessorChecklistController::class, 'history'])->name('assessor.history')->middleware('role:Super Admin,Admin,Assessor Main Dealer,PIC NOS MD');

    //Audit Log
    Route::get('/auditlog', [AuditLogController::class, 'index'])->name('auditlog')->middleware('role:Super Admin,Admin');

    // API Regional
    Route::get('/area/ajax/mappingCity/{province_id}', [AjaxMappingRegional::class, 'selectCity'])->name('mappingCity');
    Route::get('/area/ajax/mappingDistrict/{city_id}', [AjaxMappingRegional::class, 'selectDistrict'])->name('mappingDistrict');
    Route::get('/area/ajax/mappingSubDistrict/{district_id}', [AjaxMappingRegional::class, 'selectSubDistrict'])->name('mappingSubDistrict');
    Route::get('/area/ajax/mappingPostalCode/{subdistrict_id}', [AjaxMappingRegional::class, 'selectPostalCode'])->name('mappingPostalCode');

    //Mapping Json
    Route::get('/mapping/dealer/{id}', [DashboardController::class, 'mappingdealer'])->name('mapping.dealer');
    Route::get('/json_position/{id}', [MstPositionController::class, 'json_position'])->name('json_position');
    Route::post('/check_email_employee', [MstEmployeeController::class, 'check_email'])->name('check_email_employee');
});