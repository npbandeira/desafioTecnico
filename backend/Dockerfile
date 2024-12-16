# Usa uma imagem oficial do PHP com suporte a extensões necessárias para Laravel
FROM php:8.2-cli

# Instala dependências do sistema
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg-dev \
    libpng-dev \
    libwebp-dev \
    libpq-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_pgsql -j$(nproc) gd gd

# Instala o Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# Define o diretório de trabalho no container
WORKDIR /var/www

# Copia o código do Laravel para o container
COPY . .

RUN composer install

# Configura as permissões para as pastas de armazenamento e cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Define o comando padrão para rodar o servidor do Laravel
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
