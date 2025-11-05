<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AjaxMappingRegional;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\AuditorController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CaptchaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\FormChecklistController;
use App\Http\Controllers\ListAssignedChecklistController;
use App\Http\Controllers\MstAssignChecklistController;
use App\Http\Controllers\MstJaringanController;
use App\Http\Controllers\MstParentChecklistController;
use App\Http\Controllers\MstChecklistController;
use App\Http\Controllers\MstDepartmentController;
use App\Http\Controllers\MstDropdownController;
use App\Http\Controllers\MstEmployeeController;
use App\Http\Controllers\MstGradingController;
use App\Http\Controllers\MstMapChecklistController;
use App\Http\Controllers\MstPeriodNameController;
use App\Http\Controllers\MstPeriodChecklistController;
use App\Http\Controllers\MstPositionController;
use App\Http\Controllers\MstRuleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewChecklistController;
use App\Http\Controllers\SchedulerController;
use App\Http\Controllers\UserController;


// LOGIN
Route::get('/', [AuthController::class, 'login'])->name('login');
Route::get('/captcha/generate', [CaptchaController::class, 'generate'])->name('captcha.generate');
Route::post('auth/login', [AuthController::class, 'postlogin'])->name('postlogin')->middleware("throttle:5,2");

Route::get('/verify-2fa', [AuthController::class, 'show2fa'])->name('verify.2fa');
Route::post('/verify-2fa', [AuthController::class, 'verify2fa'])->name('verify.2fa.post');
Route::post('/resend-2fa', [AuthController::class, 'resend2fa'])->name('resend.2fa');

