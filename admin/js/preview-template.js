var wpsl_updating_template	=	false;

function wpsl_responsive_widgets_hnd(){

	jQuery('.wpsl_locker_widget_cont').each(function( i, el ) {

		if( jQuery(el).outerWidth() > 850 ){
			jQuery(el).removeClass('res470').removeClass('res600').removeClass('res760').removeClass('res850');
		}else if( jQuery(el).outerWidth() > 760 ){
			jQuery(el).removeClass('res470').removeClass('res600').removeClass('res760').addClass('res850');
		}else if( jQuery(el).outerWidth() > 600 ){
			jQuery(el).removeClass('res470').removeClass('res600').addClass('res760').addClass('res850');
		}else if( jQuery(el).outerWidth() > 470 ){
			jQuery(el).removeClass('res470').addClass('res600').addClass('res760').addClass('res850');
		}else{
			jQuery(el).addClass('res470').addClass('res600').addClass('res760').addClass('res850');
		}

		if( jQuery(el).outerWidth() > 850 ){
			if( jQuery(el).find('.wpsl_email_locker_icon.dashicons').length ){
				jQuery(el).find('.wpsl_left_icon').css('height', '');
				jQuery(el).find('.wpsl_email_locker_icon.dashicons').css('font-size', '').css('line-height', '');
			}
		}else{
			if( jQuery(el).find('.wpsl_email_locker_icon.dashicons').length ){

				var iwid = jQuery(el).find('.wpsl_left_icon').outerWidth();

				if( iwid ){
					jQuery(el).find('.wpsl_left_icon').css('height', iwid+'px');
					jQuery(el).find('.wpsl_email_locker_icon.dashicons').css('font-size', iwid*0.66+'px').css('line-height', iwid+'px');
				}else{
					var iwid = jQuery(el).find('.wpsl_email_locker_icon.dashicons').outerWidth();
					var ifns = parseInt(jQuery(el).find('.wpsl_email_locker_icon.dashicons').css('font-size'));
					if( iwid != ifns ){
						jQuery(el).find('.wpsl_email_locker_icon.dashicons').css('font-size', iwid+'px');
					}
				}

			}
		}

	});

}

jQuery(window).on('resize', wpsl_responsive_widgets_hnd );

window.onload = function () {

	wpsl_responsive_widgets_hnd();

	jQuery("#name_field").change();
	jQuery('#locker_header').keyup();
	jQuery('.wpsl_customizer_desc').html(tinymce.get('locker_message').getContent());
	jQuery('#button_label').keyup();

	if( typeof tinymce != 'undefined' ){

		if( tinymce.get('locker_message') != null ){

			tinymce.get('locker_message').on('keyup',function(e){
				jQuery('.wpsl_customizer_desc').html( this.getContent() );
			});

		}

		if( tinymce.get('powered_by_text') != null ){

			tinymce.get('powered_by_text').on('keyup',function(e){
				jQuery('.wpsl_credit').html( this.getContent() );
			});

		}

		if( tinymce.get('locker_message') != null ){
			jQuery('.wpsl_customizer_desc').html(tinymce.get('locker_message').getContent());
		}

		if( tinymce.get('powered_by_text') != null ){
			jQuery('.wpsl_credit').html(tinymce.get('powered_by_text').getContent());
		}

	}

	jQuery('.wpsl_customizer_section input, .wpsl_customizer_section textarea').keyup();
	jQuery('.wpsl_customizer_section select').change();

	if( jQuery('#locker_widget_template').val() == 'ImageBG' ){

		if( jQuery('#name_field').is(':checked') ){
			var ismobb = jQuery('.wpsl_email_widget').outerWidth() < 610;
			jQuery('.wpsl_email_widget .wpsl_customizer_textbox:first').css('width', ( ismobb ? '47%' : '49%')).css('margin-left', 0).css('margin-right', '1%');
			jQuery('.wpsl_email_widget .wpsl_customizer_textbox:last').css('width', '49%').css('margin-left', 0).css('margin-right', 0);
		}else{
			jQuery('.wpsl_email_widget .wpsl_customizer_textbox').css('width', '100%').css('margin-left', 0).css('margin-right', 0);
		}

		jQuery('.wpsl_email_widget .wpsl_submit_button').css('width', '100%').css('margin-left', 0).css('margin-right', 0).css('max-width', 'none').css('margin-top', '10px');

	}

	if( jQuery('.wpsl_temp_to_sel.selected').length === 0 ){
		jQuery('.wpsl_temp_to_sel:first a').trigger('click');
	}

	if( jQuery('#wpsl_fl_btn_loc_left').prop('checked') ){
		jQuery('#wpsl_preview_container').addClass('left');
	}else{
		jQuery('#wpsl_preview_container').addClass('right');
	}

	jQuery('#wpsl_stu_widgets_preview, #wpsl_etu_widgets_preview, #wpsl_ftu_widgets_preview, #wpsl_fw_widgets_preview').on('click', function(){
		jQuery(window).trigger('resize');
	});

};

