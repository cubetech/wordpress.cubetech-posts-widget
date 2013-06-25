<?php
/*
Plugin Name: cubetech Posts Widget
Plugin URI: http://www.cubetech.ch
Description: A sidebar Widget for displaying the most recent posts of any post type.
Version: 1.1.3
Author: cubetech GmbH
Author URI: http://www.cubetech.ch
Tags: custom, post, types, sidebar, widget, recent
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
		$title 	 = $instance['title']; 	// Widget title
		$phead 	 = $instance['phead']; 	// Heading format 		
		$ptype 	 = $instance['ptype']; 	// Post type 		
		$pcont 	 = $instance['pcont']; 	// Excerpt type 		
		$elength = $instance['elength'];// Excerpt length 		
		$pshow 	 = $instance['pshow']; 	// Number of Posts
		$pmore	 = $instance['pmore']; 	// Show "more" link
		$phr	 = $instance['phr']; 	// Show hr
		$plink	 = $instance['plink']; 	// Show posts link
		$ltitle  = $instance['ltitle'];	// Posts link title
	
		$beforetitle = '<'.$phead.'>';
		$aftertitle = '</'.$phead.'>';
		
	    // Output
		echo $before_widget;

		echo '<div class="ct-widget-content">
		';
		
			if ($title) echo $beforetitle . $title . $aftertitle; 
			
			$pq = new WP_Query(array( 'post_type' => $ptype, 'showposts' => $pshow ));
			if( $pq->have_posts() ) : 
			?>
	
			<?php while($pq->have_posts()) : $pq->the_post(); ?>
			
			<?php if ($ptype == 'portfolio') : ?>
											
				<p><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php echo get_the_post_thumbnail($pq->ID, 'medium'); ?></a></p>
			
			<?php else : ?>

				<div class="post-entry">
	
					<p class="post-date"><?php the_time("d.m.Y"); ?></p>
		
					<?php if ($pcont == 'title' || $pcont == 'both') : ?>
						<p class="post-title"><a href="<?php the_permalink(); ?>" title="Show article"><?php the_title(); ?></a></p>
					<?php endif;
						if ($pcont == 'excerpt' || $pcont == 'both') : ?>
						<p class="post-excerpt"><a href="<?php the_permalink(); ?>" title="Show article"><?php echo substr(get_the_excerpt(), 0, $elength) . '...'; ?></a></p>
					<?php endif; ?>
				
					<?php if ($pmore == true) : ?><a href="<?php the_permalink(); ?>" rel="bookmark" title="Permanenter Link zu <?php the_title_attribute(); ?>" class="post-title">Weiterlesen</a><?php endif; ?>
				
					<?php if ($phr == true) : ?><hr /><?php endif; ?>
				
				</div>

			<?php endif; ?>
				
			<?php wp_reset_query(); 
			endwhile; ?>

			</div>

			<?php if ($plink == true) echo '<p class="more"><a href="' . get_permalink( get_option('page_for_posts' ) ) . '" title="' . $ltitle . '">' . $ltitle . '</a></p>'; ?>
			
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
		$instance['pcont'] = strip_tags( $new_instance['pcont'] );
		$instance['elength'] = strip_tags( $new_instance['elength'] );
		$instance['pshow'] = strip_tags( $new_instance['pshow'] );
		$instance['pmore'] = strip_tags( $new_instance['pmore'] );
		$instance['phr'] = strip_tags( $new_instance['phr'] );
		$instance['plink'] = strip_tags( $new_instance['plink'] );
		$instance['ltitle'] = strip_tags( $new_instance['ltitle'] );

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
			$pcont = $instance[ 'pcont' ];
			$elength = $instance[ 'elength' ];
			$pshow = $instance[ 'pshow' ];
			$pmore = $instance[ 'pmore' ];
			$phr = $instance[ 'phr' ];
			$plink = $instance[ 'plink' ];
			$ltitle = $instance[ 'ltitle' ];
		}
		else {
			$title = __( 'Blog', 'text_domain' );
			$phead = __( 'h2', 'text_domain' );
			$ptype = __( 'post', 'text_domain' );
			$pcont = 'title';
			$elength = 100;
			$pshow = __( '2', 'text_domain' );
			$phr = true;
			$pmore = true;
			$plink = false;
			$ltitle = __( 'Show all articles', 'text_domain' );
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
		<label for="<?php echo $this->get_field_id( 'ptype' ); ?>"><?php echo __('Post type:'); ?><br />
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
		<label for="<?php echo $this->get_field_id( 'pcont' ); ?>"><?php echo __('Article content:'); ?><br />
		<select name="<?php echo $this->get_field_name( 'pcont' ); ?>">
			<option value=""> - <?php echo __( 'Select content type' ); ?> - </option>
			<option value="title" <?php if ($pcont == 'title') { echo 'selected="selected"'; } ?>>Title</option>
			<option value="excerpt" <?php if ($pcont == 'excerpt') { echo 'selected="selected"'; } ?>>Excerpt</option>
			<option value="both" <?php if ($pcont == 'both') { echo 'selected="selected"'; } ?>>Title & Excerpt</option>
		</select>
		</label>
		</p>
		<?php if($pcont == 'excerpt' || $pcont == 'both') : ?>
		<p>
		<label for="<?php echo $this->get_field_id( 'elength' ); ?>"><?php echo __( 'Excerpt length' ); ?>
			<input id="<?php echo $this->get_field_id( 'elength' ); ?>" name="<?php echo $this->get_field_name( 'elength' ); ?>" type="text" value="<?php echo esc_attr( $elength ); ?>" size="3" />
		</label>
		</p>
		<?php endif; ?>
		<p>
		<label for="<?php echo $this->get_field_id( 'pshow' ); ?>"><?php echo __( 'Number of posts to show' ); ?>
			<input id="<?php echo $this->get_field_id( 'pshow' ); ?>" name="<?php echo $this->get_field_name( 'pshow' ); ?>" type="text" value="<?php echo esc_attr( $pshow ); ?>" size="2" />
		</label>
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'pmore' ); ?>"><?php echo __( 'Show "more" link' ); ?>
			<input id="<?php echo $this->get_field_id( 'pmore' ); ?>" name="<?php echo $this->get_field_name( 'pmore' ); ?>" type="checkbox" <?php if ($pmore == true) echo "checked "; ?>/>
		</label>
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'phr' ); ?>"><?php echo __( 'Show hr' ); ?>
			<input id="<?php echo $this->get_field_id( 'phr' ); ?>" name="<?php echo $this->get_field_name( 'phr' ); ?>" type="checkbox" <?php if ($phr == true) echo "checked "; ?>/>
		</label>
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'plink' ); ?>"><?php echo __( 'Show link to all articles' ); ?>
			<input id="<?php echo $this->get_field_id( 'plink' ); ?>" name="<?php echo $this->get_field_name( 'plink' ); ?>" type="checkbox" <?php if ($plink == true) echo "checked "; ?>/>
		</label>
		</p>
		<?php if($plink == true) : ?>
		<p>
		<label for="<?php echo $this->get_field_id( 'ltitle' ); ?>"><?php echo __( 'Link title' ); ?>
			<input id="<?php echo $this->get_field_id( 'ltitle' ); ?>" name="<?php echo $this->get_field_name( 'ltitle' ); ?>" type="text" value="<?php echo esc_attr( $ltitle ); ?>" size="20" />
		</label>
		</p>
		<?php endif; ?>
		<?php 
	}

} // class Foo_Widget

// register Foo_Widget widget
add_action( 'widgets_init', create_function( '', 'register_widget( "ct_posts_widget" );' ) );
