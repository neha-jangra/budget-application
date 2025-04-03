<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Settings\EditProfile;
use App\Http\Livewire\Settings\ChangePassword;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Auth::routes();

Route::get('currency-format', function () {
    return view('currency-format');
});
Route::get('/', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('/');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('send-email', [App\Http\Controllers\Frontend\UserController::class, 'sendEmail'])->name('send.email');

Route::get('reset-verification-code', [App\Http\Controllers\Frontend\UserController::class, 'resetVerifyCode'])->name('reset.verification.code');

Route::post('verify-code', [App\Http\Controllers\Frontend\UserController::class, 'verifyCode'])->name('verify.code');

Route::get('confirm-password', [App\Http\Controllers\Frontend\UserController::class, 'confirmPassword'])->name('confirm.password');

Route::post('password-change', [App\Http\Controllers\Frontend\UserController::class, 'passwordChange'])->name('password.change');

Route::get('get-current-project-user-data', [App\Http\Controllers\Frontend\CommonController::class, 'Currentprojectuserdata']);
Route::get('report-pdf', [App\Http\Controllers\Frontend\ReportsController::class, 'takeScreenshot'])->name('reports.pdf');
Route::get('export-report-pdf', [App\Http\Controllers\Frontend\ReportsController::class, 'exportPdf'])->name('reports-export.pdf');

Route::middleware('auth')->group(function () {

    Route::resource('project', App\Http\Controllers\Frontend\ProjectController::class)->middleware('permission:project');

    Route::get('get-current-project-estimate-budget', [App\Http\Controllers\Frontend\CommonController::class, 'CurrentprojectestimateBudget']);

    Route::get('get-user-project', [App\Http\Controllers\Frontend\ProjectController::class, 'Userfetchproject']);

    Route::get('get-other-direct-expenses', [App\Http\Controllers\Frontend\IndirectCostsBudgetController::class, 'getOtherDirectExpenses']);

    Route::get('get-donor-project', [App\Http\Controllers\Frontend\ProjectController::class, 'getDonorProject']);

    Route::get('get-lineitem-project', [App\Http\Controllers\Frontend\ProjectController::class, 'getLineitemProject']);

    Route::get('update-last-tab', [App\Http\Controllers\Frontend\ProjectController::class, 'updateLasttab']);

    Route::resource('user', App\Http\Controllers\Frontend\ManagementController::class)->middleware('permission:user');

    Route::resource('donor', App\Http\Controllers\Frontend\DonorController::class)->middleware('permission:donor');

    Route::resource('line-item', App\Http\Controllers\Frontend\LineItemController::class)->middleware('permission:line_item');

    Route::get('line-item/create/consultant', [App\Http\Controllers\Frontend\LineItemController::class, 'createConsultant'])->name('consultant.create');

    Route::get('line-item/edit/{consultant}/consultant', [App\Http\Controllers\Frontend\LineItemController::class, 'editConsultant'])->name('consultant.edit');

    Route::get('line-item/create/sub-grantee', [App\Http\Controllers\Frontend\LineItemController::class, 'createSubgrantee'])->name('subgrantee.create');

    Route::get('line-item/edit/{subgrantee}/sub-grantee', [App\Http\Controllers\Frontend\LineItemController::class, 'editSubgrantee'])->name('subgrantee.edit');

    Route::get('line-item/create/employee', [App\Http\Controllers\Frontend\LineItemController::class, 'createEmployee'])->name('employee.create');

    Route::get('line-item/edit/{employee}/employee', [App\Http\Controllers\Frontend\LineItemController::class, 'editEmployee'])->name('employee.edit');

    Route::resource('role-management', App\Http\Controllers\Frontend\RoleManagementController::class)->middleware('permission:role_management');

    Route::get('indirect-costs-budget', [App\Http\Controllers\Frontend\IndirectCostsBudgetController::class, 'index'])->name('indirect.index');
    Route::get('indirect-all-tab', [App\Http\Controllers\Frontend\IndirectCostsBudgetController::class, 'allTabData'])->name('indirect.allTab');

    Route::get('reports', [App\Http\Controllers\Frontend\ReportsController::class, 'index'])->name('reports.index');
    // Route::post('report-pdf', [App\Http\Controllers\Frontend\ReportsController::class, 'generatePDF'])->name('reports.pdf');
    Route::post('/comments/send', [App\Http\Controllers\Frontend\CommentsController::class, 'sendComment'])->name('comments.send');
    Route::get('/comments/users', [App\Http\Controllers\Frontend\CommentsController::class, 'getCommentsUsers'])->name('comments.users');
    Route::post('/comments/reply', [App\Http\Controllers\Frontend\CommentsController::class, 'sendReply'])->name('comments.reply');
    Route::post('/comments/edit/{id}', [App\Http\Controllers\Frontend\CommentsController::class, 'editComment'])->name('comments.edit');
    Route::delete('/comments/delete/{id}', [App\Http\Controllers\Frontend\CommentsController::class, 'deleteComment'])->name('comments.delete');
    Route::get('/comments/unread-count', [App\Http\Controllers\Frontend\CommentsController::class, 'getUnreadCommentsCount']);
    Route::post('/comments/mark-as-read', [App\Http\Controllers\Frontend\CommentsController::class, 'markCommentsAsRead']);

    Route::get('/settings', [App\Http\Controllers\Frontend\SettingsController::class, 'index'])->name('settings.index');
    Route::get('/settings/edit-profile', EditProfile::class)->name('settings.edit-profile');
    Route::get('/settings/change-password', ChangePassword::class)->name('settings.change-password');

});
