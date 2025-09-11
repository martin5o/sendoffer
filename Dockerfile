FROM php:8.2-fpm-alpine

WORKDIR /app

COPY . .

RUN docker-php-ext-install mysqli pdo pdo_mysql

# Installiere Composer, falls du PHPMailer per Composer nutzen willst
# RUN apk add --no-cache composer

EXPOSE 9000
CMD ["php-fpm"]
