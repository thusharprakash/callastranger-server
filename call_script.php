#!/usr/bin/env php
<?php
require_once "phpagi.php";
require_once "database.php";
$agi = new AGI();

$time = time();
$client_list = get_online_clients(); 
echo $client_list;
$times=4;
if($client_list ==null)
{
	die();
}
else
{
	foreach($client_list as $client)
	{
		$exten = $agi->get_variable('EXTEN');
		$caller = $agi->request['agi_callerid'];
		if($client == $caller)
		{
			continue;
		}
		$agi->set_callerid("Stranger". "<".$exten['data'].">");
		$data['timestamp']=time();
		$data['call_id']=$exten['data'];
		$data['caller']=$caller;
		$data['callee']=$$client;
		$data['ip_caller'] = get_ip_from_extension($caller);
		$data['ip_callee'] = get_ip_from_extension($strPhone);
		$query=create_query($data,'call_log');
	        mysqli_query($con,$query);
		$agi->exec("DIAL", "SIP/".$client.",15,L(180000)");
	        $dial_status = $agi->get_variable('DIALSTATUS');
		$data['call_status'] = $dial_status['data'];
		file_put_contents ("/var/www/html/phone_app/log.txt",json_encode($dial_status)."\n",FILE_APPEND);
		if($dial_status['data'] == "ANSWER")
		{
			die();
		}
		$times--;
	}
}
?>
