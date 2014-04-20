<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<div class="wrap">
  <div class="form-wrap">
    <div id="icon-edit" class="icon32 icon32-posts-post"></div>
    <h2><?php _e('Poll One', 'poll-one'); ?></h2>
	<?php
	$pool1_que_css_page = htmlspecialchars(get_option('pool1_que_css_page'));
	$pool1_ans_css_page = htmlspecialchars(get_option('pool1_ans_css_page'));
	$pool1_btn_css_page = htmlspecialchars(get_option('pool1_btn_css_page'));
	$pool1_que_css_res_page = htmlspecialchars(get_option('pool1_que_css_res_page'));
	$pool1_ans_css_res_page = htmlspecialchars(get_option('pool1_ans_css_res_page'));
	if (isset($_POST['pool1_form_submit']) && $_POST['pool1_form_submit'] == 'yes')
	{
		check_admin_referer('po1lone_form_setting');
		$pool1_que_css_page = stripslashes($_POST['pool1_que_css_page']);
		$pool1_ans_css_page = stripslashes($_POST['pool1_ans_css_page']);
		$pool1_btn_css_page = stripslashes($_POST['pool1_btn_css_page']);
		$pool1_que_css_res_page = stripslashes($_POST['pool1_que_css_res_page']);
		$pool1_ans_css_res_page = stripslashes($_POST['pool1_ans_css_res_page']);
		
		update_option('pool1_que_css_page', $pool1_que_css_page );
		update_option('pool1_ans_css_page', $pool1_ans_css_page );
		update_option('pool1_btn_css_page', $pool1_btn_css_page );
		update_option('pool1_que_css_res_page', $pool1_que_css_res_page );
		update_option('pool1_ans_css_res_page', $pool1_ans_css_res_page );
		
		$pool1_que_css_page = htmlspecialchars($pool1_que_css_page);
		$pool1_ans_css_page = htmlspecialchars($pool1_ans_css_page);
		$pool1_btn_css_page = htmlspecialchars($pool1_btn_css_page);
		$pool1_que_css_res_page = htmlspecialchars($pool1_que_css_res_page);
		$pool1_ans_css_res_page = htmlspecialchars($pool1_ans_css_res_page);
		
		?>
		<div class="updated fade">
			<p><strong><?php _e('Details successfully updated.', 'poll-one'); ?></strong></p>
		</div>
		<?php
	}
	?>
	<form name="vsrp_form" method="post" action="">
		<h3><?php _e('Page setting (Applicable only for short code)', 'poll-one'); ?></h3>
		
		<label for="tag-width"><?php _e('Question CSS', 'poll-one'); ?></label>
		<input name="pool1_que_css_page" type="text" value="<?php echo $pool1_que_css_page; ?>"  id="pool1_que_css_page" size="100">
		<p><?php _e('Keyword:', 'poll-one'); ?> ##QUESTION##</p>
		
		<label for="tag-width"><?php _e('Answer CSS', 'poll-one'); ?></label>
		<input name="pool1_ans_css_page" type="text" value="<?php echo $pool1_ans_css_page; ?>"  id="pool1_ans_css_page" size="100">
		<p><?php _e('Keyword:', 'poll-one'); ?> ##ANSWER##</p>
		
		<label for="tag-width"><?php _e('Button CSS', 'poll-one'); ?></label>
		<input name="pool1_btn_css_page" type="text" value="<?php echo $pool1_btn_css_page; ?>"  id="pool1_btn_css_page" size="100">
		<p><?php _e('Keyword:', 'poll-one'); ?> ##BUTTON##</p>
		
		<label for="tag-width"><?php _e('Question CSS result box', 'poll-one'); ?></label>
		<input name="pool1_que_css_res_page" type="text" value="<?php echo $pool1_que_css_res_page; ?>"  id="pool1_que_css_res_page" size="100">
		<p><?php _e('Keyword:', 'poll-one'); ?> ##QUESTION##</p>
		
		<label for="tag-width"><?php _e('Answer CSS result box', 'poll-one'); ?></label>
		<input name="pool1_ans_css_res_page" type="text" value="<?php echo $pool1_ans_css_res_page; ?>"  id="pool1_ans_css_res_page" size="100">
		<p><?php _e('Keyword:', 'poll-one'); ?> ##ANSWER##, ##RES##</p>
		
		<div style="padding-top:10px;padding-bottom:10px;">
			<input name="po1lone_submit" id="po1lone_submit" class="button" value="<?php _e('Submit', 'poll-one'); ?>" type="submit" />&nbsp;
			<a class="button" target="_blank" href="http://www.gopiplus.com/work/2012/03/19/pool-one-wp-wordpress-plugin/"><?php _e('Help', 'poll-one'); ?></a>
		</div>
		<input type="hidden" name="pool1_form_submit" value="yes"/>
		<?php wp_nonce_field('po1lone_form_setting'); ?>
    </form>
  </div>
<p class="description">
	<?php _e('Check official website for more information', 'poll-one'); ?>
	<a target="_blank" href="<?php echo WP_po1lone_FAV; ?>"><?php _e('click here', 'poll-one'); ?></a>
</p>
</div>