FROM ubuntu:16.04

ENV APPLICATION_UID 1000
ENV APPLICATION_USER application
ENV APPLICATION_GID 1000
ENV APPLICATION_GROUP application

RUN apt-get clean && apt-get -y update && apt-get install -y \
    locales \
    curl \
    wget \
    curl \
    apt-utils \
    xz-utils \
    libxrender-dev \
    git \
    lsb-release \
    acl \
    vim \
    software-properties-common \
    git \
    nginx \
    nmap \
  && locale-gen en_US.UTF-8

RUN curl -sL https://deb.nodesource.com/setup_8.x | bash -

RUN LC_ALL=en_US.UTF-8 add-apt-repository ppa:ondrej/php
RUN apt-get update
RUN apt-get install -y \
    php7.1-bcmath \
    php7.1-bz2 \
    php7.1-cli \
    php7.1-common \
    php7.1-curl \
    php7.1-cgi \
    php7.1-dev \
    php7.1-fpm \
    php7.1-gd \
    php7.1-gmp \
    php7.1-imap \
    php7.1-intl \
    php7.1-json \
    php7.1-ldap \
    php7.1-mbstring \
    php7.1-mcrypt \
    php7.1-mysql \
    php7.1-odbc \
    php7.1-opcache \
    php7.1-readline \
    php7.1-soap \
    php7.1-sqlite3 \
    php7.1-xml \
    php7.1-xmlrpc \
    php7.1-xsl \
    php7.1-zip \
    libsqlite3-dev \
    libicu-dev \
    libldb-dev \
    libpng-dev \
    php-imagick \
    php-redis \
    php-apcu \
    php-pear \
    nodejs \
    supervisor \
&& apt-get autoclean

RUN curl https://getcomposer.org/installer > composer-setup.php \
    && php composer-setup.php \
    && mv composer.phar /usr/local/bin/composer \
    && rm composer-setup.php

RUN composer global require "hirak/prestissimo:^0.3" \
    && pecl install apcu_bc-beta

RUN GOREPLACE_VERSION=1.1.2 \
    && wget -O /usr/local/bin/go-replace https://github.com/webdevops/go-replace/releases/download/$GOREPLACE_VERSION/gr-64-linux \
    && chmod +x /usr/local/bin/go-replace

RUN npm install -g yarn nuxt

ADD config/nginx/default.conf /etc/nginx/sites-available/default
ADD config/php/pool.d/dev.conf /etc/php/7.1/fpm/pool.d/www.conf

ADD config/php/php.ini /etc/php/7.1/fpm/conf.d/99-php.ini
ADD config/php/php.ini /etc/php/7.1/cli/conf.d/99-php.ini

ADD config/supervisor/supervisor.conf /etc/supervisor.conf
ADD config/supervisor/services.conf /etc/supervisor/conf.d/nginx-php.conf

RUN go-replace --mode=line --regex \
    -s '^[\s;]*user[\s]*='  -r "user = $APPLICATION_USER" \
    -s '^[\s;]*group[\s]*=' -r "group = $APPLICATION_GROUP" \
    --path=/etc/php/7.1/fpm/pool.d/ \
    --path-pattern='*.conf'

ADD bootstrap.sh /bootstrap.sh
RUN chmod 700 /bootstrap.sh

ENV BOOTSTRAP /bootstrap.sh

RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*
RUN mkdir -p /run/php && touch /run/php/php7.1-fpm.sock
RUN ln -s /usr/bin/php /usr/local/bin/php

EXPOSE 80
EXPOSE 8080

WORKDIR /app

RUN /bootstrap.sh
CMD ["supervisord", "-c", "/etc/supervisor.conf", "--user", "root"]
