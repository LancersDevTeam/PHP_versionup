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
    php8.1-xml \
    php8.1-zip \
    python2 \
    python3.10-venv \
    supervisor \
    unzip \
    vim


# nginx
COPY docker/prd/app-admin/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY docker/prd/app-admin/nginx/fastcgi.conf /etc/nginx/conf.d/fastcgi.conf
COPY docker/prd/app-admin/nginx/header.conf.include /etc/nginx/conf.d/header.conf.include
COPY docker/prd/app-admin/nginx/krgn2.conf /etc/nginx/conf.d/krgn2.conf
COPY docker/prd/app-admin/nginx/log.conf.http /etc/nginx/conf.d/log.conf.http
COPY docker/prd/app-admin/nginx/nginx.conf /etc/nginx/nginx.conf

# PHP
COPY docker/prd/app-admin/php/php.ini /etc/php.ini
COPY docker/prd/app-admin/php/10-opcache.ini /etc/php.d/10-opcache.ini
COPY docker/prd/app-admin/php/40-apcu.ini /etc/php.d/40-apcu.ini
# PHP-FPM
COPY docker/prd/app-admin/php-fpm/php-fpm.conf /etc/php-fpm.conf
COPY docker/prd/app-admin/php-fpm/www.conf /etc/php-fpm.d/www.conf

# copy src
COPY docker/prd/app-admin/src/environment.php /usr/local/src/environment.php

# make directory
RUN mkdir -p $APP_ROOT
COPY . $APP_ROOT

# php composer.phar install
RUN php composer.phar install

# Install aws-cli
RUN curl https://bootstrap.pypa.io/get-pip.py -o /tmp/get-pip.py
RUN python3 /tmp/get-pip.py
RUN pip3 install awscli awscli-cwlogs --upgrade
RUN ln -s /usr/local/bin/aws /usr/bin/aws

# Install awslogs
RUN mkdir -p /etc/awslogs /var/lib/awslogs
RUN mv awscli.conf /etc/awslogs/
COPY docker/prd/app-admin/awslogs/awslogs.conf /etc/awslogs/awslogs.conf
COPY docker/prd/app-admin/awslogs/awslogsd /usr/sbin/awslogsd
RUN chmod 755 /usr/sbin/awslogsd
RUN curl https://s3.amazonaws.com/aws-cloudwatch/downloads/latest/awslogs-agent-setup.py -o /tmp/awslogs-agent-setup.py
RUN python2 /tmp/awslogs-agent-setup.py -n -r ap-northeast-1 -c /etc/awslogs/awslogs.conf
RUN mv /var/awslogs/etc/* /etc/awslogs/ && rm -rf /etc/awslogs/aws.conf

# Install datadog-php-tracer
RUN cd /tmp && curl -OL "https://github.com/DataDog/dd-trace-php/releases/download/0.76.1/datadog-php-tracer_0.76.1_amd64.deb"
RUN dpkg -i /tmp/datadog-php-tracer_0.76.1_amd64.deb

## Setting supervisor
COPY docker/prd/app-admin/supervisor/supervisord.conf /etc/supervisord.conf
COPY docker/prd/app-admin/supervisor/app.conf /etc/supervisord.d/app.conf

RUN chmod 777 logs
RUN chmod 777 tmp

RUN touch logs/dummy.log

# Service to run
CMD ["/usr/bin/supervisord"]
