<?php

/**
 * Social Locker Widget Shortcode
 *
 * @deprecated Using shortcode [wpsl_locker] hooked in function 'wpsl_locker_widget_shortcode'
 */
function wpsl_social_lock_cb( $atts, $content ){

	$uniqid						=	uniqid();
	$email_signup			=	$atts['email'];
	$title						=	( $atts['title'] == '' ? get_option("wpsl_lock_title") : $atts['title'] );
	$desc							=	( $atts['desc'] == '' ? get_option("wpsl_lock_description") : $atts['desc'] );
	$master_disable		=	get_option("wpsl_master_enable");

	if($master_disable){
		return $content;
	}


	$outp			= '';

	$outp			.= '<div class="wpsl_locked_widget" id="wpsl_cont_'.$uniqid.'">';

		$outp			.= '<h3>' . $title . '</h3>';
		$outp			.= '<p>' . $desc . '</p>';

		if( $email_signup == "true"){

			$outp			.= '<form action="" method="post" class="wpsl_unlock_form">';

				$outp			.= '<input type="text" required class="wpsl_text_input" name="first_name" placeholder="First Name" />';
				$outp			.= '<input type="email" required class="wpsl_text_input" name="email_address" placeholder="Email" />';
				$outp			.= '<input type="hidden" name="content" value="'.wpsl_encrypt($content).'" />';
				$outp			.= '<input type="hidden" id="uniqid" name="uniqid" value="'.$uniqid.'" />';
				$outp			.= '<input type="submit" name="email_method" value="Send It!" class="wpsl_submit_button" />';

			$outp			.= '</form>';

		}else{

			$outp			.= '<ul>';

				/**
				 * Filters the list of keys to unlock.
				 *
				 * @since 0.1
				 * @deprecated
				 *
				 * @param array $lock_keys The list keys to unlock.
				 */
				$lock_keys		=	apply_filters('wpsl_lock_keys', array(
					'facebook_like'			=> 'Facebook Like',
					'facebook_share'		=> 'Facebook Share',
					'twitter_tweet'			=> 'Twitter Tweet',
					'youtube_subscribe'	=> 'Youtube Subscribe'
				));

				$encontent		=	wpsl_encrypt($content);
				$permalink		=	get_permalink();
				foreach( $lock_keys as $id=>$label ){

					if( get_option( 'wpsl_enable_' . $id ) ){
						if( 'twitter_tweet' == $id ){
							$url		=	site_url("/?wpsl_authorize=twitter&hash=$encontent&post_url=$permalink");
							$outp			.= '<li><a href="'.$url.'" data-post_url="'.$permalink.'" id="wpsl_' . $id . '" data-uniqid="'.$uniqid.'" data-hash="'.$encontent.'" class="key_a ' . $id . '">' . $label . '</a></li>';
						}elseif( 'youtube_subscribe' == $id ){
							$url		=	site_url("/?wpsl_authorize=youtube&hash=$encontent&post_url=$permalink");
							$outp			.= '<li><a href="'.$url.'" data-post_url="'.$permalink.'" id="wpsl_' . $id . '" data-uniqid="'.$uniqid.'" data-hash="'.$encontent.'" class="key_a ' . $id . '">' . $label . '</a></li>';
						}else{
							$outp			.= '<li><a href="javascript:;" data-post_url="'.$permalink.'" id="wpsl_' . $id . '" data-uniqid="'.$uniqid.'" data-hash="'.$encontent.'" class="key_a ' . $id . '">' . $label . '</a></li>';
						}
					}

				}

			$outp			.= '</ul>';

		}

	$outp			.= '</div>';

	return $outp;


}

add_shortcode('social_lock_content', 'wpsl_social_lock_cb');


/**
 * Locker Widget Shortcode
 *
 * @TODO Need to make a parser function instead of using `str_replace`
 */
