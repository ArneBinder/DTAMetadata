#!/bin/sh

# update composer
php composer.phar self-update
# install needed components
php composer.phar install

# SQL Schema wird generiert
php app/console propel:sql:build
# PHP Code generieren
php app/console propel:model:build
# SQL Schema wird in die Datenbank geschrieben
# php app/console propel:sql:insert --force

# set permissions
sudo chmod -R 077 app/cache 
sudo chmod -R 077 app/logs 