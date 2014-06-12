#!/bin/sh

# Download and install composer to the bin folder
curl -sS https://getcomposer.org/installer | php -- --install-dir=bin

# The installer installs with a .phar extension - get rid of it
mv bin/composer.phar bin/composer

# Use composer to fetch the required dependencies.
./bin/composer install
