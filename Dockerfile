FROM ubuntu:16.04

RUN apt-get update && apt-get install -y \
    vim \
    curl \
    software-properties-common \
    python-software-properties \
    nginx \
    git \
    bzip2 \
    zip

RUN export LC_ALL="C.UTF-8" && add-apt-repository -y ppa:ondrej/php

RUN apt-get update && apt-get install -y --no-install-recommends apt-utils \
    php7.2 \
    php7.2-fpm \
    php7.2-cli \
    php7.2-mbstring \
    php7.2-zip \
    php7.2-gd \
    php7.2-pgsql \
    php7.2-bcmath \
    php7.2-xml \
    php7.2-intl \
    php7.2-mysql

RUN curl -sS https://getcomposer.org/installer -o composer-setup.php
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer

RUN curl -sL https://deb.nodesource.com/setup_8.x -o nodesource_setup.sh
RUN bash nodesource_setup.sh
RUN apt-get -y install nodejs

RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

COPY . /var/www/html/amar-steps
COPY --chown=www:www . /var/www/html/amar-steps

WORKDIR /var/www/html/amar-steps

RUN npm install

USER www
