FROM php:7.4-apache
RUN apt-get update \
    && apt-get upgrade -y
RUN docker-php-ext-install mysqli
# COPY ./src /var/www/html
EXPOSE 80