<?php

define('THEME_NAME', 'thebank');

$template_directory = get_template_directory();

$template_directory_uri = get_template_directory_uri();

require( $template_directory . '/inc/default/functions.php' );

require( $template_directory . '/inc/default/hooks.php' );

require( $template_directory . '/inc/default/shortcodes.php' );

// Custom Actions

add_action( 'after_setup_theme', 'custom_setup_theme' );

add_action( 'init', 'custom_init');

add_action( 'wp', 'custom_wp');

add_action( 'widgets_init', 'custom_widgets_init' );

add_action( 'wp_enqueue_scripts', 'custom_scripts', 30);

add_action( 'wp_print_styles', 'custom_styles', 30);

add_action('wp_footer', 'add_google_analytics');


// Custom Filters

add_filter( 'comment_form_defaults', 'custom_comment_form_defaults');

add_filter( 'body_class', 'custom_body_classes', 10, 2 );

add_filter( 'pre_get_posts', 'custom_pre_get_posts');

add_filter( 'the_excerpt', 'custom_the_exceprt');

add_filter('mce_buttons_2', 'wpb_mce_buttons_2');

add_filter( 'tiny_mce_before_init', 'my_mce_before_init_insert_formats' ); 

add_filter( 'embed_oembed_html', 'custom_oembed_filter', 10, 4 ) ;

add_filter('image_send_to_editor','give_linked_images_class',10,8);



//Custom shortcodes

remove_shortcode('gallery');

add_shortcode( 'gallery', 'custom_gallery' );


function custom_setup_theme() {
		
	add_theme_support( 'post-thumbnails' );
	
	add_theme_support( 'html5' );

	add_theme_support('editor_style');

	add_post_type_support('page', 'excerpt');


	register_nav_menus( array(
		'primary' => __( 'Primary', THEME_NAME ),
		'secondary' => __( 'secondary', THEME_NAME ),
	) );

	add_editor_style('css/editor-style.css');

}

function custom_init(){
	global $template_directory;

	require( $template_directory . '/inc/classes/bfi-thumb.php' );

	require( $template_directory . '/inc/classes/custom-post-type.php' );

	require( $template_directory . '/inc/classes/category-dropdown-url-walker.php' );	

	if(function_exists('get_field')) {

			
		$work_uri = get_page_uri(get_field('work_page', 'options'));
		$family_uri = get_page_uri(get_field('family_page', 'options'));

		//Works custom post type
		if ($work_uri) {

			$works = new Custom_Post_Type( 'Work', 
				array(
					'rewrite' => array('with_front' => false, 'slug' => $work_uri),
					'capability_type' => 'post',
					'publicly_queryable' => true,
					'has_archive' => true, 
					'hierarchical' => true,
					'menu_position' => null,
					'menu_icon' => 'dashicons-admin-generic',
					'supports' => array('title', 'editor', 'thumbnail'),
					'plural' => "Work",		
				)
			);

			$works->register_taxonomy("Client",
				array(
					'name' => 'client',
					'rewrite' => array( 'slug' => 'clients' ),
				),
				array(
					'plural' => "Clients"
				)
			);

			$works->register_post_type();
		}	
		//Family custom post type
		if ($family_uri) {
			$family = new Custom_Post_Type( 'Family', 
				array(
					'rewrite' => array('with_front' => false, 'slug' => $family_uri),
					'capability_type' => 'post',
					'publicly_queryable' => true,
					'has_archive' => false, 
					'hierarchical' => true,
					'menu_position' => null,
					'menu_icon' => 'dashicons-admin-users',
					'supports' => array('title', 'editor', 'thumbnail'),
					'plural' => "Family",		
				)
			);

			$family->register_taxonomy("Family Category",
				array(
					'name' => 'family_cat',
					'rewrite' => array( 'slug' => 'family-category' ),
				),
				array(
					'plural' => "Family Categories"
				)
			);

			$family->register_post_type();
		}

		//Slides custom post type
		$slides = new Custom_Post_Type( 'Slides', 
			array(
				'rewrite' => array('with_front' => false, 'slug' => 'slides'),
				'capability_type' => 'post',
				'publicly_queryable' => true,
				'has_archive' => true, 
				'hierarchical' => true,
				'menu_position' => null,
				'menu_icon' => 'dashicons-format-gallery',
				'supports' => array('title', 'editor', 'thumbnail'),
				'plural' => "Slides",		
			)
		);		

		$slides->register_post_type();		
	}
}

