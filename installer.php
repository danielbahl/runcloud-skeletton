<?php

/*
 * This file is part of Cloud Run Skeleton Project 
 * https://github.com/danielbahl/servicepoint-k8s-launcher
 *
 * (c) Daniel Bahl <me@danielbahl.com>
 *     Tristan White <oliver.tristan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * One-line installer:
 $ curl -o installer.php https://raw.githubusercontent.com/danielbahl/servicepoint-k8s-launcher/master/installer.php && php installer.php
 *
 */


// Install Path
$installpath = "~/Desktop/"; // trailing slash, ex. ~/Desktop/ will result in ~/Desktop/{projectname}/{servicename}/

setupEnvironment();
//process(is_array($argv) ? $argv : array());

/**
 * Ensures the environment is sane
 */
function setupEnvironment()
{
	ini_set('display_errors', 1);
	system('clear');
}

echo "\n\n";

echo "🦴 Welcome to the ☁️  Cloud Run ☁️  Skeleton Project 🦴\n\n";
echo "This script will guide you through the follow steps:\n\n";
echo " ✅ Download and install (if not already installed) Docker and gcloud SDK\n";
echo " ✅ Generate the config-file for the Cloud Run scripts\n";
echo " ✅ Create new project and enable APIs in the Google Cloud Platform\n";
echo " ✅ Download the latest version of the Cloud run Skeleton scripts\n";
echo " ✅ Generate Dockerfile and corresponding YAML file\n";
echo "\nℹ️  Please note: Cloud Run Skeleton will be installed under ~/Desktop/{projectname}/{servicename}/ but can later be moved to anywhere. If you wish to change this install-path, you can edit the installpath-variable in the installer.php file and run this script again.\n\n";

echo "Are you ready to run this installer?  Type 'Y' to continue: ";
$handle = fopen ("php://stdin","r");
$line = fgets($handle);
if(trim(strtolower($line)) != 'y'){
	echo "Okay, bye!! :)\n";
	exit;
}

/*************
*** Checking if needed tools are installed ***
*************/

echo "\n";
echo "Checking if Docker is installed...\n";
$output_docker = shell_exec('docker info | grep -q "Containers" && echo "installed" || echo "NA"');
if(trim($output_docker) != "installed") {
	system("clear");
	echo "🚧🚧🚧  Docker is not installed 🚧🚧🚧\n";
	echo " Please download and install Docker: \n";
	echo " Opening URL @ https://docs.docker.com/docker-for-mac/install/\n";
	echo "\n ▶️  Run this script again after installing Docker.";
	echo "\n 😈 If this message shows again after installing Docker, try exit and reopen your terminal or restart your shell ($ exec -l \$SHELL).\n\n";
	system("open https://docs.docker.com/docker-for-mac/install/");
	exit;
} else {
	echo "✅  Docker is installed :-)\n\n";
}
echo "\n";
echo "Checking if gcloud SDK is installed...\n";
$output_gcloud = shell_exec('gcloud version | grep -q "SDK" && echo "installed" || echo "NA"');
if(trim($output_gcloud) != "installed") {
	system("clear");
	echo "🚧🚧🚧  gcloud SDK is not installed 🚧🚧🚧\n";
	echo " Please download and install the latest version of the Google Cloud SDK: \n";
	echo " Opening URL @ https://cloud.google.com/sdk/docs/downloads-interactive\n";
	echo "\n ▶️  Run this script again after installing gcloud.";
	echo "\n 😈 If this message shows again after installing gcloud, try exit and reopen your terminal or restart your shell ($ exec -l \$SHELL).\n\n";
	system("open https://cloud.google.com/sdk/docs/downloads-interactive");
	exit;
} else {
	echo "✅  gcloud SDK is installed :-)\n\n";
}


/*************
*** Asking for projectname ***
*************/
system("clear");

echo "🚢  Project Name: A unique, user-assigned ID of the Project 🚢\n";
echo "A project organizes all your Google Cloud resources. We recommend creating a seperate project for every Cloud Run website, to seperate billing, authentication, monitoring, DNS, Cloud SQL, Docker images etc.\n\n"; 
echo "ℹ️  If you type the ID of an existing project, this will be used, if the projectID does not exists, it will be created!\n\n";

echo "It must be 6 to 30 lowercase letters, digits, or hyphens. It must start with a letter. Trailing hyphens are prohibited. Example: copenhagen-rain-123\n\n";
echo "Input your Project ID: ";
$handle = fopen ("php://stdin","r");
$line = fgets($handle);
$projectID = trim(strtolower($line));
if(empty($projectID)){
	echo "No Project ID? I will cancel the script :)\n";
	exit;
}

