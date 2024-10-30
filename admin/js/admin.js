jQuery(document).ready(function($) {

		$(".meta-box-sortables").sortable({
			cancel: '#wpsl_fw_widgets_metabox_temp_chngr, #wpsl_stu_widgets_metabox_temp_chngr, #wpsl_ftu_widgets_metabox_temp_chngr, #wpsl_etu_widgets_metabox_temp_chngr'
		});

		// Collapse Admin Menu on Locker edit/add screen
		if( $('body').hasClass('post-type-ftu_widgets') || $('body').hasClass('post-type-etu_widgets') || $('body').hasClass('post-type-stu_widgets') || $('body').hasClass('post-type-floating_widgets') ){
			$('body').addClass('folded');
		}

    // Color Picker
    $('.hd-color-picker').wpColorPicker();


		// Preview floating as page scrolled
		var preview_cont			=	$('#wpsl_stu_widgets_preview, #wpsl_etu_widgets_preview, #wpsl_ftu_widgets_preview, #wpsl_fw_widgets_preview');
		var preview_cont_top	=	preview_cont.offset().top - 35;

		$(window).on('scroll', function() {

			if( $(this).scrollTop() > preview_cont_top ){

				if( ! preview_cont.hasClass('wpsl_fixed_position_widget') ){
					preview_cont.addClass('wpsl_fixed_position_widget');
					preview_cont.width( preview_cont.parent().width() );
				}

			}else{
				preview_cont.removeClass('wpsl_fixed_position_widget');
			}

		});

		$(window).on('resize', function() {
			preview_cont.width( preview_cont.parent().width() );
		});

    // Media Upload
    $('body').on('click', '.hd-upload-button', function(e) {

        e.preventDefault();

        var upload_input = $(this).siblings('.hd-upload-input'),
            hd_uploader;

        if (hd_uploader) {
            hd_uploader.open();
            return;
        }
        hd_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Upload Media',
            button: {
                text: 'Select',
            },
            multiple: false
        });

        hd_uploader.on('select', function() {
            var media_file = hd_uploader.state().get('selection').first().toJSON();
            upload_input.val(media_file.url);
        });

        hd_uploader.open();

    });

		function wpsl_show_hide_fields412y4(obj){

			targetFields = $('*[data-wpsl_show_activator="'+$(obj).attr('id')+'"]').parents('tr');
			targetData = targetFields.add(targetFields.prev()); // Their Labels are in another tr

			if( $(obj).prop('checked') ){
				targetData.show();
				$('#'+$(obj).attr('id').replace('activate', 'wpsl')).show();
			}else{
				targetData.hide();
				$('#'+$(obj).attr('id').replace('activate', 'wpsl')).hide();
			}

		}

		$('#activate_facebook_share, #activate_twitter_tweet, #activate_instagram_follow, #activate_youtube_subscribe, #activate_twitter_follow').each(function(i, el) {
			wpsl_show_hide_fields412y4(el);
		});

		$('#activate_facebook_share, #activate_twitter_tweet, #activate_instagram_follow, #activate_youtube_subscribe, #activate_twitter_follow').change(function(e) {

			var checked_length = $('#activate_facebook_share:checked, #activate_twitter_tweet:checked, #activate_instagram_follow:checked, #activate_youtube_subscribe:checked, #activate_twitter_follow:checked').length;
			if( checked_length == 0 ){
				alert('CNC: At least one button is required. Enable some other button to disable this.');
				$(this).prop('checked', true);
				return false;
			}else{
				wpsl_show_hide_fields412y4(this);
			}

		});

		$('body').on('change', '#icon_image_togl', function(){

			if( $(this).prop('checked') ){
				$('#wpsl_preview_container .wpsl_left_icon').css('height', '');
				$('#wpsl_preview_container .wpsl_left_icon .wpsl_email_locker_icon').removeAttr('class').removeAttr('style').addClass('wpsl_email_locker_icon');
				$('#wpsl_preview_container .wpsl_left_icon .wpsl_email_locker_icon').html('<img src="' + $('#custom_image_icon').val() + '" />');
			}else{
				$('#wpsl_widget_icon').change();
			}

		});

		jQuery('.wpsl_temp_to_sel.wpsl_premium_only a').tooltip({
			items: '.wpsl_temp_to_sel.wpsl_premium_only a',
			content: "Only available in premium version",
			disabled: true
		});

		$('body').on('click', '.wpsl_temp_to_sel.wpsl_premium_only a', function(e) {
			jQuery(this).tooltip('open');
			setTimeout(function(){
				jQuery(this).tooltip('disable');
			}, 1000);
		});

		jQuery('.wpslcnc_copy_to_clipboard').tooltip({
			items: '.wpslcnc_copy_to_clipboard',
			content: "Copied to clipboard!",
			disabled: true
		});

		$('body').on('click', '.wpslcnc_copy_to_clipboard', function(e) {

			text = $(this).prev().text();

			if( window.clipboardData && window.clipboardData.setData ){
				// Internet Explorer-specific code path to prevent textarea being shown while dialog is visible.
				clipboardData.setData("Text", text);
				jQuery( ".wpslcnc_copy_to_clipboard" ).tooltip( "option", "content", "Copied to clipboard!" );
			}else if( document.queryCommandSupported && document.queryCommandSupported("copy") ){

				var textarea = document.createElement("textarea");
				textarea.textContent = text;
				textarea.style.position = "fixed";  // Prevent scrolling to bottom of page in Microsoft Edge.
				document.body.appendChild(textarea);

				textarea.select();

				try{
					document.execCommand("copy");
					jQuery( ".wpslcnc_copy_to_clipboard" ).tooltip( "option", "content", "Copied to clipboard!" );
				}catch( ex ){
					jQuery( ".wpslcnc_copy_to_clipboard" ).tooltip( "option", "content", "Copy Failed!" );
				}finally{
					document.body.removeChild(textarea);
				}

			}

			// Open 'Copied to clipboard!' message and close after 1 sec
			jQuery('.wpslcnc_copy_to_clipboard').tooltip('open');
			setTimeout(function(){
				jQuery('.wpslcnc_copy_to_clipboard').tooltip('disable');
			}, 1000);

		});

		jQuery('#wpsl_preset_changer > div > a').click(function(e) {
			jQuery('#wpsl_preset_changer > div > a').removeClass('active');
			jQuery(this).addClass('active');
		});

		jQuery('.wpsl_dashicon_icon #wpsl_widget_icon, .wpsl_font_family_selector').select2({
			dropdownCssClass: "wpsl_select2_dropdown"
		});

});

/*window.onload = function(){

	var theform = document.post;

	window.onbeforeunload = function(e){

		var e = e || window.event, simon = "go";
		for( i=0; i < theform.elements.length; i++ ){

			if(theform.elements[i].type == "radio" || theform.elements[i].type == "checkbox"){
				if(theform.elements[i].checked != theform.elements[i].defaultChecked){
					simon = "no";
				}
			}else if(theform.elements[i].type == "select-one"){
				if( !theform.elements[i].options[theform.elements[i].selectedIndex].defaultSelected ){
					simon = "no";
				}
			}else if(theform.elements[i].type == 'submit'){
				theform.elements[i].onmouseup = function(){
					simon = 'go';
				}
			}else{
				if(theform.elements[i].value != theform.elements[i].defaultValue){
					simon = "no";
				}
			}

		}

		if(simon != "go"){

			if(e){
				e.returnValue = "unsaved chages detected";
			}

			return "unsaved chages detected";

		}

	}

};*/
