FROM php:8.3-apache

RUN apt update
RUN apt install -y \
    git \
    zip \
    curl \
    sudo \
    unzip \
    libicu-dev \
    libbz2-dev \
    libpng-dev \
    libjpeg-dev \
    libmcrypt-dev \
    libreadline-dev \
    libfreetype6-dev \
    g++

RUN docker-php-ext-install \
    bz2 \
    intl \
    bcmath \
    opcache \
    calendar \
    pdo \
    pdo_mysql \
    mysqli

RUN docker-php-ext-enable mysqli

COPY apache.conf /etc/apache2/sites-available/apache.conf

RUN a2enmod rewrite headers

RUN a2dissite 000-default.conf
RUN a2ensite apache.conf

RUN service apache2 restart

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer
RUN chmod +x /usr/local/bin/composer
RUN composer self-update

RUN useradd -G www-data,root -u 1000 -d /home/devuser devuser
RUN mkdir -p /home/devuser/.composer && \
    chown -R devuser:devuser /home/devuser

EXPOSE 80
EXPOSE 3306
