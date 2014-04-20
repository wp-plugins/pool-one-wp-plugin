<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<div class="wrap">
<?php
$did = isset($_GET['did']) ? $_GET['did'] : '0';

// First check if ID exist with requested ID
$sSql = $wpdb->prepare(
	"SELECT COUNT(*) AS `count` FROM ".POOLONETABLEQ."
	WHERE `poolq_id` = %d",
	array($did)
);
$result = '0';
$result = $wpdb->get_var($sSql);

if ($result != '1')
{
	?><div class="error fade"><p><strong><?php _e('Oops, selected details doesnt exist.', 'poll-one'); ?></strong></p></div><?php
}
else
{
	$poolq_errors = array();
	$poolq_success = '';
	$poolq_error_found = FALSE;
	
	$sSql = $wpdb->prepare("
		SELECT *
		FROM `".POOLONETABLEQ."`
		WHERE `poolq_id` = %d
		LIMIT 1
		",
		array($did)
	);
	$data = array();
	$data = $wpdb->get_row($sSql, ARRAY_A);
	
	// Preset the form fields
	$form = array(
		'poolq_question' => $data['poolq_question'],
		'poolq_start' => substr($data['poolq_start'],0,10),
		'poolq_end' => substr($data['poolq_end'],0,10)
	);
}
// Form submitted, check the data
if (isset($_POST['poolq_form_submit']) && $_POST['poolq_form_submit'] == 'yes')
{
	//	Just security thingy that wordpress offers us
	check_admin_referer('poolq_form_edit');
	
	$form['pool1_qus'] = isset($_POST['pool1_qus']) ? $_POST['pool1_qus'] : '';
	if ($form['pool1_qus'] == '')
	{
		$poolq_errors[] = __('Please enter the poll question.', 'poll-one');
		$poolq_error_found = TRUE;
	}
	$form['pool1_startdate'] = isset($_POST['pool1_startdate']) ? $_POST['pool1_startdate'] : '';
	if ($form['pool1_startdate'] == '')
	{
		$poolq_errors[] = __('Please enter poll start date.', 'poll-one');
		$poolq_error_found = TRUE;
	}
	$form['pool1_enddate'] = isset($_POST['pool1_enddate']) ? $_POST['pool1_enddate'] : '';
	if ($form['pool1_enddate'] == '')
	{
		$poolq_errors[] = __('Please enter poll end date.', 'poll-one');
		$poolq_error_found = TRUE;
	}

	//	No errors found, we can add this Group to the table
	if ($poolq_error_found == FALSE)
	{	
		$sSql = $wpdb->prepare(
				"UPDATE `".POOLONETABLEQ."`
				SET `poolq_question` = %s,
				`poolq_start` = %s,
				`poolq_end` = %s
				WHERE poolq_id = %d
				LIMIT 1",
				array($form['pool1_qus'], $form['pool1_startdate'], $form['pool1_enddate'], $did)
			);
		$wpdb->query($sSql);
		
		$poolq_success = __('Details was successfully updated.', 'poll-one');
	}
}

if ($poolq_error_found == TRUE && isset($poolq_errors[0]) == TRUE)
{
  ?>
  <div class="error fade">
    <p><strong><?php echo $poolq_errors[0]; ?></strong></p>
  </div>
  <?php
}
if ($poolq_error_found == FALSE && strlen($poolq_success) > 0)
{
  ?>
  <div class="updated fade">
    <p><strong><?php echo $poolq_success; ?> 
	<a href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=PollOne"><?php _e('Click here to view the details', 'poll-one'); ?></a></strong></p>
  </div>
  <?php
}
?>
<script language="JavaScript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/pool-one-wp-plugin/pages/setting.js"></script>
<div class="form-wrap">
	<div id="icon-edit" class="icon32 icon32-posts-post"></div>
	<h2><?php _e('Poll One', 'poll-one'); ?></h2>
	<form name="pool1_form" method="post" action="#" onsubmit="return pool1_submit()"  >
	<h3><?php _e('Update details', 'poll-one'); ?></h3>
	
	<label for="tag-a"><?php _e('Enter the poll question', 'poll-one'); ?></label>
	<input name="pool1_qus" type="text" id="pool1_qus" value="<?php echo stripslashes($form['poolq_question']); ?>" size="120" />
	<p><?php _e('Please enter your poll question.', 'poll-one'); ?></p>
	
	<label for="tag-a"><?php _e('Start date', 'poll-one'); ?></label>
	<input name="pool1_startdate" type="text" id="pool1_startdate" value="<?php echo $form['poolq_start']; ?>" maxlength="10"  />
	<p><?php _e('Please enter poll start date.', 'poll-one'); ?> (YYYY-MM-DD)</p>
	
	<label for="tag-a"><?php _e('End date', 'poll-one'); ?></label>
	<input name="pool1_enddate" type="text" id="pool1_enddate" value="<?php echo $form['poolq_end']; ?>" maxlength="10"  />
	<p><?php _e('Please enter poll end date.', 'poll-one'); ?> (YYYY-MM-DD)</p>	
	
	<input name="poolq_id" id="poolq_id" type="hidden" value="">
	<input type="hidden" name="poolq_form_submit" value="yes"/>
	<p class="submit">
		<input name="publish" lang="publish" class="button" value="<?php _e('Submit', 'poll-one'); ?>" type="submit" />&nbsp;
		<input name="publish" lang="publish" class="button" onclick="poolq_redirect()" value="<?php _e('Cancel', 'poll-one'); ?>" type="button" />&nbsp;
		<input name="Help" lang="publish" class="button" onclick="poolq_help()" value="<?php _e('Help', 'poll-one'); ?>" type="button" />
	</p>
	<?php wp_nonce_field('poolq_form_edit'); ?>
	</form>
</div>
<p class="description">
	<?php _e('Check official website for more information', 'poll-one'); ?>
	<a target="_blank" href="<?php echo WP_po1lone_FAV; ?>"><?php _e('click here', 'poll-one'); ?></a>
</p>
</div>