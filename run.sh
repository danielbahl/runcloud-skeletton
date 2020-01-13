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

# Build Docker.yaml 
docker-compose -f Docker.yaml up
docker-compose -f Docker.yaml down