<?php
	header('Access-Control-Allow-Origin: *');
	header('Content-Type: application/json; charset=utf-8');
	
	$arr = array();
	if (isset($_GET['id'])) {
		$channel = $_GET['id'];
		$content = file_get_contents("https://t.me/" . $channel);
		if (strpos($content, "tgme_page_post") === FALSE) {
			$arr['is_channel'] = false;
		} else {
			$arr = array();
			$arr['is_channel'] = true;
			$title = str_replace("\n  ", "", getFromClass2($content, "tgme_page_title"));
			$title = str_replace("\n", "", $title);
			$arr['title'] = $title;
			$members = str_replace(" members", "", getFromClass($content, "tgme_page_extra"));
			$arr['members'] = str_replace(' ', '', $members);
			$arr['description'] = getFromClass2($content, "tgme_page_description");
			$arr['photo'] = getPhoto($content);
		}
	} else {
		$arr['is_channel'] = false;
	}
	echo json_encode($arr, JSON_UNESCAPED_UNICODE);
	
function getFromClass($html, $class) {
	$dom = new DomDocument();
	$dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);
	$xpath = new DOMXpath($dom);
	$nodes = $xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $class ')]");
	$result = $nodes->item(0)->nodeValue;
	return $result;
}
function getFromClass2($html, $class) {
	$dom = new DomDocument();
	$dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);
	$xpath = new DOMXpath($dom);
	$nodes = $xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $class ')]");
	$result = DOMinnerHTML($nodes->item(0));
	return $result;
}
function getPhoto($html) {
	$dom = new DomDocument();
	$dom->loadHTML('<?xml encoding="utf-8" ?>' . $html);
	$xpath = new DOMXpath($dom);
	$class = 'tgme_page_photo_image';
	$nodes = $xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $class ')]");
	$result = $nodes->item(0)->getAttribute('src');
	return $result;
}
function DOMinnerHTML(DOMNode $element) { 
    $innerHTML = ""; 
    $children  = $element->childNodes;

    foreach ($children as $child) {
		if (($child->nodeName == 'br') or ($child->nodeName == '#text')) {
			$innerHTML .= $element->ownerDocument->saveHTML($child);
		}
    }

    return $innerHTML; 
} 
?>