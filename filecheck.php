<?
$errortext = "";
$fp        = fopen($OutputPath . "xmleventsoutcall.txt", "a");

if (!$fp) {
    $errortext = "Error: &nbsp;This example requires write permissions. &nbsp;Your Web server cannot write to this directory.";
} else {
    fclose($fp);
    $fp = fopen($OutputPath . "eventsoutcall.txt", "a");
    fclose($fp);
    $fp = fopen($OutputPath . "xmleventsincall.txt", "a");
    fclose($fp);
    $fp = fopen($OutputPath . "eventsincall.txt", "a");
    fclose($fp);
    $fp = fopen($OutputPath . "xmlsummaryincall.txt", "a");
    fclose($fp);
    $fp = fopen($OutputPath . "summaryincall.txt", "a");
    fclose($fp);
    $fp = fopen($OutputPath . "summaryoutcall.txt", "a");
    fclose($fp);
    $fp = fopen($OutputPath . "xmlsummaryoutcall.txt", "a");
    fclose($fp);
    $fp = fopen($OutputPath . "xmlsummaryoutsms.txt", "a");
    fclose($fp);
    $fp = fopen($OutputPath . "summaryoutsms.txt", "a");
    fclose($fp);
    $fp = fopen($OutputPath . "xmlsummaryinsms.txt", "a");
    fclose($fp);
    $fp = fopen($OutputPath . "summaryinsms.txt", "a");
    fclose($fp);
}
?>