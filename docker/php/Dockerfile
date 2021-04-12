FROM php:7.4-fpm

WORKDIR /var/www/html/

RUN docker-php-ext-install mysqli pdo pdo_mysql \
    exif \
    pcntl \
    bcmath

RUN docker-php-source delete

RUN curl -sSk https://getcomposer.org/installer | php -- --disable-tls && \
	mv composer.phar /usr/local/bin/composer

RUN chmod 0777 -Rf /var/www/html/

EXPOSE 9000
