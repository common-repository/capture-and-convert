<?php
/**
 * Stats and Reports
 *
 * This Class collects stats of widgets visibility, click count and ratio etc.
 * and also generates graph based on that
 *
 * @class			WPSL_Leads
 * @since			0.2
 * @package		WP_Social_Locker
 * @category	Class
 * @author		Rizwan <m.rizwan_47@yahoo.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WPSL_Stats_and_Reports{

	/**
	 * Database Table Name
	 *
	 * @since 0.2
	 * @access private
	 * @var string
	 */
	private $table_name;


	/**
	 * Initialize
	 *
	 * @since 0.2
	 */
	public function init(){

		global $wpdb;

		/**
		 * Setting Table Name
		 */
		$this->table_name	=	$wpdb->prefix . "wpsl_stats";

		/**
		 * Create table on plugin activation
		 */
		register_activation_hook( WPSL_FILE, array( $this, 'create_table' ) );

		/**
		 * Hook in wp_ajax to add stats via ajax
		 */
		add_action( 'wp_ajax_wpsl_add_stats', array( $this, 'ajaxed_stats' ) );
		add_action( 'wp_ajax_nopriv_wpsl_add_stats', array( $this, 'ajaxed_stats' ) );

	}

	/**
	 * Collect ajaxed stats
	 */
	public function ajaxed_stats(){

		$widget_id		=	intval(sanitize_text_field($_POST['widget_id']));
		$widget_type	=	sanitize_text_field($_POST['widget_type']);
		$source				=	sanitize_text_field($_POST['source']);
		$action_type	=	sanitize_text_field($_POST['action_type']);

		// Insert method is handling validaation etc
		$this->insert( $widget_id, $widget_type, $source, $action_type );

		die;

	}

	/**
	 * Create DB Table
	 *
	 * @since 0.2
	 */
	public function create_table(){

		global $wpdb;

		$charset_collate	= $wpdb->get_charset_collate();
		$table_name				=	$this->table_name;

		$sql	=	"CREATE TABLE IF NOT EXISTS $table_name (
			`id` INT(14) NOT NULL AUTO_INCREMENT,
			`widget_id` INT(9) NOT NULL,
			`widget_type` ENUM('share','follow','email','floating_widget') NOT NULL,
			`action_type` ENUM('view','impression') NOT NULL,
			`source` ENUM('facebook_like','facebook_share','twitter_tweet','twitter_follow','youtube_subscribe','manual_email','none') NOT NULL,
			`time` DATETIME NOT NULL,
			PRIMARY KEY (`id`)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

	}

	/**
	 * Insert Entry
	 *
	 * @since 0.2
	 * @access public
	 *
	 * @param int					$widget_id			Widget id
	 * @param string			$widget_type		Widget Type ('share','follow' or 'email')
	 * @param string|null	$source					Source (Facebook Like, Share, Tweet, follow etc) | Null if action is view
	 * @param string			$action_type		Type of action ('view' or 'impression')
	 */
	public function insert( $widget_id, $widget_type, $source=NULL, $action_type='view' ){

		global $wpdb;

		if( $widget_id > 0 ){

			if( ! in_array( $action_type, array('view','impression') ) )
				$action_type		=	'view';

			if( ! in_array( $widget_type, array('share','follow','email') ) )
				return false;

			if( $source === NULL || ! in_array( $source, array('facebook_like','facebook_share','twitter_tweet','twitter_follow','youtube_subscribe','manual_email') ) )
				$source	=	'none';

			$meta_key	=	'total_'.$action_type.'s';
			$total		=	intval(get_post_meta( $widget_id, $meta_key, true ));
			$total++;
			update_post_meta( $widget_id, $meta_key, $total );

			return $wpdb->insert( $this->table_name, array(
				'widget_id'		=> $widget_id,
				'widget_type'	=> $widget_type,
				'action_type'	=> $action_type,
				'source'			=> $source,
				'time'				=> current_time('mysql', 1)
			));

		}

		return false;

	}

	/**
	 * Get Stats of Widget
	 *
	 * @since 0.2
	 * @access public
	 *
	 * @param int			$widget_id			Widget id
	 * @param string	$widget_type		Widget Type ('share','follow' or 'email')
	 *
	 * @return array	Array containing number of views, impressions and ratio
	 */
	function get_widget_stats( $widget_id=NULL, $widget_type=NULL ){

		if( !$widget_id )
			$widget_id	=	get_the_ID();
		// global $wpdb;
		//
		// $table_name	=	$this->table_name;
		//
		// $views				=	$wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $table_name WHERE widget_id = %d AND widget_type = %s AND action_type = 'view' ", $widget_id, $widget_type ) );
		// $impressions	=	$wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $table_name WHERE widget_id = %d AND widget_type = %s AND action_type = 'impression' ", $widget_id, $widget_type ) );
		$views				=	intval(get_post_meta( $widget_id, 'total_views', true ));
		$impressions	=	intval(get_post_meta( $widget_id, 'total_impressions', true ));

		if( $views > 0 ){
			$ratio				=	(float) number_format(($impressions	/	$views	*	100), 2);
		}else{
			$ratio				=	0.00;
		}

		if( is_nan($ratio) )
			$ratio	=	0.00;

		return array(
			'views'				=>	$views,
			'impressions'	=>	$impressions,
			'ratio'				=>	$ratio
		);

	}

	/**
	 * Generate json data for Charts
	 *
	 * TODO Add filter by option
	 *
	 * @param 	array		$args Description
	 * @return	string	JSON String
	 */
	public function generate_charts_data( $args ){

		global $wpdb;

		$table_name		=	$this->table_name;

		// $filter				=	$args['filter'];
		$date_range1	=	$args['date_range1'];
		$date_range2	=	$args['date_range2'];

		$begin				= new DateTime( $date_range1 );
		$end					= new DateTime( $date_range2 );
		$end					=	$end->modify( '+1 day' );
		$interval			= DateInterval::createFromDateString('1 day');
		$period				= new DatePeriod($begin, $interval, $end);

		$labels				=	array();
		$datasets_arr	=	array();

		$datasets_arr['views']['label']														=	'Views';
		$datasets_arr['views']['backgroundColor']									=	'rgba(0, 0, 0, 0.2)';
		$datasets_arr['views']['borderColor']											=	'rgba(0, 0, 0, 1)';
		$datasets_arr['views']['borderWidth']											=	'1';

		$datasets_arr['impressions']['label']											=	'Impressions';
		$datasets_arr['impressions']['backgroundColor']						=	'rgba(0, 255, 0, 0.2)';
		$datasets_arr['impressions']['borderColor']								=	'rgba(0, 255, 0, 1)';
		$datasets_arr['impressions']['borderWidth']								=	'1';

		$datasets_arr['facebook_likes']['label']									=	'Facebook Likes';
		$datasets_arr['facebook_likes']['backgroundColor']				=	'rgba(0, 69, 255, 0.2)';
		$datasets_arr['facebook_likes']['borderColor']						=	'rgba(0, 69, 255, 1)';
		$datasets_arr['facebook_likes']['borderWidth']						=	'1';

		$datasets_arr['facebook_shares']['label']									=	'Facebook Shares';
		$datasets_arr['facebook_shares']['backgroundColor']				=	'rgba(0, 0, 255, 0.2)';
		$datasets_arr['facebook_shares']['borderColor']						=	'rgba(0, 0, 255, 1)';
		$datasets_arr['facebook_shares']['borderWidth']						=	'1';

		$datasets_arr['twitter_tweets']['label']									=	'Twitter Tweets';
		$datasets_arr['twitter_tweets']['backgroundColor']				=	'rgba(29, 161, 242, 0.2)';
		$datasets_arr['twitter_tweets']['borderColor']						=	'rgba(29, 161, 242, 1)';
		$datasets_arr['twitter_tweets']['borderWidth']						=	'1';

		$datasets_arr['twitter_follows']['label']									=	'Twitter Follows';
		$datasets_arr['twitter_follows']['backgroundColor']				=	'rgba(0, 149, 220, 0.2)';
		$datasets_arr['twitter_follows']['borderColor']						=	'rgba(0, 149, 220, 1)';
		$datasets_arr['twitter_follows']['borderWidth']						=	'1';

		$datasets_arr['youtube_subscribes']['label']							=	'Youtube Subscribes';
		$datasets_arr['youtube_subscribes']['backgroundColor']		=	'rgba(205, 32, 31, 0.2)';
		$datasets_arr['youtube_subscribes']['borderColor']				=	'rgba(205, 32, 31, 1)';
		$datasets_arr['youtube_subscribes']['borderWidth']				=	'1';

		$datasets_arr['manual_emails']['label']										=	'Manual Email Signups';
		$datasets_arr['manual_emails']['backgroundColor']					=	'rgba(255, 169, 48, 0.2)';
		$datasets_arr['manual_emails']['borderColor']							=	'rgba(255, 169, 48, 1)';
		$datasets_arr['manual_emails']['borderWidth']							=	'1';

		$widget_id	=	$args['widget_id'];
		if( $widget_id > 0 && is_numeric($widget_id) ){
			$sql_qr_append = " AND `widget_id`='$widget_id' ";
		}else{
			$sql_qr_append = '';
		}

		foreach( $period as $dt ){

			$inst_date		=	$dt->format( "Y-m-d" );
			$labels[]			=	"'".$inst_date."'";

			$views				=	$wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $table_name WHERE action_type = 'view' AND DATE(`time`) = %s $sql_qr_append ", $inst_date ) );
			$datasets_arr['views']['data'][]	=	$views;

			$impressions	=	$wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $table_name WHERE action_type = 'impression' AND DATE(`time`) = %s $sql_qr_append ", $inst_date ) );
			$datasets_arr['impressions']['data'][]	=	$impressions;

			$facebook_likes	=	$wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $table_name WHERE source = 'facebook_like' AND DATE(`time`) = %s $sql_qr_append ", $inst_date ) );
			$datasets_arr['facebook_likes']['data'][]	=	$facebook_likes;

			$facebook_shares	=	$wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $table_name WHERE source = 'facebook_share' AND DATE(`time`) = %s $sql_qr_append ", $inst_date ) );
			$datasets_arr['facebook_shares']['data'][]	=	$facebook_shares;

			$twitter_tweets	=	$wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $table_name WHERE source = 'twitter_tweet' AND DATE(`time`) = %s $sql_qr_append ", $inst_date ) );
			$datasets_arr['twitter_tweets']['data'][]	=	$twitter_tweets;

			$twitter_follows	=	$wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $table_name WHERE source = 'twitter_follow' AND DATE(`time`) = %s $sql_qr_append ", $inst_date ) );
			$datasets_arr['twitter_follows']['data'][]	=	$twitter_follows;

			$youtube_subscribes	=	$wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $table_name WHERE source = 'youtube_subscribe' AND DATE(`time`) = %s $sql_qr_append ", $inst_date ) );
			$datasets_arr['youtube_subscribes']['data'][]	=	$youtube_subscribes;

			$manual_emails	=	$wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $table_name WHERE source = 'manual_email' AND DATE(`time`) = %s $sql_qr_append ", $inst_date ) );
			$datasets_arr['manual_emails']['data'][]	=	$manual_emails;

		}

		$datasets	=	array();
		foreach( $datasets_arr as $ds ){

			$datasets[]	=	"{
				label: '".$ds['label']."',
				data: [".implode(',', $ds['data'])."],
				backgroundColor: '".$ds['backgroundColor']."',
				borderColor: '".$ds['borderColor']."',
				borderWidth: ".$ds['borderWidth']."
			}";

		}

		return "{
        labels: [".implode(',', $labels)."],
        datasets: [".implode(',', $datasets)."]
    }";
	}


}
