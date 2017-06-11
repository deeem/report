FROM php
MAINTAINER Dmitry Karpov

RUN apt-get update && apt-get install -y curl git zlib1g-dev \
	&& docker-php-ext-install zip \
	&& docker-php-ext-install mbstring \
	&& docker-php-ext-install pdo_mysql

# COMPOSER
RUN curl -sS https://getcomposer.org/installer | php
RUN mv composer.phar /usr/local/bin/composer

# XDEBUG
RUN pecl install xdebug
RUN docker-php-ext-enable xdebug
RUN echo "xdebug.remote_enable=on\n" >> /usr/local/etc/php/conf.d/xdebug.ini \
	&& echo "xdebug.remote_autostart=off\n" >> /usr/local/etc/php/conf.d/xdebug.ini \
	&& echo "xdebug.remote_port=9000\n" >> /usr/local/etc/php/conf.d/xdebug.ini

VOLUME /app
WORKDIR /app
CMD ["php","-S","0.0.0.0:8000","-t","/app/src"]
