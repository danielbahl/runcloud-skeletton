# Google Cloud Platform (GCP) - Cloud Run
Skeleton and Deployment-scripts for Cloud Run Instances in Google Cloud Platform (GCP)

## config.ini

Config-file used for defining variables like project-ID, service-ID, billing-ID and container specifications.

```
# Region in which the resource can be found.
region=us-central1

# Set a memory limit. Ex: 1Gi, 512Mi.
memory=128Mi

# The maximum number of container instances of the Service to run. Use 'default' to unset the limit and use the platform default.
maxinstances=5

# Set the number of concurrent requests allowed per container instance. Max 80
concurrency=80

# Time within which a response must be returned (maximum 3600 seconds).
timeout=300

# The Overall Project-name for this project
# Will be created automatically by the script is not exiting!
# You can use lowercase a-z, 0-9 and dashes (-)
projectid=projectname

# The service-name for this project
# Will be created automatically by the script is not exiting!
# You can use lowercase a-z, 0-9 and dashes (-)
serviceid=servicename

# Billing Account ID
# Find it here: https://console.cloud.google.com/billing?project=&folder=&organizationId=0&supportedpurview=project
billingaccountid="XXXXXX-XXXXXX-XXXXXX"
```
## init.sh

Run this first to build your new project. 

## run.sh

Build and run a local docker

## dev.sh

Build and deploy a dev-enviroment in Google Cloud Platform Cloud Run.

## build.sh

Build and deploy a prod-enviroment in Google Cloud Platform Cloud Run. 
You can map your domain here.

## pull.sh

Request the latest build from the Container Repo.
