<?php

use App\Http\Controllers\ContactsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('contact');
});

Route::post('/contacts', ContactsController::class);