function wpsl_locker_widget_shortcode( $atts, $content ){

	global $wpsl_stats;

	$lockerID		=	absint($atts['id']);
	$uniqid			=	$lockerID.hexdec(uniqid());
	$locker			=	wpsl_get_locker( $lockerID );

	if( $locker === NULL )
		return __( 'Sorry, the locker you asked for is either removed or never existed.', WPSL_TD );

	if( $locker === FALSE )
		return __( 'Invalid Locker Widget ID.', WPSL_TD );

	if( get_post_status($lockerID) != 'publish' )
		return __( 'This widget is not published yet.', WPSL_TD );

	$template		=	wpsl_locker_widget_html( $locker['template'], $locker['widget_type'], false, $lockerID );
	$encontent	=	wpsl_encrypt($content);
	$permalink	=	get_permalink();
	$post_title	=	get_the_title();

	if( $locker['widget_type'] == 'email' ){

		$hidden_fields		=	'<input type="hidden" name="content" value="'.$encontent.'" /> <input type="hidden" id="uniqid" name="uniqid" value="'.$uniqid.'" />';
		$hidden_fields		.=	'<input type="hidden" class="widget_id" name="widget_id" value="'.$lockerID.'" />';

		if( get_post_meta( $lockerID, 'name_field', true ) ){
			$hidden_fields	.=	'<input type="text" required class="wpsl_text_input wpsl_customizer_textbox" name="first_name" placeholder="Your Name" />';
		}

		$locker['description'] = str_replace('%post_url%', $permalink, $locker['description']);
		$locker['description'] = str_replace('%post_title%', $post_title, $locker['description']);

		$parsed_template	=	str_replace( array(
			'%locker_header%',
			'%locker_message%',
			'%hidden_fields%',
			'%submit_label%',
		), array(
			$locker['heading'],
			str_replace(array('<p>', '</p>'), '', wpautop($locker['description'])), // We need br tags but not p
			$hidden_fields,
			$locker['submit_label']
		), $template );

	}else{

		$heading	=	preg_replace('/\*(.*)\*/', '<span>$1</span>', $locker['heading']);

		if( $locker['widget_type'] == 'share' ){

			$lock_keys	=	array(
				'facebook_share'	=> 'Facebook Share',
				'twitter_tweet'		=> 'Twitter Tweet'
			);

		}else{

			$lock_keys	=	array(
				'instagram_follow'		=> 'Instagram Follow',
				'youtube_subscribe'	=> 'Youtube Subscribe',
				'twitter_follow'		=> 'Twitter Follow'
			);

		}

		$soical_keys		=	'';

		foreach( $lock_keys as $id=>$label ){

			if( get_post_meta( $lockerID, 'activate_' . $id, true ) ){

				switch( $id ){

					case 'twitter_tweet':
						$msg_to_tweet	=		get_post_meta( $lockerID, 'tw_tweet_msg', true );
						$url					=		site_url("/?wpsl_authorize=twitter&hash=$encontent&post_url=".urlencode($permalink)."&locker_id=$lockerID&msg_to_tweet=".urlencode($msg_to_tweet));
						$label				=		get_post_meta( $lockerID, 'tw_tweet_button_title', true );
						$soical_keys	.= '<a href="'.$url.'" data-post_url="'.$permalink.'" id="wpsl_twitter_tweet" data-uniqid="'.$uniqid.'" data-hash="'.$encontent.'" class="key_a twitter_tweet">' . $label . '</a>';
					break;

					case 'instagram_follow':
						$url_to_follow	=		get_post_meta( $lockerID, 'insta_url_to_follow', true );
						$label				=		get_post_meta( $lockerID, 'insta_follow_button_title', true );
						$soical_keys	.=	'<a href="javascript:;" data-post_url="'.$url_to_follow.'" id="wpsl_instagram_follow" data-uniqid="'.$uniqid.'" data-hash="'.$encontent.'" class="key_a instagram_follow">' . $label . '</a>';
					break;

					case 'facebook_share':
						$url_to_share	=		get_post_meta( $lockerID, 'fb_url_to_share', true );
						$label				=		get_post_meta( $lockerID, 'fb_share_button_title', true );
						$soical_keys	.= '<a href="javascript:;" data-post_url="'.$url_to_share.'" id="wpsl_facebook_share" data-uniqid="'.$uniqid.'" data-hash="'.$encontent.'" class="key_a facebook_share">' . $label . '</a>';
					break;

					case 'twitter_follow':
						$user_to_follow		=		get_post_meta( $lockerID, 'tw_username_to_follow', true );
						$url							=		site_url("/?wpsl_authorize=twitter&hash=$encontent&post_url=".urlencode($permalink)."&locker_id=$lockerID&wpsl_tw_user_to_follow=".urlencode($user_to_follow));
						$label						=		get_post_meta( $lockerID, 'tw_follow_button_text', true );
						$soical_keys			.=	'<a href="'.$url.'" data-post_url="'.$permalink.'" id="wpsl_twitter_follow" data-uniqid="'.$uniqid.'" data-hash="'.$encontent.'" class="key_a twitter_follow">' . $label . '</a>';
					break;

					case 'youtube_subscribe':
						$channel_id		=		get_post_meta( $lockerID, 'channel_id_to_subscribe', true );
						$url					=		site_url("/?wpsl_authorize=youtube&hash=$encontent&post_url=".urlencode($permalink)."&locker_id=$lockerID&channel_id=".$channel_id);
						$label				=		get_post_meta( $lockerID, 'yt_subscribe_button_text', true );
						$soical_keys	.=	'<a href="javascript:;" data-cnc_channel_id="'.$channel_id.'" data-old_url="'.$url.'" data-post_url="'.$permalink.'" id="wpsl_youtube_subscribe" data-uniqid="'.$uniqid.'" data-hash="'.$encontent.'" class="key_a youtube_subscribe">' . $label . '</a></li>';
					break;

				}

			}

		}

		$locker['description'] = str_replace('%post_url%', $permalink, $locker['description']);
		$locker['description'] = str_replace('%post_title%', $post_title, $locker['description']);

		$parsed_template	=	str_replace( array(
			'%locker_header%',
			'%locker_message%',
			'%soical_keys%'
		), array(
			$heading,
			str_replace(array('<p>', '</p>'), '', wpautop($locker['description'])), // We need br tags but not p
			$soical_keys
		), $template );

	}

	$customizer_ar	=	get_post_meta( $lockerID, 'wpsl_customized_data' );

	$return	=		wpsl_generate_customizer_css( $customizer_ar[0], "#wpsl_cont_$uniqid" );
	$return	.=	'<div class="wpsl_locked_widget '.$locker['template'].' '.( $locker['blurred'] ? 'blurred' : '' ).'" id="wpsl_cont_'.$uniqid.'">';
	$return	.=	$parsed_template;
	if( $locker['blurred'] )
		$return	.=	'<span class="wpslcnc_blurred_text">'.$content.'</span>';
	$return	.=	'</div>';

	$icon_sl			=	(empty($customizer_ar[0]['wpsl_email_locker_icon']['icon_slug']) ? '' : $customizer_ar[0]['wpsl_email_locker_icon']['icon_slug']);
	$use_custom_img		=	(empty($customizer_ar[0]['wpsl_email_locker_icon']['use_custom_img']) ? '' : $customizer_ar[0]['wpsl_email_locker_icon']['use_custom_img']);
	$custom_image_icon	=	(empty($customizer_ar[0]['wpsl_email_locker_icon']['custom_image_icon']) ? '' : $customizer_ar[0]['wpsl_email_locker_icon']['custom_image_icon']);
	$header_img_url		=	(empty($customizer_ar[0]['wpsl_locker_header_img']['url']) ? '' : $customizer_ar[0]['wpsl_locker_header_img']['url']);
	$bg_img_url			=	(empty($customizer_ar[0]['wpsl_customizer_primary_bg_img']['url']) ? '' : $customizer_ar[0]['wpsl_customizer_primary_bg_img']['url']);

	if( $bg_img_url ){

		$return	.=	'<script type="text/javascript">';
			$return	.=	'jQuery(document).ready(function(){';
				$return	.=	'jQuery("#wpsl_cont_' . $uniqid . ' .wpsl_customizer_bg_img").css( "background-image", "url(\''.esc_url($bg_img_url).'\')" ).css( "background-size", "cover" );';
			$return	.=	'});';
		$return	.=	'</script>';

	}

	if( $header_img_url != '' ){

		$return	.=	'<script type="text/javascript">';
			$return	.=	'jQuery(document).ready(function(){';
				$return	.=	'jQuery(\'#wpsl_cont_' . $uniqid . ' .wpsl_header_img\').html( \'<img src="'.esc_url($header_img_url).'" />\' );';
			$return	.=	'});';
		$return	.=	'</script>';

	}

	if( $icon_sl != "0" && $icon_sl != "" && $use_custom_img != 'on' ){

		$return	.=	'<script type="text/javascript">';
			$return	.=	'jQuery(document).ready(function(){';
					$return	.=	'var icon_hhh	=	jQuery("#wpsl_cont_'.$uniqid.' .wpsl_email_locker_icon");';
					$return	.=	'icon_hhh.removeAttr("class");';
					$return	.=	'icon_hhh.empty();';
					$return	.=	'icon_hhh.addClass("wpsl_email_locker_icon dashicons '.$icon_sl.'");';
			$return	.=	'});';
		$return	.=	'</script>';

	}

	if( $use_custom_img == 'on' ){

		$return	.=	'<script type="text/javascript">';
			$return	.=	'jQuery(document).ready(function(){';
					$return	.=	'var icon_hhh	=	jQuery("#wpsl_cont_' . $uniqid . ' .wpsl_email_locker_icon");';
					$return	.=	'icon_hhh.parent(\'.wpsl_left_icon\').css( "background", "none" ).css("padding", "0");';
					$return	.=	'icon_hhh.removeAttr("class");';
					$return	.=	'icon_hhh.addClass("wpsl_email_locker_icon");';
					$return	.=	'icon_hhh.html(\'<img src="' . $custom_image_icon . '" />\');';
			$return	.=	'});';
		$return	.=	'</script>';

	}

	$wpsl_stats->insert( $lockerID, $locker['widget_type'] );
	return $return;

}

add_shortcode( 'wpsl_locker', 'wpsl_locker_widget_shortcode' );
