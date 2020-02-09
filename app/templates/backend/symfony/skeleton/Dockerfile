FROM php:7.2-apache
ADD ./ /var/www/api
ADD ./docker/php_config/php-extras.ini /usr/local/etc/php/conf.d/php-extras.ini
ADD ./docker/apache_config/000-default.conf /etc/apache2/sites-available/000-default.conf
RUN apt-get update
RUN apt-get install -y wget
RUN wget https://get.symfony.com/cli/installer -O - | bash
RUN mv /root/.symfony/bin/symfony /usr/local/bin/symfony
RUN export PATH="$HOME/.symfony/bin:$PATH"

RUN apt-get update
RUN curl -sL https://deb.nodesource.com/setup_10.x | bash -
RUN apt-get install -y subversion nodejs git zlib1g-dev \
 && docker-php-ext-install zip \
 && curl -sS https://getcomposer.org/installer \
  | php -- --install-dir=/usr/local/bin --filename=composer
RUN a2enmod rewrite

RUN requirements="libxslt-dev libldap2-dev libmcrypt-dev libpq-dev libxml2-dev libfreetype6-dev libjpeg62-turbo-dev libpng-dev libtidy-dev" \
    && apt-get update && apt-get install -y $requirements \
    && docker-php-ext-configure gd --with-jpeg-dir=/usr/lib \
    && docker-php-ext-install -j$(nproc) iconv \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd
    
RUN pecl install xdebug apcu
RUN docker-php-ext-enable xdebug apcu
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql
RUN docker-php-ext-install pdo_pgsql pgsql

RUN chown www-data:www-data /var/www/api
WORKDIR /var/www/api
#ENTRYPOINT ["sh", "/var/www/api/bin/install.sh"]
EXPOSE 80 9000
