<?php 


/*function g8_get_gc_value ($g8_lo_id,$g8_cmd,$g8_param,$g8_value)
{
	
}*/

function g8_set_gc_value ($g8_lo_id,$g8_cmd,$g8_param,$g8_value)
{
	
	
	$g8_str='';
		switch(strtolower($g8_param))
		{
			case 'switch_on':
				$g8_str = g8_aws_start_instance($_SESSION['g8_Client'],'i-9773d579');
				break;
			
			case 'switch_off':
				$g8_str = g8_aws_stop_instance($_SESSION['g8_Client'],'i-9773d579');
				break;
			
			case 'reboot':
				$g8_str = g8_aws_reboot_instance($_SESSION['g8_Client'],'i-9773d579');
				break;

				
		}
	return $g8_str;
}



#function for starting a machine
function g8_aws_start_instance($g8_Client,$g8_instance_id=''){
	$g8_inst_array=explode(",",$g8_instance_id);
	 $g8_result = $g8_Client->startInstances(array('InstanceIds' => $g8_inst_array));
	
	$g8_str="";
	//$g8_result= var_dump($g8_result);
	
	# Here we are displaying a whole lof of information received.
	$g8_reservations = $g8_result['StartingInstances'];
	foreach ($g8_reservations as $g8_reservation) {
			   //$g8_reservation['InstanceId'];
			  $g8_str = $g8_reservation['CurrentState']['Name'];
			}
	return $g8_str;
	
}

#function for stopping a machine
function g8_aws_stop_instance($g8_Client,$g8_instance_id=''){
	$g8_inst_array=explode(",",$g8_instance_id);
	$g8_result = $g8_Client->stopInstances(array('InstanceIds' => $g8_inst_array));
	$g8_str="";
	//$g8_result= var_dump($g8_result);
	
	# Here we are displaying a whole lof of information received.
	$g8_reservations = $g8_result['StoppingInstances'];
	foreach ($g8_reservations as $g8_reservation) {
			   //$g8_reservation['InstanceId'];
			  $g8_str = $g8_reservation['CurrentState']['Name'];
			}
	return $g8_str;
}

function g8_aws_reboot_instance($g8_Client,$g8_instance_id='')
{
	$g8_inst_array=explode(",",$g8_instance_id);
	$g8_result = $g8_Client->rebootInstances(array('InstanceIds' => $g8_inst_array));
}




#function for copy image of a machine
function g8_create_instance($g8_Client,$g8_instance_id=''){
	//$g8_inst_array=explode(",",$g8_instance_id);
   echo $g8_result = $g8_Client->createImage(array('InstanceId' => 'i-082a8e24','Name' => 'Punit6','Description' => 'Test machine'));
}






function g8_aws_run_instance($g8_Client)
{
	
echo $result = $g8_Client->runInstances(array(
    'ImageId'        => 'ami-98aa1cf0',
    'MinCount'       => 1,
    'MaxCount'       => 1,
    'InstanceType'   => 't1.micro',
    'KeyName'        => 'Wiley_test',
    'SecurityGroups' => array('launch-wizard-29'),
));


	
}


function g8_aws_terminate_instance($g8_Client,$g8_instance_id='')
{

	 $g8_inst_array=explode(",",$g8_instance_id);
	 $g8_result = $g8_Client->terminateInstances(array('InstanceIds' => $g8_inst_array));
	
		/*	$g8_reservations = $g8_result['TerminatingInstances'];
			foreach ($g8_reservations as $reservation) {
				 $instances = $reservation['TerminatingInstances'];
				
				echo ' Instance ID: <b>' . $instance['CurrentState'] . "</b><BR>".PHP_EOL;
				
				
				
			}
*/
}






function g8_get_instance_info($g8_Client,$g8_instance_id='') {
	$g8_inst_array=explode(",",$g8_instance_id);
	# Getting the results of DescribeInstance command - this is what you would change for reboot Instance, StopInstance Startinstance etc.
    $g8_result = $g8_Client ->DescribeInstances(array( 'InstanceIds' => $g8_inst_array));
	# $g8_result now has a long list of information
	$g8_str="";

	# Here we are displaying a whole lof of information received.
	$g8_reservations = $g8_result['Reservations'];
	foreach ($g8_reservations as $reservation) {
			  $reservation['OwnerId'];
	    $instances = $reservation['Instances'];
	    foreach ($instances as $instance) {
			
			//echo $instance['State'];
				//echo $instance['PrivateDnsName'];
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
       	
       	
       	
       		$g8_str.='<li> PrivateDnsName: <b>' . $instance['PrivateDnsName'] . "</b><BR>".PHP_EOL;
       		
       		
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

