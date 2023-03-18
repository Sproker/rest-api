FROM php:8.0-apache

RUN a2enmod rewrite
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable mysqli pdo pdo_mysql
RUN apt-get update && apt-get upgrade -y

WORKDIR /var/www/html/bestloan

COPY app /var/www/html/bestloan
ENV APACHE_DOCUMENT_ROOT=/var/www/html/bestloan/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
COPY virtualhost.conf /etc/apache2/sites-enabled/000-default.conf