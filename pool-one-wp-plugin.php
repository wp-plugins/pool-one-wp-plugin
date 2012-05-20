<?php

/*
Plugin Name: Poll one wp plugin
Plugin URI: http://www.gopiplus.com/work/2012/03/19/pool-one-wp-wordpress-plugin/
Description: Pool one wp plug-in is simple Ajax based pool plug-in for WordPress. using this plug-in we can customize the pool in the website widget.
Author: Gopi.R
Version: 2.0
Author URI: http://www.gopiplus.com/work/2012/03/19/pool-one-wp-wordpress-plugin/
Donate link: http://www.gopiplus.com/work/2012/03/19/pool-one-wp-wordpress-plugin/
Tags: poll, plugin, wordpress, widget
*/

global $wpdb, $wp_version;
define("POOLONETABLEQ", $wpdb->prefix . "pooloneq_wp_plugin");
define("POOLONETABLEA", $wpdb->prefix . "poolonea_wp_plugin");

if ( ! defined( 'POOLONE_PLUGIN_BASENAME' ) )
	define( 'POOLONE_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

if ( ! defined( 'POOLONE_PLUGIN_NAME' ) )
	define( 'POOLONE_PLUGIN_NAME', trim( dirname( POOLONE_PLUGIN_BASENAME ), '/' ) );

if ( ! defined( 'POOLONE_PLUGIN_DIR' ) )
	define( 'POOLONE_PLUGIN_DIR', WP_PLUGIN_DIR . '/' . POOLONE_PLUGIN_NAME );

if ( ! defined( 'POOLONE_PLUGIN_URL' ) )
	define( 'POOLONE_PLUGIN_URL', WP_PLUGIN_URL . '/' . POOLONE_PLUGIN_NAME );

function pool1_plugin_path( $path = '' ) {
	return path_join( POOLONE_PLUGIN_DIR, trim( $path, '/' ) );
}

function pool1_plugin_url( $path = '' ) {
	return plugins_url( $path, POOLONE_PLUGIN_BASENAME );
}

function poolone() 
{
	global $wpdb;
	$pool1_que_css = get_option('pool1_que_css');
	$pool1_ans_css = get_option('pool1_ans_css');
	$pool1_btn_css = get_option('pool1_btn_css');
	echo '<span id="pool1"><span id="pool1_msg" style="padding-top:5px;padding-left:10px;color:#FF0000;"></span><br />';
	$pool_random = "YES";
	$sSql = "select poolq_id, poolq_question from ". POOLONETABLEQ ." where 1=1";
	$sSql = $sSql . " and (`poolq_start` <= NOW() and `poolq_end` >= NOW())";
	if($pool_random == "YES"){ $sSql = $sSql . " ORDER BY RAND()"; }
	$sSql = $sSql . " limit 0,1;";
	$pool_question = $wpdb->get_results($sSql);
	if ( ! empty($pool_question) ) 
	{
		foreach ( $pool_question as $question ) 
		{
			$poolq_id = $question->poolq_id;
			$poolq_question = $question->poolq_question;
			$poolq_question = str_replace( "##QUESTION##" , $poolq_question, $pool1_que_css);
			echo $poolq_question;
			$sSql = "select poola_id, poola_answer from ". POOLONETABLEA ." where 1=1 and poolq_id = ". $poolq_id;
			$pool_answer = $wpdb->get_results($sSql);
			if ( ! empty($pool_answer) ) 
			{
				foreach ( $pool_answer as $answer ) 
				{
					$poola_id = $answer->poola_id;
					$poola_answer = $answer->poola_answer;
					$poola_answer = "<input type ='Radio' onClick='SetPool(".$poola_id.")' name = 'poolanswer' id = 'poolanswer' value= '".$poola_id."'> ".$poola_answer."<br>";
					$poola_answer = str_replace( "##ANSWER##" , $poola_answer, $pool1_ans_css);
					echo $poola_answer;
				}
			}
			echo '<input name="pool1_ans" id="pool1_ans" type="hidden" value="" />';
			$url = '"' . pool1_plugin_url('') . '"';
			$pool_button = "<input class='pool1_btn_cls' name='pool1_btn' id='pool1_btn' onClick='return pool1_ajx(".$url.")' value='Submit' type='button'>";
			$pool_button = str_replace( "##BUTTON##" , $pool_button, $pool1_btn_css);
			echo $pool_button;
			echo '</span>';
		}
	}
	else
	{
		echo '<div style="text-align:center;">No pool available</div>';
	}
}

function pool1_install() 
{
	global $wpdb;
	if($wpdb->get_var("show tables like '". POOLONETABLEQ . "'") != POOLONETABLEQ) 
	{
		$sSql = "CREATE TABLE IF NOT EXISTS `". POOLONETABLEQ . "` (";
		$sSql = $sSql . "`poolq_id` INT NOT NULL AUTO_INCREMENT ,";
		$sSql = $sSql . "`poolq_question` VARCHAR( 1024 ) NOT NULL ,";
		$sSql = $sSql . "`poolq_start` datetime NOT NULL default '0000-00-00 00:00:00' ,";
		$sSql = $sSql . "`poolq_end` datetime NOT NULL default '0000-00-00 00:00:00' ,";
		$sSql = $sSql . "`poolq_ext0` TEXT CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ,";
		$sSql = $sSql . "`poolq_ext1` VARCHAR( 200 ) NOT NULL ,";
		$sSql = $sSql . "`poolq_ext2` VARCHAR( 200 ) NOT NULL ,";
		$sSql = $sSql . "`poolq_ext3` VARCHAR( 200 ) NOT NULL ,";
		$sSql = $sSql . "PRIMARY KEY ( `poolq_id` )";
		$sSql = $sSql . ")";
		$wpdb->query($sSql);
		
		$sSql = "";
		$IsSql = "INSERT INTO `". POOLONETABLEQ . "` (`poolq_question`, `poolq_start`, `poolq_end`)"; 
		$sSql = $IsSql . " VALUES ('Do you like this plugin?', '2012-01-01 00:00:00', '2015-01-01 00:00:00');";
		$wpdb->query($sSql);
		
		$qid = mysql_insert_id();
		
		$sSql = "CREATE TABLE IF NOT EXISTS `". POOLONETABLEA . "` (";
		$sSql = $sSql . "`poola_id` INT NOT NULL AUTO_INCREMENT ,";
		$sSql = $sSql . "`poola_answer` VARCHAR( 1024 ) NOT NULL ,";
		$sSql = $sSql . "`poola_vote` INT NOT NULL default '0' ,";
		$sSql = $sSql . "`poolq_id` INT NOT NULL ,";
		$sSql = $sSql . "`poola_ext0` VARCHAR( 200 ) NOT NULL ,";
		$sSql = $sSql . "`poola_ext1` VARCHAR( 200 ) NOT NULL ,";
		$sSql = $sSql . "`poola_ext2` VARCHAR( 200 ) NOT NULL ,";
		$sSql = $sSql . "`poola_ext3` VARCHAR( 200 ) NOT NULL ,";
		$sSql = $sSql . "PRIMARY KEY ( `poola_id` )";
		$sSql = $sSql . ")";
		$wpdb->query($sSql);
		
		$sSql = "";
		$IsSql = "INSERT INTO `". POOLONETABLEA . "` (`poola_answer`, `poola_vote`, `poolq_id`)"; 
		$sSql = $IsSql . " VALUES ('Yes, I like it.', 0, ".$qid." );";
		$wpdb->query($sSql);
		$sSql = $IsSql . " VALUES ('Yes, But i need more option.', 0, ".$qid.");";
		$wpdb->query($sSql);
		$sSql = $IsSql . " VALUES ('No, Its very basic.', 0, ".$qid." );";
		$wpdb->query($sSql);
	}
	add_option('pool1_title', "Poll One");
	add_option('pool1_que_css', "<div style='color:#000000;font-weight:bold;padding-left:10px;padding-bottom:10px;'>##QUESTION##</div>");
	add_option('pool1_ans_css', "<div style='color:#000000;padding-left:10px;padding-bottom:10px;'>##ANSWER##</div>");
	add_option('pool1_btn_css', "<div style='padding-left:10px;padding-bottom:10px;'>##BUTTON##</div>");
	add_option('pool1_que_css_res', "<div style='color:#000000;font-weight:bold;padding-left:10px;padding-bottom:10px;'>##QUESTION##</div>");
	add_option('pool1_ans_css_res', "<div style='color:#000000;padding-left:10px;padding-bottom:10px;'>##ANSWER## (##RES##)</div>");
}

function pool1_admin_options() 
{
	include_once("pool-management.php");
}

function pool1_deactivation() 
{

}


function pool1_add_javascript_files() 
{
	if (!is_admin())
	{
		wp_enqueue_script( 'pool1', pool1_plugin_url('js/widget.js'));
	}	
}



function pool1_add_to_menu() 
{
	add_options_page('Pool one wp plugin', 'Pool one wp plugin', 'manage_options', __FILE__, 'pool1_admin_options' );
}

if (is_admin()) 
{
	add_action('admin_menu', 'pool1_add_to_menu');
}

function pool1_widget($args) 
{
	extract($args);
	echo $before_widget . $before_title;
	echo get_option('pool1_title');
	echo $after_title;
	poolone();
	echo $after_widget;
}

function pool1_control() 
{
	$pool1_title = get_option('pool1_title');
	$pool1_que_css = htmlspecialchars(get_option('pool1_que_css'));
	$pool1_ans_css = htmlspecialchars(get_option('pool1_ans_css'));
	$pool1_btn_css = htmlspecialchars(get_option('pool1_btn_css'));
	$pool1_que_css_res = htmlspecialchars(get_option('pool1_que_css_res'));
	$pool1_ans_css_res = htmlspecialchars(get_option('pool1_ans_css_res'));
	
	if (@$_POST['pool1_submit']) 
	{
		$pool1_title = stripslashes($_POST['pool1_title']);
		$pool1_que_css = stripslashes($_POST['pool1_que_css']);
		$pool1_ans_css = stripslashes($_POST['pool1_ans_css']);
		$pool1_btn_css = stripslashes($_POST['pool1_btn_css']);
		$pool1_que_css_res = stripslashes($_POST['pool1_que_css_res']);
		$pool1_ans_css_res = stripslashes($_POST['pool1_ans_css_res']);
		
		update_option('pool1_title', $pool1_title );
		update_option('pool1_que_css', $pool1_que_css );
		update_option('pool1_ans_css', $pool1_ans_css );
		update_option('pool1_btn_css', $pool1_btn_css );
		update_option('pool1_que_css_res', $pool1_que_css_res );
		update_option('pool1_ans_css_res', $pool1_ans_css_res );
		
		$pool1_que_css = htmlspecialchars($pool1_que_css);
		$pool1_ans_css = htmlspecialchars($pool1_ans_css);
		$pool1_btn_css = htmlspecialchars($pool1_btn_css);
		$pool1_que_css_res = htmlspecialchars($pool1_que_css_res);
		$pool1_ans_css_res = htmlspecialchars($pool1_ans_css_res);
	}
	
	echo '<p>Title:<br><input  style="width: 200px;" type="text" value="';
	echo $pool1_title . '" name="pool1_title" id="pool1_title" /></p>';
	
	echo '<p>Question CSS:<br><input  style="width: 675px;" type="text" value="';
	echo $pool1_que_css . '" name="pool1_que_css" id="pool1_que_css" /><br>Keyword: ##QUESTION##</p>';
	
	echo '<p>Answer CSS:<br><input  style="width: 675px;" type="text" value="';
	echo $pool1_ans_css . '" name="pool1_ans_css" id="pool1_ans_css" /><br>Keyword: ##ANSWER##</p>';
	
	echo '<p>Button CSS:<br><input  style="width: 675px;" type="text" value="';
	echo $pool1_btn_css . '" name="pool1_btn_css" id="pool1_btn_css" /><br>Keyword: ##BUTTON##</p>';
	
	echo '<p>Question CSS result box:<br><input  style="width: 675px;" type="text" value="';
	echo $pool1_que_css_res . '" name="pool1_que_css_res" id="pool1_que_css_res" /><br>Keyword: ##QUESTION##</p>';
	
	echo '<p>Answer CSS result box:<br><input  style="width: 675px;" type="text" value="';
	echo $pool1_ans_css_res . '" name="pool1_ans_css_res" id="pool1_ans_css_res" /><br>Keyword: ##ANSWER##, ##RES##</p>';

	echo "<span style='color:#990000;'>Check official website for more information ";
	echo "<a target='_blank' href='http://www.gopiplus.com/work/2012/03/19/pool-one-wp-wordpress-plugin/'>Click here</a></span> ";

	echo '<input type="hidden" id="pool1_submit" name="pool1_submit" value="1" />';
}

function pool1_init()
{
	if(function_exists('wp_register_sidebar_widget')) 
	{
		wp_register_sidebar_widget('Pool one wp plugin', 'Pool one wp plugin', 'pool1_widget');
	}
	
	if(function_exists('wp_register_widget_control')) 
	{
		wp_register_widget_control('Pool one wp plugin', array('Pool one wp plugin', 'widgets'), 'pool1_control', 'width=750');
	} 
}

add_action("plugins_loaded", "pool1_init");
register_activation_hook(__FILE__, 'pool1_install');
register_deactivation_hook(__FILE__, 'pool1_deactivation');
add_action('init', 'pool1_add_javascript_files');
?>