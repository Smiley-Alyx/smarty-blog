FROM php:8.2-fpm

ARG DART_SASS_VERSION=1.77.8

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        ca-certificates \
        curl \
        git \
        unzip \
        libzip-dev \
    && docker-php-ext-install pdo_mysql \
    && ARCH="$(uname -m)" \
    && case "$ARCH" in \
        x86_64) SASS_ARCH=linux-x64 ;; \
        aarch64) SASS_ARCH=linux-arm64 ;; \
        *) echo "Unsupported architecture: $ARCH" >&2; exit 1 ;; \
    esac \
    && curl -fsSL "https://github.com/sass/dart-sass/releases/download/${DART_SASS_VERSION}/dart-sass-${DART_SASS_VERSION}-${SASS_ARCH}.tar.gz" \
        | tar -xz -C /opt \
    && ln -sf "/opt/dart-sass/sass" /usr/local/bin/sass \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY docker/php/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

WORKDIR /var/www/html

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["php-fpm"]
