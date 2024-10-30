<?php
@session_start();

/**
 * Initialize Twitter
 */
// require_once( WPSL_ROOT_DIR . 'libs/twitter/twitteroauth.php' );
define('TWITTER_CB_URL', site_url('/wpsl_cnc_twitter_cb'));
use Abraham\TwitterOAuth\TwitterOAuth;

define('TWITTER_CONSUMER_KEY',		get_option('wpsl_twitter_key'));
define('TWITTER_CONSUMER_SECRET',	get_option('wpsl_twitter_secret'));

add_action( 'wp_footer', 'wpsl_tweet_conf' );
add_action( 'init', 'wpsl_init_twitter_func' );

function wpsl_tweet_conf(){

	if( defined('WPSL_TWEET_CONFIRMED') && WPSL_TWEET_CONFIRMED ){

		$hash			=	str_replace(' ', '+', urldecode($_REQUEST['hash']));
		$content	=	wpsl_decrypt($hash);

		echo '
		<script type="text/javascript">
			jQuery(document).ready(function(){
				var wpsl_wid_container	=	jQuery("a[data-hash=\''.$hash.'\']").parents(".wpsl_locked_widget");

				var uniqid	=	jQuery("a[data-hash=\''.$hash.'\']").data("uniqid");
				var dcpcnt	=	'.json_encode($content).';

				wpsl_record_stat( uniqid, "share", "impression" );

				wpsl_wid_container.empty();
				wpsl_wid_container.addClass("wpsl_unlocked_content");
				wpsl_wid_container.removeClass("wpsl_locked_widget");

				wpsl_wid_container.html(dcpcnt);

			});
		</script>';

	}

}

