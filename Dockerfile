FROM php:8.5-apache
RUN apt-get update && apt-get install -y --no-install-recommends libpq-dev && docker-php-ext-install pdo_pgsql && rm -rf /var/lib/apt/lists/*
COPY app/ /var/www/html/
RUN printf 'display_errors=Off\ndisplay_startup_errors=Off\nlog_errors=On\nerror_log=/dev/stderr\n' > /usr/local/etc/php/conf.d/errors.ini
