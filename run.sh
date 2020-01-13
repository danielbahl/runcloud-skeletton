#!/bin/bash

# Read config
. cloudrun.ini

# Define Docker.yaml
tee Docker.yaml << END
version: '3'
services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    image: ${projectid}
    ports:
      - 80:80
    environment:
      - PORT=80
    volumes:
      - ./project/:/var/www/html/
END

# Show http://localhost in Safari webbrowser
screen -dm bash -c 'sleep 2; open -a /Applications/Safari.app http://localhost;'

# Build Docker.yaml
docker-compose -f Docker.yaml up

# Unmount and kill docker container
docker-compose -f Docker.yaml down