FROM php:5.6.33-fpm

WORKDIR /var/www/html

# set timezone
ENV TZ Asia/Tokyo
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

# install packages
RUN echo "mysql-server mysql-server/root_password password root" | debconf-set-selections \
  && echo "mysql-server mysql-server/root_password_again password root" | debconf-set-selections \
  && apt-get update && apt-get install -y --no-install-recommends \
  libcurl3-dev \
  libmcrypt-dev \
  mysql-server \
  nginx \
  unzip \
  zip \
  && rm -rf /var/lib/apt/lists/*

# setup php-fpm
RUN docker-php-ext-install mysql curl mcrypt pdo_mysql
RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer
COPY ./gber/ /var/www/html
COPY ./docker/php/php.ini /usr/local/etc/php/php.ini
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install

# setup nginx
COPY ./docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY ./docker/nginx/location.conf /etc/nginx/conf.d/location.conf
COPY ./docker/nginx/certs/server.crt /etc/nginx/server.crt
COPY ./docker/nginx/certs/server.key /etc/nginx/server.key
RUN useradd nginx

# setup mysql
COPY ./docker/mysql/docker-entrypoint-initdb.d/00_gber.sql /etc/mysql/00_gber.sql
COPY ./docker/demo/demo_data.sql /etc/mysql/demo_data.sql
VOLUME /var/lib/mysql

# set env variables
ENV MYSQL_SERVER=127.0.0.1 MYSQL_USER=root MYSQL_PASSWORD=root MYSQL_DATABASE=gber IS_MAINTAINANCE=false IS_DEMO=true

COPY ./docker/demo/run.sh /run.sh
ENTRYPOINT /run.sh

EXPOSE 80 443
