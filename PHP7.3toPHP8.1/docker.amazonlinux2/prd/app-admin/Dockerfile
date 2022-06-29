FROM amazonlinux:2

ENV APP_ROOT /var/www/lancers_admin
ENV LANG ja_JP.utf8
ENV LC_ALL ja_JP.utf8
WORKDIR $APP_ROOT

# Install amazon-linux-extras
RUN amazon-linux-extras install -y epel
RUN amazon-linux-extras enable nginx1

RUN yum update -y && \
    yum -y install \
    awslogs \
    gd \
    git \
    glibc-langpack-ja \
    keyutils-libs-devel \
    libXpm \
    libedit-devel \
    libpng \
    libselinux-devel \
    libtiff \
    libtool \
    libverto-devel \
    libwebp \
    libxslt \
    procps \
    python-pip \
    supervisor \
    unzip \
    vim \
    xz-devel \
    yum-utils \
    zlib-devel

RUN amazon-linux-extras install -y \
    nginx1

# Install remi
RUN yum -y install \
    http://rpms.remirepo.net/enterprise/remi-release-7.rpm

# Install php common
RUN yum -y install \
    php81 \
    php81-php-cli \
    php81-php-common \
    php81-php-devel \
    php81-php-fpm \
    php81-php-gd \
    php81-php-intl \
    php81-php-mbstring \
    php81-php-mysqlnd \
    php81-php-opcache \
    php81-php-pear \
    php81-php-pecl-apcu \
    php81-php-process \
    php81-php-redis \
    php81-php-soap \
    php81-php-xml \
    php81-php-zip \
    https://github.com/DataDog/dd-trace-php/releases/download/0.70.1/datadog-php-tracer-0.70.1-1.x86_64.rpm \
    && \
    yum clean all

RUN alternatives --install /usr/bin/php php /usr/bin/php81 1

# Setup UTC+9
RUN unlink /etc/localtime
RUN ln -s /usr/share/zoneinfo/Japan /etc/localtime

# awslogs need pip install requests
RUN pip install --upgrade pip
RUN pip install requests

# make directory
RUN mkdir -p $APP_ROOT
COPY . $APP_ROOT

# php composer.phar install
RUN php composer.phar install

# copy src
COPY docker/prd/app-admin/src/environment.php /usr/local/src/environment.php

# nginx
COPY docker/prd/app-admin/nginx/default.conf /etc/nginx/conf.d/default.conf
COPY docker/prd/app-admin/nginx/fastcgi.conf /etc/nginx/conf.d/fastcgi.conf
COPY docker/prd/app-admin/nginx/header.conf.include /etc/nginx/conf.d/header.conf.include
COPY docker/prd/app-admin/nginx/krgn2.conf /etc/nginx/conf.d/krgn2.conf
COPY docker/prd/app-admin/nginx/log.conf.http /etc/nginx/conf.d/log.conf.http
COPY docker/prd/app-admin/nginx/nginx.conf /etc/nginx/nginx.conf

# awslogs
RUN mv awscli.conf /etc/awslogs/
COPY docker/prd/app-admin/awslogs/awslogs.conf /etc/awslogs/awslogs.conf
COPY docker/prd/app-admin/logrotate/awslogs /etc/logrotate.d/awslogs

# PHP
COPY docker/prd/app-admin/php/php.ini /etc/opt/remi/php81/php.ini
COPY docker/prd/app-admin/php/10-opcache.ini /etc/opt/remi/php81/php.d/10-opcache.ini
COPY docker/prd/app-admin/php/40-apcu.ini /etc/opt/remi/php81/php.d/40-apcu.ini
# PHP-FPM
COPY docker/prd/app-admin/php-fpm/php-fpm.conf /etc/opt/remi/php81/php-fpm.conf
COPY docker/prd/app-admin/php-fpm/www.conf /etc/opt/remi/php81/php-fpm.d/www.conf

## Setting supervisor
COPY docker/prd/app-admin/supervisor/supervisord.conf /etc/supervisord.conf
COPY docker/prd/app-admin/supervisor/app.conf /etc/supervisord.d/app.conf

RUN chmod 777 logs
RUN chmod 777 tmp

RUN touch logs/dummy.log

# Service to run
CMD ["/usr/bin/supervisord"]
