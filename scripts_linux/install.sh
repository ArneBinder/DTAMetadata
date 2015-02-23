#!/bin/sh

# update composer
php composer.phar self-update
# install needed components
php composer.phar install

cp -r src/DTA/MetadataBundle/Resources/schemas_final/* src/DTA/MetadataBundle/Resources/config/

# SQL Schema wird generiert
php app/console propel:sql:build
# PHP Code generieren
php app/console propel:model:build
# SQL Schema wird in die Datenbank geschrieben
# php app/console propel:sql:insert --force

# set permissions
sudo chmod -R 0777 app/logs
# to enable /Update functionality
sudo chown -R www-data .
#sudo chown -R www-data app/cache
## conversion: schema files will be overwritten by content of Resources/schemas_final
#sudo chown -R www-data src/DTA/MetadataBundle/Resources/config/*schema.xml
## for propel:model:build
#sudo chown -R www-data src/DTA/MetadataBundle/Model
## for propel:sql:build
#sudo chown -R www-data app/propel
