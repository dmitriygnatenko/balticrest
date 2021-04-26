docker_path = docker
php_service_name = php-fpm

usage:
	@echo "========== DEV environment =========="
	@echo
	@echo "make build           - docker build"
	@echo "make up              - docker up"
	@echo "make down            - docker down"
	@echo "make clear           - clear cache"
	@echo "make gulp            - minimize static"
	@echo "make migrate         - run migrations"
	@echo "make diff            - run migrations diff"
	@echo "make bash            - run bash"
	@echo
	@echo "========== TEST environment =========="
	@echo
	@echo "make test-migrate    - run migrations"
	@echo "make test-fixtures   - load fixtures"
	@echo "make test-codecept   - run tests"


build:
	cd $(docker_path) && docker-compose up --build -d

up:
	cd $(docker_path) && docker-compose up -d

down:
	cd $(docker_path) && docker-compose down

clear:
	cd $(docker_path) && docker-compose exec $(php_service_name) php ./bin/console cache:clear

gulp:
	cd $(docker_path) && docker-compose exec $(php_service_name) gulp

migrate:
	cd $(docker_path) && docker-compose exec $(php_service_name) php ./bin/console doctrine:migrations:migrate

diff:
	cd $(docker_path) && docker-compose exec $(php_service_name) php ./bin/console doctrine:migrations:diff

bash:
	cd $(docker_path) && docker-compose exec $(php_service_name) bash

test-migrate:
	cd $(docker_path) && docker-compose exec $(php_service_name) php ./bin/console doctrine:migrations:migrate --env=test

test-fixtures:
	cd $(docker_path) && docker-compose exec $(php_service_name) php ./bin/console doctrine:fixtures:load --env=test

test-codecept:
	cd $(docker_path) && docker-compose exec $(php_service_name) php ./vendor/bin/codecept run