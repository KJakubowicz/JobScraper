<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JobOfferController;
use App\Http\Controllers\ProcessLogController;
use App\Http\Controllers\JobOfferVersionController;

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

Route::get('/', [JobOfferController::class, 'index'])->name('job-offers.index');
Route::get('/{jobOfferId}/history', [JobOfferController::class, 'history'])->name('job-offers.history');
Route::get('/process-logs', [ProcessLogController::class, 'index'])->name('process-logs.index');
Route::post('/version/active/{versionId}', [JobOfferVersionController::class, 'setActiveVersion'])->name('job-offers-version.set-active-version');
Route::delete('/version/delete/{versionId}', [JobOfferVersionController::class, 'deleteVersion'])->name('job-offers-version.delete-version');