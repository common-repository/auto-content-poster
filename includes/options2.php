<?php if( isset($_GET['settings-updated']) ) {
	$advoptions = get_option('acp_cj_advance_settings');
	
?>
    <div id="message" class="updated">
        <p><strong><?php _e('Great Work ! All done.','auto-content-poster') ?></strong></p>
    </div>
<?php } ?>
<?php if( isset($_GET['post_now']) == true and isset($_GET['adid'])) {
	
	$adid = $_GET['adid'];
	
	if($adid == 'random'){
		acp_cjposter();
	
	 ?>
	 <div id="message" class="updated">
        <p><strong><?php _e('One post/link created successfully.','auto-content-poster') ?></strong></p>
    </div>
<?php }else{
	$adid = (int)$adid;
	global $wpdb;
	$resu = $wpdb->get_results('SELECT adname,adcat FROM bestcjdb WHERE adid='.$adid);
	acp_cjposter($adid);
	?>
	 <div id="message" class="updated">
        <p><strong><?php _e('One post/link created successfully for '.$resu[0]->adname,'auto-content-poster') ?></strong></p>
    </div>
<?php } } ?>
<?php if( isset($_GET['refresh']) == true ) {
	acp_cj_refreshDB();
	 ?>
	 <div id="message" class="updated">
        <p><strong><?php _e('Database refreshed successfully, New advertisers are added.','auto-content-poster') ?></strong></p>
    </div>
<?php } ?>
<div class="wrap">
	<h2><?php _e('Auto Content Poster - Commission Junction Advance Settings','auto-content-poster');?></h2>

	<form method="post" action="options.php" name="adv_form" onsubmit="return validate();">
		<?php settings_fields( 'acp_cj_advance_settings' ); ?>
		<?php $advoptions = get_option('acp_cj_advance_settings'); ?> 
		<table class="form-table">
			<tr valign="top"><th scope="row"><?php _e('How many post(product) you want to get for each advertiser','auto-content-poster');?> ?</th>
				<td><input type="number" name="acp_cj_advance_settings[post_record]" value="<?php if(!empty($advoptions['post_record']))echo $advoptions['post_record']; ?>" pattern="[0-9]{1}" oninvalid="setCustomValidity('Please enter numbers only, Maximum 9')" onchange="try{setCustomValidity('')}catch(e){}"/></td>
			</tr>
			<tr valign="top"><th scope="row"><?php _e('Choose option for category assignment: ','auto-content-poster');?></th>
       <td style="width: 50%;"> <input type="radio" name="acp_cj_advance_settings[category]" value="auto"<?php checked( 'auto' == $advoptions['category'] ); ?> onclick="hide2();"/><?php _e('Automatically post in category based on advertiser\'s category in CJ','auto-content-poster');?> </td>
<td style="width: 50%;"><input type="radio" name="acp_cj_advance_settings[category]" value="manual"<?php checked( 'manual' == $advoptions['category'] ); ?> onclick="show2();"/><?php _e('Enter Category name: ','auto-content-poster');?><input type="text" id="category_name" name="acp_cj_advance_settings[category_name]" value="<?php if(!empty($advoptions['category_name']))echo $advoptions['category_name'];?>" style="display: none;"/></td>
				
			</tr>
			<tr valign="top"><th scope="row"><?php _e('Choose an Interval: ','auto-content-poster');?></th>
       <td style="width: 100px;"> <input type="radio" name="acp_cj_advance_settings[interval]" value="hourly"<?php checked( 'hourly' == $advoptions['interval'] ); ?> onclick="hide();"/><?php _e('Per Hour','auto-content-poster');?> </td>
       <td style="width: 100px;"><input type="radio" name="acp_cj_advance_settings[interval]" value="twicedaily"<?php checked( 'twicedaily' == $advoptions['interval'] ); ?> onclick="hide();"/><?php _e('Twice Daily ','auto-content-poster');?></td>
       <td style="width: 100px;"><input type="radio" name="acp_cj_advance_settings[interval]" value="daily"<?php checked( 'daily' == $advoptions['interval'] ); ?> onclick="hide();"/><?php _e('Daily ','auto-content-poster');?></td>
       
       </tr>
       <tr valign="top"><th scope="row"><?php _e('Choose all/specific category\'s advertisers from CJ: ','auto-content-poster');?></th>
       <td style="width: 100px;"> <input type="radio" value="auto_cj" name="acp_cj_advance_settings[category_cj]" <?php checked( 'auto_cj' == $advoptions['category_cj'] ); ?> onclick="hide3();" disabled/><?php _e('Use all category of CJ ','auto-content-poster');?></td>
       <td style="width: 100px;"><input type="radio" value="manual_cj" name="acp_cj_advance_settings[category_cj]" <?php checked( 'manual_cj' == $advoptions['category_cj'] ); ?> onclick="show3();" disabled/><?php _e('Choose below:(number shows available advertisers in that category) ','auto-content-poster');?><select name="acp_cj_advance_settings[cselect]" id="cselect" style="display: none;" disabled>
<option value="" selected="selected">Choose a category</option><?php echo acp_cj_select();?></select><br><a href="http://www.autocontentposter.com/single-site/8-auto-content-poster-for-commission-junction-premium-plugin.html" style="color: #a20000; font-size: 10px;">Available in Premium Commission Junction Plugin, Buy it Here</a></td>
		</tr>
		<tr valign="top"><th scope="row"><?php _e('Auto-posting of all products from individual(single) advertiser: ','auto-content-poster');?></th>
		<td style="width: 100px;">
		<select name="acp_cj_advance_settings[adid3]" id="sing" disabled>
