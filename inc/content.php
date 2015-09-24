<?php 
/********************************
 * Regular Content
********************************/
 ?>

<?php $id = (isset($id)) ? $id : $post->ID; ?>
<?php $i = 0; ?>
<?php if(get_field('content', $id)): ?>
<?php while (has_sub_field('content', $id)) : ?>
	<?php
		$layout = get_row_layout();
		switch($layout){

			case 'row':	
				if(get_sub_field('columns')): ?>
							
					<div <?php if(get_sub_field('anchor_id')): ?>id="<?php the_sub_field('anchor_id'); ?>"<?php endif; ?> <?php if(get_sub_field('anchor_label')): ?>data-label="<?php the_sub_field('anchor_label'); ?>"<?php endif; ?> class="row" style="<?php if (get_sub_field('background_color')): ?>background-color: <?php the_sub_field('background_color'); ?>; <?php endif; ?><?php if (get_sub_field('background_image')): ?>background-image: url('<?php the_sub_field('background_image'); ?>');<?php endif; ?>
						">
						<div class="inner container" style="<?php if (get_sub_field('custom_css')): ?><?php the_sub_field('custom_css'); ?>; <?php endif; ?>">
						
						<?php $total_columns = count( get_sub_field('columns', $id)); ?>

						<?php while (has_sub_field('columns', $id)) : ?>
							<?php
							switch($total_columns){
								case 2:
									$class = 'five';
									break;
								case 3:
									$class = 'one-third';
									break;
								case 4:
									$class = 'one-fourth';
									break;
								case 5:
									$class = 'one-fifth';
									break;
								case 6:
									$class = 'one-sixth';
									break;
								case 1:
								default:
									$class = 'ten';
									break;
							} ?>
							<div class="break-on-tablet span <?php if (get_sub_field('column_width')):?><?php the_sub_field('column_width'); ?><?php else: ?><?php echo $class; ?><?php endif; ?><?php if(get_sub_field('textbox_only')): ?> textblock<?php endif; ?>" style="
							<?php if (get_sub_field('text_color')):?>color: <?php the_sub_field('text_color'); ?>;<?php endif; ?><?php if (get_sub_field('css')):?><?php the_sub_field('css'); ?>;<?php endif; ?>
							">
								<?php the_sub_field('column_content'); ?>
							</div>
						<?php endwhile; ?>
						</div>
					</div>
				<?php endif; ?>
			<?php
			break;
			case 'family':

				if(get_sub_field('choose_family_members')): ?>

					<?php $posts = get_sub_field('choose_family_members'); ?>

					<div <?php if(get_sub_field('anchor_id')): ?>id="<?php the_sub_field('anchor_id'); ?>"<?php endif; ?> <?php if(get_sub_field('anchor_label')): ?>data-label="<?php the_sub_field('anchor_label'); ?>"<?php endif; ?> class="row family">
						<div class="container">
						    <h2 class="section-title"><?php the_sub_field('section_title'); ?></h2>
						    <ul class="family">
						    <?php foreach( $posts as $p ): // variable must NOT be called $post (IMPORTANT) ?>
						        <?php 
						            $image_size = array('width' => 239, 'height' => 301);
						            $image_src = get_image(get_post_thumbnail_id($p->ID), $image_size);
						        ?>
						        <li>
									<img src="<?php echo $image_src; ?>" />
									<div class="post-title">
										<h2><?php echo get_the_title( $p->ID); ?></h2>
										<p><?php the_field('role', $p->ID); ?></p>
									</div>
						        </li>           
						    <?php endforeach; ?>
						    </ul>
					    </div>
					</div>
				<?php endif; ?>
			<?php 
			break;
			case 'client_grid': ?>

			<?php 
			$terms = get_sub_field('clients');
			if( $terms ): ?>
				<div <?php if(get_sub_field('anchor_id')): ?>id="<?php the_sub_field('anchor_id'); ?>"<?php endif; ?> <?php if(get_sub_field('anchor_label')): ?>data-label="<?php the_sub_field('anchor_label'); ?>"<?php endif; ?> class="row clients">
					<div class="container">
						<h1 class="section-title"><?php the_sub_field('section_title'); ?></h1>
						<ul>
							<?php foreach( $terms as $term ): ?>
								<li>
								<?php 
									$taxonomy = $term->taxonomy;
									$term_id = $term->term_taxonomy_id;  
									$thumbnail = get_field('client_logo', $taxonomy . '_' . $term_id);
									$image_size = array('width' => 200);
									$image_url = get_image($thumbnail, $image_size);
								?>					
									<a class="overlay-btn">
										<img src="<?php echo $image_url; ?>" alt="">
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
				    </div>
				</div>				
			<?php endif; ?>			
			<?php } ?>

	<?php $i++; ?>
<?php endwhile; ?>
<?php wp_reset_postdata(); ?>
<?php endif; ?>
