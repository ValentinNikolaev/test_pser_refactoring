FROM php:7.4-fpm-alpine
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

COPY . /app
WORKDIR /app

RUN composer install --no-ansi --no-progress -o --prefer-dist --classmap-authoritative \
    && composer dump-autoload -o  \
    && composer --version