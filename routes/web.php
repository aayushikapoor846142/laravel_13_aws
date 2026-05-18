<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Support Knowledge Base Routes
use App\Http\Controllers\SupportKnowledgeBaseController;

Route::prefix('support')->name('support.')->group(function () {
    Route::get('/', [SupportKnowledgeBaseController::class, 'index'])->name('index');
    Route::post('/ask', [SupportKnowledgeBaseController::class, 'ask'])->name('ask');
    Route::post('/feedback/{queryId}', [SupportKnowledgeBaseController::class, 'feedback'])->name('feedback');
    Route::get('/category/{category}', [SupportKnowledgeBaseController::class, 'category'])->name('category');
    Route::get('/article/{id}', [SupportKnowledgeBaseController::class, 'show'])->name('article');
    
    // Admin routes
    Route::middleware('auth')->group(function () {
        Route::post('/articles', [SupportKnowledgeBaseController::class, 'storeArticle'])->name('articles.store');
        Route::get('/analytics', [SupportKnowledgeBaseController::class, 'analytics'])->name('analytics');
    });
});
