<? include "/htdocs/webinc/body/draw_elements.php"; ?>
<form id="mainform" onsubmit="return false;">
<div class="orangebox">
	<h1><?echo i18n("Dynamic DNS");?></h1>
	<p>
		<?echo i18n("The Dynamic DNS feature allows you to host a server (Web, FTP, Game Server, etc...) using a domain name that you have purchased (www.whateveryournameis.com) with your dynamically assigned IP address. Most broadband Internet Service Providers assign dynamic (changing) IP addresses. Using a DDNS service provider, your friends can enter your host name to connect to your game server no matter what your IP address is.");?>
	</p>
	<p id="dsc_oray" style="display:none">
		Oray.cn <?echo i18n("Peanut Dynamic Domain Resolve Service");?>:<br>
		&nbsp; <a href="http://www.oray.cn/Passport/Passport_Register.asp" target=_blank>* <?echo i18n("Peanut Dynamic Domain Resolve Service");?></a><br>
		&nbsp; <a href="http://www.oray.cn/peanuthull/peanuthull_prouser.htm" target=_blank>* <?echo i18n("Update to Professional Service of Dynamic Domain Resolve of Peanut");?></a><br>
		&nbsp; <a href="http://ask.oray.cn/help/" target=_blank>* <?echo i18n("Help About Dynamic Domain Resolve Service of Peanut");?></a>
	</p>
	<p><input type="button" value="<?echo i18n("Save Settings");?>" onclick="BODY.OnSubmit();" />
		<input type="button" value="<?echo i18n("Don't Save Settings");?>" onclick="BODY.OnReload();" /></p>
</div>
<div class="blackbox">
	<h2><?echo i18n("Dynamic DNS Settings");?></h2>
	<div class="centerline" align="center">
		<div class="textinput">
			<span class="name"><?echo i18n("Enable Dynamic DNS");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input type="checkbox" id="en_ddns" onclick="PAGE.EnableDDNS();"></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("Server Address");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input type="text" id="v4addr" maxlength="60" size="20">
			<input type="button" value="<<" class="arrow" onClick="OnClickAddr();" />		
				<select id="server">
					<option value="title">Select Dynamic DNS Server</option>
					<!-- <? if ($FEATURE_CHINA=="1") echo '<option value="DLINK.COM.CN">dlinkddns.com.cn</option>\n';?> -->				
					<option value="DLINK">dlinkddns.com</option>
					<option value="DYNDNS">dyndns.com</option>
					<? if ($FEATURE_CHINA == "1" && $FEATURE_CHINA_PEANUT == "1") echo '<option value="ORAY">Oray.cn(Peanut)</option>\n';?>
				</select>
			</span>
		</div>
		<div class="textinput" id="host_div">
			<span class="name"><?echo i18n("Host Name");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input type="text" id="host" maxlength="60" size="40"></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("Username or Key");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input type="text" id="user" maxlength="16" size="40"></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("Password or Key");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input type="password" id="passwd" maxlength="16" size="40"></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("Verify Password or Key");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input type="password" id="passwd_verify" maxlength="16" size="40"></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("Timeout");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input type="text" id="timeout" maxlength="4" size="10"><?echo i18n("(hours)");?></span>
		</div>
		<div id="report_div" class="textinput">
			<span class="name"><?echo i18n("Status");?></span>
			<span class="delimiter" >:</span>
			<span class="value" id="report" ></span>
		</div>
	</div>
	<div id="peanut_status_div" class="textinput" style="display:none; height:auto;">
		<span class="name"><?echo i18n("Linkage Status");?></span>
		<span class="delimiter">:</span>
		<span class="value" id="peanut_status"></span>
	</div>
	<div style="clear:both;"></div>
	<div id="peanut_detail_div" style="display:none;">
		<div class="textinput">
			<span class="name"><?echo i18n("Service Level");?></span>
			<span class="delimiter">:</span>
			<span class="value" id="peanut_level"></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("Net Domain");?></span>
			<span class="delimiter">:</span>
			<span class="value"></span>
		</div>
	
		<?
		// Here, we don't check the data in the file, 
		// and trust the data is well structured as domain names immediately followed with it status.
		if ( isfile("/var/run/all_ddns_domain_name")==1 )
		{
			$dnstext = fread("r", "/var/run/all_ddns_domain_name");
			//echo $dnstext.'<br>';
			$cnt = scut_count($dnstext, "");
			$no = 0;
			$i  = 0;
			while ($i < $cnt)
			{
				$domain_name = scut($dnstext, $i, "");
				$i++;
				$status = scut($dnstext, $i, "");
				$i++;
				$color ="red";
				$symbol =":&nbsp;&nbsp;";
				if($status=="ON") { $color="green"; $symbol =":&nbsp;&nbsp;&nbsp;";}
				echo '<div class="textinput">\n';
				echo '\t<span class="name"></span>\n';
				echo '\t<span class="delimiter"></span>\n';
				echo '\t<span class="value"><span style="color: '.$color.';">'.$status.'</span>'.$symbol.$domain_name.'</span>\n';
				echo '</div>\n';
			}
		}
		?>
	</div>
	<div class="gap"></div>
</div>
<div class="blackbox">
	<h2><?echo i18n("Dynamic DNS For IPv6 HOSTS");?></h2>
			<div class="textinput">
			<span class="name"><?echo i18n("Enable");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input type="checkbox" id="en_ddns_v6" onclick="PAGE.EnableDDNS();"></span>
		</div>
		<div class="textinput">
			<span class="name"><?echo i18n("IPv6 Address");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input type="text" id="v6addr" maxlength="60" size="25">
			<input type="button" value="<<" class="arrow" onClick="OnClickPCArrow('<?=$INDEX?>');" />
			<? DRAW_select_v6dhcpclist("LAN-4","pc_".$INDEX, i18n("Computer Name"), "",  "", "1", "broad"); ?>
			</span>
		
		</div>

		<div class="textinput">
			<span class="name"><?echo i18n("Host Name");?></span>
			<span class="delimiter">:</span>
			<span class="value"><input type="text" id="v6host" maxlength="60" size="25"><?echo i18n("(e.g.: ipv6.mydomain.net)");?></span>
		</div>		
		<p><input type="button" id="add_ddns_v6" value="<?echo i18n("Save");?>" onclick="PAGE.AddDDNS();" />
		<input type="button" id="clear_ddns_v6" value="<?echo i18n("Clear");?>" onclick="ClearDDNS();" /></p>
		<div class="gap"></div>
</div>	
<div class="blackbox">
	<h2><?echo i18n("IPv6 DYNAMIC DNS LIST ");?></h2>
	<table id="v6ddns_list" class="general">
		<tr>
			<th width=10%><?echo i18n("Enable");?></th>
			<th width=44%><?echo i18n("Host Name");?></th>
			<th width=30%><?echo i18n("IPv6 Address");?></th>
			<th width=8%><?echo i18n("");?></th>
			<th width=8%><?echo i18n("");?></th>
		</tr>
	</table>
	<div class="gap"></div>
</div>	
<p><input type="button" value="<?echo i18n("Save Settings");?>" onclick="BODY.OnSubmit();" />
	<input type="button" value="<?echo i18n("Don't Save Settings");?>" onclick="BODY.OnReload();" /></p>
</form>
