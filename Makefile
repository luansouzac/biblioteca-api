SAIL = ./vendor/bin/sail

.PHONY: install up down restart build ps logs shell migrate fresh seed tinker routes test artisan

install:
	[ -f .env ] || cp .env.example .env
	docker run --rm -u "$$(id -u):$$(id -g)" -v "$$(pwd):/var/www/html" -w /var/www/html laravelsail/php84-composer:latest composer install --ignore-platform-reqs
	$(SAIL) up -d --wait
	$(SAIL) artisan key:generate
	$(SAIL) artisan migrate --seed --force

up:
	$(SAIL) up -d

down:
	$(SAIL) down

restart: down up

build:
	$(SAIL) build --no-cache

ps:
	$(SAIL) ps

logs:
	$(SAIL) logs -f

shell:
	$(SAIL) shell

migrate:
	$(SAIL) artisan migrate

fresh:
	$(SAIL) artisan migrate:fresh --seed

seed:
	$(SAIL) artisan db:seed

tinker:
	$(SAIL) artisan tinker

routes:
	$(SAIL) artisan route:list --path=api

test:
	$(SAIL) artisan test

artisan:
	$(SAIL) artisan $(c)
