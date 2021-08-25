FROM php:7.4-fpm-alpine AS balticrest-php-fpm

RUN apk add --no-cache --virtual build-dependencies $PHPIZE_DEPS

RUN pecl install xdebug

RUN apk add --no-cache coreutils \
    libzip-dev \
    freetype-dev \
    oniguruma-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    icu-dev \
    git \
    nodejs \
    npm \
    mc

RUN docker-php-ext-install \
    zip \
    intl \
    gd \
    bcmath \
    sockets \
    mbstring \
    pdo \
    pdo_mysql \
    && docker-php-ext-configure gd

RUN apk del build-dependencies

RUN curl -sS https://getcomposer.org/installer | php && \
    chmod 755 composer.phar && \
    mv composer.phar /usr/local/bin/composer

RUN npm install --global gulp-cli

COPY config/override.ini /usr/local/etc/php/conf.d/
COPY config/xdebug.ini /usr/local/etc/php/conf.d/

RUN rm /usr/local/etc/php-fpm.d/*.conf
COPY config/pool.conf /usr/local/etc/php-fpm.d/

CMD ["sh", "-c", "php-fpm -F"]

WORKDIR /var/www/balticrest.ru
EXPOSE 9000
