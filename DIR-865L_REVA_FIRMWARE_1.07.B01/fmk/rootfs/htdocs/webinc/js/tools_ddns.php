<script type="text/javascript">
function Page() {}
Page.prototype =
{
	services: "DDNS6.WAN-1,DDNS4.WAN-1, DDNS4.WAN-3, RUNTIME.DDNS4.WAN-1",    
	OnLoad: function()
	{
		if (!this.rgmode)
		{
			BODY.DisableCfgElements(true);
		}

	},
	OnUnload: function() {},
	OnSubmitCallback: function(code, result) { return false; },
	org: null,
	cfg: null,
	g_table_index: 1,
	g_edit: 0,
	InitValue: function(xml)
	{
		if (this.org) delete this.org;
		if (this.cfg) delete this.cfg;
		this.org = new Array();
		this.cfg = new Array();
		
		PXML.doc = xml;
		var p = PXML.FindModule("DDNS6.WAN-1");						
		var table = OBJ("v6ddns_list");
		var i=1;
 		var cnt=XG(p+"/ddns6/cnt");
 		 		
 		BODY.CleanTable("v6ddns_list");
		for(i=1;i<=cnt;i++)
		{
			var b = p+"/ddns6/entry:"+i;
			var data	= [	'<input type="checkbox" id="en_ddns_v6'+i+'">',
					'<span id="en_ddns_v6host'+i+'"></span>',
					'<span id="en_ddns_v6addr'+i+'"></span>',
					'<a href="javascript:PAGE.OnEdit('+i+');"><img src="pic/img_edit.gif"></a>',
					'<a href="javascript:PAGE.OnDelete('+i+');"><img src="pic/img_delete.gif"></a>'
					];
			var type	= ["","", "","",""];
	
			BODY.InjectTable("v6ddns_list", i, data, type);
			if(XG(b+"/enable") =="1")
			{
				OBJ("en_ddns_v6"+i).checked=true;	
			}
			else
			{
				OBJ("en_ddns_v6"+i).checked=false;
		
			}		
			OBJ("en_ddns_v6addr"+i).innerHTML=XG(b+"/v6addr");
			OBJ("en_ddns_v6host"+i).innerHTML=XG(b+"/hostname");
			OBJ("en_ddns_v6"+i).disabled=false;
			
			this.org[i] = {
				enable:	XG(b+"/enable"),
				v6addr:	XG(b+"/v6addr"),
				hostname:	XG(b+"/hostname")
				};
				
			this.cfg[i] = {
				enable:	XG(b+"/enable"),
				v6addr:	XG(b+"/v6addr"),
				hostname:	XG(b+"/hostname")
				};	
		}
		this.g_table_index=i;
		
		var p = PXML.FindModule("DDNS4."+this.devicemode);
		if (p === "") alert("ERROR!");
		OBJ("en_ddns").checked = (XG(p+"/inf/ddns4")!=="");
		var ddnsp = GPBT(p+"/ddns4", "entry", "uid", this.ddns, 0);
		//OBJ("server").value	= XG(ddnsp+"/provider");
		OBJ("server").value	= "title";
		var ddns_server = XG(ddnsp+"/provider");
		if(ddns_server == "DLINK") OBJ("v4addr").value	= "dlinkddns.com";
		else if(ddns_server == "DYNDNS") OBJ("v4addr").value	= "dyndns.com";
		//else if(ddns_server == "DLINK.COM.CN") OBJ("v4addr").value	= "dlinkddns.com.cn";
		else if(ddns_server == "ORAY") OBJ("v4addr").value	= "Oray.cn";
		else OBJ("v4addr").value	= ddns_server;
		
		OBJ("host").value	= XG(ddnsp+"/hostname");
		OBJ("user").value	= XG(ddnsp+"/username");
		OBJ("passwd").value	= XG(ddnsp+"/password");
		OBJ("passwd_verify").value	= XG(ddnsp+"/password");
		var interval = XG(ddnsp+"/interval")/60;	if(interval=="" || interval==0)	interval = 567;
		OBJ("timeout").value	= interval;
		
		if(OBJ("en_ddns").checked)
		{
			OBJ("report").innerHTML = "<?echo I18N("j","Connecting");?>";
			if(OBJ("v4addr").value==="Oray.cn")
			{
				OBJ("peanut_status").innerHTML="<?echo I18N("j","Connecting");?>";
			}
		}
		else
		{
			OBJ("report").innerHTML = "<?echo I18N("j","Disconnected");?>";
		}
		/*
		var self = this;
		var ajaxObj = GetAjaxObj("updatenow");
		ajaxObj.createRequest();
		ajaxObj.onCallback = function(xml)
		{
			ajaxObj.release();
			self.GetReport(xml);
		}
		ajaxObj.setHeader("Content-Type", "application/x-www-form-urlencoded");
		ajaxObj.sendRequest("ddns_act.php", "act=getreport");
		*/
		
		//update DDNS status
		if(OBJ("v4addr").value==="Oray.cn")
		{
			setTimeout('PAGE.UpdatnowOray()',3000);
		}
		else
		{
			setTimeout('PAGE.Updatnow()', 3000);
		}
		
		this.ori_enabled = (XG(p+"/inf/ddns4")!=="");
		this.OnChangeServer();
		this.EnableDDNS();
		return true;
	},
	PreSubmit: function()
	{
		var p4 = PXML.FindModule("DDNS4."+this.devicemode);
		var p6 = PXML.FindModule("DDNS6.WAN-1");		
		
		if(OBJ("en_ddns").checked)
		{
			if(COMM_EatAllSpace(OBJ("v4addr").value) == "")
			{			
				BODY.ShowAlert("<?echo I18N("j","Please select server address.");?>");
				return null;	
			}	
			
			if(COMM_EatAllSpace(OBJ("host").value) == "")
			{			
				BODY.ShowAlert("<?echo I18N("j","Please input the host name.");?>");
				return null;	
			}	
			
			if(COMM_EatAllSpace(OBJ("user").value) == "")
			{			
				BODY.ShowAlert("<?echo I18N("j","Please input the user account.");?>");
				return null;	
			}	
			
			if(COMM_EatAllSpace(OBJ("passwd").value) == "")
			{			
				BODY.ShowAlert("<?echo I18N("j","Please input the password.");?>");
				return null;	
			}		
		
			if (!COMM_EqSTRING(OBJ("passwd").value, OBJ("passwd_verify").value))
			{
				BODY.ShowAlert("<?echo I18N("j","Password and Verify Password do not match the new User password.");?>");
				return null;
			}
			if (!TEMP_IsDigit(OBJ("timeout").value))
			{
				BODY.ShowAlert("<?echo I18N("j","Invalid period. The range of Timeout is 1~8670.");?>");
				OBJ("timeout").focus();
				return null;
			}
			
			//+++ Jerry Kao, move the codes below within the "if(OBJ("en_ddns").checked)".			
			if ( (OBJ("v4addr").value!=="")	|| (OBJ("host").value!=="") ||
				 (OBJ("user").value!=="")	|| (OBJ("passwd").value!=="") )
			{
				var ddnsp = GPBT(p4+"/ddns4", "entry", "uid", this.ddns, 0);
				if (!ddnsp)
				{
					var c = XG(p4+"/ddns4/count");
					var s = XG(p4+"/ddns4/seqno");
					c += 1;
					s += 1;
					XS(p4+"/ddns4/entry:"+c+"/uid", this.ddns);
					XS(p4+"/ddns4/count", c);
					XS(p4+"/ddns4/seqno", s);
					ddnsp = p4+"/ddns4/entry:"+c;
				}
				//XS(ddnsp+"/provider", OBJ("server").value);
				var ddns_server = OBJ("v4addr").value;
				if(ddns_server == "dlinkddns.com")		XS(ddnsp+"/provider", "DLINK");
				else if(ddns_server == "dyndns.com")	XS(ddnsp+"/provider", "DYNDNS");
				//else if(ddns_server == "dlinkddns.com.cn")	XS(ddnsp+"/provider", "DLINK.COM.CN");
				else if(ddns_server == "Oray.cn")	XS(ddnsp+"/provider", "ORAY");
				else XS(ddnsp+"/provider", ddns_server);
					
				XS(ddnsp+"/hostname", OBJ("host").value);
				XS(ddnsp+"/username", OBJ("user").value);
				XS(ddnsp+"/password", OBJ("passwd").value);
				var timeout_value=OBJ("timeout").value*60;
				XS(ddnsp+"/interval", timeout_value);
			}
			//--- Jerry Kao.			
			
		}		
		
		if(OBJ("en_ddns_v6").checked)
		{								
			/*+++ Jerry Kao, checks below should be in AddDDNS() for del DDNS list, but
			//               without input IPv6 address and host name.
			if(COMM_EatAllSpace(OBJ("v6addr").value) == "")
			{			
				alert("Please input the IPv6 address.");
				return null;	
			}
			
			if(COMM_EatAllSpace(OBJ("v6host").value) == "")
			{			
				alert("Please input the IPv6 host name.");
				return null;	
			}
			*/
			
			//+++ Jerry Kao, move codes below in the "if(OBJ("en_ddns").checked)".			
			//var p6 = PXML.FindModule("DDNS6.WAN-1");		
			var table = OBJ("v6ddns_list");
			var rows = table.getElementsByTagName("tr");
		
			var row_len = rows.length;								
			if(this.org.length > rows.length) rowslen = this.org.length;
			
			var cnt = rows.length-1;
			while (cnt > 0) {XD(p6+"/ddns6/entry");cnt-=1;}
					
			XS(p6+"/ddns6/cnt",rows.length-1);
	
			var table_ind=1;
			var b=null;
			for(var i = 1;i <= row_len+1;i++)
			{												
				if(OBJ("en_ddns_v6"+i)!=null)
				{				
					b = p6+"/ddns6/entry:"+table_ind;
					XS(b+"/enable",   OBJ("en_ddns_v6"+i).checked? "1" : "0");
					XS(b+"/v6addr",   OBJ("en_ddns_v6addr"+i).innerHTML);
					XS(b+"/hostname", OBJ("en_ddns_v6host"+i).innerHTML);
					table_ind++;
				}
			}
			//--- Jerry Kao.		
		}													
		
		XS(p4+"/inf/ddns4", OBJ("en_ddns").checked ? this.ddns : "");
		
		// PXML.ActiveModule("DDNS6.WAN-1");  
		// PXML.ActiveModule("DDNS4.WAN-1");  		
		// PXML.ActiveModule("DDNS4.WAN-3");

		PXML.IgnoreModule("RUNTIME.DDNS4.WAN-1");
				
		if (this.devicemode == "WAN-3")	PXML.IgnoreModule("DDNS4.WAN-1");
		else							PXML.IgnoreModule("DDNS4.WAN-3");
				
		return PXML.doc;
	},
	//IsDirty: null,
	IsDirty: function()
	{
		var table = OBJ("v6ddns_list");
		var rows = table.getElementsByTagName("tr");
		var tab_index=1;
		
		if (this.cfg) delete this.cfg;
		this.cfg = new Array();
		
		for(var i = 1;i <= rows.length;i++)
		{
			if(OBJ("en_ddns_v6"+i)!=null)
			{
				this.cfg[tab_index] = {
				enable:	OBJ("en_ddns_v6"+i).checked? "1" : "0",
				v6addr:	OBJ("en_ddns_v6addr"+i).innerHTML,
				hostname:	OBJ("en_ddns_v6host"+i).innerHTML
				};				
				tab_index++;
			}
		}
				
		if (this.org.length !== this.cfg.length) return true;
		for (var i=1; i<this.cfg.length; i++)
		{				
			if (this.org[i].enable !== this.cfg[i].enable ||
				this.org[i].v6addr!== this.cfg[i].v6addr||
				this.org[i].hostname !== this.cfg[i].hostname ) 
				return true; 
		}
		
		return false;
	},
	Synchronize: function() {},
	// The above are MUST HAVE methods ...
	///////////////////////////////////////////////////////////////////
	rgmode: <?if (query("/runtime/device/layout")=="bridge") echo "false"; else echo "true";?>,
	devicemode: "WAN-1",
	ddns: "DDNS4-1",
	ori_enabled: false,
	GrayItems: function(disabled)
	{
		var frmObj = document.forms[0];
		for (var idx = 0; idx < frmObj.elements.length; idx+=1)
		{
			var obj = frmObj.elements[idx];
			var name = obj.tagName.toLowerCase();
			if (name === "input" || name === "select")
			{
				obj.disabled = disabled;
			}
		}
	},
	
	GetReport: function(xml)
	{
		var self = this;
		{
			if (xml.Get("/ddns4/valid")==="1" )
			{
				var s = xml.Get("/ddns4/status");
				var r = xml.Get("/ddns4/result");
				var msg = "";
				if (s === "IDLE") 
				{
					if (r === "SUCCESS") msg = "<?echo I18N("j","Connected");?>";
					else msg = "<?echo I18N("j","Disconnected");?>";	
				}
				else if (s === "CONNECTING" || s === "UPDATING")
				{
					msg = "<?echo I18N("j","Connecting");?>";	
				}
				else
				{		
					msg = "<?echo I18N("j","Disconnected");?>";
				}
			}
			else
			{	
				msg = "<?echo I18N("j","Disconnected");?>";
			}
			OBJ("report").innerHTML = msg;

		}

	},
	OnEdit: function(i)
	{		
		OBJ("en_ddns_v6").checked=OBJ("en_ddns_v6"+i).checked;
		OBJ("v6addr").disabled = false;
		OBJ("v6host").disabled = false;
		OBJ("v6addr").value=OBJ("en_ddns_v6addr"+i).innerHTML;
		OBJ("v6host").value=OBJ("en_ddns_v6host"+i).innerHTML;
		OBJ("add_ddns_v6").disabled = false;
		OBJ("clear_ddns_v6").disabled = false;
		this.g_edit=i;
	},
	
	OnDelete: function(i)
	{
		var z;
		var table = OBJ("v6ddns_list");
		var rows = table.getElementsByTagName("tr");

		
		for (z=1; z<=rows.length; z++) 
		{
			if(rows[z]!=null)
			{
				if (rows[z].id==i)
				{
					table.deleteRow(z);
				}
			}
		}
		
		
	},
	ddns_count: 0,
	ddns_testtime: "",
	OnClickUpdateNow: function()
	{	
		/*
		if(OBJ("host").value == "") return alert("Please input the host name.");
		if(OBJ("user").value == "") return alert("Please input the user account");
		if(OBJ("passwd").value == "") return alert("Please input the password");
		*/
		
		PXML.IgnoreModule("DDNS4.WAN-1");
		PXML.IgnoreModule("DDNS4.WAN-3");
		PXML.ActiveModule("RUNTIME.DDNS4.WAN-1");
		
		var self = this;
		var time_now = new Date();
		self.ddns_testtime = time_now.getHours().toString() + time_now.getMinutes().toString() + time_now.getSeconds().toString();
		
		var p = PXML.FindModule("RUNTIME.DDNS4.WAN-1");
		XS(p+"/runtime/inf/ddns4/provider", OBJ("v4addr").value);
		XS(p+"/runtime/inf/ddns4/hostname", OBJ("host").value);
		XS(p+"/runtime/inf/ddns4/username", OBJ("user").value);
		XS(p+"/runtime/inf/ddns4/password", OBJ("passwd").value);
		XS(p+"/runtime/inf/ddns4/testtime", self.ddns_testtime);
		
		var xml = PXML.doc;
		PXML.UpdatePostXML(xml);
        COMM_CallHedwig(PXML.doc, function(xml){PXML.hedwig_callback(xml);});
 		
		this.GrayItems(true);   
		OBJ("report").innerHTML = "<?echo I18N("j","Start updating...");?>";
		self.ddns_count = 0 ;
		
		var ajaxObj = GetAjaxObj("updatenow");
		ajaxObj.createRequest();
		ajaxObj.onCallback = function(xml)
		{
			ajaxObj.release();
			self.GetReport();
		}
		ajaxObj.setHeader("Content-Type", "application/x-www-form-urlencoded");
		ajaxObj.sendRequest("ddns_act.php", "act=getreport");
	},
	OnChangeServer: function()
	{
		if(OBJ("v4addr").value==="Oray.cn")
		{
			OBJ("dsc_oray").style.display = "block";
			OBJ("report_div").style.display = "none";
			OBJ("peanut_status_div").style.display = "block";
			OBJ("host_div").style.display = "none";
			OBJ("host").disabled = true;
		}
		else
		{
			OBJ("report_div").style.display="block";
			OBJ("dsc_oray").style.display="none";
			OBJ("peanut_status_div").style.display="none";
			OBJ("peanut_detail_div").style.display="none";
			OBJ("host_div").style.display = "block";
			OBJ("host").disabled = false;
			// When switching to servers other than the ORAY, stops refreshing.
			//if(this.timer_refresh) { clearTimeout(this.timer_refresh); this.timer_refresh=null; }
		}
	},
	UpdatnowOray: function()
	{
		var p = PXML.FindModule("RUNTIME.DDNS4."+this.devicemode);
		if (p==="")  { alert("ERROR!"); return;}
		PAGE.RegetXML_sync();
		var status = XG(p+"/runtime/inf/ddns4/status");	
			
		if(status=="successed")
		{
			/* OBJ("peanut_status").innerHTML="<?echo i18n("Online");?>" + " / " + "<?echo i18n("Domain Name Registered");?>"; */
			OBJ("peanut_status").innerHTML="<?echo I18N("j","Connected");?>";
				
			if(XG(p+"/runtime/inf/ddns4/usertype")==="1")
				OBJ("peanut_level").innerHTML="<?echo I18N("j","Professional Service");?>";
			else OBJ("peanut_level").innerHTML="<?echo I18N("j","Normal Service");?>";
				OBJ("peanut_detail_div").style.display="";
		}
		else if(status=="connecting")
		{
			/* OBJ("peanut_status").innerHTML="<?echo i18n("Connecting");?>"+"......"; */
			OBJ("peanut_status").innerHTML="<?echo I18N("j","Connecting");?>";
			OBJ("peanut_detail_div").style.display="none";
		}
		else if(status=="badAuth")
		{
			/* OBJ("peanut_status").innerHTML="<?echo i18n("Offline");?>" + " / " + "<?echo i18n("Authentication Failed");?>" + "<br>" +
			"<?echo i18n("Please check the account, the password, and the connectivity to the Internet");?>" +"."; */
			OBJ("peanut_status").innerHTML="<?echo I18N("j","Disconnected");?>";
			OBJ("peanut_detail_div").style.display="none";
		}
		else
		{
			/* OBJ("peanut_status").innerHTML="<?echo i18n("Offline");?>"; */
			OBJ("peanut_status").innerHTML="<?echo I18N("j","Disconnected");?>";
			OBJ("peanut_detail_div").style.display="none";
		}
			
		if( this.ori_enabled && this.ori_server==="ORAY" )  /*Refresh the page, only when the initial data is as "enable ORAY".*/
		{
			clearTimeout(this.timer_refresh);
			this.timer_refresh = setTimeout('self.location.href="/tools_ddns.php"' ,20000);
		}
		setTimeout('PAGE.UpdatnowOray()',3000);
	},
	Updatnow: function() //update DDNS status
	{
		var self = this;
		var ajaxObj = GetAjaxObj("updatenow");
		ajaxObj.createRequest();
		ajaxObj.onCallback = function(xml)
		{
			ajaxObj.release();
			self.GetReport(xml);
			setTimeout('PAGE.Updatnow()', 3000);
		}
		ajaxObj.setHeader("Content-Type", "application/x-www-form-urlencoded");
		ajaxObj.sendRequest("ddns_act.php", "act=getreport");
		
	},
	CableLinkage: function()
	{	
		var p = PXML.FindModule("DDNS4."+this.devicemode);
		var rphy = PXML.FindModule("RUNTIME.PHYINF");

		var wanphyuid = XG  (p+"/inf/phyinf");
		this.rwanphyp = GPBT(rphy+"/runtime", "phyinf", "uid", wanphyuid, false);

		if((XG  (this.rwanphyp+"/linkstatus")!="0")&&(XG  (this.rwanphyp+"/linkstatus")!=""))
			return true;
		else
			return false;
	},
	EnableDDNS: function()
	{
		if(OBJ("en_ddns").checked)
			OBJ("server").disabled = OBJ("v4addr").disabled = OBJ("host").disabled = OBJ("user").disabled = OBJ("passwd").disabled = OBJ("passwd_verify").disabled = OBJ("timeout").disabled = false; 
		else
			OBJ("server").disabled = OBJ("v4addr").disabled = OBJ("host").disabled = OBJ("user").disabled = OBJ("passwd").disabled = OBJ("passwd_verify").disabled = OBJ("timeout").disabled = true;
					
		if(OBJ("en_ddns_v6").checked)
		{
			OBJ("v6addr").disabled = OBJ("v6host").disabled = OBJ("add_ddns_v6").disabled = OBJ("clear_ddns_v6").disabled = false; 			
			for(var i = 1;i < this.g_table_index; i++)
			{
				OBJ("en_ddns_v6"+i).disabled = false;		
			}			
		}
		else
		{
			OBJ("v6addr").disabled = OBJ("v6host").disabled = OBJ("add_ddns_v6").disabled = OBJ("clear_ddns_v6").disabled =  true;						
			for(var i = 1;i < this.g_table_index; i++)
			{
				OBJ("en_ddns_v6"+i).disabled = true;		
			}			
		}			
	},
	RegetXML_sync: function()
	{
        	sync_GetCFG(
                	false,
                	PAGE.services,
                	function(xml) {
                        	PXML.doc = xml;
        		}
        	);
	},
	AddDDNS: function()
	{
		var add_index=0;
				
		if(COMM_EatAllSpace(OBJ("v6addr").value) == "")
		{			
			BODY.ShowAlert("<?echo I18N("j", "Please input the IPv6 address.");?>");
			return null;	
		}
		
		if(COMM_EatAllSpace(OBJ("v6host").value) == "")
		{			
			BODY.ShowAlert("<?echo I18N("j", "Please input the IPv6 host name.");?>");
			return null;
		}	
		
		/* +++ Jerry Kao, move here to below for limit the number of records.
		//+++sam_pan add			
		var e = 
		{
			enable:OBJ("en_ddns_v6").checked? "1" : "0",
			v6addr:OBJ("v6addr").value,
			hostname:OBJ("v6host").value
		};
				
		for (j=1; j<this.cfg.length; j++)
		{														
			if(this.cfg[j].v6addr == e.v6addr)
			{
				alert("<?echo I18N("j", "The IPv6 address");?>"+" '"+e.v6addr+"'<?echo I18N("j", " is already existed!");?>");
				return null;
			}
			
			if(this.cfg[j].hostname == e.hostname)
			{
				alert("<?echo I18N("j", "The IPv6 host name");?>"+" '"+e.hostname+"'<?echo I18N("j", " is already existed!");?>");
				return null;
			}
		}	
				
		this.cfg[j++] = e;											 		
		//---sam_pan add
		*/
		
		//+++ Jerry Kao, remove all space in ipv6 address and host name.		
		OBJ("v6addr").value = COMM_EatAllSpace(OBJ("v6addr").value);			
		OBJ("v6host").value = COMM_EatAllSpace(OBJ("v6host").value);
		
							
		if(this.g_edit!=0)
		{
			add_index=this.g_edit;
		}
		else
		{
			add_index=this.g_table_index;
		}
		
		//+++ Jerry Kao, added max number for "IPv6 DDNS list".
		var p = PXML.FindModule("DDNS6.WAN-1");
		var max_cnt = XG(p+"/ddns6/max");	// added node "/ddns6/max" in dir-845, only.				
		
		if (max_cnt=="")
			max_cnt = 32;				
		
		if (add_index <= max_cnt)
		{
			//+++sam_pan add		
			var e = 
			{
				enable:OBJ("en_ddns_v6").checked? "1" : "0",
				v6addr:OBJ("v6addr").value,
				hostname:OBJ("v6host").value
			};
							
			for (j=1; j<this.cfg.length; j++)
			{														
				//+++ Jerry Kao, added for modifiing exist records easier.			
				if (this.g_edit != 0 && j != this.g_edit)
				{					
					if(this.cfg[j].v6addr == e.v6addr)
					{
						alert("<?echo I18N("j", "The IPv6 address");?>"+" '"+e.v6addr+"'<?echo I18N("j", " is already existed!");?>");
						return null;
					}
					
					if(this.cfg[j].hostname == e.hostname)
					{
						alert("<?echo I18N("j", "The IPv6 host name");?>"+" '"+e.hostname+"'<?echo I18N("j", " is already existed!");?>");
						return null;
					}
				}
			}
													
			this.cfg[j++] = e;											 		
			//---sam_pan add
				
			var data	= [	'<input type="checkbox" id="en_ddns_v6'+add_index+'">',
							'<span id="en_ddns_v6host'+add_index+'"></span>',
							'<span id="en_ddns_v6addr'+add_index+'"></span>',
							'<a href="javascript:PAGE.OnEdit('+add_index+');"><img src="pic/img_edit.gif"></a>',
							'<a href="javascript:PAGE.OnDelete('+add_index+');"><img src="pic/img_delete.gif"></a>'
							];
			var type	= ["","", "","",""];
			
			BODY.InjectTable("v6ddns_list", add_index, data, type);
			if(OBJ("en_ddns_v6").checked)
			{
				OBJ("en_ddns_v6"+add_index).checked=true;	
			}
			else
			{
				OBJ("en_ddns_v6"+add_index).checked=false;
		
			}		
			OBJ("en_ddns_v6addr"+add_index).innerHTML=OBJ("v6addr").value;
			OBJ("en_ddns_v6host"+add_index).innerHTML=OBJ("v6host").value;
			OBJ("en_ddns_v6"+add_index).disabled=false;
			if(this.g_edit!=0)
			{
				this.g_edit=0;
			}
			else
			{
				this.g_table_index++;	
			}
			
			//+++ Jerry Kao, Reset after save.
			OBJ("v6addr").value = "";
			OBJ("v6host").value = "";
			
			OBJ("mainform").setAttribute("modified", "true");
		}
		else
		{			
			alert("<?echo I18N("j", "The maximum number of list is");?>"+" "+max_cnt);								
		}
	}		
};