jQuery(document).ready(function(){

	jQuery('body').on('click', '.wpslcnc_remove_api', function(){

		confirm_del = confirm('This will unlink and delete api details of ' + jQuery(this).data('api_name') + ' from this website. Are you sure you want to continue?');

		if( confirm_del ){
			window.location.href = jQuery(this).data('del_url');
		}

	});

	jQuery('#toplevel_page_capture-and-convert-menu ul li').last().find('a').attr('target','_blank');

	jQuery('body').on('click', '#wpsl_hide_upgrade_notice', function (){

		jQuery.ajax({
			method: "POST",
			url: wpsl_admin_ajax.ajaxurl,
			timeout: 0,
			data: {
				'action': 'wpsl_hide_upgrade_notice'
			}
		}).done(function( content ){
			jQuery('.wpsl_admin_notification').hide(200);
		});

	});


	jQuery('body').on('change', '.wpsl_gradient_toggler', function (){

		if( jQuery(this).is(':checked') ){
			jQuery(this).parents('.wpsl_customizer_section').find('.gradient_option').show();
			jQuery(this).parents('.wpsl_customizer_section').find('.wpsl_plain_color').hide();
		}else{
			jQuery(this).parents('.wpsl_customizer_section').find('.gradient_option').hide();
			jQuery(this).parents('.wpsl_customizer_section').find('.wpsl_plain_color').show();
		}

	});

	jQuery('body').on('change', '#wpsl_fw_powered_by_metabox input, #wpsl_etu_powered_by_metabox input, #wpsl_ftu_powered_by_metabox input, #wpsl_stu_powered_by_metabox input', function (){

		if( jQuery(this).val() == 'light' ){
			jQuery('.wpsl_credit').removeClass('dark');
			jQuery('.wpsl_credit').removeClass('gold');
			jQuery('.wpsl_credit').removeClass('clear');
			jQuery('.wpsl_credit').addClass('light');
		}else if( jQuery(this).val() == 'gold' ){
			jQuery('.wpsl_credit').removeClass('dark');
			jQuery('.wpsl_credit').removeClass('light');
			jQuery('.wpsl_credit').removeClass('clear');
			jQuery('.wpsl_credit').addClass('gold');
		}else if( jQuery(this).val() == 'clear' ){
			jQuery('.wpsl_credit').removeClass('gold');
			jQuery('.wpsl_credit').removeClass('light');
			jQuery('.wpsl_credit').removeClass('dark');
			jQuery('.wpsl_credit').addClass('clear');
		}else{
			jQuery('.wpsl_credit').removeClass('gold');
			jQuery('.wpsl_credit').removeClass('light');
			jQuery('.wpsl_credit').removeClass('clear');
			jQuery('.wpsl_credit').addClass('dark');
		}

	});

	jQuery('body').on('change', '.wpsl_icon_img_toggler', function (){

		if( jQuery(this).is(':checked') ){
			jQuery(this).parents('.wpsl_customizer_section').find('.wpsl_custom_img_icon').show();
			jQuery(this).parents('.wpsl_customizer_section').find('.wpsl_dashicon_icon').hide();
		}else{
			jQuery(this).parents('.wpsl_customizer_section').find('.wpsl_custom_img_icon').hide();
			jQuery(this).parents('.wpsl_customizer_section').find('.wpsl_dashicon_icon').show();
		}

	});


	jQuery('body').on('change', '#name_field', function (){

		if( jQuery(this).is(':checked') ){

			jQuery('.wpsl_email_widget input[name=first_name]').show();
			jQuery('.wpsl_email_widget .wpsl_customizer_textbox').css('width', "29%").css('margin-left', 0).css('margin-right', 0);

		}else{

			jQuery('.wpsl_email_widget input[name=first_name]').hide();
			jQuery('.wpsl_email_widget .wpsl_customizer_textbox').css('width', "58%").css('margin-left', 0).css('margin-right', 0);

		}

	});


	jQuery('#wpsl_custom_img_btn').click(function(e) {

		e.preventDefault();
		var image_frame;

		if(image_frame){
			image_frame.open();
		}

		image_frame = wp.media({
			title: 'Select Custom Icon',
			multiple : false,
			library : {
				type : 'image',
			}
		});

		image_frame.on('close',function() {

			var attachment = image_frame.state().get('selection').first().toJSON();

			if( typeof attachment.sizes.medium == 'undefined' ){
				var thefurl	=	attachment.url;
			}else{
				var thefurl	=	attachment.sizes.medium.url;
			}

			jQuery('#custom_image_icon').val( thefurl );
			jQuery('#wpsl_custom_img_btn').attr('value', 'Change Icon');
			jQuery('#wpsl_custom_img_btn').parent().find('.wpsl_preview_icon_sd').remove();
			jQuery('#wpsl_custom_img_btn').parent().append('<img class="wpsl_preview_icon_sd" src="' + thefurl + '" />');

			var icon_hhh	=	jQuery('.wpsl_email_locker_icon');
			icon_hhh.removeAttr('class');
			icon_hhh.addClass('wpsl_email_locker_icon');
			icon_hhh.html('<img src="' + thefurl + '" />');

		});

		image_frame.open();

	});

	jQuery('#custom_fw_btn_icon_btn').click(function(e) {

		e.preventDefault();
		var image_frame;

		if(image_frame){
			image_frame.open();
		}

		image_frame = wp.media({
			title: 'Select Custom Icon',
			multiple : false,
			library : {
				type : 'image',
			}
		});

		image_frame.on('close',function() {

			var attachment = image_frame.state().get('selection').first().toJSON();

			if( typeof attachment.sizes.medium == 'undefined' ){
				var thefurl	=	attachment.url;
			}else{
				var thefurl	=	attachment.sizes.medium.url;
			}

			jQuery('#custom_fw_btn_icon').val( thefurl );
			jQuery('#custom_fw_btn_icon_btn').attr('value', 'Change Icon');
			jQuery('#custom_fw_btn_icon_btn').parent().find('.wpsl_preview_icon_sd').remove();
			jQuery('#custom_fw_btn_icon_btn').parent().append('<img class="wpsl_preview_icon_sd" src="' + thefurl + '" />');


			var icon_hhh	=	jQuery('.wpslfw_button');
			icon_hhh.css('background', 'none');
			icon_hhh.html('<img src="' + thefurl + '" />');

		});

		image_frame.open();

	});

	jQuery('#wpsl_header_img_btn').click(function(e) {

		e.preventDefault();
		var image_frame;

		if(image_frame){
			image_frame.open();
		}

		image_frame = wp.media({
			title: 'Select Header Image',
			multiple : false,
			library : {
				type : 'image',
			}
		});

		image_frame.on('close',function() {
			var attachment = image_frame.state().get('selection').first().toJSON();
			jQuery('#wpsl_header_img').val( attachment.url );
			jQuery('.wpsl_header_img').html( '<img src="' + attachment.url + '" />' );
			jQuery('#wpsl_header_img_btn').attr('value', 'Change Image');
			jQuery('#wpsl_header_img_btn').parent().find('.wpsl_preview_icon_sd').remove();
			jQuery('#wpsl_header_img_btn').parent().append('<img class="wpsl_preview_icon_sd" src="' + attachment.url + '" />');

		});

		image_frame.open();

	});


	jQuery('#wpsl_bg_img_btn').click(function(e) {

		e.preventDefault();
		var image_frame;

		if(image_frame){
			image_frame.open();
		}

		image_frame = wp.media({
			title: 'Select Background Image',
			multiple : false,
			library : {
				type : 'image',
			}
		});

		image_frame.on('close',function() {
			var attachment = image_frame.state().get('selection').first().toJSON();
			jQuery('#wpsl_bg_img').val( attachment.url );
			jQuery('.wpsl_customizer_bg_img').css( 'background-image', 'url(' + attachment.url + ')' ).css( 'background-size', 'cover' );
			jQuery('#wpsl_bg_img_btn').attr('value', 'Change Image');
			jQuery('#wpsl_bg_img_btn').parent().find('.wpsl_preview_icon_sd').remove();
			jQuery('#wpsl_bg_img_btn').parent().append('<img class="wpsl_preview_icon_sd" src="' + attachment.url + '" />');

		});

		image_frame.open();

	});

	jQuery('.wpsl_email_widget .wpsl_text_input').css('width', '29%');

	jQuery('#wpsl_widget_icon').change(function(){

		if( this.value != "0" && ! jQuery('#icon_image_togl').prop('checked') ){
			var icon_hhh	=	jQuery('.wpsl_email_locker_icon');
			icon_hhh.removeAttr('class');
			icon_hhh.addClass('wpsl_email_locker_icon dashicons '+this.value);
			icon_hhh.empty();
		}

	});

	jQuery('#wpsl_fw_btn_icon').change(function(){

		if( this.value != "0" && ! jQuery('#fw_btn_icon_image_togl').prop('checked') ){
			var icon_hhh	=	jQuery('.wpsl_fw_submit_btn');
			icon_hhh.removeAttr('class');
			icon_hhh.addClass('wpsl_fw_submit_btn dashicons '+this.value);
			icon_hhh.empty();
		}

	});

	jQuery('#locker_header').keyup(function(){
		jQuery('.wpsl_customizer_heading').html(jQuery(this).val());
	});

	jQuery('#locker_submit_label').keyup(function(){
		jQuery('.wpsl_customizer_button').val( jQuery(this).val() );
	});

	jQuery('#insta_follow_button_title').keyup(function(){
		jQuery('#wpsl_instagram_follow').text(jQuery(this).val());
	});

 	jQuery('#yt_subscribe_button_text').keyup(function(){
		jQuery('#wpsl_youtube_subscribe').text(jQuery(this).val());
	});

 	jQuery('#tw_follow_button_text').keyup(function(){
		jQuery('#wpsl_twitter_follow').text(jQuery(this).val());
	});

 	jQuery('#fb_share_button_title').keyup(function(){
		jQuery('#wpsl_facebook_share').text(jQuery(this).val());
	});

 	jQuery('#tw_tweet_button_title').keyup(function(){
		jQuery('#wpsl_twitter_tweet').text(jQuery(this).val());
	});

	jQuery('.wpsl_colorpicker').each(function( ind, el ){

		jQuery(el).wpColorPicker({
			change: function(event, ui) {

				jQuery(el).parents('.wp-picker-container').find('.wp-color-result').attr('title', ui.color.toString());

				if( jQuery(this).data('type') == 'gradient_a' || jQuery(this).data('type') == 'gradient_b' ){

					if( jQuery(this).data('type') == 'gradient_a' ){
						var other_gradient	=	jQuery(this).parents('.wpsl_customizer_section').find('.wpsl_colorpicker.gradient_b').val();
						jQuery(jQuery(this).data('appliesto')).css( 'background', 'linear-gradient(' + ui.color.toString() + ', ' + other_gradient + ')');
					}else{
						var other_gradient	=	jQuery(this).parents('.wpsl_customizer_section').find('.wpsl_colorpicker.gradient_a').val();
						jQuery(jQuery(this).data('appliesto')).css( 'background', 'linear-gradient(' + other_gradient + ', ' + ui.color.toString() + ')');
					}

				}else if( jQuery(this).data('type') == 'background' ){
					jQuery(jQuery(this).data('appliesto')).css( 'background', ui.color.toString());
				}else if( jQuery(this).data('type') == 'border' ){
					jQuery(jQuery(this).data('appliesto')).css( 'border-color', ui.color.toString());
				}else{
					jQuery(jQuery(this).data('appliesto')).css( 'color', ui.color.toString());
				}
			}
		});

	});

	jQuery('.wpsl_font_size_selector').change(function(){
		jQuery(jQuery(this).data('appliesto')).css( 'font-size', this.value);
	});

	jQuery('.wpsl_border_width_selector').change(function(){
		jQuery(jQuery(this).data('appliesto')).css( 'border-width', this.value);
	});

	jQuery('.wpsl_border_style_selector').change(function(){
		jQuery(jQuery(this).data('appliesto')).css( 'border-style', this.value);
	});

	var font_variants_dic = {
		'100': 'Thin 100',
		'100italic': 'Thin 100 Italic',
		'200': 'Extra-light 200',
		'200italic': 'Extra-light 200 Italic',
		'300': 'Light 300',
		'300italic': 'Light 300 Italic',
		'regular': 'Regular',
		'italic': 'Regular Italic',
		'500': 'Medium 500',
		'500italic': 'Medium 500 Italic',
		'600': 'Semi-bold 600',
		'600italic': 'Semi-bold 600 Italic',
		'700': 'Bold 700',
		'700italic': 'Bold 700 Italic',
		'800': 'Extra-bold 800',
		'800italic': 'Extra-bold 800 Italic',
		'900': 'Black 900',
		'900italic': 'Black 900 Italic'
	};


	jQuery('.wpsl_font_family_selector').change(function(){

		var appliesto = jQuery(this).data('appliesto');

		if( this.value == "0" ){
			jQuery(appliesto).css( 'font-family', 'inherit');
		}else{

			// Update font style selector
			var gfont_ss = jQuery(this).parent().find('.wpsl_font_style_selector');
			gfont_ss.html('<option value="">Default</option>'+wpsl_cnc_gfonts_json[this.value].map(function(val){
				return '<option value="'+val+'"'+( gfont_ss.data('oldval') == val ? ' selected' : '' )+'>'+font_variants_dic[val]+'</option>';
			}).join('')).show();

			var font_slug	=	this.value.replace(' ', '+');
			var gf_uri_id = 'font_applied_to-'+appliesto.replace('.','');

 			if( jQuery('#'+gf_uri_id).length )
				jQuery('#'+gf_uri_id).remove();

			if( font_slug != '' && font_slug != 0 ){
				jQuery('head').append('<link rel="stylesheet" id="'+gf_uri_id+'" href="https://fonts.googleapis.com/css?family='+font_slug+'" type="text/css" />');
				jQuery(appliesto).css( 'font-family', this.value);
			}
		}

	});

	jQuery('.wpsl_font_style_selector').change(function(){

		var font_faml = jQuery(this).parent().find('.wpsl_font_family_selector').val();
		var font_slug = font_faml.replace(' ', '+');
		var font_vari = this.value;
		var appliesto = jQuery(this).data('appliesto');
		var gf_uri_id = 'font_applied_to-'+appliesto.replace('.','');

		if( jQuery('#'+gf_uri_id).length )
		jQuery('#'+gf_uri_id).remove();

		if( font_slug === '0' )
			return;

		jQuery(appliesto).css( 'font-weight', '');
		jQuery(appliesto).css( 'font-style', '');

		if( font_vari == "0" ){
			jQuery('head').append('<link rel="stylesheet" id="'+gf_uri_id+'" href="https://fonts.googleapis.com/css?family='+font_slug+'" type="text/css" />');
			jQuery(appliesto).css( 'font-family', font_faml);
		}else{
			jQuery('head').append('<link rel="stylesheet" id="'+gf_uri_id+'" href="https://fonts.googleapis.com/css?family='+font_slug+':'+font_vari+'" type="text/css" />');
			jQuery(appliesto).css( 'font-family', font_faml);
			jQuery(appliesto).css( 'font-weight', font_vari.replace(/[^0-9]/g,''));
			if( /italic/.test(font_vari) )
				jQuery(appliesto).css( 'font-style', 'italic');
		}

	});

	jQuery('.wpsl_customizer_button').val( jQuery('#locker_submit_label').val() );

});

