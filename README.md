# Google Cloud Platform (GCP) - Cloud Run
Skeleton and Deployment-scripts for Cloud Run Instances in Google Cloud Platform (GCP). 

With this set of tools you can go from zero to fully-managed auto-scaleable containerised web-application running on Cloud Run from GCP. Everything is running securely with TLS certificates and will auto-scale out-of-the-box. Cloud Run also supports scale-to-zero meaning you can run your applications without cost, when there's no load.

![Welcome to the Cloud Run Skeleton Project by Daniel Bahl](https://servicepoint.blob.core.windows.net/servicepoint-files/CleanShot-2020-01-12-at-09.06.56-6PLS9MfvNvoZ81x5Jg7EeoWyCIU2BwpD.png)

## What can I run in Cloud Run?

If a web-application can be packaged into a container-image (Docker) and can run on Linux (x86-64), it can be executed on Googles Cloud Run platform.

Web applications written in languages like Node.js, Python, Go, Java, Ruby, PHP, Rust, Kotlin, Swift, C/C++, C# will work on Cloud Run.

Cloud Run is designed to run stateless request-driven containers. This means you can deplo web applications, APIs or webhooks.

Cloud Run kan also be used for internal or private services with the new autentication layer, data transformation and background jobs and potentially triggered asynchronously by Pub/Sub events or Cloud Schelduler.

Other kinds of applications may not be fit for Cloud Run. If your application is doing processing while it’s not handling requests or storing in-memory state, it may not be suitable for Cloud Run.

This script is based on PHP 7.4, using the Docker Hub image "php:7.4-apache". This can easily be changed in the Dockerfile after running the installer script below.


```
  _______             __  ___          
 / ___/ /__  __ _____/ / / _ \__ _____ 
/ /__/ / _ \/ // / _  / / , _/ // / _ \
\___/_/\___/\_,_/\_,_/ /_/|_|\_,_/_//_/
                                       
```

## Run the installer from your macOS terminal:

```
curl -o installer.php https://raw.githubusercontent.com/danielbahl/skeleton-cloudrun-gcp/master/installer.php && php installer.php
```

![Screenshot: Cloud Run Skeleton Installer Script](https://servicepoint.blob.core.windows.net/servicepoint-files/iTerm-2020-01-12-at-08.30.25-C21GBjqSUPZiO9MvmwWpxlQtQxsD61z2.png)

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
