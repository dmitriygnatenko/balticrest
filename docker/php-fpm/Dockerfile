FROM ubuntu:20.04

WORKDIR /var/www/balticrest.ru

ENV DEBIAN_FRONTEND noninteractive

RUN apt-get update && apt-get install -y software-properties-common mc nano curl

RUN add-apt-repository ppa:ondrej/php

RUN mkdir /run/php

RUN apt-get install -y \
    php7.4-cli \
    php7.4-common \
    php7.4-mysql \
    php7.4-sqlite \
    php7.4-gd \
    php7.4-fpm \
    php7.4-cgi \
    php7.4-curl \
    php7.4-json \
    php7.4-mbstring \
    php7.4-intl \
    php7.4-zip \
    php7.4-xml \
    php-xdebug

RUN curl -sS https://getcomposer.org/installer | php && \
    chmod 755 composer.phar && \
    mv composer.phar /usr/local/bin/composer

RUN curl -sL https://deb.nodesource.com/setup_14.x | bash - && \
    apt-get install -y nodejs && \
    npm install --global gulp-cli

COPY config/cli/php.ini /etc/php/7.4/cli/php.ini
COPY config/fpm/php.ini /etc/php/7.4/fpm/php.ini
COPY config/fpm/php-fpm.conf /etc/php/7.4/fpm/php-fpm.conf
COPY config/fpm/pool.d/www.conf /etc/php/7.4/fpm/pool.d/www.conf
COPY config/fpm/xdebug.ini /etc/php/7.4/mods-available/xdebug.ini

EXPOSE 9000

CMD ["php-fpm7.4"]
