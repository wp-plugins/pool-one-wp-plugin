/**
 *     Poll one wp plugin
 *     Copyright (C) 2011 - 2014 www.gopiplus.com
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

function pool1_submit()
{
	var tot = 2;
	if(document.pool1_form.pool1_qus.value=="")
	{
		alert("Please enter the question.")
		document.pool1_form.pool1_qus.focus();
		return false;
	}
	else if(document.pool1_form.pool1_ans1.value=="")
	{
		alert("Please enter at least two option.")
		document.pool1_form.pool1_ans1.focus();
		return false;
	}
	else if(document.pool1_form.pool1_ans2.value=="")
	{
		alert("Please enter at least two option.")
		document.pool1_form.pool1_ans2.focus();
		return false;
	}
	else if(document.pool1_form.pool1_startdate.value=="")
	{
		alert("Please enter start date.")
		document.pool1_form.pool1_startdate.focus();
		return false;
	}
	else if(document.pool1_form.pool1_enddate.value=="")
	{
		alert("Please enter end date.")
		document.pool1_form.pool1_enddate.focus();
		return false;
	}
	
	if(document.pool1_form.pool1_ans3.value!="")
	{
		tot = tot + 1;
	}
	if(document.pool1_form.pool1_ans4.value!="")
	{
		tot = tot + 1;
	}
	if(document.pool1_form.pool1_ans5.value!="")
	{
		tot = tot + 1;
	}
	if(document.pool1_form.pool1_ans6.value!="")
	{
		tot = tot + 1;
	}
	document.pool1_form.pool1_tot.value = tot;
}

function poolq_delete(id)
{
	if(confirm("Do you want to delete this record?"))
	{
		document.frm_poolq_display.action="admin.php?page=PollOne&ac=del&did="+id;
		document.frm_poolq_display.submit();
	}
}	

function poolq_redirect()
{
	window.location = "admin.php?page=PollOne";
}

function poolq_help()
{
	window.open("http://www.gopiplus.com/work/2012/03/19/pool-one-wp-wordpress-plugin/");
}