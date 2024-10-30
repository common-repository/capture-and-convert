<?php
// WP_List_Table is not loaded automatically so we need to load it in our application
if( ! class_exists( 'WP_List_Table' ) ) {
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

/**
 * Create a new table class for all Widgets that will extend the WP_List_Table
 *
 * @since 0.2
 * @author Rizwan <m.rizwan_47@yahoo.com>
 */
class WPSL_List_Widgets_Table extends WP_List_Table{

  /**
   * Prepare the items for the table to process
   *
   * @return Void
   */
  public function prepare_items(){

		$columns	= $this->get_columns();
    $hidden		= $this->get_hidden_columns();
    $sortable	= $this->get_sortable_columns();
    $data			= $this->table_data();

		usort( $data, array( &$this, 'sort_data' ) );

    $perPage = 10;

    $currentPage	= $this->get_pagenum();
    $totalItems		= count($data);

    $this->set_pagination_args( array(
      'total_items'	=> $totalItems,
      'per_page'		=> $perPage
    ) );

    $data	= array_slice($data,(($currentPage-1)*$perPage),$perPage);

    $this->_column_headers = array($columns, $hidden, $sortable);
    $this->items = $data;

  }

  /**
   * Override the parent columns method. Defines the columns to use in your listing table
   *
   * @return Array
   */
  public function get_columns(){

    return array(
			'uiper'									=> 'V / I / %',
			'title'									=> 'Locker Title',
			'shortcode'							=> 'Shortcode',
			'theme'									=> 'Theme',
			'widget_type'						=> 'Widget Type',
			'visibility_conditions'	=> 'Visibility Conditions',
			'actions'								=> 'Actions'
    );

  }

  /**
   * Define which columns are hidden
   *
   * @return Array
   */
  public function get_hidden_columns(){
		return array();
  }

  /**
   * Define the sortable columns
   *
   * @return Array
   */
  public function get_sortable_columns(){
		return array('title' => array('title', false));
  }

  /**
   * Get the table data
   *
   * @return Array
   */
  private function table_data(){

		global $wpsl_stats;

    $data = array();

		$widgets_query		=	new WP_Query( array(
			'post_type'				=> array( 'stu_widgets', 'ftu_widgets', 'etu_widgets' ),
			'posts_per_page'	=> -1,
			// 'post_status'			=> array( 'publish' )
		) );

		if( $widgets_query->have_posts() ){

			while( $widgets_query->have_posts() ){ $widgets_query->the_post();

				$widget_id					=	get_the_ID();
				$post_type					=	get_post_type();

				$stats_widget_types	=	array(
					'stu_widgets'				=>	'share',
					'ftu_widgets'				=>	'follow',
					'etu_widgets'				=>	'email'
				);

				$stats							=	$wpsl_stats->get_widget_stats( $widget_id, $stats_widget_types[$post_type] );
				$uiper							=	$stats['views'].' / '.$stats['impressions'].' / '.$stats['ratio'].'%';
				$title							=	esc_attr(get_the_title());
				$shortcode					=	wpsl_widget_shortcode( $widget_id );
				$widget_type				=	wpsl_label_widget_type( $post_type );
				$edit_url						=	esc_url(get_edit_post_link( $widget_id ));
				$delete_url					=	esc_url(get_delete_post_link( $widget_id ));
				$theme						  =	get_post_meta( $widget_id, 'locker_widget_template', true );

				if( $title == '' )
					$title		=	'(NO TITLE)';

				$data[] = array(
					'uiper'									=> $uiper,
					'title'									=> '<a href="'.$edit_url.'">'.esc_attr($title).'</a>',
					'shortcode'							=> '<code>'.$shortcode.'</code>',
					'theme'									=> $theme,
					'widget_type'						=> $widget_type,
					'visibility_conditions'	=> '-',
					'actions'								=> '<a href="'.$edit_url.'">Edit</a> | <a href="'.$delete_url.'">Delete</a>'
		    );

			}

		}

    return $data;

  }

  /**
   * Define what data to show on each column of the table
   *
   * @param  Array $item        Data
   * @param  String $column_name - Current column name
   *
   * @return Mixed
   */
  public function column_default( $item, $column_name ){

    switch( $column_name ) {
			case 'uiper':
			case 'title':
			case 'shortcode':
			case 'theme':
			case 'widget_type':
			case 'visibility_conditions':
			case 'actions':
				return $item[ $column_name ];
      default:
				return print_r( $item, true ) ;
    }

  }

  /**
   * Allows you to sort the data by the variables set in the $_GET
   *
   * @return Mixed
   */
  private function sort_data( $a, $b ){

    // Set defaults
    $orderby	= 'title';
    $order		= 'asc';

    // If orderby is set, use this as the sort column
    if(!empty($_GET['orderby'])){
			$orderby = sanitize_text_field($_GET['orderby']);
    }

    // If order is set use this as the order
    if(!empty($_GET['order'])){
			$order = sanitize_text_field($_GET['order']);
    }

    $result = strcmp( $a[$orderby], $b[$orderby] );

    if($order === 'asc'){
			return $result;
    }

    return -$result;

	}

}
