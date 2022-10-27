FROM php:8.1-fpm-alpine

ENV LANG en_US.UTF-8
ENV LANGUAGE en_US:en
ENV LC_ALL en_US.UTF-8

EXPOSE 80
WORKDIR /application
RUN apk --update add \
        git \
        curl \
        nginx \
        zip \
        supervisor \
    && rm -rf /var/cache/apk/*


COPY docker_files/nginx.conf /etc/nginx/nginx.conf
COPY docker_files/start_nginx.sh /application/start_nginx.sh
COPY docker_files/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

RUN ln -s /usr/bin/php8 /usr/bin/php

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin -- --filename=composer && \
    docker-php-ext-install pdo_mysql && \ 
    docker-php-ext-enable pdo_mysql

COPY . /application
RUN cd /application && composer install && composer update && chown -R www-data:www-data /application && chmod +x /application/start_nginx.sh

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