/*************
*** Asking for servicename ***
*************/
system("clear");

echo "🛎  Service identity: A unique, user-assigned ID of the Cloud Run Serivce 🛎\n";
echo "Under a project, you can run multiple Cloud Run services. We recommend using the same Service Name as the Project Name, if you are just running a simple website. If you are running multiple services under the same project, you can defined them with individual naming like ex. frontend, backend, api etc. .\n\n"; 
echo "⚠️  If you type the ID of an existing service under the same project, this service will be overwritten, if the seriveID does not exists, it will be created!\n\n";

echo "It must be 6 to 30 lowercase letters, digits, or hyphens. It must start with a letter. Trailing hyphens are prohibited. Example: ".$projectID."\n\n";
echo "Input your Service identity (leave this empty to use '".$projectID."'): ";
$handle = fopen ("php://stdin","r");
$line = fgets($handle);
$serviceID = trim(strtolower($line));
if(empty($serviceID)){
	$serviceID = $projectID;
}


/*************
*** Clear screen and sum up ***
*************/

system("clear");
echo "\n🚢  Project ID: " . $projectID . "\n";
echo "🛎  Service ID: " . $serviceID . "\n";
echo " =======  \n\n";


/*************
*** Create Folder Structure ***
*************/

echo "📁 Crafting directory-structure: " . $installpath . $projectID . "/" . $serviceID."/\n";

system("mkdir -p " . $installpath . $projectID . "/");
system("mkdir -p " . $installpath . $projectID . "/" . $serviceID."/");
system("mkdir -p " . $installpath . $projectID . "/" . $serviceID."/project/");
system("touch " . $installpath . $projectID . "/" . $serviceID."/project/index.php");
system("touch " . $installpath . $projectID . "/" . $serviceID."/cloudrun.ini");
/*system("echo '<?php echo \"Hello world.\"; ?>' >> " . $installpath . $projectID . "/" . $serviceID . "/project/" . "index.php");*/

echo "✅  Directories created... \n\n";

/*************
*** Download latest script ***
*************/

echo "⤵️  Downloading scripts... \n\n";

$filesToDownload = array(
'build.sh' => 'https://raw.githubusercontent.com/danielbahl/servicepoint-k8s-launcher/master/build.sh', // build.sh
'run.sh' => 'https://raw.githubusercontent.com/danielbahl/servicepoint-k8s-launcher/master/run.sh', // run.sh
'Dockerfile' => 'https://raw.githubusercontent.com/danielbahl/servicepoint-k8s-launcher/master/Dockerfile', // Dockerfile
'dev.sh' => 'https://raw.githubusercontent.com/danielbahl/servicepoint-k8s-launcher/master/dev.sh', // dev.sh
'init.sh' => 'https://raw.githubusercontent.com/danielbahl/servicepoint-k8s-launcher/master/init.sh', // init.sh
'project/index.php' => 'https://raw.githubusercontent.com/danielbahl/servicepoint-k8s-launcher/master/helloworld.php', // project/index.php
);
foreach($filesToDownload as $fileToDownloadName => $fileToDownloadUri ) {

	
	echo "👇 Downloading " . $fileToDownloadName . " to " . $installpath . $projectID . "/" . $serviceID."/" . $fileToDownloadName ." \n";
	
	system("curl -o " . $installpath . $projectID . "/" . $serviceID."/" . $fileToDownloadName . " " . $fileToDownloadUri);
	if($fileToDownloadName != "project/index.php") {
		system("chmod +x " . $installpath . $projectID . "/" . $serviceID."/" . $fileToDownloadName);
	}
	
}

echo "✅  Scripts downloaded... \n\n";


/*************
*** Clear screen and sum up ***
*************/

system("clear");
echo "\n🚢  Project ID: " . $projectID . "\n";
echo "🛎  Service ID: " . $serviceID . "\n";
echo " =======  \n\n";



/*************
*** Generate Config-file ***
*************/

echo "⤵️  Crafting the config-file... \n\n";

echo "🌎  Pick a region\n ==========";
echo "\n1) asia-northeast1 (Tokyo)\n
2) europe-west1 (Belgium)\n
3) us-central1 (Iowa) (default)\n
4) us-east1 (South Carolina)\n\n";

echo "Pick a Region (1-4): ";
$handle = fopen ("php://stdin","r");
$line = fgets($handle);
$regionID = intval($line);
if($regionID == 1) {
	$region = "asia-northeast1";
} elseif($regionID == 2) {
	$region = "europe-west1";
} elseif($regionID == 4) {
	$region = "us-east1";
} else {
	$region = "us-central1";
}