function wpsl_preview_admin_template( template_id, type ){

	wpsl_updating_template	=	true;

	jQuery.ajax({
		method: "POST",
		url: wpsl_admin_ajax.ajaxurl,
		timeout: 0,
		data: {
			'action': wpsl_admin_ajax.action,
			'template_id': template_id,
			'type': type
		}
	}).done(function( content ){

		if( content == 'error' ){
			alert('Something went wrong.');
		}

		/*var old_style	=	jQuery('#wpsl_preview_container style').clone();*/

		jQuery('#wpsl_preview_container').html(content);
		/*jQuery('#wpsl_preview_container').append(old_style);*/

		/*jQuery('.wpsl_email_widget .wpsl_text_input').css('width', '29%');*/

		wpsl_updating_template	=	false;
		setTimeout(wpsl_responsive_widgets_hnd, 100);
		setTimeout(wpsl_responsive_widgets_hnd, 400);
		setTimeout(wpsl_responsive_widgets_hnd, 900);
		setTimeout(wpsl_responsive_widgets_hnd, 1500);

	});

}

function wpsl_show_hide_appliesonlyto( template ){

	jQuery('.wpsl_admin_only_applies_to_few').each(function( i, el ){

		if( jQuery(el).data('templates').indexOf( template ) < 0 ){
			// Disable
			jQuery(el).addClass('disabled');
		}else{
			// Enable
			jQuery(el).removeClass('disabled');
		}

	});

}

