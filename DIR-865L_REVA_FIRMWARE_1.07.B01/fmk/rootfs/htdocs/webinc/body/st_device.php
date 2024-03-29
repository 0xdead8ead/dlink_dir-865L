<form id="mainform" onsubmit="return false;">
<div class="orangebox">
	<h1><?echo i18n("Device Information");?></h1>
	<p>
		<?echo i18n("All of your Internet and network connection details are displayed on this page. The firmware version is also displayed here.");?>
	</p>
</div>
<div class="blackbox">
	<h2><?echo i18n("General");?></h2>
	<div class="textinput">
		<span class="name"><?echo i18n("Time");?></span>
		<span class="delimiter">:</span>
		<span class="value" id="st_time"></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Firmware Version");?></span>
		<span class="delimiter">:</span>
		<span class="value"><?echo query("/runtime/device/firmwareversion").' '.query("/runtime/device/firmwarebuilddate");?></span>
	</div>
<? if(query("/device/layout") =="bridge") {echo '<!--';}?>
	<div class="textinput" <? if(isfile("/htdocs/web/bsc_mydlink.php")==0) echo "style='display:none;'";?>>
		<span class="name"><?echo I18N("h", "mydlink Service");?></span>
		<span class="delimiter">:</span>
		<span class="value"><? if(query("/mydlink/register_st")=="1"){ echo I18N("h", "Registered");} else { echo I18N("h", "Non-Registered");} ?></span>
	</div>
	<div class="textinput" <? if(isfile("/htdocs/web/bsc_mydlink.php")==0) echo "style='display:none;'";?>>
		<span class="name"><?echo I18N("h", "mydlink E-mail");?></span>
		<span class="delimiter">:</span>
		<span class="value"><? if(query("/mydlink/regemail")!="") echo query("/mydlink/regemail"); ?></span>
	</div>	
<? if(query("/device/layout") =="bridge") {echo '-->';}?>
	<div class="gap"></div>
</div>
<div class="blackbox" id="wan_ethernet_block" style="display:none">
    <h2><?echo i18n("WAN");?></h2>
    <div class="textinput">
        <span class="name"><?echo i18n("Connection Type");?></span>
        <span class="delimiter">:</span>
        <span class="value" id="st_wantype"></span>
    </div>
    <div class="textinput">
        <span class="name"><?echo i18n("Cable Status");?></span>
        <span class="delimiter">:</span>
        <span class="value" id="st_wancable"></span>
    </div>
    <div class="textinput" id="wan_failover_block" style="display:none;">
        <span class="name"><?echo i18n("WAN Failover Status");?></span>
        <span class="delimiter">:</span>
        <span class="value" id="st_wan_failover"></span>
    </div>    
    <div class="textinput">
        <span class="name"><?echo i18n("Network Status");?></span>
        <span class="delimiter">:</span>
        <span class="value" id="st_networkstatus"></span>
    </div>
    <div class="textinput" id="st_wan_dhcp_action" style="display:none;">
        <span class="name"></span>
        <span class="delimiter"></span>
        <span class="value">
            <input type="button" id="st_wan_dhcp_renew" value="<?echo i18n("Renew");?>" onClick="PAGE.DHCP_Renew();"/>&nbsp;&nbsp;
            <input type="button" id="st_wan_dhcp_release" value="<?echo i18n("Release");?>" onClick="PAGE.DHCP_Release();"/>  
        </span>
    </div> 
    <div class="textinput" id="st_wan_ppp_action" style="display:none;">
        <span class="name"></span>
        <span class="delimiter"></span>
        <span class="value">
            <input type="button" id="st_wan_ppp_connect" value="<?echo i18n("Connect");?>" onClick="PAGE.PPP_Connect();"/>&nbsp;&nbsp;
            <input type="button" id="st_wan_ppp_disconnect" value="<?echo i18n("Disconnect");?>" onClick="PAGE.PPP_Disconnect();"/>  
        </span>
    </div>    
	<div class="textinput">
        <span class="name"><?echo i18n("Connection Up Time");?></span>
        <span class="delimiter">:</span>
        <span class="value" id="st_connection_uptime"></span>
    </div>
    <div class="textinput">
        <span class="name"><?echo i18n("MAC Address");?></span>
	    <span class="delimiter">:</span>
	    <span class="value" id="st_wan_mac"></span>
    </div>
    <div class="textinput">
        <span class="name" id= "name_wanipaddr"></span>
        <span class="delimiter">:</span>
        <span class="value" id="st_wanipaddr"></span>
    </div>
    <div class="textinput">
        <span class="name"><?echo i18n("Subnet Mask");?></span>
        <span class="delimiter">:</span>
        <span class="value" id="st_wannetmask"></span>
    </div>
    <div class="textinput">
        <span class="name" id= "name_wangateway"></span>
        <span class="delimiter">:</span>
        <span class="value" id="st_wangateway"></span>
    </div>
    <div class="textinput">
        <span class="name"><?echo i18n("Primary DNS Server");?></span>
        <span class="delimiter">:</span>
        <span class="value" id="st_wanDNSserver"></span>
    </div>
    <div class="textinput" >
        <span class="name"><?echo i18n("Secondary DNS Server");?></span>
        <span class="delimiter">:</span>
        <span class="value" id="st_wanDNSserver2"></span>
    </div>    
    <div class="gap"></div>
