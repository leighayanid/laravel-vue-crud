FROM composer:2 AS build

WORKDIR /app

COPY . .

RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader
RUN apk add --no-cache nodejs npm
RUN npm ci
RUN npm run build
RUN rm -rf node_modules

FROM serversideup/php:8.4-fpm-nginx

WORKDIR /var/www/html

ENV APP_ENV=production
ENV APP_DEBUG=false
ENV LOG_CHANNEL=stderr
ENV AUTORUN_ENABLED=true
ENV PHP_OPCACHE_ENABLE=1
ENV SSL_MODE=off

COPY --chown=www-data:www-data --from=build /app /var/www/html

USER www-data
