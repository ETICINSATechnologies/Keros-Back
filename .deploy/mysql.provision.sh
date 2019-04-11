#!/bin/bash
set -e
sleep 3
mysql -u root -proot -e "DROP DATABASE IF EXISTS keros;"
mysql -u root -proot -e "CREATE DATABASE keros;"
mysql -u root -proot keros < /opt/Database/keros.sql
mysql -u root -proot keros < /opt/Database/kerosData.sql