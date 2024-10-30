<?php
/* A HTML Helper Class */

if ( ! class_exists( 'HD_HTML_Helper' ) ) :
/**
 * HTML Helper Class
 *
 * a Simple HTML Helper Class to generate form field.
 *
 * @version 1.0
 * @author  Harish Dasari
 * @link    http://github.com/harishdasari
 */
class HD_HTML_Helper {

	/**
	 * Constructor
	 */
	public function __construct() {

	}

	/**
	 * Returns the Form Table html
	 *
	 * @param  array   $fields    Input fields options
	 * @param  boolean $show_help Show or hide help string
	 * @return string             HTML string
	 */
	public function get_form_table( $fields, $show_help = true ) {

		$form_table = '';

		$form_table .= '<table class="form-table">';

		foreach ( (array) $fields as $field )
			if( $field['type'] != 'register_only' )
			$form_table .= $this->get_table_row( $field, $show_help );

		$form_table .= '</table>';

		return apply_filters( 'hd_html_helper_form_table', $form_table, $fields, $show_help );

	}

	/**
	 * Echo/Display the HTML Form table
	 *
	 * @param  array   $fields    Input fields options
	 * @param  boolean $show_help Show or hide help string
	 * @return null
	 */
	public function display_form_table( $fields, $show_help = true ) {

		echo $this->get_form_table( $fields, $show_help );

	}

	/**
	 * Returns the table row html
	 *
	 * @param  array   $field
	 * @param  boolean $show_help
	 * @return string
	 */
	public function get_table_row( $field, $show_help ) {

		if( $field['type'] == 'html' ){
			$table_row = '<tr valign="top">';
				$html	=	str_replace( '%post_id%', get_the_ID(), $field['html'] );

				$attrs		=	'';
				if( !empty($field['attrs']) && is_array($field['attrs']) && count($field['attrs']) > 0 ){
					foreach($field['attrs'] as $k=>$v){
						$attrs	.=	' '.esc_attr($k).'="'.esc_attr($v).'"';
					}
				}

				if( $field['sanit'] == 'fullhtml' ){
					$table_row .= sprintf( '<td%s>%s</td>', $attrs, $html );
				}else{
					$table_row .= sprintf( '<td>%s</td>', wp_kses_data( $html ) );
				}
			$table_row .= '</tr>';
		}else{
			$table_row = '<tr valign="top">';
				$table_row .= sprintf( '<th><label for="%s">%s</label></th>', esc_attr( $field['id'] ), $field['title'] );
			$table_row .= '</tr>';
			$table_row .= '<tr valign="top">';
				$table_row .= sprintf( '<td>%s</td>', $this->get_field( $field, $show_help ) );
			$table_row .= '</tr>';
		}

		return apply_filters( 'hd_html_helper_table_row', $table_row, $field, $show_help );

	}

	/**
	 * returns a input field based on field options
	 *
	 * @param  array   $field     Input field options
	 * @param  boolean $show_help Show or hide help string
	 * @return string             HTML string
	 */
	public function get_field( $field, $show_help = true ) {

		$field_default = array(
			'title'    => '',
			'id'       => '',
			'type'     => '',
			'default'  => '',
			'choices'  => array(),
			'images'	 => array(),
			'value'    => '',
			'desc'     => '',
			'sanit'    => '',
			'attrs'    => array(),
			'multiple' => false, // for multiselect fiield
		);

		$field = wp_parse_args( $field, $field_default );

		$input_html = '';

		$attrs		=	'';
		if( count($field['attrs']) > 0 ){
			foreach($field['attrs'] as $k=>$v){
				$attrs	.=	' '.esc_attr($k).'="'.esc_attr($v).'"';
			}
		}

		switch ( $field['type'] ) {
			case 'text'				: $input_html .= $this->text_input( $field, $attrs ); break;
			case 'textarea'		: $input_html .= $this->textarea_input( $field, $attrs ); break;
			case 'select'			: $input_html .= $this->select_input( $field, $attrs ); break;
			case 'radio'			: $input_html .= $this->radio_input( $field, $attrs ); break;
			case 'checkbox'		: $input_html .= $this->checkbox_input( $field, $attrs ); break;
			case 'multicheck'	: $input_html .= $this->multicheck_input( $field, $attrs ); break;
			case 'upload'			: $input_html .= $this->upload_input( $field, $attrs ); break;
			case 'color'			: $input_html .= $this->color_input( $field, $attrs ); break;
			case 'editor'			: $input_html .= $this->editor_input( $field, $attrs ); break;
			case 'img_radio'	: $input_html .= $this->img_radio_input( $field, $attrs ); break;
		}

		if ( $show_help && 'checkbox' !== $field['type'] && 'text' !== $field['type'] )
			$input_html .= $this->help_text( $field );

		return apply_filters( 'hd_html_helper_input_field', $input_html, $field, $show_help );

	}

