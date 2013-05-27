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
 
/**
 * Adds Foo_Widget widget.
 */
class CT_Posts_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
	 		'ct_posts_widget', // Base ID
			'cubetech Posts Widget', // Name
			array( 'description' => __( 'cubetech Posts Widget', 'text_domain' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );

		// These are our own options
		$title 	 = $instance['title']; // Widget title
		$phead 	 = $instance['phead']; // Heading format 		
		$ptype 	 = $instance['ptype']; // Post type 		
		$pshow 	 = $instance['pshow']; // Number of Tweets
		$plink	 = $instance['plink']; // Show posts link
	
		$beforetitle = '<'.$phead.'>';
		$aftertitle = '</'.$phead.'>';
		
	    // Output
		echo $before_widget;
		
			if ($title) echo $beforetitle . $title . $aftertitle; 
			
			$pq = new WP_Query(array( 'post_type' => $ptype, 'showposts' => $pshow ));
			if( $pq->have_posts() ) : 
			?>
	
			<?php while($pq->have_posts()) : $pq->the_post(); ?>
			
			<?php if ($ptype == 'portfolio') : ?>
											
				<p><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php echo get_the_post_thumbnail($pq->ID, 'medium'); ?></a></p>
			
			<?php else : ?>
	
				<p class="post-date"><?php the_time("d.m.Y"); ?></p>
		
				<p><?php the_title(); ?></p>
				
				<a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanenter Link zu <?php the_title_attribute(); ?>" class="post-title">Weiterlesen</a>
				
				<hr />
				
			<?php endif; ?>
				
			<?php wp_reset_query(); 
			endwhile; ?>

			<?php if ($plink == true) echo get_permalink( get_option('page_for_posts' ) ); ?>
			
			<?php endif; ?>		
			
		<?php
		// echo widget closing tag
		echo $after_widget;

	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['phead'] = strip_tags( $new_instance['phead'] );
		$instance['ptype'] = strip_tags( $new_instance['ptype'] );
		$instance['pshow'] = strip_tags( $new_instance['pshow'] );
		$instance['plink'] = strip_tags( $new_instance['plink'] );

		return $instance;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
			$phead = $instance[ 'phead' ];
			$ptype = $instance[ 'ptype' ];
			$pshow = $instance[ 'pshow' ];
			$plink = $instance[ 'plink' ];
		}
		else {
			$title = __( 'Blog', 'text_domain' );
			$phead = __( 'h2', 'text_domain' );
			$ptype = __( 'post', 'text_domain' );
			$pshow = __( '2', 'text_domain' );
			$plink = false;
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?><br />
			<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" size="90" />
		</label>
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'phead' ); ?>"><?php echo __( 'Widget Heading Format:' ); ?><br />
		<select name="<?php echo $this->get_field_name( 'phead' ); ?>">
			<option value="h2" <?php if ($phead == 'h2') { echo 'selected="selected"'; } ?>>H2 - &lt;h2&gt;&lt;/h2&gt;</option>
			<option value="h3" <?php if ($phead == 'h3') { echo 'selected="selected"'; } ?>>H3 - &lt;h3&gt;&lt;/h3&gt;</option>
			<option value="h4" <?php if ($phead == 'h4') { echo 'selected="selected"'; } ?>>H4 - &lt;h4&gt;&lt;/h4&gt;</option>
			<option value="strong" <?php if ($phead == 'strong') { echo 'selected="selected"'; } ?>>Bold - &lt;strong&gt;&lt;/strong&gt;</option>
		</select>
		</label>
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'ptype' ); ?>">
		<select name="<?php echo $this->get_field_name( 'ptype' ); ?>">
			<option value=""> - <?php echo __( 'Select Post Type' ); ?> - </option>
			<?php $args = array( 'public' => true );
			$post_types = get_post_types( $args, 'names' );
			foreach ($post_types as $post_type ) { ?>
				<option value="<?php echo $post_type; ?>" <?php if( $ptype == $post_type) { echo 'selected="selected"'; } ?>><?php echo $post_type;?></option>
			<?php }	?>
		</select>
		</label>
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'pshow' ); ?>"><?php echo __( 'Number of posts to show' ); ?>
			<input id="<?php echo $this->get_field_id( 'pshow' ); ?>" name="<?php echo $this->get_field_name( 'pshow' ); ?>" type="text" value="<?php echo esc_attr( $pshow ); ?>" size="2" />
		</label>
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'plink' ); ?>"><?php echo __( 'Show link to all articles' ); ?>
			<input id="<?php echo $this->get_field_id( 'plink' ); ?>" name="<?php echo $this->get_field_name( 'plink' ); ?>" type="checkbox" value="<?php echo esc_attr( $plink ); ?>" />
		</label>
		</p>
		<?php 
	}

} // class Foo_Widget

// register Foo_Widget widget
add_action( 'widgets_init', create_function( '', 'register_widget( "ct_posts_widget" );' ) );
