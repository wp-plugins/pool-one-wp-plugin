<?php
/*
Plugin Name: Poll one wp plugin
Plugin URI: http://www.gopiplus.com/work/2012/03/19/pool-one-wp-wordpress-plugin/
Description: Poll one wp plugin is simple Ajax based poll plug-in for WordPress. using this plug-in we can customize the poll in the website widget.
Author: Gopi Ramasamy
Version: 7.1
Author URI: http://www.gopiplus.com/work/2012/03/19/pool-one-wp-wordpress-plugin/
Donate link: http://www.gopiplus.com/work/2012/03/19/pool-one-wp-wordpress-plugin/
Tags: poll, plugin, wordpress
*/

if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

global $wpdb, $wp_version;
define("POOLONETABLEQ", $wpdb->prefix . "pooloneq_wp_plugin");
define("POOLONETABLEA", $wpdb->prefix . "poolonea_wp_plugin");
define('WP_po1lone_FAV', 'http://www.gopiplus.com/work/2012/03/19/pool-one-wp-wordpress-plugin/');

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
	
	$pool_random = "YES";
	$sSql = "select poolq_id, poolq_question from ". POOLONETABLEQ ." where 1=1";
	$sSql = $sSql . " and (`poolq_start` <= NOW() and `poolq_end` >= NOW())";
	if($pool_random == "YES"){ $sSql = $sSql . " ORDER BY RAND()"; }
	$sSql = $sSql . " limit 0,1;";
	$pool_question = $wpdb->get_results($sSql);
	if ( ! empty($pool_question) ) 
	{
		// setcookie
		ob_start();
		$cookie_pool_question = $pool_question[0];
		if ( !empty($cookie_pool_question) ) $cookie_poolq_id = $cookie_pool_question->poolq_id; 
		// setcookie
		if (isset($_COOKIE["POLLONE-$cookie_poolq_id"]))
		{
			$pool1_que_css = get_option('pool1_que_css_res');
			$pool1_ans_css = get_option('pool1_ans_css_res');
			echo '<div id="pool1" class="poll_main_cls">';
			echo '<div id="pool1_msg" class="poll_msg_cls" style=""></div>';
			foreach ( $pool_question as $question ) 
			{
				$poolq_id = $question->poolq_id;
				$poolq_question = stripslashes($question->poolq_question);
				$poolq_question = str_replace( "##QUESTION##" , $poolq_question, $pool1_que_css);
				echo "<div class='poll_question_cls'>". $poolq_question."</div>";
				$sSql = "select poola_id, poola_answer, poola_vote from ". POOLONETABLEA ." where 1=1 and poolq_id = ". $poolq_id;
				$pool_answer = $wpdb->get_results($sSql);
				if ( ! empty($pool_answer) ) 
				{
					echo "<ul class='poll_ans_ul_cls'>";
					foreach ( $pool_answer as $answer ) 
					{
						$poola_id = $answer->poola_id;
						$poola_answer = stripslashes($answer->poola_answer);
						$poola_vote = $answer->poola_vote;
						$poola_answer = str_replace( "##ANSWER##" , $poola_answer, $pool1_ans_css);
						$poola_answer = str_replace( "##RES##" , $poola_vote, $poola_answer);
						echo "<li class='poll_ans_li_cls'>".$poola_answer."</li>";
					}
					echo "</ul>";
				}
			}
			echo '</div>';
		}
		else
		{
			foreach ( $pool_question as $question ) 
			{
				echo '<div id="pool1" class="poll_main_cls">';
				echo '<div id="pool1_msg" class="poll_msg_cls" style=""></div>';
				$poolq_id = $question->poolq_id;
				$poolq_question = $question->poolq_question;
				$poolq_question = str_replace( "##QUESTION##" , $poolq_question, $pool1_que_css);
				echo "<div class='poll_question_cls'>". $poolq_question."</div>";
				$sSql = "select poola_id, poola_answer from ". POOLONETABLEA ." where 1=1 and poolq_id = ". $poolq_id;
				$pool_answer = $wpdb->get_results($sSql);
				if ( ! empty($pool_answer) ) 
				{
					echo "<ul class='poll_ans_ul_cls'>";
					foreach ( $pool_answer as $answer ) 
					{
						$poola_id = $answer->poola_id;
						$poola_answer = stripslashes($answer->poola_answer);
						$poola_answer = "<input type ='Radio' onClick='SetPool(".$poola_id.")' name = 'poolanswer' id = 'poolanswer' value= '".$poola_id."'> ".$poola_answer."<br />";
						$poola_answer = str_replace( "##ANSWER##" , $poola_answer, $pool1_ans_css);
						echo "<li class='poll_ans_li_cls'>".$poola_answer."</li>";
					}
					echo "</ul>";
				}
				echo '<input name="pool1_ans" id="pool1_ans" type="hidden" value="" />';
				$url = '"' . home_url() . '"';
				$pool_button = "<input class='pool1_btn_cls' name='pool1_btn' id='pool1_btn' onClick='return PollOne_Submit(".$url.")' value='Submit' type='button'>";
				$pool_button = str_replace( "##BUTTON##" , $pool_button, $pool1_btn_css);
				echo '<div class="pool1_btn_cls">';
				echo $pool_button;
				echo '</div>';
				echo '</div>';
			}
		}
	}
	else
	{
		echo '<div style="text-align:center;">'.__('No poll available', 'poll-one').'</div>';
	}
}

