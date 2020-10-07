<?php
Route::namespace('Jcc\Jwt\http\Controllers')->middleware(['\Barryvdh\Cors\HandleCors'])->prefix('/api/v1')->group(function () {
    Route::post('login', 'AuthorizationController@login')->name('auth.login');
    Route::post('logout', 'AuthorizationController@logout')->name('auth.logout');
    Route::put('update', 'AuthorizationController@update')->name('auth.update');

    Route::middleware(['api.jwt.refresh'])->group(function () {
        Route::get('show', 'AuthorizationController@show')->name('auth.show')->defaults('desc', '登录用户信息');
    });

});
