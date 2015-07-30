<?
/* fatlady is used to validate the configuration for the specific service.
 * FATLADY_prefix was defined to the path of Session Data.
 * 3 variables should be returned for the result:
 * FATLADY_result, FATLADY_node & FATLADY_message. */
 include "/htdocs/phplib/trace.php";
function set_result($result, $node, $message)
{
    $_GLOBALS["FATLADY_result"] = $result;
    $_GLOBALS["FATLADY_node"]   = $node;
    $_GLOBALS["FATLADY_message"]= $message;
}
$wfaprefix = $FATLADY_prefix."/webaccess";
$enable = query($wfaprefix."/enable"); 
$httpenable = query($wfaprefix."/httpenable"); 
$httpsenable = query($wfaprefix."/httpsenable"); 
$httpport = query($wfaprefix."/httpport"); 
$httpsport = query($wfaprefix."/httpsport"); 
if($enable!="1") {set($wfaprefix."/enable",0); }
if($httpenable!="1") {set($wfaprefix."/httpenable",0); }
if($httpsenable!="1") {set($wfaprefix."/httpsenable",0); }
TRACE_debug("httpport=".$httpenable."https=".$httpsenable); 

/*Check the web file access path is valid or not. User could not set the invalid path, the invalid path is security issue.*/
$webaccess_path_valid = 1;
foreach($wfaprefix."/account/entry")
{
	foreach("entry")
	{
		$webaccess_path = get("", "path");
		if(strstr($webaccess_path, "..")!="")
		{$webaccess_path_valid = 0;}
	}
}

if ($httpport<1 || $httpport>65535) 
{
	set_result("FAILED", $wfaprefix."/httpport", i18n("Invalid HTTP port number.")); 
}
else if ($httpsport<1 || $httpsport>65535) 
{
	set_result("FAILED", $wfaprefix."/httpsenable", i18n("Invalid HTTPS port number.")); 
}
else if($webaccess_path_valid != 1)
{
	set_result("FAILED", $wfaprefix."/account", i18n("Invalid Path!"));
}
else
{
set($FATLADY_prefix."/valid", "1");
	set_result("OK","",""); 
}
?>
