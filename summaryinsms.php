<?php

include 'vsxml.php'; // VoiceShot-specific XML parser

global $current, $menuid, $callerid, $callid, $text;

$data = $GLOBALS['HTTP_RAW_POST_DATA'];
$CRLF = "\r\n";

$fp = fopen("xmlsummaryinsms.txt", "a");
if (!$fp) {
    die("ERROR - fopen() - Unable to open file.");
}
fputs($fp, "--- " . date("m/d/y h:i:s A") . " ------------------------------------------------------" . $CRLF);
fputs($fp, str_replace("<?xml version=\"1.0\"?>", "", $data) . $CRLF . $CRLF);
fclose($fp);

function tag_contents($parser, $data)
{
    global $current, $wavdata;
    #echo "Contents : ".$data."<br />";
    if ($current == "FILE") {
        $wavdata .= $data;
    }
}

function end_tag($parser, $name)
{
    # echo "Reached ending tag ".$name."<br /><br />";
}

if (!($xmlparser = xml_parser_create())) {
    die("ERROR - xml_parser_create() - Unable to create parser.");
}

xml_set_element_handler($xmlparser, "start_tag", "end_tag");
xml_set_character_data_handler($xmlparser, "tag_contents");

if (!xml_parse($xmlparser, $data)) {
    $reason = xml_error_string(xml_get_error_code($xmlparser));
    $reason .= xml_get_current_line_number($xmlparser);
    die($reason);
}
xml_parser_free($xmlparser);

$fp = fopen("summaryinsms.txt", "a");
if (!$fp) {
    die("ERROR - fopen() - Unable to open file.");
}
fputs($fp, "--- " . date("m/d/y h:i:s A") . " ------------------------------------------------------" . $CRLF);
fputs($fp, "MenuId: " . $menuid . $CRLF);
if($callid != null) {
	fputs($fp, "CallId: " . $callid . $CRLF);
}
fputs($fp, "CallerId: " . $callerid . $CRLF);
fputs($fp, "Text: " . $text . $CRLF);

fputs($fp, $CRLF);
fclose($fp);
?>