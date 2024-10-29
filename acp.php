<?php
/*
Plugin Name: Auto Content Poster for Commission Junction
Text Domain: auto-content-poster
Plugin URI: http://www.autocontentposter.com
Description: Allows users to automatically post products/link from commission junction API to WordPress.
Version: 1.9.4
Author: Bhavin Toliya
Author URI: http://www.autocontentposter.com
License: GPL v2.
*/
ini_set('display_errors', 0 ); 
set_time_limit(0);
class acp_cj_Wordpress {

	//Install default data
	public function activate() {
		$options = array( 'advertiser_relationship' => 'joined' ,
		 'cache_duration' => '3600');
		 
    if ( ! get_option('acp_cj_settings')){
      add_option('acp_cj_settings' , $options);
    } else {
      update_option('acp_cj_settings' , $options);
	}
	}
	
	
	public function add_admin_items() {
		add_options_page('Auto Content Poster Commission Junction Settings', 'Auto Poster CJ Settings', 'administrator', 'acp_cjoptions', array($this,'acp_cj_options'));
		
	}
	public function add_advance_items() {
		 $page_hook_suffix = add_options_page('Auto Content Poster Commission Junction Advance Settings', 'Auto-Poster CJ Advance Settings', 'administrator', 'PostSetting', array($this,'acp_cj_options2'));
		 add_action('admin_print_scripts-' . $page_hook_suffix, array($this,'acp_cj_scripts'));
	}
	
	public function acp_cj_scripts(){
		 wp_enqueue_script( 'acp_cj-script' );
	}

	public function register_aioasettings() {
		register_setting( 'acp_cj_settings', 'acp_cj_settings' );
		
	}	
	public function register_advance_settings() {
		$advoptions = array();
		 add_option('acp_cj_advance_settings' , $advoptions);
		register_setting( 'acp_cj_advance_settings', 'acp_cj_advance_settings' );
		wp_register_script( 'acp_cj-script', plugins_url( 'js/acp_cj.js', __FILE__ ) );
	}	
	public function acp_cj_options() {
		include('includes/options-page.php');
	}
	public function acp_cj_options2() {
		include('includes/options2.php');
	}
	
}

function acp_cj_interval($c,$int=''){
	switch($c){
		case 'daily':
			wp_clear_scheduled_hook('acp_cjdailyevent');
			wp_schedule_event(time(),'daily','acp_cjdailyevent');
			break;
		case 'hourly':
			wp_clear_scheduled_hook('acp_cjdailyevent');
			wp_schedule_event(time(),'hourly','acp_cjdailyevent');
			break;
		case 'twicedaily':
			wp_clear_scheduled_hook('acp_cjdailyevent');
			wp_schedule_event(time(),'twicedaily','acp_cjdailyevent');
			break;
		
	}

}

function acp_cj_deactivate() {
		global $wpdb;
		wp_clear_scheduled_hook('acp_cjdailyevent');
		delete_option('acp_cj_settings');
		delete_option('acp_cj_advance_settings');
		$q = "DROP TABLE bestcjdb";
		$q2 = "DROP TABLE acp_cj_tmp";
		$q3 = "DROP TABLE opttable";
		$wpdb->query($q);
		$wpdb->query($q2);
		$wpdb->query($q3);
	}

function acp_cj_cj($b){
		global $cjapi_key;
	$url = 'https://advertiser-lookup.api.cj.com/v3/advertiser-lookup?advertiser-ids=joined'.
			'&records-per-page=100'.
			'&page-number='.$b;
		//Request results from CJ REST API and return results as XML.
		$headers = array( 'Authorization' => $cjapi_key );
		$request = new WP_Http;
		$result = $request->request( $url , array( 'method' => 'GET', 'headers' => $headers, 'sslverify' => false ) );
		if ( is_wp_error($result) ) {
			return false;
		} else {
		$xml = new SimpleXMLElement($result['body']);
			 return $xml;
		}
}

function acp_cj_alltodb(){
			
	
		global $wpdb;		//wordpress class

$sql = "CREATE TABLE bestcjdb( 
       id INT AUTO_INCREMENT,
	   adid INT,
	   adname TEXT,
	   adcat VARCHAR(20),
	   tmp INT,
	   PRIMARY KEY ( id )); ";
