var http_req = false;
function PollOnePOSTRequest(url, parameters) 
{
	http_req = false;
	if (window.XMLHttpRequest) 
	{
		http_req = new XMLHttpRequest();
		if (http_req.overrideMimeType) 
		{
			http_req.overrideMimeType('text/html');
		}
	} 
	else if (window.ActiveXObject) 
	{
		try 
		{
			http_req = new ActiveXObject("Msxml2.XMLHTTP");
		} 
		catch (e) 
		{
			try 
			{
				http_req = new ActiveXObject("Microsoft.XMLHTTP");
			} 
			catch (e) {}
		}
	}
	if (!http_req) 
	{
		alert('Cannot create XMLHTTP instance');
		return false;
	}
	http_req.onreadystatechange = PollOneContents;
	http_req.open('POST', url, true);
	http_req.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	http_req.setRequestHeader("Content-length", parameters.length);
	http_req.setRequestHeader("Connection", "close");
	http_req.send(parameters);
}

function PollOneContents() 
{
	//alert(http_req.readyState);
	//alert(http_req.responseText);
	if (http_req.readyState == 4) 
	{
		if (http_req.status == 200) 
		{
			result = http_req.responseText;
			result = result.trim();
			if((result == "exs"))
			{
				document.getElementById("pool1_msg").innerHTML = "Please try after some time.";
			}
			else if((result == "there-was-problem"))
			{
				document.getElementById("pool1_msg").innerHTML = "There was a problem with the request.";
			}
			else
			{
				document.getElementById("pool1").innerHTML = result;
			}
		} 
		else 
		{
			alert('There was a problem with the request.');
		}
	}
}

function SetPool(a)
{
	var pool1_ans = document.getElementById("pool1_ans");
	pool1_ans.value = a;
}

function PollOne_Submit(url) 
{
	var ans = document.getElementById("pool1_ans");
	if( ans.value == "" || ans.value == "0" )
	{
		 alert("Please choose an answer.");
		 return false;  
	}
	document.getElementById('pool1_msg').innerHTML = "Sending...";
	
	var date_now = new Date()
    var mynumber = Math.random()
	var str = "ans=" + encodeURI(ans.value) + "&timestamp=" + encodeURI(date_now) + "&action=" + encodeURI(mynumber);
	PollOnePOSTRequest(url+'/?pollone=poll-submitted', str);
}