#!/bin/bash

# Read config
. cloudrun.ini

#Update and download gcloud components
gcloud components update
gcloud components install beta

# Authenticate
gcloud auth login

# Create new project and enable cloud run API
gcloud projects create $projectid
gcloud config set project $projectid
gcloud beta billing projects link $projectid --billing-account=$billingaccountid
gcloud services enable run.googleapis.com # Enable Cloud Run API
gcloud services enable containerregistry.googleapis.com # Enable Container Registry API for storing docker images
gcloud services enable cloudbuild.googleapis.com # For building Docker-images
