FROM php:7.4-apache
RUN apt-get update
RUN apt-get install -y libpq-dev libzip-dev
RUN docker-php-ext-install mysqli pdo_mysql
RUN apt-get install -y git zlib1g-dev \
 && docker-php-ext-install pdo pdo_mysql zip \
 && curl -sS https://getcomposer.org/installer \
  | php -- --install-dir=/usr/local/bin --filename=composer
RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
    && docker-php-ext-install -j$(nproc) iconv \
    && docker-php-ext-install -j$(nproc) gd
COPY api/ /var/www/api
WORKDIR /var/www/api
ADD docker/php_config/php.ini /usr/local/etc/php/conf.d/php-extras.ini
ADD docker/apache_config/000-default.conf /etc/apache2/sites-available/000-default.conf
RUN chown -R www-data:www-data /var/www/api
RUN pecl install xdebug apcu
RUN a2enmod rewrite
RUN composer install
EXPOSE 80 9000
