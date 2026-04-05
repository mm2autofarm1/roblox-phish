FROM php:8.2-apache

# Disable all MPMs and enable only prefork (compatible with PHP)
RUN a2dismod mpm_event mpm_worker && \
    a2enmod mpm_prefork && \
    a2enmod rewrite

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
