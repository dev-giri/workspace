<?php

$pages = [

    'welcome' => 'welcome.md',
    'installation' => 'installation.md',
    'configurations' => 'configurations.md',
    'upgrading' => 'upgrading.md',

    'features/authentication' => 'features/authentication.md',
    'features/user-profiles' => 'features/user-profiles.md',
    'features/user-impersonation' => 'features/user-impersonation.md',
    'features/billing' => 'features/billing.md',
    'features/subscription-plans' => 'features/subscription-plans.md',
    'features/user-roles' => 'features/user-roles.md',
    'features/notifications' => 'features/notifications.md',
    'features/announcements' => 'features/announcements.md',
    'features/blog' => 'features/blog.md',
    'features/api' => 'features/api.md',
    'features/admin' => 'features/admin.md',
    'features/themes' => 'features/themes.md',

    'concepts/routing' => 'concepts/routing.md',
    'concepts/themes' => 'concepts/themes.md',
    'concepts/structure' => 'concepts/structure.md',


];

$menu_items = [

    (object)[
        'title' => 'Getting Started',
        'sections' => (object)[
            (object)[
                'title' => 'Welcome',
                'url' => '/docs'
            ],
            (object)[
                'title' => 'Installation',
                'url' => '/docs/installation'
            ],
            (object)[
                'title' => 'Configurations',
                'url' => '/docs/configurations'
            ],
            (object)[
                'title' => 'Upgrading',
                'url' => '/docs/upgrading'
            ]
        ]
    ],
    (object)[
        'title' => 'Features',
        'sections' => (object)[
            (object)[
                'title' => 'Authentication',
                'url' => '/docs/features/authentication'
            ],
            (object)[
                'title' => 'User Profiles',
                'url' => '/docs/features/user-profiles'
            ],
            (object)[
                'title' => 'User Impersonation',
                'url' => '/docs/features/user-impersonation'
            ],
            (object)[
                'title' => 'Billing',
                'url' => '/docs/features/billing'
            ],
            (object)[
                'title' => 'Subscription Plans',
                'url' => '/docs/features/subscription-plans'
            ],
            (object)[
                'title' => 'User Roles',
                'url' => '/docs/features/user-roles'
            ],
            (object)[
                'title' => 'Notifications',
                'url' => '/docs/features/notifications'
            ],
            (object)[
                'title' => 'Announcements',
                'url' => '/docs/features/announcements'
            ],
            (object)[
                'title' => 'Blog',
                'url' => '/docs/features/blog'
            ],
            (object)[
                'title' => 'API',
                'url' => '/docs/features/api'
            ],
            (object)[
                'title' => 'Admin',
                'url' => '/docs/features/admin'
            ],
            (object)[
                'title' => 'Themes',
                'url' => '/docs/features/themes'
            ]
        ]
    ],

    (object)[
        'title' => 'Basic Concepts',
        'sections' => (object)[
            (object)[
                'title' => 'Routing',
                'url' => '/docs/concepts/routing'
            ],
            (object)[
                'title' => 'Themes',
                'url' => '/docs/concepts/themes'
            ],
            (object)[
                'title' => 'Structure',
                'url' => '/docs/concepts/structure'
            ]
        ]
    ],

    (object)[
        'title' => 'Resources',
        'sections' => (object)[
            (object)[
                'title' => 'Videos',
                'url' => 'https://workspace.in/course/workspace',
                'attributes' => 'target="_blank"'
            ],
            (object)[
                'title' => 'Support',
                'url' => 'https://workspace.in/workspace#pro',
                'attributes' => 'target="_blank"'
            ],
            (object)[
                'title' => 'Laravel',
                'url' => 'https://laravel.com',
                'attributes' => 'target="_blank"'
            ],
            (object)[
                'title' => 'Voyager',
                'url' => 'https://voyager.workspace.in',
                'attributes' => 'target="_blank"'
            ],
            (object)[
                'title' => 'DigitalOcean',
                'url' => 'https://digitalocean.com',
                'attributes' => 'target="_blank"'
            ]
        ]
    ],

];


$uri = trim(str_replace('/docs', '', Request::getRequestUri()), '/');

// Get the requested page and check if we are at home.
$home = false;
if($uri == "")
{
    $page = 'welcome.md';
    $home = true;
}
else
{
    if( !isset( $pages[$uri] ) ){
        abort(404);
    }
    $page = $pages[$uri];
}

$title = 'Welcome to Workspace';

foreach($menu_items as $item){
    foreach($item->sections as $index => $section){
        if(Request::getRequestUri() && Request::getRequestUri() == $section->url){
            $title = $section->title . ' - Workspace will help you to build Multi-Tenant SAAS application. Out of the box Administration, Module (Plugin) manager, Theme manager, BREAD (CRUD) operations, Media manager, Menu builder, Authentication, Subscriptions, Invoices, Announcements, User Profiles, API, and so much more!';
        }
    }
}

$file = file_get_contents(  base_path() . '/core/docs/' . $page );

?>
