<?php

use App\Livewire\EmailVerification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Route;

use App\Livewire\Register;
use App\Livewire\Login;
use App\Livewire\ForgotPassword;
use App\Livewire\ResetPassword;

use App\Livewire\Dashboard;
use App\Livewire\Logout;
use App\Livewire\Profile;
use App\Livewire\ChangePassword;
use App\Http\Controllers\AIController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::group(['middleware' => ['guest']], function () {
    Route::get('register', Register::class)->name('register');
    Route::get('/', Login::class);
    Route::get('login', Login::class)->name('login');
    Route::get('forgot-password', ForgotPassword::class)->name('forgot-password');
    Route::get('reset-password/{link}', ResetPassword::class)->name('reset-password');
    Route::get('verify-email/{token}', EmailVerification::class)->name('verify-email');
});

Route::group(['middleware' => ['auth','checkEmailVerified']], function () {
    Route::get('dashboard', Dashboard::class)->name('dashboard');
    Route::get('logout', Logout::class)->name('logout');

    Route::get('profile', Profile::class)->name('profile');
    Route::get('change-password', ChangePassword::class)->name('change-password');

});

Route::get('/analyze', [AIController::class, 'analyze'])->name('analyze');
Route::post('/summary', [AIController::class, 'generateSummary'])->name('generate-summary');