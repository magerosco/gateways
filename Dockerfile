FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    default-mysql-client \
    netcat-traditional \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    locales \
    zip \
    libzip-dev \
    unzip \
    git \
    curl \
    libonig-dev \
    libxml2-dev \
    redis-server \
    memcached \
    libmemcached-dev \
    libpq-dev && rm -rf /var/lib/apt/lists/*

 # Instalar Node.js y npm
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
&& apt-get install -y nodejs

# Rewrite Module enabled
RUN a2enmod rewrite \
&& a2enmod headers

RUN pecl install redis && docker-php-ext-enable redis


RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd pdo_pgsql pgsql fileinfo gettext dom intl zip


RUN pecl install mongodb && docker-php-ext-enable mongodb

# Copy composer.lock and composer.json before the installing dependencies
COPY package*.json ./

# Install dependencies
RUN npm install

#### Composer ####
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

#### Apache ####
COPY docker/apache/default.conf /etc/apache2/sites-available/000-default.conf

COPY . /var/www/html

#### Set permissions ####
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

RUN git config --global --add safe.directory /var/www/html

RUN echo "ServerName gateway.test" >> /etc/apache2/apache2.conf

# Build assets
RUN npm run build

EXPOSE 8088

#### Wait for database to be ready and run Laravel migrations from script
COPY docker/scripts/wait-for-db.sh /usr/local/bin/wait-for-db.sh

CMD [ "sh", "-c", "/usr/local/bin/wait-for-db.sh && apache2-foreground echo 'Dockerfile is ready'" ]
####  ####
