<?php
require 'feed.php';
header("Cache-Control: no-cache");
$url = 'https://alert.swiss/';
$feed = new feed($url,'ALERTSWISS');
$feed->author = 'BABS';
$data = json_decode(file_get_contents('https://www.alert.swiss/content/alertswiss-internet/de/_jcr_content.alertswiss_alerts.json'));
$alertCount = 0;
$alertCount = $alertCount + $data->alertCount->severe;
$alertCount = $alertCount + $data->alertCount->moderate;
$alertCount = $alertCount + $data->alertCount->minor;
$alertCount = $alertCount + $data->alertCount->allClear;
if($alertCount > 0){
	$item = $feed->newItem('https://alert.swiss','New Alert');
	$item->content = 'A new Alert appeared please check it out!';
	$item->link = 'https://alert.swiss';
}
$feed->printFeed();