// JScript File
//################################################################################
//###### Project   : POLL WP PLUGIN											######
//###### Author    : Gopi                        							######
//################################################################################

var xmlHttp
function GetXmlHttpObject(handler)
{ 
	var objXmlHttp=null
	if (navigator.userAgent.indexOf("Opera")>=0)
	{
		alert("This page doesn't work in Opera") 
		return 
	}
	if (navigator.userAgent.indexOf("MSIE")>=0)
	{ 
		var strName="Msxml2.XMLHTTP"
		if (navigator.appVersion.indexOf("MSIE 5.5")>=0)
		{
			strName="Microsoft.XMLHTTP"
		} 
		try
		{ 
			objXmlHttp=new ActiveXObject(strName)
			objXmlHttp.onreadystatechange=handler 
			return objXmlHttp
		} 
		catch(e)
		{ 
			alert("Error. Scripting for ActiveX might be disabled") 
			return 
		} 
	} 
	if (navigator.userAgent.indexOf("Mozilla")>=0)
	{
		objXmlHttp=new XMLHttpRequest()
		objXmlHttp.onload=handler
		objXmlHttp.onerror=handler 
		return objXmlHttp
	}
} 

function SetPool(a)
{
	var pool1_ans = document.getElementById("pool1_ans");
	pool1_ans.value = a;
}


function pool1_ajx(siteurl)
{
	var ans = document.getElementById("pool1_ans");
	if( ans.value == "" || ans.value == "0" )
	{
		 alert("Please choose an answer.");
		 return false;  
	}
	document.getElementById("pool1_msg").innerHTML="loading...";
	var date_now=new Date()
    var mynumber=Math.random()
	var url=siteurl+"/pool-one-wp-answer.php?ans="+ ans.value + "&timestamp=" + date_now + "&action=" + mynumber;
    xmlHttp=GetXmlHttpObject(newchanged_ncc)
    xmlHttp.open("GET", url , true)
    xmlHttp.send(null)
	
}

function newchanged_ncc() 
{ 
	//alert(xmlHttp.readyState);
	//alert(xmlHttp.responseText);
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
	{ 
		if((xmlHttp.responseText).trim()=="exs")
		{
			document.getElementById("pool1_msg").innerHTML="Please try after some time.";
		}
		else
		{
			document.getElementById("pool1").innerHTML = (xmlHttp.responseText).trim();
		}
	} 
} 

String.prototype.trim = function() {
	return this.replace(/^\s+|\s+$/g,"");
}
String.prototype.ltrim = function() {
	return this.replace(/^\s+/,"");
}
String.prototype.rtrim = function() {
	return this.replace(/\s+$/,"");
}