function ClearDDNS()
{
	OBJ("v6addr").value = "";
	OBJ("v6host").value = "";
	OBJ("mainform").setAttribute("modified", "true");
}
function OnClickPCArrow(idx)
{
	OBJ("v6addr").value = OBJ("pc_"+idx).value;

}
function OnClickAddr()
{
	var ddns_addr = OBJ("server").value;

	if(OBJ("en_ddns").checked)
	{
		if(ddns_addr == "DLINK")	OBJ("v4addr").value = "dlinkddns.com";
		else if(ddns_addr == "DYNDNS")	OBJ("v4addr").value = "dyndns.com";
		//else if(ddns_addr == "DLINK.COM.CN")	OBJ("v4addr").value = "dlinkddns.com.cn";
		else if(ddns_addr == "ORAY")	OBJ("v4addr").value = "Oray.cn";
			
		if(OBJ("v4addr").value==="Oray.cn")
		{
			OBJ("dsc_oray").style.display = "block";
			OBJ("report_div").style.display = "none";
			OBJ("peanut_status_div").style.display = "block";
			OBJ("host_div").style.display = "none";
			OBJ("host").disabled = true;
		}
		else
		{
			OBJ("report_div").style.display="block";
			OBJ("dsc_oray").style.display="none";
			OBJ("peanut_status_div").style.display="none";
			OBJ("peanut_detail_div").style.display="none";
			OBJ("host_div").style.display = "block";
			OBJ("host").disabled = false;
		}
	}
	else
	{	
		OBJ("v4addr").value = "";
	}
	//PAGE.OnChangeServer();
}
function sync_GetCFG(Cache, Services, Handler)
{
	var ajaxObj = GetAjaxObj("getData");
	var payload = "";
  	ajaxObj.requestAsyn=false;

	if (Cache) payload = "CACHE=true";
	if (payload!="") payload += "&";
	payload += "SERVICES="+escape(COMM_EatAllSpace(Services));

	ajaxObj.createRequest();
	ajaxObj.onCallback = function (xml)
	{
		ajaxObj.release();
		if (Handler!=null) Handler(xml);
	}
	ajaxObj.setHeader("Content-Type", "application/x-www-form-urlencoded");
	ajaxObj.sendRequest("getcfg.php", payload);
}
</script>
