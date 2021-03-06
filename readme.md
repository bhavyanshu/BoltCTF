# Bolt CTF

Easily host jeopardy-style CTF competitions at your organization using this open source web app.

Powered by:
Laravel , VueJs , Bootstrap , AdminLTE


## Setup

* Clone this repository to your local/testing server.
* **Important** : Open `database/seeds/SudoUserTableSeeder.php` and change the username, email and password of the first super user.
* Make sure you have latest **php**, **composer** and **npm** setup.

  * **For Ubuntu (like) hosts**
    - Run `bash setup.sh` and answer questions asked by the script carefully. Once done, your app will be ready to deploy.

  * **For other hosts**
    - First you need to create a **.env** file. Copy-Paste and edit the following parameters in your file.

    ```
    APP_ENV=production
    APP_DEBUG=false
    APP_TZ=America/Chicago

    # Step: 1 - Generate using php artisan key:generate --env=production
    APP_KEY=

    # Step: 2- Change database connection settings below
    DB_HOST=CHANGE_DB_HOST
    DB_DATABASE=CHANGE_DB_NAME
    DB_USERNAME=CHANGE_DB_USER
    DB_PASSWORD=CHANGE_DB_PASSWORD

    CACHE_DRIVER=file
    SESSION_DRIVER=file
    QUEUE_DRIVER=sync

    # Step 3 (optional) - Set REDIS settings below
    REDIS_HOST=localhost
    REDIS_PASSWORD=null
    REDIS_PORT=6379

    # Step 4 - Setup SMTP mail server settings below
    MAIL_DRIVER=smtp
    MAIL_HOST=mailtrap.io
    MAIL_PORT=2525
    MAIL_USERNAME=null
    MAIL_PASSWORD=null
    MAIL_ENCRYPTION=null
    MAIL_FROM_ADDRESS=no-reply@example.com
    MAIL_FROM_NAME=no-reply

    # Step 5 - Generate encryption keys for Passport service
    # php artisan passport:keys
    ```

    - Next you will have to build it like any other laravel based app using following commands.

    ```bash
    composer install --optimize-autoloader
    npm install
    php artisan passport:keys
    php artisan key:generate
    npm run prod
    php artisan optimize
    php artisan cache:clear
    php artisan migrate --seed
    php artisan serve
    ```
  * **Docker**

    - Once you are done, the app will be accessible on port 80.

    ```
    # Build
    sudo docker-compose up --build

    # Set permissions
    sudo docker-compose exec app chgrp -R www-data storage bootstrap/cache
    sudo docker-compose exec app chmod -R ug+rwx storage bootstrap/cache

    # Run setup script
    sudo docker-compose bash setup.sh
    ```

## Hosting on nginx

Let's change the group ownership of the storage and bootstrap/cache directories to www-data.

    sudo chgrp -R www-data storage bootstrap/cache

Then recursively grant all permissions, including write and execute, to the group.

    sudo chmod -R ug+rwx storage bootstrap/cache
