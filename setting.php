<div class="wrap">
  <div class="form-wrap">
    <div id="icon-edit" class="icon32 icon32-posts-post"></div>
    <h2><?php echo WP_po1lone_TITLE; ?></h2>
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
			<p><strong>Details successfully updated.</strong></p>
		</div>
		<?php
	}
	?>
	<form name="vsrp_form" method="post" action="">
		<h3>Page setting (Applicable only for short code)</h3>
		
		<label for="tag-width">Question CSS</label>
		<input name="pool1_que_css_page" type="text" value="<?php echo $pool1_que_css_page; ?>"  id="pool1_que_css_page" size="150">
		<p>Keyword: ##QUESTION##</p>
		
		<label for="tag-width">Answer CSS</label>
		<input name="pool1_ans_css_page" type="text" value="<?php echo $pool1_ans_css_page; ?>"  id="pool1_ans_css_page" size="150">
		<p>Keyword: ##ANSWER##</p>
		
		<label for="tag-width">Button CSS</label>
		<input name="pool1_btn_css_page" type="text" value="<?php echo $pool1_btn_css_page; ?>"  id="pool1_btn_css_page" size="150">
		<p>Keyword: ##BUTTON##</p>
		
		<label for="tag-width">Question CSS result box</label>
		<input name="pool1_que_css_res_page" type="text" value="<?php echo $pool1_que_css_res_page; ?>"  id="pool1_que_css_res_page" size="150">
		<p>Keyword: ##QUESTION##</p>
		
		<label for="tag-width">Answer CSS result box</label>
		<input name="pool1_ans_css_res_page" type="text" value="<?php echo $pool1_ans_css_res_page; ?>"  id="pool1_ans_css_res_page" size="150">
		<p>Keyword: ##ANSWER##, ##RES##</p>
		
		<div style="padding-top:10px;padding-bottom:10px;">
			<input name="po1lone_submit" id="po1lone_submit" class="button" value="Submit" type="submit" />&nbsp;
			<a class="button" target="_blank" href="http://www.gopiplus.com/work/2012/03/19/pool-one-wp-wordpress-plugin/">Help</a>
		</div>
		<input type="hidden" name="pool1_form_submit" value="yes"/>
		<?php wp_nonce_field('po1lone_form_setting'); ?>
    </form>
  </div>
  <p class="description"><?php echo WP_po1lone_LINK; ?></p>
</div>