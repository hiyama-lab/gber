FROM mysql:5.6

COPY ./docker/mysql/docker-entrypoint-initdb.d/*.sql /docker-entrypoint-initdb.d/
COPY ./docker/mysql/my.cnf /etc/mysql/my.cnf
