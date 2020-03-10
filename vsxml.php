<?php

// VoiceShot-specific XML parser

function start_tag($parser, $name, $attribs)
{
    global $current, $menuid, $duration, $callerid, $timeofcall, $wavname, $callid, $action, $keypress, $promptid, $phonenumber, $lasterror;
    $current = $name;
    #echo "Current tag: ".$name."<br />";
    if (is_array($attribs)) {
        #echo "Attributes: <br />";
        while (list($key, $val) = each($attribs)) {
            switch ($key) {
                case "MENUID":
                    $menuid = "$val";
                    break;
                case "DURATION":
                    $duration = "$val";
                    break;
                case "CALLERID":
                    $callerid = "$val";
                    break;
                case "DATEANDTIME":
                    $timeofcall = "$val";
                    break;
                case "NAME":
                    $wavname = "$val";
                    break;
                case "CALLID":
                    $callid = "$val";
                    break;
                case "ACTION":
                    $action = "$val";
                    break;
                case "KEYPRESS":
                    $keypress = "$val";
                    break;
                case "PROMPTID":
                    $promptid = "$val";
                    break;
                case "PHONENUMBER":
                    $phonenumber = "$val";
                    break;
                case "COMMENT":
                    $lasterror = "$val";
                    break;
                case "STATUS":
                    $status = "$val";
                    break;
                case "TXT":
                    $text = "$val";
                    break;
            }
            # echo "Attribute ".$key." has value ".$val."<br />";
        }
    }
}

?>