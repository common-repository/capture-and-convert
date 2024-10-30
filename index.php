<?php
/**
 * Capture and Convert
 *
 * @package     Capture and Convert
 * @author      captureandconvert.io <hello@captureandconvert.io>
 * @copyright   2020 Capture & Convert <hello@captureandconvert.io>
 * @license     GPLv3
 *
 * @wordpress-plugin
 * Plugin Name: Capture and Convert
 * Plugin URI:  http://captureandconvert.io/
 * Description: Plugin to lock content and require people to like/share to unlock it
 * Version:     1.4.4
 * Author:      captureandconvert.io <hello@captureandconvert.io>
 * Author URI:  https://captureandconvert.io/
 * Text Domain: capture-and-convert
 * License:     GPLv3
 */

/**
 * Some useful constants
 */
define( 'WPSL_TITLE', "Capture and Convert" );
define( 'WPSL_SLUG', "capture-and-convert" );
define( 'WPSL_FILE', __FILE__ );
define( 'WPSL_ROOT_DIR', plugin_dir_path( __FILE__ ) );
define( 'WPSL_ROOT_URL', plugin_dir_url( __FILE__ ) );
define( 'WPSL_VERSION', "1.4.4" );
define( 'WPSL_TD', 'capture-and-convert' );


/**
 * Update old database
 *
 * Replaces old templates names with new ones
 *
 * @since 1.2
 */
function wpslcnc_update_old_db(){

	global $wpdb;

	$wpdb->update( $wpdb->postmeta,
		array( 'meta_value' => 'Basic' ),
		array( 'meta_value' => 'Simple', 'meta_key' => 'locker_widget_template' )
	);

	$wpdb->update( $wpdb->postmeta,
		array( 'meta_value' => 'Simple Light' ),
		array( 'meta_value' => 'Premium Dashing', 'meta_key' => 'locker_widget_template' )
	);

	$wpdb->update( $wpdb->postmeta,
		array( 'meta_value' => 'Simple Light' ),
		array( 'meta_value' => 'Premium Dash', 'meta_key' => 'locker_widget_template' )
	);

	$wpdb->update( $wpdb->postmeta,
		array( 'meta_value' => 'Simple Dark' ),
		array( 'meta_value' => 'Premium Dark', 'meta_key' => 'locker_widget_template' )
	);


	$all_rows = $wpdb->get_results( "SELECT * FROM $wpdb->postmeta WHERE meta_key = 'wpsl_customized_data' AND `meta_value` LIKE '%templates/%' " );

	foreach( $all_rows as $row ){

		$post_id	= $row->post_id;
		$data			= get_post_meta( $post_id, 'wpsl_customized_data' );

		if( $data[0] ){

			$changed = false;
			foreach ($data[0] as $sel=>$styles){

				foreach( $styles as $prop=>$val ){

					if( strpos($val, '/templates/') !== false ){
						$changed =  true;
						$data[0][$sel][$prop] = str_replace(array(
							'Simple',
							'Premium%20Dash',
							'Premium%20Dashing',
							'Premium%20Dark'
						), array(
							'Basic',
							'Simple%20Light',
							'Simple%20Light',
							'Simple%20Dark'
						), $val);
					}

				}

			}

			if( $changed ){
				update_post_meta( $post_id, 'wpsl_customized_data', $data[0] );
			}

		}

	}

}

register_activation_hook( WPSL_FILE, 'wpslcnc_update_old_db' );


function wpsl_hide_upgrade_notice_func()
{
	update_option( 'wpsl_hide_premium_cta', true );
}

add_action( 'wp_ajax_wpsl_hide_upgrade_notice', 'wpsl_hide_upgrade_notice_func' );

/**
 * clear transient when updating license key
 */
function wpsl_clear_transient_for_license( $option, $old_value, $value )
{
	if( $option == 'wpsl_license_key' ){
		delete_transient('wpsl_license_status');
	}
}
add_action( 'updated_option', 'wpsl_clear_transient_for_license', 10, 3 );

/**
 * Keys for encryption
 */
