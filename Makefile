prod-build:
	docker-compose build
dev-build:
	docker-compose -f dev.docker-compose.yml build

prod-up:
	docker-compose up -d
dev-up:
	docker-compose -f dev.docker-compose.yml up -d
dev-up-bare:
	php -S 0.0.0.0:8000 -t src src/index.php

prod-down:
	docker-compose down -v
dev-down:
	docker-compose -f dev.docker-compose.yml down -v

prod-logs:
	docker-compose logs -f
dev-logs:
	docker-compose -f dev.docker-compose.yml logs -f

dev-test:
	docker exec -it keros-back vendor/bin/phpunit -c tests/phpunit.xml --stop-on-failure
dev-test-bare:
	./vendor/bin/phpunit -c tests/phpunit.xml --stop-on-failure

exec-keros:
	docker exec -it keros-back /bin/bash
exec-mysql:
	docker exec -it mysql /bin/sh	

db-init-docker:
	docker exec -it mysql /opt/.deploy/mysql.provision.sh

prod: prod-down prod-build prod-up
dev: dev-down dev-build dev-up
dev-bare: dev-up-local