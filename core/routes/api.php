<?php

use Illuminate\Support\Facades\Route;

Route::post('login', '\Workspace\Http\Controllers\API\AuthController@login');
Route::post('register', '\Workspace\Http\Controllers\API\AuthController@register');
Route::post('logout', '\Workspace\Http\Controllers\API\AuthController@logout');
Route::post('refresh', '\Workspace\Http\Controllers\API\AuthController@refresh');
Route::post('token', '\Workspace\Http\Controllers\API\AuthController@token');

// BROWSE
Route::get('/{datatype}', '\Workspace\Http\Controllers\API\ApiController@browse');

// READ
Route::get('/{datatype}/{id}', '\Workspace\Http\Controllers\API\ApiController@read');

// EDIT
Route::put('/{datatype}/{id}', '\Workspace\Http\Controllers\API\ApiController@edit');

// ADD
Route::post('/{datatype}', '\Workspace\Http\Controllers\API\ApiController@add');

// DELETE
Route::delete('/{datatype}/{id}', '\Workspace\Http\Controllers\API\ApiController@delete');
