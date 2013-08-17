<div class="wrap">
<?php
$did = isset($_GET['did']) ? $_GET['did'] : '0';

// First check if ID exist with requested ID
$sSql = $wpdb->prepare(
	"SELECT COUNT(*) AS `count` FROM ".POOLONETABLEA."
	WHERE `poolq_id` = %d",
	array($did)
);
$result = '0';
$result = $wpdb->get_var($sSql);

if ($result == '0')
{
	?><div class="error fade"><p><strong>Oops, selected details doesn't exist.</strong></p></div><?php
}
else
{
	$poolq_errors = array();
	$poolq_success = '';
	$poolq_error_found = FALSE;
}
// Form submitted, check the data
if (isset($_POST['poolq_form_submit']) && $_POST['poolq_form_submit'] == 'yes')
{
	//	Just security thingy that wordpress offers us
	check_admin_referer('poolq_form_edit');
	
	$form['pool1_ans1_id'] = isset($_POST['pool1_ans1_id']) ? $_POST['pool1_ans1_id'] : '';
	if ($form['pool1_ans1_id'] == '')
	{
		$poolq_errors[] = __('Please enter the poll option 1.', poolq_UNIQUE_NAME);
		$poolq_error_found = TRUE;
	}
	$form['pool1_ans2_id'] = isset($_POST['pool1_ans2_id']) ? $_POST['pool1_ans2_id'] : '';
	if ($form['pool1_ans2_id'] == '')
	{
		$poolq_errors[] = __('Please enter the poll option 2.', poolq_UNIQUE_NAME);
		$poolq_error_found = TRUE;
	}

	$form['poolq_total'] = isset($_POST['poolq_total']) ? $_POST['poolq_total'] : '';

	//	No errors found, we can add this Group to the table
	if ($poolq_error_found == FALSE)
	{	
		$ansid = "pool1_ans1_id";
		$ans = "pool1_ans1";
		for($i = 1; $i <= intval($form['poolq_total']); $i++)
		{
			$ansid = 'pool1_ans'.$i."_id";
			$ans = 'pool1_ans'.$i;
			if($_POST[$ansid] != "" ) 
			{
				$newansid = $_POST[$ansid];
				$poola_answer = isset($_POST[$ans]) ? $_POST[$ans] : '';				
				$sSql = $wpdb->prepare(
					"UPDATE `".POOLONETABLEA."`
					SET `poola_answer` = %s
					WHERE poola_id = %d
					LIMIT 1",
					array($poola_answer, $newansid)
				);
				$wpdb->query($sSql);
				echo $sSql."<br>";
			}
		}
		
		$poolq_success = 'Details was successfully updated.';
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
    <p><strong><?php echo $poolq_success; ?> <a href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=PollOne">Click here</a> to view the details</strong></p>
  </div>
  <?php
}
?>
<script language="JavaScript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/pool-one-wp-plugin/pages/setting.js"></script>
<div class="form-wrap">
	<div id="icon-edit" class="icon32 icon32-posts-post"></div>
	<h2><?php echo WP_po1lone_TITLE; ?></h2>
	<form name="pool1_form" method="post" action="#"  >
	<h3>Update details</h3>
	
	<?php

	
	$sSql = $wpdb->prepare("
		SELECT *
		FROM `".POOLONETABLEA."`
		WHERE `poolq_id` = %d
		LIMIT 6
		",
		array($did)
	);
	$data = array();
	$form = $wpdb->get_results($sSql, ARRAY_A);
	if ( !empty($form) ) 
	{
		$optioncnt = 0;
		$hidden = 1;
		foreach ( $form as $frm )
		{
			?>
			<label for="tag-a">Enter option <?php echo $hidden; ?></label>
			<input name="pool1_ans<?php echo $hidden; ?>" type="text" id="pool1_ans<?php echo $hidden; ?>" value="<?php echo stripslashes($frm['poola_answer']); ?>" size="120" />
			<p>Please enter your poll option. <input name="pool1_ans<?php echo $hidden; ?>_id" id="pool1_ans<?php echo $hidden; ?>_id" type="hidden" value="<?php echo $frm['poola_id']; ?>"></p>
			<?php
			$optioncnt = $optioncnt + 1;
			$hidden = $hidden + 1;
		}
	}
	?>
	
	<input name="poolq_total" id="poolq_total" type="hidden" value="<?php echo $optioncnt; ?>">
	<input type="hidden" name="poolq_form_submit" value="yes"/>
	<p class="submit">
		<input name="publish" lang="publish" class="button" value="Submit" type="submit" />&nbsp;
		<input name="publish" lang="publish" class="button" onclick="poolq_redirect()" value="Cancel" type="button" />&nbsp;
		<input name="Help" lang="publish" class="button" onclick="poolq_help()" value="Help" type="button" />
	</p>
	<?php wp_nonce_field('poolq_form_edit'); ?>
	</form>
</div>
<p class="description"><?php echo WP_po1lone_LINK; ?></p>
</div>