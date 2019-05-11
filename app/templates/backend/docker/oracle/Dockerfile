FROM php:7.2.8-apache

RUN apt-get update

RUN apt-get install -y libpq-dev

RUN apt-get install -y git zlib1g-dev \
 && docker-php-ext-install zip \
 && curl -sS https://getcomposer.org/installer \
  | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt-get update && apt-get -y install wget bsdtar libaio1 && \
wget -qO- https://raw.githubusercontent.com/caffeinalab/php-fpm-oci8/master/oracle/instantclient-basic-linux.x64-12.2.0.1.0.zip | bsdtar -xvf- -C /usr/local && \
wget -qO- https://raw.githubusercontent.com/caffeinalab/php-fpm-oci8/master/oracle/instantclient-sdk-linux.x64-12.2.0.1.0.zip | bsdtar -xvf-  -C /usr/local && \
wget -qO- https://raw.githubusercontent.com/caffeinalab/php-fpm-oci8/master/oracle/instantclient-sqlplus-linux.x64-12.2.0.1.0.zip | bsdtar -xvf- -C /usr/local && \
ln -s /usr/local/instantclient_12_2 /usr/local/instantclient && \
ln -s /usr/local/instantclient/libclntsh.so.* /usr/local/instantclient/libclntsh.so && \
ln -s /usr/local/instantclient/lib* /usr/lib && \
ln -s /usr/local/instantclient/sqlplus /usr/bin/sqlplus && \
docker-php-ext-configure oci8 --with-oci8=instantclient,/usr/local/instantclient && \
docker-php-ext-install oci8

RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
    && docker-php-ext-install -j$(nproc) iconv \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd

COPY . /var/www/application

ADD docker/php.ini /usr/local/etc/php/conf.d/php-extras.ini
ADD docker/000-default.conf /etc/apache2/sites-available/000-default.conf

RUN chown -R www-data:www-data /var/www/application

RUN cd /var/www/application && composer install

RUN a2enmod rewrite

EXPOSE 80
