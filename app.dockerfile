FROM php:7.0.4-fpm

RUN apt-get update && apt-get upgrade -yq && apt-get install -y libmcrypt-dev \
  mysql-client libmagickwand-dev --no-install-recommends \
  && docker-php-ext-install mcrypt pdo_mysql mbstring zip

# Install dependencies
RUN apt-get install -yq g++ libssl-dev apache2-utils curl git python make nano

# install latest Node.js and npm
RUN curl -sL https://deb.nodesource.com/setup_6.x | bash
RUN apt-get install -yq nodejs

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