add_shortcode( 'pollone', 'pool1_shortcode' );

function pool1_shortcode( $atts ) 
{
	global $wpdb;
	$pool1_que_css = get_option('pool1_que_css_page');
	$pool1_ans_css = get_option('pool1_ans_css_page');
	$pool1_btn_css = get_option('pool1_btn_css_page');
	
	//[pollone id="1"]
	if ( ! is_array( $atts ) )
	{
		return '';
	}
	$id = $atts['id'];
	
	$poll = "";
	$pool_random = "YES";
	$sSql = "select poolq_id, poolq_question from ". POOLONETABLEQ ." where 1=1";
	$sSql = $sSql . " and (`poolq_start` <= NOW() and `poolq_end` >= NOW())";
	if($id <> ""){ $sSql = $sSql . " and poolq_id=$id "; }
	if($pool_random == "YES"){ $sSql = $sSql . " ORDER BY RAND()"; }
	$sSql = $sSql . " limit 0,1;";
	$pool_question = $wpdb->get_results($sSql);
	if ( ! empty($pool_question) ) 
	{
		// setcookie
		ob_start();
		$cookie_pool_question = $pool_question[0];
		if ( !empty($cookie_pool_question) ) $cookie_poolq_id = $cookie_pool_question->poolq_id; 
		// setcookie
		if (isset($_COOKIE["POLLONE-$cookie_poolq_id"]))
		{
			$pool1_que_css = get_option('pool1_que_css_res_page');
			$pool1_ans_css = get_option('pool1_ans_css_res_page');
			$res = '<div id="pool1" class="poll_main_cls">';
			$res = $res . '<div id="pool1_msg" class="poll_msg_cls" style=""></div>';
			foreach ( $pool_question as $question ) 
			{
				$poolq_id = $question->poolq_id;
				$poolq_question = stripslashes($question->poolq_question);
				$poolq_question = str_replace( "##QUESTION##" , $poolq_question, $pool1_que_css);
				$res = $res . "<div class='poll_question_cls'>". $poolq_question. "</div>";
				$sSql = "select poola_id, poola_answer, poola_vote from ". POOLONETABLEA ." where 1=1 and poolq_id = ". $poolq_id;
				$pool_answer = $wpdb->get_results($sSql);
				if ( ! empty($pool_answer) ) 
				{
					$res = $res . "<ul class='poll_ans_ul_cls'>";
					foreach ( $pool_answer as $answer ) 
					{
						$poola_id = $answer->poola_id;
						$poola_answer = stripslashes($answer->poola_answer);
						$poola_vote = $answer->poola_vote;
						$poola_answer = str_replace( "##ANSWER##" , $poola_answer, $pool1_ans_css);
						$poola_answer = str_replace( "##RES##" , $poola_vote, $poola_answer);
						$res = $res . "<li class='poll_ans_li_cls'>" . $poola_answer. "</li>";
					}
					$res = $res . "</ul>";
				}
			}
			$res = $res . '</div>';
			$poll = $res;
		}
		else
		{
			foreach ( $pool_question as $question ) 
			{
				$poll = $poll . '<div id="pool1" class="poll_main_cls">';
				$poll = $poll . '<div id="pool1_msg" class="poll_msg_cls"></div>';
				$poolq_id = $question->poolq_id;
				$poolq_question = stripslashes($question->poolq_question);
				$poolq_question = str_replace( "##QUESTION##" , $poolq_question, $pool1_que_css);
				$poll = $poll . "<div class='poll_question_cls'>". $poolq_question. "</div>";
				$sSql = "select poola_id, poola_answer from ". POOLONETABLEA ." where 1=1 and poolq_id = ". $poolq_id;
				$pool_answer = $wpdb->get_results($sSql);
				if ( ! empty($pool_answer) ) 
				{
					$poll = $poll . "<ul class='poll_ans_ul_cls'>";
					foreach ( $pool_answer as $answer ) 
					{
						$poola_id = $answer->poola_id;
						$poola_answer = stripslashes($answer->poola_answer);
						$poola_answer = "<input type ='Radio' onClick='SetPool(".$poola_id.")' name = 'poolanswer' id = 'poolanswer' value= '".$poola_id."'> ".$poola_answer."<br />";
						$poola_answer = str_replace( "##ANSWER##" , $poola_answer, $pool1_ans_css);
						$poll = $poll . "<li class='poll_ans_li_cls'>" . $poola_answer. "</li>";
					}
					$poll = $poll . "</ul>";
				}
				$poll = $poll.'<input name="pool1_ans" id="pool1_ans" type="hidden" value="" />';
				$url = '"' . home_url() . '"';
				$pool_button = "<input class='pool1_btn_cls' name='pool1_btn' id='pool1_btn' onClick='return PollOne_Submit(".$url.")' value='Submit' type='button'>";
				$pool_button = str_replace( "##BUTTON##" , $pool_button, $pool1_btn_css);
				$poll = $poll.'<div class="pool1_btn_cls">';
				$poll = $poll. $pool_button;
				$poll = $poll.'</div>';
				$poll = $poll.'</div>';			
			}
		}
	}
	else
	{
		$poll = $poll . '<div style="text-align:center;">'.__('No poll available', 'poll-one').'</div>';
	}
	
	return $poll;
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
		$sSql = $sSql . ") ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
		$wpdb->query($sSql);
		
		$sSql = "";
		$IsSql = "INSERT INTO `". POOLONETABLEQ . "` (`poolq_question`, `poolq_start`, `poolq_end`)"; 
		$sSql = $IsSql . " VALUES ('Do you like this plugin?', '2012-01-01 00:00:00', '2015-01-01 00:00:00');";
		$wpdb->query($sSql);
		
		$qid = $wpdb->insert_id;
		
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
		$sSql = $sSql . ") ENGINE=MyISAM  DEFAULT CHARSET=utf8;";
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
	add_option('pool1_que_css', "##QUESTION##");
	add_option('pool1_ans_css', "##ANSWER##");
	add_option('pool1_btn_css', "##BUTTON##");
	add_option('pool1_que_css_res', "##QUESTION##");
	add_option('pool1_ans_css_res', "##ANSWER## (##RES##)");
	
	add_option('pool1_que_css_page', "##QUESTION##");
	add_option('pool1_ans_css_page', "##ANSWER##");
	add_option('pool1_btn_css_page', "##BUTTON##");
	add_option('pool1_que_css_res_page', "##QUESTION##");
	add_option('pool1_ans_css_res_page', "##ANSWER## (##RES##)");
}

