<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use TCG\Voyager\Facades\Voyager;

Route::impersonate();
Auth::routes();

Route::get('/storage/{path}', '\Workspace\Http\Controllers\HynOverrideMediaController')
    ->where('path', '.+')
    ->name('tenant.media');


// Voyager Admin routes
Route::group(['prefix' => 'admin'], function () {
	Route::get('modules', '\Workspace\Http\Controllers\ModuleManageController@index')->name('modules.all');
	Route::post('modules/update-package', '\Workspace\Http\Controllers\ModuleManageController@updatePackage')->name('modules.update-package');
	Route::post('modules/update-status', '\Workspace\Http\Controllers\ModuleManageController@updateStatus')->name('modules.update-status');
	Route::post('modules/delete-module', '\Workspace\Http\Controllers\ModuleManageController@deleteModule')->name('modules.delete-module');
    Voyager::routes();
});    

Route::get('/', '\Workspace\Http\Controllers\HomeController@index')->name('workspace.home');
Route::get('@{username}', '\Workspace\Http\Controllers\ProfileController@index')->name('workspace.profile');

// Documentation routes
Route::view('docs/{page?}', 'docs::index')->where('page', '(.*)');

// Additional Auth Routes
Route::get('logout', '\Workspace\Http\Controllers\Auth\LoginController@logout')->name('workspace.logout');
Route::get('user/verify/{verification_code}', '\Workspace\Http\Controllers\Auth\RegisterController@verify')->name('verify');
Route::post('register/complete', '\Workspace\Http\Controllers\Auth\RegisterController@complete')->name('workspace.register-complete');

Route::get('blog', '\Workspace\Http\Controllers\BlogController@index')->name('workspace.blog');
Route::get('blog/{category}', '\Workspace\Http\Controllers\BlogController@category')->name('workspace.blog.category');
Route::get('blog/{category}/{post}', '\Workspace\Http\Controllers\BlogController@post')->name('workspace.blog.post');

Route::view('install', 'Workspace::install')->name('workspace.install');

/***** Pages *****/
Route::get('p/{page}', '\Workspace\Http\Controllers\PageController@page');

/***** Pricing Page *****/
Route::view('pricing', 'theme::pricing')->name('workspace.pricing');

/***** Billing Routes *****/
Route::post('paddle/webhook', '\Workspace\Http\Controllers\SubscriptionController@webhook');
Route::post('checkout', '\Workspace\Http\Controllers\SubscriptionController@checkout')->name('checkout');

Route::get('test', '\Workspace\Http\Controllers\SubscriptionController@test');

Route::group(['middleware' => 'auth'], function () {
	Route::get('dashboard', '\Workspace\Http\Controllers\DashboardController@index')->name('workspace.dashboard');
});

Route::group(['middleware' => 'auth'], function(){
	Route::get('settings/{section?}', '\Workspace\Http\Controllers\SettingsController@index')->name('workspace.settings');

	Route::post('settings/profile', '\Workspace\Http\Controllers\SettingsController@profilePut')->name('workspace.settings.profile.put');
	Route::put('settings/security', '\Workspace\Http\Controllers\SettingsController@securityPut')->name('workspace.settings.security.put');

	Route::post('settings/api', '\Workspace\Http\Controllers\SettingsController@apiPost')->name('workspace.settings.api.post');
	Route::put('settings/api/{id?}', '\Workspace\Http\Controllers\SettingsController@apiPut')->name('workspace.settings.api.put');
	Route::delete('settings/api/{id?}', '\Workspace\Http\Controllers\SettingsController@apiDelete')->name('workspace.settings.api.delete');

	Route::get('settings/invoices/{invoice}', '\Workspace\Http\Controllers\SettingsController@invoice')->name('workspace.invoice');

	Route::get('notifications', '\Workspace\Http\Controllers\NotificationController@index')->name('workspace.notifications');
	Route::get('announcements', '\Workspace\Http\Controllers\AnnouncementController@index')->name('workspace.announcements');
	Route::get('announcement/{id}', '\Workspace\Http\Controllers\AnnouncementController@announcement')->name('workspace.announcement');
	Route::post('announcements/read', '\Workspace\Http\Controllers\AnnouncementController@read')->name('workspace.announcements.read');
	Route::get('notifications', '\Workspace\Http\Controllers\NotificationController@index')->name('workspace.notifications');
	Route::post('notification/read/{id}', '\Workspace\Http\Controllers\NotificationController@delete')->name('workspace.notification.read');

    /********** Checkout/Billing Routes ***********/
    Route::post('cancel', '\Workspace\Http\Controllers\SubscriptionController@cancel')->name('workspace.cancel');
    Route::view('checkout/welcome', 'theme::welcome');

    Route::post('subscribe', '\Workspace\Http\Controllers\SubscriptionController@subscribe')->name('workspace.subscribe');
	Route::view('trial_over', 'theme::trial_over')->name('workspace.trial_over');
	Route::view('cancelled', 'theme::cancelled')->name('workspace.cancelled');
    Route::post('switch-plans', '\Workspace\Http\Controllers\SubscriptionController@switchPlans')->name('workspace.switch-plans');
});

Route::group(['middleware' => 'admin.user'], function(){
    Route::view('admin/do', 'Workspace::do');
});
