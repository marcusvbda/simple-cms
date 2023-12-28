<?php

use App\Http\Controllers\ProductController;

Route::group(['prefix' => "products"], function () {
    Route::get('{code}/set-category', [ProductController::class, 'setCategory']);
});
