FROM php:5.6.33-fpm

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y --no-install-recommends \
  libcurl3-dev \
  libmcrypt-dev \
  unzip \
  zip \
  && rm -rf /var/lib/apt/lists/*
RUN docker-php-ext-install mysql curl mcrypt pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

COPY ./gber/ /var/www/html
COPY ./docker/php/php.ini /usr/local/etc/php/php.ini

ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install

ENV TZ Asia/Tokyo
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone
