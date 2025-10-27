<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetController;
use App\Http\Livewire\MyPets;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';

// Rotas de pets
Route::get('/pets/{id?}', \App\Http\Livewire\PetsIndex::class)->name('pets.index');

// Rota de teste do mapa
Route::get('/teste', function () {
    return view('teste-mapa');
});

Route::get('/minhas-publicacoes', MyPets::class)
    ->middleware(['auth']) // só usuários logados podem acessar
    ->name('minhas.publicacoes');

