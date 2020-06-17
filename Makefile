# Build targets
staging-build:
	docker-compose build
dev-docker-build:
	docker-compose -f docker-compose.yml -f docker-compose.dev.yml build

# Launch targets
staging-up:
	docker-compose up -d
dev-docker-up:
	docker-compose -f docker-compose.yml -f docker-compose.dev.yml up -d
dev-up:
	php -S 0.0.0.0:8000 -t src src/index.php

# Stop targets
## Down containers
staging-down:
	docker-compose down
## Down containers and volumes
staging-down-all:
	docker-compose down -v
## Down containers
dev-docker-down:
	docker-compose -f docker-compose.yml -f docker-compose.dev.yml down
## Down containers and volumes
dev-docker-down-all:
	docker-compose -f docker-compose.yml -f docker-compose.dev.yml down -v

# Logs targets
staging-logs:
	docker-compose logs -f
dev-logs:
	docker-compose -f docker-compose.yml -f docker-compose.dev.yml logs -f

# Test targets
test-docker:
	docker exec -it keros-back /bin/bash -c "cd keros-api && vendor/bin/phpunit -c tests/phpunit.xml --stop-on-failure"
test-all-docker:
	docker exec -it keros-back /bin/bash -c "cd keros-api && vendor/bin/phpunit -c tests/phpunit.xml"
test:
	./vendor/bin/phpunit -c tests/phpunit.xml --stop-on-failure
test-all:
	./vendor/bin/phpunit -c tests/phpunit.xml

# Exec targets (for getting shell access to docker containers)
exec-keros:
	docker exec -it keros-back /bin/bash
exec-mysql:
	docker exec -it mysql /bin/sh	

# Init targets
## Initialise the docker mysql database by creating keros db
init-db-docker:
	docker exec -it mysql /opt/.deploy/mysql.provision.sh

# Main targets
## Full staging setup
staging: staging-down staging-build staging-up
## Full docker dev setup
dev-docker-setup: dev-docker-down-all dev-docker-build dev-docker-up
dev-docker: dev-docker-up
## Bare-metal dev setup
dev: dev-up
