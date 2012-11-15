<?php
/**
 * Plugin Name: cubetech Posts Widget
 * Plugin URI: http://www.cubetech.ch
 * Description: A sidebar Widget for displaying the most recent posts of any post type.
 * Version: 1.0
 * Author: cubetech GmbH
 * Author URI: http://www.cubetech.ch
 * Tags: custom post types, sidebar widget
 */
 
/**
 * Create the init function
 */ 
function ct_posts_widget_init() {
	if ( !function_exists( 'register_sidebar_widget' ))
		return;

	// code here
}

/**
 * The widget output function
 */
function ct_posts_widget($args) {
	global $post;
	extract($args);

	// These are our own options
	$options = get_option( 'ct_posts_widget' );
	$title 	 = $options['title']; // Widget title
	$phead 	 = $options['phead']; // Heading format 		
	$ptype 	 = $options['ptype']; // Post type 		
	$pshow 	 = $options['pshow']; // Number of Tweets

	$beforetitle = '<'.$phead.'>';
	$aftertitle = '</'.$phead.'>';
	
    // Output
	echo $before_widget;
	
		if ($title) echo $beforetitle . $title . $aftertitle; 
		
		$pq = new WP_Query(array( 'post_type' => $ptype, 'showposts' => $pshow ));
		if( $pq->have_posts() ) : 
		?>

		<?php while($pq->have_posts()) : $pq->the_post(); ?>

			<p class="post-date"><?php the_time("d.m.Y"); ?></p>
	
			<p><?php the_title(); ?></p>
			
			<a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanenter Link zu <?php the_title_attribute(); ?>" class="post-title">Weiterlesen</a>
			
			<hr />
		<?php wp_reset_query(); 
		endwhile; ?>
		
		<?php endif; ?>		
		
	<?php
	// echo widget closing tag
	echo $after_widget;
}

/**
 * Widget settings form function
 */
function ct_posts_widget_control() {

	// Get options
	$options = get_option( 'ct_posts_widget' );
	// options exist? if not set defaults
	if ( !is_array( $options ))
		$options = array( 
			'title' => 'Blog',
			'phead' => 'h2',
			'ptype' => 'post',
			'pshow' => '2' 
		);
		// form posted?
		if ( $_POST['latest-cpt-submit'] ) {
			$options['title'] = strip_tags( $_POST['latest-cpt-title'] );
			$options['phead'] = $_POST['latest-cpt-phead'];
			$options['ptype'] = $_POST['latest-cpt-ptype'];
			$options['pshow'] = strip_tags( $_POST['latest-cpt-pshow'] );
			update_option( 'ct_posts_widget', $options );
		}	
		// Get options for form fields to show
		$title = $options['title'];
		$phead = $options['phead'];
		$ptype = $options['ptype'];
		$pshow = $options['pshow'];

		// The widget form fields
		?>
		<p>
		<label for="latest-cpt-title"><?php echo __( 'Widget Title' ); ?><br />
			<input id="latest-cpt-title" name="latest-cpt-title" type="text" value="<?php echo $title; ?>" size="30" />
		</label>
		</p>
		<p>
		<label for="latest-cpt-phead"><?php echo __( 'Widget Heading Format' ); ?><br />
		<select name="latest-cpt-phead">
			<option value="h2" <?php if ($phead == 'h2') { echo 'selected="selected"'; } ?>>H2 - <h2></h2></option>
			<option value="h3" <?php if ($phead == 'h3') { echo 'selected="selected"'; } ?>>H3 - <h3></h3></option>
			<option value="h4" <?php if ($phead == 'h4') { echo 'selected="selected"'; } ?>>H4 - <h4></h4></option>
			<option value="strong" <?php if ($phead == 'strong') { echo 'selected="selected"'; } ?>>Bold - <strong></strong></option>
		</select>
		</label>
		</p>
		<p>
		<label for="latest-cpt-ptype">
		<select name="latest-cpt-ptype">
			<option value=""> - <?php echo __( 'Select Post Type' ); ?> - </option>
			<?php $args = array( 'public' => true );
			$post_types = get_post_types( $args, 'names' );
			foreach ($post_types as $post_type ) { ?>
				<option value="<?php echo $post_type; ?>" <?php if( $options['ptype'] == $post_type) { echo 'selected="selected"'; } ?>><?php echo $post_type;?></option>
			<?php }	?>
		</select>
		</label>
		</p>
		<p>
		<label for="latest-cpt-pshow"><?php echo __( 'Number of posts to show' ); ?>
			<input id="latest-cpt-pshow" name="latest-cpt-pshow" type="text" value="<?php echo $pshow; ?>" size="2" />
		</label>
		</p>
		<input type="hidden" id="latest-cpt-submit" name="latest-cpt-submit" value="1" />
<?php 
}

wp_register_sidebar_widget( 'widget_latest_cpt', __('cubetech Posts Widget'), 'ct_posts_widget' );
wp_register_widget_control( 'widget_latest_cpt', __('cubetech Posts Widget'), 'ct_posts_widget_control', 300, 200 );