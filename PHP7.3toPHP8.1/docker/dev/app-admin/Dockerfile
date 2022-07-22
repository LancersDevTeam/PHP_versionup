FROM ubuntu:latest

ENV APP_ROOT /var/www/lancers_admin
ENV LANG ja_JP.utf8
ENV LC_ALL ja_JP.utf8
WORKDIR $APP_ROOT

# Setup UTC+9
ENV TZ=Asia/Tokyo
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt update && \
    apt -y upgrade && \
    apt -y install \
    dnsmasq \
    curl \
    git \
    groff-base \
    language-pack-ja \
    nginx \
    openssl \
    php8.1 \
    php8.1-apcu \
    php8.1-curl \
    php8.1-fpm \
    php8.1-intl \
    php8.1-mbstring \
    php8.1-mysql \
    php8.1-opcache \
    php8.1-redis \
    php8.1-pdo-sqlite \
    php8.1-xdebug \
    php8.1-xml \
    php8.1-zip \
    python2 \
    python3.10-venv \
    supervisor \
    unzip \
    vim

## dnsmasq
RUN sed -ri \
    -e 's/^#user=/user=root/' \
    -e 's/^#group=/group=root/' \
    /etc/dnsmasq.conf

# nginx
COPY nginx/default.conf /etc/nginx/conf.d/default.conf
COPY nginx/fastcgi.conf /etc/nginx/conf.d/fastcgi.conf
COPY nginx/header.conf.include /etc/nginx/conf.d/header.conf.include
COPY nginx/krgn2.conf /etc/nginx/conf.d/krgn2.conf
COPY nginx/log.conf.http /etc/nginx/conf.d/log.conf.http
COPY nginx/nginx.conf /etc/nginx/nginx.conf

# PHP
COPY php/php.ini /etc/php/8.1/fpm/php.ini
COPY php/php.ini /etc/php/8.1/cli/php.ini
COPY php/apcu.ini /etc/php/8.1/mods-available/apcu.ini
COPY php/opcache.ini /etc/php/8.1/mods-available/opcache.ini
COPY php/xdebug.ini /etc/php/8.1/mods-available/xdebug.ini
# PHP-FPM
COPY php-fpm/php-fpm.conf /etc/php-fpm.conf
COPY php-fpm/www.conf /etc/php-fpm.d/www.conf

# copy src
COPY src/environment.php /usr/local/src/environment.php

# Install aws-cli
RUN curl https://bootstrap.pypa.io/get-pip.py -o /tmp/get-pip.py
RUN python3 /tmp/get-pip.py
RUN pip3 install awscli awscli-cwlogs --upgrade
RUN ln -s /usr/local/bin/aws /usr/bin/aws

# Install awslogs
RUN mkdir -p /etc/awslogs /var/lib/awslogs
COPY awslogs/awslogs.conf /etc/awslogs/awslogs.conf
COPY awslogs/awscli.conf /etc/awslogs/awscli.conf
COPY awslogs/awslogsd /usr/sbin/awslogsd
RUN chmod 755 /usr/sbin/awslogsd
RUN curl https://s3.amazonaws.com/aws-cloudwatch/downloads/latest/awslogs-agent-setup.py -o /tmp/awslogs-agent-setup.py
RUN python2 /tmp/awslogs-agent-setup.py -n -r ap-northeast-1 -c /etc/awslogs/awslogs.conf
RUN mv /var/awslogs/etc/* /etc/awslogs/ && rm -rf /etc/awslogs/aws.conf

## Setting supervisor
COPY supervisor/supervisord.conf /etc/supervisord.conf
COPY supervisor/app.conf /etc/supervisord.d/app.conf

# Service to run
CMD ["/usr/bin/supervisord"]
