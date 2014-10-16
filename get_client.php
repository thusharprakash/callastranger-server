<?php
require_once 'database.php';
if($_REQUEST['id']=="NEW")
{

	$prev_id = file_get_contents("last_inserted_name.txt");
	$id = trim($prev_id)+1;
	$fields['name']=$id;
	$fields['defaultuser']=$id;
	$fields['host']='dynamic';
	$fields['type']='friend';
	$fields['context']='nethram';
	$fields['permit']='0.0.0.0/0.0.0.0';
	$fields['secret']=$sip_password;
	$fields['qualify']='20000';
	$fields['transport']='tcp,udp';
	$fields['directmedia']='no';
	$fields['nat']='force_rport';
	$query = create_query($fields,'sip_devices');
	mysqli_query($con,$query);
	if(mysqli_error($con)!=null)
	{
		echo mysqli_error($con);
		$result['status']='error';
		$id=$id-1;
	}
	else
	{
	
		$result['status']='succes';
		$result['server']=$ip_address;
		$result['password']=$sip_password;
		$result['id']=$id;
		$result['username']=(string)$id;
		$result['number']=$phone;
		$result['rating']="3.0";
	}
	file_put_contents("last_inserted_name.txt",$id);
}
else 
{

	 if(!isset($_REQUEST['id']) || trim($_REQUEST['id']) == null)
	 {
		die();
	 }
	 $id=$_REQUEST['id'];
	 $result['server']=$ip_address;
         $result['password']=$sip_password;
         $result['rating']="3.0";
         $result['username']=(string)$id;	
}
echo json_encode($result);
?>
