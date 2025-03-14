#!/bin/sh
# Ejecutar migraciones de Laravel (si deseas que se ejecuten en cada deploy)
php artisan migrate --force

# Iniciar Supervisor, que levantará Nginx y PHP‑FPM
exec supervisord -n
