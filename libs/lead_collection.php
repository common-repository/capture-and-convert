<?php
/**
 * Lead Collection
 *
 * This Class collects leads when someone unlocks the content by either liking,
 * sharing and manually entering their email/name
 *
 * @class			WPSL_Leads
 * @since			0.1
 * @package		WP_Social_Locker
 * @category	Class
 * @author		Rizwan <m.rizwan_47@yahoo.com>
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Ctct\Components\Contacts\Contact;
use Ctct\ConstantContact;
use Ctct\Exceptions\CtctException;


class WPSL_Leads{

	/**
	 * Database Table Name
	 *
	 * @since 0.1
	 * @access private
	 * @var string
	 */
	private $table_name;


	/**
	 * Initialize
	 *
	 * @since 0.1
	 */
	public function init(){

		global $wpdb;

		/**
		 * Setting Table Name
		 */
		$this->table_name	=	$wpdb->prefix . "wpsl_leads";

		/**
		 * Create table on plugin activation
		 */
		register_activation_hook( WPSL_FILE, array( $this, 'create_table' ) );

		/**
		 * Hook in wp_ajax to collect leads
		 */
		add_action( 'wp_ajax_wpsl_collect_lead', array( $this, 'collect_ajaxed_leads' ) );
		add_action( 'wp_ajax_nopriv_wpsl_collect_lead', array( $this, 'collect_ajaxed_leads' ) );


	}

	/**
	 * Collect Leads from ajax
	 */
	public function collect_ajaxed_leads(){

		$data		=	$_POST['data']; // NOTE: Sanitized array elements individually below
		$source	=	sanitize_text_field($_POST['source']);

		$this->insert( sanitize_email($data['email']), sanitize_text_field($data['first_name']).' '.sanitize_text_field($data['last_name']), $source );

		die;

	}

	/**
	 * Create DB Table
	 *
	 * @since 0.1
	 */
	public function create_table(){

		global $wpdb;

		$charset_collate	= $wpdb->get_charset_collate();
		$table_name				=	$this->table_name;

		$sql	=	"CREATE TABLE $table_name (
			`id` INT(9) NOT NULL AUTO_INCREMENT,
			`name` VARCHAR(255) NOT NULL,
			`email` VARCHAR(255) NOT NULL,
			`source` ENUM('facebook','twitter','youtube','manual') NOT NULL,
			`time` DATETIME NOT NULL,
			PRIMARY KEY (`id`)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

	}

	/**
	 * Check if email with same source already exists
	 *
	 * @since 0.2
	 * @access private
	 *
	 * @param string $email			Email Address
	 * @param string $source		Source of lead
	 */
	private function exists( $email, $source='manual' ){

		global $wpdb;

		$count			=	$wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $this->table_name WHERE `email` = %s AND `source` = %s ", $email, $source ) );

		return ($count > 0);

	}

	/**
	 * Insert Entry
	 *
	 * @since 0.1
	 * @access public
	 *
	 * @param string $email			Email Address
	 * @param string $name			Full Name
	 * @param string $source		Source of lead
	 */
	public function insert( $email, $name, $source='manual' ){

		global $wpdb;

		if( $email == '' )
			return false;

		if( ! in_array( $source, array('facebook','twitter','youtube','manual' ) ) )
			$source		=	'manual';

		if( $this->exists( $email, $source ) )
			return false;

		/**
		 * Filter lead data before inserting into database
		 *
		 * @since 0.1
		 */
		$lead	=	apply_filters( 'wpsl_lead_insert', array(
			'name'		=> $name,
			'email'		=> $email,
			'source'	=> $source
		) );

		$lead['time']	= current_time('mysql', 1);

		return $wpdb->insert( $this->table_name, $lead );

	}

	/**
	 * Get Leads
	 *
	 * Get all leads from database
	 * TODO: make it filter-able by date rane
	 *
	 * @since 0.1
	 * @access public
	 *
	 * @return array		Array of objects containing leads data
	 */
	function get_leads(){

		global $wpdb;

		$table_name	=	$this->table_name;
		$leads			=	$wpdb->get_results("SELECT * FROM $table_name ");

		/**
		 * Filter leads data when listing/exporting them
		 *
		 * @since 0.1
		 */
		return apply_filters( 'wpsl_leads_data', $leads );

	}

	/**
	 * Get number of leads
	 *
	 * @access public
	 * @return int number of leads
	 */
	public function count(){

		global $wpdb;

		return $wpdb->get_var("SELECT COUNT(*) FROM $this->table_name");

	}


	/**
	 * Export Leads
	 *
	 * Export all leads record into a csv file.
	 * TODO: make it filter-able by date rane
	 *
	 * @param string $type			Type of export (csv|mailchimp|activecampaign|infusionsoft|aweber)
	 *
	 * @since 0.1
	 * @deprecated 0.8
	 * @access public
	 */
	public function export_leads( $type ){

		if( !in_array( $type, array(
			'csv', 'mailchimp', 'activecampaign', 'infusionsoft', 'aweber', 'constantcontact'
		))) wp_die('Invalid Export Type');

		$leads			=	$this->get_leads();

		switch( $type ){

			case 'csv':

				header('Content-Type: text/csv');
				header('Content-Disposition: attachment; filename="wpsl_leads_'.date("m-j-Y_H-i-s").'.csv"');
				header('Pragma: no-cache');
				header('Expires: 0');

				// headers
				echo 'name,';
				echo 'email,';
				echo 'source,';
				echo 'time';
				echo PHP_EOL;

				foreach( $leads as $lead ){
					echo $lead->name.',';
					echo $lead->email.',';
					echo $lead->source.',';
					echo $lead->time;
					echo PHP_EOL;
				}

				exit;

			break;

			case 'mailchimp':

				$mailchimp_api	=	get_option('wpsl_mailchimp_api_key');
				$mailchimp_list	=	get_option('wpsl_mailchimp_list_id');

				try{

					$MailChimp			= new MailChimp($mailchimp_api);
					foreach( $leads as $lead ){
						$MailChimp->post("lists/$mailchimp_list/members", [
	            'email_address'	=> $lead->email,
	            'status'				=> 'subscribed',
							'merge_fields'	=> ['FNAME'	=> $lead->name]
	        	]);
					}

				}catch( Exception $e ){

					add_filter('wpslcnc_admin_errors', function($errors) use($e){
						$errors[] = array(
							'type'	=> 'error',
							'error'	=> 'Mailchimp: '.$e->getMessage()
						);
						return $errors;
					});

					return false;

				}

				return true;

			break;

			case 'activecampaign':

				$ac_api_url	=	get_option('wpsl_activecampaign_api_url');
				$ac_api_key	=	get_option('wpsl_activecampaign_api_key');
				$ac_list_id	=	get_option('wpsl_activecampaign_list_id');

				$ActiveCampaign			=	new ActiveCampaign($ac_api_url, $ac_api_key);

				foreach( $leads as $lead ){

					$contact_sync	=	$ActiveCampaign->api("contact/sync", array(
						"email"									=> $lead->email,
						"first_name"						=> $lead->name,
						"p[{$ac_list_id}]"			=> $ac_list_id,
						"status[{$ac_list_id}]"	=> 1
					));

					if( ! (int) $contact_sync->success ){
						wp_die( "Syncing contact failed. Error returned: " . $contact_sync->error );
					}

				}

				return true;

			break;


			case 'infusionsoft':

				if( ! is_plugin_active( 'infusionsoft-sdk/infusionsoft-sdk.php' )){
					echo '<div class="error"><p>Error: Capture and Convert needs <a href="https://wordpress.org/plugins/infusionsoft-sdk/" target="_blank">Infusionsoft SDK</a> to export leads to infusionsoft</p></div>';
					return false;
				}

				foreach( $leads as $lead ){

					$contact = new Infusionsoft_Contact();
					$contact->FirstName		= $lead->name;
					$contact->Email				= $lead->email;
					$contact->save();

				}

				return true;

			break;

			case 'aweber':

				$consumerKey				=	get_option('wpsl_aweber_consumer_key');
				$consumerSecret			=	get_option('wpsl_aweber_consumer_secret');
				// $account_id     		= get_option('wpsl_aweber_account_id');

				$list_id        		= get_option('wpsl_aweber_list_id');
				$accessKey					=	get_option('wpsl_aweber_accessKey');
				$accessSecret				=	get_option('wpsl_aweber_accessSecret');
				$requestTokenSecret	=	get_option('wpsl_aweber_request_token_secret');
				$callbackURL				= admin_url('admin.php?page=capture-and-convert-menu&tab=wpsl_leads_integration&section=aweber');

				try{

					$aweber							= new AWeberAPI($consumerKey, $consumerSecret);

					if( ! $requestTokenSecret || ! $accessKey || ! $accessSecret ){

						$oauth_token = sanitize_text_field($_GET['oauth_token']);

						if( $oauth_token == '' ){
							list($requestTokenKey, $requestTokenSecret) = $aweber->getRequestToken($callbackURL);
							$authorizationURL = $aweber->getAuthorizeUrl();
							update_option('wpsl_aweber_request_token_secret', $requestTokenSecret);
							wp_redirect($authorizationURL);
							exit;
						}

						$oauth_verifier  = sanitize_text_field($_GET['oauth_verifier']);

						$aweber->user->tokenSecret		= $requestTokenSecret;
				    $aweber->user->requestToken		= $oauth_token;
				    $aweber->user->verifier				= $oauth_verifier;
				    list($accessToken, $accessTokenSecret) = $aweber->getAccessToken();

						update_option('wpsl_aweber_accessKey', $accessToken);
						update_option('wpsl_aweber_accessSecret', $accessTokenSecret);

				    wp_redirect($callbackURL);
						exit;

					}

					$account = $aweber->getAccount($accessKey, $accessSecret);
					$account_id = $account->id;
					$listURL = "/accounts/{$account_id}/lists/{$list_id}";
					$list = $account->loadFromUrl($listURL);
					$subscribers = $list->subscribers;

					foreach( $leads as $lead ){

						try{
							$new_subscriber = $subscribers->create(array(
								'email'	=> $lead->email,
								'name'	=> ($lead->name == '' ? 'No Name' : $lead->name)
							));
						}catch(AWeberAPIException $exc){
							// TODO: Log errors
						}

					}

				}catch(AWeberAPIException $exc){

			    print "<h3>AWeberAPIException:</h3>";
			    print " <li> Type: $exc->type              <br>";
			    print " <li> Msg : $exc->message           <br>";
			    print " <li> Docs: $exc->documentation_url <br>";
			    print "<hr>";

					if( $exc->type == 'UnauthorizedError' ){
						delete_option('wpsl_aweber_accessKey');
						delete_option('wpsl_aweber_accessSecret');
						delete_option('wpsl_aweber_request_token_secret');
						echo '<a href="'.$callbackURL.'">try again</a>';
					}

			    exit(1);

				}


				return true;

			break;

			case 'constantcontact':

				$cc_api_key				=	get_option('wpsl_constantcontact_api_key');
				$cc_api_secret		=	get_option('wpsl_constantcontact_api_secret');
				$cc_access_token	=	get_option('wpsl_constantcontact_access_token');

				$cc								= new ConstantContact( $cc_api_key );

				try{
					$lists	= $cc->listService->getLists( $cc_access_token );
				}catch( CtctException $ex ){

					foreach( $ex->getErrors() as $error ){
						print_r($error);
					}

					if (!isset($lists)) {
						$lists = null;
					}

				}

				foreach( $leads as $lead ){

					$email	=	$lead->email;
					$name		=	$lead->name;
					$list		=	$lists[0];
					$listID	=	$list->id;

					$action = "Getting Contact By Email Address";

					try{

						$response	= $cc->contactService->getContacts($cc_access_token, array("email" => $email));

						if( empty($response->results) ){
							$action		= "Creating Contact";
							$contact	= new Contact();

							$contact->addEmail($email);
							$contact->addList($listID);
							$contact->first_name = $name;
							// $contact->last_name = $_POST['last_name'];
							$returnContact = $cc->contactService->addContact($cc_access_token, $contact, true);
						}else{
							$action		= "Updating Contact";
							$contact	= $response->results[0];

							if( $contact instanceof Contact ){
								$contact->addList($listID);
								$contact->first_name = $name;
								// $contact->last_name = $_POST['last_name'];
								$returnContact = $cc->contactService->updateContact($cc_access_token, $contact, true);
							}else{
								$e = new CtctException();
								$e->setErrors(array("type", "Contact type not returned"));
								throw $e;
							}
						}

					}catch( CtctException $ex ){
						echo '<span class="label label-important">Error ' . $action . '</span>';
						echo '<div class="container alert-error"><pre class="failure-pre">';
						print_r($ex->getErrors());
						echo '</pre></div>';
						die();
					}

				}

				return true;

			break;

		}

	}

	/**
	 * Generate Mailchimp lists cache
	 *
	 * @return array Array of lists
	 */
	public function list_mc_lists(){

		$mailchimp_api	=	get_option('wpsl_mailchimp_api_key');

		$lists		=	array();
		if( $mailchimp_api != '' ){

			try{
				$mc_api		= new MailChimp($mailchimp_api);
				$mc_respo	=	$mc_api->get("lists");
			}catch( Exception $e ){

				add_filter('wpslcnc_admin_errors', function($errors) use($e){
					$errors[] = array(
						'type'	=> 'error',
						'error'	=> 'Mailchimp: '.$e->getMessage()
					);
					return $errors;
				});

				return false;

			}

			if( count($mc_respo['lists']) > 0 ){

				foreach( (array) $mc_respo['lists'] as $list ){
					$lists[$list['id']]	=	$list['name'];
				}

			}
		}

		return $lists;

	}

	/**
	 * Generate ActiveCampaign lists cache
	 *
	 * @return array Array of lists
	 */
	public function list_ac_lists(){

		$ac_api_url	=	get_option('wpsl_activecampaign_api_url');
		$ac_api_key	=	get_option('wpsl_activecampaign_api_key');

		if( $ac_api_url != '' && $ac_api_key != '' ){

			$ActiveCampaign	=	new ActiveCampaign($ac_api_url, $ac_api_key);

			$activecampaign_lists	=	$ActiveCampaign->api("list/list_", [
				'ids'  => 'all'
			]);

			if( $activecampaign_lists->error != '' ){

				$error_msg = $activecampaign_lists->error;
				add_filter('wpslcnc_admin_errors', function($errors) use($error_msg){
					$errors[] = array(
						'type'	=> 'error',
						'error'	=> 'ActiveCampaign: '.$error_msg
					);
					return $errors;
				});

				return false;

			}

			$rtt = array();
			if( $activecampaign_lists->result_message == 'Success: Something is returned' ){
				foreach( $activecampaign_lists as $k=>$v){
					if( is_numeric($k) )
						$rtt[$v->id]	= $v->name.' ('.$v->subscriber_count.')';
				}
			}else{
				add_filter('wpslcnc_admin_errors', function($errors) use($error_msg){
					$errors[] = array(
						'type'	=> 'warning',
						'error'	=> 'ActiveCampaign: It seems there are no lists available.'
					);
					return $errors;
				});
			}

		}

		return $rtt;

	}

	/**
	 * Generate ConstantContact lists cache
	 *
	 * @return array Array of lists
	 */
	public function list_cc_lists(){

		$cc_api_key				=	get_option('wpsl_constantcontact_api_key');
		$cc_api_secret		=	get_option('wpsl_constantcontact_api_secret');
		$cc_access_token	=	get_option('wpsl_constantcontact_access_token');

		if( $cc_api_key == '' )
			return false;


		$cc								= new ConstantContact( $cc_api_key );

		try{
			$lists	= $cc->listService->getLists( $cc_access_token );
		}catch( CtctException $ex ){

			$err_msgs = $ex->getMessage();
			add_filter('wpslcnc_admin_errors', function($errors) use($err_msgs){
				$errors[] = array(
					'type'	=> 'error',
					'error'	=> 'ConstantContact: '.$err_msgs
				);
				return $errors;
			});

			return false;

			if (!isset($lists)) {
				$lists = null;
			}

		}

		// return $lists;

		$cc_lists		=	array();
		if( is_array($lists) && count($lists) > 0 ){
			foreach( (array) $lists as $list ){
				$cc_lists[$list->id]	=	$list->name;
			}
		}

		return $cc_lists;

	}

	/**
	 * Generate Drip campaigns cache
	 *
	 * @return array Array of campaigns
	 */
	public function list_drip_campaigns(){

		$drip_api_key			= get_option('wpsl_drip_api_key');
		$drip_account_id	= get_option('wpsl_drip_account_id');

		if( $drip_api_key != '' && $drip_account_id > 0 ){

			try {

				$dripclient = new \Drip\Client($drip_api_key, $drip_account_id);

				$drip_campaigns_r = $dripclient->get_campaigns(array(
					'status' => 'all'
				));

				if( ! $drip_campaigns_r->is_success()){

					$err_msg = '';
					foreach( $drip_campaigns_r->get_errors() as $error ){
						$err_msg .= $error->get_message().' ';
					}

					add_filter('wpslcnc_admin_errors', function($errors) use($err_msg){
						$errors[] = array(
							'type'	=> 'error',
							'error'	=> 'Drip: '.$err_msg
						);
						return $errors;
					});

					return false;

				}else{

					$drip_campaigns = $drip_campaigns_r->get_contents();

					$dripcamps	=	array();
					if( is_array($drip_campaigns['campaigns']) && count($drip_campaigns['campaigns']) > 0 ){
						foreach( (array) $drip_campaigns['campaigns'] as $camp ){
							$dripcamps[$camp['id']]	=	$camp['name'];
						}
					}

					return $dripcamps;

				}

			}catch( Exception $e ){
				// TODO:
			}

		}
	}


	/**
	 * Generate HubSpot lists cache
	 *
	 * @return array Array of lists
	 */
	public function list_hubspot_lists(){

		$hubspot_api	=	get_option('wpsl_hubspot_api_key');

		$lists	=	array();
		if( $hubspot_api != '' ){

			$hs_lists_res	=	wp_remote_get('https://api.hubapi.com/contacts/v1/lists?count=250&hapikey='.urlencode($hubspot_api));
			$hs_lists			=	json_decode($hs_lists_res['body'], true);

			if( $hs_lists['status'] == 'error' ){

				$err_msg = $hs_lists['message'];
				add_filter('wpslcnc_admin_errors', function($errors) use($err_msg){
					$errors[] = array(
						'type'	=> 'error',
						'error'	=> 'Hubspot: '.$err_msg
					);
					return $errors;
				});

				return false;

			}

			if( count($hs_lists['lists']) > 0 ){

				foreach( (array) $hs_lists['lists'] as $list ){
					$lists[$list['listId']]	=	$list['name'];
				}

			}

		}

		return $lists;

	}


	/**
	 * Generate ConvertKit forms cache
	 *
	 * @return array Array of forms
	 */
	public function list_convertkit_forms()
	{

		$api_key	= get_option('wpsl_convertkit_api_key');
		$lists		= array();

		if( $api_key != '' ){

			$endpoint = 'https://api.convertkit.com/v3';

			$resp	= wp_remote_get( $endpoint.'/forms?api_key='.$api_key );

			$lists_r	= json_decode($resp['body'], true);

			if( $lists_r['error'] != '' ){

				$err_msg = $lists_r['error'] . '. ' . $lists_r['message'];
				add_filter('wpslcnc_admin_errors', function($errors) use($err_msg){
					$errors[] = array(
						'type'	=> 'error',
						'error'	=> 'ConvertKit: '.$err_msg
					);
					return $errors;
				});

				return false;

			}

			foreach( $lists_r['forms'] as $list ){
				$lists[$list['id']]	=	$list['name'];
			}

		}

		return $lists;

	}


	/**
	 * Generate GetResponse lists cache
	 *
	 * @return array Array of lists
	 */
	public function list_getresponse_lists()
	{

 		$api_key	= get_option('wpsl_getresponse_api_key');
		$lists		= array();

 		if( $api_key != '' ){

 			$endpoint = 'https://api.getresponse.com/v3';
 			$resp	= wp_remote_get( $endpoint.'/campaigns', array(
				'headers'	=> array(
					'X-Auth-Token'	=> 'api-key '.$api_key,
					'Content-Type'	=> 'application/json'
				)
			) );

 			$lists_r	= json_decode($resp['body']);

			if( ! is_array($lists_r) ){

				$error_msg = $lists_r->message;

				add_filter('wpslcnc_admin_errors', function($errors) use($error_msg){
					$errors[] = array(
						'type'	=> 'error',
						'error'	=> 'GetResponse: '.$error_msg
					);
					return $errors;
				});

				return false;

			}

 			foreach( $lists_r as $list ){
				$lists[$list->campaignId]	=	$list->name;
			}

 		}

 		return $lists;

 	}


	/**
	 * Generate Campaign Monitor lists cache
	 *
	 * @return array Array of lists
	 */
	public function list_campaignmonitor_lists()
	{

		$api_key	= get_option('wpsl_campaignmonitor_api_key');
		$clientid	= get_option('wpsl_campaignmonitor_client_id');
		$lists		= array();

		if( $api_key != '' && $clientid != '' ){

			$wrap = new CS_REST_Clients( $clientid,  array(
				'api_key' => $api_key
			));

			$result	= $wrap->get_lists();
			if( $result->was_successful() ){

				foreach( $result->response as $cm_list ){
					$lists[$cm_list->ListID]	= $cm_list->Name;
				}

			}else{

				$err_msg = $result->response->Message;
				add_filter('wpslcnc_admin_errors', function($errors) use($err_msg){
					$errors[] = array(
						'type'	=> 'error',
						'error'	=> 'Campaign Monitor: '.$err_msg
					);
					return $errors;
				});

				return false;

			}

		}

		return $lists;

	}


	/**
	 * Generate Aweber lists cache
	 *
	 * @return array Array of lists or may redirect for authorization
	 */
	public function list_aw_lists(){

		$consumerKey				=	get_option('wpsl_aweber_consumer_key');
		$consumerSecret			=	get_option('wpsl_aweber_consumer_secret');

		$accessKey					=	get_option('wpsl_aweber_accessKey');
		$accessSecret				=	get_option('wpsl_aweber_accessSecret');
		$requestTokenSecret	=	get_option('wpsl_aweber_request_token_secret');
		$callbackURL				= admin_url('admin.php?page=capture-and-convert-menu&tab=wpsl_leads_integration&section=aweber');

		try{

			$aweber							= new AWeberAPI($consumerKey, $consumerSecret);

			if( ! $requestTokenSecret || ! $accessKey || ! $accessSecret ){

				$oauth_token = sanitize_text_field($_GET['oauth_token']);

				if( $oauth_token == '' ){
					list($requestTokenKey, $requestTokenSecret) = $aweber->getRequestToken($callbackURL);
					$authorizationURL = $aweber->getAuthorizeUrl();
					update_option('wpsl_aweber_request_token_secret', $requestTokenSecret);
					wp_redirect($authorizationURL);
					exit;
				}

				$oauth_verifier = sanitize_text_field($_GET['oauth_verifier']);

				$aweber->user->tokenSecret		= $requestTokenSecret;
				$aweber->user->requestToken		= $oauth_token;
				$aweber->user->verifier				= $oauth_verifier;
				list($accessToken, $accessTokenSecret) = $aweber->getAccessToken();

				update_option('wpsl_aweber_accessKey', $accessToken);
				update_option('wpsl_aweber_accessSecret', $accessTokenSecret);

				wp_redirect($callbackURL);
				exit;

			}

			$account = $aweber->getAccount($accessKey, $accessSecret);
			$account_id = $account->id;
			$listURL = "/accounts/{$account_id}/lists";
			$lists_o = $account->loadFromUrl($listURL);

			$lists	= array();
			foreach( (array) $lists_o->data['entries'] as $list_r ){
				$lists[$list_r['id']]	= $list_r['name'];
			}

			return $lists;

		}catch(AWeberAPIException $exc){

			print "<h3>AWeberAPIException:</h3>";
			print " <li> Type: $exc->type              <br>";
			print " <li> Msg : $exc->message           <br>";
			print " <li> Docs: $exc->documentation_url <br>";
			print "<hr>";

			if( $exc->type == 'UnauthorizedError' ){
				delete_option('wpsl_aweber_accessKey');
				delete_option('wpsl_aweber_accessSecret');
				delete_option('wpsl_aweber_request_token_secret');
				echo '<a href="'.$callbackURL.'">try again</a>';
			}

			exit(1);

		}


	}

}
