/**
 *     Poll one wp plugin
 *     Copyright (C) 2012  www.gopiplus.com
 *	   http://www.gopiplus.com/work/2012/03/19/pool-one-wp-wordpress-plugin/
 * 
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 * 
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 * 
 *     You should have received a copy of the GNU General Public License
 *     along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
 
 
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
