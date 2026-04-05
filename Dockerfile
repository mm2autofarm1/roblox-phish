FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    && rm -rf /var/lib/apt/lists/*

# Disable conflicting MPMs, enable only prefork
RUN a2dismod mpm_event mpm_worker 2>/dev/null || true && \
    a2enmod mpm_prefork && \
    a2enmod rewrite

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

EXPOSE 80

CMD ["apache2-foreground"]
