<div class="wrap">
<?php
$poolq_errors = array();
$poolq_success = '';
$poolq_error_found = FALSE;

// Preset the form fields
$form = array(
	'poolq_question' => '',
	'poolq_start' => '',
	'poolq_end' => '',
	'pool1_ans1' => '',
	'pool1_ans2' => '',
	'pool1_ans3' => '',
	'pool1_ans4' => '',
	'pool1_ans5' => '',
	'pool1_ans6' => ''
);

// Form submitted, check the data
if (isset($_POST['poolq_form_submit']) && $_POST['poolq_form_submit'] == 'yes')
{
	//	Just security thingy that wordpress offers us
	check_admin_referer('poolq_form_add');
	
	$form['pool1_qus'] = isset($_POST['pool1_qus']) ? $_POST['pool1_qus'] : '';
	if ($form['pool1_qus'] == '')
	{
		$poolq_errors[] = __('Please enter the poll question.', WP_po1lone_UNIQUE_NAME);
		$poolq_error_found = TRUE;
	}
	$form['pool1_startdate'] = isset($_POST['pool1_startdate']) ? $_POST['pool1_startdate'] : '';
	if ($form['pool1_startdate'] == '')
	{
		$poolq_errors[] = __('Please enter poll start date.', WP_po1lone_UNIQUE_NAME);
		$poolq_error_found = TRUE;
	}
	$form['pool1_enddate'] = isset($_POST['pool1_enddate']) ? $_POST['pool1_enddate'] : '';
	if ($form['pool1_enddate'] == '')
	{
		$poolq_errors[] = __('Please enter poll end date.', WP_po1lone_UNIQUE_NAME);
		$poolq_error_found = TRUE;
	}

	$form['pool1_ans1'] = isset($_POST['pool1_ans1']) ? $_POST['pool1_ans1'] : '';
	if ($form['pool1_ans1'] == '')
	{
		$poolq_errors[] = __('Please enter poll option 1.', WP_po1lone_UNIQUE_NAME);
		$poolq_error_found = TRUE;
	}
	
	$form['pool1_ans2'] = isset($_POST['pool1_ans2']) ? $_POST['pool1_ans2'] : '';
	if ($form['pool1_ans2'] == '')
	{
		$poolq_errors[] = __('Please enter poll option 2.', WP_po1lone_UNIQUE_NAME);
		$poolq_error_found = TRUE;
	}
	
	$form['pool1_ans3'] = isset($_POST['pool1_ans3']) ? $_POST['pool1_ans3'] : '';
	$form['pool1_ans4'] = isset($_POST['pool1_ans4']) ? $_POST['pool1_ans4'] : '';
	$form['pool1_ans5'] = isset($_POST['pool1_ans5']) ? $_POST['pool1_ans5'] : '';
	$form['pool1_ans6'] = isset($_POST['pool1_ans6']) ? $_POST['pool1_ans6'] : '';

	//	No errors found, we can add this Group to the table
	if ($poolq_error_found == FALSE)
	{
		$sql = $wpdb->prepare(
			"INSERT INTO `".POOLONETABLEQ."`
			(`poolq_question`, `poolq_start`, `poolq_end`)
			VALUES(%s, %s, %s)",
			array($form['pool1_qus'], $form['pool1_startdate'], $form['pool1_enddate'])
		);
		$wpdb->query($sql);
		
		$qid = mysql_insert_id();
		if($form['pool1_ans1'] <> "")
		{
			LoadAnswer($form['pool1_ans1'], $qid);
		}
		if($form['pool1_ans2'] <> "")
		{
			LoadAnswer($form['pool1_ans2'], $qid);
		}
		if($form['pool1_ans3'] <> "")
		{
			LoadAnswer($form['pool1_ans3'], $qid);
		}
		if($form['pool1_ans4'] <> "")
		{
			LoadAnswer($form['pool1_ans4'], $qid);
		}
		if($form['pool1_ans5'] <> "")
		{
			LoadAnswer($form['pool1_ans5'], $qid);
		}
		if($form['pool1_ans6'] <> "")
		{
			LoadAnswer($form['pool1_ans6'], $qid);
		}
		
		$poolq_success = __('New details was successfully added.', WP_po1lone_UNIQUE_NAME);
		
		// Reset the form fields
		$form = array(
			'poolq_question' => '',
			'poolq_start' => '',
			'poolq_end' => '',
			'pool1_ans1' => '',
			'pool1_ans2' => '',
			'pool1_ans3' => '',
			'pool1_ans4' => '',
			'pool1_ans5' => '',
			'pool1_ans6' => ''
		);
	}
}

