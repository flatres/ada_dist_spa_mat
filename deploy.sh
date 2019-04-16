#!/bin/bash
git pull origin master
echo "Updating Composer"
cd src/api
composer update
echo "Syncing"
sudo rsync -av --delete-after /var/www/build/source/ /var/www/html/ --exclude '.git' --exclude '.htaccess'
echo "Setting Environment"
cp /var/www/build/env/prod.env /var/www/html/.env

