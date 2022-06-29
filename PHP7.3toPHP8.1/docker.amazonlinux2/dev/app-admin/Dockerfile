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
    dnsmasq \
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
    php81-php-pecl-xdebug3 \
    php81-php-redis \
    php81-php-soap \
    php81-php-xml \
    php81-php-zip \
    && \
    yum clean all

RUN alternatives --install /usr/bin/php php /usr/bin/php81 1

# Setup UTC+9
RUN unlink /etc/localtime
RUN ln -s /usr/share/zoneinfo/Japan /etc/localtime

# awslogs need pip install requests
RUN pip install --upgrade pip
RUN pip install requests

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
COPY awslogs/awscli.conf /etc/awslogs/awscli.conf
COPY awslogs/awslogs.conf /etc/awslogs/awslogs.conf
COPY logrotate/awslogs /etc/logrotate.d/awslogs

# PHP
COPY php/php.ini /etc/opt/remi/php81/php.ini
COPY php/10-opcache.ini /etc/opt/remi/php81/php.d/10-opcache.ini
COPY php/15-xdebug.ini /etc/opt/remi/php81/php.d/15-xdebug.ini
COPY php/40-apcu.ini /etc/opt/remi/php81/php.d/40-apcu.ini
# PHP-FPM
COPY php-fpm/php-fpm.conf /etc/opt/remi/php81/php-fpm.conf
COPY php-fpm/www.conf /etc/opt/remi/php81/php-fpm.d/www.conf

## Setting supervisor
COPY supervisor/supervisord.conf /etc/supervisord.conf
COPY supervisor/app.conf /etc/supervisord.d/app.conf

# Service to run
CMD ["/usr/bin/supervisord"]