	/**
	 * Displays a Input field based field options
	 *
	 * @param  array   $field     Input field options
	 * @param  boolean $show_help Show or hide help string
	 * @return null
	 */
	public function display_field( $field, $show_help = true ) {

		echo $this->get_field( $field, $show_help );

	}

	/**
	 * Print Text Input
	 *
	 * @param  array $field Input Options
	 * @return null
	 */
	private function text_input( $field, $attrs='' ) {

		return sprintf(
			'<input type="text" placeholder="%s" name="%s" id="%s" value="%s" class="regular-text wpsl_text_field" %s/>',
			esc_attr( $field['desc'] ),
			esc_attr( $field['id'] ),
			esc_attr( $field['id'] ),
			esc_attr( $field['value'] ),
			$attrs // already escaped/sanetized
		);

	}

	/**
	 * Print Textarea Input
	 *
	 * @param  array $field Input Options
	 * @return null
	 */
	private function textarea_input( $field, $attrs='' ) {

		return sprintf(
			'<textarea name="%s" id="%s" rows="5" cols="40" %s>%s</textarea>',
			esc_attr( $field['id'] ),
			esc_attr( $field['id'] ),
			$attrs, // already escaped/sanetized
			esc_textarea( $field['value'] )
		);

	}

	/**
	 * Print Select Input
	 *
	 * @param  array $field Input Options
	 * @return null
	 */
	private function select_input( $field, $attrs='' ) {

		$selected_value = $field['value'];

		$multiple = ( true == $field['multiple'] || 'true' == $field['multiple'] ) ? true : false ;

		if ( $multiple )
			$field['id'] = $field['id'] . '[]';

		$select_field = sprintf(
			'<select name="%s" id="%s"%s %s>',
			esc_attr( $field['id'] ),
			esc_attr( $field['id'] ),
			( $multiple ? ' multiple' : '' ),
			$attrs // already escaped/sanetized
		);

		if ( ! empty( $field['choices'] ) ) {
			foreach ( (array) $field['choices'] as $value => $label ) {
				$selected = $multiple ? selected( in_array( $value, (array) $selected_value ), true, false ) : selected( $selected_value, $value, false );
				$select_field .= sprintf(
					'<option value="%s"%s>%s</option>',
					esc_attr( $value ),
					$selected,
					esc_html( $label )
				);
			}
		}

		$select_field .= '</select>';

		return $select_field;

	}

	/**
	 * Print Checkbox Input
	 *
	 * @param  array $field Input Options
	 * @return null
	 */
	private function checkbox_input( $field, $attrs='' ) {

		return sprintf(
			'<label><input type="checkbox" name="%s" id="%s"%s %s> <span class="small">%s</span></label>',
			esc_attr( $field['id'] ),
			esc_attr( $field['id'] ),
			checked( $field['value'], 'on', false ),
			$attrs, // already escaped/sanetized
			esc_html( $field['desc'] )
		);

	}

	/**
	 * Print Radio Input with Images
	 *
	 * @author Rizwan (m.rizwan_47@yahoo.com)
	 * @param  array $field Input Options
	 * @return null
	 */
	private function img_radio_input( $field, $attrs='' ) {

		$selected_value = $field['value'];

		$radio_field = '';

		if ( ! empty( $field['choices'] ) ) {
			$h	=	0;
			foreach ( (array) $field['choices'] as $template ){
				// <label><img src="%s" width="50" /><input type="radio" name="%s" id="" value="%s"%s> %s</label><br/>
				$radio_field .= sprintf(
					'<label class="fw_label" for="tmpl_%d"><input type="radio" name="%s" id="tmpl_%d" value="%d" class="template_selector"%s %s /><img src="%s" /></label><br />',
					intval( $template['id'] ),
					esc_attr( $field['id'] ),
					intval( $template['id'] ),
					intval( $template['id'] ),
					checked( $selected_value, $template['id'], false ),
					$attrs, // already escaped/sanetized
					esc_url( $template['icon'] )
				);
			}
		}

		return $radio_field;

	}

