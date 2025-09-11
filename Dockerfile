# 1️⃣ Basis-Image mit PHP-FPM
FROM php:8.2-fpm-alpine

# 2️⃣ Arbeitsverzeichnis
WORKDIR /app

# 3️⃣ Dateien kopieren
COPY . .

# 4️⃣ PHP-Erweiterungen installieren (falls benötigt)
RUN docker-php-ext-install pdo pdo_mysql

# 5️⃣ Nginx installieren
RUN apk add --no-cache nginx supervisor

# 6️⃣ Nginx konfigurieren
RUN mkdir -p /run/nginx /var/log/nginx

COPY nginx.conf /etc/nginx/nginx.conf

# 7️⃣ Supervisor konfigurieren, um PHP-FPM + Nginx zusammen laufen zu lassen
COPY supervisord.conf /etc/supervisord.conf

# 8️⃣ Port freigeben
EXPOSE 80

# 9️⃣ Startbefehl
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
