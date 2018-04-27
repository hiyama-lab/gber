FROM mysql:5.6

COPY ./docker/mysql/docker-entrypoint-initdb.d/00_gber.sql /docker-entrypoint-initdb.d/00_gber.sql
COPY ./docker/mysql/my.cnf /etc/mysql/my.cnf
