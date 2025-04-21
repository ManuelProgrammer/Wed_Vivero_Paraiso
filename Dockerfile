FROM php:8.1-apache

# Copiar archivos del proyecto al contenedor
COPY . /var/www/html/

# Dar permisos
RUN chown -R www-data:www-data /var/www/html

# Habilitar mod_rewrite si lo usas
RUN a2enmod rewrite
