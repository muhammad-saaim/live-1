<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminConsoleController;
use App\Http\Controllers\AnalysisController;
use App\Http\Controllers\Auth\ProviderController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\MatchingController;
use App\Http\Controllers\PersonalInfoController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SolutionsController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\SurveyModelController;
use App\Http\Controllers\SurveyRateController;
use App\Http\Controllers\TestingController;
use App\Http\Controllers\TrainingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

// Socialite login integration
Route::get('/auth/{provider}/redirect', [ProviderController::class, 'redirect']);
Route::get('/auth/{provider}/callback', [ProviderController::class, 'callback']);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/account', [AccountController::class, 'edit'])->name('account.edit');
    Route::patch('/account', [AccountController::class, 'update'])->name('account.update');
    Route::delete('/account', [AccountController::class, 'destroy'])->name('account.destroy');

    Route::get('/profile/information', [PersonalInfoController::class, 'index'])->name('profile.information');
    Route::post('/profile/information', [PersonalInfoController::class, 'store'])->name('profile.information');

    Route::resource('dashboard', DashboardController::class);
    Route::resource('testing', TestingController::class);
    Route::resource('analysis', AnalysisController::class);
    Route::resource('training', TrainingController::class);
    Route::resource('matching', MatchingController::class);
    Route::resource('solutions', SolutionsController::class);

    Route::post('/rate-survey', [SurveyRateController::class, 'rate'])->name('rate.survey');
    Route::post('/survey/next-question', [SurveyRateController::class, 'getNextQuestion'])->name('survey.nextQuestion');
    Route::post('/survey/save-answer', [SurveyRateController::class, 'saveAnswer'])->name('survey.saveAnswer');
    Route::get('/survey/previous-question', [SurveyRateController::class, 'getPreviousQuestion'])->name('survey.previousQuestion');
    Route::post('/survey/submit-answer', [SurveyRateController::class, 'submitAnswer'])->name('survey.submitAnswer');
    Route::post('survey/ShowSurvey', [SurveyRateController::class, 'ShowSurvey'])->name('survey.ShowSurvey');


    Route::resource('group',GroupController::class);

    // Route::post('/add/member', [InviteController::class, 'addtoinvite'])->name('add-members.store');
    Route::post('/group/invite', [InviteController::class, 'sendInvite'])->name('group.invite');
    Route::get('/groups/accept-invite', [InviteController::class, 'acceptInvite'])->name('groups.accept-invite');

    Route::get('/reports/index', [ReportsController::class, 'index'])->name('reports.index');

    Route::group(['middleware' => ['role:admin']], function () {

        Route::resource('admin', AdminConsoleController::class);
        Route::resource('surveyModel',SurveyModelController::class);
        Route::resource('survey',SurveyController::class);
        Route::resource('users',UserController::class);

        Route::prefix('question')->name('question.')->group(function () {
            Route::get('/', [QuestionController::class, 'index'])->name('index'); // List all questions
            Route::get('/create', [QuestionController::class, 'create'])->name('create'); // Show form to create a new question
            Route::post('/', [QuestionController::class, 'store'])->name('store'); // Store new question
            Route::get('/{question}', [QuestionController::class, 'show'])->name('show'); // Show a single question
            Route::get('/{question}/edit', [QuestionController::class, 'edit'])->name('edit'); // Show form to edit a question
            Route::put('/{question}', [QuestionController::class, 'update'])->name('update'); // Update question
            Route::delete('/{question}', [QuestionController::class, 'destroy'])->name('destroy'); // Delete question
        });

    });
    Route::get('options/auto-complete', [QuestionController::class, 'autoComplete'])->name('options.autoComplete');
});

require __DIR__.'/auth.php';

Route::fallback(function () {
    return redirect()->route('login')->with('error', 'Page not found. Please log in.');
});

/*
php artisan cache:clear
php artisan route:clear
php artisan config:clear
php artisan view:clear
php artisan optimize:clear
*/
