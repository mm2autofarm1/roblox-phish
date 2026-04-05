FROM php:8.2-apache

RUN find /etc/apache2 -name "*mpm*" -delete && \
    find /etc/apache2/conf* -name "*.conf" -exec grep -l "mpm" {} \; -delete && \
    apt-get install -y libapache2-mod-php8.2 2>/dev/null || true

RUN echo "LoadModule mpm_prefork_module /usr/lib/apache2/modules/mod_mpm_prefork.so" \
    > /etc/apache2/mods-enabled/mpm_prefork.load

RUN a2enmod rewrite php8.2

COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
CMD ["apache2-foreground"]
