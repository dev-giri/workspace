# Routing

In this section we'll quickly cover the Workspace routes.

- [Workspace Web Routes](#web-routes)
- [Workspace API Routes](#api-routes)
- [Workspace Middleware](#workspace-middleware)

---

<a name="web-routes"></a>
### Workspace Web Routes

If you take a look inside of `workspace/routes/web.php` you will see all the Workspace web routes:

```php
<?php

Route::impersonate();

Route::get('/', '\Workspace\Http\Controllers\HomeController@index')->name('workspace.home');
Route::get('@{username}', '\Workspace\Http\Controllers\ProfileController@index')->name('workspace.profile');

// Documentation routes
Route::view('docs/{page?}', 'docs::index')->where('page', '(.*)');

// Additional Auth Routes
Route::get('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('user/verify/{verification_code}', 'Auth\RegisterController@verify')->name('verify');
Route::post('register/complete', '\Workspace\Http\Controllers\Auth\RegisterController@complete')->name('workspace.register-complete');

Route::get('blog', '\Workspace\Http\Controllers\BlogController@index')->name('workspace.blog');
Route::get('blog/{category}', '\Workspace\Http\Controllers\BlogController@category')->name('workspace.blog.category');
Route::get('blog/{category}/{post}', '\Workspace\Http\Controllers\BlogController@post')->name('workspace.blog.post');

Route::view('install', 'workspace::install')->name('workspace.install');

/***** Pages *****/
Route::get('p/{page}', '\Workspace\Http\Controllers\PageController@page');

/***** Pricing Page *****/
Route::view('pricing', 'theme::pricing')->name('workspace.pricing');

/***** Billing Routes *****/
Route::post('/billing/webhook', '\Workspace\Http\Controllers\WebhookController@handleWebhook');
Route::post('paddle/webhook', '\Workspace\Http\Controllers\SubscriptionController@hook');
Route::post('checkout', '\Workspace\Http\Controllers\SubscriptionController@checkout')->name('checkout');

Route::get('test', '\Workspace\Http\Controllers\SubscriptionController@test');

Route::group(['middleware' => 'workspace'], function () {
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
    Route::view('admin/do', 'workspace::do');
});
```

Next, if you take a look inside of your `routes/web.php`, you will see the following line:

```php
// Include Workspace Routes
Workspace::routes();
```

This line includes all the Workspace routes into your application.

<a name="api-routes"></a>
### Workspace API Routes

The Workspace API routes are located at `workspace/routes/api.php`. The contents of the file are as follows:

```php
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
```

Then, if you take a look inside of your `routes/api.php`, you will see the following line:

```php
// Include Workspace Routes
Workspace::api();
```

This line includes all the Workspace API routes into your application API.

<a name="workspace-middleware"></a>
### Workspace Middleware

Inside of the Workspace routes.php file you will see the following line:

```php
Route::group(['middleware' => 'workspace'], function () {
    Route::get('dashboard', '\Workspace\Http\Controllers\DashboardController@index')->name('workspace.dashboard');
});
```

This is the only current route protected by the `workspace` middleware. The `workspace` middleware is used to protect routes against users who no longer have an active subscription or are no longer on a trial. You can include your application routes inside of this middleware:

```php
Route::group(['middleware' => 'workspace'], function () {
    // Add your application routes here.
});
```

You may also wish to include this middleware in a single route:

```php
Route::get('awesome', 'AwesomeController@index')->middleware('workspace');
```

And now your application routes will be protected from users who are no longer active paying users.
