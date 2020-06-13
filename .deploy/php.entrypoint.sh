#!/bin/bash
set -e
cd /usr/src/app && php -S 0.0.0.0:8000 -t src src/index.php
