FROM php:8.3-apache

# Install dependencies yang dibutuhkan Laravel
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-install pdo_sqlite zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Aktifkan mod_rewrite Apache untuk routing Laravel
RUN a2enmod rewrite

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Salin file composer dari folder backend
COPY backend/composer.json backend/composer.lock ./

# Install dependensi PHP (tanpa dev-dependencies)
RUN composer install --no-dev --optimize-autoloader --no-scripts

# Salin seluruh kode proyek backend ke dalam container
COPY backend/ .

# Set hak akses folder storage dan bootstrap/cache agar bisa ditulis oleh Apache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Konfigurasi Apache DocumentRoot ke folder public Laravel
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Mengubah port Apache agar mendengarkan environment $PORT dari Render
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# Salin script start.sh dari root ke dalam container
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# Eksekusi script saat container berjalan
CMD ["/usr/local/bin/start.sh"]
