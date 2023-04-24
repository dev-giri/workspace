<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('chat')->group(function() {
    Route::get('/', 'ChatController@index');
    Route::get( '/home', 'ChatController@home' )->name( 'home' );

    Route::get( 'user-list', "MessageController@user_list" )->name( 'user.list' );
    Route::get( 'user-message/{id}', "MessageController@user_message" )->name( 'user.message' );
    Route::post( 'send-message', "MessageController@send_message" )->name( 'send.message' );
    Route::get( 'delete-single-message/{id}', "MessageController@delete_single_message" )->name( 'delete_single_message' );
    Route::get( 'delete-all-message/{user_id}', "MessageController@delete_all_message" )->name( 'delete_all_message' );
});
