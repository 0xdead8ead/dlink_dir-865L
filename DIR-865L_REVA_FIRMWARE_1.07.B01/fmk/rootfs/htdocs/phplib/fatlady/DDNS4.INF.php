<? /* vi: set sw=4 ts=4: */
/* fatlady is used to validate the configuration for the specific service.
 * FATLADY_prefix was defined to the path of Session Data.
 * 3 variables should be returned for the result:
 * FATLADY_result, FATLADY_node & FATLADY_message. */
include "/htdocs/phplib/trace.php";
include "/htdocs/phplib/xnode.php";

function set_result($result, $node, $message)
{
	$_GLOBALS["FATLADY_result"] = $result;
	$_GLOBALS["FATLADY_node"]   = $node;
	$_GLOBALS["FATLADY_message"]= $message;
	return $result;
}

function verify_ddns4($path)
{
	$cnt = query($path."/count");
	$num = query($path."/entry#");
	while ($num > $cnt)
	{
		del($path."/entry:".$num);
		$num = query($path."/entry#");
	}

	foreach ($path."/entry")
	{
		if (query("uid")=="")
		{
			$seqno = query($path."/seqno");
			set("uid", "DDNS4-".$seqno);
			$seqno++;
			set($path."/seqno", $seqno);
		}

		if (query("provider")!="ORAY")
		{
			if (query("hostname")=="")
				return set_result(
						"FAILED",
						$path."/entry:".$InDeX."/hostanme",
						i18n("Please input the host name.")
						);
			if (isdomain(query("hostname"))=="0")
				return set_result(
						"FAILED",
						$path."/entry:".$InDeX."/hostanme",
						i18n("Invalid host name.")
						);
		}
		if (query("username")=="")
			return set_result(
						"FAILED",
						$path."/entry:".$InDeX."/username",
						i18n("Please input the user account or e-mail.")
						);
		if (query("password")=="")
			return set_result(
						"FAILED",
						$path."/entry:".$InDeX."/password",
						i18n("Please input the password.")
						);
		$per_err = 0;
		
		$v = query("interval");
		if ($v!="")
		{
			if (isdigit($v)=="0")	$per_err++;
			//for maximum timeout, we follow dir655, 8670 hours = 520200 minutes
			if($v<=0 || $v > 520200) 	$per_err++;
		}
		if ($per_err > 0)
			return set_result(
						"FAILED",
						$path."/entry:".$InDeX."/interval",
						i18n("Invalid period. The range of Timeout is 1~8670.")
						);
	}
	return "OK";
}

function verify_inf_ddns4($path)
{
	foreach ($path."/inf")
	{
		$ddns4 = query("ddns4");
		TRACE_debug("FATLADY: ".query("uid")." has ddns4 = [".$ddns4."]");
		if ($ddns4!="" && XNODE_getpathbytarget($path."/ddns4", "entry", "uid", $ddns4, 0)=="")
		{
			TRACE_debug("FATLADY: ".$ddns4." is invalid.");
			return set_result(
						"FAILED",
						$path."/inf:".$InDeX."/ddns4",
						i18n("Invalid dynamic DNS setting.")
						);
		}
	}
	return "OK";
}


function verify_ddns4_url($path)
{
	TRACE_debug("FATLADY: Checking DDNS URL.");
	foreach ($path."/entry")
	{
		if (query("provider") == "")
		{
			return set_result("FAILED", $path."/entry:".$InDeX."/provider", i18n("Please input the server address."));
		}
		else
		{
			$url = query("provider");
			if($url != "DLINK" && $url != "DYNDNS.C" && $url != "DYNDNS" && $url != "DYNDNS.S")
			{
				/* remove the leading 'http://' */
				$tmpurl = cut($url, 0, "/");
				if($tmpurl == "http:") $url = scut($url, 0, "http://");

				if(cut_count($url,"/") == 1)  //no find '/' char
				{
					if(isdomain($url) != 1)   //filename
					{
						if (isalnum(charcodeat($url,0)) != 1 || isalnum(charcodeat($url,strlen($url)-1)) != 1)
						{
							return set_result("FAILED", $path."/entry:".$InDeX."/provider", i18n("Invalid URL."));
						}
						$i = 1; 
						while($i < strlen($url)-1)
						{
							if (isalnum(charcodeat($url,$i)) != 1 && 
								charcodeat($url,$i) != "_" && 
								charcodeat($url,$i) != "." && 
								charcodeat($url,$i) != "-" && 
								charcodeat($url,$i) != "~" && 
								charcodeat($url,$i) != "&" && 
								charcodeat($url,$i) != "?" && 
								charcodeat($url,$i) != "%" && 
								charcodeat($url,$i) != ":" && 
								charcodeat($url,$i) != "=")
							{
								return set_result("FAILED", $path."/entry:".$InDeX."/provider", i18n("Invalid URL."));
							}
							$i++;
						}
					}
				}
				else //if(cut_count($url,"/")>1)    find  more than one '/' char
				{
					$tmpurl1 = cut($url, 0, "/");   //first is hostname,other is path and filename
					if(isdomain($tmpurl1) != 1)
					{
						return set_result("FAILED", $path."/entry:".$InDeX."/provider", i18n("Invalid URL."));
					}
		
					//path and filename
					$i = 0; 
					while ($i < strlen($url))
					{
						if (isalnum(charcodeat($url,$i)) != 1 && 
							charcodeat($url,$i) != "=" && 
							charcodeat($url,$i) != "_" && 
							charcodeat($url,$i) != "-"  && 
							charcodeat($url,$i) != "/"  && 
							charcodeat($url,$i) != "~"  && 
							charcodeat($url,$i) != "&"  && 
							charcodeat($url,$i) != "?"  && 
							charcodeat($url,$i) != "%"  && 
							charcodeat($url,$i) != ":"  && 
							charcodeat($url,$i) != ".")
						{
							return set_result("FAILED", $path."/entry:".$InDeX."/provider", i18n("Invalid URL."));
						}
						$i++;
					}
				}	
			}
		}
	}
	return "OK";
}


if (verify_ddns4($FATLADY_prefix."/ddns4")=="OK" &&
	verify_inf_ddns4($FATLADY_prefix)=="OK" && verify_ddns4_url($FATLADY_prefix."/ddns4")=="OK") 
{
	set($FATLADY_prefix."/valid", "1");
	set_result("OK", "", "");
}
?>
