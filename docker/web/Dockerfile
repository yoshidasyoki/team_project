FROM php:8.5-apache

# Docker内の生成ファイルの所有者をrootからホスト側のユーザーに変更するための設定
ARG HOST_UID
ARG HOST_GID
RUN groupadd -g ${HOST_GID} web \
    && useradd -u ${HOST_UID} -g ${HOST_GID} -m web

# mod_rewrite モジュールを使えるようにする
RUN a2enmod rewrite

# 必要モジュールのインストール
RUN apt-get update \
    && apt-get install -y \
        libzip-dev \
        unzip \
    && docker-php-ext-install \
        zip \
        pdo_mysql \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug

# Composerインストール
COPY --from=composer:2.10.1 /usr/bin/composer /usr/bin/composer

# xdebugの設定反映
COPY ./docker/web/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

WORKDIR /var/www/html
COPY ./src/ ./