// LOGOUT
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    // DASHBOARD
    Route::controller(DashboardController::class)->group(function () {
        Route::prefix('dashboard')->group(function () {
            Route::get('/', 'index')->name('dashboard');
            Route::get('/mapping/dealer/{id}', 'mappingdealer')->name('mapping.dealer');
            Route::get('/detailresult/{id}', 'detailresult')->name('dashboard.detailresult');
            Route::post('/', 'index')->name('dashboard');
            Route::post('/switch-theme', 'switchTheme')->name('switchTheme');
        });
    });

    // PROFIL
    Route::controller(ProfileController::class)->group(function () {
        Route::prefix('profile')->group(function () {
            Route::get('/', 'index')->name('profile.index');
            Route::post('/update-photo', 'updatePhoto')->name('profile.updatePhoto');
        });
    });

    // MANAGE CONFIGURATION
    Route::middleware('role:Super Admin,Admin')->group(function () {
        // User
        Route::controller(UserController::class)->group(function () {
            Route::prefix('user')->group(function () {
                Route::get('/', 'index')->name('user.index');
                Route::post('/create', 'store')->name('user.store');
                Route::post('/update/{id}', 'update')->name('user.update');
                Route::post('/enable2fa/{id}', 'enable2fa')->name('user.enable2fa');
                Route::post('/disable2fa/{id}', 'disable2fa')->name('user.disable2fa');
                Route::post('/reset/{id}', 'reset')->name('user.reset');
                Route::post('/activate/{id}', 'activate')->name('user.activate');
                Route::post('/deactivate/{id}', 'deactivate')->name('user.deactivate');
                Route::post('/delete/{id}', 'delete')->name('user.delete');
                Route::post('/check_email_employee', 'check_email')->name('user.check_email_employee');
            });
        });
        // Dropdown
        Route::controller(MstDropdownController::class)->group(function () {
            Route::prefix('dropdown')->group(function () {
                Route::get('/', 'index')->name('dropdown.index');
                Route::post('/create', 'store')->name('dropdown.store');
                Route::post('/update/{id}', 'update')->name('dropdown.update');
                Route::post('/activate/{id}', 'activate')->name('dropdown.activate');
                Route::post('/deactivate/{id}', 'deactivate')->name('dropdown.deactivate');
            });
        });
        // Rule
        Route::controller(MstRuleController::class)->group(function () {
            Route::prefix('rule')->group(function () {
                Route::get('/', 'index')->name('rule.index');
                Route::post('/create', 'store')->name('rule.store');
                Route::post('/update/{id}', 'update')->name('rule.update');
                Route::post('/activate/{id}', 'activate')->name('rule.activate');
                Route::post('/deactivate/{id}', 'deactivate')->name('rule.deactivate');
            });
        });
        // Scheduler
        Route::controller(SchedulerController::class)->group(function () {
            Route::prefix('scheduler')->group(function () {
                Route::get('/', 'index')->name('scheduler.index');
                Route::get('/expired-period', 'expiredPeriod')->name('scheduler.expiredPeriod');
                Route::get('/submit-period', 'reminderSubmitPeriod')->name('scheduler.reminderSubmitPeriod');
            });
        });
        // Grading
        Route::controller(MstGradingController::class)->group(function () {
            Route::prefix('grading')->group(function () {
                Route::get('/', 'index')->name('grading.index');
            });
        });
    });

    // MASTER DATA ENTITY COMPANY
    Route::middleware('role:Super Admin,Admin')->group(function () {
        // Jaringan
        Route::controller(MstJaringanController::class)->group(function () {
            Route::prefix('jaringan')->group(function () {
                Route::get('/', 'index')->name('jaringan.index');
                Route::post('/create', 'store')->name('jaringan.store');
                Route::post('/update/{id}', 'update')->name('jaringan.update');
            });
        });
        // Employee
        Route::controller(MstEmployeeController::class)->group(function () {
            Route::prefix('employee')->group(function () {
                Route::get('/', 'index')->name('employee.index');
                Route::post('/create', 'store')->name('employee.store');
                Route::post('/update/{id}', 'update')->name('employee.update');
                Route::post('/check_email_employee', 'check_email')->name('check_email_employee');
            });
        });
        // Department
        Route::controller(MstDepartmentController::class)->group(function () {
            Route::prefix('department')->group(function () {
                Route::get('/', 'index')->name('department.index');
                Route::post('/create', 'store')->name('department.store');
                Route::post('/update/{id}', 'update')->name('department.update');
                Route::post('/activate/{id}', 'activate')->name('department.activate');
                Route::post('/deactivate/{id}', 'deactivate')->name('department.deactivate');
            });
        });
        // Position
        Route::controller(MstPositionController::class)->group(function () {
            Route::prefix('position')->group(function () {
                Route::get('/', 'index')->name('position.index');
                Route::get('/json_position/{id}', 'json_position')->name('json_position');
                Route::post('/create', 'store')->name('position.store');
                Route::post('/update/{id}', 'update')->name('position.update');
                Route::post('/activate/{id}', 'activate')->name('position.activate');
                Route::post('/deactivate/{id}', 'deactivate')->name('position.deactivate');
            });
        });
    });

    // MASTER CHECKLIST
    Route::middleware('role:Super Admin,Admin,PIC Dealers')->group(function () {
        // Parent Checklist
        Route::controller(MstParentChecklistController::class)->group(function () {
            Route::prefix('parentchecklist')->group(function () {
                Route::get('/', 'typechecklist')->name('parentchecklist.typechecklist');
                Route::get('/{type}', 'index')->name('parentchecklist.index');
                Route::get('/detail/{id}', 'detail')->name('parentchecklist.detail');
                Route::post('/detail/{id}', 'updateParent')->name('parentchecklist.updateParent');

                Route::get('/info/{id}', 'info')->name('parentchecklist.info');
                Route::get('/edit/{id}', 'edit')->name('parentchecklist.edit');
                Route::get('/order-no/{type_checklist}', 'mappingOrderNo')->name('mappingOrderNo'); //untuk ajax orderno
                Route::post('/create', 'store')->name('parentchecklist.store');
                Route::post('/update/{id}', 'update')->name('parentchecklist.update');
            });
        });
        // Checklist
        Route::controller(MstChecklistController::class)->group(function () {
            Route::prefix('checklist')->group(function () {
                Route::get('/', 'typechecklist')->name('checklist.typechecklist');
                Route::get('/{type}', 'index')->name('checklist.index');
                Route::get('/detail/{id}', 'detail')->name('checklist.detail');
                Route::post('/update/headCheck/{id}', 'updateHeadCheck')->name('checklist.updateHeadCheck');
                Route::post('/update/checklist/{id}', 'updateCheckDetail')->name('checklist.updateCheckDetail');
                Route::post('/update/mark/{id}', 'updateMark')->name('checklist.updateMark');

                Route::get('/mappingparent/{name}', 'mappingparent')->name('mappingParent');
                Route::get('/order-no/{parentPoint}/{typeChecklist}', 'mappingOrderNo')->name('mappingOrderNoChecklist'); //untuk ajax orderno
                Route::get('/info/{id}', 'info')->name('checklist.info');
                Route::get('/edit/{id}', 'edit')->name('checklist.edit');
                Route::get('/mark/{id}', 'mark')->name('checklist.mark');
                Route::get('/exc-order/{id}', 'exchangeOrder')->name('checklist.exchange');
                Route::post('/create', 'store')->name('checklist.store');
                Route::post('/update/{id}', 'update')->name('checklist.update');
                Route::post('/createmark/{id}', 'markstore')->name('checklist.markstore');
                Route::post('/deletemark/{id}', 'markdelete')->name('checklist.markdelete');
                Route::post('/exc-order/update/{id}', 'exchangeOrderUpdate')->name('checklist.exc_order.update');
            });
        });
        // Mapping Checklist
        Route::controller(MstMapChecklistController::class)->group(function () {
            Route::prefix('mapchecklist')->group(function () {
                Route::get('/', 'index')->name('mapchecklist.index');
                Route::get('/type/{type}', 'type')->name('mapchecklist.type');
                Route::get('/detail/{type}/{typecheck}', 'detail')->name('mapchecklist.detail');
                Route::post('/add/{type}', 'addtype')->name('mapchecklist.addtype');
                Route::post('/delete/{type}', 'deletetype')->name('mapchecklist.deletetype');
                Route::post('/addparent/{type}', 'addparent')->name('mapchecklist.addparent');
                Route::post('/deleteparent/{id}', 'deleteparent')->name('mapchecklist.deleteparent');
            });
        });
    });

    // ASSIGN CHECKLIST MENU
    Route::middleware('role:Super Admin,Admin,PIC Dealers')->group(function () {
        // Naming Period
        Route::controller(MstPeriodNameController::class)->group(function () {
            Route::prefix('periodname')->group(function () {
                Route::get('/', 'index')->name('periodname.index');
                Route::post('/create', 'store')->name('periodname.store');
                Route::post('/update/{id}', 'update')->name('periodname.update');
                Route::post('/activate/{id}', 'activate')->name('periodname.activate');
                Route::post('/deactivate/{id}', 'deactivate')->name('periodname.deactivate');
            });
        });
        // Period Checklist
        Route::controller(MstPeriodChecklistController::class)->group(function () {
            Route::prefix('periodchecklist')->group(function () {
                Route::get('/', 'index')->name('periodchecklist.index');
                Route::post('/create', 'store')->name('periodchecklist.store');
                Route::post('/update/{id}', 'update')->name('periodchecklist.update');
                Route::post('/updateexpired/{id}', 'updateexpired')->name('periodchecklist.updateexpired');
                Route::post('/activate/{id}', 'activate')->name('periodchecklist.activate');
                Route::post('/deactivate/{id}', 'deactivate')->name('periodchecklist.deactivate');
                Route::post('/delete/{id}', 'delete')->name('periodchecklist.delete');
            });
        });
        // Assign
        Route::controller(MstAssignChecklistController::class)->group(function () {
            Route::prefix('assignchecklist')->group(function () {
                Route::get('/{id}', 'index')->name('assignchecklist.index');
                Route::get('/searchchecklist/{id}', 'searchchecklist')->name('searchchecklist');
                Route::get('/type/{id}/{type}', 'type')->name('assignchecklist.type');
                Route::post('/create/{id}', 'store')->name('assignchecklist.store');
                Route::post('/submit/{id}', 'submit')->name('assignchecklist.submit');
                Route::post('/delete/{id}', 'delete')->name('assignchecklist.delete');
            });
        });
    });

    // LIST ASSIGNED CHECKLIST
    Route::middleware('role:Super Admin,Admin,PIC Dealers,Internal Auditor Dealer,Assessor Main Dealer,PIC NOS MD')->group(function () {
        Route::controller(ListAssignedChecklistController::class)->group(function () {
            Route::prefix('listassigned')->group(function () {
                Route::get('/period', 'periodList')->name('listassigned.periodList');
                Route::get('/period/detail/{id}', 'periodDetail')->name('listassigned.periodDetail');
                Route::get('/period/log-activity/{id}', 'logActivity')->name('listassigned.logActivity');
                // Detail Checklist
                Route::get('/checklist/detail/{id}', 'detailChecklist')->name('listassigned.detailChecklist');
            });
        });
    });

    // AUDITOR SECTION
    Route::middleware('role:Super Admin,Admin,Internal Auditor Dealer')->group(function () {
        Route::controller(AuditorController::class)->group(function () {
            Route::prefix('auditor')->group(function () {
                Route::post('/start-checklist/{id}', 'startChecklist')->name('auditor.start');
                Route::post('/submit-checklist/{id}', 'submitChecklist')->name('auditor.submit');
            });
        });
        // Form Audit
        Route::controller(FormChecklistController::class)->group(function () {
            Route::prefix('form')->group(function () {
                Route::get('/{id}', 'checklist')->name('form.checklist');
                Route::get('/get-form-pc/{id}', 'getFormPerCheck')->name('form.getFormPC');
                Route::get('/get-form-pp/{id}', 'getFormPerParent')->name('form.getFormPP');
                Route::post('/store-file-form-pc', 'storeFileFormPerCheck')->name('form.storeFileFormPC');
                Route::post('/store-file-form-pp', 'storeFileFormPerParent')->name('form.storeFileFormPP');
                Route::post('/finish-checklist', 'finishChecklist')->name('form.finishCheck');
                
            });
        });
    });

    // APPROVAL SECTION
    Route::middleware('role:Super Admin,Admin,PIC Dealers,Internal Auditor Dealer,Assessor Main Dealer,PIC NOS MD')->group(function () {
        Route::controller(ReviewChecklistController::class)->group(function () {
            Route::prefix('review')->group(function () {
                // Assesor
                Route::post('/take-review/{id}', 'takeReview')->name('review.takeReview');
                Route::post('/decision/{id}/approve', 'approve')->name('review.approve');
                Route::post('/decision/{id}/reject', 'reject')->name('review.reject');
                Route::post('/decision/{id}/reset', 'reset')->name('review.reset');
                Route::post('/decision/{id}/correction', 'correction')->name('review.correction');
                Route::post('/decision/{id}/renderCardOnly', 'renderCardOnly')->name('review.renderCardOnly');
                Route::post('/syncResultCorrection/{id}', 'syncResultCorrection')->name('review.syncResultCorrection');
                Route::post('/decision-checklist', 'decisionChecklist')->name('review.decisionChecklist');
                Route::post('/note/{id}', 'updateNoteChecklist')->name('review.updateNoteChecklist');
                Route::post('submit/{id}', 'submitReviewChecklist')->name('review.submitReviewChecklist');
                Route::post('submitCorrection/{id}', 'submitCorrectionChecklist')->name('review.submitCorrectionChecklist');
                // PIC NOS MD
                Route::post('decisionpic/{id}', 'updateDecisionPIC')->name('review.updateDecisionPIC');
                Route::post('submit-pic/{id}', 'submitPICReviewChecklist')->name('review.submitPICReviewChecklist');
            });
        });
    });

    // EXPORT
    Route::get('/export-period/{id}', [ExportController::class, 'exportPeriod'])->name('export.period')->middleware('role:Super Admin,Admin,PIC NOS MD');

    // API REGIONAL
    Route::controller(AjaxMappingRegional::class)->group(function () {
        Route::prefix('area/ajax')->group(function () {
            Route::get('/mappingCity/{province_id}', 'selectCity')->name('mappingCity');
            Route::get('/mappingDistrict/{city_id}', 'selectDistrict')->name('mappingDistrict');
            Route::get('/mappingSubDistrict/{district_id}', 'selectSubDistrict')->name('mappingSubDistrict');
            Route::get('/mappingPostalCode/{subdistrict_id}', 'selectPostalCode')->name('mappingPostalCode');
        });
    });

    // AUDIT LOG
    Route::get('/auditlog', [AuditLogController::class, 'index'])->name('auditlog')->middleware('role:Super Admin,Admin');
});
