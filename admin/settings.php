<?php
use Ctct\Auth\CtctOAuth2;
use Ctct\Exceptions\OAuth2Exception;


/**
 * Admin enqueues
 */
add_action( 'admin_enqueue_scripts', 'wpsl_admin_enqueues' );

function wpsl_admin_enqueues() {

	wp_enqueue_style( 'wpsl_style', WPSL_ROOT_URL . 'admin/css/wpsl_style.css' );

	// NOTE: Patch for confliction with wpColorPicket
	if( !empty($_GET['tab']) && sanitize_text_field($_GET['tab']) == 'wpsl_dashboard' ){
		wp_enqueue_script( 'chartjs', WPSL_ROOT_URL . 'admin/js/Chart.bundle.min.js', array( 'jquery' ) );
	}

}


/**
 * Add Menu Page
 */
add_action('admin_menu', 'wpsl_admin_Page');

function wpsl_admin_Page() {

	global $wpsl_leads;

	$menu_slug		=	WPSL_SLUG.'-menu';
	$leads_count	=	$wpsl_leads->count();

	add_menu_page(WPSL_TITLE, 'Capture and Convert', 'manage_options', $menu_slug, 'wpsl_settings_page_output' , 'dashicons-unlock' );

	add_submenu_page( $menu_slug, 'All Widgets', 'All Widgets', 'manage_options', $menu_slug);
	add_submenu_page( $menu_slug, '+ New Widget', '+ New Widget', 'manage_options', 'admin.php?page='.$menu_slug.'&tab=wpsl_new_widget');
	add_submenu_page( $menu_slug, 'Leads ('.$leads_count.')', 'Leads ('.$leads_count.')', 'manage_options', 'admin.php?page='.$menu_slug.'&tab=wpsl_lead_collection');
	add_submenu_page( $menu_slug, 'Leads Integration', 'Leads Integration', 'manage_options', 'admin.php?page='.$menu_slug.'&tab=wpsl_leads_integration');
	add_submenu_page( $menu_slug, 'Stats & Reports', 'Stats & Reports', 'manage_options', 'admin.php?page='.$menu_slug.'&tab=wpsl_dashboard');
	// add_submenu_page( $menu_slug, 'Global Settings', 'Global Settings', 'manage_options', 'admin.php?page='.$menu_slug.'&tab=wpsl_general');
	// add_submenu_page( $menu_slug, 'How To Use?', 'How To Use?', 'manage_options', 'admin.php?page='.$menu_slug.'&tab=wpsl_how_to_use');
	// add_submenu_page( $menu_slug, 'Licence Manager', 'Licence Manager', 'manage_options', 'admin.php?page='.$menu_slug.'&tab=wpsl_license');
	add_submenu_page( $menu_slug, 'Social Integration', 'Social Integration', 'manage_options', 'admin.php?page='.$menu_slug.'&tab=wpsl_content_unlockers');
	add_submenu_page( $menu_slug, 'Go Premium', 'Go Premium', 'manage_options', 'https://captureandconvert.io/install/');

}

/**
 * Show notice/error messages to admin
 */
add_action('admin_notices', 'wpslcnc_admin_error_msgs');
function wpslcnc_admin_error_msgs(){

	if( isset($_GET['wpslcnc_api_removed']) && $_GET['wpslcnc_api_removed'] == 'true' ){
		echo '<div class="notice notice-success is-dismissible">';
			echo '<p>Api details deleted successfully!</p>';
		echo '</div>';
	}

	$admin_errors = apply_filters('wpslcnc_admin_errors', array());

	foreach( $admin_errors as $admin_error ){
		echo '<div class="notice notice-' . $admin_error['type'] . ' is-dismissible">';
			echo '<p><b>CNC Error: </b>' . $admin_error['error'] . '</p>';
		echo '</div>';
	}

}

/**
 * Remove API details from DB
 */
add_action('init', 'wpslcnc_remove_api_func');
function wpslcnc_remove_api_func(){

	if( isset($_GET['wpslcnc_remove_api']) && $_GET['wpslcnc_remove_api'] != '' ){

		switch( $_GET['wpslcnc_remove_api'] ){

			case 'mailchimp':
				delete_option('wpsl_mailchimp_api_key');
				delete_option('wpsl_mailchimp_cache_lists');
			break;

			case 'activecampaign':
				delete_option('wpsl_activecampaign_api_url');
				delete_option('wpsl_activecampaign_api_key');
				delete_option('wpsl_activecampaign_cache_lists');
			break;

			case 'aweber':
				delete_option('wpsl_aweber_consumer_key');
				delete_option('wpsl_aweber_consumer_secret');
				delete_option('wpsl_aweber_cache_lists');
			break;

			case 'constantcontact':
				delete_option('wpsl_constantcontact_api_key');
				delete_option('wpsl_constantcontact_api_secret');
				delete_option('wpsl_constantcontact_access_token');
				delete_option('wpsl_constantcontact_cache_lists');
			break;

			case 'campaignmonitor':
				delete_option('wpsl_campaignmonitor_api_key');
				delete_option('wpsl_campaignmonitor_client_id');
				delete_option('wpsl_campaignmonitor_cache_lists');
			break;

			case 'drip':
				delete_option('wpsl_drip_api_key');
				delete_option('wpsl_drip_account_id');
				delete_option('wpsl_drip_cache_campaigns');
			break;

			case 'hubspot':
				delete_option('wpsl_hubspot_api_key');
				delete_option('wpsl_hubspot_cache_lists');
			break;

			case 'getresponse':
				delete_option('wpsl_getresponse_api_key');
				delete_option('wpsl_getresponse_cache_lists');
			break;

			case 'convertkit':
				delete_option('wpsl_convertkit_api_key');
				delete_option('wpsl_convertkit_cache_forms');
			break;

		}

		wp_redirect('admin.php?page=capture-and-convert-menu&tab=wpsl_leads_integration&section='.sanitize_text_field($_GET['section']).'&wpslcnc_api_removed=true');
		exit;

	}

}


