#!/bin/bash
## This file is currently unused and should be deleted
set -e
/opt/.deploy/mysql.provision.sh &
exec docker-entrypoint.sh mysqld
