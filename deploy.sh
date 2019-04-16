#!/bin/bash
git pull origin master
echo "Updating Composer"
cd src/api
composer update
echo "Syncing"
sudo rsync -av --delete-after /var/www/build/src/ /var/www/html/ --exclude '.git' --exclude '.htaccess'
