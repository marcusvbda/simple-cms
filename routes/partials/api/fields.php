<?php

use App\Http\Controllers\Api\FieldsController;

Route::group(['prefix' => "fields"], function () {
    Route::get('', function () {
        $routes = collect(Route::getRoutes()->getRoutes())
            ->filter(function ($route) {
                return isset($route->action['prefix']) && $route->action['prefix'] === 'api/fields';
            })
            ->pluck('uri')
            ->toArray();

        return response()->json($routes);
    });
    Route::get('get-field/{field?}', [FieldsController::class, 'getFields']);
});
