FROM php:8.2-fpm-alpine

# تثبيت الإضافات المطلوبة لـ Laravel و Postgres
RUN apk add --no-cache \
    nginx \
    supervisor \
    curl \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# تثبيت Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# نسخ ملفات المشروع
COPY . .

# تثبيت الـ Dependencies
RUN composer install --no-dev --optimize-autoloader

# تظبيط صلاحيات الفولدرات المهمة في لارفيل
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# إعدادات Nginx الأساسية لتشغيل Laravel
RUN echo 'server { \
    listen 10000; \
    root /var/www/html/public; \
    index index.php index.html; \
    location / { \
        try_files $uri $uri/ /index.php?$query_string; \
    } \
    location ~ \.php$ { \
        include fastcgi_params; \
        fastcgi_pass 127.0.0.1:9000; \
        fastcgi_index index.php; \
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name; \
    } \
}' > /etc/nginx/http.d/default.conf

EXPOSE 10000

# أمر تشغيل السيرفر (PHP-FPM و Nginx معاً)
CMD php-fpm -D && nginx -g "daemon off;"