#syntax=docker/dockerfile:1

FROM php:8.3-apache AS base

WORKDIR /var/www/html

ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

RUN apt update && apt install -y \
    npm && \
    install-php-extensions @composer \
    calendar \
    exif \
    ffi \
    gettext \
    imap \
    intl \
    mysqli \
    pcntl \
    pdo \
    pdo_mysql \
    phar \
    random \
    shmop \
    sockets \
    sysvmsg \
    sysvsem \
    sysvshm \
    xsl \
    opcache && \
    ln -s /usr/bin/php /usr/bin/php8.3

COPY . .

RUN composer install --optimize-autoloader && \
npm install && \
composer dump-autoload && \
php artisan optimize

RUN chown root:www-data -R /var/www && \
find /var/www -type f -exec chmod 664 {} + && \
find /var/www -type d -exec chmod 775 {} +

FROM base AS apache2

COPY ./env_conf/formtestovoe.conf /etc/apache2/sites-available

RUN a2enmod rewrite && \
a2ensite formtestovoe && \
php artisan key:generate

EXPOSE 80

CMD ["apachectl", "-D", "FOREGROUND"]