function wpsl_template_preset( type, template ){

	if( type == 'follow' ){

		if( template == 'ImageBG' ){
			alert('Only available in premium version');
		}

		if( template == 'Simple Light' ){

			var templ_load_wait	=	setInterval(function(){

				if( ! wpsl_updating_template ){

					// Default website font for everything
					jQuery('.wpsl_font_family_selector').val('0').change();

					// Heading's Text
					if( jQuery('#locker_header').val() == '' ){
						jQuery('#locker_header').val('Follow to Unlock').keyup();
					}

					// Message's Text
					if( tinymce.get('locker_message') != null && tinymce.get('locker_message').getContent() == '' ){
						tinymce.get('locker_message').setContent('<p>We will never send you spam. Ever! Probably!<br />Get access to this awesome locked feature when you subscribe!</p>');
					}

					jQuery('#locker_header').keyup();
					jQuery('.wpsl_customizer_desc').html(tinymce.get('locker_message').getContent());

					// Heading's Font Size and Color
					jQuery('#wpsl_customized_data_heading_fs').val('45px').change();
					jQuery('#wpsl_customized_data_heading_color').wpColorPicker('color', '#4990E2');
					jQuery('#wpsl_customized_data_heading_ff').val('Roboto').change();

					// Message's Font Size and Color
					jQuery('#wpsl_customized_data_desc_fs').val('18px').change();
					jQuery('#wpsl_customized_data_desc_color').wpColorPicker('color', '#262626');

					// Icon
					jQuery('#icon_image_togl').prop('checked', false).change();
					jQuery('#icon_bg_grad_togl').prop('checked', false).change();
					jQuery('#wpsl_widget_icon').val('dashicons-thumbs-up').change();
					jQuery('#wpsl_customized_data_email_locker_icon_fs').val('96px').change();
					jQuery('#wpsl_customized_data_email_locker_icon_color').wpColorPicker('color', '#F5F5F5');
					jQuery('#wpsl_customized_data_left_icon_bg').wpColorPicker('color', '#4990E2');

					// For Background
					jQuery('#prmr_bg_grad_togl').prop('checked', false).change();
					jQuery('#wpsl_customized_data_primary_bg_bg').wpColorPicker('color', '#F5F5F5');

					// Turn 'powered by text' to dark
					jQuery('input[name=powered_by_style][value=dark]').prop('checked', true).change();
					wpsl_preset( 'follow', 'flat' );

					clearInterval(templ_load_wait);

				}

			}, 500);

		}

		if( template == 'Simple Dark' ){

			var templ_load_wait	=	setInterval(function(){

				if( ! wpsl_updating_template ){

					// Default website font for everything
					jQuery('.wpsl_font_family_selector').val('0').change();

					// Heading's Text
					if( jQuery('#locker_header').val() == '' ){
						jQuery('#locker_header').val('Follow to Unlock').keyup();
					}

					// Message's Text
					if( tinymce.get('locker_message') != null && tinymce.get('locker_message').getContent() == '' ){
						tinymce.get('locker_message').setContent('<p>We will never send you spam. Ever! Probably!<br />Get access to this awesome locked feature when you subscribe!</p>');
					}

					jQuery('#locker_header').keyup();
					jQuery('.wpsl_customizer_desc').html(tinymce.get('locker_message').getContent());

					// Heading's Font Size and Color
					jQuery('#wpsl_customized_data_heading_fs').val('45px').change();
					jQuery('#wpsl_customized_data_heading_color').wpColorPicker('color', '#FFFFFF');
					jQuery('#wpsl_customized_data_heading_ff').val('Roboto').change();

					// Message's Font Size and Color
					jQuery('#wpsl_customized_data_desc_fs').val('18px').change();
					jQuery('#wpsl_customized_data_desc_color').wpColorPicker('color', '#EAEAEA');

					// Icon
					jQuery('#icon_image_togl').prop('checked', false).change();
					jQuery('#icon_bg_grad_togl').prop('checked', false).change();
					jQuery('#wpsl_widget_icon').val('dashicons-thumbs-up').change();
					jQuery('#wpsl_customized_data_email_locker_icon_fs').val('96px').change();
					jQuery('#wpsl_customized_data_email_locker_icon_color').wpColorPicker('color', '#20324F');
					jQuery('#wpsl_customized_data_left_icon_bg').wpColorPicker('color', '#FFBE00');

					// For Background
					jQuery('#prmr_bg_grad_togl').prop('checked', false).change();
					jQuery('#wpsl_customized_data_primary_bg_bg').wpColorPicker('color', '#20324F');

					// Turn 'powered by text' to gold
					jQuery('input[name=powered_by_style][value=gold]').prop('checked', true).change();
					wpsl_preset( 'follow', 'flat' );

					clearInterval(templ_load_wait);

				}

			}, 500);

		}

		if( template == 'Flat' ){
			alert('Only available in premium version');
		}

		if( template == 'Basic' ){

			var templ_load_wait	=	setInterval(function(){

				if( ! wpsl_updating_template ){

					// Default website font for everything
					jQuery('.wpsl_font_family_selector').val('0').change();

					// Heading's Text
					if( jQuery('#locker_header').val() == '' ){
						jQuery('#locker_header').val('Follow to Unlock').keyup();
					}

					// Message's Text
					if( tinymce.get('locker_message') != null && tinymce.get('locker_message').getContent() == '' ){
						tinymce.get('locker_message').setContent('<p>We will never send you spam. Ever! Probably!</p>');
					}

					jQuery('#locker_header').keyup();
					jQuery('.wpsl_customizer_desc').html(tinymce.get('locker_message').getContent());

					// Heading's Font Size and Color
					jQuery('#wpsl_customized_data_heading_fs').val('45px').change();
					jQuery('#wpsl_customized_data_heading_color').wpColorPicker('color', '#343434');
					jQuery('#wpsl_customized_data_heading_ff').val('Roboto').change();

					// Message's Font Size and Color
					jQuery('#wpsl_customized_data_desc_fs').val('18px').change();
					jQuery('#wpsl_customized_data_desc_color').wpColorPicker('color', '#999999');

					// Icon
					jQuery('#icon_image_togl').prop('checked', false).change();
					jQuery('#icon_bg_grad_togl').prop('checked', false).change();
					jQuery('#wpsl_widget_icon').val('dashicons-thumbs-up').change();
					jQuery('#wpsl_customized_data_email_locker_icon_fs').val('60px').change();
					jQuery('#wpsl_customized_data_email_locker_icon_color').wpColorPicker('color', '#515151');
					jQuery('#wpsl_customized_data_left_icon_bg').wpColorPicker('color', null);

					// For Background
					jQuery('#prmr_bg_bg_grad_togl').prop('checked', false).change();
					jQuery('#wpsl_customized_data_primary_bg_bg').wpColorPicker('color', '#FFFFFF');

					wpsl_preset( 'follow', 'flat' );

					clearInterval(templ_load_wait);

				}

			}, 500);

		}

	}

	if( type == 'share' ){

		if( template == 'ImageBG' ){
			alert('Only available in premium version');
		}

		if( template == 'Simple Light' ){

			var templ_load_wait	=	setInterval(function(){

				if( ! wpsl_updating_template ){

					// Default website font for everything
					jQuery('.wpsl_font_family_selector').val('0').change();

					// Heading's Text
					if( jQuery('#locker_header').val() == '' ){
						jQuery('#locker_header').val('Share to Unlock').keyup();
					}

					// Message's Text
					if( tinymce.get('locker_message') != null && tinymce.get('locker_message').getContent() == '' ){
						tinymce.get('locker_message').setContent('<p>We will never send you spam. Ever! Probably!<br />Get access to this awesome locked feature when you subscribe!</p>');
					}

					jQuery('#locker_header').keyup();
					jQuery('.wpsl_customizer_desc').html(tinymce.get('locker_message').getContent());

					// Heading's Font Size and Color
					jQuery('#wpsl_customized_data_heading_fs').val('45px').change();
					jQuery('#wpsl_customized_data_heading_color').wpColorPicker('color', '#4990E2');
					jQuery('#wpsl_customized_data_heading_ff').val('Roboto').change();

					// Message's Font Size and Color
					jQuery('#wpsl_customized_data_desc_fs').val('18px').change();
					jQuery('#wpsl_customized_data_desc_color').wpColorPicker('color', '#262626');

					// Icon
					jQuery('#icon_image_togl').prop('checked', false).change();
					jQuery('#icon_bg_grad_togl').prop('checked', false).change();
					jQuery('#wpsl_widget_icon').val('dashicons-admin-links').change();
					jQuery('#wpsl_customized_data_email_locker_icon_fs').val('96px').change();
					jQuery('#wpsl_customized_data_email_locker_icon_color').wpColorPicker('color', '#F5F5F5');
					jQuery('#wpsl_customized_data_left_icon_bg').wpColorPicker('color', '#4990E2');

					// For Background
					jQuery('#prmr_bg_bg_grad_togl').prop('checked', false).change();
					jQuery('#wpsl_customized_data_primary_bg_bg').wpColorPicker('color', '#F5F5F5');

					// Turn 'powered by text' to dark
					jQuery('input[name=powered_by_style][value=dark]').prop('checked', true).change();
					wpsl_preset( 'share', 'flat' );

					clearInterval(templ_load_wait);

				}

			}, 500);

		}

		if( template == 'Simple Dark' ){

			var templ_load_wait	=	setInterval(function(){

				if( ! wpsl_updating_template ){

					// Default website font for everything
					jQuery('.wpsl_font_family_selector').val('0').change();

					// Heading's Text
					if( jQuery('#locker_header').val() == '' ){
						jQuery('#locker_header').val('Share to Unlock').keyup();
					}

					// Message's Text
					if( tinymce.get('locker_message') != null && tinymce.get('locker_message').getContent() == '' ){
						tinymce.get('locker_message').setContent('<p>We will never send you spam. Ever! Probably!<br />Get access to this awesome locked feature when you subscribe!</p>');
					}

					jQuery('#locker_header').keyup();
					jQuery('.wpsl_customizer_desc').html(tinymce.get('locker_message').getContent());

					// Heading's Font Size and Color
					jQuery('#wpsl_customized_data_heading_fs').val('45px').change();
					jQuery('#wpsl_customized_data_heading_color').wpColorPicker('color', '#FFFFFF');
					jQuery('#wpsl_customized_data_heading_ff').val('Roboto').change();

					// Message's Font Size and Color
					jQuery('#wpsl_customized_data_desc_fs').val('18px').change();
					jQuery('#wpsl_customized_data_desc_color').wpColorPicker('color', '#EAEAEA');

					// Icon
					jQuery('#icon_image_togl').prop('checked', false).change();
					jQuery('#icon_bg_grad_togl').prop('checked', false).change();
					jQuery('#wpsl_widget_icon').val('dashicons-admin-links').change();
					jQuery('#wpsl_customized_data_email_locker_icon_fs').val('96px').change();
					jQuery('#wpsl_customized_data_email_locker_icon_color').wpColorPicker('color', '#20324F');
					jQuery('#wpsl_customized_data_left_icon_bg').wpColorPicker('color', '#FFBE00');

					// For Background
					jQuery('#prmr_bg_bg_grad_togl').prop('checked', false).change();
					jQuery('#wpsl_customized_data_primary_bg_bg').wpColorPicker('color', '#20324F');

					// Turn 'powered by text' to gold
					jQuery('input[name=powered_by_style][value=gold]').prop('checked', true).change();
					wpsl_preset( 'share', 'flat' );

					clearInterval(templ_load_wait);

				}

			}, 500);

		}

		if( template == 'Flat' ){
			alert('Only available in premium version');
		}

		if( template == 'Basic' ){

			var templ_load_wait	=	setInterval(function(){

				if( ! wpsl_updating_template ){

					// Default website font for everything
					jQuery('.wpsl_font_family_selector').val('0').change();

					// Heading's Text
					if( jQuery('#locker_header').val() == '' ){
						jQuery('#locker_header').val('Share to Unlock').keyup();
					}

					// Message's Text
					if( tinymce.get('locker_message') != null && tinymce.get('locker_message').getContent() == '' ){
						tinymce.get('locker_message').setContent('<p>We will never send you spam. Ever! Probably!</p>');
					}

					jQuery('#locker_header').keyup();
					jQuery('.wpsl_customizer_desc').html(tinymce.get('locker_message').getContent());

					// Heading's Font Size and Color
					jQuery('#wpsl_customized_data_heading_fs').val('45px').change();
					jQuery('#wpsl_customized_data_heading_color').wpColorPicker('color', '#2C2C2C');
					jQuery('#wpsl_customized_data_heading_ff').val('Roboto').change();

					// Message's Font Size and Color
					jQuery('#wpsl_customized_data_desc_fs').val('16px').change();
					jQuery('#wpsl_customized_data_desc_color').wpColorPicker('color', '#AAAAAA');

					// Icon
					jQuery('#icon_image_togl').prop('checked', false).change();
					jQuery('#icon_bg_grad_togl').prop('checked', false).change();
					jQuery('#wpsl_widget_icon').val('dashicons-share').change();
					jQuery('#wpsl_customized_data_email_locker_icon_fs').val('60px').change();
					jQuery('#wpsl_customized_data_email_locker_icon_color').wpColorPicker('color', '#262626');
					jQuery('#wpsl_customized_data_left_icon_bg').wpColorPicker('color', null);

					// For Background
					jQuery('#prmr_bg_bg_grad_togl').prop('checked', false).change();
					jQuery('#wpsl_customized_data_primary_bg_bg').wpColorPicker('color', '#FFFFFF');

					wpsl_preset( 'share', 'flat' );

					clearInterval(templ_load_wait);

				}

			}, 500);

		}

	}

	if( type == 'email' ){

		if( template == 'ImageBG' ){
			alert('Only available in premium version');
		}

		if( template == 'Simple Light' ){

			var templ_load_wait	=	setInterval(function(){

				if( ! wpsl_updating_template ){

					// Default website font for everything
					jQuery('.wpsl_font_family_selector').val('0').change();

					// Heading's Text
					if( jQuery('#locker_header').val() == '' ){
						jQuery('#locker_header').val('Subscribe to Unlock').keyup();
					}

					// Message's Text
					if( tinymce.get('locker_message') != null && tinymce.get('locker_message').getContent() == '' ){
						tinymce.get('locker_message').setContent('<p>We will never send you spam. Ever! Probably!<br />Get access to this awesome locked feature when you subscribe!</p>');
					}

					jQuery('#locker_header').keyup();
					jQuery('.wpsl_customizer_desc').html(tinymce.get('locker_message').getContent());

					// Heading's Font Size and Color
					jQuery('#wpsl_customized_data_heading_fs').val('45px').change();
					jQuery('#wpsl_customized_data_heading_color').wpColorPicker('color', '#4990E2');
					jQuery('#wpsl_customized_data_heading_ff').val('Roboto').change();

					// Message's Font Size and Color
					jQuery('#wpsl_customized_data_desc_fs').val('18px').change();
					jQuery('#wpsl_customized_data_desc_color').wpColorPicker('color', '#262626');

					// Icon
					jQuery('#icon_image_togl').prop('checked', false).change();
					jQuery('#icon_bg_grad_togl').prop('checked', false).change();
					jQuery('#wpsl_widget_icon').val('dashicons-lock').change();
					jQuery('#wpsl_customized_data_email_locker_icon_fs').val('96px').change();
					jQuery('#wpsl_customized_data_email_locker_icon_color').wpColorPicker('color', '#F5F5F5');
					jQuery('#wpsl_customized_data_left_icon_bg').wpColorPicker('color', '#4990E2');

					// For Background
					jQuery('#prmr_bg_grad_togl').prop('checked', false).change();
					jQuery('#wpsl_customized_data_primary_bg_bg').wpColorPicker('color', '#F5F5F5');

					// Turn 'powered by text' to dark
					jQuery('input[name=powered_by_style][value=dark]').prop('checked', true).change();
					wpsl_preset( 'email', '2' );

					clearInterval(templ_load_wait);

				}

			}, 500);

		}

		if( template == 'Simple Dark' ){

			var templ_load_wait	=	setInterval(function(){

				if( ! wpsl_updating_template ){

					// Default website font for everything
					jQuery('.wpsl_font_family_selector').val('0').change();

					// Heading's Text
					if( jQuery('#locker_header').val() == '' ){
						jQuery('#locker_header').val('Subscribe to Unlock').keyup();
					}

					// Message's Text
					if( tinymce.get('locker_message') != null && tinymce.get('locker_message').getContent() == '' ){
						tinymce.get('locker_message').setContent('<p>We will never send you spam. Ever! Probably!<br />Get access to this awesome locked feature when you subscribe!</p>');
					}

					jQuery('#locker_header').keyup();
					jQuery('.wpsl_customizer_desc').html(tinymce.get('locker_message').getContent());

					// Heading's Font Size and Color
					jQuery('#wpsl_customized_data_heading_fs').val('45px').change();
					jQuery('#wpsl_customized_data_heading_color').wpColorPicker('color', '#FFFFFF');
					jQuery('#wpsl_customized_data_heading_ff').val('Roboto').change();

					// Message's Font Size and Color
					jQuery('#wpsl_customized_data_desc_fs').val('18px').change();
					jQuery('#wpsl_customized_data_desc_color').wpColorPicker('color', '#EAEAEA');

					// Icon
					jQuery('#icon_image_togl').prop('checked', false).change();
					jQuery('#icon_bg_grad_togl').prop('checked', false).change();
					jQuery('#wpsl_widget_icon').val('dashicons-lock').change();
					jQuery('#wpsl_customized_data_email_locker_icon_fs').val('96px').change();
					jQuery('#wpsl_customized_data_email_locker_icon_color').wpColorPicker('color', '#20324F');
					jQuery('#wpsl_customized_data_left_icon_bg').wpColorPicker('color', '#FFBE00');

					// For Background
					jQuery('#prmr_bg_grad_togl').prop('checked', false).change();
					jQuery('#wpsl_customized_data_primary_bg_bg').wpColorPicker('color', '#20324F');

					// Turn 'powered by text' to gold
					jQuery('input[name=powered_by_style][value=gold]').prop('checked', true).change();
					wpsl_preset( 'email', '2.1' );

					clearInterval(templ_load_wait);

				}

			}, 500);

		}

		if( template == 'Flat' ){
			alert('Only available in premium version');
		}

		if( template == 'Basic' ){

			var templ_load_wait	=	setInterval(function(){

				if( ! wpsl_updating_template ){

					// Default website font for everything
					jQuery('.wpsl_font_family_selector').val('0').change();

					// Heading's Text
					if( jQuery('#locker_header').val() == '' ){
						jQuery('#locker_header').val('Subscribe to Unlock').keyup();
					}

					// Message's Text
					if( tinymce.get('locker_message') != null && tinymce.get('locker_message').getContent() == '' ){
						tinymce.get('locker_message').setContent('<p>We will never send you spam. Ever! Probably!</p>');
					}

					jQuery('#locker_header').keyup();
					jQuery('.wpsl_customizer_desc').html(tinymce.get('locker_message').getContent());

					// Heading's Font Size and Color
					jQuery('#wpsl_customized_data_heading_fs').val('45px').change();
					jQuery('#wpsl_customized_data_heading_color').wpColorPicker('color', '#262626');
					jQuery('#wpsl_customized_data_heading_ff').val('Roboto').change();

					// Message's Font Size and Color
					jQuery('#wpsl_customized_data_desc_fs').val('18px').change();
					jQuery('#wpsl_customized_data_desc_color').wpColorPicker('color', '#999999');

					// Icon
					jQuery('#icon_image_togl').prop('checked', false).change();
					jQuery('#icon_bg_grad_togl').prop('checked', false).change();
					jQuery('#wpsl_widget_icon').val('dashicons-lock').change();
					jQuery('#wpsl_customized_data_email_locker_icon_fs').val('60px').change();
					jQuery('#wpsl_customized_data_email_locker_icon_color').wpColorPicker('color', '#4E4E4E');
					jQuery('#wpsl_customized_data_left_icon_bg').wpColorPicker('color', null);

					// For Background
					jQuery('#prmr_bg_bg_grad_togl').prop('checked', false).change();
					jQuery('#wpsl_customized_data_primary_bg_bg').wpColorPicker('color', '#FFFFFF');

					wpsl_preset( 'email', '1' );

					clearInterval(templ_load_wait);

				}

			}, 500);

		}

		jQuery('#wpsl_email_text_border_width').val('1px').change();
		jQuery('#wpsl_email_text_border_style').val('solid').change();
		jQuery('#wpsl_customized_email_text_border_color').wpColorPicker('color', '#CCCCCC');

	}


}

