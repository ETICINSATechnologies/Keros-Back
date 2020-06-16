#!/bin/bash
set -e
mysql -u root -proot -e "DROP DATABASE IF EXISTS keros;"
mysql -u root -proot -e "CREATE DATABASE keros CHARACTER SET utf8 COLLATE utf8_general_ci;"
mysql -u root -proot --default-character-set=utf8 keros < /opt/Database/keros.sql
mysql -u root -proot --default-character-set=utf8 keros < /opt/Database/kerosData.sql