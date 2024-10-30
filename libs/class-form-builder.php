<?php
/**
 * Library to Generate Form Elements
 *
 * @author      Rizwan <m.rizwan_47@yahoo.com>
 */
class WPSL_FormBuilder{

	/**
	 * Settings Fields
	 */
	var $fields;

	/**
	 * Essential functions before form
	 */
	public function before( $group, $section ){
		settings_fields($group.$section);
		do_settings_sections($group.$section);
		echo '<table class="form-table '.$group.'_'.$section.'">';
	}

	public function after(){
		echo '</table>';
		submit_button();
	}

	public function register_setting(){
		foreach( $this->fields as $setting_group=>$sections ){
			foreach($sections as $section=>$fields ){
				foreach( $fields as $field ){
					register_setting( $setting_group.$section, $field['id'] );
				}
			}
		}
	}

	public function add_element( $id, $label, $description="", $group='general', $section=null, $type="text", $default_value="" ){

		if( $section == null )
			$section	=	'all';

		$this->fields[$group][$section][]	=	array(
			'id'							=>	$id,
			'type'						=>	$type,
			'label'						=>	$label,
			'description'			=>	$description,
			'default_value'		=>	$default_value
		);

	}

	public function render_html( $group, $section='' ){

		if( !$section )
			$section	=	'all';

		$this->before($group, $section);

		foreach( (array) $this->fields[$group][$section] as $field ){

			echo '<tr>';

				echo '<th scope="row">';
					echo '<label for="' . $field['id'] . '">' . $field['label'] . '</label>';
				echo '</th>';

				echo '<td>';
					echo $this->generate_field( $field['id'], $field['label'], $field['type'], $field['default_value'] );
					echo '<p class="description">' . $field['description'] . '</p>';
				echo '</td>';

			echo '</tr>';

		}

		$this->after();

	}

	private function generate_field( $id, $label, $type, $default_value='' ){

		$old_val	=	get_option($id);

		switch ($type) {

			case 'select':
				// TODO: ???
				# code...
			break;

			case 'blank':
				echo '';
			break;

			case 'textarea':
				echo '<textarea name="' . $id . '" id="' . $id . '" rows="3" cols="45">' . ( $old_val == '' ? $default_value : $old_val ) . '</textarea>';
			break;

			case 'checkbox':
				echo '<label for="' . $id . '"><input name="' . $id . '" type="checkbox" id="' . $id . '" value="1" '. checked( $old_val, 1, false ) .' class="checkbox"> '.$label.'</label>';
			break;

			default:
				echo '<input name="' . $id . '" type="' . $type . '" id="' . $id . '" value="' . ( $old_val == '' ? $default_value : $old_val ) . '" class="regular-text ltr">';
			break;

		}

	}

}

$wpsl_forms	=	new WPSL_FormBuilder;
