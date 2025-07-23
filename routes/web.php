<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;

Route::get('/', function () {
    return 'تجربة ناجحة 🎉';
});

Route::get('/users', function () {
    return User::all();
});
