FROM php:7.4.0-fpm

ADD docker/php-fpm74/php.ini /usr/local/etc/php/conf.d/php.ini

RUN apt-get update
RUN apt-get install -y libonig-dev libpq-dev
RUN docker-php-ext-install pdo_mysql mysqli mbstring

RUN apt-get update
RUN apt-get install -y libmemcached-dev zlib1g-dev
RUN pecl install memcached-3.1.3
RUN docker-php-ext-enable memcached opcache

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt-get -y install git

RUN mkdir -p /var/www/html/src/
VOLUME /var/www/html/src/
