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

$callid   = "";
$callerid = "";
$txt      = "";

if ($_POST['usedefault'] == '1') {
    $menuid = '1';
} else {
    $menuid = $_POST['menuid'];
}


$errortext = '';
if ($_POST['phonenumber'] == '') {
    $errortext = "Required Field - PhoneNumber - is blank.";
} else {
    if ($menuid == '') {
        $errortext = "Required Field - MenuID - is blank.";
    }
}
if ($_POST['callid'] != '') {
    $callid = "callid=\"" . $_POST['callid'] . "\"";
}
if ($_POST['callerid'] != '') {
    $callerid = "callerid=\"" . $_POST['callerid'] . "\"";
}
if ($_POST['txt'] != '') {
    $txt = "txt=\"" . $_POST['txt'] . "\"";
}
if ($txt != '') {
    $promptinfo .= "<prompt " . $txt . " />";
}

$XML = "<campaign action=\"0\" menuid=\"" . $menuid . "\" " . $callerid . " >";
if ($promptinfo != "") {
    $XML .= "<prompts>" . $promptinfo . "</prompts>";
}
$XML .= "<phonenumbers>";
$XML .= "<phonenumber number=\"" . $_POST['phonenumber'] . "\" " . $callid . " />";
$XML .= "</phonenumbers>";
$XML .= "</campaign>";
if ($errortext != '') {
    echo "<html>" . $errortext . "</html>";
} else {
    if ($_POST['submit'] == 'View') {
        header("Content-type: text/xml");
        echo $XML;
    } else {
        $response  = "";
        $header    = "";
        // Post header
        $VS_header = "POST /ivrapi.asp HTTP/1.0\r\n";
        $VS_header .= "User-Agent: IVR API\r\n";
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
}

?>