function LoadAnswer($pool1_ans1, $qid)
{
	global $wpdb;
	$sql = $wpdb->prepare(
		"INSERT INTO `".POOLONETABLEA."`
		(`poola_answer`, `poola_vote`, `poolq_id`)
		VALUES(%s, %s, %s)",
		array($pool1_ans1, 0, $qid)
	);
	$wpdb->query($sql);
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
	<form name="pool1_form" method="post" action="#" onsubmit="return pool1_submit()"  >
      <h3>Add details</h3>
      	
		<label for="tag-a">Enter the poll question</label>
		<input name="pool1_qus" type="text" id="pool1_qus" value="" size="120" />
		<p>Please enter your poll question.</p>
		
		<label for="tag-a">Enter option 1</label>
		<input name="pool1_ans1" type="text" id="pool1_ans1" value="" size="50"  />
		<p>Please enter your 1st poll option.</p>
		
		<label for="tag-a">Enter option 2</label>
		<input name="pool1_ans2" type="text" id="pool1_ans2" value="" size="50"  />
		<p>Please enter your 2nd poll option.</p>
		
		<label for="tag-a">Enter option 3</label>
		<input name="pool1_ans3" type="text" id="pool1_ans3" value="" size="50"  />
		<p>Please enter your 3rd poll option.</p>
		
		<label for="tag-a">Enter option 4</label>
		<input name="pool1_ans4" type="text" id="pool1_ans4" value="" size="50"  />
		<p>Please enter your 4th poll option.</p>
		
		<label for="tag-a">Enter option 5</label>
		<input name="pool1_ans5" type="text" id="pool1_ans5" value="" size="50"  />
		<p>Please enter your 5th poll option.</p>
		
		<label for="tag-a">Enter option 6</label>
		<input name="pool1_ans6" type="text" id="pool1_ans6" value="" size="50"  />
		<p>Please enter your 6th poll option.</p>
		
		<label for="tag-a">Start date</label>
		<input name="pool1_startdate" type="text" id="pool1_startdate" value="2013-01-01" maxlength="10"  />
		<p>Please enter poll start date. (YYYY-MM-DD)</p>
		
		<label for="tag-a">End date</label>
		<input name="pool1_enddate" type="text" id="pool1_enddate" value="9999-12-31" maxlength="10"  />
		<p>Please enter poll end date. (YYYY-MM-DD)</p>
		<input name="pool1_tot" id="pool1_tot" type="hidden" value="">
		
      <input name="poolq_id" id="poolq_id" type="hidden" value="">
      <input type="hidden" name="poolq_form_submit" value="yes"/>
      <p class="submit">
        <input name="publish" lang="publish" class="button" value="Submit" type="submit" />&nbsp;
        <input name="publish" lang="publish" class="button" onclick="poolq_redirect()" value="Cancel" type="button" />&nbsp;
        <input name="Help" lang="publish" class="button" onclick="poolq_help()" value="Help" type="button" />
      </p>
	  <?php wp_nonce_field('poolq_form_add'); ?>
    </form>
</div>
<p class="description"><?php echo WP_po1lone_LINK; ?></p>
</div>