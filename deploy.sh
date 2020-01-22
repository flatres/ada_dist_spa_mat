#!/bin/bash
git stash
git pull origin master
echo "Updating Composer"
cd src/api
composer update
echo "Syncing"
sudo rsync -av --delete-after /var/www/build/src/ /var/www/html/ --exclude 'portal' --exclude 'igc' --exclude '.git' --exclude '.htaccess' --exclude 'api/v1/public/filestore' --exclude 'api/v1/logs'
sudo chmod -R 777 /var/www/html/api/v1/public/filestore
sudo chmod -R 777 /var/www/html/api/v1/logs
