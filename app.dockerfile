# Set master image
FROM php:7.2-fpm-alpine

MAINTAINER Kostas Papadopoulos <kpapadop92@gmail.com>

# Set working directory
WORKDIR /var/www

# # Copy composer.lock and composer.json
# # COPY composer.lock composer.json /var/www/


RUN apk --no-cache add shadow && usermod -u 1000 www-data

# # Add and Enable PHP-PDO Extenstions
RUN docker-php-ext-install pdo pdo_mysql
RUN docker-php-ext-enable pdo_mysql

# # Remove Cache
# RUN rm -rf /var/cache/apk/*

# # RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# RUN curl --silent --show-error https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer && php /usr/local/bin/composer install
# # RUN php /usr/local/bin/composer install

# # Add user for laravel application
RUN addgroup -g 1000 www-data
RUN adduser -u 1000 -ms /bin/bash -g www-data www-data

# Copy existing application directory contents
COPY . /var/www

# Copy existing application directory permissions
COPY --chown=www-data:www-data . /var/www/



# Change current user to www-data
USER www-data

# # Expose port 9000 and start php-fpm server
# EXPOSE 9000
# CMD ["php-fpm"]















# RUN apk update && apk add libmcrypt-dev mysql-client && docker-php-ext-install mcrypt pdo_mysql
# ADD . /var/www
# RUN chown -R www-data:www-data /var/www