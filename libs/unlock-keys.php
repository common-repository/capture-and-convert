<?php
/**
 * Unlock Keys enqueues
 */
function wpsl_unlock_enqueues() {

	$api_key	=	get_option("wpsl_fb_api_key");

	wp_enqueue_script( 'wpsl_unlock_keys', WPSL_ROOT_URL . 'assets/js/wpsl_unlock_keys.js', array('jquery'), '1.0.0', true );
	wp_localize_script('wpsl_unlock_keys', 'wpsl_ajax', array(
		'ajaxurl' 				=> admin_url('admin-ajax.php'),
		'plugin_url'			=> WPSL_ROOT_URL,
		'plugin_path'			=> WPSL_ROOT_DIR,
		'action'					=> 'wpsl_unlock_content',
		'options'					=> array (
			// TODO
			// 'quality'				=> get_option('wpsc_imgoptm_quality'),
			// 'lossless'			=> get_option('wpsc_imgoptm_losslessly'),
			// 'remove_meta'		=> get_option('wpsc_imgoptm_remove_meta') // Currently only works if using optipng or jpegtran
		)
	));

	// wp_add_inline_script( 'wpsl_unlock_keys', 'window.fbAsyncInit = function() {
	//     FB.init({
	//       appId      : "' . $api_key . '",
	//       xfbml      : true,
	//       version    : "v2.7"
	//     });
	//   };
	//
	//   (function(d, s, id){
	//      var js, fjs = d.getElementsByTagName(s)[0];
	//      if (d.getElementById(id)) {return;}
	//      js = d.createElement(s); js.id = id;
	//      js.src = "//connect.facebook.net/en_US/sdk.js";
	//      fjs.parentNode.insertBefore(js, fjs);
	//    }(document, "script", "facebook-jssdk"));'
	//  , 'before' );

}

add_action( 'wp_enqueue_scripts', 'wpsl_unlock_enqueues' );


// AJAX Actions
add_action( 'wp_ajax_wpsl_unlock_content', 'handle_content_unlocking' );
add_action( 'wp_ajax_nopriv_wpsl_unlock_content', 'handle_content_unlocking' );

