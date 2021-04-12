FROM php:7.4-fpm

WORKDIR /var/www/html/

RUN apt-get update && apt-get install -y \
    build-essential \
    # libpng-dev \
    # libjpeg62-turbo-dev \
    # libfreetype6-dev \
    # locales \
    # git \
    zip \
    libzip-dev \
    unzip \
    libonig-dev \
    curl

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install mysqli pdo pdo_mysql \
    exif \
    pcntl \
    zip \
    mbstring \
    bcmath

RUN docker-php-source delete

RUN curl -sSk https://getcomposer.org/installer | php -- --disable-tls && \
	mv composer.phar /usr/local/bin/composer

RUN chmod 0777 -Rf /var/www/html/

EXPOSE 9000
