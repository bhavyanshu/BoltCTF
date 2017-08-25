checkProgram() {
  if ! [ -x "$(command -v $1)" ]; then
    echo "Error: $1 is not installed. Please install $1 and make sure the path is set in your environment." >&2
    exit 1
  fi
}

printMessage() {
  RED='\033[1;36m'
  NC='\033[0m'
  printf "$RED$1$NC\n"
  sleep 2
}

printMessage "########### Welcome to BoltCTF Setup #############"

printMessage "\nChecking for required programs and utilities..."
checkProgram php
checkProgram composer
checkProgram npm

printMessage "\nFetching and installing all dependencies..."
composer install --optimize-autoloader
npm install

printMessage "\nProcessing .env file..."
read -p "Would you like to keep your old .env file? (y/n) [y]" -n 1 -r
if [[ $REPLY =~ ^[Nn]$ ]]
then
    rm .env
    printMessage "\nProvide configuration for the database..."
    read -p "Enter Your Database Name: "  dbname
    read -p "Enter Your Database User Name: "  dbuser
    read -p "Enter Your Database Password: "  dbpassword
    read -p "Enter Your Database Host name: "  dbhostname

    echo -e "APP_ENV=production\nAPP_DEBUG=false\nAPP_KEY=\n" >> .env

    echo -e "DB_HOST=$dbhostname\nDB_DATABASE=$dbname\nDB_USERNAME=$dbuser\nDB_PASSWORD=$dbpassword\n" >> .env

    echo -e "CACHE_DRIVER=file\nSESSION_DRIVER=file\nQUEUE_DRIVER=sync\n" >> .env

    echo -e "REDIS_HOST=localhost\nREDIS_PASSWORD=null\nREDIS_PORT=6379\n" >> .env

    echo -e "MAIL_DRIVER=smtp\nMAIL_HOST=mailtrap.io\nMAIL_PORT=2525\nMAIL_USERNAME=null\nMAIL_PASSWORD=null\nMAIL_ENCRYPTION=null\n" >> .env
fi

printMessage "\nGenerating encryption keys.."
printMessage "Type yes if running for the first time, else press enter key to skip"
php artisan passport:keys
php artisan key:generate
printMessage "\nYou can edit your .env file if anything changes in future."

printMessage "\nBuilding asset libraries"
npm rebuild node-sass --force
npm run prod

printMessage "\nRunning optimizer and migrator."

php artisan optimize
php artisan cache:clear

printMessage "\nDATABASE MIGRATION : Type yes if running for the first time else press enter key to skip"
php artisan migrate:refresh --seed

printMessage "\nAll Done. Run 'php artisan serve' to launch the app or check the README file for NGINX server setup."
