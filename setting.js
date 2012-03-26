/**
 *     Pool one wordpress plugin
 *     Copyright (C) 2011  www.gopiplus.com
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

function pool1_delete(id)
{
	if(confirm("Do you want to delete this record?"))
	{
		document.frm_pool1_display.action="options-general.php?page=pool-one-wp-plugin/pool-one-wp-plugin.php&AC=DEL&DID="+id;
		document.frm_pool1_display.submit();
	}
}	

function pool1_redirect()
{
	window.location = "options-general.php?page=pool-one-wp-plugin/pool-one-wp-plugin.php";
}