</div>
<div class="blackbox" id="wan_ethernet_dslite_block" style="display:none">
    <h2><?echo i18n("WAN");?></h2>
    <div class="textinput">
        <span class="name"><?echo i18n("Connection Type");?></span>
        <span class="delimiter">:</span>
        <span class="value" id="st_dslite_wantype"></span>
    </div>
    <div class="textinput">
        <span class="name"><?echo i18n("Cable Status");?></span>
        <span class="delimiter">:</span>
        <span class="value" id="st_dslite_wancable"></span>
    </div>
    <div class="textinput">
        <span class="name"><?echo i18n("Network Status");?></span>
        <span class="delimiter">:</span>
        <span class="value" id="st_dslite_networkstatus"></span>
    </div>
    <div class="textinput">
        <span class="name"><?echo i18n("Connection Up Time");?></span>
        <span class="delimiter">:</span>
        <span class="value" id="st_dslite_connection_uptime"></span>
    </div>
    <div class="textinput">
        <span class="name"><?echo i18n("MAC Address");?></span>
	    <span class="delimiter">:</span>
	    <span class="value" id="st_dslite_wan_mac"></span>
    </div>
    <div class="textinput">
        <span class="name"><?echo i18n("AFTR Address");?></span>
        <span class="delimiter">:</span>
        <span class="value" id="st_aftrserver"></span>
    </div>
    <div class="textinput" >
        <span class="name"><?echo i18n("DS-Lite DHCPv6 option");?></span>
        <span class="delimiter">:</span>
        <span class="value" id="st_dslite_dhcp6opt"></span>
    </div>    
    <div class="gap"></div>
</div>
<?
	if (isfile("/htdocs/webinc/body/st_device_3G.php")==1)
		dophp("load", "/htdocs/webinc/body/st_device_3G.php");
?>
<div class="blackbox" id="lan_ethernet_block" style="display:none">
	<h2><?echo i18n("LAN");?></h2>
	<div class="textinput">
		<span class="name"><?echo i18n("MAC Address");?></span>
		<span class="delimiter">:</span>
		<span class="value"><?echo query("/runtime/devdata/lanmac");?></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("IP Address");?></span>
		<span class="delimiter">:</span>
		<span class="value" id="st_ip_address"></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Subnet Mask");?></span>
		<span class="delimiter">:</span>
		<span class="value" id="st_netmask"></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("DHCP Server");?></span>
		<span class="delimiter">:</span>
		<span class="value" id="st_dhcpserver_enable"></span>
	</div>
	<div class="gap"></div>
