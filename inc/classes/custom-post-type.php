<?php

class Custom_Post_Type
	{
		public $post_type_name;
		public $post_type_args;
		public $post_type_labels;
		
		/* Class constructor */
		public function __construct( $name, $args = array(), $labels = array() )
		{
			// Set some important variables
			$this->post_type_name		= strtolower( str_replace( ' ', '_', $name ) );
			$this->post_type_args 		= $args;
			$this->post_type_labels 	= $labels;

			//$this->register_post_type();

			return $this;
		}
		
		/* Method which registers the post type */
		public function register_post_type()	
		{		
			//Capitilize the words and make it plural
			$name 		= ucwords( str_replace( '_', ' ', $this->post_type_name ) );
			$plural = (isset($this->post_type_args['plural'])) ? $this->post_type_args['plural'] : $name . "s";

			// We set the default labels based on the post type name and plural. We overwrite them with the given labels.
			$labels = array_merge(

				// Default
				array(
					'name' 					=> _x( $plural, 'post type general name' ),
					'singular_name' 		=> _x( $name, 'post type singular name' ),
					'add_new' 				=> _x( 'Add New', strtolower( $name ) ),
					'add_new_item' 			=> __( 'Add New ' . $name ),
					'edit_item' 			=> __( 'Edit ' . $name ),
					'new_item' 				=> __( 'New ' . $name ),
					'all_items' 			=> __( 'All ' . $plural ),
					'view_item' 			=> __( 'View ' . $name ),
					'search_items' 			=> __( 'Search ' . $plural ),
					'not_found' 			=> __( 'No ' . strtolower( $plural ) . ' found'),
					'not_found_in_trash' 	=> __( 'No ' . strtolower( $plural ) . ' found in Trash'), 
					'parent_item_colon' 	=> '',
					'menu_name' 			=> $plural
				),

				// Given labels
				$this->post_type_labels

			);

			// Same principle as the labels. We set some default and overwite them with the given arguments.
			$args = array_merge(

				// Default
				array(
					'label' 				=> $plural,
					'labels' 				=> $labels,
					'public' 				=> true,
					'show_ui' 				=> true,
					'show_in_nav_menus' 	=> true,
					'_builtin' 				=> false,
				),

				// Given args
				$this->post_type_args

			);

			// Register the post type
			register_post_type( $this->post_type_name, $args );
			
			return $this;
		}
		
		/* Method to attach the taxonomy to the post type */
		public function register_taxonomy( $name, $args = array(), $labels = array() )
		{
			if( ! empty( $name ) ) {			
				// We need to know the post type name, so the new taxonomy can be attached to it.
				$post_type_name = $this->post_type_name;

				// Taxonomy properties
				$taxonomy_name		= ( !empty($args['name']) ) ? $args['name'] : strtolower( str_replace( ' ', '_', $name ) );
				$taxonomy_labels	= $labels;
				$taxonomy_args		= $args;

			if( ! taxonomy_exists( $taxonomy_name ) ) {
				//Capitilize the words and make it plural
				$name 		= ucwords( str_replace( '_', ' ', $name ) );
				$plural = (isset($labels['plural'])) ? $labels['plural'] : $name . "s";

				// Default labels, overwrite them with the given labels.
				$labels = array_merge(

					// Default
					array(
						'name' 					=> _x( $plural, 'taxonomy general name' ),
						'singular_name' 		=> _x( $name, 'taxonomy singular name' ),
					    'search_items' 			=> __( 'Search ' . $plural ),
					    'all_items' 			=> __( 'All ' . $plural ),
					    'parent_item' 			=> __( 'Parent ' . $name ),
					    'parent_item_colon' 	=> __( 'Parent ' . $name . ':' ),
					    'edit_item' 			=> __( 'Edit ' . $name ), 
					    'update_item' 			=> __( 'Update ' . $name ),
					    'add_new_item' 			=> __( 'Add New ' . $name ),
					    'new_item_name' 		=> __( 'New ' . $name . ' Name' ),
					    'menu_name' 			=> __( $plural ),
					),

					// Given labels
					$taxonomy_labels

				);

				// Default arguments, overwitten with the given arguments
				$args = array_merge(

					// Default
					array(
						'label'					=> $plural,
						'labels'				=> $labels,
						'public' 				=> true,
						'show_ui' 				=> true,
						'show_in_nav_menus' 	=> true,
						'hierarchical' 			=> true,
						'_builtin' 				=> false,
					),

					// Given
					$taxonomy_args

				);
				
				// Add the taxonomy to the post type
				register_taxonomy( $taxonomy_name, $post_type_name, $args );
				
			} else {
							
				register_taxonomy_for_object_type( $taxonomy_name, $post_type_name );
				
			}
		}
	}	
}