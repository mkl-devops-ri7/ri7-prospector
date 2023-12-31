include .env
ifneq ("$(wildcard .env.local)","")
	include .env.local
endif

env=dev

isContainerRunning := $(shell docker info > /dev/null 2>&1 && docker ps | grep "${PROJECT_NAME}-php" > /dev/null 2>&1 && echo 1 || echo 0)

# Executables (local)
DOCKER_COMP 	= docker compose
PHP_CONT    	= APP_ENV=$(env)
PHP_CONT_TEST   = APP_ENV=test

ifeq ($(isContainerRunning),1)
	# Docker containers
    PHP_CONT = $(DOCKER_COMP) exec -e APP_ENV=$(env) php
    PHP_CONT_TEST = $(DOCKER_COMP) exec -e APP_ENV=test php
endif

# Executables
PHP      = $(PHP_CONT) symfony
COMPOSER = $(PHP_CONT) composer
SYMFONY  = $(PHP) console

# Misc
.DEFAULT_GOAL = help
.PHONY        : help docker-restart

## —— 🎵 🐳 The Symfony Docker Makefile 🐳 🎵 ——————————————————————————————————
help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9\./_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

## —— Docker 🐳 ————————————————————————————————————————————————————————————————
docker-build: ## Builds the Docker images
	@$(DOCKER_COMP) build --pull --no-cache

docker-up: ## Start the docker hub in detached mode (no logs)
	@$(DOCKER_COMP) up --detach --wait

docker-start: docker-build docker-up ## Build and start the containers

docker-restart: docker-down docker-up ## Build and start the containers

docker-down: ## Stop the docker hub
	@$(DOCKER_COMP) down --remove-orphans

docker-logs: ## Show live logs
	@$(DOCKER_COMP) logs --tail=0 --follow

docker-sh: ## Connect to the PHP FPM container
	@$(PHP_CONT) zsh

## —— Composer 🧙 ——————————————————————————————————————————————————————————————
composer: ## Run composer, pass the parameter "c=" to run a given command, example: make composer c='req symfony/orm-pack'
	@$(eval c ?=)
	@$(COMPOSER) $(c)

vendor: ## Install vendors according to the current composer.lock file
vendor: c=install --prefer-dist --no-progress --no-scripts --no-interaction
vendor: composer

composer-update: ## Composer update
composer-update: c=update --prefer-dist --no-progress --no-scripts --no-interaction
composer-update: composer

## —— Symfony 🎵 ———————————————————————————————————————————————————————————————
exec:
	@$(eval c ?=)
	docker compose exec -e APP_ENV=env php $(c)

sf: ## List all Symfony commands or pass the parameter "c=" to run a given command, example: make sf c=about
	@$(eval c ?=)
	@$(SYMFONY) $(c)

cc: c=c:c ## Clear the cache
cc: sf

stan:
	$(PHP_CONT) ./vendor/bin/phpstan analyse --memory-limit=256M $q

cs-fix:
	$(PHP_CONT) ./vendor/bin/php-cs-fixer fix $q --allow-risky=yes

lint:
	$(SYMFONY) lint:container $q
	$(SYMFONY) lint:yaml --parse-tags config/ $q
	$(SYMFONY) lint:twig templates/ $q
	$(SYMFONY) doctrine:schema:validate --skip-sync $q

analyze: lint stan cs-fix #infection ## Run all analysis tools

tests-all: ## Run all tests
	@$(MAKE) --no-print-directory database-drop env=test
	@$(MAKE) --no-print-directory doctrine-schema-create env=test
	@$(MAKE) --no-print-directory doctrine-fixtures env=test
	@$(MAKE) --no-print-directory paratest env=test

jobs ?= $(shell nproc)
paratest: ## Run tests
	$(PHP_CONT) rm -rf var/test{0-9}+.db
	$(PHP_CONT) zsh -c 'tee $(foreach TEST_TOKEN,$(shell seq 1 $(jobs)),var/test$(TEST_TOKEN).db) < var/test.db' >/dev/null
	$(PHP_CONT) ./vendor/bin/paratest --processes=$(jobs) --runner=WrapperRunner $(c)
	$(PHP_CONT) rm -rf var/test{0-9}+.db

test: ## Run tests
	$(PHP_CONT_TEST) ./vendor/bin/phpunit $(c)

database-drop:
	$(SYMFONY) doctrine:schema:drop --force --full-database $q

doctrine-migration:
	$(SYMFONY) make:migration $q

doctrine-migrate: ## Apply doctrine migrate
	$(SYMFONY) doctrine:migrations:migrate -n $q

doctrine-schema-create:
	$(SYMFONY) doctrine:schema:create $q

doctrine-fixtures:
	$(SYMFONY) doctrine:fixtures:load -n $q

doctrine-reset: database-drop doctrine-migrate
doctrine-apply-migration: doctrine-reset doctrine-migration doctrine-reset  ## Apply doctrine migrate and reset database

## —— Git 🎵 ———————————————————————————————————————————————————————————————

git-rebase:
	git pull --rebase
	git pull --rebase origin main

message ?= $(shell git branch --show-current | sed -E 's/^([0-9]+)-([^-]+)-(.+)/\2: \#\1 \3/' | sed "s/-/ /g")
git-auto-commit:
	git add .
	git commit -m "${message}" -q || true

current_branch=$(shell git rev-parse --abbrev-ref HEAD)
git-push:
	git push origin "$(current_branch)" --force-with-lease --force-if-includes

commit:
	@$(MAKE) --no-print-directory analyze
	@$(MAKE) --no-print-directory tests-all
	@$(MAKE) --no-print-directory git-auto-commit git-rebase git-push ## Commit and push the current branch
