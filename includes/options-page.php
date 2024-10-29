<?php if( isset($_GET['settings-updated']) ) { ?>
    <div id="message" class="updated">
        <p><strong><?php _e('Good Work ! Now please go to Settings->ACP Advance Settings','auto-content-poster') ?></strong></p>
    </div>
<?php } ?>
<div class="wrap">
	<h2><?php _e('Auto Content Poster -  Commission Junction Settings','auto-content-poster');?></h2>
	<form method="post" action="options.php">
		<?php settings_fields( 'acp_cj_settings' ); ?>
		<?php $options = get_option('acp_cj_settings'); ?> 
		<table class="form-table">
			<tr valign="top"><th scope="row"><?php _e('CJ Website ID(PID)','auto-content-poster');?></th>
				<td><input type="text" name="acp_cj_settings[cj_site_id]" value="<?php if(!empty($options['cj_site_id']))echo $options['cj_site_id']; ?>" /></td>
			</tr>
			<tr valign="top"><th scope="row"><?php _e('CJ API KEY','auto-content-poster');?></th>
				<td><input type="text" name="acp_cj_settings[acp_cj_key]" value="<?php if(!empty($options['acp_cj_key']))echo $options['acp_cj_key']; ?>" /></td>
			</tr>
			
			<tr valign="top"><th scope="row"><?php _e('Amazon AccessKey ID:','auto-content-poster');?></th>
				<td><input type="text" name="acp_cj_settings[acp_cj_amz_key]" value="<?php if(!empty($options['acp_cj_amz_key']))echo $options['acp_cj_amz_key']; ?>" disabled/><br><a href="http://www.autocontentposter.com/single-site/8-auto-content-poster-for-commission-junction-premium-plugin.html" style="color: #a20000; font-size: 10px;">Available in Premium Commission Junction Plugin, Buy it Here</a></td>
			</tr>
			
			<tr valign="top"><th scope="row"><?php _e('Amazon Private(Secret)key:','auto-content-poster');?></th>
				<td><input type="text" name="acp_cj_settings[acp_cj_amz_pkey]" value="<?php if(!empty($options['acp_cj_amz_pkey']))echo $options['acp_cj_amz_pkey']; ?>" disabled/><br><a href="http://www.autocontentposter.com/single-site/8-auto-content-poster-for-commission-junction-premium-plugin.html" style="color: #a20000; font-size: 10px;">Available in Premium Commission Junction Plugin, Buy it Here</a></td>
			</tr>
			<tr valign="top"><th scope="row"><?php _e('Amazon Associate Tag:','auto-content-poster');?></th>
				<td><input type="text" name="acp_cj_settings[acp_cj_amz_atag]" value="<?php if(!empty($options['acp_cj_amz_atag']))echo $options['acp_cj_amz_atag']; ?>" disabled/><br><a href="http://www.autocontentposter.com/single-site/8-auto-content-poster-for-commission-junction-premium-plugin.html" style="color: #a20000; font-size: 10px;">Available in Premium Commission Junction Plugin, Buy it Here</a></td>
			</tr>
			<tr valign="top"><th scope="row"><?php _e('Select Amazon Associate Region:','auto-content-poster');?></th>
				<td><select name="acp_cj_settings[rselect]" id="rselect" disabled>
				<option value="com" <?php selected('com' == $options['rselect']);?>>USA</option>
				<option value="ca" <?php selected('ca' == $options['rselect']);?>>Canada</option>
				<option value="co.uk" <?php selected('co.uk' == $options['rselect']);?>>UK</option>
				<option value="cn" <?php selected('cn' == $options['rselect']);?>>China</option>
				<option value="in" <?php selected('in' == $options['rselect']);?>>India</option>
				<option value="fr" <?php selected('fr' == $options['rselect']);?>>France</option>
				<option value="de" <?php selected('de' == $options['rselect']);?>>Germany</option>
				<option value="es" <?php selected('es' == $options['rselect']);?>>Spain</option>
				<option value="it" <?php selected('it' == $options['rselect']);?>>Italy</option>
				<option value="co.jp" <?php selected('co.jp' == $options['rselect']);?>>Japan</option>
				</select><br><a href="http://www.autocontentposter.com/single-site/8-auto-content-poster-for-commission-junction-premium-plugin.html" style="color: #a20000; font-size: 10px;">Available in Premium Commission Junction Plugin, Buy it Here</a>
				</td>
			</tr>
			<tr valign="top"><th scope="row"><?php _e('Ebay Affiliate ID(CampaignID in Ebay Partner Network):','auto-content-poster');?></th>
				<td><input type="text" name="acp_cj_settings[acp_cj_ebay_key]" value="<?php if(!empty($options['acp_cj_ebay_key']))echo $options['acp_cj_ebay_key']; ?>" disabled/><br><a href="http://www.autocontentposter.com/single-site/8-auto-content-poster-for-commission-junction-premium-plugin.html" style="color: #a20000; font-size: 10px;">Available in Premium Commission Junction Plugin, Buy it Here</a></td>
			</tr>	
			<tr valign="top"><th scope="row"><?php _e('Select Ebay Country(ebay-site):','auto-content-poster');?></th>
			<td>
				<select name="acp_cj_settings[eb_select]" id="acp_cj_eb_select" disabled>
					<option value="0" <?php if($options['eb_select']=="0"){_e('selected');}?>><?php _e("United States","auto-content-poster") ?></option>
					<option value="2" <?php if($options['eb_select']=="2"){_e('selected');}?>><?php _e("Canada","auto-content-poster") ?></option>
					<option value="3" <?php if($options['eb_select']=="3"){_e('selected');}?>><?php _e("United kingdom","auto-content-poster") ?></option>
					<option value="15" <?php if($options['eb_select']=="15"){_e('selected');}?>><?php _e("Australia","auto-content-poster") ?></option>
					<option value="16" <?php if($options['eb_select']=="16"){_e('selected');}?>><?php _e("Austria","auto-content-poster") ?></option>
					<option value="23" <?php if($options['eb_select']=="23"){_e('selected');}?>><?php _e("Belgium (French)","auto-content-poster") ?></option>
					<option value="71" <?php if($options['eb_select']=="71"){_e('selected');}?>><?php _e("France","auto-content-poster") ?></option>
					<option value="77" <?php if($options['eb_select']=="77"){_e('selected');}?>><?php _e("Germany","auto-content-poster") ?></option>
					<option value="100" <?php if($options['eb_select']=="100"){_e('selected');}?>><?php _e("eBay Motors","auto-content-poster") ?></option>
					<option value="101" <?php if($options['eb_select']=="101"){_e('selected');}?>><?php _e("Italy","auto-content-poster") ?></option>
					<option value="123" <?php if($options['eb_select']=="123"){_e('selected');}?>><?php _e("Belgium (Dutch)","auto-content-poster") ?></option>
					<option value="146" <?php if($options['eb_select']=="146"){_e('selected');}?>><?php _e("Netherlands","auto-content-poster") ?></option>
					<option value="186" <?php if($options['eb_select']=="186"){_e('selected');}?>><?php _e("Spain","auto-content-poster") ?></option>
					<option value="193" <?php if($options['eb_select']=="193"){_e('selected');}?>><?php _e("Switzerland","auto-content-poster") ?></option>
					<option value="196" <?php if($options['eb_select']=="196"){_e('selected');}?>><?php _e("Taiwan","auto-content-poster") ?></option>
					<option value="223" <?php if($options['eb_select']=="223"){_e('selected');}?>><?php _e("China","auto-content-poster") ?></option>
					<option value="203" <?php if($options['eb_select']=='203') {_e('selected');}?>><?php _e("India","auto-content-poster") ?></option>
					<option value="205" <?php if($options['eb_select']=='205') {_e('selected');}?>><?php _e("Ireland","auto-content-poster") ?></option>
				</select><br><a href="http://www.autocontentposter.com/single-site/8-auto-content-poster-for-commission-junction-premium-plugin.html" style="color: #a20000; font-size: 10px;">Available in Premium Commission Junction Plugin, Buy it Here</a>
			</td>
			</tr>
						
		</table>
		 <?php submit_button();?>
	</form>
<?php

?>	
	<p style="font-size: 16px; color: #4db805">You can find commission junction api key <a href="https://www.api.cj.com">Here</a></p>
	<p>If you have a any problem in using our plugin or if you want to buy our premium plugins then please <a href="http://www.autocontentposter.com">Visit plugin site</a></p>
</div>