<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\LeadsTable;

Route::get('/', function () {
    return view('leads');
});

Route::get('/leads', LeadsTable::class)->name('leads.index');
