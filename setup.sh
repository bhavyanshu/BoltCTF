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
if [ -e .env ]; then
  read -p "Would you like to keep your old .env file? (y/n) [y]" -n 1 -r
  if [[ $REPLY =~ ^[Nn]$ ]]
  then
      rm .env
      printMessage "Provide configuration for the database..."
      read -p "Enter Your Database Name: "  dbname
      read -p "Enter Your Database User Name: "  dbuser
      read -p "Enter Your Database Password: "  dbpassword
      read -p "Enter Your Database Host name: "  dbhostname

      echo "APP_ENV=production
      APP_DEBUG=false
      APP_KEY=
      " >> .env

      echo "DB_HOST=$dbhostname
      DB_DATABASE=$dbname
      DB_USERNAME=$dbuser
      DB_PASSWORD=$dbpassword
      " >> .env

      echo "CACHE_DRIVER=file
      SESSION_DRIVER=file
      QUEUE_DRIVER=sync
      " >> .env

      echo "REDIS_HOST=localhost
      REDIS_PASSWORD=null
      REDIS_PORT=6379
      " >> .env

      echo "MAIL_DRIVER=smtp
      MAIL_HOST=mailtrap.io
      MAIL_PORT=2525
      MAIL_USERNAME=null
      MAIL_PASSWORD=null
      MAIL_ENCRYPTION=null
      " >> .env
  fi
fi

printMessage "\nGenerating encryption keys.."
printMessage "Type yes if running for the first time, else press enter key to skip"
php artisan passport:keys
php artisan key:generate
printMessage "\nYou can edit your .env file if anything changes in future."

printMessage "\nBuilding asset libraries"
npm run prod

printMessage "\nRunning optimizer and migrator."

php artisan optimize
php artisan cache:clear

printMessage "\nType yes if running for the first time else press enter key to skip"
php artisan migrate --seed

printMessage "\nAll Done. Run 'php artisan serve' to launch the app."
