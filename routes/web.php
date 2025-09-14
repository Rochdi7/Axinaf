<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Public site
require __DIR__.'/frontoffice.php';

// Admin dashboard
require __DIR__.'/backoffice.php';

// 🔐 Auth routes
Auth::routes(['register' => false]); // Supprime cette option si tu veux autoriser l'inscription

// (Optionnel) page d’accueil après login
Route::get('/home', function () {
    return view('backoffice.dashboard'); // ou ce que tu veux afficher après login
})->middleware('auth')->name('home');