/**
 * Update Admin Page title
 *
 * NOTE: There's some issue, for some reason only the first submenu page's title
 * is used for all submenu pages
 */
add_filter('admin_title', 'wpslcnc_update_admin_title_tag', 10, 2);
function wpslcnc_update_admin_title_tag( $admin_title, $title )
{

	global $wpsl_leads;

	if( !empty($_GET['page']) && $_GET['page'] == 'capture-and-convert-menu' ){

		$title_map = array(
			'wpsl_all_widgets'				=>	'All Widgets',
			'wpsl_new_widget'					=>	'+ New Widget',
			'wpsl_lead_collection'		=>	'Leads ('.$wpsl_leads->count().')',
			'wpsl_leads_integration'	=>	'Leads Integration',
			'wpsl_dashboard'					=>	'Stats & Reports',
			'wpsl_license'						=>	'License Manager',
			'wpsl_content_unlockers'	=>	'Social Integration',
			'wpsl_go_premium'					=>	'Go Premium'
		);

		$curr_tab = ($_GET['tab'] == '' ? 'wpsl_all_widgets' : $_GET['tab']);

		$title = str_replace($title,  $title_map[$curr_tab], $admin_title);

	}

	return $title;

}


/**
 * Initialize Generating Settings Page
 */
add_action( 'admin_init', 'wpsl_initializing_settings' );

