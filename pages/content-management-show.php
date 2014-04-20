<?php if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); } ?>
<?php
// Form submitted, check the data
if (isset($_POST['frm_poolq_display']) && $_POST['frm_poolq_display'] == 'yes')
{
	$did = isset($_GET['did']) ? $_GET['did'] : '0';
	
	$poolq_success = '';
	$poolq_success_msg = FALSE;
	
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
		// Form submitted, check the action
		if (isset($_GET['ac']) && $_GET['ac'] == 'del' && isset($_GET['did']) && $_GET['did'] != '')
		{
			//	Just security thingy that wordpress offers us
			check_admin_referer('poolq_form_show');
			
			//	Delete selected record from the table
			$sSql = $wpdb->prepare("DELETE FROM `".POOLONETABLEQ."`
					WHERE `poolq_id` = %d
					LIMIT 1", $did);
			$wpdb->query($sSql);
			
			//	Delete selected record from the table
			$sSql = $wpdb->prepare("DELETE FROM `".POOLONETABLEA."`
					WHERE `poolq_id` = %d
					LIMIT 6", $did);
			$wpdb->query($sSql);
			
			//	Set success message
			$poolq_success_msg = TRUE;
			$poolq_success = __('Selected record was successfully deleted.', 'poll-one');
		}
	}
	
	if ($poolq_success_msg == TRUE)
	{
		?><div class="updated fade"><p><strong><?php echo $poolq_success; ?></strong></p></div><?php
	}
}
function LoadAnswer($qid)
{
	global $wpdb;
	$arrData = array();
	$data_ans = $wpdb->get_results("select poola_id, poola_answer, poola_vote from ".POOLONETABLEA." where poolq_id = $qid limit 6");
	if ( !empty($data_ans) ) 
	{
		$c = 0;
		foreach ( $data_ans as $ans )
		{
			$arrData[$c]["pool1_ans1_id"] = $ans->poola_id;
			$arrData[$c]["pool1_ans1_x"] = stripslashes($ans->poola_answer);
			$arrData[$c]["pool1_vote1_x"] = "  Vote: " . stripslashes($ans->poola_vote);		
			$c = $c + 1;
		}
	}
	return $arrData;
}
?>
<div class="wrap">
  <div id="icon-edit" class="icon32 icon32-posts-post"></div>
    <h2><?php _e('Poll one', 'poll-one'); ?><a class="add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=PollOne&ac=add"><?php _e('Add New', 'poll-one'); ?></a></h2>
    <div class="tool-box">
	<?php
		$sSql = "SELECT * FROM `".POOLONETABLEQ."` order by poolq_id desc";
		$myData = array();
		$myData = $wpdb->get_results($sSql, ARRAY_A);
		?>
		<script language="JavaScript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/pool-one-wp-plugin/pages/setting.js"></script>
		<form name="frm_poolq_display" method="post">
      <table width="100%" class="widefat" id="straymanage">
        <thead>
          <tr>
            <th class="check-column" scope="col"><input type="checkbox" name="poolq_group_item[]" /></th>
			<th scope="col"><?php _e('Id', 'poll-one'); ?></th>
			<th scope="col"><?php _e('Poll questions', 'poll-one'); ?></th>
			<th scope="col"><?php _e('Start date', 'poll-one'); ?></th>
			<th scope="col"><?php _e('End date', 'poll-one'); ?></th>
			<th scope="col"><?php _e('Action', 'poll-one'); ?></th>
          </tr>
        </thead>
		<tfoot>
          <tr>
            <th class="check-column" scope="col"><input type="checkbox" name="poolq_group_item[]" /></th>
			<th scope="col"><?php _e('Id', 'poll-one'); ?></th>
			<th scope="col"><?php _e('Poll questions', 'poll-one'); ?></th>
			<th scope="col"><?php _e('Start date', 'poll-one'); ?></th>
			<th scope="col"><?php _e('End date', 'poll-one'); ?></th>
			<th scope="col"><?php _e('Action', 'poll-one'); ?></th>
          </tr>
        </tfoot>
		<tbody>
			<?php 
			$i = 0;
			if(count($myData) > 0 )
			{
				foreach ($myData as $data)
				{
					?>
					<tr class="<?php if ($i&1) { echo'alternate'; } else { echo ''; }?>">
						<td align="left"><input type="checkbox" value="<?php echo $data['poolq_id']; ?>" name="poolq_group_item[]"></td>
						<td><?php echo $data['poolq_id']; ?></td>
						<td><?php echo stripslashes($data['poolq_question']); ?>
						<div style="padding-top:10px;">
						<?php
						$arrData = array();
						$arrData = LoadAnswer($data['poolq_id']);
						if(count($arrData) > 0)
						{
							foreach ($arrData as $arrData)
							{
								echo $arrData["pool1_ans1_x"] ."  <span style='color:#009933;font-weight:bold;'>". $arrData["pool1_vote1_x"] ."</span><br>";
							}
						}
						?>
						</div>
						</td>
						<td><?php echo substr($data['poolq_start'],0,10); ?></td>
						<td><?php echo substr($data['poolq_end'],0,10); ?></td>
						<td>
						<div class="row-actions">
							<span class="edit"><a title="Edit Question" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=PollOne&amp;ac=edit&amp;did=<?php echo $data['poolq_id']; ?>"><?php _e('Edit Question', 'poll-one'); ?></a> | </span>
							<span class="edit"><a title="Edit Answer" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=PollOne&amp;ac=ans&amp;did=<?php echo $data['poolq_id']; ?>"><?php _e('Edit Answer', 'poll-one'); ?></a> | </span>
							<span class="trash"><a title="Delete" onClick="javascript:poolq_delete('<?php echo $data['poolq_id']; ?>')" href="javascript:void(0);"><?php _e('Delete', 'poll-one'); ?></a></span> 
						</div>
						</td>
					</tr>
					<?php 
					$i = $i+1; 
				} 	
			}
			else
			{
				?><tr><td colspan="6" align="center"><?php _e('No records available.', 'poll-one'); ?></td></tr><?php 
			}
			?>
		</tbody>
        </table>
		<?php wp_nonce_field('poolq_form_show'); ?>
		<input type="hidden" name="frm_poolq_display" value="yes"/>
      </form>	
	  <div class="tablenav">
	  <h2>
	  <a class="button add-new-h2" href="<?php echo get_option('siteurl'); ?>/wp-admin/admin.php?page=PollOne&amp;ac=add"><?php _e('Add New', 'poll-one'); ?></a>
	  <a class="button add-new-h2" target="_blank" href="<?php echo WP_po1lone_FAV; ?>"><?php _e('Help', 'poll-one'); ?></a>
	  </h2>
	  </div>
	  <div style="height:5px"></div>
	<h3><?php _e('Plugin configuration option', 'poll-one'); ?></h3>
	<ol>
		<li><?php _e('Add directly in to the theme using PHP code.', 'poll-one'); ?></li>
		<li><?php _e('Drag and drop the widget to your sidebar.', 'poll-one'); ?></li>
	</ol>
	<p class="description">
		<?php _e('Check official website for more information', 'poll-one'); ?>
		<a target="_blank" href="<?php echo WP_po1lone_FAV; ?>"><?php _e('click here', 'poll-one'); ?></a>
	</p>
	</div>
</div>