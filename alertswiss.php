<?php
require 'feed.php';
header("Cache-Control: no-cache");
$url = 'https://alert.swiss/';
$feed = new feed($url,'ALERTSWISS');
$feed->author = 'BABS';
$data = json_decode(file_get_contents('https://www.alert.swiss/content/alertswiss-internet/de/_jcr_content.alertswiss_alerts.json'));
foreach($data->alerts as $alert){
	if($alert->technicalTestAlert != 'true'){
		$item = $feed->newItem('https://www.alert.swiss/'.$alert->identifier,$alert->title);
		$item->content = $alert->description;
		$item->link = $url;
	}
}
$feed->printFeed();
