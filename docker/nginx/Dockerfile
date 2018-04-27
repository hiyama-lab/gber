FROM nginx:1.12.2

COPY ./docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY ./docker/nginx/location.conf /etc/nginx/conf.d/location.conf
COPY ./docker/nginx/certs/server.crt /etc/nginx/server.crt
COPY ./docker/nginx/certs/server.key /etc/nginx/server.key

COPY ./gber/ /var/www/html

ENV TZ Asia/Tokyo
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone
