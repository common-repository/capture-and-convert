<?php
/**
 * Registering all post types for this plugin
 *
 * @since 0.2
 */
add_action( 'init', 'wpsl_register_widget_post_type' );
function wpsl_register_widget_post_type() {

	$powered_by_setting	=	array();

	$powered_by_setting['powered_by_style']	=	array(
		'title'   => 'Powered By Style?',
		'type'    => 'radio',
		'choices' => array(
			'light'	=> 'Light',
			'gold'	=> 'Gold',
			'clear'	=> 'Clear',
			'dark'	=> 'Dark'
		),
		'desc'    => 'Chose styling of powered by section at bottom.',
		'sanit'   => 'nohtml',
	);



	/**
	 * Register post type for 'Share to Unlock' Widget
	 */
	register_post_type( 'stu_widgets', array(
		'labels'							=> array(
			'name'									=> _x( 'Share to Unlock Widgets', 'post type general name', 'capture-and-convert' ),
			'singular_name'					=> _x( 'Share to Unlock Widget', 'post type singular name', 'capture-and-convert' ),
			'menu_name'							=> _x( 'Share to Unlock Widgets', 'admin menu', 'capture-and-convert' ),
			'name_admin_bar'				=> _x( 'Share to Unlock Widget', 'add new on admin bar', 'capture-and-convert' ),
			'add_new'								=> _x( '+ StU Widget', 'book', 'capture-and-convert' ),
			'add_new_item'					=> __( 'Add New \'Share to Unlock\' Widget', 'capture-and-convert' ),
			'new_item'							=> __( 'New Widget', 'capture-and-convert' ),
			'edit_item'							=> __( 'Edit Widget', 'capture-and-convert' ),
			'view_item'							=> __( 'View Widget', 'capture-and-convert' ),
			'all_items'							=> __( 'All Widgets', 'capture-and-convert' ),
			'search_items'					=> __( 'Search Widgets', 'capture-and-convert' ),
			'parent_item_colon'			=> __( 'Parent Widgets:', 'capture-and-convert' ),
			'not_found'							=> __( 'No widgets found.', 'capture-and-convert' ),
			'not_found_in_trash'		=> __( 'No widgets found in Trash.', 'capture-and-convert' ),
			'featured_image'				=> __( 'Custom Icon', 'capture-and-convert' ),
			'set_featured_image'		=> __( 'Upload Custom Icon', 'capture-and-convert' ),
			'remove_featured_image'	=> __( 'Remove Custom Icon', 'capture-and-convert' ),
			'use_featured_image'		=> __( 'Use Custom Icon', 'capture-and-convert' )
		),
    'description'        => __( 'Description.', 'capture-and-convert' ),
		'public'             => false,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_menu'       => false,
		'query_var'          => false,
		'rewrite'            => array( 'slug' => 'widget' ),
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title' )
	) );

	/**
	 * Register post type for 'Follow to Unlock' Widget
	 */
	register_post_type( 'ftu_widgets', array(
		'labels'						=> array(
			'name'									=> _x( 'Follow to Unlock Widgets', 'post type general name', 'capture-and-convert' ),
			'singular_name'					=> _x( 'Follow to Unlock Widget', 'post type singular name', 'capture-and-convert' ),
			'menu_name'							=> _x( 'Follow to Unlock Widgets', 'admin menu', 'capture-and-convert' ),
			'name_admin_bar'				=> _x( 'Follow to Unlock Widget', 'add new on admin bar', 'capture-and-convert' ),
			'add_new'								=> _x( '+ FtU Widget', 'book', 'capture-and-convert' ),
			'add_new_item'					=> __( 'Add New \'Follow to Unlock\' Widget', 'capture-and-convert' ),
			'new_item'							=> __( 'New Widget', 'capture-and-convert' ),
			'edit_item'							=> __( 'Edit Widget', 'capture-and-convert' ),
			'view_item'							=> __( 'View Widget', 'capture-and-convert' ),
			'all_items'							=> __( 'All Widgets', 'capture-and-convert' ),
			'search_items'					=> __( 'Search Widgets', 'capture-and-convert' ),
			'parent_item_colon'			=> __( 'Parent Widgets:', 'capture-and-convert' ),
			'not_found'							=> __( 'No widgets found.', 'capture-and-convert' ),
			'not_found_in_trash'		=> __( 'No widgets found in Trash.', 'capture-and-convert' ),
			'featured_image'				=> __( 'Custom Icon', 'capture-and-convert' ),
			'set_featured_image'		=> __( 'Upload Custom Icon', 'capture-and-convert' ),
			'remove_featured_image'	=> __( 'Remove Custom Icon', 'capture-and-convert' ),
			'use_featured_image'		=> __( 'Use Custom Icon', 'capture-and-convert' )
		),
		'description'        => __( 'Description.', 'capture-and-convert' ),
		'public'             => false,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_menu'       => false,
		'query_var'          => false,
		'rewrite'            => array( 'slug' => 'widget' ),
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title' )
	) );


	/**
	 * Register post type for 'Email to Unlock' Widget
	 */
	register_post_type( 'etu_widgets', array(
		'labels'             => array(
			'name'									=> _x( 'Email to Unlock Widgets', 'post type general name', 'capture-and-convert' ),
			'singular_name'					=> _x( 'Email to Unlock Widget', 'post type singular name', 'capture-and-convert' ),
			'menu_name'							=> _x( 'Email to Unlock Widgets', 'admin menu', 'capture-and-convert' ),
			'name_admin_bar'				=> _x( 'Email to Unlock Widget', 'add new on admin bar', 'capture-and-convert' ),
			'add_new'								=> _x( '+ EtU Widget', 'book', 'capture-and-convert' ),
			'add_new_item'					=> __( 'Add New \'Email to Unlock\' Widget', 'capture-and-convert' ),
			'new_item'							=> __( 'New Widget', 'capture-and-convert' ),
			'edit_item'							=> __( 'Edit Widget', 'capture-and-convert' ),
			'view_item'							=> __( 'View Widget', 'capture-and-convert' ),
			'all_items'							=> __( 'All Widgets', 'capture-and-convert' ),
			'search_items'					=> __( 'Search Widgets', 'capture-and-convert' ),
			'parent_item_colon'			=> __( 'Parent Widgets:', 'capture-and-convert' ),
			'not_found'							=> __( 'No widgets found.', 'capture-and-convert' ),
			'not_found_in_trash'		=> __( 'No widgets found in Trash.', 'capture-and-convert' ),
			'featured_image'				=> __( 'Custom Icon', 'capture-and-convert' ),
			'set_featured_image'		=> __( 'Upload Custom Icon', 'capture-and-convert' ),
			'remove_featured_image'	=> __( 'Remove Custom Icon', 'capture-and-convert' ),
			'use_featured_image'		=> __( 'Use Custom Icon', 'capture-and-convert' )
		),
		'description'        => __( 'Description.', 'capture-and-convert' ),
		'public'             => false,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_menu'       => false,
		'query_var'          => false,
		'rewrite'            => array( 'slug' => 'widget' ),
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title' )
	) );


	$templates		=	wpsl_locker_widget_templates();
	
	$post_id = (empty($_GET['post']) ? '' : sanitize_text_field($_GET['post']));
	
	if( is_admin() && $post_id > 0 ){
		$wid					=	$post_id;
		$locker_widget_template	=	get_post_meta( $wid, 'locker_widget_template', true );
	}else{
		$locker_widget_template	=	reset($templates); // ''; // $templates[0];
	}


	/**
	 * Metaboxes for 'Share to Unlock' widget
	 */
	 $stu_widgets_shortcode = new HD_WP_Metabox_API( array(
 		'metabox_title' => 'Shortcode',
 		'metabox_id'    => 'wpsl_stu_widgets_shortcode',
 		'post_type'     => 'stu_widgets',
 		'context'       => 'side',
 		'priority'      => 'core'
 	), array(
 	  'shortcode' => array(
			'title'   => 'Shortcode',
			'type'    => 'html',
			'html'    => '<code>[wpsl_locker id="%post_id%"][/wpsl_locker]</code> <a href="javascript:;" class="fas fa-copy wpslcnc_copy_to_clipboard"></a>',
			'sanit'   => 'fullhtml'
 		)
 	) );

	$stu_widgets_preview = new HD_WP_Metabox_API( array(
 		'metabox_title' => 'Preview',
 		'metabox_id'    => 'wpsl_stu_widgets_preview',
 		'post_type'     => 'stu_widgets',
		'context'       => 'side',
 		'priority'      => 'high'
 	), array(
 	  'shortcode' => array(
			'title'   => 'Preview',
			'type'    => 'html',
			'attrs'		=> array(
				'id'		=> 'wpsl_preview_container'
			),
			'html'    => '<div class="wpsl_locked_widget '.$locker_widget_template.'">'.
											wpsl_locker_widget_preview_html( $locker_widget_template, 'share' ).
										'</div>',
			'sanit'   => 'fullhtml'
 		)
 	) );

	$stu_widgets_template_changer = new HD_WP_Metabox_API( array(
 		'metabox_title' => 'Select Your Form\'s Design',
 		'metabox_id'    => 'wpsl_stu_widgets_metabox_temp_chngr',
 		'post_type'     => 'stu_widgets',
 		'context'       => 'normal',
 		'priority'      => 'high',
 	), array(
 		'locker_widget_template_html' => array(
			'title'   => 'Template Changer',
			'type'    => 'html',
			'attrs'		=> array(
				'onchange'	=> 'wpsl_preview_admin_template(this.value, \'share\')',
				'id'				=> 'wpsl_template_changer'
			),
			'desc'    => 'Start with one of our pre-designed forms and customize to your liking. Or, build your own from scratch.',
			'sanit'   => 'fullhtml',
			'html'		=> wpsl_template_changer_html('share')
 		),
		'locker_widget_template' => array(
			'title'   => 'Locker Widget Template',
			'type'    => 'register_only',
			'desc'    => 'Template for the locker widget',
			'sanit'   => 'nohtml'
 		)
 	) );

 	$stu_widgets_metabox = new HD_WP_Metabox_API( array(
 		'metabox_title' => 'Form Texts',
 		'metabox_id'    => 'wpsl_stu_widgets_metabox',
 		'post_type'     => 'stu_widgets',
 		'context'       => 'normal',
 		'priority'      => 'high',
 	), array(
 	  'locker_header' => array(
			'title'   => 'Headline',
			'type'    => 'text',
			'desc'    => '', // Type a header which attracts attention or calls to action. You can leave this field empty.',
			'sanit'   => 'nohtml',
 		),
 		'locker_message' => array(
			'title'   => 'Message',
			'type'    => 'editor',
			'desc'    => 'Type a message that will appear under header. <br>Shortcodes: %post_title%, %post_url%.',
			'sanit'   => 'html',
			'attrs'		=> array(
				'media_buttons'		=>	false
			),
			// NOTE: For future release
			// 'blur_hidden_text' => array(
			// 	'title'   => 'Show Blurred Text?',
			// 	'type'    => 'checkbox',
			// 	'desc'    => 'If checked, original text will be shown blurred.',
			// 	'sanit'   => 'nohtml',
			// )
 		)
 	) );

	$stu_widgets_customizer = new HD_WP_Metabox_API( array(
 		'metabox_title' => 'Customize Form Texts',
 		'metabox_id'    => 'wpsl_stu_widgets_customize',
 		'post_type'     => 'stu_widgets',
 		'context'       => 'normal',
 		'priority'      => 'low'
 	), array(
 	  'wpsl_customizer' => array(
			'title'   => 'Customize',
			'type'    => 'html',
			'attrs'		=> array(
				'id'		=> 'wpsl_customizer_container'
			),
			'html'    => wpsl_locker_widget_preview_customizer( '1', 'share' ),
			'sanit'   => 'fullhtml'
 		),
		'wpsl_customized_data' => array(
			'title'   => 'Customized Data',
			'type'    => 'register_only',
			'desc'    => 'Encoded data of customization',
			'sanit'   => 'fullhtml'
 		)
 	) );

	$stu_widgets_customizer2 = new HD_WP_Metabox_API( array(
 		'metabox_title' => 'Customize Your Form',
 		'metabox_id'    => 'wpsl_stu_widgets_customize2',
 		'post_type'     => 'stu_widgets',
 		'context'       => 'normal',
 		'priority'      => 'low'
 	), array(
 	  'wpsl_customizer' => array(
			'title'   => 'Customize',
			'type'    => 'html',
			'attrs'		=> array(
				'id'		=> 'wpsl_customizer_container2'
			),
			'html'    => wpsl_locker_widget_preview_customizer( '2', 'share' ),
			'sanit'   => 'fullhtml'
 		)
 	) );

	// $stu_widgets_customizer3 = new HD_WP_Metabox_API( array(
 // 		'metabox_title' => 'Select Your Form\'s Button Style',
 // 		'metabox_id'    => 'wpsl_stu_widgets_customize3',
 // 		'post_type'     => 'stu_widgets',
 // 		'context'       => 'normal',
 // 		'priority'      => 'low'
 // 	), array(
 // 	  'wpsl_customizer' => array(
	// 		'title'   => 'Customize',
	// 		'type'    => 'html',
	// 		'attrs'		=> array(
	// 			'id'		=> 'wpsl_customizer_container3'
	// 		),
	// 		'html'    => wpsl_locker_widget_preview_customizer( '3', 'share' ),
	// 		'sanit'   => 'fullhtml'
 // 		)
 // 	) );

	$stu_widgets_customizer4 = new HD_WP_Metabox_API( array(
 		'metabox_title' => 'Customize Button',
 		'metabox_id'    => 'wpsl_stu_widgets_customize4',
 		'post_type'     => 'stu_widgets',
 		'context'       => 'normal',
 		'priority'      => 'low'
 	), array(
 	  'wpsl_customizer' => array(
			'title'   => 'Customize',
			'type'    => 'html',
			'attrs'		=> array(
				'id'		=> 'wpsl_customizer_container4'
			),
			'html'    => wpsl_locker_widget_preview_customizer( '4', 'share' ),
			'sanit'   => 'fullhtml'
 		)
 	) );

	$stu_fb_share_social_options_metabox = new HD_WP_Metabox_API( array(
 		'metabox_title' => 'Facebook Share',
 		'metabox_id'    => 'wpsl_stu_fb_share_social_options_metabox',
 		'post_type'     => 'stu_widgets',
 		'context'       => 'normal',
 		'priority'      => 'high',
 	), array(
 	  'activate_facebook_share' => array(
			'title'   => 'Enable Facebook Share?',
			'type'    => 'checkbox',
			'desc'    => 'If this option is checked, Facebook share will be enabled.',
			'sanit'   => 'nohtml',
 		),
 		'fb_url_to_share' => array(
			'title'   => 'URL to Share',
			'type'    => 'text',
			'desc'    => 'Enter the url you want to be shared.',
			'sanit'   => 'url',
			'attrs'		=> array(
				'data-wpsl_show_activator'	=> 'activate_facebook_share'
			)
 		),
		'fb_share_button_title' => array(
			'title'   => 'Button Title',
			'type'    => 'text',
			'desc'    => 'Enter the title of button. Leave blank for default.',
			'sanit'   => 'nohtml',
			'attrs'		=> array(
				'data-wpsl_show_activator'	=> 'activate_facebook_share'
			)
 		)
 	) );

	$stu_tw_tweet_social_options_metabox = new HD_WP_Metabox_API( array(
 		'metabox_title' => 'Twitter Tweet',
 		'metabox_id'    => 'wpsl_stu_tw_tweet_social_options_metabox',
 		'post_type'     => 'stu_widgets',
 		'context'       => 'normal',
 		'priority'      => 'high',
 	), array(
 	  'activate_twitter_tweet' => array(
			'title'   => 'Enable Twitter Tweet?',
			'type'    => 'checkbox',
			'desc'    => 'If this option is checked, Twitter tweet will be enabled.',
			'sanit'   => 'nohtml',
 		),
 		'tw_tweet_msg' => array(
			'title'   => 'Tweet Message',
			'type'    => 'text',
			'desc'    => 'Enter the message you want people to be tweet.',
			'sanit'   => 'nohtml',
			'attrs'		=> array(
				'data-wpsl_show_activator'	=> 'activate_twitter_tweet'
			)
 		),
		'tw_tweet_button_title' => array(
			'title'   => 'Button Title',
			'type'    => 'text',
			'desc'    => 'Enter the title of button. Leave blank for default.',
			'sanit'   => 'nohtml',
			'attrs'		=> array(
				'data-wpsl_show_activator'	=> 'activate_twitter_tweet'
			)
 		)
 	) );


	$stu_powered_by_metabox = new HD_WP_Metabox_API( array(
		'metabox_title' => 'Powered By',
		'metabox_id'    => 'wpsl_stu_powered_by_metabox',
		'post_type'     => 'stu_widgets',
		'context'       => 'normal',
		'priority'      => 'low',
	), $powered_by_setting );


	/**
	 * Metaboxes for 'Follow to Unlock' widget
	 */
	 $ftu_widgets_shortcode = new HD_WP_Metabox_API( array(
 		'metabox_title' => 'Shortcode',
 		'metabox_id'    => 'wpsl_ftu_widgets_shortcode',
 		'post_type'     => 'ftu_widgets',
 		'context'       => 'side',
 		'priority'      => 'core'
 	), array(
 	  'shortcode' => array(
			'title'   => 'Shortcode',
			'type'    => 'html',
			'html'    => '<code>[wpsl_locker id="%post_id%"][/wpsl_locker]</code> <a href="javascript:;" class="fas fa-copy wpslcnc_copy_to_clipboard"></a>',
			'sanit'   => 'fullhtml'
 		)
 	) );

	$ftu_widgets_preview = new HD_WP_Metabox_API( array(
 		'metabox_title' => 'Preview',
 		'metabox_id'    => 'wpsl_ftu_widgets_preview',
 		'post_type'     => 'ftu_widgets',
 		'context'       => 'side',
 		'priority'      => 'high'
 	), array(
 	  'shortcode' => array(
			'title'   => 'Preview',
			'type'    => 'html',
			'attrs'		=> array(
				'id'		=> 'wpsl_preview_container'
			),
			'html'    => '<div class="wpsl_locked_widget '.$locker_widget_template.'">'.
											wpsl_locker_widget_preview_html( $locker_widget_template, 'follow' ).
										'</div>',
			'sanit'   => 'fullhtml'
 		)
 	) );

	$ftu_widgets_customizer = new HD_WP_Metabox_API( array(
 		'metabox_title' => 'Customize Form Texts',
 		'metabox_id'    => 'wpsl_ftu_widgets_customize',
 		'post_type'     => 'ftu_widgets',
 		'context'       => 'normal',
 		'priority'      => 'low'
 	), array(
 	  'wpsl_customizer' => array(
			'title'   => 'Customize',
			'type'    => 'html',
			'attrs'		=> array(
				'id'		=> 'wpsl_customizer_container'
			),
			'html'    => wpsl_locker_widget_preview_customizer( '1', 'follow' ),
			'sanit'   => 'fullhtml'
 		),
		'wpsl_customized_data' => array(
			'title'   => 'Customized Data',
			'type'    => 'register_only',
			'desc'    => 'Encoded data of customization',
			'sanit'   => 'fullhtml'
 		)
 	) );


	$ftu_widgets_customizer2 = new HD_WP_Metabox_API( array(
		'metabox_title' => 'Customize Your Form',
		'metabox_id'    => 'wpsl_ftu_widgets_customize2',
		'post_type'     => 'ftu_widgets',
		'context'       => 'normal',
		'priority'      => 'low'
	), array(
		'wpsl_customizer' => array(
			'title'   => 'Customize',
			'type'    => 'html',
			'attrs'		=> array(
				'id'		=> 'wpsl_customizer_container2'
			),
			'html'    => wpsl_locker_widget_preview_customizer( '2', 'follow' ),
			'sanit'   => 'fullhtml'
		)
	) );

	// $ftu_widgets_customizer3 = new HD_WP_Metabox_API( array(
	// 	'metabox_title' => 'Select Your Form\'s Button Style',
	// 	'metabox_id'    => 'wpsl_ftu_widgets_customize3',
	// 	'post_type'     => 'ftu_widgets',
	// 	'context'       => 'normal',
	// 	'priority'      => 'low'
	// ), array(
	// 	'wpsl_customizer' => array(
	// 		'title'   => 'Customize',
	// 		'type'    => 'html',
	// 		'attrs'		=> array(
	// 			'id'		=> 'wpsl_customizer_container3'
	// 		),
	// 		'html'    => wpsl_locker_widget_preview_customizer( '3', 'follow' ),
	// 		'sanit'   => 'fullhtml'
	// 	)
	// ) );

	$ftu_widgets_customizer4 = new HD_WP_Metabox_API( array(
		'metabox_title' => 'Customize Button',
		'metabox_id'    => 'wpsl_ftu_widgets_customize4',
		'post_type'     => 'ftu_widgets',
		'context'       => 'normal',
		'priority'      => 'low'
	), array(
		'wpsl_customizer' => array(
			'title'   => 'Customize',
			'type'    => 'html',
			'attrs'		=> array(
				'id'		=> 'wpsl_customizer_container4'
			),
			'html'    => wpsl_locker_widget_preview_customizer( '4', 'follow' ),
			'sanit'   => 'fullhtml'
		)
	) );

	$ftu_widgets_template_changer = new HD_WP_Metabox_API( array(
 		'metabox_title' => 'Select Your Form\'s Design',
 		'metabox_id'    => 'wpsl_ftu_widgets_metabox_temp_chngr',
 		'post_type'     => 'ftu_widgets',
 		'context'       => 'normal',
 		'priority'      => 'high',
 	), array(
 		'locker_widget_template_html' => array(
			'title'   => 'Template Changer',
			'type'    => 'html',
			'attrs'		=> array(
				'onchange'	=> 'wpsl_preview_admin_template(this.value, \'follow\')',
				'id'				=> 'wpsl_template_changer'
			),
			'desc'    => 'Start with one of our pre-designed forms and customize to your liking. Or, build your own from scratch.',
			'sanit'   => 'fullhtml',
			'html'		=> wpsl_template_changer_html('follow')
 		),
		'locker_widget_template' => array(
			'title'   => 'Locker Widget Template',
			'type'    => 'register_only',
			'desc'    => 'Template for the locker widget',
			'sanit'   => 'nohtml'
 		)
 	) );

 	$ftu_widgets_metabox = new HD_WP_Metabox_API( array(
 		'metabox_title' => 'Form Texts',
 		'metabox_id'    => 'wpsl_ftu_widgets_metabox',
 		'post_type'     => 'ftu_widgets',
 		'context'       => 'normal',
 		'priority'      => 'high',
 	), array(
 	  'locker_header' => array(
			'title'   => 'Headline',
			'type'    => 'text',
			'desc'    => '', // 'Type a header which attracts attention or calls to action. You can leave this field empty.',
			'sanit'   => 'nohtml',
 		),
 		'locker_message' => array(
			'title'   => 'Message',
			'type'    => 'editor',
			'desc'    => 'Type a message that will appear under header. <br>Shortcodes: %post_title%, %post_url%.',
			'sanit'   => 'html',
			'attrs'		=> array(
				'media_buttons'		=>	false
			)
		),
		// NOTE: For future release
		/*'blur_hidden_text' => array(
			'title'   => 'Show Blurred Text?',
			'type'    => 'checkbox',
			'desc'    => 'If checked, original text will be shown blurred.',
			'sanit'   => 'nohtml',
 		)/*,
 		'locker_widget_template' => array(
			'title'   => 'Locker Widget Template',
			'type'    => 'select',
			'attrs'		=> array(
				'onchange'	=> 'wpsl_preview_admin_template(this.value, \'follow\')'
			),
			'desc'    => 'Select the template for Locker Widget.',
			'sanit'   => 'nohtml',
			'choices'	=> wpsl_list_lockers_templates('follow')
 		)*/
 	) );

	$ftu_insta_follow_social_options_metabox = new HD_WP_Metabox_API( array(
 		'metabox_title' => 'Instagram Follow',
 		'metabox_id'    => 'wpsl_ftu_insta_follow_social_options_metabox',
 		'post_type'     => 'ftu_widgets',
 		'context'       => 'normal',
 		'priority'      => 'high',
 	), array(
 	  'activate_instagram_follow' => array(
			'title'   => 'Enable Instagram Follow?',
			'type'    => 'checkbox',
			'desc'    => 'If this option is checked, Facebook like will be enabled.',
			'sanit'   => 'nohtml',
 		),
 		'insta_url_to_follow' => array(
			'title'   => 'URL of Profile',
			'type'    => 'text',
			'desc'    => 'Enter the url of profile you want to be followed.',
			'sanit'   => 'url',
			'attrs'		=> array(
				'data-wpsl_show_activator'	=> 'activate_instagram_follow'
			)
 		),
		'insta_follow_button_title' => array(
			'title'   => 'Button Title',
			'type'    => 'text',
			'desc'    => 'Enter the title of button. Leave blank for default.',
			'sanit'   => 'nohtml',
			'attrs'		=> array(
				'data-wpsl_show_activator'	=> 'activate_instagram_follow'
			)
 		)
 	) );

	$ftu_yt_subscribe_metabox = new HD_WP_Metabox_API( array(
 		'metabox_title' => 'Youtube Subscribe',
 		'metabox_id'    => 'wpsl_ftu_yt_subscribe_metabox',
 		'post_type'     => 'ftu_widgets',
 		'context'       => 'normal',
 		'priority'      => 'high',
 	), array(
 	  'activate_youtube_subscribe' => array(
			'title'   => 'Enable Youtube Subscribe?',
			'type'    => 'checkbox',
			'desc'    => 'Check this if you want Youtube Subscribe button to show.',
			'sanit'   => 'nohtml',
 		),
 		'channel_id_to_subscribe' => array(
			'title'   => 'Channel ID to be Subscribed <a href="https://support.google.com/youtube/answer/3250431?hl=en" target="_blank">Read More</a>.',
			'type'    => 'text',
			'desc'    => 'Enter your Channel ID to be Subscribed.',
			'sanit'   => 'nohtml',
			'attrs'		=> array(
				'data-wpsl_show_activator'	=> 'activate_youtube_subscribe'
			)
 		),
 		'yt_subscribe_button_text' => array(
			'title'   => 'Button Text',
			'type'    => 'text',
			'desc'    => 'Enter the text that button should read.',
			'sanit'   => 'nohtml',
			'attrs'		=> array(
				'data-wpsl_show_activator'	=> 'activate_youtube_subscribe'
			)
 		)
 	) );

	$ftu_tw_follow_metabox = new HD_WP_Metabox_API( array(
 		'metabox_title' => 'Twitter Follow',
 		'metabox_id'    => 'wpsl_ftu_tw_follow_metabox',
 		'post_type'     => 'ftu_widgets',
 		'context'       => 'normal',
 		'priority'      => 'high',
 	), array(
 	  'activate_twitter_follow' => array(
			'title'   => 'Enable Twitter Follow?',
			'type'    => 'checkbox',
			'desc'    => 'Check this if you want Twitter Follow button to show.',
			'sanit'   => 'nohtml',
 		),
 		'tw_username_to_follow' => array(
			'title'   => 'Twitter Username to be Followed',
			'type'    => 'text',
			'desc'    => 'Enter your Twitter Username to be Followed, without "@" sign or twitter.com',
			'sanit'   => 'nohtml',
			'attrs'		=> array(
				'data-wpsl_show_activator'	=> 'activate_twitter_follow'
			)
 		),
 		'tw_follow_button_text' => array(
			'title'   => 'Button Text',
			'type'    => 'text',
			'desc'    => 'Enter the text that button should read.',
			'sanit'   => 'nohtml',
			'attrs'		=> array(
				'data-wpsl_show_activator'	=> 'activate_twitter_follow'
			)
 		)
 	) );


	$ftu_powered_by_metabox = new HD_WP_Metabox_API( array(
		'metabox_title' => 'Powered By',
		'metabox_id'    => 'wpsl_ftu_powered_by_metabox',
		'post_type'     => 'ftu_widgets',
		'context'       => 'normal',
		'priority'      => 'low',
	), $powered_by_setting );


	/**
	 * Metaboxes for 'Email to Unlock' widget
	 */
	$etu_widgets_shortcode = new HD_WP_Metabox_API( array(
 		'metabox_title' => 'Shortcode',
 		'metabox_id'    => 'wpsl_etu_widgets_shortcode',
 		'post_type'     => 'etu_widgets',
 		'context'       => 'side',
 		'priority'      => 'core'
 	), array(
 	  'shortcode' => array(
			'title'   => 'Shortcode',
			'type'    => 'html',
			'html'    => '<code>[wpsl_locker id="%post_id%"][/wpsl_locker]</code> <a href="javascript:;" class="fas fa-copy wpslcnc_copy_to_clipboard"></a>',
			'sanit'   => 'fullhtml'
 		)
 	) );

	$etu_widgets_preview = new HD_WP_Metabox_API( array(
 		'metabox_title' => 'Preview',
 		'metabox_id'    => 'wpsl_etu_widgets_preview',
 		'post_type'     => 'etu_widgets',
 		'context'       => 'side',
 		'priority'      => 'high'
 	), array(
 	  'shortcode' => array(
			'title'   => 'Preview',
			'type'    => 'html',
			'attrs'		=> array(
				'id'		=> 'wpsl_preview_container'
			),
			'html'    => '<div class="wpsl_locked_widget '.$locker_widget_template.'">'.
											wpsl_locker_widget_preview_html( $locker_widget_template, 'email' ).
										'</div>',
			'sanit'   => 'fullhtml'
 		),
		'form_lines' => array(
			'title'   => 'Form Lines',
			'type'    => 'register_only',
			'desc'    => 'Single or Double line form',
			'sanit'   => 'nohtml'
 		)
 	) );

	$etu_widgets_template_changer = new HD_WP_Metabox_API( array(
		'metabox_title' => 'Select Your Form\'s Design',
		'metabox_id'    => 'wpsl_etu_widgets_metabox_temp_chngr',
		'post_type'     => 'etu_widgets',
		'context'       => 'normal',
		'priority'      => 'high',
	), array(
		'locker_widget_template_html' => array(
			'title'   => 'Template Changer',
			'type'    => 'html',
			'attrs'		=> array(
				'onchange'	=> 'wpsl_preview_admin_template(this.value, \'email\')',
				'id'				=> 'wpsl_template_changer'
			),
			'desc'    => 'Start with one of our pre-designed forms and customize to your liking. Or, build your own from scratch.',
			'sanit'   => 'fullhtml',
			'html'		=> wpsl_template_changer_html('email')
		),
		'locker_widget_template' => array(
			'title'   => 'Locker Widget Template',
			'type'    => 'register_only',
			'desc'    => 'Template for the locker widget',
			'sanit'   => 'nohtml'
		)
	) );

	$etu_widgets_customizer = new HD_WP_Metabox_API( array(
 		'metabox_title' => 'Customize Form Texts',
 		'metabox_id'    => 'wpsl_etu_widgets_customize',
 		'post_type'     => 'etu_widgets',
 		'context'       => 'normal',
 		'priority'      => 'low'
 	), array(
 	  'wpsl_customizer' => array(
			'title'   => 'Customize',
			'type'    => 'html',
			'attrs'		=> array(
				'id'		=> 'wpsl_customizer_container'
			),
			'html'    => wpsl_locker_widget_preview_customizer( '1', 'email' ),
			'sanit'   => 'fullhtml'
 		),
		'wpsl_customized_data' => array(
			'title'   => 'Customized Data',
			'type'    => 'register_only',
			'desc'    => 'Encoded data of customization',
			'sanit'   => 'fullhtml'
 		)
 	) );


	$etu_widgets_customizer2 = new HD_WP_Metabox_API( array(
		'metabox_title' => 'Customize Your Form',
		'metabox_id'    => 'wpsl_etu_widgets_customize2',
		'post_type'     => 'etu_widgets',
		'context'       => 'normal',
		'priority'      => 'low'
	), array(
		'wpsl_customizer' => array(
			'title'   => 'Customize',
			'type'    => 'html',
			'attrs'		=> array(
				'id'		=> 'wpsl_customizer_container2'
			),
			'html'    => wpsl_locker_widget_preview_customizer( '2', 'email' ),
			'sanit'   => 'fullhtml'
		)
	) );

	// $etu_widgets_customizer3 = new HD_WP_Metabox_API( array(
	// 	'metabox_title' => 'Select Your Form\'s Button Style',
	// 	'metabox_id'    => 'wpsl_etu_widgets_customize3',
	// 	'post_type'     => 'etu_widgets',
	// 	'context'       => 'normal',
	// 	'priority'      => 'low'
	// ), array(
	// 	'wpsl_customizer' => array(
	// 		'title'   => 'Customize',
	// 		'type'    => 'html',
	// 		'attrs'		=> array(
	// 			'id'		=> 'wpsl_customizer_container3'
	// 		),
	// 		'html'    => wpsl_locker_widget_preview_customizer( '3', 'email' ),
	// 		'sanit'   => 'fullhtml'
	// 	)
	// ) );

	$etu_widgets_customizer4 = new HD_WP_Metabox_API( array(
		'metabox_title' => 'Customize Button',
		'metabox_id'    => 'wpsl_etu_widgets_customize4',
		'post_type'     => 'etu_widgets',
		'context'       => 'normal',
		'priority'      => 'low'
	), array(
		'wpsl_customizer' => array(
			'title'   => 'Customize',
			'type'    => 'html',
			'attrs'		=> array(
				'id'		=> 'wpsl_customizer_container4'
			),
			'html'    => wpsl_locker_widget_preview_customizer( '4', 'email' ),
			'sanit'   => 'fullhtml'
		)
	) );


 	$etu_widgets_metabox = new HD_WP_Metabox_API( array(
 		'metabox_title' => 'Form Texts',
 		'metabox_id'    => 'wpsl_etu_widgets_metabox',
 		'post_type'     => 'etu_widgets',
 		'context'       => 'normal',
 		'priority'      => 'high',
 	), array(
 	  'locker_header' => array(
			'title'   => 'Headline',
			'type'    => 'text',
			'desc'    => '', // 'Type a header which attracts attention or calls to action. You can leave this field empty.',
			'sanit'   => 'nohtml',
 		),
 		'locker_message' => array(
			'title'   => 'Message',
			'type'    => 'editor',
			'desc'    => 'Type a message that will appear under header. <br>Shortcodes: %post_title%, %post_url%.',
			'sanit'   => 'html',
			'attrs'		=> array(
				'media_buttons'		=>	false
			)
 		),
		'locker_submit_label' => array(
			'title'   => 'Button Label',
			'type'    => 'text',
			'desc'    => 'Type text for submit button label.',
			'sanit'   => 'nohtml',
		),
		// NOTE: For future release
		/*'blur_hidden_text' => array(
			'title'   => 'Show Blurred Text?',
			'type'    => 'checkbox',
			'desc'    => 'If checked, original text will be shown blurred.',
			'sanit'   => 'nohtml',
 		),
		/*,
 		'locker_widget_template' => array(
			'title'   => 'Locker Widget Template',
			'type'    => 'select',
			'attrs'		=> array(
				'onchange'	=> 'wpsl_preview_admin_template(this.value, \'email\')'
			),
			'desc'    => 'Select the template for Locker Widget.',
			'sanit'   => 'nohtml',
			'choices'	=> wpsl_list_lockers_templates('email')
 		)*/
 	) );

	$etu_email_inter_metabox = new HD_WP_Metabox_API( array(
 		'metabox_title' => 'Email Integrations',
 		'metabox_id'    => 'wpsl_etu_email_inter_metabox',
 		'post_type'     => 'etu_widgets',
 		'context'       => 'normal',
 		'priority'      => 'high',
 	), array(
		'wpsl_widget_activecampaign_list' => array(
			'title'   => 'ActiveCampaign List',
			'type'    => 'select',
			'desc'    => 'Select the ActiveCampaign list for this form <br /><small>(not seeing anything? update api details <a href="admin.php?page=capture-and-convert-menu&tab=wpsl_leads_integration&section=activecampaign">here</a> and make sure to regenerate cache)</small>.',
			'sanit'   => 'nohtml',
			'choices'	=> array( "0" => 'Please Select') + (array) wpsl_list_activecampaign_lists()
 		),
		'wpsl_widget_mailchimp_list' => array(
			'title'   => 'Mailchimp List',
			'type'    => 'select',
			'desc'    => 'Select the Mailchimp list for this form <br /><small>(not seeing anything? update api details <a href="admin.php?page=capture-and-convert-menu&tab=wpsl_leads_integration&section=mailchimp">here</a> and make sure to regenerate cache)</small>.',
			'sanit'   => 'nohtml',
			'choices'	=> array( "0" => 'Please Select') + (array) wpsl_list_mailchimp_lists()
 		),
		'wpsl_widget_constantcontact_list' => array(
			'title'   => 'ConstantContact List',
			'type'    => 'select',
			'desc'    => 'Select the ConstantContact list for this form <br /><small>(not seeing anything? update api details <a href="admin.php?page=capture-and-convert-menu&tab=wpsl_leads_integration&section=constantcontact">here</a> and make sure to regenerate cache)</small>.',
			'sanit'   => 'nohtml',
			'choices'	=> array( "0" => 'Please Select') + (array) wpsl_list_constantcontact_lists()
		),
		'wpsl_widget_aweber_list' => array(
			'title'   => 'Aweber List',
			'type'    => 'select',
			'desc'    => 'Select the Aweber list for this form <br /><small>(not seeing anything? update api details <a href="admin.php?page=capture-and-convert-menu&tab=wpsl_leads_integration&section=aweber">here</a> and make sure to regenerate cache)</small>.',
			'sanit'   => 'nohtml',
			'choices'	=> array( "0" => 'Please Select') + (array) wpsl_list_aweber_lists()
		),
		'wpsl_widget_drip_campaign' => array(
			'title'   => 'Drip Campaigns',
			'type'    => 'select',
			'desc'    => 'Select the Drip campaign for this form <br /><small>(not seeing anything? update api details <a href="admin.php?page=capture-and-convert-menu&tab=wpsl_leads_integration&section=drip">here</a> and make sure to regenerate cache)</small>.',
			'sanit'   => 'nohtml',
			'choices'	=> array( "0" => 'Please Select') + (array) wpsl_list_drip_campaigns()
		),
		'wpsl_widget_hubspot_list' => array(
			'title'   => 'HubSpot List',
			'type'    => 'select',
			'desc'    => 'Select the HubSpot list for this form <br /><small>(not seeing anything? update api details <a href="admin.php?page=capture-and-convert-menu&tab=wpsl_leads_integration&section=hubspot">here</a> and make sure to regenerate cache)</small>.',
			'sanit'   => 'nohtml',
			'choices'	=> array( "0" => 'Please Select') + (array) wpsl_list_hubspot_lists()
		),
		'wpsl_widget_campaignmonitor_list' => array(
			'title'   => 'Campaign Monitor List',
			'type'    => 'select',
			'desc'    => 'Select the Campaign Monitor list for this form <br /><small>(not seeing anything? update api details <a href="admin.php?page=capture-and-convert-menu&tab=wpsl_leads_integration&section=campaignmonitor">here</a> and make sure to regenerate cache)</small>.',
			'sanit'   => 'nohtml',
			'choices'	=> array( "0" => 'Please Select') + (array) wpsl_list_campaignmonitor_lists()
		),
		'wpsl_widget_getresponse_list' => array(
			'title'   => 'GetResponse List',
			'type'    => 'select',
			'desc'    => 'Select the GetResponse list for this form <br /><small>(not seeing anything? update api details <a href="admin.php?page=capture-and-convert-menu&tab=wpsl_leads_integration&section=getresponse">here</a> and make sure to regenerate cache)</small>.',
			'sanit'   => 'nohtml',
			'choices'	=> array( "0" => 'Please Select') + (array) wpsl_list_getresponse_lists()
		),
		'wpsl_widget_convertkit_form' => array(
			'title'   => 'ConvertKit Form',
			'type'    => 'select',
			'desc'    => 'Select the ConvertKit form for this form <br /><small>(not seeing anything? update api details <a href="admin.php?page=capture-and-convert-menu&tab=wpsl_leads_integration&section=convertkit">here</a> and make sure to regenerate cache)</small>.',
			'sanit'   => 'nohtml',
			'choices'	=> array( "0" => 'Please Select') + (array) wpsl_list_convertkit_forms()
 		)
	) );

	$etu_name_options_metabox = new HD_WP_Metabox_API( array(
 		'metabox_title' => 'Fields',
 		'metabox_id'    => 'wpsl_etu_name_options_metabox',
 		'post_type'     => 'etu_widgets',
 		'context'       => 'normal',
 		'priority'      => 'high',
 	), array(
 	  'name_field' => array(
			'title'   => 'Enable Name Field?',
			'type'    => 'checkbox',
			'desc'    => 'If this option is checked, name option will be available too.',
			'sanit'   => 'nohtml',
 		),
		'wpsl_customizer5' => array(
			'title'   => 'Customize',
			'type'    => 'html',
			'attrs'		=> array(
				'id'		=> 'wpsl_customizer_container5'
			),
			'html'    => wpsl_locker_widget_preview_customizer( '5', 'email' ),
			'sanit'   => 'fullhtml'
		)
 	) );

	$etu_powered_by_metabox = new HD_WP_Metabox_API( array(
		'metabox_title' => 'Powered By',
		'metabox_id'    => 'wpsl_etu_powered_by_metabox',
		'post_type'     => 'etu_widgets',
		'context'       => 'normal',
		'priority'      => 'low',
	), $powered_by_setting );


}


/**
 * Publish metabox should always be on top
 */
add_filter( 'get_user_option_meta-box-order_stu_widgets', 'wpcnc_submitdiv_always_on_top' );
add_filter( 'get_user_option_meta-box-order_ftu_widgets', 'wpcnc_submitdiv_always_on_top' );
add_filter( 'get_user_option_meta-box-order_etu_widgets', 'wpcnc_submitdiv_always_on_top' );

function wpcnc_submitdiv_always_on_top( $order ) {

	global $wp_meta_boxes, $post_type;
	
	$side_meta_boxes = array_merge( 
		array_keys( $wp_meta_boxes[$post_type]['side']['high'] ),
		array_keys( $wp_meta_boxes[$post_type]['side']['core'] )
	);

	$order['side'] = 'submitdiv,' . implode( ',', array_diff($side_meta_boxes, array('submitdiv')) );
	
	return $order;

}
