<?php

use App\Http\Controllers\Backend\IndexController;
use App\Http\Controllers\Backend\UserProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::group(['prefix' => 'admin', 'middleware' => ['auth:admin', 'verified']], function() {
    Route::get('/', [IndexController::class, 'index']);
    Route::get('/dashboard', [IndexController::class, 'index'])->name('admin.dashboard');

    // User & Profile...
    Route::get('/user/profile', [UserProfileController::class, 'show'])
                ->name('admin.profile.show');

    // // API...
    // if (Jetstream::hasApiFeatures()) {
    //     Route::get('/user/api-tokens', [ApiTokenController::class, 'index'])->name('api-tokens.index');
    // }

    // // Teams...
    // if (Jetstream::hasTeamFeatures()) {
    //     Route::get('/teams/create', [TeamController::class, 'create'])->name('teams.create');
    //     Route::get('/teams/{team}', [TeamController::class, 'show'])->name('teams.show');
    //     Route::put('/current-team', [CurrentTeamController::class, 'update'])->name('current-team.update');

    //     Route::get('/team-invitations/{invitation}', [TeamInvitationController::class, 'accept'])
    //                 ->middleware(['signed'])
    //                 ->name('team-invitations.accept');
    // }
});

require __DIR__.'/auth.php';
