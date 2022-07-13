FROM php:8.1-fpm-alpine

ENV APP_ROOT /var/www/lancers_admin
ENV LANG ja_JP.utf8
ENV LC_ALL ja_JP.utf8
WORKDIR $APP_ROOT

# Add mirror repo
# Alpineがデフォルトで参照するリポジトリがダウンしてもエラーにならないようにする
RUN echo "https://alpine.cs.nctu.edu.tw/v3.15/main" >> /etc/apk/repositories; \
    echo "https://alpine.cs.nctu.edu.tw/v3.15/community" >> /etc/apk/repositories; \
    echo "http://alpine.northrepo.ca/v3.15/main" >> /etc/apk/repositories; \
    echo "http://alpine.northrepo.ca/v3.15/community" >> /etc/apk/repositories;

# Setup UTC+9
RUN apk --update add tzdata && \
    cp /usr/share/zoneinfo/Asia/Tokyo /etc/localtime && \
    apk del tzdata && \
    rm -rf /var/cache/apk/*

# install packages
RUN apk update && \
    apk upgrade && \
    apk add --update --no-cache  \
    autoconf \
    bash \
    dnsmasq \
    freetype-dev \
    g++ \
    gcc \
    icu-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libxml2-dev \
    libwebp-dev \
    make \
    nginx \
#    py3-pip \
    supervisor \
    zlib-dev

# PHP
COPY php/php.ini /usr/local/etc/php/php.ini
COPY php/docker-php-ext-pdo_mysql.ini /usr/local/etc/php/conf.d/docker-php-ext-pdo_mysql.ini

RUN docker-php-ext-install intl pdo_mysql

# PHP-FPM
RUN rm -f /usr/local/etc/php-fpm.conf.default
RUN rm -f /usr/local/etc/php-fpm.d/zz-docker.conf
RUN mkdir -m 777 /var/log/php-fpm
COPY php-fpm/php-fpm.conf /usr/local/etc/php-fpm.conf
COPY php-fpm/www.conf /usr/local/etc/php-fpm.d/www.conf

# pecl install
RUN pecl install apcu redis xdebug
RUN docker-php-ext-enable apcu redis xdebug
COPY php/docker-php-ext-xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

## Install dnsmasq
RUN sed -ri \
    -e 's/^#user=/user=root/' \
    -e 's/^#group=/group=root/' \
    /etc/dnsmasq.conf

# copy src
COPY src/environment.php /usr/local/src/environment.php

# nginx
COPY nginx/default.conf /etc/nginx/conf.d/default.conf
COPY nginx/fastcgi.conf /etc/nginx/conf.d/fastcgi.conf
COPY nginx/header.conf.include /etc/nginx/conf.d/header.conf.include
COPY nginx/krgn2.conf /etc/nginx/conf.d/krgn2.conf
COPY nginx/log.conf.http /etc/nginx/conf.d/log.conf.http
COPY nginx/nginx.conf /etc/nginx/nginx.conf

# awslogs
#RUN pip install awslogs
#COPY awslogs/awscli.conf /etc/awslogs/awscli.conf
#COPY awslogs/awslogs.conf /etc/awslogs/awslogs.conf
#COPY logrotate/awslogs /etc/logrotate.d/awslogs

## Setting supervisor
COPY supervisor/supervisord.conf /etc/supervisord.conf
COPY supervisor/app.conf /etc/supervisord.d/app.conf

# Service to run
CMD ["/usr/bin/supervisord"]
