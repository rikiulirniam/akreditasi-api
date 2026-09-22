<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(["message" => "Welcome to API Akreditasi"], 200);
});
