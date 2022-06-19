FROM ubuntu:18.04

ENV APPLICATION_UID 1000
ENV APPLICATION_USER application
ENV APPLICATION_GID 1000
ENV APPLICATION_GROUP application
ENV DEBIAN_FRONTEND=noninteractive

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
    gcc \
    g++ \
    make \
  && locale-gen en_US.UTF-8

RUN curl -sL https://deb.nodesource.com/setup_8.x | bash -

RUN LC_ALL=en_US.UTF-8 add-apt-repository ppa:ondrej/php
RUN apt-get update
RUN apt-get install -y --fix-missing \
    php7.1-bcmath \
    php7.1-bz2 \
    php7.1-cli \
    php7.1-common \
    php7.1-curl \
    php7.1-cgi \
    php7.1-dev \
    php7.1-dom \
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
    php7.1-redis \
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
    php7.1-imagick \
    php7.1-apcu \
    php7.1-apcu-bc \
    nodejs \
    supervisor \
&& apt-get autoclean

RUN curl https://getcomposer.org/installer > composer-setup.php \
    && php composer-setup.php --version=1.10.17 \
    && mv composer.phar /usr/local/bin/composer \
    && rm composer-setup.php

RUN composer global require "hirak/prestissimo:^0.3"

RUN GOREPLACE_VERSION=1.1.2 \
    && wget -O /usr/local/bin/go-replace https://github.com/webdevops/go-replace/releases/download/$GOREPLACE_VERSION/gr-64-linux \
    && chmod +x /usr/local/bin/go-replace

RUN npm install -g yarn nuxt@2.10.0 which-pm-runs-cli @babel/preset-env@7.12.17 @babel/core@7.12.17

# Server Side Rendering
RUN curl -o /tmp/google-chrome-stable_current_amd64.deb \
        https://dl.google.com/linux/direct/google-chrome-stable_current_amd64.deb \
    && (dpkg -i /tmp/google-chrome-stable_current_amd64.deb || true)

RUN apt-get update --fix-missing && apt-get install -y -f
RUN apt-get install -y xvfb

ADD bootstrap.sh /bootstrap.sh
RUN chmod 700 /bootstrap.sh

ENV BOOTSTRAP /bootstrap.sh

RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*
RUN mkdir -p /run/php && touch /run/php/php7.1-fpm.sock
RUN ln -s /usr/bin/php /usr/local/bin/php

EXPOSE 80
EXPOSE 8080

WORKDIR /app

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

COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint
RUN chmod +x /usr/local/bin/docker-entrypoint

ENTRYPOINT ["docker-entrypoint"]

RUN /bootstrap.sh
CMD ["supervisord", "-c", "/etc/supervisor.conf", "--user", "root"]