$db_key	=	get_option("wpsl_unique_keys");

if( $db_key == '' ){
	$encryption_key		=	$_SERVER['DOCUMENT_ROOT'].$_SERVER['SERVER_SOFTWARE'].$_SERVER['PHP_SELF'].$_SERVER['SERVER_ADDR'];
}else{
	$encryption_key		=	$db_key;
}

define( 'WPSL_KEY', $encryption_key );

/**
 * Include usefull scripts
 */
require_once( WPSL_ROOT_DIR . 'admin/widgets-post-type-and-metabox.php' );
require_once( WPSL_ROOT_DIR . 'libs/functions.php' );
require_once( WPSL_ROOT_DIR . 'libs/class-form-builder.php' );
require_once( WPSL_ROOT_DIR . 'admin/libs/class-hd-wp-metabox-api.php' );
require_once( WPSL_ROOT_DIR . 'admin/libs/all_widget.php' );
require_once( WPSL_ROOT_DIR . 'admin/libs/preview_template_ajax.php' );
require_once( WPSL_ROOT_DIR . 'admin/settings.php' );
require_once( WPSL_ROOT_DIR . 'libs/shortcode.php' );
require_once( WPSL_ROOT_DIR . 'libs/unlock-keys.php' );
require_once( WPSL_ROOT_DIR . 'libs/lead_collection.php' );
require_once( WPSL_ROOT_DIR . 'libs/stats_and_reports.php' );
require_once( WPSL_ROOT_DIR . 'libs/init_social_networks.php' );
require_once( WPSL_ROOT_DIR . 'vendor/autoload.php' );

if( !class_exists('MailChimp') )
	require_once( WPSL_ROOT_DIR . 'libs/email_integrations/mailchimp.php' );

if( !class_exists('ActiveCampaign') )
	require_once( WPSL_ROOT_DIR . 'libs/email_integrations/activecampaign/ActiveCampaign.class.php' );

if( !class_exists('AWeberAPI') )
	require_once( WPSL_ROOT_DIR . 'libs/email_integrations/aweber/aweber.php' );

if( !class_exists('Ctct') ){
	require_once( WPSL_ROOT_DIR . 'libs/email_integrations/constantcontact/autoload.php' );
	require_once( WPSL_ROOT_DIR . 'libs/email_integrations/constantcontact/Ctct/autoload.php' );
}

/**
 * Require Infusionsoft SDK
 */
function wpsl_dependencies() {
  if( ! is_plugin_active( 'infusionsoft-sdk/infusionsoft-sdk.php' ))
    echo '<div class="error"><p>Warning: Capture and Convert needs <a href="https://wordpress.org/plugins/infusionsoft-sdk/" target="_blank">Infusionsoft SDK</a> to export leads to infusionsoft</p></div>';
}
// add_action( 'admin_notices', 'wpsl_dependencies' );


/**
 * Enqueues
 */
function wpsl_enqueues() {
	wp_enqueue_style( 'fontawesome', WPSL_ROOT_URL . 'assets/css/fa_all.css' );
  wp_enqueue_style( 'capture-and-convert', WPSL_ROOT_URL . 'assets/css/wpsl_stye.css' );
	wp_enqueue_style( 'dashicons' );
}
add_action( 'wp_enqueue_scripts', 'wpsl_enqueues' );


/**
 * Initialize Lead Collection and Stats/Reports
 */
$wpsl_leads	=	new WPSL_Leads;
$wpsl_leads->init();

$wpsl_stats	=	new WPSL_Stats_and_Reports;
$wpsl_stats->init();


/**
 * Export Leads
 */
function wpsl_export_leads(){

	global $wpsl_leads;

	$license_key	= sanitize_text_field($_POST['wpsl_license_key']);
	$export				= sanitize_text_field($_GET['wpsl_export']);

	if( isset($license_key) ){
		delete_transient( 'wpsl_license_status' );
	}

	if( $export != '' ){
		$wpsl_leads->export_leads($export);
	}

}

add_action( 'admin_init', 'wpsl_export_leads' );


