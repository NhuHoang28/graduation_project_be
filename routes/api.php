<?php

use App\Http\Controllers\AnswerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::post('/register', 'register')->name('register');
    Route::post('/login', 'login')->name('login');
    Route::get('/logout', 'logout')->middleware('auth:sanctum')->name('logout');
});

Route::middleware('auth:sanctum')->group(function() {
    Route::get('/me', [UserController::class, 'getMe'])->name('get-me');
    Route::controller(ClassController::class)->prefix('class')->group(function() {
        Route::get('/list', 'index')->name('class-list');
        Route::get('/{id}', 'show')->name('class-detail');
        Route::put('/{id}', 'update')->name('class-update');
        Route::delete('/{id}', 'destroy')->name('class-destroy');
    });
    Route::controller(GroupController::class)->prefix('teachers')->group(function() {
        Route::get('/groups', 'index')->name('group-list');
        Route::get('/groups/{id}', 'show')->name('group-detail');
        Route::get('/groups/{id}/students', 'listStudentInGroup')->name('group-students');
        Route::get('/groups/{id}/score', 'listScoreStudentInGroup')->name('group-score-students');
        Route::get('/groups/{id}/assignments', 'getAssignments')->name('group-assignment');
        Route::post('/groups/{id}/assignments', 'storeAssignmentGroupId')->name('group-assignment-store');
        Route::get('/groups/{id}/assignments/{assignment_id}', 'getAssignmentById')->name('group-assignment-get');
        Route::put('/groups/{id}/assignments/{assignment_id}', 'updateAssignmentGroupId')->name('group-assignment-update');
        Route::delete('/groups/{id}/assignments/{assignment_id}', 'deleteAssignmentGroupId')->name('group-assignment-delete');
    });
    Route::controller(QuestionController::class)->prefix('teachers')->group(function() {
        Route::post('/questions', 'store')->name('questions-store');
        Route::get('/library', 'libraryList')->name('library-list');
        Route::get('/my-questions', 'questionList')->name('my-questions-list');
        Route::get('/questions/{id}', 'show')->name('my-questions-show');
        Route::put('/questions/{id}', 'updateQuestion')->name('update-questions');

    });
    Route::controller(AnswerController::class)->prefix('teachers')->group(function() {
        Route::get('/groups/{group_id}/assignments/{assignment_id}/answers', 'answerList')->name('answer-list');
        Route::get('/groups/{group_id}/assignments/{assignment_id}/answers/{answer_id}', 'answerDetail')->name('answer-detail');
        Route::put('/groups/{group_id}/assignments/{assignment_id}/answers/{answer_id}', 'answerUpdate')->name('answer-update');
    });

    Route::controller(StudentController::class)->prefix('students')->group(function() {
        Route::get('/groups/{group_id}/assignments/{assignment_id}/answers', 'allAnswer')->name('student-answer-detail');
        Route::get('/groups/{group_id}/assignments/{assignment_id}/answer', 'answerDetail')->name('answer-detail');
        Route::post('/answer', 'createAnswer')->name('create-answer');
        Route::get('/groups', 'listGroupOfStudent')->name('student-group-list');
    });
});
