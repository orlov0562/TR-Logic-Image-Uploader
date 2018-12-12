FROM php:7.2.13

LABEL name="Image Uploader Docker Container"
LABEL version="1.0"
LABEL author="Vitalii Orlov"
LABEL author.email="orlov0562@gmail.com"

# ==================================
# UPDATE PACKAGE LISTS
# ==================================

RUN apt-get update

# ==================================
# INSTALL ZIP, UNZIP
# ==================================

RUN apt-get install -y apt-utils zip unzip

# ==================================
# ADD GD AND EXIF SUPPORT TO PHP
# ==================================

RUN apt-get install -y libpng-dev libfreetype6-dev libjpeg62-turbo-dev
RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
                             && docker-php-ext-install -j$(nproc) gd
RUN docker-php-ext-install exif

# ==================================
# INSTALL GIT
# ==================================

RUN apt-get install -y git

# ==================================
# INSTALL COMPOSER
# ==================================

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# ==================================
# INSTALL BOWER
# ==================================

RUN apt-get install -y gcc make gnupg
RUN curl --silent --location https://deb.nodesource.com/setup_8.x | bash -
RUN apt-get install -y nodejs
RUN npm install --global bower

# ==================================
# DOWNLOAD APP FROM GIT
# ==================================

RUN mkdir -p /opt/web/app
RUN git clone https://github.com/orlov0562/TR-Logic-Image-Uploader.git /opt/web/app/

# ==================================
# INSTALL APP DEPENDENCIES
# ==================================

RUN cd /opt/web/app && composer install
RUN cd /opt/web/app && bower --allow-root install

# ==================================
# CREATE APP SYMLINKS
# ==================================

RUN mkdir -p /opt/web/app/storage/app/public
RUN rm -rf /opt/web/app/public/storage
RUN ln -s /opt/web/app/storage/app/public /opt/web/app/public/storage

# ==================================
# RUN APP TESTS
# ==================================

RUN /opt/web/app/vendor/bin/phpunit /opt/web/app/tests

# ==================================
# CONFIGURE APP ENVIRONMENT
# ==================================

RUN echo "APP_DEBUG = true\n" > /opt/web/app/.env

# ==================================
# CLEAN UP
# ==================================

RUN rm -rf /var/lib/apt/lists/*
RUN rm -rf /opt/web/app/storage/framework/testing/*

# ==================================
# INSTALLATION COMPLETE
# ==================================

CMD ["php", "-S", "0.0.0.0:8000", "-t", "/opt/web/app/public"]
