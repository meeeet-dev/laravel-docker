FROM php:7.4-fpm-alpine3.13

LABEL dockerlabel="PHP 7.4 - Docker"

WORKDIR /app
# Install system packages
RUN apk update && \
    apk add --no-cache \
        bash \
        git \
        curl \
        nano \
        g++ \
        sudo \
        freetds \
        freetype \
        icu \
        libintl \
        libldap \
        libjpeg \
        libpng \
        libpq \
        libwebp \
        libmemcached \
        supervisor \
        libzip \
        composer && \
    apk add --no-cache --virtual build-dependencies \
        curl-dev \
        freetds-dev \
        freetype-dev \
        gettext-dev \
        icu-dev \
        jpeg-dev \
        libpng-dev \
        libwebp-dev \
        libxml2-dev \
        libzip-dev \
        libmemcached-dev \
        openldap-dev \
        postgresql-dev \
        zlib-dev \
        autoconf \
        build-base
# Install PHP extensions
RUN docker-php-ext-configure gd \
        --with-freetype=/usr/include/ \
        --with-jpeg=/usr/include/ && \
    docker-php-ext-configure ldap --with-libdir=lib/ && \
    docker-php-ext-configure pdo_dblib --with-libdir=lib/ && \
    docker-php-ext-install \
        bcmath \
        curl \
        ctype \
        fileinfo \
        json \
        gettext \
        gd \
        exif \
        iconv \
        intl \
        tokenizer \
        ldap \
        opcache \
        pdo_mysql \
        pdo_pgsql \
        pdo_dblib \
        soap \
        sockets \
        zip \
        xml \
        pcntl
# Install PECL extensions
# Install memcached if needed
# RUN pecl install memcached && \
#     docker-php-ext-enable memcached

RUN pecl install redis
RUN docker-php-ext-enable redis

RUN apk del build-dependencies

# Install NVM, Node.js, and npm
RUN curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.3/install.sh | bash \
    && export NVM_DIR="/root/.nvm" \
    && [ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh" \
    && nvm install --lts \
    && nvm use --lts \
    && nvm alias default 'lts/*' \
    && npm install -g npm

# Add NVM initialization to .bashrc
RUN echo "export NVM_DIR=\"/root/.nvm\"" >> /root/.bashrc \
    && echo "[ -s \"$NVM_DIR/nvm.sh\" ] && \. \"$NVM_DIR/nvm.sh\"" >> /root/.bashrc \
    && echo "export PATH=\"$NVM_DIR/versions/node/$(nvm current)/bin:\$PATH\"" >> /root/.bashrc

# Ensure nvm is available to all users
ENV NVM_DIR /root/.nvm
ENV PATH $NVM_DIR/versions/node/$(nvm current)/bin:$PATH

COPY .docker/config /tmp/config
# Download trusted certs
RUN mkdir -p /etc/ssl/certs && update-ca-certificates && \
# Copy php configuration
    cp /tmp/config/php.ini /usr/local/etc/php/php.ini && \
# Copy supervisor config
    mkdir /etc/supervisor.d && \
    cp /tmp/config/supervisord.conf /etc/supervisord.conf && \
    cp -r /tmp/config/supervisor.d /etc && \
# Copy cron job
    cp /tmp/config/crontab /etc/crontabs/www-data && \
    cp /tmp/config/entrypoint.sh /entrypoint.sh && \
    rm -rf /tmp/config && \
    chmod a+x /entrypoint.sh

ENV ENABLE_CRON_JOB=${ENABLE_CRON_JOB:-false}
ENV ENABLE_LARAVEL_WORKER=${ENABLE_LARAVEL_WORKER:-false}
ENV ENABLE_LARAVEL_HORIZON=${ENABLE_LARAVEL_HORIZON:-false}
ENV ENABLE_LARAVEL_WEBSOCKETS=${ENABLE_LARAVEL_WEBSOCKETS:-false}
ARG UID=1000
ARG USER='developer'
#Create non-root sudoer
RUN adduser -S -D -G www-data -u $UID -h /home/$USER $USER
RUN mkdir -p /home/$USER/.composer && \
    chown -R $USER:www-data /home/$USER
RUN chown -R $USER:www-data /app
EXPOSE 6001
ENTRYPOINT ["/entrypoint.sh"]

CMD ["php-fpm", "-F"]