function wpsl_initializing_settings(){

	global $wpsl_forms;

	// General Settings
	$wpsl_forms->add_element( 'wpsl_lock_title', 'Lock Title', 'Title to be shown on Lock snippet', 'wpsl_general', null, 'text', 'This content is Locked.' );
	$wpsl_forms->add_element( 'wpsl_lock_description', 'Lock Description', 'Description to be shown under the title of Lock Widget', 'wpsl_general', null, 'textarea', 'This content is locked. You can access the content by hiting the like, share or follow button bellow.' );
	// $wpsl_forms->add_element( 'wpsl_master_enable', 'Master Disable Locks', 'If this option is checked, all the locks will be unlocked.', 'wpsl_general', null, 'checkbox', true );

	// Facebook Settings
	// $wpsl_forms->add_element( 'wpsl_enable_facebook_like', 'Facebook Like', 'If this option is checked, "Facebook Like" can unlock the content.', 'wpsl_content_unlockers', 'facebook', 'checkbox', true );
	// $wpsl_forms->add_element( 'wpsl_enable_facebook_share', 'Facebook Share', 'If this option is checked, "Facebook Share" can unlock the content.', 'wpsl_content_unlockers', 'facebook', 'checkbox', true );
	$wpsl_forms->add_element( 'wpsl_fb_app_id', 'Facebook APP ID', 'Enter your Facebook APP ID here. <a href="https://captureandconvert.io/documentation/facebook-app-id/" target="_blank">Learn More</a>', 'wpsl_content_unlockers', 'facebook' );

	// Twitter Settings
	// $wpsl_forms->add_element( 'wpsl_enable_twitter_tweet', 'Twitter Tweet', 'If this option is checked, "Twitter Tweet" can unlock the content.', 'wpsl_content_unlockers', 'twitter', 'checkbox', true );
	$wpsl_forms->add_element( 'wpsl_twitter_key', 'Twitter Consumer Key', 'Enter your Twitter Consumer Key here. <a href="https://captureandconvert.io/documentation/twitter-api-key/" target="_blank">Learn More</a><br>Callback URL: '.TWITTER_CB_URL, 'wpsl_content_unlockers', 'twitter' );
	$wpsl_forms->add_element( 'wpsl_twitter_secret', 'Twitter Consumer Secret', 'Enter your Twitter Consumer Secret here. <a href="https://captureandconvert.io/documentation/twitter-api-key/" target="_blank">Learn More</a>', 'wpsl_content_unlockers', 'twitter' );

	// Google/Youtube Settings
	// $wpsl_forms->add_element( 'wpsl_enable_youtube_subscribe', 'Youtube Subscribe', 'If this option is checked, "Youtube Subscribe" can unlock the content.', 'wpsl_content_unlockers', 'youtube', 'checkbox', true );
	// $wpsl_forms->add_element( 'wpsl_google_oauth2_client_id', 'Google Oauth Client ID', 'Enter your Oauth Client ID here (<a href="https://console.developers.google.com/" target="_blank">generate</a>).', 'wpsl_content_unlockers', 'youtube' );
	// $wpsl_forms->add_element( 'wpsl_google_oauth2_client_secret', 'Google Oauth Client Secret', 'Enter your Oauth Client Secret here (<a href="https://console.developers.google.com/" target="_blank">generate</a>).', 'wpsl_content_unlockers', 'youtube' );
	// $wpsl_forms->add_element( 'wpsl_youtube_subscribe_channel_id', 'Youtube Channel ID', 'Enter the Youtube Channel ID to be subscribed.', 'wpsl_content_unlockers', 'youtube' );

	// Mailchimp Settings
	$wpsl_forms->add_element( 'wpsl_mailchimp_api_key', 'Mailchimp API Key', 'Enter the API key of Mailchimp. <a href="https://captureandconvert.io/documentation/email-api/" target="_blank">Learn More</a>', 'wpsl_leads_integration', 'mailchimp' );
	// $wpsl_forms->add_element( 'wpsl_mailchimp_list_id', 'Mailchimp List ID', 'Enter the List ID of mailchimp. <a href="https://kb.mailchimp.com/lists/manage-contacts/find-your-list-id" target="_blank">Learn More</a>', 'wpsl_leads_integration', 'mailchimp' );
	$wpsl_forms->add_element( 'wpsl_mailchimp_auto_export', 'Mailchimp Auto Export', 'If checked, the widgets that have Mailchimp List selected will automatically export data to that particular list.', 'wpsl_leads_integration', 'mailchimp', 'checkbox', true );

	if( get_option('wpsl_mailchimp_api_key') != '' ){

		$mc_list	=	get_option('wpsl_mailchimp_cache_lists');
		$mc_l_str	=	implode('</li><li> - ', (array) $mc_list);
		$gen_url	=	"admin.php?page=capture-and-convert-menu&tab=wpsl_leads_integration&section=mailchimp&wpsl_generate_lists=mailchimp";
		$wpsl_forms->add_element( 'wpsl_mailchimp_cache_lists', 'Your Lists', '<a href="'.$gen_url.'">Cache lists from your account</a><br /><ul><li> - '.$mc_l_str.'</li></ul>', 'wpsl_leads_integration', 'mailchimp', 'blank' );

		$rem_url	=	"admin.php?page=capture-and-convert-menu&tab=wpsl_leads_integration&section=mailchimp&wpslcnc_remove_api=mailchimp";
		$wpsl_forms->add_element( 'wpsl_mailchimp_remove_from_db', 'Remove API Details', '<a href="javascript:;" class="wpslcnc_remove_api" data-api_name="Mailchimp" data-del_url="'.$rem_url.'">Remove API details from database</a>', 'wpsl_leads_integration', 'mailchimp', 'blank' );

	}


	// ActiveCampaign Settings
	$wpsl_forms->add_element( 'wpsl_activecampaign_api_url', 'ActiveCampaign API URL', 'Enter the API url of ActiveCampaign. <a href="https://help.activecampaign.com/hc/en-us/articles/207317590-Getting-started-with-the-API#how-to-obtain-your-activecampaign-api-url-and-key" target="_blank">Learn More</a>', 'wpsl_leads_integration', 'activecampaign' );
	$wpsl_forms->add_element( 'wpsl_activecampaign_api_key', 'ActiveCampaign API Key', 'Enter the API Key of ActiveCampaign. <a href="https://help.activecampaign.com/hc/en-us/articles/207317590-Getting-started-with-the-API#how-to-obtain-your-activecampaign-api-url-and-key" target="_blank">Learn More</a>', 'wpsl_leads_integration', 'activecampaign' );
	// $wpsl_forms->add_element( 'wpsl_activecampaign_list_id', 'ActiveCampaign List ID', 'Enter the List ID of ActiveCampaign. <a href="http://support.exitbee.com/email-marketing-crm-integrations/how-to-find-your-activecampaign-list-id" target="_blank">Learn More</a>', 'wpsl_leads_integration', 'activecampaign' );
	$wpsl_forms->add_element( 'wpsl_activecampaign_auto_export', 'ActiveCampaign Auto Export', 'If checked, the widgets that have ActiveCampaign List selected will automatically export data to that particular list.', 'wpsl_leads_integration', 'activecampaign', 'checkbox', true );

	if( get_option('wpsl_activecampaign_api_url') != '' && get_option('wpsl_activecampaign_api_key') != '' ){

		$ac_lists	=	get_option('wpsl_activecampaign_cache_lists');
		$ac_l_str	=	implode('</li><li> - ', (array) $ac_lists);
		$gen_url	=	"admin.php?page=capture-and-convert-menu&tab=wpsl_leads_integration&section=activecampaign&wpsl_generate_lists=activecampaign";
		$wpsl_forms->add_element( 'wpsl_activecampaign_cache_lists', 'Your Lists', '<a href="'.$gen_url.'">Cache lists from your account</a><br /><ul><li> - '.$ac_l_str.'</li></ul>', 'wpsl_leads_integration', 'activecampaign', 'blank' );

		$rem_url	=	"admin.php?page=capture-and-convert-menu&tab=wpsl_leads_integration&section=activecampaign&wpslcnc_remove_api=activecampaign";
		$wpsl_forms->add_element( 'wpsl_activecampaign_remove_from_db', 'Remove API Details', '<a href="javascript:;" class="wpslcnc_remove_api" data-api_name="ActiveCampaign" data-del_url="'.$rem_url.'">Remove API details from database</a>', 'wpsl_leads_integration', 'activecampaign', 'blank' );

	}


	// Constant Contact Settings
	$wpsl_forms->add_element( 'wpsl_constantcontact_api_key', 'Constant Contact API Key', 'Enter the API Key of Constant Contact. <a href="https://community.constantcontact.com/t5/Product-News/How-to-generate-an-API-Key-and-Access-Token/ba-p/293856" target="_blank">Learn More</a>', 'wpsl_leads_integration', 'constantcontact' );
	$wpsl_forms->add_element( 'wpsl_constantcontact_api_secret', 'Constant Contact API Secret', 'Enter the API Secret of Constant Contact. <a href="https://community.constantcontact.com/t5/Product-News/How-to-generate-an-API-Key-and-Access-Token/ba-p/293856" target="_blank">Learn More</a>', 'wpsl_leads_integration', 'constantcontact' );
	$wpsl_forms->add_element( 'wpsl_constantcontact_access_token', 'Constant Contact Access Token', 'Enter the Access Token of Constant Contact. <a href="https://community.constantcontact.com/t5/Product-News/How-to-generate-an-API-Key-and-Access-Token/ba-p/293856" target="_blank">Learn More</a>', 'wpsl_leads_integration', 'constantcontact' );
	$wpsl_forms->add_element( 'wpsl_constantcontact_auto_export', 'Constant Contact Auto Export', 'If checked, the widgets that have ConstantContact List selected will automatically export data to that particular list.', 'wpsl_leads_integration', 'constantcontact', 'checkbox', true );

	if( get_option('wpsl_constantcontact_api_key') != '' && get_option('wpsl_constantcontact_api_secret') != '' && get_option('wpsl_constantcontact_access_token') != '' ){

		$cc_lists	=	get_option('wpsl_constantcontact_cache_lists');
		$cc_l_str	=	implode('</li><li> - ', (array) $cc_lists);
		$gen_url	=	"admin.php?page=capture-and-convert-menu&tab=wpsl_leads_integration&section=constantcontact&wpsl_generate_lists=constantcontact";
		$wpsl_forms->add_element( 'wpsl_constantcontact_cache_lists', 'Your Lists', '<a href="'.$gen_url.'">Cache lists from your account</a><br /><ul><li> - '.$cc_l_str.'</li></ul>', 'wpsl_leads_integration', 'constantcontact', 'blank' );

		$rem_url	=	"admin.php?page=capture-and-convert-menu&tab=wpsl_leads_integration&section=constantcontact&wpslcnc_remove_api=constantcontact";
		$wpsl_forms->add_element( 'wpsl_constantcontact_remove_from_db', 'Remove API Details', '<a href="javascript:;" class="wpslcnc_remove_api" data-api_name="Constant Contact" data-del_url="'.$rem_url.'">Remove API details from database</a>', 'wpsl_leads_integration', 'constantcontact', 'blank' );

	}

	// Campaign Monitor Settings campaignmonitor
	$wpsl_forms->add_element( 'wpsl_campaignmonitor_api_key', 'Campaign Monitor API Key', 'Enter the API Key of Campaign Monitor.', 'wpsl_leads_integration', 'campaignmonitor' );
	$wpsl_forms->add_element( 'wpsl_campaignmonitor_client_id', 'Campaign Monitor Client ID', 'Enter the Client ID of Campaign Monitor.', 'wpsl_leads_integration', 'campaignmonitor' );
	$wpsl_forms->add_element( 'wpsl_campaignmonitor_auto_export', 'Campaign Monitor Auto Export', 'If checked, the widgets that have Campaign Monitor List selected will automatically export data to that particular list.', 'wpsl_leads_integration', 'campaignmonitor', 'checkbox', true );

	if( get_option('wpsl_campaignmonitor_api_key') != '' && get_option('wpsl_campaignmonitor_client_id') != '' ){
 		$cm_lists	=	get_option('wpsl_campaignmonitor_cache_lists');
		$cm_l_str	=	implode('</li><li> - ', (array) $cm_lists);
		$gen_url	=	"admin.php?page=capture-and-convert-menu&tab=wpsl_leads_integration&section=campaignmonitor&wpsl_generate_lists=campaignmonitor";
		$wpsl_forms->add_element( 'wpsl_campaignmonitor_cache_lists', 'Your Lists', '<a href="'.$gen_url.'">Cache Lists from your account</a><br /><ul><li> - '.$cm_l_str.'</li></ul>', 'wpsl_leads_integration', 'campaignmonitor', 'blank' );

		$rem_url	=	"admin.php?page=capture-and-convert-menu&tab=wpsl_leads_integration&section=campaignmonitor&wpslcnc_remove_api=campaignmonitor";
		$wpsl_forms->add_element( 'wpsl_campaignmonitor_remove_from_db', 'Remove API Details', '<a href="javascript:;" class="wpslcnc_remove_api" data-api_name="Campaign Monitor" data-del_url="'.$rem_url.'">Remove API details from database</a>', 'wpsl_leads_integration', 'campaignmonitor', 'blank' );

 	}

	// GetResponse Settings
	$wpsl_forms->add_element( 'wpsl_getresponse_api_key', 'GetResponse API Key', 'Enter the API Key of GetResponse.', 'wpsl_leads_integration', 'getresponse' );
	$wpsl_forms->add_element( 'wpsl_getresponse_auto_export', 'GetResponse Auto Export', 'If checked, the widgets that have GetResponse List selected will automatically export data to that particular list.<br> <small>Note: Disposable emails may not go to GetResponse if they\'ve been blacklisted</small>', 'wpsl_leads_integration', 'getresponse', 'checkbox', true );

 	if( get_option('wpsl_getresponse_api_key') != '' ){

 		$gr_lists	=	get_option('wpsl_getresponse_cache_lists');
		$gr_l_str	=	implode('</li><li> - ', (array) $gr_lists);
		$gen_url	=	"admin.php?page=capture-and-convert-menu&tab=wpsl_leads_integration&section=getresponse&wpsl_generate_lists=getresponse";
		$wpsl_forms->add_element( 'wpsl_getresponse_cache_lists', 'Your Lists', '<a href="'.$gen_url.'">Cache Lists from your account</a><br /><ul><li> - '.$gr_l_str.'</li></ul>', 'wpsl_leads_integration', 'getresponse', 'blank' );

		$rem_url	=	"admin.php?page=capture-and-convert-menu&tab=wpsl_leads_integration&section=getresponse&wpslcnc_remove_api=getresponse";
		$wpsl_forms->add_element( 'wpsl_getresponse_remove_from_db', 'Remove API Details', '<a href="javascript:;" class="wpslcnc_remove_api" data-api_name="GetResponse" data-del_url="'.$rem_url.'">Remove API details from database</a>', 'wpsl_leads_integration', 'getresponse', 'blank' );

 	}


	// ConvertKit Settings
	$wpsl_forms->add_element( 'wpsl_convertkit_api_key', 'ConvertKit API Key', 'Enter the API Key of ConvertKit.', 'wpsl_leads_integration', 'convertkit' );
	$wpsl_forms->add_element( 'wpsl_convertkit_auto_export', 'ConvertKit Auto Export', 'If checked, the widgets that have ConvertKit Form selected will automatically export data to that particular Form.<br> <small>Note: User may receive an additional confirmation email from ConvertKit</small>', 'wpsl_leads_integration', 'convertkit', 'checkbox', true );

	if( get_option('wpsl_convertkit_api_key') != '' ){

		$ck_forms	=	get_option('wpsl_convertkit_cache_forms');
		$ck_l_str	=	implode('</li><li> - ', (array) $ck_forms);
		$gen_url	=	"admin.php?page=capture-and-convert-menu&tab=wpsl_leads_integration&section=convertkit&wpsl_generate_lists=convertkit";
		$wpsl_forms->add_element( 'wpsl_convertkit_cache_forms', 'Your Forms', '<a href="'.$gen_url.'">Cache Forms from your account</a><br /><ul><li> - '.$ck_l_str.'</li></ul>', 'wpsl_leads_integration', 'convertkit', 'blank' );

		$rem_url	=	"admin.php?page=capture-and-convert-menu&tab=wpsl_leads_integration&section=convertkit&wpslcnc_remove_api=convertkit";
		$wpsl_forms->add_element( 'wpsl_convertkit_remove_from_db', 'Remove API Details', '<a href="javascript:;" class="wpslcnc_remove_api" data-api_name="ConvertKit" data-del_url="'.$rem_url.'">Remove API details from database</a>', 'wpsl_leads_integration', 'convertkit', 'blank' );

	}


	// Drip API settings
	$wpsl_forms->add_element( 'wpsl_drip_api_key', 'Drip API Key', 'Enter your Drip API Token (more info <a href="https://www.getdrip.com/user/edit" target="_blank">here</a>).', 'wpsl_leads_integration', 'drip' );
	$wpsl_forms->add_element( 'wpsl_drip_account_id', 'Drip Account ID', 'Enter your Drip Account ID (more info <a href="https://www.getdrip.com/settings/site" target="_blank">here</a>).', 'wpsl_leads_integration', 'drip' );
	$wpsl_forms->add_element( 'wpsl_drip_auto_export', 'Drip Auto Export', 'If checked, the widgets that have Drip List selected will automatically export data to that particular list.', 'wpsl_leads_integration', 'drip', 'checkbox', true );
	if( get_option('wpsl_drip_api_key') != '' && get_option('wpsl_drip_account_id') != '' ){
		$cc_lists	=	get_option('wpsl_drip_cache_campaigns');
		$cc_l_str	=	implode('</li><li> - ', (array) $cc_lists);
		$gen_url	=	"admin.php?page=capture-and-convert-menu&tab=wpsl_leads_integration&section=drip&wpsl_generate_lists=drip";
		$wpsl_forms->add_element( 'wpsl_drip_cache_campaigns', 'Your Campaigns', '<a href="'.$gen_url.'">Cache Campaigns from your account</a><br /><ul><li> - '.$cc_l_str.'</li></ul>', 'wpsl_leads_integration', 'drip', 'blank' );

		$rem_url	=	"admin.php?page=capture-and-convert-menu&tab=wpsl_leads_integration&section=drip&wpslcnc_remove_api=drip";
		$wpsl_forms->add_element( 'wpsl_drip_remove_from_db', 'Remove API Details', '<a href="javascript:;" class="wpslcnc_remove_api" data-api_name="Drip" data-del_url="'.$rem_url.'">Remove API details from database</a>', 'wpsl_leads_integration', 'drip', 'blank' );

	}

	// HubSpot API Settings
	$wpsl_forms->add_element( 'wpsl_hubspot_api_key', 'Hubspot API Key', 'Enter your Hubspot API (more info <a href="https://knowledge.hubspot.com/articles/kcs_article/integrations/how-do-i-get-my-hubspot-api-key" target="_blank">here</a>).', 'wpsl_leads_integration', 'hubspot' );
	$wpsl_forms->add_element( 'wpsl_hubspot_auto_export', 'Hubspot Auto Export', 'If checked, the widgets that have Hubspot List selected will automatically export data to that particular list.', 'wpsl_leads_integration', 'hubspot', 'checkbox', true );

	if( get_option('wpsl_hubspot_api_key') != '' ){
		$hs_lists	=	get_option('wpsl_hubspot_cache_lists');
		$hs_l_str	=	implode('</li><li> - ', (array) $hs_lists);
		$gen_url	=	"admin.php?page=capture-and-convert-menu&tab=wpsl_leads_integration&section=hubspot&wpsl_generate_lists=hubspot";
		$wpsl_forms->add_element( 'wpsl_hubspot_cache_lists', 'Your HubSpot Lists', '<a href="'.$gen_url.'">Cache Campaigns from your account</a><br /><ul><li> - '.$hs_l_str.'</li></ul>', 'wpsl_leads_integration', 'hubspot', 'blank' );

		$rem_url	=	"admin.php?page=capture-and-convert-menu&tab=wpsl_leads_integration&section=hubspot&wpslcnc_remove_api=hubspot";
		$wpsl_forms->add_element( 'wpsl_hubspot_remove_from_db', 'Remove API Details', '<a href="javascript:;" class="wpslcnc_remove_api" data-api_name="Hubspot" data-del_url="'.$rem_url.'">Remove API details from database</a>', 'wpsl_leads_integration', 'hubspot', 'blank' );

	}

	// AWeber Settings
	$wpsl_forms->add_element( 'wpsl_aweber_consumer_key', 'AWeber Consumer Key', 'Enter the Consumer key of AWeber.', 'wpsl_leads_integration', 'aweber' );
	$wpsl_forms->add_element( 'wpsl_aweber_consumer_secret', 'AWeber Consumer Secret', 'Enter the Consumer Secret of AWeber.', 'wpsl_leads_integration', 'aweber' );
	$wpsl_forms->add_element( 'wpsl_aweber_auto_export', 'Aweber Auto Export', 'If checked, the widgets that have Aweber List selected will automatically export data to that particular list.', 'wpsl_leads_integration', 'aweber', 'checkbox', true );

	if( get_option('wpsl_aweber_consumer_key') != '' && get_option('wpsl_aweber_consumer_secret') != '' ){

		$aw_lists	=	get_option('wpsl_aweber_cache_lists');
		$aw_l_str	=	implode('</li><li> - ', (array) $aw_lists);
		$gen_url	=	"admin.php?page=capture-and-convert-menu&tab=wpsl_leads_integration&section=aweber&wpsl_generate_lists=aweber";
		$wpsl_forms->add_element( 'wpsl_aweber_cache_lists', 'Your Lists', '<a href="'.$gen_url.'">Cache lists from your account</a><br /><ul><li> - '.$aw_l_str.'</li></ul>', 'wpsl_leads_integration', 'aweber', 'blank' );

		$rem_url	=	"admin.php?page=capture-and-convert-menu&tab=wpsl_leads_integration&section=aweber&wpslcnc_remove_api=aweber";
		$wpsl_forms->add_element( 'wpsl_aweber_remove_from_db', 'Remove API Details', '<a href="javascript:;" class="wpslcnc_remove_api" data-api_name="Aweber" data-del_url="'.$rem_url.'">Remove API details from database</a>', 'wpsl_leads_integration', 'aweber', 'blank' );

	}

	// $wpsl_forms->add_element( 'wpsl_aweber_account_id', 'AWeber Account ID', 'Enter the Account ID of AWeber.', 'wpsl_leads_integration', 'aweber' );
	// $wpsl_forms->add_element( 'wpsl_aweber_list_id', 'AWeber List ID', 'Enter the List ID of AWeber. <a href="https://help.aweber.com/hc/en-us/articles/204028426-What-is-the-unique-list-ID-" target="_blank">Learn More</a>', 'wpsl_leads_integration', 'aweber' );

	// $wpsl_forms->add_element( 'wpsl_license_key', 'Enter License Key', 'Enter the License key you got from SellWire.', 'wpsl_license' );

	$wpsl_forms->register_setting();

}