	/**
	 * Print Radio Input
	 *
	 * @param  array $field Input Options
	 * @return null
	 */
	private function radio_input( $field, $attrs='' ) {

		$selected_value = $field['value'];

		$radio_field = '';

		if ( ! empty( $field['choices'] ) ) {
			foreach ( (array) $field['choices'] as $value => $label )
				$radio_field .= sprintf(
					'<label><input type="radio" name="%s" id="" value="%s"%s %s> %s</label><br/>',
					esc_attr( $field['id'] ),
					esc_attr( $value ),
					checked( $selected_value, $value, false ),
					$attrs, // already escaped/sanetized
					esc_html( $label )
				);
		}

		return $radio_field;

	}

	/**
	 * Print Multi-Checkbox Input
	 *
	 * @param  array $field Input Options
	 * @return null
	 */
	private function multicheck_input( $field, $attrs='' ) {

		$selected_value = (array) $field['value'];

		$multicheck_field = '';

		if ( ! empty( $field['choices'] ) ) {
			foreach ( (array) $field['choices'] as $value => $label )
				$multicheck_field .= sprintf(
					'<label><input type="checkbox" name="%s[]" id="" value="%s"%s %s> <span class="small">%s</span></label><br/>',
					esc_attr( $field['id'] ),
					esc_attr( $value ),
					checked( in_array( $value, $selected_value ), true, false ),
					$attrs, // already escaped/sanetized
					esc_html( $label )
				);
		}

		return $multicheck_field;

	}

	/**
	 * Print Upload Input
	 *
	 * @param  array $field Input Options
	 * @return null
	 */
	private function upload_input( $field, $attrs='' ) {

		// NOTE: No fella, it breaks featured image functionality
		// // TODO $attrs
		// // dang! dang!! dang!!!
		// // We require to enqueue Media Uploader Scripts and Styles
		// wp_enqueue_media();

		return sprintf(
			'<input type="text" name="%s" id="%s" value="%s" class="regular-text hd-upload-input"/>' .
			'<input type="button" value="%s" class="hd-upload-button button button-secondary" id="hd_upload_%s"/>',
			esc_attr( $field['id'] ),
			esc_attr( $field['id'] ),
			esc_attr( $field['value'] ),
			__( 'Upload' ),
			esc_attr( $field['id'] )
		);

	}

	/**
	 * Print Color Picker Input
	 *
	 * @param  array $field Input Options
	 * @return null
	 */
	private function color_input( $field, $attrs='' ) {

		// TODO $attrs

		$default_color = empty( $field['default'] ) ? '' : ' data-default-color="' . esc_attr( $field['default'] ) . '"';

		return sprintf(
			'<input type="text" name="%s" id="%s" value="%s" class="hd-color-picker"%s/>',
			esc_attr( $field['id'] ),
			esc_attr( $field['id'] ),
			esc_attr( $field['value'] ),
			$default_color
		);

	}

	/**
	 * Print TinyMCE Editor Input
	 *
	 * @param  array $field Input Options
	 * @return null
	 */
	private function editor_input( $field, $attrs='' ) {

		// TODO $attrs

		$settings	= array_merge( array(
			'textarea_rows' => 5,
			'textarea_cols' => 45,
			'quicktags'			=> false
		), (array) $field['attrs'] );

		$content = $field['value'];
		$content = empty( $content ) ? '' : $content;

		ob_start();
		wp_editor( $content, $field['id'], $settings );
		return ob_get_clean();

	}

	/**
	 * Print Help/Descripting for field
	 *
	 * @param  array $field Input Options
	 * @return (string|null)
	 */
	private function help_text( $field ) {

		if ( empty( $field['desc'] ) )
			return '';

		return '<p class="description">' . wp_kses( $field['desc'], 'post' ) . '</p>';

	}

} // End HD_HTML_Helper

endif; // end class_exists check
