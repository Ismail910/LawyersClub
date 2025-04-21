
<?php

use App\Http\Controllers\Accountant\ArchivedBudgetController;
use App\Http\Controllers\Accountant\ArchivedInvoiceController;
use App\Http\Controllers\Accountant\BudgetController;
use App\Http\Controllers\Accountant\BudgetStatisticsController;
use App\Http\Controllers\Accountant\InvoiceController;
use App\Http\Controllers\Accountant\InvoiceStatisticsController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CounterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HR\MemberController;
use App\Http\Controllers\HR\MembershipSectionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SequenceController;
use App\Models\Category;
use Illuminate\Support\Facades\Route;







Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('index/{locale}', [App\Http\Controllers\HomeController::class, 'lang']);


Route::post('/login', [LoginController::class, 'login'])->name('post-login');

// Logout route
Route::post('logout', [LoginController::class, 'logout'])->name('logout');


Route::get('/', [App\Http\Controllers\HomeController::class, 'root'])->name('root');




Route::post('/increment-member-subscription-sequence', [CounterController::class, 'incrementMemberSubscriptionSequence']);
Route::post('/increment-disbursement-order-sequence', [CounterController::class, 'incrementDisbursementOrderSequence']);
Route::post('/increment-supply-order-sequence', [CounterController::class, 'incrementSupplyOrderSequence']);


Route::middleware(['auth:admin,accountant,hr'])->group(function () {



    Route::get('profile', [ProfileController::class, 'showProfileForm'])->name('profile.show');
    Route::put('profile', [ProfileController::class, 'updateProfile'])->name('profile.update');


    Route::get('/', [App\Http\Controllers\HomeController::class, 'root'])->name('root');

    Route::resource('budgets', BudgetController::class);
    Route::resource('archived-budgets', ArchivedBudgetController::class);
    Route::resource('invoices', InvoiceController::class);
    Route::resource('archived-invoices', ArchivedInvoiceController::class);

    Route::prefix('hr')->name('hr.')->group(function () {
        Route::resource('members', MemberController::class);
        Route::resource('membership-sections', MembershipSectionController::class);
    });



    Route::get('/categories/{parent_id}/children', [BudgetController::class, 'getSubcategories']);


    Route::get('/budgets-statistics', [BudgetStatisticsController::class, 'getStatistics'])->name('budget.statistics');
    Route::get('/budgets-statistics/view', [BudgetStatisticsController::class, 'showStatisticsPage'])->name('budget.statistics.view');

    // Route to fetch child categories based on the selected parent category
    Route::get('/budgets/get-child-categories', [BudgetStatisticsController::class, 'getChildCategories'])->name('budget.getChildCategories');

    Route::get('/invoices-statistics', [InvoiceStatisticsController::class, 'getStatistics'])->name('invoice.statistics');
    Route::get('/invoices-statistics/view', [InvoiceStatisticsController::class, 'showStatisticsPage'])->name('invoice.statistics.view');

    Route::get('/invoices/get-child-categories', [InvoiceStatisticsController::class, 'getChildCategories'])->name('invoice.getChildCategories');


    Route::get('/sequences/member', [SequenceController::class, 'getMemberSequences'])->name('sequences.member');
    Route::get('/sequences/invoice', [SequenceController::class, 'getInvoiceSequences'])->name('sequences.invoice');
    Route::get('/sequences/budget', [SequenceController::class, 'getBudgetSequences'])->name('sequences.budget');


Route::post('/members/upload', [MemberController::class, 'upload'])->name('members.upload');


    Route::resource('categories', CategoryController::class);
});