function handle_content_unlocking() {

	global $wpsl_leads, $wpsl_stats;

	$data						=	$_POST['data']; // NOTE: Sanitizing array elements individually below

	if( is_array($data) ){

		$raw_content		=	sanitize_text_field($data['content']);
		$email_address	=	sanitize_email($data['email_address']);
		$content				=	wpsl_decrypt($raw_content);

		if( $email_address ){

			$first_name			=	sanitize_title($data['first_name']);

			if( $email_address == '' ){
				return false;
			}else{

				$widget_id = sanitize_text_field($_POST['etu_widget_id']);

				// Auto Export to Email Marketing Platform
				$auto_ac_export = get_option('wpsl_activecampaign_auto_export');
				$auto_mc_export = get_option('wpsl_mailchimp_auto_export');
				$auto_cc_export = get_option('wpsl_constantcontact_auto_export');
				$auto_aw_export = get_option('wpsl_aweber_auto_export');
				$auto_drip_export	= get_option('wpsl_drip_auto_export');
				$auto_hs_export = get_option('wpsl_hubspot_auto_export');
				$auto_cm_export = get_option('wpsl_campaignmonitor_auto_export');
				$auto_gr_export = get_option('wpsl_getresponse_auto_export');
				$auto_ck_export = get_option('wpsl_convertkit_auto_export');

				$autoac_list_id = get_post_meta($widget_id, 'wpsl_widget_activecampaign_list', true);
				$mailchimp_list	=	get_post_meta($widget_id, 'wpsl_widget_mailchimp_list', true);
				$cc_list				=	get_post_meta($widget_id, 'wpsl_widget_constantcontact_list', true);
				$aweber_list		=	get_post_meta($widget_id, 'wpsl_widget_aweber_list', true);
				$drip_campaign	=	get_post_meta($widget_id, 'wpsl_widget_drip_campaign', true);
				$hubspot_list		=	get_post_meta($widget_id, 'wpsl_widget_hubspot_list', true);
				$campaignmon_li	=	get_post_meta($widget_id, 'wpsl_widget_campaignmonitor_list', true);
				$getresponse_li	=	get_post_meta($widget_id, 'wpsl_widget_getresponse_list', true);
				$convertkit_frm	=	get_post_meta($widget_id, 'wpsl_widget_convertkit_form', true);

				// Auto ConvertKit
				if( $auto_ck_export != '' && $convertkit_frm != '' && $convertkit_frm != '0' ){

					$ck_api_key	= get_option('wpsl_convertkit_api_key');

					$ck_data	= array(
						"api_key"	=> $ck_api_key,
						"email"		=> $email_address
					);

					if( $first_name != '' )
						$ck_data["first_name"]	= $first_name;

					$endpoint	= 'https://api.convertkit.com/v3';

					wp_safe_remote_post( $endpoint.'/forms/'.$convertkit_frm.'/subscribe', array(
						'body'	=> json_encode($ck_data),
						'headers'	=> array(
							'Content-Type'	=> 'application/json'
						)
					));

				}


 				// Auto GetResponse
				if( $auto_gr_export != '' && $getresponse_li != '' && $getresponse_li != '0' ){

 					$gr_api_key	= get_option('wpsl_getresponse_api_key');
 					$endpoint		= 'https://api.getresponse.com/v3';

					$gr_data		= array(
						"email"			=> $email_address,
						"campaign"	=> array(
							"campaignId"	=> $getresponse_li
						)
					);

 					if( $first_name != '' ){
						$gr_data["name"]	= $first_name;
					}

 					wp_safe_remote_post( $endpoint.'/contacts', array(
						'body'	=> json_encode($gr_data),
						'headers'	=> array(
							'X-Auth-Token'	=> 'api-key '.$gr_api_key,
							'Content-Type'	=> 'application/json'
						)
					));

 				}


				// Auto Campaign Monitor
				if( $auto_cm_export && $campaignmon_li ){

					$cm_api_key	= get_option('wpsl_campaignmonitor_api_key');

 					$cm_wrap	= new CS_REST_Subscribers( $campaignmon_li, array(
						'api_key' => $cm_api_key
					) );

 					$cm_result = $cm_wrap->add( array(
						'EmailAddress'	=> $email_address,
						'Name'					=> $first_name,
						'ConsentToTrack'=> 'yes',
						'Resubscribe'		=> true
					) );

 					if( ! $cm_result->was_successful() ){
						// TODO: Error Logging
						// echo 'Failed with code '.$cm_result->http_status_code."\n<br /><pre>";
						// var_dump($cm_result->response);
						// echo '</pre>';
					}

 				}

				// Auto HubSpot
				if( $auto_hs_export && $hubspot_list ){

					$hs_api_key			=	get_option('wpsl_hubspot_api_key');

					// Add/Update Contact
					$hsr = wp_safe_remote_post('https://api.hubapi.com/contacts/v1/contact/createOrUpdate/email/'.urlencode($email_address).'/?hapikey='.urlencode($hs_api_key), array(
						'body'	=> json_encode(array(
							"properties" => array(
								array(
									"property"	=> "firstname",
									"value"			=> $first_name
								)
							)
						))
					));

					$resp = json_decode($hsr['body'], true);

					if( $resp['status'] == 'error' ){
						// TODO: error logging $resp['message'] $resp['correlationId'] $resp['requestId']
					}elseif( $resp['vid'] > 0 ){
						// Add Contact to List
						$rr = wp_safe_remote_post('https://api.hubapi.com/contacts/v1/lists/'.intval($hubspot_list).'/add?hapikey='.urlencode($hs_api_key), array(
							'headers'	=> array( 'Content-type' => 'application/json' ),
							'body'		=> json_encode(array(
								'vids'		=> array( $resp['vid'] ),
								'emails'	=> array( $email_address )
							))
						));
					}

				}

				// Auto ActiveCampaign
				if( $auto_ac_export && $autoac_list_id ){
					$ac_api_url			=	get_option('wpsl_activecampaign_api_url');
					$ac_api_key			=	get_option('wpsl_activecampaign_api_key');

					$ActiveCampaign	=	new ActiveCampaign($ac_api_url, $ac_api_key);
					$ActiveCampaign->api("contact/sync", array(
						"email"									=> $email_address,
						"first_name"						=> $first_name,
						"p[{$autoac_list_id}]"			=> $autoac_list_id,
						"status[{$autoac_list_id}]"	=> 1
					));
				}

				// Auto Mailchimp
				if( $auto_mc_export && $mailchimp_list ){
					$mailchimp_api	=	get_option('wpsl_mailchimp_api_key');

					$MailChimp			= new MailChimp($mailchimp_api);
					$MailChimp->post("lists/$mailchimp_list/members", [
            'email_address'	=> $email_address,
            'status'				=> 'subscribed',
						'merge_fields'	=> ['FNAME'	=> $first_name]
        	]);
				}

				// Auto ConstantContact
				if( $auto_cc_export && $cc_list ){
					$cc_api_key				=	get_option('wpsl_constantcontact_api_key');
					$cc_api_secret		=	get_option('wpsl_constantcontact_api_secret');
					$cc_access_token	=	get_option('wpsl_constantcontact_access_token');

					$cc			= new Ctct\ConstantContact( $cc_api_key );
					$email	=	$email_address;
					$name		=	$first_name;
					$listID	=	$cc_list;

					$action = "Getting Contact By Email Address";

					try{

						$response	= $cc->contactService->getContacts($cc_access_token, array("email" => $email));

						if( empty($response->results) ){
							$action		= "Creating Contact";
							$contact	= new Ctct\Components\Contacts\Contact();

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
								$e = new Ctct\Exceptions\CtctException();
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

				// Auto Aweber
				if( $auto_aw_export && $aweber_list ){

					$consumerKey				=	get_option('wpsl_aweber_consumer_key');
					$consumerSecret			=	get_option('wpsl_aweber_consumer_secret');

					$accessKey					=	get_option('wpsl_aweber_accessKey');
					$accessSecret				=	get_option('wpsl_aweber_accessSecret');
					$requestTokenSecret	=	get_option('wpsl_aweber_request_token_secret');
					$callbackURL				= admin_url('admin.php?page=capture-and-convert-menu&tab=wpsl_lead_collection&wpsl_export=aweber');

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

					try{
						$account = $aweber->getAccount($accessKey, $accessSecret);
						$account_id = $account->id;
						$listURL = "/accounts/{$account_id}/lists/{$aweber_list}";
						$list = $account->loadFromUrl($listURL);
						$subscribers = $list->subscribers;

						try{
							$new_subscriber = $subscribers->create(array(
								'email'	=> $email_address,
								'name'	=> $first_name
							));
						}catch(AWeberAPIException $exc){
							// TODO Log errors
						}

					}catch(AWeberAPIException $exc){
						// TODO Log errors
					}

				}

				// Auto Drip
				if( $auto_drip_export && $drip_campaign ){
 					$drip_api_key			= get_option('wpsl_drip_api_key');
					$drip_account_id	= get_option('wpsl_drip_account_id');
 					if( $drip_api_key != '' && $drip_account_id > 0 ){
 						try{
 							$dripclient = new \Drip\Client( $drip_api_key, $drip_account_id );
 							// $dripclient->create_or_update_subscriber(array(
							// 	'email'					=> $email_address,
							// 	'custom_fields'	=> array(
							// 		'name'	=> $first_name
							// 	)
							// ));
 							$rr = $dripclient->subscribe_subscriber(array(
								'campaign_id'		=> $drip_campaign,
								'email'					=> $email_address,
								'custom_fields'	=> array(
									'name'	=> $first_name
								)
							));
 						}catch( Exception $e ){  /* TODO: */ }
 					}
 				}

				$wpsl_leads->insert($email_address, $first_name);
				$wpsl_stats->insert($widget_id, 'email', 'manual_email', 'impression');
				echo $content;
			}

		}

	}else{
		$clean_data = sanitize_text_field($data);
		echo wpsl_decrypt($clean_data);
	}

	wp_die();

}
