#!/bin/bash
set -e
/opt/.deploy/mysql.provision.sh &
exec docker-entrypoint.sh mysqld
