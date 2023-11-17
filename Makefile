include .env
ifneq ("$(wildcard .env.local)","")
	include .env.local
endif
env=dev

# Executables (local)
DOCKER_COMP = docker compose

# Docker containers
PHP_CONT = $(DOCKER_COMP) exec php

# Executables
PHP      = $(PHP_CONT) php
COMPOSER = $(PHP_CONT) composer
SYMFONY  = $(PHP) bin/console

# Misc
.DEFAULT_GOAL = help
.PHONY        : help build up start down logs sh composer vendor sf cc

## —— 🎵 🐳 The Symfony Docker Makefile 🐳 🎵 ——————————————————————————————————
help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9\./_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

## —— Docker 🐳 ————————————————————————————————————————————————————————————————
docker-build: ## Builds the Docker images
	@$(DOCKER_COMP) build --pull --no-cache

docker-up: ## Start the docker hub in detached mode (no logs)
	@$(DOCKER_COMP) up --detach

docker-start: docker-build docker-up ## Build and start the containers

docker-restart: docker-down docker-up ## Build and start the containers

docker-down: ## Stop the docker hub
	@$(DOCKER_COMP) down --remove-orphans

docker-logs: ## Show live logs
	@$(DOCKER_COMP) logs --tail=0 --follow

docker-ls: ## Show live logs
	@$(DOCKER_COMP) lsdocker

docker-sh: ## Connect to the PHP FPM container
	@$(PHP_CONT) sh

## —— Composer 🧙 ——————————————————————————————————————————————————————————————
composer: ## Run composer, pass the parameter "c=" to run a given command, example: make composer c='req symfony/orm-pack'
	@$(eval c ?=)
	@$(COMPOSER) $(c)

vendor: ## Install vendors according to the current composer.lock file
vendor: c=install --prefer-dist --no-dev --no-progress --no-scripts --no-interaction
vendor: composer

## —— Symfony 🎵 ———————————————————————————————————————————————————————————————
sf: ## List all Symfony commands or pass the parameter "c=" to run a given command, example: make sf c=about
	@$(eval c ?=)
	@$(SYMFONY) $(c)

cc: c=c:c ## Clear the cache
cc: sf

stan:
	@APP_ENV=$(env) $(PHP_CONT) ./vendor/bin/phpstan analyse $q --memory-limit 256M

cs-fix:
	@APP_ENV=$(env) $(PHP_CONT) ./vendor/bin/php-cs-fixer fix $q --allow-risky=yes

lint:
	$(SYMFONY) lint:container $q
	$(SYMFONY) lint:yaml --parse-tags config/ $q
	$(SYMFONY) lint:twig templates/ $q
	$(SYMFONY) doctrine:schema:validate --skip-sync $q


analyze: lint stan cs-fix #infection ## Run all analysis tools

git-rebase:
	git pull --rebase
	git pull --rebase origin main

message ?= $( shell git branch --show-current | sed -E 's/^([0-9]+)-([^-]+)-(.+)/\2: #\1 \3/' | sed "s/-/ /g")
git-auto-commit:
	git add .
	git commit -m "${message}" -q || true

current_branch=$(shell git rev-parse --abbrev-ref HEAD)
git-push:
	git push origin "$(current_branch)" --force-with-lease --force-if-includes

commit: analyze git-auto-commit git-rebase git-push ## Commit and push the current branch
