FROM php:8.3-apache

# ──────────────────────────────────────────────
# 1) 必要パッケージをインストール
#    - libpq-dev : libpq（PostgreSQL C ライブラリ）のヘッダ
#    - zip / unzip などは Composer 利用時に便利（任意）
# ──────────────────────────────────────────────
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
        libpq-dev \
        zip unzip && \
    rm -rf /var/lib/apt/lists/*

# ──────────────────────────────────────────────
# 2) PHP 拡張をビルド・有効化
#    - “pgsql” と “pdo_pgsql” をまとめてビルドするのが定石
# ──────────────────────────────────────────────
RUN docker-php-ext-install pgsql pdo_pgsql

# （任意） php.ini の追加設定
COPY php.ini /usr/local/etc/php/

# ドキュメントルートはデフォルト /var/www/html
