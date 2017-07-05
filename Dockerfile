FROM phalconphp/php-fpm:7-min
RUN docker-php-ext-install pdo pdo_mysql
WORKDIR /app
VOLUME /app