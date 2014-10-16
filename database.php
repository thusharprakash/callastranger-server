<?php
$host="localhost";
$username="root";
$password="root@123";
$db="realtime";
$ip_address = "192.168.0.54";
$sip_password="1234";
$phone="1000";
$con = mysqli_connect($host,$username,$password,$db);
if (mysqli_connect_errno())
{
	echo "Failed to connect to MySQL: " . mysqli_connect_error();
}

function create_query($data,$table)
{
	global $con;
	foreach($data as $key=>$value)
	{
		$data[$key] = mysqli_real_escape_string($con,$value);
	}
	$fields = implode(',', array_keys($data));
	$values = implode("', '", array_values($data));
	$query = sprintf("insert into ".$table." (%s) values('%s')",$fields,$values);
	return $query;
}
function get_ip_from_extension($extn)
{
	global $con;
	$query = "SELECT ipaddr FROM sip_devices WHERE name='$extn'";
        $result = mysqli_query($con,$query);
        if(mysqli_num_rows($result)!=0)
        {
                $row=mysqli_fetch_assoc($result);
                $ip_caller = $row['ipaddr'];
        }
        else
        {
                $ip_caller = "NA";
        }
	return $ip_caller;
}
function get_online_clients()
{
	global $con;
	$time=time();
	$query = "SELECT * FROM sip_devices WHERE regseconds > '$time' ORDER BY RAND() LIMIT 5";
	$result=mysqli_query($con,$query);
	$count = mysqli_num_rows($result);
	$online_list=null;
	if($count>0)
	{
		while(($row=mysqli_fetch_assoc($result))!=null)
		{
			$online_list[]=$row['name'];
		}
	}
	return $online_list;
}

?>
