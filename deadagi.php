#!/usr/bin/env php
<?php


pcntl_signal(SIGHUP, SIG_IGN);
require_once "phpagi.php";
require_once "database.php";

$agi = new AGI();
$exten = $agi->get_variable('EXTEN');
$caller = $agi->request['agi_callerid'];
$dial_status = $agi->get_variable('DIALSTATUS');
file_put_contents("/var/www/html/phone_app/deadlog.txt",$exten['data']."\n".$caller."\n".$dial_status['data']."\n\n",FILE_APPEND);


function signalHandler($signal) {
 
  exit;
}
?>
