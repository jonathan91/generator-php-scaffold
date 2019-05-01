FROM php:7.2.8-apache

RUN apt-get update

RUN apt-get install -y libpq-dev

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
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd
    
COPY . /var/www/html

ADD docker/php.ini /usr/local/etc/php/conf.d/php-extras.ini
ADD docker/000-default.conf /etc/apache2/sites-available/000-default.conf

RUN chown -R www-data:www-data /var/www/html

CMD bash -c "composer install"

RUN a2enmod rewrite

EXPOSE 80