echo "🚀  Pick Deployment Platform\n ==========";
echo "\n1) Fully managed Cloud Run (default)\n
2) Google Kubernetes Engine (GKE) from Google Cloud\n
3) Kubernetes (Knative-compatible kubernetes cluster)\n\n";

echo "Pick a Platform (1-3): ";
$handle = fopen ("php://stdin","r");
$line = fgets($handle);
$platformID = intval($line);
if($platformID == 1) {
	$platform = "managed";
} elseif($platformID == 2) {
	$platform = "gke";
} elseif($platformID == 3) {
	$platform = "kubernetes";
} else {
	$platform = "managed";
}

/*************
*** Clear screen and sum up ***
*************/

system("clear");
echo "\n🚢  Project ID: " . $projectID . "\n";
echo "🛎   Service ID: " . $serviceID . "\n";
echo "🌎  Region: " . $region . "\n";
echo " =======  \n\n";


echo "🌎  Input your Billing Account ID\n ==========";
echo "\n Please type your Billing Account ID, this is needed to create new projects and enable the Cloud Run API";
echo "\n You can find your Billing Account ID here: https://console.cloud.google.com/billing?project=&folder=&organizationId=0&supportedpurview=project";
echo "\n The format is XXXXXX-XXXXXX-XXXXXX, ex. 00A43B-BA04EC-D2439B\n\n";

echo "Input your Google Billing account ID: ";
$handle = fopen ("php://stdin","r");
$line = fgets($handle);
$billingID = trim(strtoupper($line));

$configfile = '# Region in which the resource can be found.
# More info: https://cloud.google.com/run/docs/locations
region='.$region.'

# Platform
# Target platform for running commands:
# managed (default Cloud Run), 
# gke (Cloud Run on Google Kubernetes Engine) 
# kubernetes (Cloud Run on knative-compatible kubernetes cluster)
platform='.$platform.'

# Set a memory limit per container. 
# Ex: 1Gi, 512Mi. 
# Max 2Gi. Min 128Mi.
memory=128Mi

# The maximum number of container instances of the Service to run.
# Use default to unset the limit and use the platform default.
maxinstances=5

# Set the number of concurrent requests allowed per container instance. 
# Max 80. Min 1.
concurrency=80

# Time within which a response must be returned
# Maximum 3600 seconds (1 hour).
timeout=300

# Project
# It must be 6 to 30 lowercase letters, digits, or hyphens.
# It must start with a letter. Trailing hyphens are prohibited.
projectid='.$projectID.'

# Service
# It must be 6 to 30 lowercase letters, digits, or hyphens.
# It must start with a letter. Trailing hyphens are prohibited.
serviceid='.$serviceID.'

# Billing account ID
# Find it here: https://console.cloud.google.com/billing?project=&folder=&organizationId=0&supportedpurview=project
billingaccountid="'.$billingID.'"';

$configfileArr = explode("\n", $configfile);
foreach ($configfileArr as $configfileLine) {
	system("echo '".$configfileLine."' >> " . $installpath . $projectID . "/" . $serviceID . "/" . "cloudrun.ini");
}
//file_put_contents($installpath . $projectID . "/" . $serviceID . "/" . "cloudrun.ini", $configfile); # does not understnd ~
system("clear");


echo "✅  Your new project is ready to launch 🚀" . "\n";
echo "\n";

echo "\n🚢  Project ID: " . $projectID . "\n";
echo "🛎   Service ID: " . $serviceID . "\n";
echo "🌎  Region: " . $region . "\n";
echo "📁  Deployed to: " . $installpath . $projectID . "/" . $serviceID . "\n";
echo "      Tip: Running iTerm2 on macOS? Just cmd+click this path to open Finder.\n";
echo " =======  \n\n";
echo "Now let's init this project! 🔥\n";
echo "Everything we have done so far has been just preparation. We haven't submitted anything to Google yet!\n";
echo "To start this process, you must now run the init.sh script from the deployment directory.\n";
echo "\n";
echo "This is done as follows:\n";
echo "\n";
echo "$ cd " . $installpath . $projectID . "/" . $serviceID . "/\n";
echo "$ ./init.sh\n";
echo "\nFollow the guide from the init-script and you will be running live within approx. 13 seconds 😎.\n";
echo " --- Thank you for using the Cloud Run Skeleton Project --- ";
echo "\n\n";
exit;
