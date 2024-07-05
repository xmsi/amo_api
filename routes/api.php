<?php

use App\Http\Controllers\ContactsController;
use Illuminate\Support\Facades\Route;

Route::post("contacts", ContactsController::class);
