FROM php:8.2-apache

# Install ekstensi database yang diperlukan Laravel
RUN docker-php-ext-install pdo pdo_mysql

# Aktifkan mod_rewrite untuk Apache (penting untuk routing Laravel)
RUN a2enmod rewrite

# Ubah Document Root Apache ke folder public Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# Copy seluruh file proyek ke dalam server
COPY . /var/www/html

# Atur izin folder storage dan bootstrap agar Laravel bisa menulis cache/log
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 80