function pool1_admin_options() 
{
	global $wpdb;
	$current_page = isset($_GET['ac']) ? $_GET['ac'] : '';
	switch($current_page)
	{
		case 'edit':
			include('pages/content-management-edit.php');
			break;
		case 'add':
			include('pages/content-management-add.php');
			break;
		case 'ans':
			include('pages/content-management-ans.php');
			break;
		default:
			include('pages/content-management-show.php');
			break;
	}
}

function pool1_page_setting() 
{
	global $wpdb;
	include_once("setting.php");
}

function pool1_deactivation() 
{
	// No action required.
}

function pool1_add_javascript_files() 
{
	if (!is_admin())
	{
		wp_enqueue_script( 'pool1_widget', pool1_plugin_url('js/widget.js'));
		//wp_enqueue_script( 'pool1_page', pool1_plugin_url('js/page.js'));
		wp_enqueue_style( 'pool1_css', pool1_plugin_url('css/style.css'));
	}	
}

function pool1_add_to_menu() 
{
	if (is_admin()) 
	{
		add_menu_page( __( 'Poll one', 'poll-one' ), __( 'Poll one', 'poll-one' ), 'administrator', 'PollOne', 'pool1_admin_options' );
		add_submenu_page( 'PollOne', __( 'Poll One questions', 'poll-one' ), __( 'Poll Questions', 'poll-one' ),'administrator', 'PollOne', 'pool1_admin_options' );
		add_submenu_page( 'PollOne', __( 'Poll One page setting', 'poll-one' ), __( 'Page Settings', 'poll-one' ),'administrator', 'PollOneSetting', 'pool1_page_setting' );
	}
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
	
	if (isset($_POST['pool1_submit'])) 
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
	
	echo '<p>'.__('Title:', 'poll-one').'<br><input  style="width: 500px;" type="text" value="';
	echo $pool1_title . '" name="pool1_title" id="pool1_title" /></p>';
	
	echo '<p>'.__('Question Style:', 'poll-one').'<br><input  style="width: 500px;" type="text" value="';
	echo $pool1_que_css . '" name="pool1_que_css" id="pool1_que_css" /><br>Keyword: ##QUESTION##</p>';
	
	echo '<p>'.__('Answer Style:', 'poll-one').'<br><input  style="width: 500px;" type="text" value="';
	echo $pool1_ans_css . '" name="pool1_ans_css" id="pool1_ans_css" /><br>Keyword: ##ANSWER##</p>';
	
	echo '<p>'.__('Button Style:', 'poll-one').'<br><input  style="width: 500px;" type="text" value="';
	echo $pool1_btn_css . '" name="pool1_btn_css" id="pool1_btn_css" /><br>Keyword: ##BUTTON##</p>';
	
	echo '<p>'.__('Question Style [result box]:', 'poll-one').'<br><input  style="width: 500px;" type="text" value="';
	echo $pool1_que_css_res . '" name="pool1_que_css_res" id="pool1_que_css_res" /><br>Keyword: ##QUESTION##</p>';
	
	echo '<p>'.__('Answer Style [result box]:', 'poll-one').'<br><input  style="width: 500px;" type="text" value="';
	echo $pool1_ans_css_res . '" name="pool1_ans_css_res" id="pool1_ans_css_res" /><br>Keyword: ##ANSWER##, ##RES##</p>';
	
	?>
	<p class="description">
		<?php _e('Check official website for more information', 'poll-one'); ?>
		<a target="_blank" href="<?php echo WP_po1lone_FAV; ?>"><?php _e('click here', 'poll-one'); ?></a>
	</p>
	<?php
	
	echo '<input type="hidden" id="pool1_submit" name="pool1_submit" value="1" />';
}

