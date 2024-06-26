<?php

use App\Jobs\NewJob;
use App\Events\NewEvent;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/job', function () {
    $batch = Bus::batch([
        new NewJob(),
    ])->dispatch();
});

Route::get('/cache', function () {
    if (Cache::has('key')) {
        return Cache::get('key');
    }

    Cache::add('key', 'value');
    return Cache::get('key');
});

Route::get('/dumps', function () {
    return dump('hello Morshedy from the dump');
});

Route::get('/event', function () {
    NewEvent::dispatch();
});

Route::get('/exception', function () {
    throw new Exception("this is an exception");
});

Route::get('/gates', function () {
    if (Gate::forUser(Auth::user())->allows('newGate')) {
        return 'you are allowed to take this action';
    }
    abort(403);
});

Route::get('/http', function () {
    return Http::get('http://example.com');
});

Route::get('/logs', function () {
    // Log::info("hello from the logs info level"); => work error level only
    Log::error("hello from the logs error level");
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
