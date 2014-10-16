<?php
# These files are required for interfacgin with AWS
require '/var/www/g8gc/aws/aws-autoloader.php';
require '/var/www/g8gc/g8_cred.php';

# Instantiating an object
$g8_Client = \Aws\Ec2\Ec2Client::factory($g8_config);

$_REQUEST['g8_lo_id'];
$_REQUEST['g8_lab_status'];



?>