if( function_exists('acf_add_options_page') ) acf_add_options_page();

function custom_wp(){
	
}

function custom_widgets_init() {
	global $template_directory;

	require( $template_directory . '/inc/widgets/socials.php' );
	
	register_sidebar( array(
		'name' => __( 'Default', THEME_NAME ),
		'id' => 'default',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' => '</aside>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',	
	) );
}

function custom_scripts() {
	global $template_directory_uri, $post;

	wp_enqueue_script('jquery');

	wp_enqueue_script('modernizr', $template_directory_uri.'/js/libs/modernizr.min.js', null, '', true);
	//wp_enqueue_script('plugins', $template_directory_uri.'/js/plugins.js', array('jquery', 'modernizr'), '', true);
	wp_enqueue_script('wowslider', $template_directory_uri.'/js/plugins/wowslider.js', array('jquery'), '', true);
	wp_enqueue_script('imagesloaded', $template_directory_uri.'/js/plugins/jquery.imagesloaded.js', array('jquery'), '', true);
	wp_enqueue_script('magnific', $template_directory_uri.'/js/plugins/jquery.magnific-popup.min.js', array('jquery'), '', true);
	wp_enqueue_script( 'expander',  get_template_directory_uri() . '/js/plugins/jquery.expander.min.js', array('jquery'),null,true );	
	wp_enqueue_script( 'infinite_scroll',  get_template_directory_uri() . '/js/plugins/jquery.infinitescroll.min.js', array('jquery'),null,true );
	wp_enqueue_script( 'wow/script',  get_template_directory_uri() . '/js/script.js', array('jquery'),null,true );
	wp_enqueue_script('lettering', $template_directory_uri.'/js/plugins/jquery.lettering.js', array('jquery'), '', true);	
	wp_enqueue_script('main', $template_directory_uri.'/js/main.js', array('jquery', 'modernizr'), '', true);

	wp_enqueue_script( 'parallax',  get_template_directory_uri() . '/js/plugins/parallax.min.js', array('jquery'),null,true );

	wp_localize_script( 'main', 'url', array(
		'template' => $template_directory_uri,
		'base' => site_url(),
		'ajax' => admin_url('admin-ajax.php')
	));

	if( !empty($post) ) {
		wp_localize_script( 'main', 'post', array(
			'id' => $post->ID
		));
	}

	wp_register_script('infinitescroll', $template_directory_uri.'/js/plugins/jquery.infinitescroll.js', array('jquery'), '', true);
}


function custom_styles() {
	global $wp_styles, $template_directory_uri;

	wp_enqueue_style( 'style', $template_directory_uri . '/css/style.css' );	
	wp_enqueue_style( 'wow-style', $template_directory_uri . '/css/wow-style.css' );	
	wp_enqueue_style( 'fonts', '//fast.fonts.net/cssapi/a50373cb-2797-4569-af88-71d84b5791d0.css' );

	wp_dequeue_style('gforms_formsmain_css');

}


function custom_comment_form_defaults($args){
	$args['title_reply'] = '<span class="title">'.__( 'Join the conversation' ).'</span>';
	$args['title_reply_to'] = '<span class="title">'.__( 'Join the conversation' ).'</span>';
	$args['comment_notes_before'] = '';
	//$args['cancel_reply_link'] = __( 'Cancel reply' );
	//$args['label_submit'] = __( 'Post Comment' );
	return $args;
}

