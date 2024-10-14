# Use a imagem base do PHP
FROM php:8.3-fpm

# Instala as dependências necessárias
RUN apt-get upgrade -y && apt-get update -y \
    && apt-get install -y \
        curl \
        git \
        zip \
        libzip-dev \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && docker-php-ext-install \
        pdo_mysql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Copia o conteúdo do projeto para o contêiner
COPY . /var/www/html

# Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Define o diretório de trabalho
WORKDIR /var/www/html
