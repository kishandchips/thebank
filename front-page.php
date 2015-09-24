<?php get_header(); ?>
<section id="front-page">

	<?php
	$args = array(									
		'post_type'   => 'slides',
		'post_status' => 'publish',	
		'posts_per_page' => 6,
		'order'       => 'ASC',
		'orderby'     => 'menu_order'
	);

	$slide_query = new WP_Query( $args ); ?>

	<?php if ( $slide_query->have_posts() ): ?>

		<div id="wowslider">
			<div class="ws_images">
				<ul>
					<?php while ( $slide_query->have_posts() ) : $slide_query->the_post(); ?>

						<?php 
							$post_object = get_field('slide_linkage'); 
							$image_size = array('width' => 1670, 'height' => 650);
							$image_full = get_field('slide_image');
							$image = bfi_thumb($image_full, $image_size);

							$image_size_large = array('width' => 1920, 'height' => 747);
							$image_large = bfi_thumb($image_full, $image_size_large);							
						?>

					    <li>
					    	<a href="<?php if ($post_object) { echo get_permalink($post_object->ID);} ?>">
								<img src="<?php echo $image; ?>"
								     srcset="<?php echo $image_large; ?> 1670w"
								     alt="<?php the_title(); ?>">						    	
					    	</a>
				    	</li>
			    	<?php endwhile; ?>
				</ul>
			</div>
			<div class="ws_bullets">
				<div>
					<?php while ( $slide_query->have_posts() ) : $slide_query->the_post(); ?>
						<a href="<?php the_field('slide_image'); ?>" title="'1'">
							<span>
							</span>
						</a>						
					<?php endwhile; ?>
				</div>
			</div>
		</div>		
		</div>		
	<?php endif; ?>

	<div class="front-news">
		<h2 class="section-title"><?php _e('Latest News') ?></h2>
		<div class="inner container">
			<?php

				if ( get_query_var('paged') ) { $paged = get_query_var('paged'); }
				elseif ( get_query_var('page') ) { $paged = get_query_var('page'); }
				else { $paged = 1; }		
				
				query_posts(array(									
					'post_type'   		=> 'post',
					'post_status' 		=> 'publish',	
					'posts_per_page' 	=> 6,
					'order'       		=> 'DESC',
					'orderby'     		=> 'date',	
					'ignore_sticky_posts' => true,
					'paged'		  		=> $paged	
				));
			
				$query = new WP_Query( $args ); 
			?>

				<ul class="posts">
					<?php 
					$i = 0;
					$i = 1;				
					while ( have_posts() ) : the_post(); ?>
						<?php 
							$image_size = array('width' => 400, 'height' => 260);
						?>
		            <li>
		                <?php include_module('post-item', array(
							'title' => get_the_title(),
							'url' =>  get_permalink(),
							'image_url' => get_post_thumbnail_src($image_size),
						)); ?>
		            </li>								
					<?php 
					$i++;
					endwhile; // end of the loop. ?>
				</ul>

		</div>
		<div id="navbelow">
			<?php next_posts_link('Next &raquo;'); ?>			
		</div>
		<a class="primary-btn" id="next"><?php _e('More News'); ?></a>		
		<?php wp_reset_postdata(); ?>
	</div>
</section>
<?php get_footer(); ?>