<?php

/*
 * This file is part of Cloud Run Skeleton Project 
 * https://github.com/danielbahl/skeleton-cloudrun-gcp
 *
 * (c) Daniel Bahl <me@danielbahl.com>
 *     Tristan White <oliver.tristan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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

