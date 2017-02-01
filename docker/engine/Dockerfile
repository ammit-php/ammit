FROM php:7.0-fpm
MAINTAINER Guillaume MOREL <g.morel@imediafrance.fr>

RUN yes | pecl install xdebug \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_enable=on" >> /usr/local/etc/php/conf.d/xdebug.ini \
    && echo "xdebug.remote_autostart=off" >> /usr/local/etc/php/conf.d/xdebug.ini

ADD . /var/www
WORKDIR /var/www

RUN usermod -u 1000 www-data


CMD ["php-fpm"]
