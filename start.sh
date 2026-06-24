#!/bin/bash

# Jalankan perintah optimasi Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Buat file database.sqlite jika belum ada agar migrasi tidak error
touch database/database.sqlite

# Jalankan migrasi database secara paksa di environment production
php artisan migrate --force

# Mulai server Apache di background (foreground agar container tidak mati)
apache2-foreground
