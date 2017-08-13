# Bolt CTF

Easily host jeopardy-style CTF competitions at your organization using this open source web app.

Powered by:
Laravel , VueJs , Bootstrap , AdminLTE


## Setup

* Clone this repository to your local/testing server.
* I**Important** : Open `database/seeds/SudoUserTableSeeder.php` and change the username, email and password of the first super user.
* Make sure you have latest **php**, **composer** and **npm** setup.
  * For Ubuntu (like) hosts
    - Run `bash setup.sh` and answer questions asked by the script carefully. Once done, your app will be ready to deploy.

  * For other hosts
    - First you need to create a **.env** file. Copy-Paste and edit the following parameters in your file.

    ```
    APP_ENV=production
    APP_DEBUG=false

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
