FROM php:8.0-fpm

RUN apt-get update \
    && apt-get install -y cron unzip libzip-dev zlib1g-dev libpq-dev git libicu-dev libxml2-dev gnupg apt-utils python3-pip wget libfreetype6-dev libjpeg62-turbo-dev libpng-dev libmagickwand-dev libmagick++-dev

RUN docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql \
    && docker-php-ext-install zip xml \
    && docker-php-ext-install opcache \
    && docker-php-ext-install sockets \
    && docker-php-ext-install bcmath \
    && docker-php-ext-install soap \
    && docker-php-ext-install calendar \
    && docker-php-ext-install exif \
    && docker-php-ext-install pcntl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && pecl install apcu \
    && pecl install imagick \
  	&& docker-php-ext-enable apcu \
    && docker-php-ext-enable imagick

RUN apt-get clean

# Set timezone
RUN rm /etc/localtime
RUN ln -s /usr/share/zoneinfo/Europe/Vilnius /etc/localtime
RUN "date"
RUN mkdir -p  /var/log/supervisor

COPY php.ini /usr/local/etc/php/conf.d/50-setting.ini
#COPY php-fpm-fpm.conf  /usr/local/etc/php-fpm-fpm.d/php-fpm-fpm.conf

WORKDIR /var/www/symfony

RUN curl --insecure https://getcomposer.org/composer.phar -o /usr/bin/composer && chmod +x /usr/bin/composer
