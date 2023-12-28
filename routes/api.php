<?php
Route::group(['middleware' => ['api.basic_auth']], function () {
    require "partials/api/fields.php";
});
