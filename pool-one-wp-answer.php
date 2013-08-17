<?php
ob_start();
$pool1_abspath = dirname(__FILE__);
$pool1_abspath_1 = str_replace('wp-content/plugins/pool-one-wp-plugin', '', $pool1_abspath);
$pool1_abspath_1 = str_replace('wp-content\plugins\pool-one-wp-plugin', '', $pool1_abspath_1);
require_once($pool1_abspath_1 .'wp-config.php');

$ans = @$_GET["ans"];
$res = "";
global $wpdb, $wp_version;
$pool1_que_css = get_option('pool1_que_css_res');
$pool1_ans_css = get_option('pool1_ans_css_res');

$cSql = "select poolq_id, poolq_question from ". POOLONETABLEQ ." where poolq_id in (select poolq_id from ". POOLONETABLEA . " where poola_id =" . $ans . ") limit 0,1;";
$res = $res . '<span id="pool1_msg" style="padding-top:5px;padding-left:10px;color:#FF0000;"></span>';
$pool_question = $wpdb->get_results($cSql);
if ( !empty($pool_question) ) 
{
	$sql = "update ". POOLONETABLEA . " set poola_vote = poola_vote+1 where poola_id = " . $ans;
	$wpdb->get_results($sql);
	
	foreach ( $pool_question as $question ) 
	{
		$poolq_id = $question->poolq_id;
		$poolq_question = $question->poolq_question;
		// setcookie
		setcookie("POLLONE-".$poolq_id, 1, time() + 30000000, COOKIEPATH);
		// setcookie
		$poolq_question = str_replace( "##QUESTION##" , $poolq_question, $pool1_que_css);
		$res = $res . $poolq_question;
		$sSql = "select poola_id, poola_answer, poola_vote from ". POOLONETABLEA ." where 1=1 and poolq_id = ". $poolq_id;
		$pool_answer = $wpdb->get_results($sSql);
		if ( ! empty($pool_answer) ) 
		{
			foreach ( $pool_answer as $answer ) 
			{
				$poola_id = $answer->poola_id;
				$poola_answer = stripslashes($answer->poola_answer);
				$poola_vote = $answer->poola_vote;
				$poola_answer = str_replace( "##ANSWER##" , $poola_answer, $pool1_ans_css);
				$poola_answer = str_replace( "##RES##" , $poola_vote, $poola_answer);
				$res = $res . $poola_answer;
			}
		}
	}
	$res = $res . "<div style='padding-top:20px;'></div>";
	echo $res;
}
else
{
	echo "exs";
}
?>