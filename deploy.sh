#!/bin/bash
git stash
git pull origin master
echo "Updating Composer"
cd src/api
composer update
echo "Syncing"
sudo rsync -av --delete-after /var/www/build/src/ /var/www/html/ --exclude '.git' --exclude '.htaccess' --exclude '/var/www/build/src/api/v1/public/filestore'
