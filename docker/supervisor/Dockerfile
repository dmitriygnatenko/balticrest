FROM balticrest-php-fpm

RUN apk add --no-cache supervisor

RUN mkdir -p /var/log/supervisor

COPY config/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

WORKDIR /var/www/balticrest.ru

CMD ["/usr/bin/supervisord"]
