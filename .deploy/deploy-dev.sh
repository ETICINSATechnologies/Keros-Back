#!/usr/bin/env bash
sshpass -p "${SSH_PASS}" rsync -r --delete-after --quiet --exclude '/src/settings.ini' $TRAVIS_BUILD_DIR/src ${SSH_USER}@${SSH_HOST}:/var/www/keros-api-dev
sshpass -p "${SSH_PASS}" ssh ${SSH_USER}@${SSH_HOST} "(cd /var/www/keros-api-dev; composer install )"