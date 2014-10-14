<?php
# These files are required for interfacgin with AWS
require '/var/www/gc/aws/aws-autoloader.php';
require '/var/www/gc/g8_cred.php';

# Instantiating an object
$g8_Client = \Aws\Ec2\Ec2Client::factory($g8_config);
echo g8_get_instance_info($g8_Client,'i-289fe803,i-ef6c3c01');

 g8_aws_start_instance($g8_Client,'i-289fe803');


// g8_aws_stop_instance($g8_Client,'i-289fe803');
 //g8_create_instance($g8_Client,'i-289fe803');

//g8_aws_run_instance($g8_Client,'i-289fe803');

 echo g8_aws_reboot_instance($g8_Client,'i-ef6c3c01');

#function for starting a machine
function g8_aws_start_instance($g8_Client,$g8_instance_id=''){
	$g8_inst_array=explode(",",$g8_instance_id);
	return $g8_result = $g8_Client->startInstances(array('InstanceIds' => $g8_inst_array));
}

#function for stopping a machine
function g8_aws_stop_instance($g8_Client,$g8_instance_id=''){
	$g8_inst_array=explode(",",$g8_instance_id);
	return $g8_result = $g8_Client->stopInstances(array('InstanceIds' => $g8_inst_array));
}

#function for copy image of a machine
function g8_create_instance($g8_Client,$g8_instance_id=''){
	$g8_inst_array=explode(",",$g8_instance_id);
   return $g8_result = $g8_Client->createImage(array('InstanceId' => 'i-289fe803','Name' => 'Punit1','Description' => 'Test machine'));
}


function g8_aws_reboot_instance($g8_Client,$g8_instance_id='')
{
	$g8_inst_array=explode(",",$g8_instance_id);
	
return	$g8_result = $g8_Client->rebootInstances(array('InstanceIds' => $g8_inst_array));
	
}
function g8_aws_run_instance($g8_Client,$g8_instance_id='')
{
	
}

function g8_get_instance_info($g8_Client,$g8_instance_id='') {
	$g8_inst_array=explode(",",$g8_instance_id);
	# Getting the results of DescribeInstance command - this is what you would change for reboot Instance, StopInstance Startinstance etc.
	$g8_result = $g8_Client->DescribeInstances(array( 'InstanceIds' => $g8_inst_array));
	# $g8_result now has a long list of information
	$g8_str="";

	# Here we are displaying a whole lof of information received.
	$g8_reservations = $g8_result['Reservations'];
	foreach ($g8_reservations as $reservation) {
	    $instances = $reservation['Instances'];
	    foreach ($instances as $instance) {

	        $instanceName = '';
	        foreach ($instance['Tags'] as $tag) {
	            if ($tag['Key'] == 'Name') {
                $instanceName = $tag['Value'];
            }
        }

       	$g8_str.='Instance Name: ' . $instanceName . "<BR>".PHP_EOL;
       	$g8_str.='<ul><li>State: <b>' . ucwords($instance['State']['Name']) . "</b><BR>".PHP_EOL;
	if($instance['PublicIpAddress'] ) {
	       	$g8_str.='<li> Public IP: <b>' . $instance['PublicIpAddress'] . "</b><BR>".PHP_EOL;
	}
       	$g8_str.='<li> Instance ID: <b>' . $instance['InstanceId'] . "</b><BR>".PHP_EOL;
	if($instance['StateReason']['Code']) {
	       	$g8_str.='<li> State reason: ' . $instance['StateReason']['Code'] . "<BR>".PHP_EOL;
	}
       	$g8_str.='<li> Image ID: ' . $instance['ImageId'] ."<BR>". PHP_EOL;
	if($instance['PrivateDnsName'] ){
	       	$g8_str.='<li> Private Dns Name: ' . $instance['PrivateDnsName'] ."<BR>". PHP_EOL;
	}
      	$g8_str.='<li> Launch time: ' . $instance['LaunchTime'] ."<BR>". PHP_EOL;
       	$g8_str.='<li> Instance Type: ' . $instance['InstanceType'] . "<BR>".PHP_EOL;
	if($instance['PrivateIpAddress'] ) {
	       	$g8_str.='<li> Private IP: ' . $instance['PrivateIpAddress'] . "<BR>".PHP_EOL;
	}
       	$g8_str.='<li> Security Group: ' . $instance['SecurityGroups'][0]['GroupName'] ."<BR>". PHP_EOL;
       	$g8_str.='<li> Disk1: ' . $instance['BlockDeviceMappings'][0]['Ebs']['VolumeId'] ."<BR>". PHP_EOL;
	if($instance['BlockDeviceMappings'][1]['Ebs']['VolumeId']) {
	       	$g8_str.='<li> Disk2: ' . $instance['BlockDeviceMappings'][1]['Ebs']['VolumeId'] ."<BR>". PHP_EOL;
	}
	if($instance['BlockDeviceMappings'][2]['Ebs']['VolumeId']) {
	       	$g8_str.='<li> Disk3: ' . $instance['BlockDeviceMappings'][2]['Ebs']['VolumeId'] ."<BR>". PHP_EOL;
	}
	$g8_str.="</ul><hr>";
    }

   }
// ***********************
/* 
# I have left the raw output also open - to study the output content in case something else is required.
echo "<pre>";
	print_r($g8_result);
echo "<pre>";
*/
   return $g8_str;
}
?>
