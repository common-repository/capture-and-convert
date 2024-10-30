<?php
global $wpsl_stats;

$args	=	array();
if( !empty($_GET['filtering']) && sanitize_text_field($_GET['filtering']) == "true" ){
	// $args['filter']				=	$_GET['filter'];
	$args['date_range1']	=	sanitize_text_field($_GET['date_range1']);
	$args['date_range2']	=	sanitize_text_field($_GET['date_range2']);
}else{
	// $args['filter']				=	'0';
	$args['date_range1']	=	date('Y-m-d', strtotime('-7 days', time()));
	$args['date_range2']	=	date('Y-m-d');
}

if( !empty($_GET['widget']) && sanitize_text_field($_GET['widget']) > 0 ){
	$args['widget_id']	=	sanitize_text_field($_GET['widget']);
}

$data	=	$wpsl_stats->generate_charts_data($args); ?>

<h4>Stats and Reports</h4>
</form> <!-- Closing old form -->
<form action="<?php echo admin_url('admin.php'); ?>" method="get" enctype="multipart/form-data">

	<input type="hidden" name="page" value="capture-and-convert-menu" />
	<input type="hidden" name="tab" value="wpsl_dashboard" />
	<input type="hidden" name="filtering" value="true" />

	<div class="tablenav top">

		<?php /*
		<div class="alignleft actions">
			<h3>Filter By</h3>
			<select name="filter" id="filter">
				<?php $filby	=	$args['filter']; ?>
				<option <?php echo ($filby === '0' ? 'selected="selected"' : ''); ?> value="0">All</option>
				<option <?php echo ($filby === 'share' ? 'selected="selected"' : ''); ?> value="share">All Share Widgets</option>
				<option <?php echo ($filby === 'follow' ? 'selected="selected"' : ''); ?> value="follow">All Follow Widgets</option>
				<option <?php echo ($filby === 'email' ? 'selected="selected"' : ''); ?> value="email">All Email Widgets</option>
				<option <?php echo ($filby === 'floating_widget' ? 'selected="selected"' : ''); ?> value="floating_widget">All Floating Widgets</option>
				<option <?php echo ($filby === 'facebook_like' ? 'selected="selected"' : ''); ?> value="facebook_like">Facebook Likes</option>
				<option <?php echo ($filby === 'facebook_share' ? 'selected="selected"' : ''); ?> value="facebook_share">Facebook Shares</option>
				<option <?php echo ($filby === 'twitter_tweet' ? 'selected="selected"' : ''); ?> value="twitter_tweet">Twitter Tweets</option>
				<option <?php echo ($filby === 'twitter_follow' ? 'selected="selected"' : ''); ?> value="twitter_follow">Twitter Follows</option>
				<option <?php echo ($filby === 'youtube_subscribe' ? 'selected="selected"' : ''); ?> value="youtube_subscribe">Youtube Subscribes</option>
				<option <?php echo ($filby === 'manual_email' ? 'selected="selected"' : ''); ?> value="manual_email">Manual Email</option>
			</select>
		</div> */ ?>

		<div class="alignleft actions">
			<h3>Date Range</h3>
			<?php
			$widget = sanitize_text_field($_GET['widget']);
			if( $widget != '' ){
				echo '<span class="wpsl_rm_tag">'.get_the_title($widget).' <a href="'.esc_url( add_query_arg( 'widget', false ) ).'">X</a></span><br />';
				echo '<input type="hidden" name="widget" value="'.$widget.'" />';
			} ?>
			<input type="date" name="date_range1" id="date_range1" placeholder="Date From" value="<?php echo $args['date_range1']; ?>" />
			<input type="date" name="date_range2" id="date_range2" placeholder="Date To" value="<?php echo $args['date_range2']; ?>" />

			<input type="submit" name="filter_action" id="post-query-submit" class="button" value="Filter">
		</div>

		<br class="clear">

	</div>

</form>

<canvas id="myChart" width="400" height="150"></canvas>
<script>
var ctx = document.getElementById("myChart");
var myChart = new Chart(ctx, {
    type: 'line',
    data: <?php echo $data; ?>,
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    }
});
</script>

<h2>Top Widgets</h2>

<?php

$widgets_query	=	new WP_Query( array(
	'post_type'				=> array( 'stu_widgets', 'ftu_widgets', 'etu_widgets' ),
	'posts_per_page'	=> 10,
	'meta_key'				=> 'total_impressions',
	'orderby'					=> 'meta_value_num',
	'order'						=> 'DESC'
) );
?>

<table class="wp-list-table widefat fixed striped">

	<thead>
		<tr>
			<th scope="col" id="title" class="manage-column">Locker Title</th>
			<th scope="col" id="widget_type" class="manage-column">Widget Type</th>
			<th scope="col" id="views" class="manage-column">Views</th>
			<th scope="col" id="impressions" class="manage-column">Impressions</th>
			<th scope="col" id="ratio" class="manage-column">Ratio</th>
			<th scope="col" id="theme" class="manage-column">Theme</th>
		</tr>
	</thead>

	<tbody id="the-list">
		<?php
		if( $widgets_query->have_posts() ){

			while( $widgets_query->have_posts() ){
				$widgets_query->the_post();

				$stats				=	$wpsl_stats->get_widget_stats();
				$theme				=	get_post_meta( get_the_ID(), 'locker_widget_template', true );
				$widget_type	=	wpsl_label_widget_type( get_post_type() );

				echo '<tr>';
					echo '<td><a href="'.esc_url( add_query_arg( 'widget', get_the_ID() ) ).'">';
						the_title();
					echo '</a></td>';
					echo '<td>'.$widget_type.'</td>';
					echo '<td>'.$stats['views'].'</td>';
					echo '<td>'.$stats['impressions'].'</td>';
					echo '<td>'.$stats['ratio'].'%</td>';
					echo '<td>'.$theme.'</td>';
				echo '</tr>';

			}

		}

		?>
	</tbody>

</table>
