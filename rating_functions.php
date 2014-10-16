<?php
require_once "database.php";

//test array parameter format points,no of ratings,no of unanswered, no of succesful incoming, no of succesful outgoing
function calculate_and_update_rating($id, $test_params = null)
{
	global $con;
	$rating = 0.0;
	$base_rating = 3.0;
	$query = "SELECT * FROM sip_devices WHERE name = '$id'";
	$result = mysqli_query($con,$query);
	$count = mysqli_num_rows($result);
	if($count!=0)
	{
		$row= mysqli_fetch_assoc($result);
		$total_points = $row['total_points'];
		$no_of_ratings = $row['times_rated'];
		$no_of_unanswered = $row['rejected_calls'];
		$no_of_succesful = $row['succesful_incoming_calls']+$row['succesful_outgoing_calls'];
		if($test_params!=null)
		{
			$total_points = $test_params[0];
			$no_of_ratings = $test_params[1];
			$no_of_unanswered = $test_params[2];
			$no_of_succesful = $test_params[3]+$test_params[4];
		}
		if($no_of_ratings ==0)
		{
			if($no_of_unanswered != 0 && $no_of_succesful == 0)
			{
				$rating = $base_rating - $no_of_unanswered;
			}
			else if($no_of_unanswered !=0 && $no_of_succesful !=0)
			{
				$rating = $base_rating - ($no_of_unanswered / $no_of_succesful);
			}
			else
			{
				$rating = $base_rating;
			}
		}
		else
		{
			$rating = ($total_points/$no_of_ratings);
			$rating = $rating - ($no_of_unanswered/$no_of_succesful);
		}
	}
	else
	{
		$rating = 0.0;
	}
	if($rating < 0.0)
	{
		$rating = 0.0;
	}
	$query="UPDATE sip_devices SET rating = '$rating' WHERE name = '$id'";
	if($test_params==null)
	{
		mysqli_query($con,$query);
	}
	return $rating;
}

/*
test array parameter format points,no of ratings,no of unanswered, no of succesful incoming, no of succesful outgoing
$arr[0] = array (0,0,2,0,0);
$arr[1] = array (0,0,5,0,0);
$arr[2] = array (0,0,2,2,0);
$arr[3] = array (0,0,2,5,0);
$arr[4] = array (0,0,0,3,0);
$arr[5] = array (0,0,0,1,0);
$arr[6] = array (10,2,2,1,0);
$arr[7] = array (12,3,2,4,0);
$arr[8] = array (4,2,2,2,0);
foreach ($arr as $value)
{
	print_r($value);
	echo (calculate_and_update_rating(100012,$value)."<br><br>");
}
*/

?>
