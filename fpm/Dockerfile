FROM php:8.2-fpm

COPY ./php.ini /usr/local/etc/php/conf.d/php-custom.ini

COPY ./zz-docker.conf /usr/local/etc/php-fpm.d/zz-docker.conf

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

RUN apt-get update && apt-get install zip unzip

WORKDIR /data

VOLUME /data

CMD ["php-fpm"]

