FROM php:7.1.0-fpm

# PHP Modules
RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng12-dev \
        zlib1g-dev \
        libbz2-dev \
        libxslt-dev \
        libicu-dev \
        libcurl3-dev \
        libc-client-dev \
        libkrb5-dev \
        libedit-dev \
        libxslt1-dev \
        libreadline6-dev \
        libtidy-dev \
        libxml++2.6-dev \
        g++ \
        unixodbc-dev \
        libxml2-dev \
        libaio-dev \
        freetds-dev \
        libssl-dev \
        openssl \
        curl \
        bzip2 \
        libfontconfig1 \
        libfontconfig1-dev \
        libexif-dev \
        libxrender1 \
        xvfb \
        wkhtmltopdf \
    && docker-php-ext-install -j$(nproc) iconv mcrypt \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
    && pecl install redis-3.1.0 \
    && pecl install xdebug-2.5.5 \
    && docker-php-ext-enable redis xdebug \
    && docker-php-ext-configure imap --with-imap --with-imap-ssl --with-kerberos \
    && docker-php-ext-install bcmath bz2 curl dom fileinfo exif gettext imap intl json \
        mbstring mysqli pcntl pdo pdo_mysql phar posix readline session \
        shmop simplexml soap sockets tidy tokenizer xml zip \
    && pecl install apcu \
    && pecl install apcu_bc-1.0.3 \
    && docker-php-ext-enable apcu --ini-name 10-docker-php-ext-apcu.ini \
    && docker-php-ext-enable apc --ini-name 20-docker-php-ext-apc.ini

# Google Chrome
RUN curl -o /tmp/google-chrome-stable_current_amd64.deb \
        https://dl.google.com/linux/direct/google-chrome-stable_current_amd64.deb \
    && (dpkg -i /tmp/google-chrome-stable_current_amd64.deb || true) \
    && apt-get install -y -f

# NodeJS Stuff
# RUN apt-get install -y nodejs npm \
RUN apt-get install -y python-software-properties git build-essential \
    && curl -sL https://deb.nodesource.com/setup_8.x | bash - \
    && apt-get install -y nodejs \
    # && ln -s /usr/bin/nodejs /usr/bin/node \
    && rm -rf /usr/local/lib/node_modules \
    && ln -s /usr/share/npm/node_modules /usr/local/lib/node_modules \
    && npm install -g --unsafe-perm \
        node-gyp bower grunt-cli jslint karma e2e babel-cli \
        phantomjs-prebuilt webpack sass node-sass less scss \
        yarn gulp-cli nuxt

# Supervisor
RUN apt-get install -y supervisor

# Clean Up
RUN apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# PHP config
COPY php-ini-overrides.ini /usr/local/etc/php/conf.d/php-ini-overrides.ini

WORKDIR /app

CMD ["supervisord", "-n", "-c", "/etc/supervisor/supervisord.conf"]
