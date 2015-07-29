<?php
/**
 * Getlink mp3.zing
 * This code help you to getlink mp3 from mp3.zing.vn for easy
 *
 * @author nguyenvanduocit
 */
require_once __DIR__.'/vendor/autoload.php';

$url = 'http://mp3.zing.vn/bai-hat/Khuon-Mat-Dang-Thuong-Son-Tung-M-TP/ZW70UUZF.html';
$type = getType($url);

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