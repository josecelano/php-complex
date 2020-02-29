FROM php:7.4-cli

COPY . /usr/src/app
WORKDIR /usr/src/app

# Install git and unzip extensions needed by composer
RUN apt-get update \
    && apt-get upgrade -y \
    && apt-get install -y git unzip

# Install PHP extensions installer
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/

RUN install-php-extensions zip decimal

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && chmod +x /usr/local/bin/composer \
    && composer --version

CMD [ "./vendor/bin/phpunit" ]