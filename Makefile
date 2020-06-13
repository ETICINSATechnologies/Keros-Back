# Build targets
prod-build:
	docker-compose build
dev-docker-build:
	docker-compose -f dev.docker-compose.yml build

# Launch targets
prod-up:
	docker-compose up -d
dev-docker-up:
	docker-compose -f dev.docker-compose.yml up -d
dev-up:
	php -S 0.0.0.0:8000 -t src src/index.php

# Stop targets
prod-down:
	docker-compose down -v
## Down containers
dev-docker-down:
	docker-compose -f dev.docker-compose.yml down
## Down containers and volumes
dev-docker-down-all:
	docker-compose -f dev.docker-compose.yml down -v

# Logs targets
prod-logs:
	docker-compose logs -f
dev-logs:
	docker-compose -f dev.docker-compose.yml logs -f

# Test targets
test-docker:
	docker exec -it keros-back vendor/bin/phpunit -c tests/phpunit.xml --stop-on-failure
test:
	./vendor/bin/phpunit -c tests/phpunit.xml --stop-on-failure

# Exec targets (for getting shell access to docker containers)
exec-keros:
	docker exec -it keros-back /bin/bash
exec-mysql:
	docker exec -it mysql /bin/sh	

# Init targets
## Initialise the docker dev database by creating keros db
init-db-docker:
	docker exec -it mysql /opt/.deploy/mysql.provision.sh

# Main targets
## Full production setup
prod: prod-down prod-build prod-up
## Full docker dev setup
dev-docker-setup: dev-docker-down-all dev-docker-build dev-docker-up
dev-docker: dev-docker-up
## Bare-metal dev setup
dev: dev-up