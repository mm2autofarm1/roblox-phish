FROM php:8.2-apache

# Remove all existing MPM modules and enable only prefork
RUN rm -f /etc/apache2/mods-enabled/mpm_*.load && \
    a2enmod mpm_prefork && \
    a2enmod rewrite

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