function wpsl_init_twitter_func(){

	global $wpsl_leads, $wpsl_stats;

	if( !empty($_GET['wpsl_authorize']) && $_GET['wpsl_authorize'] == 'twitter' ){

		$hash						=	$_GET['hash'];
		$post_url				=	$_GET['post_url'];
		$tweet_msg			=	$_GET['msg_to_tweet'];
		$user_to_follow	=	$_GET['wpsl_tw_user_to_follow'];

		if( $tweet_msg == '' && $user_to_follow != '' ){
			setcookie( "wordpress_cnc_tw_utf", $user_to_follow, time()+300 );
			$locker_type	=	'follow';
			$source				=	'twitter_follow';
		}elseif( $tweet_msg != '' && $user_to_follow == '' ){
			setcookie( 'wordpress_cnc_tweet_msg', $tweet_msg, time()+300 );
			$locker_type	=	'share';
			$source				=	'twitter_tweet';
		}else{
			echo 'CNC ERROR: No twitter message/profile provided.';
			exit;
		}

		$locker_id		=	intval($_GET['locker_id']);
		$wpsl_stats->insert( $locker_id, $locker_type, $source, 'impression' );

		if(strpos($post_url,'?') !== false) {
			$post_url .= '&hash='.urlencode($hash);
		}else{
			$post_url .= '?hash='.urlencode($hash);
		}

		$post_url .= '&ver='.rand(1111,999999);

		setcookie( 'wordpress_cnc_tw_post_url', $post_url, time()+300 );

		// define('OAUTH_CALLBACK', $post_url);
		define('OAUTH_CALLBACK', TWITTER_CB_URL.'?ver='.rand(1111,999999));
		define('POST_URL', $post_url);

		if(isset($_REQUEST['oauth_token']) && $_COOKIE['wordpress_cnc_tw_token'] !== $_REQUEST['oauth_token']) {
			session_destroy();
			header('Location: '.POST_URL);
			exit;
		}else{

			if(isset($_GET["denied"])){
				header('Location: '.POST_URL);
				die();
			}

			//Fresh authentication
			try{
				$connection = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET);
				$request_token = $connection->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));
			}catch(Abraham\TwitterOAuth\TwitterOAuthException $e){

				$error = $e->getMessage();

				if( substr($error, 0, 1) == '{' ){
					$error_j = json_decode($error, true);
					$error = 'Twitter Error: ' . $error_j['errors'][0]['message'];
				}

				echo $error;
				exit;

			}

			//Received token info from twitter
			setcookie( 'wordpress_cnc_tw_token', $request_token['oauth_token'], time()+300 );
			setcookie( 'wordpress_cnc_tw_token_secret', $request_token['oauth_token_secret'], time()+300 );

			//Any value other than 200 is failure, so continue only if http code is 200
			if($connection->getLastHttpCode() == '200'){
				//redirect user to twitter
				$twitter_url = $connection->url('oauth/authorize', array('oauth_token' => $request_token['oauth_token']));
				header('Location: ' . $twitter_url);
			}else{

				unset($_COOKIE['wordpress_cnc_tw_token']);
				unset($_COOKIE['wordpress_cnc_tw_token_secret']);
				unset($_COOKIE['wordpress_cnc_tweet_msg']);
				unset($_COOKIE['wordpress_cnc_tw_utf']);
				unset($_COOKIE['wordpress_cnc_tw_post_url']);

				die("error connecting to twitter! try again later!");
			}

		}

		exit;

	}

	if( (!empty($_GET['wpsl_cnc_twitter_cb']) && $_GET['wpsl_cnc_twitter_cb'] == "true") || get_query_var('wpsl_cnc_twitter_cb') == "true" || strpos($_SERVER['REQUEST_URI'], 'wpsl_cnc_twitter_cb') !== false ){

		$redirect_url	=	$_COOKIE['wordpress_cnc_tw_post_url'].'&oauth_token='.$_GET['oauth_token'].'&oauth_verifier='.$_GET['oauth_verifier'].'&ver='.rand(1111,999999);
		header('Location: '.$redirect_url);
		exit;

	}

	if( !empty($_REQUEST['hash']) && $_REQUEST['hash'] != '' && isset($_REQUEST['oauth_token']) && $_COOKIE['wordpress_cnc_tw_token'] == $_REQUEST['oauth_token'] ){

		//Successful response returns oauth_token, oauth_token_secret, user_id, and screen_name

		try{
			$connection = new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, $_COOKIE['wordpress_cnc_tw_token'] , $_COOKIE['wordpress_cnc_tw_token_secret']);
			$access_token = $connection->oauth("oauth/access_token", ["oauth_verifier" => $_REQUEST['oauth_verifier']]);
		}catch(Abraham\TwitterOAuth\TwitterOAuthException $e){

			$error = $e->getMessage();

			if( substr($error, 0, 1) == '{' ){
				$error_j = json_decode($error, true);
				$error = 'Twitter Error: ' . $error_j['errors'][0]['message'];
			}

			// echo $error;
			// exit;

		}

		if($connection->getLastHttpCode() == '200'){
			//Redirect user to twitter
			setcookie( 'wordpress_cnc_tw_status', 'verified', time()+300 );
			setcookie( 'wordpress_cnc_request_vars', json_encode($access_token), time()+300 );

			$screen_name 				= $access_token['screen_name'];
			$twitter_id					= $access_token['user_id'];
			$oauth_token 				= $access_token['oauth_token'];
			$oauth_token_secret = $access_token['oauth_token_secret'];

			try{
				$connection	= new TwitterOAuth(TWITTER_CONSUMER_KEY, TWITTER_CONSUMER_SECRET, $oauth_token, $oauth_token_secret);
				$content		= $connection->get('account/verify_credentials', array('include_entities' => 'true', 'skip_status' => 'true', 'include_email' => 'true'));
			}catch(Abraham\TwitterOAuth\TwitterOAuthException $e){

				$error = $e->getMessage();

				if( substr($error, 0, 1) == '{' ){
					$error_j = json_decode($error, true);
					$error = 'Twitter Error: ' . $error_j['errors'][0]['message'];
				}

				// echo $error;
				// exit;

			}

			$wpsl_leads->insert($content->email, $content->name, 'twitter');

			$tweet_msg			=	$_COOKIE['wordpress_cnc_tweet_msg'];
			$user_to_follow	=	$_COOKIE['wordpress_cnc_tw_utf'];

			if( $tweet_msg != '' && $user_to_follow == '' ){
				$status			= $connection->post('statuses/update', ['status' => stripslashes($tweet_msg)]);
			}elseif( $tweet_msg == '' && $user_to_follow != '' ){
				$status			= $connection->post('friendships/create', array('screen_name' => stripslashes($user_to_follow)));
			}

			unset($_COOKIE['wordpress_cnc_tw_token']);
			unset($_COOKIE['wordpress_cnc_tw_token_secret']);
			unset($_COOKIE['wordpress_cnc_tweet_msg']);
			unset($_COOKIE['wordpress_cnc_tw_utf']);

			define('WPSL_TWEET_CONFIRMED', true);

		}else{

			unset($_COOKIE['wordpress_cnc_tw_token']);
			unset($_COOKIE['wordpress_cnc_tw_token_secret']);
			unset($_COOKIE['wordpress_cnc_tweet_msg']);
			unset($_COOKIE['wordpress_cnc_tw_utf']);

			// die("error, try again later!");
		}

	}

}

/**
 * Initialize Facebook
 */
function wpsl_init_fb_func() {

	$app_id		=	get_option('wpsl_fb_app_id');

	if( ! $app_id )
		return;

  wp_enqueue_script( 'wpsl_fb_init_js', '//connect.facebook.net/en_US/all.js', array(), '2.0' );
  wp_add_inline_script( 'wpsl_fb_init_js', 'FB.init({
    appId  : "'.$app_id.'",
    status : true,
    cookie : true,
    xfbml  : true
  });' );

}
add_action( 'wp_enqueue_scripts', 'wpsl_init_fb_func' );
