docker_path = docker
php_service_name = php-fpm

usage:
	@echo "make clear - clear cache"
	@echo "make gulp - minimize static"
	@echo "make migrate - run migrations"
	@echo "make fixtures - load fixtures"
	@echo "make build - docker build"
	@echo "make up - docker up"
	@echo "make down - docker down"
	@echo "make bash - run bash"

clear:
	cd $(docker_path) && docker-compose exec $(php_service_name) php ./bin/console cache:clear

gulp:
	cd $(docker_path) && docker-compose exec $(php_service_name) gulp

migrate:
	cd $(docker_path) && docker-compose exec $(php_service_name) php ./bin/console doctrine:migrations:migrate

fixtures:
	cd $(docker_path) && docker-compose exec $(php_service_name) php ./bin/console doctrine:fixtures:load

build:
	cd $(docker_path) && docker-compose up --build -d

up:
	cd $(docker_path) && docker-compose up -d

down:
	cd $(docker_path) && docker-compose down

bash:
	cd $(docker_path) && docker-compose exec $(php_service_name) bash

test:
	cd $(docker_path) && docker-compose exec $(php_service_name) php ./vendor/bin/codecept run