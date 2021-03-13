<?php
include './extractCss.php';
require_once 'autoload.inc.php';
//header('Content-Type:text/html; charset=UTF-8');
use Dompdf\Dompdf;
use Dompdf\Options;

$fg;
function getbody($filename)
{
  $file = file_get_contents($filename);
  $dom = new DOMDocument;
  libxml_use_internal_errors(true);
  $dom->loadHTML($file);
  libxml_clear_errors();
	$tx = saveCssLinks($filename);
	$new_elm = $dom->createElement('style', $tx);
	$elm_type_attr = $dom->createAttribute('type');
	$elm_type_attr->value = 'text/css';
	$new_elm->appendChild($elm_type_attr);
	$dom->getElementsByTagName('head')[0]->appendChild($new_elm);
  $bodies = $dom->getElementsByTagName('html');
  assert($bodies->length === 1);
  $body = $bodies->item(0);
  $stringbody = $dom->saveHTML($body);
  $start = stripos($stringbody, '<html');
  $end = stripos($stringbody, '>', $start);
  $newtag = $stringbody;
  $fn = uniqid(rand(), true) . '.css';
  $fg = $fn;
  $myfile = fopen($fn, "w") or die("Unable to open file!");
	$txt = saveCssLinks($filename);
	fwrite($myfile, $txt);
	fclose($myfile);
  return $newtag;
}


$url = "https://indemand.group/about/";
$bodycontent = getbody($url);
function creatpdf($pdf){
  $dompdf = new Dompdf();
  $options = new Options();
$options->set('isRemoteEnabled', true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($pdf);
$dompdf->setPaper('A4', 'landscape');
//$dompdf->set_base_path('localhost/Dompdfgetfilecontent/'.$fg);
$dompdf->render();
$dompdf->stream("sample",array("Attachment"=>0));
}
//print_r($bodycontent);
creatpdf($bodycontent);
?>

