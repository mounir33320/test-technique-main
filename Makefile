COMPOSE = docker compose
EXEC = $(COMPOSE) exec app

up:
	$(COMPOSE) up -d

down:
	$(COMPOSE) down

install:
	$(EXEC) composer install

serve:
	$(EXEC) composer serve

init:
	$(EXEC) php /app/database/init.php

test:
	$(EXEC) /app/vendor/bin/phpunit /app/tests --testdox