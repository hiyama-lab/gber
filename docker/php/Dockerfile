FROM php:5.6.33-fpm

RUN apt-get update && apt-get install -y libcurl3-dev libmcrypt-dev
RUN docker-php-ext-install mysql curl mcrypt pdo_mysql

RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

COPY ./gber/ /var/www/html
COPY ./docker/php/php.ini /usr/local/etc/php/php.ini

ENV TZ Asia/Tokyo
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone
