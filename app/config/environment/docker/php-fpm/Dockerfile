FROM php:7.1-fpm

# install composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer

# Install Requirements
RUN apt-get update -qq && apt-get install -qqy \
        mysql-client \
        sudo \
        zlib1g-dev \
        libicu-dev \
        libmcrypt-dev \
        wget \
        zip \
        apt-utils \

    && docker-php-ext-configure intl \
    && docker-php-ext-install -j$(nproc) \
       iconv \
       mcrypt \
       intl \
       pdo \
       pdo_mysql \
       mbstring \
       opcache \

    && apt-get purge zlib1g-dev libicu-dev libmcrypt-dev -yy \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install PECL extensions
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug

COPY php.ini /usr/local/etc/php/php.ini

COPY start.sh /root/start.sh
RUN chmod +x /root/start.sh

CMD ["/bin/bash", "/root/start.sh"]