FROM php:7.4.19-apache
COPY src/ /var/www/html
EXPOSE 80

RUN docker-php-ext-install pdo pdo_mysql
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer