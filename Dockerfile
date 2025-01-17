FROM php:8.3-fpm-alpine

RUN apk add --update linux-headers

RUN apk update \
    && apk add --no-cache autoconf g++ make \
        openssl openssl-dev \
        build-base pkgconf

RUN docker-php-ext-install \
    pdo_mysql \
    mysqli

# Instalar y habilitar Xdebug
RUN pecl install xdebug-3.3.1 \
    && docker-php-ext-enable xdebug

# Limpieza de paquetes para mantener la imagen liviana
RUN apk del autoconf build-base pkgconf

# FROM php:8.3-fpm-alpine
#
## COPY --from=build /usr/local/lib/php/extensions/no-debug-non-zts-*/mongodb.so /usr/local/lib/php/extensions/no-debug-non-zts-*/mongodb.so
#
# COPY --from=build /usr/local/lib/php/extensions/* /usr/local/lib/php/extensions/
# COPY --from=build /usr/local/etc/php/* /usr/local/etc/php/
#
# RUN echo "extension=mongodb.so" > /usr/local/etc/php/conf.d/mongodb.ini

WORKDIR /var/www/html

EXPOSE 9000

CMD ["php-fpm", "-F"]
