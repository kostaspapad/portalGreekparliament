<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');
    $router->resource('/speakers', SpeakersController::class);
    $router->resource('/parties', PartiesController::class);
    $router->resource('/conferences', ConferencesController::class);
    $router->resource('/speeches', SpeechesController::class);
    // $router->get('/speeches/chart', 'SpeechesController@chart');
});
