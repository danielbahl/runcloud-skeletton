#!/bin/bash

# Read config
. cloudrun.ini

# Build
gcloud config set project $projectid
gcloud builds submit --tag eu.gcr.io/$projectid/$serviceid
gcloud run deploy $serviceid --image eu.gcr.io/$projectid/$serviceid:latest --platform=managed --region=$region --allow-unauthenticated --memory=$memory --timeout=$timeout --max-instances=$maxinstances --concurrency=$concurrency