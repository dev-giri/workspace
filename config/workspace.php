<?php

return [

	'profile_fields' => [
		'about'
	],

	'api' => [
		'auth_token_expires' 	=> 60,
		'key_token_expires'		=> 1,
	],

	'auth' => [
		'min_password_length' => 5,
	],

	'user_model' => \App\Models\User::class,
	'show_docs' => env('WORKSPACE_DOCS', true),
    'demo' => env('WORKSPACE_DEMO', false),
    'dev_bar' => env('WORKSPACE_BAR', false),

    'paddle' => [
        'vendor' => env('PADDLE_VENDOR_ID', ''),
        'auth_code' => env('PADDLE_VENDOR_AUTH_CODE', ''),
        'env' => env('PADDLE_ENV', 'sandbox')
    ],

    'ban_app_name' => [
		'Workspace', 'app', 'api', 'admin', 'test', 'me', 'i'
	],

    'default_modules' => [
        'Todo' => true,
        'Support' => true
    ]

];