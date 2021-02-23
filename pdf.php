<?php
include 'extractcss.php';
require_once 'autoload.inc.php';
header('Content-Type:text/html; charset=UTF-8');
use Dompdf\Dompdf;
function getbody($filename)
{
  $file = file_get_contents($filename);
  $dom = new DOMDocument;
  libxml_use_internal_errors(true);
  $dom->loadHTML($file);
  libxml_clear_errors();
  
  $bodies = $dom->getElementsByTagName('body');
  assert($bodies->length === 1);
  $body = $bodies->item(0);
  //for ($i = 0; $i < $body->children->length; $i++) {
   // $body->remove($body->children->item($i));
 // }
  $stringbody = $dom->saveHTML($body);
  $start = stripos($stringbody, '<body');
  $end = stripos($stringbody, '>', $start);
  $newtag = substr_replace($stringbody,  "<style a7a".saveCssLinks($filename)."/>", $end + 1, 0);
  return $newtag;
}

$url = "https://qor360.com";
$bodycontent = getbody($url);
function creatpdf($pdf){
  $dompdf = new Dompdf();
$dompdf->loadHtml($pdf);
$dompdf->setPaper('A4', 'landscape');
$dompdf->render();
$dompdf->stream("sample",array("Attachment"=>0));
}
creatpdf($bodycontent);
?>

<html>

<head></head>

<body>
  <?php
  
 //echo "<textarea rows='40' cols='200' >" . $bodycontent . "</textarea>";
  ?>
</body>

</html>