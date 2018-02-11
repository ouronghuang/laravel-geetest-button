<?php

use Illuminate\Support\Facades\Route;

Route::get('/captcha', 'CaptchaController@captcha')->name('captcha');
