#!/usr/bin/env bash

composer install --no-interaction --no-ansi --optimize-autoloader --apcu-autoloader
rm -rf ~/.composer/cache/*
php-fpm
