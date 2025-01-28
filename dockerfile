FROM php:7.4-apache

# RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install mysqli
 
COPY src/ /var/www/html/
COPY img /var/www/html/img/
 
EXPOSE 80
