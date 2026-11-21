FROM php:8.4-apache

# Extensión para conectarse a MySQL con PDO
RUN docker-php-ext-install pdo_mysql

# Configuración de producción: los errores de PHP no se muestran en la página
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

COPY . /var/www/html/