function wpsl_preset( type, preset ){

	if( 'share' == type ){

		jQuery('#activate_facebook_share, #activate_twitter_tweet').prop('checked', true).change();
		jQuery('#tw_tweet_button_title').val('Tweet');
		jQuery('#fb_share_button_title').val('Share');

		if( preset == 'flat' ){

			jQuery('#wpsl_customized_data_facebook_share_fs, #wpsl_customized_data_twitter_tweet_fs').val('18px').change();
			jQuery('#fbshr_bg_grad_togl, #twtrshr_bg_grad_togl').prop('checked', false).change();
			jQuery('#wpsl_customized_data_facebook_share_color, #wpsl_customized_data_twitter_tweet_color').wpColorPicker('color', '#FFFFFF');

			jQuery('#wpsl_customized_data_facebook_share_bg').wpColorPicker('color', '#3B5998');
			jQuery('#wpsl_customized_data_twitter_tweet_bg').wpColorPicker('color', '#55ACEE');

		}

		if( preset == 'grad' ){

			jQuery('#wpsl_customized_data_facebook_share_fs, #wpsl_customized_data_twitter_tweet_fs').val('18px').change();
			jQuery('#fbshr_bg_grad_togl, #twtrshr_bg_grad_togl').prop('checked', true).change();
			jQuery('#wpsl_customized_data_facebook_share_color, #wpsl_customized_data_twitter_tweet_color').wpColorPicker('color', '#FFFFFF');

			jQuery('#wpsl_customized_data_facebook_share_bga').wpColorPicker('color', '#3B5998');
			jQuery('#wpsl_customized_data_facebook_share_bgb').wpColorPicker('color', '#173470');

			jQuery('#wpsl_customized_data_twitter_tweet_bga').wpColorPicker('color', '#55ACEE');
			jQuery('#wpsl_customized_data_twitter_tweet_bgb').wpColorPicker('color', '#227BBE');

		}

	}

	if( 'follow' == type ){

		jQuery('#activate_youtube_subscribe, #activate_instagram_follow, #activate_twitter_follow').prop('checked', true).change();
		jQuery('#tw_follow_button_text').val('Follow');
		jQuery('#yt_subscribe_button_text').val('Subscribe');
		jQuery('#insta_follow_button_title').val('Follow');

		if( preset == 'flat' ){

			jQuery('#wpsl_customized_data_instagram_follow_fs, #wpsl_customized_data_youtube_subscribe_fs, #wpsl_customized_data_twitter_follow_fs').val('18px').change();
			jQuery('#fblik_bg_grad_togl, #ytsbs_bg_grad_togl, #twtrfl_bg_grad_togl').prop('checked', false).change();
			jQuery('#wpsl_customized_data_instagram_follow_color, #wpsl_customized_data_youtube_subscribe_color, #wpsl_customized_data_twitter_follow_color').wpColorPicker('color', '#FFFFFF');

			jQuery('#wpsl_customized_data_insta_follow_bg').wpColorPicker('color', '#E4405F');
			jQuery('#wpsl_customized_data_yt_subscribe_bg').wpColorPicker('color', '#B31217');
			jQuery('#wpsl_customized_data_twitter_follow_bg').wpColorPicker('color', '#1DA1F2');

		}

		if( preset == 'grad' ){

			jQuery('#wpsl_customized_data_instagram_follow_fs, #wpsl_customized_data_youtube_subscribe_fs, #wpsl_customized_data_twitter_follow_fs').val('18px').change();
			jQuery('#fblik_bg_grad_togl, #ytsbs_bg_grad_togl, #twtrfl_bg_grad_togl').prop('checked', true).change();
			jQuery('#wpsl_customized_data_instagram_follow_color, #wpsl_customized_data_youtube_subscribe_color, #wpsl_customized_data_twitter_follow_color').wpColorPicker('color', '#FFFFFF');

			jQuery('#wpsl_customized_data_insta_follow_bga').wpColorPicker('color', '#E4405F');
			jQuery('#wpsl_customized_data_insta_follow_bgb').wpColorPicker('color', '#E20029');

			jQuery('#wpsl_customized_data_yt_subscribe_bga').wpColorPicker('color', '#CD201F');
			jQuery('#wpsl_customized_data_yt_subscribe_bgb').wpColorPicker('color', '#A9080D');

			jQuery('#wpsl_customized_data_twitter_follow_bga').wpColorPicker('color', '#1DA1F3');
			jQuery('#wpsl_customized_data_twitter_follow_bgb').wpColorPicker('color', '#0084B4');

		}

	}


	if( 'email' == type ){

		jQuery('#locker_submit_label').val('Unlock').change();
		jQuery('#name_field').change();

		if( preset == '1' ){

			jQuery('#wpsl_customized_data_etu_textbox_fs, #wpsl_customized_data_etu_button_fs').val('18px').change();

			// jQuery('#wpsl_customized_data_etu_textbox_color').wpColorPicker('color', '#999999');
			jQuery('#wpsl_customized_data_etu_button_color').wpColorPicker('color', '#FFFFFF');
			jQuery('#wpsl_customized_data_etu_textbox_bg').wpColorPicker('color', '#EBEBEB');

			jQuery('#wpsl_customized_data_etu_button_bga').wpColorPicker('color', '#3F5F9F');
			jQuery('#wpsl_customized_data_etu_button_bgb').wpColorPicker('color', '#123C7E');

			// jQuery('#txtbx_bg_grad_togl').prop('checked', false).change();
			jQuery('#sbmt_btn_bg_grad_togl').prop('checked', true).change();

			// jQuery('#name_field').prop('checked', false).change();
			jQuery('#wpsl_form_lines').val('1').change();

			// jQuery('.wpsl_email_widget .wpsl_customizer_textbox').css('width', '60%');
			if( jQuery('#name_field').is(':checked') ){
				jQuery('.wpsl_email_widget .wpsl_customizer_textbox').css('width', '29%').css('margin-left', 0).css('margin-right', 0);
			}else{
				jQuery('.wpsl_email_widget .wpsl_customizer_textbox').css('width', '60%').css('margin-left', 0).css('margin-right', 0);
			}

			jQuery('.wpsl_email_widget .wpsl_customizer_button').css('max-width', '30%').css('margin-top', '0');

		}

		if( preset == '2' ){

			jQuery('#wpsl_customized_data_etu_textbox_fs, #wpsl_customized_data_etu_button_fs').val('18px').change();
			// jQuery('#wpsl_customized_data_etu_button_fs').val('12px').change();

			// jQuery('#wpsl_customized_data_etu_textbox_color').wpColorPicker('color', '#999999');
			jQuery('#wpsl_customized_data_etu_button_color').wpColorPicker('color', '#FFFFFF');

			jQuery('#wpsl_customized_data_etu_textbox_bg').wpColorPicker('color', '#E5E5E5');

			// jQuery('#wpsl_customized_data_etu_button_bga').wpColorPicker('color', '#FFC200');
			// jQuery('#wpsl_customized_data_etu_button_bgb').wpColorPicker('color', '#FF9100');
			jQuery('#wpsl_customized_data_etu_button_bg').wpColorPicker('color', '#F2852F');

			// jQuery('#txtbx_bg_grad_togl').prop('checked', false).change();
			jQuery('#sbmt_btn_bg_grad_togl').prop('checked', false).change();

			// jQuery('#name_field').prop('checked', true).change();
			jQuery('#wpsl_form_lines').val('1').change();

			// jQuery('.wpsl_email_widget .wpsl_customizer_textbox').css('width', '29%');
			if( jQuery('#name_field').is(':checked') ){
				jQuery('.wpsl_email_widget .wpsl_customizer_textbox').css('width', '29%').css('margin-left', 0).css('margin-right', 0);
			}else{
				jQuery('.wpsl_email_widget .wpsl_customizer_textbox').css('width', '60%').css('margin-left', 0).css('margin-right', 0);
			}

			jQuery('.wpsl_email_widget .wpsl_customizer_button').css('max-width', '30%').css('margin-top', '0');

		}

		if( preset == '2.1' ){

			jQuery('#wpsl_customized_data_etu_textbox_fs, #wpsl_customized_data_etu_button_fs').val('18px').change();
			// jQuery('#wpsl_customized_data_etu_button_fs').val('14px').change();

			// jQuery('#wpsl_customized_data_etu_textbox_color').wpColorPicker('color', '#999999');
			jQuery('#wpsl_customized_data_etu_button_color').wpColorPicker('color', '#FFFFFF');

			// jQuery('#wpsl_customized_data_etu_textbox_bg').wpColorPicker('color', '#E5E5E5');

			jQuery('#wpsl_customized_data_etu_button_bga').wpColorPicker('color', '#FFC200');
			jQuery('#wpsl_customized_data_etu_button_bgb').wpColorPicker('color', '#FF9100');

			// jQuery('#txtbx_bg_grad_togl').prop('checked', false).change();
			jQuery('#sbmt_btn_bg_grad_togl').prop('checked', true).change();

			// jQuery('#name_field').prop('checked', true).change();
			jQuery('#wpsl_form_lines').val('1').change();

			// jQuery('.wpsl_email_widget .wpsl_customizer_textbox').css('width', '29%');
			if( jQuery('#name_field').is(':checked') ){
				jQuery('.wpsl_email_widget .wpsl_customizer_textbox').css('width', '29%').css('margin-left', 0).css('margin-right', 0);
			}else{
				jQuery('.wpsl_email_widget .wpsl_customizer_textbox').css('width', '60%').css('margin-left', 0).css('margin-right', 0);
			}

			jQuery('.wpsl_email_widget .wpsl_customizer_button').css('max-width', '30%').css('margin-top', '0');

		}

		if( preset == '3' ){

			jQuery('#wpsl_customized_data_etu_textbox_fs, #wpsl_customized_data_etu_button_fs').val('18px').change();
			// jQuery('#wpsl_customized_data_etu_button_fs').val('14px').change();

			// jQuery('#wpsl_customized_data_etu_textbox_color').wpColorPicker('color', '#999999');
			jQuery('#wpsl_customized_data_etu_button_color').wpColorPicker('color', '#FFFFFF');

			// jQuery('#wpsl_customized_data_etu_textbox_bg').wpColorPicker('color', '#F5F5F5');
			jQuery('#wpsl_customized_data_etu_button_bg').wpColorPicker('color', '#E88121');

			// jQuery('#txtbx_bg_grad_togl').prop('checked', false).change();
			jQuery('#sbmt_btn_bg_grad_togl').prop('checked', false).change();

			// jQuery('#name_field').prop('checked', true).change();
			jQuery('#wpsl_form_lines').val('2').change();

			// jQuery('.wpsl_email_widget .wpsl_customizer_textbox').css('width', '49%');
			if( jQuery('#name_field').is(':checked') ){
				var ismobb = jQuery('.wpsl_email_widget').outerWidth() < 610;
				jQuery('.wpsl_email_widget .wpsl_customizer_textbox:first').css('width', ( ismobb ? '47%' : '49%')).css('margin-left', 0).css('margin-right', '1%');
				jQuery('.wpsl_email_widget .wpsl_customizer_textbox:last').css('width', '49%').css('margin-left', 0).css('margin-right', 0);
			}else{
				jQuery('.wpsl_email_widget .wpsl_customizer_textbox').css('width', '100%').css('margin-left', 0).css('margin-right', 0);
			}

			jQuery('.wpsl_email_widget .wpsl_customizer_button').css('width', '100%').css('max-width', 'none').css('margin-top', '10px');

		}

		if( preset == '4' ){

			jQuery('#wpsl_customized_data_etu_textbox_fs').val('18px').change();
			jQuery('#wpsl_customized_data_etu_button_fs').val('18px').change();

			// jQuery('#wpsl_customized_data_etu_textbox_color').wpColorPicker('color', '#999999');
			jQuery('#wpsl_customized_data_etu_button_color').wpColorPicker('color', '#FFFFFF');

			// jQuery('#wpsl_customized_data_etu_textbox_bg').wpColorPicker('color', '#F5F5F5');

			jQuery('#wpsl_customized_data_etu_button_bg').wpColorPicker('color', '#4990E2');

			// jQuery('#txtbx_bg_grad_togl').prop('checked', false).change();
			jQuery('#sbmt_btn_bg_grad_togl').prop('checked', false).change();

			// jQuery('#name_field').prop('checked', true).change();
			jQuery('#wpsl_form_lines').val('1').change();

			// jQuery('.wpsl_email_widget .wpsl_customizer_textbox').css('width', '32%');
			if( jQuery('#name_field').is(':checked') ){
				jQuery('.wpsl_email_widget .wpsl_customizer_textbox').css('width', '29%').css('margin-left', 0).css('margin-right', 0);
			}else{
				jQuery('.wpsl_email_widget .wpsl_customizer_textbox').css('width', '60%').css('margin-left', 0).css('margin-right', 0);
			}

			jQuery('.wpsl_email_widget .wpsl_customizer_button').css('max-width', '30%').css('margin-top', '0');

		}


	}

}
