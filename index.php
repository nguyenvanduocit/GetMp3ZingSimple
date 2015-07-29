<?php
/**
 * Getlink mp3.zing
 * This code help you to getlink mp3 from mp3.zing.vn for easy
 *
 * @author nguyenvanduocit
 */
require_once __DIR__.'/vendor/autoload.php';
global $client;

$url = 'http://mp3.zing.vn/bai-hat/Khuon-Mat-Dang-Thuong-Son-Tung-M-TP/ZW70UUZF.html';
$type = getType($url);
$content = getPageContent($url);

function getType($url){
	$regex = "/http:\\/\\/mp3\\.zing\\.vn\\/(bai-hat|album|video-clip|playlist)\\/(?:.*)/";
	preg_match($regex, $url, $matches);
	if(count($matches)===2){
		return $matches[1];
	}
	else{
		return FALSE;
	}
}

function getPageContent($url)
{
	global $client;
	if(!$client) {
		$client = new GuzzleHttp\Client();
	}
	$res = $client->get($url);
	if($res->getStatusCode() === 200){
		return $res->getBody()->getContents();
	}
	else{
		return null;
	}
}