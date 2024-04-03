#!/bin/bash

echo "Starting and provisioning the project"

if [ -e .env ]
then
    echo ".env already exists"
else
    cp .env.example .env
fi

if [ -e .env.docker ]
then
    echo ".env.docker already exists"
else
    cp .env.docker.example .env.docker
fi

DOCKER_COMPOSE="docker-compose"

if ! type "docker-compose" > /dev/null; then
  DOCKER_COMPOSE="docker compose"
fi

COMPOSE="$DOCKER_COMPOSE --env-file=.env.docker -f docker-compose.yml"

$COMPOSE down
$COMPOSE up -d --build

# Run composer install and migrations
make init-backend

# Run npm install && npm run watch
make init-dev-frontend
