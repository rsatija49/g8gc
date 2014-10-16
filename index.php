<?php
session_start();

# These files are required for interfacgin with AWS

require '/var/www/g8gc/lib/g8_core_lib.php';

require '/var/www/g8gc/aws/aws-autoloader.php';
require '/var/www/g8gc/g8_cred.php';
require '/var/www/g8gc/lib/g8_gc_lib.php';





# Instantiating an object
$g8_Client = \Aws\Ec2\Ec2Client::factory($g8_config);

$_SESSION['g8_Client'] = $g8_Client;
echo g8_get_instance_info($g8_Client,'i-eb9ac105,i-9773d579');
 

//echo g8_aws_start_instance($g8_Client,'i-eb9ac105');
//echo g8_aws_stop_instance($g8_Client,'i-eb9ac105');
// echo g8_create_instance($g8_Client,'i-082a8e24');
 // echo g8_aws_run_instance($g8_Client); 
 // g8_aws_reboot_instance($g8_Client,'i-ef6c3c01');





if(isset($_REQUEST['g8_lo_id'])) {
	$g8_lo_id=$_REQUEST['g8_lo_id'];
} else {
	# This is the default ID to be used, if nothing is specified.
	$g8_lo_id="";

}

if(isset($_REQUEST['g8_cmd'])) {
	$g8_cmd=$_REQUEST['g8_cmd'];
} else {
	$g8_cmd="SET";
	
}

if(isset($_REQUEST['g8_param'])) {
	$g8_param=$_REQUEST['g8_param'];
} else {
	$g8_param="";

}

if(isset($_REQUEST['g8_value'])) {
	$g8_value=$_REQUEST['g8_value'];
} else {
	$g8_value="";
	
}



switch($g8_cmd)
{
	case 'GET':
		$g8_str.= g8_get_gc_value ($g8_lo_id,$g8_cmd,$g8_param,$g8_value);
		break;
	
	default:
	case 'SET':

		$g8_str.= g8_set_gc_value ($g8_lo_id,$g8_cmd,$g8_param,$g8_value);
		break;
}

echo $g8_str;




?>