</div>
<div class="blackbox" id="ethernet_block" style="display:none">
    <h2><?echo i18n("ETHERNET");?></h2>
    <div class="textinput">
        <span class="name"><?echo i18n("Connection Type");?></span>
        <span class="delimiter">:</span>
        <span class="value" id="br_wantype"></span>
    </div>
    <div class="textinput">
        <span class="name"><?echo i18n("MAC Address");?></span>
	    <span class="delimiter">:</span>
	    <span class="value"><?echo query("/runtime/devdata/lanmac");?></span>
    </div>
    <div class="textinput">
        <span class="name"><?echo i18n("IP Address");?></span>
        <span class="delimiter">:</span>
        <span class="value" id="br_ipaddr"></span>
    </div>
    <div class="textinput">
        <span class="name"><?echo i18n("Subnet Mask");?></span>
        <span class="delimiter">:</span>
        <span class="value" id="br_netmask"></span>
    </div>
    <div class="textinput">
        <span class="name"><?echo i18n("Default Gateway");?></span>
        <span class="delimiter">:</span>
        <span class="value" id="br_gateway"></span>
    </div>
    <div class="textinput">
        <span class="name"><?echo i18n("Primary DNS Server");?></span>
        <span class="delimiter">:</span>
        <span class="value" id="br_dns1"></span>
    </div>
    <div class="textinput" >
        <span class="name"><?echo i18n("Secondary DNS Server");?></span>
        <span class="delimiter">:</span>
        <span class="value" id="br_dns2"></span>
    </div>    
	<div class="gap"></div>
</div>
<div class="blackbox" id="wlan">
	<h2><?echo i18n("WIRELESS LAN");?></h2>
	<div class="textinput">
		<span class="name"><?echo i18n("Wireless Radio");?></span>
		<span class="delimiter">:</span>
		<span class="value" id="st_wireless_radio"></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("MAC Address");?></span>
		<span class="delimiter">:</span>
		<span class="value"><?echo query("/runtime/devdata/wlanmac");?></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("802.11 Mode");?></span>
		<span class="delimiter">:</span>
		<span class="value" id="st_80211mode"></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Channel Width");?></span>
		<span class="delimiter">:</span>
		<span class="value" id="st_Channel_Width"></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Channel");?></span>
		<span class="delimiter">:</span>
		<span class="value" id="st_Channel"></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Network Name (SSID)");?></span>
		<span class="delimiter">:</span>
		<pre style="font-family:Tahoma"><span class="value" id="st_SSID"></span></pre>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Wi-Fi Protected Setup");?></span>
		<span class="delimiter">:</span>
		<span class="value" id="st_WPS_status"></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Security");?></span>
		<span class="delimiter">:</span>
		<span class="value" id="st_security"></span>
	</div>
	<div class="gap"></div>
	<div class="gap" <?if ($FEATURE_NOGUESTZONE == "1") echo ' style="display:none;"';?>></div>	
	<div class="textinput" <?if ($FEATURE_NOGUESTZONE == "1") echo ' style="display:none;"';?>>
		<span class="name"><?echo i18n("Guest Zone Wireless Radio");?></span>
		<span class="delimiter">:</span>
		<span class="value" id="gz_st_wireless_radio"></span>
	</div>
	<div class="textinput" <?if ($FEATURE_NOGUESTZONE == "1") echo ' style="display:none;"';?>>
		<span class="name"><?echo i18n("Guest Zone Network Name (SSID)");?></span>
		<span class="delimiter">:</span>
		<pre style="font-family:Tahoma"><span class="value" id="gz_st_SSID"></span></pre>
	</div>
	<!--
	<div class="textinput">
		<span class="name"><?echo i18n("Guest Zone Channel");?></span>
		<span class="delimiter">:</span>
		<span class="value" id="gz_st_Channel"></span>
	</div>
	-->
	<div class="textinput" <?if ($FEATURE_NOGUESTZONE == "1") echo ' style="display:none;"';?>>
		<span class="name"><?echo i18n("Guest Zone Security");?></span>
		<span class="delimiter">:</span>
		<span class="value" id="gz_st_security"></span>
	</div>
	<div class="gap"></div>
</div>	

