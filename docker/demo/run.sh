#!/bin/bash

# resolve php as localhost
echo "127.0.0.1 php" >> /etc/hosts

# run mysqld
/etc/init.d/mysql start
status=$?
if [ $status -ne 0 ]; then
  echo "Failed to start mysqld: $status"
  exit $status
fi

# init db
mysql -uroot -proot -e "CREATE DATABASE gber;" && mysql -uroot -proot gber < /etc/mysql/00_gber.sql
mysql -uroot -proot gber < /etc/mysql/demo_data.sql

# run php-fpm
/usr/local/sbin/php-fpm --daemonize
status=$?
if [ $status -ne 0 ]; then
  echo "Failed to start php-fpm: $status"
  exit $status
fi

# run nginx in foreground
/usr/sbin/nginx -g "daemon off;"
status=$?
if [ $status -ne 0 ]; then
  echo "Failed to start nginx: $status"
  exit $status
fi
