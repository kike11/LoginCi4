# Utiliza una imagen base de PHP 8 con Apache
FROM php:8-apache

# Actualiza el sistema e instala las dependencias necesarias
RUN apt-get update && apt-get install -y libicu-dev \ 
    git \
    unzip \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && docker-php-ext-install mysqli \  
    && docker-php-ext-enable mysqli \   
    && rm -rf /var/lib/apt/lists/*
    
#configuracion de Zona horaria en el servidor de docker
RUN apt-get update && apt-get install -y tzdata
RUN ln -fs /usr/share/zoneinfo/America/Mexico_City /etc/localtime && dpkg-reconfigure -f noninteractive tzdata
RUN echo "date.timezone = America/Mexico_City" > /usr/local/etc/php/conf.d/timezone.ini

# Instala Composer globalmente
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


# Habilita el módulo de Apache para PHP
RUN a2enmod rewrite

# Instala CodeIgniter 4 utilizando Composer
#RUN composer create-project codeigniter4/appstarter cat --no-dev

# Cambia el propietario y los permisos de la carpeta de la aplicación
#RUN chown -R www-data:www-data /var/www/html/cat
#RUN chmod -R 755 /var/www/html/cat

# Copia tu aplicación de CodeIgniter al directorio web de Apache en el contenedor
#COPY ./app /var/www/html

# Define el directorio de trabajo
WORKDIR /var/www/html

# Expone el puerto 80 para el tráfico web
EXPOSE 80

# Comando predeterminado para iniciar Apache en primer plano
CMD ["apache2-foreground"]