# 1. Build Stage: install Node deps and compile assets
FROM node:18 AS node-builder

WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY . .
RUN npm run build

# 2. Production Stage: PHP runtime + Composer + Laravel
FROM php:8.2-fpm

# Install system deps and PHP extensions
RUN apt-get update && apt-get install -y \
    git zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev \
  && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath xml zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/html

# Copy application and compiled Vue assets
COPY --from=node-builder /app /var/www/html
COPY --from=node-builder /app/public/build ./public/build

# Fix ownership and mark /var/www/html safe for Git
RUN chown -R www-data:www-data /var/www/html \
 && git config --global --add safe.directory /var/www/html

# Switch to www-data and install PHP dependencies
USER www-data

RUN composer install --no-dev --optimize-autoloader \
 && php artisan key:generate

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
