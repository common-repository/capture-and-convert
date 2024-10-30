<?php
/**
 * Admin Enqueue for Template preview
 */
function wpsl_admin_temp_preview_enqueues() {

	wp_enqueue_style('wp-color-picker');

	wp_enqueue_script( 'wpsl_admin_temp_preview', WPSL_ROOT_URL . 'admin/js/preview-template.js', array('jquery', 'wp-color-picker'), '1.0.0', true );
	wp_localize_script('wpsl_admin_temp_preview', 'wpsl_admin_ajax', array(
		'ajaxurl' 				=> admin_url('admin-ajax.php'),
		'plugin_url'			=> WPSL_ROOT_URL,
		'plugin_path'			=> WPSL_ROOT_DIR,
		'action'					=> 'wpsl_preview_template'
	));

}

add_action( 'admin_enqueue_scripts', 'wpsl_admin_temp_preview_enqueues' );


// AJAX Action
add_action( 'wp_ajax_wpsl_preview_template', 'handle_admin_template_preview' );

function handle_admin_template_preview() {

	$template_id		=	sanitize_text_field($_POST['template_id']);
	$type						=	sanitize_text_field($_POST['type']);

	if( $template_id == '' )
		die('error');

	if( $type != 'email' && $type != 'follow' && $type != 'share' )
		die('error');

	echo '<div class="wpsl_locked_widget '.$template_id.'">';
	echo wpsl_locker_widget_preview_html( $template_id, $type );
	echo '</div>';

	$template_detail	= wpsl_fw_template( $template_id, 'lockers' );

	if( $template_detail['config'] ){

		$config_map	=	array(
			'wpsl_customizer_textbox' => array(
				'name-field'		=> array(
					'email'	=> 'name_field',
				),
				'font-size'			=> array(
					'email'	=> 'wpsl_customized_data_etu_textbox_fs',
				),
				'font-family'		=> array(
					'email'	=> 'wpsl_customized_data_etu_textbox_ff',
				),
				'wpsl-font-var'	=> array(
					'email'	=> 'wpsl_customized_data_etu_textbox_fv',
				),
				'color'					=> array(
					'email'	=> 'wpsl_customized_data_etu_textbox_color',
				),
				'use_gradient'		=> array(
					'email'	=> 'txtbx_bg_grad_togl',
				),
				'background'		=> array(
					'email'	=> 'wpsl_customized_data_etu_textbox_bg',
				),
				'gradient_a'		=> array(
					'email'	=> 'wpsl_customized_data_etu_textbox_bga',
				),
				'gradient_b'		=> array(
					'email'	=> 'wpsl_customized_data_etu_textbox_bgb',
				),
				'border-width'	=> array(
					'email'	=> 'wpsl_email_text_border_width'
				),
				'border-style'	=> array(
					'email'	=> 'wpsl_email_text_border_style'
				),
				'border-color'	=> array(
					'email'	=> 'wpsl_customized_email_text_border_color'
				)
			),
			'wpsl_customizer_heading' => array(
				'font-size' => array(
					'email'	=> 'wpsl_customized_data_heading_fs',
					'follow'=> 'wpsl_customized_data_heading_fs',
					'share'	=> 'wpsl_customized_data_heading_fs',
				),
				'font-family' => array(
					'email'	=> 'wpsl_customized_data_heading_ff',
					'follow'=> 'wpsl_customized_data_heading_ff',
					'share'	=> 'wpsl_customized_data_heading_ff',
				),
				'wpsl-font-var' => array(
					'email'	=> 'wpsl_customizer_heading_fv',
					'follow'=> 'wpsl_customizer_heading_fv',
					'share'	=> 'wpsl_customizer_heading_fv',
				),
				'color' => array(
					'email'	=> 'wpsl_customized_data_heading_color',
					'follow'=> 'wpsl_customized_data_heading_color',
					'share'	=> 'wpsl_customized_data_heading_color',
				)
			),
			'wpsl_customizer_desc' => array(
				'font-size' => array(
					'email'	=> 'wpsl_customized_data_desc_fs',
					'follow'=> 'wpsl_customized_data_desc_fs',
					'share'	=> 'wpsl_customized_data_desc_fs',
				),
				'font-family' => array(
					'email'	=> 'wpsl_customized_data_desc_ff',
					'follow'=> 'wpsl_customized_data_desc_ff',
					'share'	=> 'wpsl_customized_data_desc_ff',
				),
				'wpsl-font-var' => array(
					'email'	=> 'wpsl_customizer_desc_fv',
					'follow'=> 'wpsl_customizer_desc_fv',
					'share'	=> 'wpsl_customizer_desc_fv',
				),
				'color' => array(
					'email'	=> 'wpsl_customized_data_desc_color',
					'follow'=> 'wpsl_customized_data_desc_color',
					'share'	=> 'wpsl_customized_data_desc_color',
				)
			),
			'wpsl_email_locker_icon' => array(
				'use_custom_img'	=> array(
					'email'	=> 'icon_image_togl',
					'follow'=> 'icon_image_togl',
					'share'	=> 'icon_image_togl',
				),
				'icon_slug' => array(
					'email'	=> 'wpsl_widget_icon',
					'follow'=> 'wpsl_widget_icon',
					'share'	=> 'wpsl_widget_icon',
				),
				'font-size' => array(
					'email'	=> 'wpsl_customized_data_email_locker_icon_fs',
					'follow'=> 'wpsl_customized_data_email_locker_icon_fs',
					'share'	=> 'wpsl_customized_data_email_locker_icon_fs',
				),
				'color' => array(
					'email'	=> 'wpsl_customized_data_email_locker_icon_color',
					'follow'=> 'wpsl_customized_data_email_locker_icon_color',
					'share'	=> 'wpsl_customized_data_email_locker_icon_color',
				),
				'custom_image_icon' => array(
					'email'	=> 'custom_image_icon',
					'follow'=> 'custom_image_icon',
					'share'	=> 'custom_image_icon',
				)
			),
			'wpsl_left_icon' => array(
				'background' => array(
					'email'	=> 'wpsl_customized_data_left_icon_bg',
					'follow'=> 'wpsl_customized_data_left_icon_bg',
					'share'	=> 'wpsl_customized_data_left_icon_bg',
				),
				'gradient_a' => array(
					'email'	=> 'wpsl_customized_data_left_icon_bga',
					'follow'=> 'wpsl_customized_data_left_icon_bga',
					'share'	=> 'wpsl_customized_data_left_icon_bga',
				),
				'gradient_b' => array(
					'email'	=> 'wpsl_customized_data_left_icon_bgb',
					'follow'=> 'wpsl_customized_data_left_icon_bgb',
					'share'	=> 'wpsl_customized_data_left_icon_bgb',
				)
			),
			'wpsl_locker_header_img' => array(
				'url' => array(
					'email'	=> 'wpsl_header_img',
					'follow'=> 'wpsl_header_img',
					'share'	=> 'wpsl_header_img',
				)
			),
			'wpsl_customizer_primary_bg' => array(
				'background' => array(
					'email'	=> 'wpsl_customized_data_primary_bg_bg',
					'follow'=> 'wpsl_customized_data_primary_bg_bg',
					'share'	=> 'wpsl_customized_data_primary_bg_bg',
				),
				'gradient_a' => array(
					'email'	=> 'wpsl_customized_data_primary_bg_bga',
					'follow'=> 'wpsl_customized_data_primary_bg_bga',
					'share'	=> 'wpsl_customized_data_primary_bg_bga',
				),
				'gradient_b' => array(
					'email'	=> 'wpsl_customized_data_primary_bg_bgb',
					'follow'=> 'wpsl_customized_data_primary_bg_bgb',
					'share'	=> 'wpsl_customized_data_primary_bg_bgb',
				)
			),
			'wpsl_customizer_primary_bg_img' => array(
				'url' => array(
					'email'	=> 'wpsl_bg_img',
					'follow'=> 'wpsl_bg_img',
					'share'	=> 'wpsl_bg_img',
				)
			),
			'facebook_share'	=> array(
				'font-size'	=> array(
					'share'	=> 'wpsl_customized_data_facebook_share_fs'
				),
				'font-family'	=> array(
					'share'	=> 'wpsl_customized_data_facebook_share_ff'
				),
				'wpsl-font-var'	=> array(
					'share'	=> 'wpsl_customized_data_facebook_share_fv'
				),
				'color'	=> array(
					'share'	=> 'wpsl_customized_data_facebook_share_color'
				),
				'background'	=> array(
					'share'	=> 'wpsl_customized_data_facebook_share_bg'
				),
				'gradient_a'	=> array(
					'share'	=> 'wpsl_customized_data_facebook_share_bga'
				),
				'gradient_b'	=> array(
					'share'	=> 'wpsl_customized_data_facebook_share_bgb'
				),
			),
			'twitter_tweet'	=> array(
				'font-size'	=> array(
					'share'	=> 'wpsl_customized_data_twitter_tweet_fs'
				),
				'font-family'	=> array(
					'share'	=> 'wpsl_customized_data_twitter_tweet_ff'
				),
				'wpsl-font-var'	=> array(
					'share'	=> 'wpsl_customized_data_twitter_tweet_fv'
				),
				'color'	=> array(
					'share'	=> 'wpsl_customized_data_twitter_tweet_color'
				),
				'background'	=> array(
					'share'	=> 'wpsl_customized_data_twitter_tweet_bg'
				),
				'gradient_a'	=> array(
					'share'	=> 'wpsl_customized_data_twitter_tweet_bga'
				),
				'gradient_b'	=> array(
					'share'	=> 'wpsl_customized_data_twitter_tweet_bgb'
				),
			),
			'instagram_follow'	=> array(
				'font-size'	=> array(
					'follow'=> 'wpsl_customized_data_instagram_follow_fs',
				),
				'font-family'	=> array(
					'follow'=> 'wpsl_customized_data_instagram_follow_ff',
				),
				'wpsl-font-var'	=> array(
					'follow'=> 'wpsl_customized_data_instagram_follow_fv',
				),
				'color'	=> array(
					'follow'=> 'wpsl_customized_data_instagram_follow_color',
				),
				'background'	=> array(
					'follow'=> 'wpsl_customized_data_insta_follow_bg',
				),
				'gradient_a'	=> array(
					'follow'=> 'wpsl_customized_data_insta_follow_bga',
				),
				'gradient_b'	=> array(
					'follow'=> 'wpsl_customized_data_insta_follow_bgb',
				),
			),
			'youtube_subscribe'	=> array(
				'font-size'	=> array(
					'follow'=> 'wpsl_customized_data_youtube_subscribe_fs',
				),
				'font-family'	=> array(
					'follow'=> 'wpsl_customized_data_youtube_subscribe_ff',
				),
				'wpsl-font-var'	=> array(
					'follow'=> 'wpsl_customized_data_youtube_subscribe_fv',
				),
				'color'	=> array(
					'follow'=> 'wpsl_customized_data_youtube_subscribe_color',
				),
				'background'	=> array(
					'follow'=> 'wpsl_customized_data_yt_subscribe_bg',
				),
				'gradient_a'	=> array(
					'follow'=> 'wpsl_customized_data_yt_subscribe_bga',
				),
				'gradient_b'	=> array(
					'follow'=> 'wpsl_customized_data_yt_subscribe_bgb',
				),
			),
			'twitter_follow'	=> array(
				'font-size'	=> array(
					'follow'=> 'wpsl_customized_data_twitter_follow_fs',
				),
				'font-family'	=> array(
					'follow'=> 'wpsl_customized_data_twitter_follow_ff',
				),
				'wpsl-font-var'	=> array(
					'follow'=> 'wpsl_customized_data_twitter_follow_fv',
				),
				'color'	=> array(
					'follow'=> 'wpsl_customized_data_twitter_follow_color',
				),
				'background'	=> array(
					'follow'=> 'wpsl_customized_data_twitter_follow_bg',
				),
				'gradient_a'	=> array(
					'follow'=> 'wpsl_customized_data_twitter_follow_bga',
				),
				'gradient_b'	=> array(
					'follow'=> 'wpsl_customized_data_twitter_follow_bgb',
				),
			),
			'wpsl_customizer_button' => array(
				'font-size' => array(
					'email'	=> 'wpsl_customized_data_etu_button_fs',
				),
				'font-family' => array(
					'email'	=> 'wpsl_customized_data_etu_button_ff',
				),
				'wpsl-font-var' => array(
					'email'	=> 'wpsl_customized_data_etu_button_fv',
				),
				'color' => array(
					'email'	=> 'wpsl_customized_data_etu_button_color',
				),
				'use_gradient'	=> array(
					'email'	=> 'sbmt_btn_bg_grad_togl',
				),
				'background' => array(
					'email'	=> 'wpsl_customized_data_etu_button_bg',
				),
				'gradient_a' => array(
					'email'	=> 'wpsl_customized_data_etu_button_bga',
				),
				'gradient_b' => array(
					'email'	=> 'wpsl_customized_data_etu_button_bgb',
				)
			)
		);

		$template_configs = parse_ini_file($template_detail['config'], true);
		$template_config	= $template_configs[$type];

		echo wpslcnc_apply_customizer_settings( $template_config, $type);

		echo '<script type="text/javascript">';

			echo "jQuery('.wpsl_customizer_section input[type=checkbox]').prop('checked', false).change();\n\n";

			// Remove name field by default
			echo "jQuery('#name_field').prop('checked', false).change();\n\n";

			// Default website font for everything
			echo "jQuery('.wpsl_font_family_selector').val('0').change();\n\n";

			if( $type == 'share' ){
				$title = 'Share to Unlock';
			}elseif( $type == 'follow' ){
				$title = 'Follow to Unlock';
			}elseif( $type == 'cta_widgets' ){
				$title = 'Read The Case Study!';
			}else{

				$title = 'Subscribe to Unlock';

			}

			$desc_text = $template_config['wpsl_customizer_desc']['text'];
			$head_text = $template_config['wpsl_customizer_heading']['text'];

			// Heading's Text
			echo "if( jQuery('#locker_header').val() == '' ){\n";
				echo "jQuery('#locker_header').val('".($head_text == '' ? $title : $head_text)."').keyup();\n";
			echo "}\n\n";

			// Message's Text
			echo "if( tinymce.get('locker_message') != null ){\n";
				echo "tinymce.get('locker_message').setContent('".$desc_text."');\n";
			echo "}\n\n";

			echo "jQuery('#locker_header').keyup();\n";
			echo "jQuery('.wpsl_customizer_desc').html('".$desc_text."');\n";

			$credit_bg = $template_config['powered_by_style']['credit'];

			echo "jQuery('input[name=powered_by_style][value=".($credit_bg != '' ? $credit_bg : 'light')."]').prop('checked', true).change();\n\n";

			foreach( $template_config as $section=>$styles ){
				foreach( $styles as $style=>$val ){

					if( $style == 'text' || $style == 'credit' ){
						continue;
					}

					$id = $config_map[$section][$style][$type];

					$val = str_replace( '%template_uri%', $template_detail['template_uri'], $val );

					if( $val == 'on' ){
						echo "jQuery('#".$id."').prop('checked', true).change();\n";
					}elseif( $val == 'off' ){
						echo "jQuery('#".$id."').prop('checked', false).change();\n";
					}elseif( preg_match('/\#[0-9abcdef]+/', $val) ){
						echo "jQuery('#".$id."').wpColorPicker('color', '".$val."');\n";
					}else{
						echo "jQuery('#".$id."').val('".$val."').change();\n";
					}

				}
			}

		echo '</script>';

	}

	wp_die();

}
