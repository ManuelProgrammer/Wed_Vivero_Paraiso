FROM php:8.1-apache

# Instalar extensiones necesarias para MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Activar mod_rewrite
RUN a2enmod rewrite

# Copiar solo lo necesario para backend PHP
COPY ./api /var/www/html/api
COPY ./includes /var/www/html/includes
COPY ./templates /var/www/html/templates
COPY ./views /var/www/html/views
COPY ./models /var/www/html/models
COPY ./style /var/www/html/style
COPY ./multimedia /var/www/html/multimedia
COPY ./BLOG /var/www/html/BLOG

# Archivos PHP raíz
COPY index.php /var/www/html/index.php
COPY config.php /var/www/html/config.php
COPY contac.php /var/www/html/contac.php
COPY nosotros.php /var/www/html/nosotros.php
COPY soport.php /var/www/html/soport.php

# Ajustar permisos
RUN chown -R www-data:www-data /var/www/html
