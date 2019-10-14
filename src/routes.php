<?php

$prefix = config('chamomile.route_prefix');

Route::get($prefix . '/{resource}/config', 'Rashidul\Chamomile\Controllers\Controller@config');
Route::post($prefix . '/{resource}', 'Rashidul\Chamomile\Controllers\Controller@store');
Route::get($prefix . '/{resource}', 'Rashidul\Chamomile\Controllers\Controller@index');
Route::get($prefix . '/{resource}/{id}', 'Rashidul\Chamomile\Controllers\Controller@show');
Route::delete($prefix . '/{resource}/{id}', 'Rashidul\Chamomile\Controllers\Controller@delete');
Route::put($prefix . '/{resource}/{id}', 'Rashidul\Chamomile\Controllers\Controller@update');