/**
 * Generate cache for email integrations' lists
 */
function wpsl_generate_lists_cache(){

	global $wpsl_leads;

	$gen_lists = sanitize_text_field($_GET['wpsl_generate_lists']);

	if( $gen_lists != '' ){

		switch( $gen_lists ){

			case 'constantcontact':
				update_option('wpsl_constantcontact_cache_lists', $wpsl_leads->list_cc_lists());
			break;

			case 'mailchimp':
				update_option('wpsl_mailchimp_cache_lists', $wpsl_leads->list_mc_lists());
			break;

			case 'activecampaign':
				update_option('wpsl_activecampaign_cache_lists', $wpsl_leads->list_ac_lists());
			break;

			case 'aweber':
				update_option('wpsl_aweber_cache_lists', $wpsl_leads->list_aw_lists());
			break;

			case 'drip':
				update_option('wpsl_drip_cache_campaigns', $wpsl_leads->list_drip_campaigns());
			break;

			case 'hubspot':
				update_option('wpsl_hubspot_cache_lists', $wpsl_leads->list_hubspot_lists());
			break;

			case 'campaignmonitor':
				update_option('wpsl_campaignmonitor_cache_lists', $wpsl_leads->list_campaignmonitor_lists());
			break;

			case 'getresponse':
				update_option('wpsl_getresponse_cache_lists', $wpsl_leads->list_getresponse_lists());
			break;

			case 'convertkit':
				update_option('wpsl_convertkit_cache_forms', $wpsl_leads->list_convertkit_forms());
			break;

		}

	}

}

add_action( 'admin_init', 'wpsl_generate_lists_cache', 1 );


function wpsl_wp_enqueue_admin_scripts()
{

	wp_enqueue_style( 'select2', WPSL_ROOT_URL . 'admin/css/select2.min.css' );
	wp_enqueue_script( 'select2', WPSL_ROOT_URL . 'admin/js/select2.min.js', array('jquery') );

	wp_enqueue_style( 'fontawesome', WPSL_ROOT_URL . 'assets/css/fa_all.css' );
	wp_enqueue_script('jquery-ui-tooltip');
	wp_enqueue_media();
}
add_action( 'admin_enqueue_scripts', 'wpsl_wp_enqueue_admin_scripts' );




function wpsl_cnc_twitter_cb_rewrite(){
	add_rewrite_tag('%wpsl_cnc_twitter_cb%', '([^&]+)');
	add_rewrite_rule('^wpsl_cnc_twitter_cb$', 'index.php?wpsl_cnc_twitter_cb=true', 'top');
}
add_action('init', 'wpsl_cnc_twitter_cb_rewrite');

function wpsl_cnc_tw_query_vars( $query_vars ){
	$query_vars[] = 'wpsl_cnc_twitter_cb';
	return $query_vars;
}
add_filter( 'query_vars', 'wpsl_cnc_tw_query_vars' );


/**
 * Save 'total_impressions' meta key if not already exists (Fix for 'widgets not
 * showing in Stats and Reports')
 */
function wpsl_save_total_imp_mkey($post_id) {

	// If this is a revision, get real post ID
	if ( $parent_id = wp_is_post_revision( $post_id ) )
		$post_id = $parent_id;

	// Update total_impressions if not exists
	if( ! get_post_meta( $post_id, 'total_impressions', true ) ){
		update_post_meta($post_id, 'total_impressions', 0);
	}

}
add_action( 'save_post', 'wpsl_save_total_imp_mkey' );


/**
 * Add google fonts json list in header
 */
function wpsl_cnc_gfonts_var_in_head() {

	global $wpsl_cnc_gfonts_json;
	echo '<script type="text/javascript">var wpsl_cnc_gfonts_json = '.$wpsl_cnc_gfonts_json.'</script>';

}
add_action( 'admin_head', 'wpsl_cnc_gfonts_var_in_head' );



// function wpsl_memory_check_tmptest()
// {
// 	var_dump(memory_get_peak_usage());
// }
// add_action( 'shutdown', 'wpsl_memory_check_tmptest' );