$sql2 = "SHOW TABLES LIKE 'bestcjdb'";
$retval =  $wpdb->query($sql2); //wpdb class method

//table check if exits or not
if($retval == 0)
{
   $wpdb->query($sql);
  
}else{
	$wpdb->query("TRUNCATE TABLE `bestcjdb`");
}

$pn=1;
$advs = acp_cj_cj($pn);
if($advs){
foreach ($advs->advertisers[0] as $adv) 
		{
	$adn = str_replace("'","",$adv->{'advertiser-name'});
			$adc = str_replace("'","",$adv->{'primary-category'}->child);
	$wpdb->query("INSERT INTO `bestcjdb`(`id`,`adid`,`adname`,`adcat`)
VALUES(NULL,'".$adv->{'advertiser-id'}."','".$adn."','".$adc."')");
		}
$attributes = $advs->advertisers->attributes();
$n = $attributes->{'total-matched'};
$t = (int)($attributes->{'total-matched'}/100);
$s = $attributes->{'total-matched'}%100;
if($s!=0){
	$t+=1;
}

if($t>=2){
for($i=2;$i<=$t;$i++){
	$advs = acp_cj_cj($i);
	foreach ($advs->advertisers[0] as $adv) 
		{
			$adn = str_replace("'","",$adv->{'advertiser-name'});
			$adc = str_replace("'","",$adv->{'primary-category'}->child);
	$wpdb->query("INSERT INTO `bestcjdb`(`id`,`adid`,`adname`,`adcat`)
VALUES(NULL,'".$adv->{'advertiser-id'}."','".$adn."','".$adc."')");
		}
  }
 }
 $wpdb->query('UPDATE bestcjdb SET tmp=1 WHERE id=1');
}
}	

function acp_cj_refreshDB(){
	global $wpdb;
	$r2 = $wpdb->get_results('SELECT tmp FROM bestcjdb WHERE id=1');
	$b = $r2[0]->tmp;
	$wpdb->query("TRUNCATE TABLE `bestcjdb`");
	$pn=1;
$advs = acp_cj_cj($pn);
foreach ($advs->advertisers[0] as $adv) 
		{
	$adn = str_replace("'","",$adv->{'advertiser-name'});
			$adc = str_replace("'","",$adv->{'primary-category'}->child);
	$wpdb->query("INSERT INTO `bestcjdb`(`id`,`adid`,`adname`,`adcat`)
VALUES(NULL,'".$adv->{'advertiser-id'}."','".$adn."','".$adc."')");
		}
$attributes = $advs->advertisers->attributes();
$n = $attributes->{'total-matched'};
$t = (int)($attributes->{'total-matched'}/100);
$s = $attributes->{'total-matched'}%100;
if($s!=0){
	$t+=1;
}

if($t>=2){
for($i=2;$i<=$t;$i++){
	$advs = acp_cj_cj($i);
	foreach ($advs->advertisers[0] as $adv) 
		{
			$adn = str_replace("'","",$adv->{'advertiser-name'});
			$adc = str_replace("'","",$adv->{'primary-category'}->child);
	$wpdb->query("INSERT INTO `bestcjdb`(`id`,`adid`,`adname`,`adcat`)
VALUES(NULL,'".$adv->{'advertiser-id'}."','".$adn."','".$adc."')");
		}
  }
 }
 $wpdb->query('UPDATE bestcjdb SET tmp='.$b.' WHERE id=1');
}

function acp_cj_checkdb(){
	global $wpdb;
	$sql = "SHOW TABLES LIKE 'bestcjdb'";
$retval =  $wpdb->query($sql); //wpdb class method
$sql2 = "SELECT count(id) FROM bestcjdb";
$retval2 =  $wpdb->get_results($sql2,ARRAY_N);
//table check if exits or not
if($retval == 0 || $retval2[0][0] == 0)
{
   return true;
  
}else{
	return false;
}
}

function acp_cjposter($a=''){
		global $wpdb,$cjapi_key,$cjwebid,$cjrecord,$cjcat,$cjtable;
		$r = $wpdb->get_results('SELECT MAX(id) FROM '.$cjtable);
		$max = $r[0]->{'MAX(id)'};
		$r2 = $wpdb->get_results('SELECT tmp FROM '.$cjtable.' WHERE id=1');
		$b = $r2[0]->tmp;
		$resu = $wpdb->get_results('SELECT adid,adname,adcat FROM '.$cjtable.' WHERE id='.$b);
		
		if($b<$max){
		$wpdb->query('UPDATE '.$cjtable.' SET tmp=tmp+1 WHERE id=1');
		}else{
		acp_cj_cj_alltodb();
		
		}
		if(empty($a)){
			$cjadid = $resu[0]->adid;
		}else{
			$cjadid = $a;
		}
		$url = 'https://product-search.api.cj.com/v2/product-search?website-id='.$cjwebid.
			'&advertiser-ids='.$cjadid.
			'&records-per-page='.$cjrecord;
		$headers = array( 'Authorization' => $cjapi_key );
		$request = new WP_Http;
		$result = $request->request( $url , array( 'method' => 'GET', 'headers' => $headers, 'sslverify' => false ) );
		$data = new SimpleXMLElement($result['body']);
		$attributes = $data->products->attributes();
	$msg = '';
		if ($attributes->{'total-matched'} == 0)
		{
			//if products not availabe for given advertiser id then getting text link
			
			$url = 'https://linksearch.api.cj.com/v2/link-search?website-id='.$cjwebid.'&advertiser-ids='.$cjadid.'&link-type=text+link&records-per-page=1';
		$headers = array( 'Authorization' => $cjapi_key );
		$request = new WP_Http;
		$result = $request->request( $url , array( 'method' => 'GET', 'headers' => $headers, 'sslverify' => false ) );
		$data = new SimpleXMLElement($result['body']);
		foreach ($data->links[0] as $link) 
							{
							// Sanitize data.
							$r3 = $wpdb->get_results('select * from '.$wpdb->links.' where link_name="'.$link->{'advertiser-name'}.'"');
							if(!empty($r3)){
								continue;
							}
							$pd = $link->{'link-code-html'};
							preg_match("/a[\s]+[^>]*?href[\s]?=[\s\"\']+".
										"(.*?)[\"\']+.*?>"."([^<]+|.*?)?<\/a>/",$pd,$matches);
							preg_match('#<img\s+src\s*=\s*"([^"]+)"#i',$pd,$mat);
							if($cjcat){
								$ids = get_term_by('slug', $cjcat, 'link_category');//wordpress function
								if($ids){
									$id = (int)$ids->term_id;
								}else{
									$cjcatarr = array('cat_name' => $cjcat, 
													'taxonomy' => 'link_category');
									$id = wp_insert_category($cjcatarr);
								}
							}else{
								$ids = get_term_by('slug', $resu[0]->adcat, 'link_category');//wordpress function
								if($ids){
									$id = (int)$ids->term_id;
								}else{
									$cjcatarr = array('cat_name' => $resu[0]->adcat, 
													'taxonomy' => 'link_category');
									$id = wp_insert_category($cjcatarr);
								}
							}
							$p = array('link_name'    => $link->{'advertiser-name'},
  										'link_url'  => $matches[1],
 			 							'link_description' =>$link->description,
										'link_image' => $mat[1],
										'link_category'  =>$id,
										'link_target' => '_blank');
							$pr = wp_insert_link( $p, true );//wordpress function
							wp_reset_query();  // Restore global post data stomped by the_post().
							}
		}else{
				foreach ($data->products[0] as $product) 
				{
				// Sanitize data.
				$postid = $wpdb->get_var('SELECT ID FROM '.$wpdb->posts.' WHERE post_title = "'.$product->name.'"');
				if($postid){
					
					continue;
				}
				
				if(!empty($product->{'sale-price'}) and $product->{'sale-price'} != '0.0'){
					$price = '&nbsp;<b>Sale Price: &nbsp;$&nbsp;</b>'.$product->{'sale-price'}.'&nbsp;&nbsp;&nbsp;';
				}elseif(!empty($product->price) and $product->price != '0.0'){
					$price = "&nbsp;<b>Price: &nbsp;$&nbsp;</b>".$product->price."&nbsp;&nbsp;&nbsp;";
				}else{
					$price = '';
				}
				$image = '<a href="'.$product->{'buy-url'}.'"><img src="'.$product->{'image-url'}.'" style="float: right; width:200px; height:200px;"/></a>';
				$pd =  $image.$product->description.$price.'<a href="'.$product->{'buy-url'}.'">Read More and Buy it here!</a>';
				
				if($cjcat){
					$ids = get_term_by('slug', $cjcat, 'category');//wordpress function
					if($ids){
						$id = (int)$ids->term_id;
					}else{
						$id = wp_create_category($cjcat);
					}
				}else{
					$ids = get_term_by('slug', $resu[0]->adcat, 'category');//wordpress function
					if($ids){
						$id = (int)$ids->term_id;
					}else{
						$id = wp_create_category($resu[0]->adcat);
					}
				}
				$p = array('post_title'    => $product->name,
  					'post_content'  => $pd,
 				 	'post_status'   => 'publish',
  					'post_author'   => 1,
  					'post_category'  =>array($id));
				$pr = wp_insert_post($p);
			}
	}
	
}

function acp_cj_get_interval(){
	foreach (_get_cron_array() as $timestamp => $crons) {
	foreach ($crons as $cron_name => $cron_args) {
		foreach ($cron_args as $cron) {
			if($cron_name == 'acp_cjdailyevent'){
				return $cron['interval'];
			}
		}
	}
}
}

function acp_cj_select(){
	global $wpdb;
	$sql = "select adcat,count(*) as c from bestcjdb group by adcat having c>0 ORDER BY adcat";
	$retval =  $wpdb->get_results($sql,ARRAY_N);
	$select = '';
	foreach($retval as $n){
	
		$select .= '<option value="'.$n[0].'">'.$n[0].' ('.$n[1].')</option>';
	
	}
	return $select;
}

function acp_cj_deletetmptabel(){
	global $wpdb;
	$sql2 = "SHOW TABLES LIKE 'acp_cj_tmp'";
	$retval =  $wpdb->query($sql2);
	if($retval != 0)
	{
   		$wpdb->query("TRUNCATE TABLE `acp_cj_tmp`");
  
	}
}

function acp_cj_alladvs(){
	global $wpdb;
	$sql = "SELECT adid,adname FROM bestcjdb ORDER BY adname ASC";
	$retval =  $wpdb->get_results($sql,ARRAY_N);
	$select = '';
	foreach($retval as $n){
	
		$select .= '<option value="'.$n[0].'">'.$n[1].'</option>';
	
	}
	return $select;
}

if( class_exists( 'acp_cj_Wordpress' ) ) {
	$acp_cj = new acp_cj_Wordpress();
	add_action('admin_menu', array(&$acp_cj,'add_admin_items') );
	add_action( 'admin_init', array(&$acp_cj,'register_aioasettings') );
	register_activation_hook( __FILE__, array(&$acp_cj, 'activate'));
	register_deactivation_hook( __FILE__, 'acp_cj_deactivate');
	$options = get_option('acp_cj_settings');
	$advoptions = get_option('acp_cj_advance_settings');
	$c = acp_cj_checkdb();
 if(!empty($options['acp_cj_key']) and $c == true){
		$cjapi_key = $options['acp_cj_key'];
		acp_cj_alltodb();
	}
 	$cjamazon = FALSE;
 	$cjebay = FALSE;
   if(!empty($options['acp_cj_key'])){
		add_action('admin_menu', array(&$acp_cj,'add_advance_items') );
		add_action( 'admin_init', array(&$acp_cj,'register_advance_settings') );
	}
 if(!empty($advoptions['post_record'])){
 	if(!empty($advoptions['category']) and $advoptions['category']=='manual' and !empty($advoptions['category_name'])){
		$cjcat = strtolower($advoptions['category_name']);
	}else{
		$cjcat = FALSE;
	}
	$cjtable = 'bestcjdb';
	$cjtempl = FALSE;
	$s = wp_get_schedule('acp_cjdailyevent');
	if($s != $advoptions['interval'] && $advoptions['interval'] != 'custom'){
		acp_cj_interval($advoptions['interval']);
	}
	include_once(ABSPATH.'wp-admin/includes/taxonomy.php');
 	include_once(ABSPATH.'wp-admin/includes/bookmark.php');
	$cjrecord = $advoptions['post_record'];
	$cjapi_key = $options['acp_cj_key'];
	$cjwebid = $options['cj_site_id'];
	add_action('acp_cjdailyevent','acp_cjposter');
}
}

if (!get_option('link_manager_enabled')){
	add_filter( 'pre_option_link_manager_enabled', '__return_true' );//wordpress option
}
?>