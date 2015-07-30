<?php
/**
 * Getlink mp3.zing
 * This code help you to getlink mp3 from mp3.zing.vn for easy
 *
 * @author nguyenvanduocit
 */
use Symfony\Component\DomCrawler\Crawler;

require_once __DIR__.'/vendor/autoload.php';
global $client;

//$url = 'http://mp3.zing.vn/album/Se-Chi-La-Mo-Single-Emily/ZWZBFCAC.html';
$url = 'http://mp3.zing.vn/bai-hat/Khuon-Mat-Dang-Thuong-Son-Tung-M-TP/ZW70UUZF.html';
//$url = 'http://mp3.zing.vn/video-clip/Giay-Phut-Cuoi-Trance-Version-Giang-Hong-Ngoc/ZW7IB6ZO.html';
$type = getLinkType($url);
if(!$type){
	die('Link is not valid');
}
$content = getPageContent($url);
if(!$content){
	die('Can not get page content');
}
$xmlLink = getXMLLink($content);
if(!$xmlLink){
	die('Can not get xml Link');
}
$xmlData = getXMLData($xmlLink);
if(!$xmlData){
	die('can not get xml data');
}

switch($type){
	case 'bai-hat':
	case 'album':
		$items = getAudioItems($xmlData);
		break;
		break;
	case 'video-clip':
		$items = getVideoItems($xmlData);
		break;
}
var_dump($items);


function getLinkType($url){
	$regex = "/http:\\/\\/mp3\\.zing\\.vn\\/(bai-hat|album|video-clip|playlist)\\/(?:.*)/";
	preg_match($regex, $url, $matches);
	if(count($matches)===2){
		return $matches[1];
	}
	else{
		return FALSE;
	}
}

function getXMLLink($content){
	$regex = "/xmlURL=(http:\\/\\/mp3\\.zing\\.vn\\/xml\\/[song|video|album]+-xml\\/[a-zA-Z]+)&amp;/";
	preg_match($regex, $content, $matches);
	if(count($matches)===2){
		return $matches[1];
	}
	else{
		return FALSE;
	}
}

function getXMLData($url){
	$content = getPageContent($url);
	if(!$content){
		return null;
	}
	$content = '<?xml version="1.0" encoding="UTF-8"?>'.$content;
	return new Crawler($content);
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

function getAudioItems( Crawler $crawler){
	$items = $crawler->filterXPath('//data/item')->each(function (Crawler $node, $i) {
		return array(
			'title' =>$node->filterXPath('//title')->text(),
			'performer' =>$node->filterXPath('//performer')->text(),
			'link' =>$node->filterXPath('//link')->text(),
			'source' =>$node->filterXPath('//source')->text(),
			'lyric' =>$node->filterXPath('//lyric')->text(),
			'backimage' =>$node->filterXPath('//backimage')->text(),
		);
	});
	return $items;
}

function getVideoItems( Crawler $crawler){
	$items = $crawler->filterXPath('//data/item')->each(function (Crawler $node, $i) {
		$item = array(
			'title' =>$node->filterXPath('//title')->text(),
			'performer' =>$node->filterXPath('//performer')->text(),
			'link' =>$node->filterXPath('//link')->text(),
			'duration' =>$node->filterXPath('//duration')->text(),
			'cover' =>$node->filterXPath('//cover')->text(),
			'mp3link' =>$node->filterXPath('//mp3link')->text(),
			'thumbnail' =>$node->filterXPath('//thumbnail')->text(),
		);
		if($f240 = $node->filterXPath('//f240')){
			$item['f240'] = $f240->text();
		}
		if($f360 = $node->filterXPath('//f360')){
			$item['f360'] = $f360->text();
		}
		if($f480 = $node->filterXPath('//f480')){
			$item['f480'] = $f480->text();
		}
		if($f720 = $node->filterXPath('//f720')){
			$item['f720'] = $f720->text();
		}
		if($f1080 = $node->filterXPath('//f1080')){
			$item['f1080'] = $f1080->text();
		}
		return $item;
	});
	return $items;
}