<option value=""<?php selected($advoptions['adid3']);?>>Select Advertiser</option>
<?php 
global $wpdb;
	$sql = "SELECT adid,adname FROM bestcjdb ORDER BY adname ASC";
	$retval =  $wpdb->get_results($sql,ARRAY_N);
	
	foreach($retval as $n){
	
		echo '<option value="'.$n[0].'"'.selected($advoptions['adid3'],$n[0]).'>'.$n[1].'</option>';
	
	}
?></select>	<br><a href="http://www.autocontentposter.com/single-site/8-auto-content-poster-for-commission-junction-premium-plugin.html" style="color: #a20000; font-size: 10px;">Available in Premium Commission Junction Plugin, Buy it Here</a>		
		</td>
		</tr>
		<tr valign="top"><th scope="row"><?php _e('Choose option Post Template : ','auto-content-poster');?></th>
       <td style="width: 50%;"> <input type="radio" name="acp_cj_advance_settings[templ]" value="default"<?php checked( 'default' == $advoptions['templ'] ); ?> onclick="hide4();" disabled/><?php _e('Default Template<br>[title][description][image][price][product_url]','auto-content-poster');?> </td>
<td style="width: 50%;"><input type="radio" name="acp_cj_advance_settings[templ]" value="cust"<?php checked( 'cust' == $advoptions['templ'] ); ?> onclick="show4();" disabled/><?php _e('Enter Custom Template : <br>Avalable shortcodes:<br>[product_url][description][image][price]<br>Use HTML for customisation','auto-content-poster');?><textarea id="cust_templ" name="acp_cj_advance_settings[cust_templ]" style="display: none;" disabled><?php if(!empty($advoptions['cust_templ']))echo $advoptions['cust_templ'];?></textarea><br><a href="http://www.autocontentposter.com/single-site/8-auto-content-poster-for-commission-junction-premium-plugin.html" style="color: #a20000; font-size: 10px;">Available in Premium Commission Junction Plugin, Buy it Here</a></td>
		</tr>
		<tr valign="top"><th scope="row"><?php _e('Enter Your e-mail address to receive transaction report of your CJ account','auto-content-poster');?></th>
		<td style="width: 100px;">
		<input type="email" name="acp_cj_advance_settings[email]" value="<?php if(!empty($advoptions['email']))echo $advoptions['email']; ?>" disabled/><br />
		<?php _e('Report will be send by your hosting provide to your email daily in HTML format. Current month\'s transactions will be included in report','auto-content-poster');?><br><a href="http://www.autocontentposter.com/single-site/8-auto-content-poster-for-commission-junction-premium-plugin.html" style="color: #a20000; font-size: 10px;">Available in Premium Commission Junction Plugin, Buy it Here</a>
			
		</td>
		</tr>
	</table>
		 <?php submit_button();?>
	</form><br />
	<form method="post" action="#" name="adv_form2" >
	<table class="form-table">
	<tr valign="top"><th scope="row"><?php _e('Post now instantly: ','auto-content-poster');?></th>
		<td style="width: 100px;">
		<?php _e('Choose random OR specific advertiser for Post ','auto-content-poster');?>
			<select name="acp_cj_advance_settings[adid]" id="adid">
<option value="random" selected="selected">Random Advertiser</option><?php echo acp_cj_alladvs();?></select>
			<a class="button-primary" href="javascript:if (document.getElementById('adid').value) window.open('options-general.php?page=PostSetting&post_now=true&adid='+document.getElementById('adid').value,'_self');">Post Now</a>
		</td>
		</tr>
		<tr valign="top"><th scope="row"><?php _e('Refresh advertiser\'s list in Database : ','auto-content-poster');?></th>
		<td style="width: 100px;">
			<a class="button-primary" href="options-general.php?page=PostSetting&refresh=true">Refresh Database</a>
			<br>Refreshing of database is depends on how frequently you joins new advertisers in CJ, Generally once a month is sufficient. Please note that plugin refreshs it automatically after all advertisers processed but you can refresh it here manually also. 
		</td>
		</tr>
	<tr valign="top"><th scope="row"><?php _e('Make featured post for selected advertisers: <br>(Hold Ctrl for multiple selection)','auto-content-poster');?></th>
		<td style="width: 100px;">
		<select name="adid2[]" id="stic" multiple="multiple" size="10" disabled>
<?php echo acp_cj_alladvs();?></select>
			<br><a href="http://www.autocontentposter.com/single-site/8-auto-content-poster-for-commission-junction-premium-plugin.html" style="color: #a20000; font-size: 10px;">Available in Premium Commission Junction Plugin, Buy it Here</a>
		</td>
		</tr>
		</table>
		 <input class="button-primary" type="submit" name="submit2" value="Make Sticky" disabled/>
	</form><br>
	<p style="font-size: 16px; color: #4db805">If you found this plugin usefull then please <a href="http://www.autocontentposter.com">check our other plugins here</a></p>
	<b style="font-size: 14px; color: #860000">Pleass Read : </b>
	<p>By default our plugin uses Wordpress's built in cron functions to schedule posts. While no additional setup is necessary. But it have a few disadvantages, namely it will only run when a visitor visits your site.</p>
				
	<p>>As an alternative you can set up an Unix Cron Job in your webhosts control panel (often cPanel) to create automatic posts. This will use less ressources on your server and be more reliable than the Wordpress solution. To set up a cron job you need to use the following command: wget -O /dev/null <?php
echo plugins_url('cron.php', __FILE__ );
?></p>
	
</div>