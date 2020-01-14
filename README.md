# ServicePoint Kubernetes Platform
Skeleton and Deployment-scripts for ServicePoint Kubernetes Instances in Google Cloud Platform (GCP) on Google Kubernetes Engine (GKE). 

With this set of tools you can go from zero to fully-managed auto-scaleable containerised web-application running on ServicePoint K8S on GCP. Everything is running securely with TLS certificates and will auto-scale out-of-the-box. ServicePoint K8S supports scale-to-zero, meaning you can run your applications without cost, when there's no load.

![Welcome to the K8S by ServicePoint Skeleton Project by Daniel Bahl](https://servicepoint.blob.core.windows.net/servicepoint-files/CleanShot-2020-01-14-at-08.57.25-KIBI0fsLQzlWxCneYRfFtgWW8zDAMH78.png)


## Run the installer from your macOS terminal:

```
curl -o installer.php https://raw.githubusercontent.com/danielbahl/skeleton-cloudrun-gcp/master/installer.php && php installer.php
```

![Screenshot: Skeleton Installer Script](https://servicepoint.blob.core.windows.net/servicepoint-files/iTerm-2020-01-12-at-08.30.25-C21GBjqSUPZiO9MvmwWpxlQtQxsD61z2.png)

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


# What can I run in Cloud Run?

If a web-application can be packaged into a container-image (Docker) and can run on Linux (x86-64), it can be executed on Googles Cloud Run platform.

Web applications written in languages like Node.js, Python, Go, Java, Ruby, PHP, Rust, Kotlin, Swift, C/C++, C# will work on Cloud Run.

Cloud Run is designed to run stateless request-driven containers. This means you can deplo web applications, APIs or webhooks.

Cloud Run kan also be used for internal or private services with the new autentication layer, data transformation and background jobs and potentially triggered asynchronously by Pub/Sub events or Cloud Schelduler.

Other kinds of applications may not be fit for Cloud Run. If your application is doing processing while it’s not handling requests or storing in-memory state, it may not be suitable for Cloud Run.

This script is based on PHP 7.4, using the Docker Hub image "php:7.4-apache". This can easily be changed in the Dockerfile after running the installer script below.

# How is it different than Googles App Engine Flexible?

GAE Flexible and Cloud Run from Google are very similar in concept, but they differ when is comes to the underlying tech. They both accept container images as deployment input, they both automatically scale and manage the infrastructure that your code runs for you. However:

## Pricing/Autoscaling: 
The pricing model between GAE Flexible Environment and Cloud Run are a bit different.

In **GAE Flexible**, you are always running at least 1 instance at any time. So even if your app is not getting any requests, you’re paying for that instance. Billing granularity is 1 minute.

In **Cloud Run**, you are only paying when you are processing requests, and the billing granularity is 0.1 second. See here for an explanation of the Cloud Run billing model.

## Underlying infrastructure
Since GAE Flexible is running on VMs, it is a bit slower than Cloud Run to deploy a new revision of your app, and scale up. Cloud Run deployments are way faster.

## Portability
Cloud Run uses the open source Knative API and its container contract. This gives you flexibility and freedom to a greater extent. If you wanted to run the same workload on an infra you manage (for example a Kubernetes/k8s cluster like GKE), you could do it with "Cloud Run on GKE".

## Conclusion

* GAE Flexible is built on VMs, therefore is much slower to deploy and scale up.
* GAE Flexible does not scale to zero, at least 1 instance must be running.
* GAE Flexible billing has 1 minute granularity, Cloud Run is jut 0.1 second.


# How can I have cronjobs on Cloud Run?

Cronjobs is not recommended because you have an unknown number of containers, potentially hundreds or thousands, all of which will execute the same cron jobs. Also, Cloud Run is designed to run based on HTTP-requests, not requests from within the system. Therefore, cronjobs on the individual containers is not a thing on Cloud Run :) It's not the Cloud Way to do it! But luckly there's Cloud-service for this 😉☁️

If you need to invoke your Cloud Run applications periodically, use [Google Cloud Scheduler](https://cloud.google.com/scheduler/). This service can make a requests to your applications specific URL at an interval you specify. See at it as a modern Cloud-based crontab. 😎

# How can I handle logging from my app / service ?

When you write logs from your service, they will be picked up automatically by Stackdriver Logging so long as the logs are written to any of these locations:

* Standard output (stdout) or standard error (stderr) streams
* Any files under the /var/log directory
* syslog (/dev/log)
* Logs written using Stackdriver Logging Client Libraries, which are available for many popular languages

# Can I mount storage volumes or disks on Cloud Run?

Cloud Run currently doesn’t offer a way to bind or mount additional storage volumes on your filesystem. There's no FUSE, mount-points, [persistant disks][pd] etc.  

Of course, this does not mean that you do not have access to persistent storage. It just means you have to think a little differently when you code.

Let's take a look on how you can achive this in PHP with the a cheap Google Cloud Storage bucket.

### Install Google Cloud Storge lib. in PHP 7

To begin, install the preferred dependency manager for PHP, [Composer](https://getcomposer.org/).

Now to install just this component:

```sh
$ composer require google/cloud-storage
```

Or to install the entire suite of components at once, if you plan to use other Cloud-services from the Google Cloud Platform.

```sh
$ composer require google/cloud
```

### Authentication

Please see our [Authentication guide](https://github.com/googleapis/google-cloud-php/blob/master/AUTHENTICATION.md) for more information
on authenticating your client. Once authenticated, you'll be ready to start making requests.

### Upload a file and make it public

```php
require 'vendor/autoload.php';
use Google\Cloud\Storage\StorageClient;
$storage = new StorageClient();
$bucket = $storage->bucket('my_bucket'); // Define your Bucket

// Upload a file to the bucket.
$bucket->upload(
    fopen('/profile_pictures/image.jpg', 'r')
);

// Using Predefined ACLs to manage object permissions, you may
// upload a file and give read access to anyone with the URL.
$bucket->upload(
    fopen('/profile_pictures/image.jpg', 'r'),
    [
        'predefinedAcl' => 'publicRead'
    ]
);
```

### Stream Wrapper

```php
require 'vendor/autoload.php';
use Google\Cloud\Storage\StorageClient;
$storage = new StorageClient();
$storage->registerStreamWrapper();

$contents = file_get_contents('gs://my_bucket/profile_pictures/image.jpg');
```

## Cloud Run for Anthos on GKE

Cloud Run for Anthos **on GKE** allows you to mount Kubernetes [Secrets] and [ConfigMaps], but **this is not yet fully supported**. See an example [here][sec-ex] about mounting [Secrets] to a Service running on GKE.

[pd]: https://cloud.google.com/persistent-disk/
[vols]: https://cloud.google.com/kubernetes-engine/docs/concepts/volumes
[Secrets]: https://cloud.google.com/kubernetes-engine/docs/concepts/secret
[ConfigMaps]: https://cloud.google.com/kubernetes-engine/docs/concepts/configmap
[sec-ex]: https://knative.dev/docs/serving/samples/secrets-go/

# Cloud Run Pricing

> [Cloud Run Pricing documentation][pricing] has the most up-to-date information on pricing.

[pricing]: https://cloud.google.com/run/pricing

# Is there a “Free Tier”?

Yes! You can run small project for free. See [Pricing documentation][pricing] for more info.
Normally I create both -prod and -dev env. and I can almost every time run the entire dev-enviroment for free.
Small projets like personal websites etc. can also be run for free or with a very low cost (unless you have a lot of traffic).

# When am I charged?

You only pay **while a request is being handled** on your container instance.

This means an application that is not getting traffic is **free of charge**.

# How is billed time calculated?

Based on "time serving requests" on each instance. If your service handles multiple requests simultaneously, you do not pay for them separately. (This is a real **cost saver!**)

Each billable timeslice is **rounded up** to the nearest **100 milliseconds**.
