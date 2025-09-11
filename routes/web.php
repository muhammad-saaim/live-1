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
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard.index');
    }
    return view('auth.login');
});


Route::get('/removeTestSurvey', [SurveyController::class, 'removeTestSurvey']);


Route::get('/removeTestSurvey', [QuestionController::class, 'removeTestSurvey']);

Route::get('/dashboard/personality-report', [DashboardController::class, 'personalityReport'])->name('dashboard.personality.report');

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

    Route::post('/personal_rate_survey', [SurveyRateController::class, 'personal_index'])->name('survey.personal_index');
    Route::post('/rate-survey', [SurveyRateController::class, 'rate'])->name('rate.survey');
    Route::post('/survey/next-question', [SurveyRateController::class, 'getNextQuestion'])->name('survey.nextQuestion');
    Route::post('/survey/get-next-question', [SurveyRateController::class, 'getNextQuestion'])->name('survey.getNextQuestion');
    Route::post('/survey/get-next-rated-question', [SurveyRateController::class, 'getNextRatedQuestion'])->name('survey.getNextRatedQuestion');
    Route::post('/survey/get-unrated-questions', [SurveyRateController::class, 'getUnratedQuestions'])->name('survey.getUnratedQuestions');
    Route::post('/survey/save-answer', [SurveyRateController::class, 'saveAnswer'])->name('survey.saveAnswer');
    Route::get('/survey/previous-question', [SurveyRateController::class, 'getPreviousQuestion'])->name('survey.previousQuestion');
    Route::post('/survey/submit-answer', [SurveyRateController::class, 'submitAnswer'])->name('survey.submitAnswer');
    Route::post('/survey/submit-group-answer', [SurveyRateController::class, 'submitGroupAnswer'])->name('survey.submitGroupAnswer');
    Route::post('/survey/check-existing-ratings', [SurveyRateController::class, 'checkExistingRatings'])->name('survey.checkExistingRatings');
    Route::post('/survey/check-all-users-rated', [SurveyRateController::class, 'checkAllUsersRated'])->name('survey.checkAllUsersRated');
    Route::post('survey/ShowSurvey', [SurveyRateController::class, 'ShowSurvey'])->name('survey.ShowSurvey');

     Route::get('/survey/previous-question/{id}', function ($id) {
        $question = \App\Models\Question::with('options')->find($id);
        if ($question) {
            return response()->json(['status' => 'success', 'question' => $question]);
        }
        return response()->json(['status' => 'error', 'message' => 'Question not found'], 404);
    });

    Route::resource('group',GroupController::class);

    // Route::post('/add/member', [InviteController::class, 'addtoinvite'])->name('add-members.store');


    Route::post('/group/invite', [InviteController::class, 'sendInvite'])->name('group.invite');
    Route::get('/groups/accept-invite', [InviteController::class, 'acceptInvite'])->name('groups.accept-invite');
    Route::delete('/groups/{group}/members', [GroupController::class, 'removeMember'])->name('groups.removeMembers');
    Route::delete('/groups/{group}/invitations/{email}', [GroupController::class, 'cancelInvitation'])->name('groups.cancelInvitation');
    // Route::delete('/groups/{group}/leave', [GroupController::class, 'leaveGroup'])->name('groups.leave');

    Route::get('/reports/index', [ReportsController::class, 'index'])->name('reports.index');
    // Mentor
    Route::get('/mentor', [\App\Http\Controllers\MentorController::class, 'index'])->middleware('role:mentor')->name('mentor.index');
    Route::get('/mentor/list', [\App\Http\Controllers\MentorController::class, 'clients'])->name('mentor.list');
    Route::post('/mentor/share', [\App\Http\Controllers\MentorController::class, 'share'])->name('mentor.share');
    Route::get('/mentor/client/{client}', [\App\Http\Controllers\MentorController::class, 'clientReports'])->middleware('role:mentor')->name('mentor.client');

    Route::group(['middleware' => ['role:admin']], function () {

        Route::resource('admin', AdminConsoleController::class);
        Route::resource('surveyModel',SurveyModelController::class);
        Route::resource('survey',SurveyController::class);
        Route::resource('users',UserController::class);
    
        Route::get('/download-report', [ReportsController::class, 'downloadPdf'])->name('report.download');
        Route::get('/export-survey', [ReportsController::class, 'exportSurveyExcel'])->name('survey.export');
 
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
Route::get('reverse', [QuestionController::class, 'reverseQuestions']);
require __DIR__.'/auth.php';

Route::fallback(function () {
    return redirect()->route('login')->with('error', 'Page not found. Please log in.');
});
Route::post('/survey/next-question', [SurveyRateController::class, 'getNextQuestion'])->name('survey.nextQuestion');