/**
 * Render Settings Page
 */
function wpsl_settings_page_output() {

	global $wpsl_forms, $wpsl_leads;

	$menu_slug	=	WPSL_SLUG.'-menu'; ?>

	<div class="wrap" id="wpsc_admin_wrap">
		<h2><i class="dashicons dashicons-unlock"></i><?php echo WPSL_TITLE; ?> Settings</h2>

		<?php

		$valid_tabs				=	array(
			'wpsl_all_widgets'				=>	'All Widgets',
			'wpsl_new_widget'					=>	'+ New Widget',
			'wpsl_lead_collection'		=>	'Leads ('.$wpsl_leads->count().')',
			'wpsl_leads_integration'	=>	'Leads Integration',
			'wpsl_dashboard'					=>	'Stats & Reports',
			// 'wpsl_general'						=>	'Global Settings',
			// 'wpsl_how_to_use'					=>	'How To Use?',
			// 'wpsl_license'						=>	'License Manager',
			'wpsl_content_unlockers'	=>	'Social Integration',
			'wpsl_go_premium'					=>	'Go Premium'
		);

		$valid_sections		=	array(
			'wpsl_content_unlockers'	=>	array(
				'facebook'				=>	'Facebook',
				'twitter'					=>	'Twitter',
				// 'youtube'					=>	'Youtube'
			),
			'wpsl_leads_integration'	=>	array(
				'mailchimp'				=>	'Mailchimp',
				'activecampaign'	=>	'ActiveCampaign',
				'aweber'					=>	'Aweber',
				'constantcontact'	=>	'Constant Contact',
				'campaignmonitor'	=>	'Campaign Monitor',
				'drip'						=>	'Drip',
				'hubspot'					=>	'HubSpot',
				'getresponse'			=>	'GetResponse',
				'convertkit'			=>	'ConvertKit'
			),
			// 'wpsl_how_to_use'	=>	array(
			// 	'intro'						=>	'Introduction',
			// 	'share_to_unlock'	=>	'Share to Unlock',
			// 	'follow_to_unlock'=>	'Follow to Unlock',
			// 	'email_to_unlock'	=>	'Email to Unlock',
			// 	'floating_widget'	=>	'Floating Widget',
			// 	'finding_api_keys'=>	'Finding API Keys'
			// )
		);

		if( isset( $_GET['tab'] ) ) {
		  $active_tab = sanitize_text_field($_GET['tab']);
		}else{
			$active_tab = "wpsl_all_widgets";
		}

		$active_section = '';
		if( isset( $_GET['section'] ) && $_GET['section'] != '' ){
			$active_section		=	sanitize_text_field($_GET['section']);
		}else{
			
			if( !empty($valid_sections[$active_tab]) ){
				$sections	=	$valid_sections[$active_tab];
				reset($sections);
				$active_section		=	key($sections);
			}

		}

		echo '<div class="wrap">';

	  settings_errors();

			echo '<h2 class="nav-tab-wrapper">';

				foreach( $valid_tabs as $slug=>$title ){

					if( 'wpsl_go_premium' == $slug ){
						echo '<a href="https://captureandconvert.io/install" target="_blank" class="nav-tab ' . ($active_tab == $slug ? "nav-tab-active" : "") . '">'.$title.'</a>';
					}else{
						echo '<a href="?page='.$menu_slug.'&tab='.$slug.'" class="nav-tab ' . ($active_tab == $slug ? "nav-tab-active" : "") . '">'.$title.'</a>';
					}

				}

			echo '</h2>';

			if( !empty($valid_sections[$active_tab]) && count($valid_sections[$active_tab]) > 0 ){

				echo '<ul class="wpsl_subsections">';

				foreach( $valid_sections[$active_tab] as $section=>$title ){
					if( $active_section == $section ){
						echo '<li>'.$title.'</li>';
					}else{
						echo '<li><a href="?page='.$menu_slug.'&tab='.$active_tab.'&section='.$section.'">'.$title.'</a></li>';
					}
				}

				echo '</ul>';

			} ?>

			<div class="admin_contents" id="wpsc-tab-<?php echo $active_tab; ?>">
				<form method="post" action="options.php" id="form-<?php echo $active_tab; ?>">
		      <?php
					switch ($active_tab) {

						case 'wpsl_all_widgets':
							$widgets_list_table = new WPSL_List_Widgets_Table();
							$widgets_list_table->prepare_items();
							echo '<script type="text/javascript">';
								echo "jQuery(document).ready(function() {
									jQuery('#uiper').attr('title', 'Views / Impressions / Ratio');
									jQuery('#uiper').tooltip({ tooltipClass: 'wpsl_tooltip' });
								});";
							echo "</script>";
	            echo '<div class="wrap">';
	                echo '<div id="icon-users" class="icon32"></div>';
	                echo '<h2>All Widgets <a href="admin.php?page='.WPSL_SLUG.'-menu&tab=wpsl_new_widget" class="add-new-h2">Add New</a></h2>';
	                $widgets_list_table->display();
	            echo '</div>';
						break;

						case 'wpsl_new_widget':
							include_once( WPSL_ROOT_DIR . 'admin/new_widget.php' );
						break;

						case 'wpsl_dashboard':
							include_once( WPSL_ROOT_DIR . 'admin/stats_and_reports.php' );
						break;

						// case 'wpsl_how_to_use':
						// 	wpsl_show_admin_help_section( $active_section );
						// break;

						case 'wpsl_go_premium':
							echo 'Coming Soon...';
						break;

						case 'wpsl_general':
						case 'wpsl_content_unlockers':
						case 'wpsl_leads_integration':
						// case 'wpsl_license':
							$wpsl_forms->render_html($active_tab, $active_section);
						break;

						case 'wpsl_lead_collection':

							$leads	=	$wpsl_leads->get_leads();

							echo '<br class="clear" />';

							echo '<a href="?page=capture-and-convert-menu&tab=wpsl_lead_collection&wpsl_export=csv">Export CSV</a>';

							// if( get_option('wpsl_mailchimp_api_key') != '' && get_option('wpsl_mailchimp_list_id') != '' )
							// 	echo '<a href="?page=capture-and-convert-menu&tab=wpsl_lead_collection&wpsl_export=mailchimp">Export to Mailchimp</a> | ';
							//
							// if( get_option('wpsl_activecampaign_api_url') != '' && get_option('wpsl_activecampaign_api_key') != '' && get_option('wpsl_activecampaign_list_id') != '' )
							// 	echo '<a href="?page=capture-and-convert-menu&tab=wpsl_lead_collection&wpsl_export=activecampaign">Export to ActiveCampaign</a> | ';
							//
							// // if( is_plugin_active( 'infusionsoft-sdk/infusionsoft-sdk.php' ))
							// 	echo '<a href="?page=capture-and-convert-menu&tab=wpsl_lead_collection&wpsl_export=infusionsoft">Export to Infusionsoft</a> | ';
							//
							// if( get_option('wpsl_aweber_list_id') != '' )
							// 	echo '<a href="?page=capture-and-convert-menu&tab=wpsl_lead_collection&wpsl_export=aweber">Export to AWeber</a> | ';
							//
							// if( get_option('wpsl_constantcontact_api_key') != '' && get_option('wpsl_constantcontact_api_secret') != '' && get_option('wpsl_constantcontact_access_token') != '' )
							// 	echo '<a href="?page=capture-and-convert-menu&tab=wpsl_lead_collection&wpsl_export=constantcontact">Export to Constant Contact</a><br />';

							echo '<br class="clear" />';

							$wpsl_export = sanitize_text_field($_GET['wpsl_export']);

							if( $wpsl_export != '' ){
								echo	'<div id="message" class="notice notice-success">
								<p>Export to '.$wpsl_export.' is successful!.</p>
								</div>';
							}

							echo '<table class="wp-list-table widefat fixed striped wpsl_leads_table">';

								echo '<thead>';
									echo '<tr>';
										echo '<th scope="col" id="name" class="manage-column column-name">Name</th>';
										echo '<th scope="col" id="email" class="manage-column column-email">Email</th>';
										echo '<th scope="col" id="source" class="manage-column column-source">Source</th>';
										echo '<th scope="col" id="date" class="manage-column column-date">Date</th>';
									echo '</tr>';
								echo '</thead>';

								echo '<tbody id="the-list">';

									if( count($leads) > 0 ){
										foreach( $leads as $lead ){
											echo '<tr>';
												echo '<td class="name column-name">'.$lead->name.'</td>';
												echo '<td class="email column-email">'.$lead->email.'</td>';
												echo '<td class="source column-source">'.($lead->source == 'manual' ? 'Email' : ucwords($lead->source)).'</td>';
												echo '<td class="date column-date">'.$lead->time.'</td>';
											echo '</tr>';
										}
									}else{
										echo '<tr class="no-items"><td class="colspanchange" colspan="4">No leads found.</td></tr>';
									}

								echo '</tbody>';

								echo '<tfoot>';
									echo '<tr>';
										echo '<th scope="col" id="name" class="manage-column column-name">Name</th>';
										echo '<th scope="col" id="email" class="manage-column column-email">Email</th>';
										echo '<th scope="col" id="source" class="manage-column column-source">Source</th>';
										echo '<th scope="col" id="date" class="manage-column column-date">Date</th>';
									echo '</tr>';
								echo '</tfoot>';

							echo '</table>';

						break;

						default:
							echo "Something went wrong.";
						break;
					} ?>

				</form>
			</div>
		</div><!-- /.wrap -->

	</div>
<?php } ?>
