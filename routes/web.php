<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json(["message" => "Welcome to API Akreditasi"], 200);
});

Route::get('/', function () {
    return response()->json(["message" => "Welcome to API Akreditasi"], 200);
});
Route::get('/hi', function () {
    return response()->json(["message" => "Hai"], 200);
});
