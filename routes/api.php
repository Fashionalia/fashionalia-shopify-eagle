<?php
use Illuminate\Support\Facades\Route;

Route::get('ping', 'ApiPingController');
Route::get('order', 'ApiOrderController');
Route::get('catalog', 'ApiCatalogController');
Route::post('order', 'ApiOrdersCreateController');
