FROM php:8.2-apache

# Active mod_rewrite (pas obligatoire ici, mais utile en général)
RUN a2enmod rewrite

# Copie des fichiers du projet dans le dossier web d’Apache
COPY . /var/www/html

# Droits (optionnel, selon ton environnement)
RUN chown -R www-data:www-data /var/www/html