function pool1_init()
{
	if(function_exists('wp_register_sidebar_widget')) 
	{
		wp_register_sidebar_widget('Poll one wp plugin', __('Poll one wp plugin', 'poll-one'), 'pool1_widget');
	}
	
	if(function_exists('wp_register_widget_control')) 
	{
		wp_register_widget_control('Poll one wp plugin', array( __('Poll one wp plugin', 'poll-one'), 'widgets'), 'pool1_control', 'width=550');
	} 
}

function pool1_textdomain() 
{
	  load_plugin_textdomain( 'poll-one', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

function pool1_plugin_query_vars($vars) 
{
	$vars[] = 'pollone';
	return $vars;
}

function pool1_plugin_parse_request($qstring)
{
	if (array_key_exists('pollone', $qstring->query_vars)) 
	{
		$page = $qstring->query_vars['pollone'];
		switch($page)
		{
			case 'poll-submitted':	
				global $wpdb;
				$res = "";
				
				$homeurl = home_url();
				$samedomain = strpos($_SERVER['HTTP_REFERER'], $homeurl);
				if (($samedomain !== false) && $samedomain < 5) 
				{
					$pool1_que_css = get_option('pool1_que_css_res');
					$pool1_ans_css = get_option('pool1_ans_css_res');
					$ans = isset($_POST['ans']) ? $_POST['ans'] : '';
					$cSql = "select poolq_id, poolq_question from ". POOLONETABLEQ ." where poolq_id in (select poolq_id from ". POOLONETABLEA . " where poola_id =" . $ans . ") limit 0,1;";
					$pool_question = $wpdb->get_results($cSql);
					if ( !empty($pool_question) ) 
					{
						$sql = "update ". POOLONETABLEA . " set poola_vote = poola_vote+1 where poola_id = " . $ans;
						$wpdb->get_results($sql);
						
						$res = $res . '<div id="pool1" class="poll_main_cls">';
						$res = $res . '<div id="pool1_msg" class="poll_msg_cls" style=""></div>';
						foreach ( $pool_question as $question ) 
						{
							$poolq_id = $question->poolq_id;
							$poolq_question = $question->poolq_question;
							// setcookie
							setcookie("POLLONE-".$poolq_id, 1, time() + 30000000, COOKIEPATH);
							// setcookie
							$poolq_question = str_replace( "##QUESTION##" , $poolq_question, $pool1_que_css);
							$res = $res . "<div class='poll_question_cls'>". $poolq_question. "</div>";
							$sSql = "select poola_id, poola_answer, poola_vote from ". POOLONETABLEA ." where 1=1 and poolq_id = ". $poolq_id;
							$pool_answer = $wpdb->get_results($sSql);
							if ( ! empty($pool_answer) ) 
							{
								$res = $res . "<ul class='poll_ans_ul_cls'>";
								foreach ( $pool_answer as $answer ) 
								{
									$poola_id = $answer->poola_id;
									$poola_answer = stripslashes($answer->poola_answer);
									$poola_vote = $answer->poola_vote;
									$poola_answer = str_replace( "##ANSWER##" , $poola_answer, $pool1_ans_css);
									$poola_answer = str_replace( "##RES##" , $poola_vote, $poola_answer);
									$res = $res . "<li class='poll_ans_li_cls'>" . $poola_answer. "</li>";
								}
								$res = $res . "</ul>";
							}
						}
						$res = $res . '</div>';
						echo $res;
					}
					else
					{
						echo "exs";
					}
				}
				else
				{
					echo "there-was-problem";
				}				
				die();
				break;		
		}
	}
}

add_action('parse_request', 'pool1_plugin_parse_request');
add_filter('query_vars', 'pool1_plugin_query_vars');
add_action('plugins_loaded', 'pool1_textdomain');
add_action('admin_menu', 'pool1_add_to_menu');
add_action("plugins_loaded", "pool1_init");
register_activation_hook(__FILE__, 'pool1_install');
register_deactivation_hook(__FILE__, 'pool1_deactivation');
add_action('wp_enqueue_scripts', 'pool1_add_javascript_files');
?>