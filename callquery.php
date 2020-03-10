<?php

function getHeader(&$stream)
{
    $header = fread($stream, 1);
    if ($header) {
        do
            $header .= fread($stream, 1); while (!preg_match('/\r\n\r\n$/', $header));
    }
    return $header;
}

function getResponse(&$stream)
{
    $response = "";
    while (!feof($stream))
        $response .= fgets($stream, 1024);
    return $response;
}


if ($_POST['usedefault'] == '1') {
    $menuid = '0';
} else {
    $menuid = $_POST['menuid'];
}
$XML = "<campaign action=\"3\" menuid=\"" . $menuid . "\">";
$XML .= "<phonenumbers>";
$XML .= "<phonenumber callid=\"" . $_POST['callid'] . "\" />";
$XML .= "</phonenumbers>";
$XML .= "</campaign>";

if ($_POST['submit'] == 'View') {
    header("Content-type: text/xml");
    echo $XML;
} else {
    // Post header
    $VS_header = "POST /ivrapi.asp HTTP/1.0\r\n";
    $VS_header .= "User-Agent: XML API\r\n";
    $VS_header .= "Host: api.voiceshot.com\r\n";
    $VS_header .= "Content-Type: text/xml\r\n";
    $VS_header .= "Content-length: " . strlen($XML) . "\r\n";
    $VS_header .= "Connection: close\r\n\r\n";
    //$stream = fsockopen("api.voiceshot.com", 80);
	$stream = fsockopen("ssl://api.voiceshot.com", 443); // Use SSL encryption
    if ($stream) {
        fputs($stream, $VS_header);
        fputs($stream, $XML);
        $header = getHeader($stream);
        if ($header) {
            $response = getResponse($stream);
            header("Content-type: text/xml");
            echo $response;
        } else {
            // Do not swap these two URLs. Always post to api.voiceshot.com first.      
            //$stream = fsockopen("apiproxy.voiceshot.com", 80);
			$stream = fsockopen("ssl://apiproxy.voiceshot.com", 443); // Use SSL encryption
            if ($stream) {
                $VS_header = "POST /ivrapi.asp HTTP/1.0\r\n";
                $VS_header .= "User-Agent: IVR API\r\n";
                $VS_header .= "Host: apiproxy.voiceshot.com\r\n";
                $VS_header .= "Content-Type: text/xml\r\n";
                $VS_header .= "Content-length: " . strlen($XML) . "\r\n";
                $VS_header .= "Connection: close\r\n\r\n";
                fputs($stream, $VS_header);
                fputs($stream, $XML);
                $header = getHeader($stream);
                if ($header) {
                    $response = getResponse($stream);
                    header("Content-type: text/xml");
                    echo $response;
                } else {
                    // Post is not successful
					die("ERROR - The post was unsuccessful.");
                }
            }
        }
    }
    fclose($stream);
}

?>