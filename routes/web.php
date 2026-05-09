<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\NoteController;

Route::inertia('/', 'Welcome', [
    'canRegister' => Features::enabled(Features::registration()),
])->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function (Request $request) {
        return Inertia::render('Dashboard', [
            'notesCount' => $request->user()->notes()->count(),
        ]);
    })->name('dashboard');

    Route::resource('notes', NoteController::class)->only(['index', 'store', 'update', 'destroy']);
});

require __DIR__.'/settings.php';
