<?php
//require_once('../PDFTemplates_old/100/100.php');
//$testObject     = new Template1();
//$content        = $testObject->getTemplate();
//echo $content;

$content = "aa";
$htmlFile   = "test.html";
file_put_contents($htmlFile, $content);

exec("wkhtmltopdf -L 0 -B 0 -T 0 -R 0 -d 96 -O Landscape -s Letter $file test.pdf");

?>
<script type="text/javascript">
    //window.open('<?php echo $file ?>');
</script>