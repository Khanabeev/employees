FROM php:7.4-fpm

COPY composer.lock composer.json /var/www/html/

WORKDIR /var/www/html/

RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libxml2-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    unzip \
    vim \
    libc-client-dev \
    libkrb5-dev \
    git \
    jpegoptim optipng pngquant gifsicle \
    libzip-dev \
    libonig-dev \
    curl \
    openssl \
    nodejs npm

#Install npm
RUN curl -sL https://deb.nodesource.com/setup_14.x | bash -
RUN npm install

RUN apt-get clean && rm -rf /var/lib/apt/lists/*
RUN apt autoremove -y

RUN docker-php-ext-install pdo pdo_mysql mbstring zip exif pcntl bcmath ctype fileinfo json tokenizer xml
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd
RUN docker-php-ext-configure imap --with-kerberos --with-imap-ssl \
    && docker-php-ext-install imap

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory contents
COPY . /var/www/html

# Copy existing application directory permissions
COPY --chown=www:www . /var/www/html

# Change current user to www
USER www

RUN composer install

EXPOSE 9000

CMD ["php-fpm"]
