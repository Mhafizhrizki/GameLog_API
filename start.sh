#!/bin/bash

# Jalankan perintah optimasi Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ambil path DB_DATABASE dari env (default ke database/database.sqlite)
DB_PATH=${DB_DATABASE:-database/database.sqlite}

# Pastikan folder tempat database berada tersedia
mkdir -p $(dirname "$DB_PATH")

# Buat file database.sqlite jika belum ada
touch "$DB_PATH"

# Berikan hak akses kepada www-data (Apache) agar bisa membaca & menulis database
chown -R www-data:www-data $(dirname "$DB_PATH")
chmod -R 775 $(dirname "$DB_PATH")

# Jalankan migrasi database secara paksa di environment production
php artisan migrate --force

# Matikan MPM event/worker dan pastikan mpm_prefork aktif (Fix khusus Railway)
a2dismod mpm_event mpm_worker || true
a2enmod mpm_prefork || true

# Mulai server Apache di background (foreground agar container tidak mati)
apache2-foreground
