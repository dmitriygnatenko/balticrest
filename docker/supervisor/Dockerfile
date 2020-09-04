FROM balticrest-php-fpm

WORKDIR /var/www/balticrest.ru

RUN apt-get update && apt-get install -y supervisor

RUN mkdir -p /var/log/supervisor

COPY config/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

CMD ["/usr/bin/supervisord"]