function custom_gallery( $atts ) {

	$output = '';

    extract(shortcode_atts(array(
        'ids'      => array()
    ), $atts));

    $ids = explode(',', $atts['ids']);

   	if( !empty($ids) ){
   		ob_start();
	    ?>    
	    <div class="post-gallery">
		<?php $i = 0; ?>
		<?php foreach($ids as $id):		
				$image_size = array('width' => 528, 'height' => 406);
				$image_url = get_image($id, $image_size);
				$image_full = get_image($id, 'full');
				$image = get_post($id);
			?>

			<?php if ($id === end($ids)): ?>
				<?php if( $i % 2 == 1): ?>				
					<a href="<?php echo $image_full; ?>" class="image overlay-btn">
						<img src="<?php echo $image_url; ?>" data-id="<?php echo $id; ?>" />
					</a>					
				<?php else: ?>
					<?php $image_size = array('width' => 1080, 'height' => 405); ?>
					<?php $image_url = get_image($id, $image_size); ?>
					<a href="<?php echo $image_full; ?>" class="image full overlay-btn">
						<img src="<?php echo $image_url; ?>" data-id="<?php echo $id; ?>" />
					</a>					
				<?php endif; ?>
			<?php else: ?>
				<a href="<?php echo $image_full; ?>" class="image overlay-btn">
					<img src="<?php echo $image_url; ?>" data-id="<?php echo $id; ?>" />
				</a>				
			<?php endif; ?>	
		<?php $i++; ?>
		<?php endforeach; ?>
	    </div>
	    <?php
	    $output = ob_get_contents();
		ob_end_clean();
    }
	return $output;
}

function custom_body_classes( $wp_classes, $extra_classes )
{
    $blacklist = array( 'author' );

    $wp_classes = array_diff( $wp_classes, $blacklist );
    $wp_classes[] = 'header-fixed';
	return array_merge( $wp_classes, (array) $extra_classes );
}

//Page Slug Body Class
function add_slug_body_class( $classes ) {
	global $post;
	if ( isset( $post ) ) {
		$classes[] = $post->post_type . '-' . $post->post_name;
	}
	return $classes;
}
add_filter( 'body_class', 'add_slug_body_class' );

function custom_pre_get_posts( $query ) {
	if($query->is_main_query() && is_category()){
		$query->set('posts_per_page', 5);
	}

	return $query;
}

function custom_the_exceprt($content) {
	return strip_shortcodes( $content );
}


function wpb_mce_buttons_2($buttons) {
	array_unshift($buttons, 'styleselect');
	return $buttons;
}

/*
* Callback function to filter the MCE settings
*/

function my_mce_before_init_insert_formats( $init_array ) {  
	$style_formats = array(  
		// Each array child is a format with it's own settings
		array(  
			'title' => 'Subtitle',  
			'block' => 'span',  
			'classes' => 'subtitle',
			'wrapper' => true,
			
		),  
		array(  
			'title' => 'Uppercase',  
			'block' => 'span',  
			'classes' => 'uppercase-low',
			'wrapper' => true,
		), 		
		array(  
			'title' => 'Uppercase - bold',  
			'block' => 'span',  
			'classes' => 'uppercase',
			'wrapper' => true,
		), 
		array(  
			'title' => 'Expanded text',  
			'block' => 'div',  
			'classes' => 'expander',
			'wrapper' => true,
		),
		array(  
			'title' => 'Supercript',  
			'inline' => 'sup',  
			'classes' => '',
			'wrapper' => true,
		),							
	);  

	$init_array['style_formats'] = json_encode( $style_formats );  
	
	return $init_array;  
}

// Custom wrapper around embedded Videos
function custom_oembed_filter($html, $url, $attr, $post_ID) {
    $return = '<div class="video-container">'.$html.'</div>';
    return $return;
}

/**
 * Attach a class to linked images' parent anchors
 * e.g. a img => a.img img
 */
function give_linked_images_class($html, $id, $caption, $title, $align, $url, $size, $alt = '' ){
  $classes = 'img'; // separated by spaces, e.g. 'img image-link'

  // check if there are already classes assigned to the anchor
  if ( preg_match('/<a.*? class=".*?">/', $html) ) {
    $html = preg_replace('/(<a.*? class=".*?)(".*?>)/', '$1 ' . $classes . '$2', $html);
  } else {
    $html = preg_replace('/(<a.*?)>/', '$1 class="' . $classes . '" >', $html);
  }
  return $html;
}

function add_google_analytics() { ?>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-66879242-1', 'auto');
  ga('send', 'pageview');

</script>

<?php }

function searchfilter($query) {

    if ($query->is_search && !is_admin() ) {
        $query->set('post_type',array('post','work'));
    }

return $query;
}

add_filter('pre_get_posts','searchfilter');

