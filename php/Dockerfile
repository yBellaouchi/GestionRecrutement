FROM php:8.1-apache
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
# FROM ubuntu:latest
# MAINTAINER docker@ekito.fr
RUN apt-get update \
    && apt-get install -y  --no-install-recommends locales apt-utils git libicu-dev g++ libpng-dev libxml2-dev libzip-dev libonig-dev libxslt-dev;
# COPY hello-cron /etc/cron.d/hello-cron
# RUN chmod 0644 /etc/cron.d/hello-cron

RUN apt-get update -y
RUN apt-get install -y cron

RUN cron

RUN apt-get install -y nano    


RUN echo "en_US.UTF-8 UTF-8" > /etc/locale.gen && \
    echo "fr_FR.UTF-8 UTF-8" >> /etc/locale.gen && \
    locale-gen
# RUN crontab /etc/cron.d/hello-cron
# RUN touch /var/log/cron.log
RUN curl -sSk https://getcomposer.org/installer | php -- --disable-tls && \
   mv composer.phar /usr/local/bin/composer
# CMD cron && tail -f /var/log/cron.log
RUN docker-php-ext-configure intl
RUN docker-php-ext-install pdo pdo_mysql gd opcache intl zip calendar dom mbstring zip gd xsl
RUN pecl install apcu && docker-php-ext-enable apcu

WORKDIR /var/www/