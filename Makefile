export

.PHONY: start stop restart tests

DC := docker-compose exec
FPM := $(DC) php-fpm
ARTISAN := $(FPM) php artisan

start:
	@docker-compose up -d

stop:
	@docker-compose down

restart: stop start

ssh:
	@$(FPM) bash

truncate:
	@$(ARTISAN) db:wipe

key-generate:
	@$(ARTISAN) key:generate

tests:
	@$(ARTISAN) test

env:
	cp ./.env.example ./.env

app-key-generate:
	@$(ARTISAN) key:generate

migrate:
	@$(ARTISAN) migrate

seed:
	@$(ARTISAN) db:seed

reset-db: truncate migrate seed

composer-install:
	@$(FPM) composer install

dump-autoload:
	@$(FPM) composer dump-autoload

sleep-15:
	sleep 15

install: start sleep-15 composer-install key-generate migrate seed
