#!/bin/bash
git checkout master
git pull origin master
quasar build
echo "Syncing With Quasar Build Files"
rsync -avr --delete-after ../../dist/spa/ src/ --exclude '.git' --exclude 'api' --exclude '.htaccess'
echo "Syncing API"
rsync -avr --delete-after ../../api/ src/api/ --exclude 'api' --exclude '../../api/v1/logs'
echo "Commiting and Pushing"
cd spa
git add --all
git commit -m "SPA Build  on `date +'%Y-%m-%d %H:%M:%S'`";
git push origin master
