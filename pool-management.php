<!--
/**
 *     Poll one wp plugin
 *     Copyright (C) 2012  www.gopiplus.com
 *	   http://www.gopiplus.com/work/2012/03/19/pool-one-wp-wordpress-plugin/
 * 
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 * 
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 * 
 *     You should have received a copy of the GNU General Public License
 *     along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
-->

<div class="wrap">
  <?php
  	global $wpdb;
    $mainurl = get_option('siteurl')."/wp-admin/options-general.php?page=pool-one-wp-plugin/pool-one-wp-plugin.php";
    $DID=@$_GET["DID"];
    $AC=@$_GET["AC"];
    $submittext = "Insert Message";
	if($AC <> "DEL" and trim(@$_POST['pool1_qus']) <> "")
    {
			if(@$_POST['poolq_id'] == "" )
			{
					$sql = "insert into ". POOLONETABLEQ
					. " set `poolq_question` = '" . mysql_real_escape_string(trim(@$_POST['pool1_qus']))
					. "', `poolq_start` = '" . trim(@$_POST['pool1_startdate'])
					. "', `poolq_end` = '" . trim(@$_POST['pool1_enddate'])
					. "'";	
					$wpdb->get_results($sql);
					$qid = mysql_insert_id();
					$option = @$_POST['pool1_tot'];
					for($i = 1; $i <= $option; $i++)
					{
						$ans = 'pool1_ans'.$i;
						$poola_answer = mysql_real_escape_string(trim(@$_POST[$ans]));
						$sql = "insert into ". POOLONETABLEA . " set `poola_answer` = '" . $poola_answer . "', `poola_vote` = 0, `poolq_id` = " . @$qid;	
						$wpdb->get_results($sql);
					}
			}
			else
			{
					$sql = "update ". POOLONETABLEQ
					. " set `poolq_question` = '" . mysql_real_escape_string(trim(@$_POST['pool1_qus']))
					. "', `poolq_start` = '" . trim(@$_POST['pool1_startdate'])
					. "', `poolq_end` = '" . trim(@$_POST['pool1_enddate'])
					. "' where poolq_id=".$_POST['poolq_id'].";";
					$wpdb->get_results($sql);
					
					for($i = 1; $i <= 6; $i++)
					{
						$ansid = 'pool1_ans'.$i."_id";
						$ans = 'pool1_ans'.$i;
						if(@$_POST[$ansid] != "" ) 
						{
							$poola_answer = mysql_real_escape_string(trim(@$_POST[$ans]));
							$sql = "update ". POOLONETABLEA . " set `poola_answer` = '" . $poola_answer . "' where `poola_id` = " . @$_POST[$ansid];	
							$wpdb->get_results($sql);
						}
					}
					
			}
    }
    
    if($AC=="DEL" && $DID > 0)
    {
        $wpdb->get_results("delete from ".POOLONETABLEQ." where poolq_id=".$DID);
		$wpdb->get_results("delete from ".POOLONETABLEA." where poolq_id=".$DID);
    }
    
    if($DID<>"" and $AC <> "DEL")
    {
        $data = $wpdb->get_results("select poolq_id, poolq_question, poolq_start, poolq_end from ".POOLONETABLEQ." where poolq_id = $DID limit 1");
        if ( empty($data) ) 
        {
           echo "<div id='message' class='error'><p>No data available! use below form to create!</p></div>";
           return;
        }
        $data = $data[0];
        if ( !empty($data) ) $poolq_id_x = $data->poolq_id; 
		if ( !empty($data) ) $pool1_qus_x = htmlspecialchars(stripslashes($data->poolq_question)); 
        if ( !empty($data) ) $pool1_startdate_x = substr($data->poolq_start, 0, 10);
		if ( !empty($data) ) $pool1_enddate_x = substr($data->poolq_end, 0, 10);
		
		
		$data_ans = $wpdb->get_results("select poola_id, poola_answer, poola_vote from ".POOLONETABLEA." where poolq_id = $DID limit 6");
		if ( !empty($data_ans) ) 
        {
			$c = 1;
			foreach ( $data_ans as $ans )
			{
				if($c==1) {
					@$pool1_ans1_id = @$ans->poola_id;
					@$pool1_ans1_x = stripslashes(@$ans->poola_answer);
					@$pool1_vote1_x = "  Vote: " . stripslashes(@$ans->poola_vote);
				}
				elseif($c==2) {
					@$pool1_ans2_id = @$ans->poola_id;
					@$pool1_ans2_x = stripslashes(@$ans->poola_answer);
					@$pool1_vote2_x = "  Vote: " . stripslashes(@$ans->poola_vote);
				}
				elseif($c==3) {
					@$pool1_ans3_id = @$ans->poola_id;
					@$pool1_ans3_x = stripslashes(@$ans->poola_answer);
					@$pool1_vote3_x = "  Vote: " . stripslashes(@$ans->poola_vote);
				}
				elseif($c==4) {
					@$pool1_ans4_id = @$ans->poola_id;
					@$pool1_ans4_x = stripslashes(@$ans->poola_answer);
					@$pool1_vote4_x = "  Vote: " . stripslashes(@$ans->poola_vote);
				}
				elseif($c==5) {
					@$pool1_ans5_id = @$ans->poola_id;
					@$pool1_ans5_x = stripslashes(@$ans->poola_answer);
					@$pool1_vote5_x = "  Vote: " . stripslashes(@$ans->poola_vote);
				}
				elseif($c==6) {
					@$pool1_ans6_id = @$ans->poola_id;
					@$pool1_ans6_x = stripslashes(@$ans->poola_answer);
					@$pool1_vote6_x = "  Vote: " . stripslashes(@$ans->poola_vote);
				}				
				$c = $c + 1;
			}
		}
		
        $submittext = "Update Message";
    }
    ?>
  <h2>Poll one wp plugin</h2>
  <script language="JavaScript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/pool-one-wp-plugin/setting.js"></script>
  <form name="pool1_form" method="post" action="<?php echo @$mainurl; ?>" onsubmit="return pool1_submit()"  >
    <table width="100%">
      <tr>
        <td colspan="2" align="left" valign="middle">Enter the pool question:</td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle"><input name="pool1_qus" type="text" id="pool1_qus" value="<?php echo @$pool1_qus_x; ?>" size="150" maxlength="1024" /></td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle">Enter option 1:</td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle"><input name="pool1_ans1" type="text" id="pool1_ans1" value="<?php echo @$pool1_ans1_x; ?>" size="125" maxlength="1024" /><span style="color:#990000;font-weight:bold;"><?php echo @$pool1_vote1_x; ?></span></td>
      </tr>
	  <tr>
        <td colspan="2" align="left" valign="middle">Enter option 2:</td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle"><input name="pool1_ans2" type="text" id="pool1_ans2" value="<?php echo @$pool1_ans2_x; ?>" size="125" maxlength="1024" /><span style="color:#990000;font-weight:bold;"><?php echo @$pool1_vote2_x; ?></span></td>
      </tr>
	  <tr>
        <td colspan="2" align="left" valign="middle">Enter option 3:</td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle"><input name="pool1_ans3" type="text" id="pool1_ans3" value="<?php echo @$pool1_ans3_x; ?>" size="125" maxlength="1024" /><span style="color:#990000;font-weight:bold;"><?php echo @$pool1_vote3_x; ?></span></td>
      </tr>
	  <tr>
        <td colspan="2" align="left" valign="middle">Enter option 4:</td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle"><input name="pool1_ans4" type="text" id="pool1_ans4" value="<?php echo @$pool1_ans4_x; ?>" size="125" maxlength="1024" /><span style="color:#990000;font-weight:bold;"><?php echo @$pool1_vote4_x; ?></span></td>
      </tr>
	  <tr>
        <td colspan="2" align="left" valign="middle">Enter option 5:</td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle"><input name="pool1_ans5" type="text" id="pool1_ans5" value="<?php echo @$pool1_ans5_x; ?>" size="125" maxlength="1024" /><span style="color:#990000;font-weight:bold;"><?php echo @$pool1_vote5_x; ?></span></td>
      </tr>
	  <tr>
        <td colspan="2" align="left" valign="middle">Enter option 6:</td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle"><input name="pool1_ans6" type="text" id="pool1_ans6" value="<?php echo @$pool1_ans6_x; ?>" size="125" maxlength="1024" /><span style="color:#990000;font-weight:bold;"><?php echo @$pool1_vote6_x; ?></span></td>
      </tr>
	  <tr>
        <td colspan="2" align="left" valign="middle">Start date:</td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle"><input name="pool1_startdate" type="text" id="pool1_startdate" value="<?php echo @$pool1_startdate_x; ?>" size="20" maxlength="10" /> (YYYY-MM-DD)</td>
      </tr>
	  <tr>
        <td colspan="2" align="left" valign="middle">End Date:</td>
      </tr>
      <tr>
        <td colspan="2" align="left" valign="middle"><input name="pool1_enddate" type="text" id="pool1_enddate" value="<?php echo @$pool1_enddate_x; ?>" size="20" maxlength="10" /> (YYYY-MM-DD)</td>
      </tr>
      <tr>
        <td height="35" align="left" valign="bottom"><table width="100%">
            <tr>
              <td align="left"><input name="publish" lang="publish" class="button-primary" value="<?php echo @$submittext?>" type="submit" />
                <input name="publish" lang="publish" class="button-primary" onclick="pool1_redirect()" value="Cancel" type="button" />
				<input name="Help" lang="publish" class="button-primary" onclick="window.open('http://www.gopiplus.com/work/2012/03/19/pool-one-wp-wordpress-plugin/');" value="Help" type="button" />
              </td>
            </tr>
          </table></td>
      </tr>
      <input name="poolq_id" id="poolq_id" type="hidden" value="<?php echo @$poolq_id_x; ?>">
	  <input name="pool1_ans1_id" id="pool1_ans1_id" type="hidden" value="<?php echo @$pool1_ans1_id; ?>">
	  <input name="pool1_ans2_id" id="pool1_ans2_id" type="hidden" value="<?php echo @$pool1_ans2_id; ?>">
	  <input name="pool1_ans3_id" id="pool1_ans3_id" type="hidden" value="<?php echo @$pool1_ans3_id; ?>">
	  <input name="pool1_ans4_id" id="pool1_ans4_id" type="hidden" value="<?php echo @$pool1_ans4_id; ?>">
	  <input name="pool1_ans5_id" id="pool1_ans5_id" type="hidden" value="<?php echo @$pool1_ans5_id; ?>">
	  <input name="pool1_ans6_id" id="pool1_ans6_id" type="hidden" value="<?php echo @$pool1_ans6_id; ?>">
	  <input name="pool1_tot" id="pool1_tot" type="hidden" value="">
    </table>
  </form>
  <div class="tool-box">
    <?php
	$data = $wpdb->get_results("select poolq_id, poolq_question from " . POOLONETABLEQ . " order by poolq_end, poolq_id");
	if ( empty($data) ) 
	{ 
		echo "<div id='message' class='error'>No data available! use below form to create!</div>";
		return;
	}
	?>
    <form name="frm_pool1_display" method="post">
      <table width="100%" class="widefat" id="straymanage">
        <thead>
          <tr>
            <th width="20px" align="left" scope="col">ID
              </td>
            <th width="" align="left" scope="col">Question
              </td>
            <th width="130px" align="left" scope="col">Action
              </td>
          </tr>
        </thead>
        <?php 
        $i = 0;
        foreach ( $data as $data ) { 
        ?>
        <tbody>
          <tr class="<?php if ($i&1) { echo'alternate'; } else { echo ''; }?>">
            <td align="left" valign="middle"><?php echo(stripslashes($data->poolq_id)); ?></td>
            <td align="left" valign="middle"><?php echo(stripslashes($data->poolq_question)); ?></td>
            <td align="left" valign="middle">
			<a href="options-general.php?page=pool-one-wp-plugin/pool-one-wp-plugin.php&DID=<?php echo($data->poolq_id); ?>">Edit / View Vote</a> &nbsp; 
			<a onClick="javascript:pool1_delete('<?php echo($data->poolq_id); ?>')" href="javascript:void(0);">Delete</a> 
			</td>
          </tr>
        </tbody>
        <?php $i = $i+1; } ?>
      </table>
    </form>
  </div>
  <div><br />
<span>Note: Check official website for more information
<a target='_blank' href='http://www.gopiplus.com/work/2012/03/19/pool-one-wp-wordpress-plugin/'>Click here</a></span>
  </div>
</div>