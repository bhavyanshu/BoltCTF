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

printMessage "\nCHECKING: required programs and utilities..."
checkProgram php
checkProgram composer
checkProgram npm

printMessage "\nINSTALLING: dependencies..."
composer install --optimize-autoloader
npm install

printMessage "\nCHECKING: .env file..."
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

######### Excuse the weird indentation (silly cat!) #########
cat > .env << EOF
APP_ENV=production
APP_TZ=America/Chicago
APP_DEBUG=false
APP_KEY=

DB_HOST=$dbhostname
DB_DATABASE=$dbname
DB_USERNAME=$dbuser
DB_PASSWORD=$dbpassword

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_DRIVER=sync

REDIS_HOST=localhost
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_DRIVER=smtp
MAIL_HOST=mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=no-reply@example.com
MAIL_FROM_NAME=no-reply

EOF
######### Excuse the weird indentation (silly cat!) #########

  fi
fi

printMessage "\nENCRYPTION KEYS: Type yes if running for the first time, else press enter key to skip"
php artisan passport:keys
php artisan key:generate

printMessage "\nBUILDING: asset libraries"
npm run prod

printMessage "\nRunning optimizer and migrator."

php artisan optimize
php artisan cache:clear

printMessage "\nType yes if running for the first time else press enter key to skip"
php artisan migrate --seed

printMessage "\nAll Done. Run 'php artisan serve' to launch the app."