<div class="blackbox" id="wlan2" style="display:none">
	<h2><?echo i18n("WIRELESS LAN2");?></h2>
	<div class="textinput">
		<span class="name"><?echo i18n("Wireless Radio");?></span>
		<span class="delimiter">:</span>
		<span class="value" id="st_wireless_radio_Aband"></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("MAC Address");?></span>
		<span class="delimiter">:</span>
		<span class="value"><?echo query("/runtime/devdata/wlanmac2");?></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("802.11 Mode");?></span>
		<span class="delimiter">:</span>
		<span class="value" id="st_80211mode_Aband"></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Channel Width");?></span>
		<span class="delimiter">:</span>
		<span class="value" id="st_Channel_Width_Aband"></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Channel");?></span>
		<span class="delimiter">:</span>
		<span class="value" id="st_Channel_Aband"></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Network Name (SSID)");?></span>
		<span class="delimiter">:</span>
		<pre style="font-family:Tahoma"><span class="value" id="st_SSID_Aband"></span></pre>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Wi-Fi Protected Setup");?></span>
		<span class="delimiter">:</span>
		<span class="value" id="st_WPS_status_Aband"></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Security");?></span>
		<span class="delimiter">:</span>
		<span class="value" id="st_security_Aband"></span>
	</div>
	<div class="gap"></div>
	<div class="gap"></div>	
	<div class="textinput">
		<span class="name"><?echo i18n("Guest Zone Wireless Radio");?></span>
		<span class="delimiter">:</span>
		<span class="value" id="gz_st_wireless_radio_Aband"></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Guest Zone Network Name (SSID)");?></span>
		<span class="delimiter">:</span>
		<pre style="font-family:Tahoma"><span class="value" id="gz_st_SSID_Aband"></span></pre>
	</div>
	<!--
	<div class="textinput">
		<span class="name"><?echo i18n("Guest Zone Channel");?></span>
		<span class="delimiter">:</span>
		<span class="value" id="gz_st_Channel"></span>
	</div>
	-->
	<div class="textinput">
		<span class="name"><?echo i18n("Guest Zone Security");?></span>
		<span class="delimiter">:</span>
		<span class="value" id="gz_st_security_Aband"></span>
	</div>
	<div class="gap"></div>
</div>	

<!--WIRELESS FOR BRIDGE/CLIENT MODE IN DIR-865-->
<div class="blackbox" id="wlan_client" style="display:none">
	<h2><?echo i18n("WIRELESS LAN");?></h2>
	<div class="textinput">
		<span class="name"><?echo i18n("Connection Status");?></span>
		<span class="delimiter">:</span>
		<span class="value" id="client_connect"></span>
	</div>	
	<div class="textinput">
		<span class="name"><?echo i18n("MAC Address");?></span>
		<span class="delimiter">:</span>
		<span class="value"><?echo query("/runtime/devdata/wlanmac");?></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Network Name (SSID)");?></span>
		<span class="delimiter">:</span>
		<pre style="font-family:Tahoma"><span class="value" id="client_SSID"></span></pre>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Channel");?></span>
		<span class="delimiter">:</span>
		<span class="value" id="client_Channel"></span>
	</div>
	<div class="textinput">
		<span class="name"><?echo i18n("Security Mode");?></span>
		<span class="delimiter">:</span>
		<span class="value" id="client_security"></span>
	</div>
	<div class="gap"></div>
</div>	

<div class="blackbox" <? if(query("/device/layout") =="bridge") echo 'style="display:none;"';?>>
	<h2><?echo i18n("LAN COMPUTERS");?></h2>
	<div class="centerline">
		<table id="client_list" class="general" width="535px">
			<tr>			
				<th width="120px"><?echo i18n("MAC Address");?></th>
				<th width="100px"><?echo i18n("IP Address");?></th>			
				<th width="100px"><?echo i18n("Name(if any)");?></th>			
			</tr>
		</table>
	</div>
</div>
<div class="blackbox" <? if(query("/device/layout") =="bridge") echo 'style="display:none;"';?>>
	<h2><?echo i18n("IGMP MULTICAST MEMBERSHIPS");?></h2>
	<table id="igmp_groups" class="general" width="535px">
			<tr>			
				<th width="270px"><?echo i18n("IPv4 Multicast Group Address");?></th>
			</tr>
	</table>	
	<table id="mld_groups" class="general" width="535px">
			<tr>			
				<th width="270px"><?echo i18n("IPv6 Multicast Group Address");?></th>
			</tr>
	</table>
</div>
</form>
