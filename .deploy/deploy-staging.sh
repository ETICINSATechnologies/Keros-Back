#!/usr/bin/env bash
sshpass -p "${SSH_PASS}" rsync -r --delete-after --quiet $TRAVIS_BUILD_DIR/src $TRAVIS_BUILD_DIR/vendor --exclude /src/settings.ini ${SSH_USER}@${SSH_HOST}:/var/www/keros-api-dev
sshpass -p "${SSH_PASS}" ssh ${SSH_USER}@${SSH_HOST} "(cd /var/www/keros-api-dev; composer install )"