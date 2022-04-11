<?php
error_reporting(0);

$url = $_REQUEST['url'];
$html = file_get_contents($url);
$dom = new domDocument;
$dom->strictErrorChecking = false;
$dom->recover = true;
$dom->loadHTML($html);


//Add base tag
$head = $dom->getElementsByTagName('head')->item(0);
$base = $dom->createElement('base');
$base->setAttribute('href',$url);

if ($head->hasChildNodes()) {
    $head->insertBefore($base,$head->firstChild);
} else {
    $head->appendChild($base);
}

//Print result
echo $dom->saveHTML();
