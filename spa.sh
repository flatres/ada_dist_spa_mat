#!/bin/bash

quasar build
echo "Syncing With Quasar Build Files"
rsync -avr --delete-after ../dist/spa/ spa/src/ --exclude '.git' --exclude 'api' --exclude '.htaccess'
echo "Syncing API"
rsync -avr --delete-after ../api/ spa/src/api/ --exclude 'api'
echo "Commiting and Pushing"
cd spa
git add --all
git commit -m "SPA Build  on `date +'%Y-%m-%d %H:%M:%S'`";
git push origin master
