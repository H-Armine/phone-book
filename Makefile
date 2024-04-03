PLATFORM?=Docker
ENV?=Docker

DOCKER_COMPOSE=docker-compose

ifeq (, $(shell which docker-compose))
  DOCKER_COMPOSE=docker compose
endif

ifeq ($(PLATFORM), Docker)
	DOCKER_EXEC=$(DOCKER_COMPOSE) --env-file=.env.docker exec php
else
	$(error 'Docker not install.')
endif

init-frontend:
	$(DOCKER_EXEC) npm i
	$(DOCKER_EXEC) npm run build

init-backend:
	$(DOCKER_EXEC) composer install
	$(DOCKER_EXEC) php artisan migrate
	$(DOCKER_EXEC) php artisan key:generate --ansi
	$(DOCKER_EXEC) php artisan storage:link

init-dev-frontend:
	$(DOCKER_EXEC) npm i
	#$(DOCKER_EXEC) npm run dev
