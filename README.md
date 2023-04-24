# workspace
Workspace will help you to build Multi-Tenant SAAS application. Out of the box Administration, Modules (Plugin) manager, Themes manager, BREAD (CRUD) operations, Media manager, Menu builder, Authentication, Subscriptions, Invoices, Announcements, User Profiles, API, and so much more!

Workspace is meant to serve as a Multi-Tenant starter kit, that will save you hundreds of hours, which is used to quickly create your SAAS app. When your application continues to grow you may wish to extend you application beyond Workspace's features. In the events that your features conflict with the core features, support may be limited.


# Installation on localhost

To install Workspace, you'll want to clone or download this repo:

git clone https://github.com/dev-giri/workspace.git project_name


Next, we can install Workspace with these 3 simple steps:

1. Create a New Database
We'll need to utilize a MySQL database during the installation. For the following stage, you'll need to create a new database and preserve the credentials.

CREATE DATABASE workspace_super;
CREATE USER 'root'@'localhost' IDENTIFIED BY '';
GRANT ALL PRIVILEGES ON root.* TO 'workspace_super'@'localhost';


2. Copy the .env.example file
We need to specify our Environment variables for our application. You will see a file named .env.example, you will need to duplicate that file and rename it to .env.
Then, open up the .env file and update your DB_DATABASE, DB_USERNAME, and DB_PASSWORD in the appropriate fields. You will also want to update the APP_URL to the URL of your application.

APP_URL=http://localhost:8080

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=workspace_super
DB_USERNAME=root
DB_PASSWORD=


3. Add Composer Dependencies
To install all composer dependencies through the following command:

composer install

Note: If you have problem with install workspace try to run the command 

php artisan workspace:install [APP_NAME]



# Run the server on localhost

To run Workspace, which we can accomplish by running the following command:

php artisan serve --port=8080

# Run the websocket (terminal 2)
set WEBSOCKETS_SERVER=true in .env
php artisan websockets:serve


# Creating a new tenant application 

php artisan tenant:create [HOST_NAME]

