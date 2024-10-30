<?php

/**
 * The MG Parallax Slider Plugin
 *
 * Plugin Name: 	MG Parallax Slider
 * Plugin URI:      http://mgwebthemes.com
 * Github URI:      https://github.com/maheshwaghmare/mg-parallax-slider
 * Description:     Create parallax slider for your website. It provide ultimate admin panel for slide customizaton. "We provide two ways for creating slider." 1. </strong> If you want to create slides using "MG Slider" custom post. Then Create slides "MG Slider->Add New". And Use shortcode <strong>[mgps-slider-post].</strong> Or <strong> 2 </strong>Use below Unlimited slides useing below section and use <strong>[mgps-slider]</strong>.
 * Author:          Mahesh Waghmare
 * Author URI:      http://mgwebthemes.com
 * Version:         1.0.
 * License:         GPL2+
 * License URI:     http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * @author          Mahesh M. Waghmare <mwaghmare7@gmail.com>
 * @license         GNU General Public License, version 2
 * @copyright       2014 MG Web Themes
*------------------------------------------------------------------------------*/



/*
 *		Add Thumbnail Support
 *
 *----------------------------------------------------------------------------*/
global $mgps;

if($mgps['mg-mgps-thumb-enable']) {

	if ( function_exists( 'add_theme_support' ) ) {  
		// Specify image sizes for MGPS 
		add_image_size( 'mgps-thumb', 300, 225 , array( 'left', 'top' ) );
	}

}





		/*
		 *		Register custom post type:		MGPS Slider
		 *
		 *----------------------------------------------------------------------------*/
		 // Register Custom Post Type
		function mgps_parallax_slider_post_init() {
			$labels = array(
				'name'                => _x( 'Sliders', 'Post Type General Name', 'text_domain' ),
				'singular_name'       => _x( 'Slider', 'Post Type Singular Name', 'text_domain' ),
				'menu_name'           => __( 'MG Slider', 'text_domain' ),
				'parent_item_colon'   => __( 'Parent Item:', 'text_domain' ),
				'all_items'           => __( 'All Items', 'text_domain' ),
				'view_item'           => __( 'View Item', 'text_domain' ),
				'add_new_item'        => __( 'Add New Item', 'text_domain' ),
				'add_new'             => __( 'Add New', 'text_domain' ),
				'edit_item'           => __( 'Edit Item', 'text_domain' ),
				'update_item'         => __( 'Update Item', 'text_domain' ),
				'search_items'        => __( 'Search Item', 'text_domain' ),
				'not_found'           => __( 'Not found', 'text_domain' ),
				'not_found_in_trash'  => __( 'Not found in Trash', 'text_domain' ),
			);
			$args = array(
				'label'               => __( 'advertise', 'text_domain' ),
				'description'         => __( 'Slider post type', 'text_domain' ),
				'labels'              => $labels,
				'supports'            => array( 'title', 'editor', 'author', 'thumbnail', 'custom-fields', ),
				'taxonomies'          => array( 'advertise_type' ),
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_nav_menus'   => true,
				'show_in_admin_bar'   => true,
				'menu_position'       => 20,
				'menu_icon'           => 'dashicons-format-gallery',
				'can_export'          => true,
				'has_archive'         => true,
				'exclude_from_search' => false,
				'publicly_queryable'  => true,
				'capability_type'     => 'post',
			);
			register_post_type( 'mgps_ps', $args);
		}

		// Hook into the 'init' action
		add_action( 'init', 'mgps_parallax_slider_post_init', 0 );



		 /*
		 *		Shortcode: [mgps-slider-post]
		 **********************************************************************/
		add_shortcode("mgps-slider-post","mg_parallax_slider_post");

		function mg_parallax_slider_post() {

				//	Activate it if user add posts as custom post
				do_action("mgps_show_slides_as_custom_post");
		}



		 /*
		 *		Shortcode: [mgps-slider-post]
		 **********************************************************************/
		add_action('wp_head','mgps_hook_js');
		function mgps_hook_js()
		{
			$output  = "<script>";
			$output .= "	jQuery(function() { ";
			$output .= "		jQuery('#da-slider').cslider();";
			$output .= "	});";
			$output .= "</script>";
			echo $output;
		}

		/*
		*
		*	Use this action hook if user add SLIDES as CUSTOM POST
		*****************************************************************/
		add_action("mgps_show_slides_as_custom_post", "mgps_show_slides_as_custom_post");
		function mgps_show_slides_as_custom_post() {
			global $mgps;
			/*
			*	Print JS
			*------------------------------------------------------------------------*/
			do_action("mgps_hook_js");

			?>
				<div id="mg-parallax-slider">
					<?php

					/*	
					*		Parallax Slider
					*
					*		Enqueue Parallax Slider JS & CSS
					***********************************************************************/
					do_action("mgps_add_parallax_slider_scripts");

					
						query_posts( 
							array ( 
									'post_type'		 	=> 	'mgps_ps',
									'posts_per_page'	=>	-1,
									'post_status'		=>	'publish',
									'orderby' 			=> 	'date',
									'order' 			=>	'DESC'
							) 
						);

					  	if ( have_posts() ) { ?>

			  	 			<div class="slider">
								<div id="da-slider" class="da-slider">

									<?php	while ( have_posts() ) : the_post(); 			?>
										<div class="da-slide">
											<h2> <?php the_title(); ?> </h2>
											<p>
												<?php 
											  			$content = get_the_content();
														echo substr($content, 0, 100);
												?>	
											</p>
											
											<a href="<?php the_permalink(); ?>" class="da-link">Read more</a>

											<div class="da-img">
												<?php 
													if(has_post_thumbnail( get_the_id() )) {
											  			the_post_thumbnail("mgps-thumb");
											  		}  else   {
											  			echo "<img src='' title='Image not found' >";
											  		}
										  	?>
										  	</div>
										</div><!-- SLIDE -->
									<?php	endwhile;  	?>

									<nav class="da-arrows">
										<span class="da-arrows-prev"></span>
										<span class="da-arrows-next"></span>
									</nav>
								</div>
				   	 	    </div>

			<?php		} else { 	?>
										<div class="slider">
											<div id="da-slider" class="da-slider">
												<div class="da-slide">
													<h2>Slides Not Found!</h2>
													<p>Please insert SLIDES to activate slider. Click
														<?php 	
																if( current_user_can('edit_posts')) : ?>
																	<a href="<?php  echo admin_url(); ?>post-new.php?post_type=mgps_ps" title="Create new MG Parallax Slide">Add New</a>
														<?php 	endif; ?>
													to create new slides. <br>
													NOTE: Use slide image height 275px for better usage.</p>
												</div>
											</div>
										</div>
			<?php
						}
						wp_reset_query();
			?>				
			</div>
			<?php
		}






/**
 *		ENQUEUE IMAGES
 *
 * 		Parallax Slider
 *********************************************************************************/
add_action( 'wp_enqueue_scripts', 'mgps_add_parallax_slider_scripts' );

function mgps_add_parallax_slider_scripts() {
	
		wp_enqueue_style( 'mgps_parallax_dynamic_css', plugins_url( '/framework/settings/style.css', __FILE__ ) );
		wp_enqueue_style( 'mgps_parallax_slider_css', plugins_url( '/css/style.css', __FILE__ ) );

		wp_enqueue_script( 'mgps_parallax_js_modernizr', plugins_url( '/js/modernizr.js', __FILE__ ) , array('jquery'), '1.0', true );
		wp_enqueue_script( 'mgps_parallax_js_cslider', plugins_url( '/js/jquery.cslider.js', __FILE__ ) , array('jquery'), '1.0', true );

}






add_shortcode("mgps-slider","mg_parallax_slider_shortcode_init");

function mg_parallax_slider_shortcode_init() {

	do_action("mgps_show_slides_as_framework");

}

 

/*
*	Use this action hook if user add SLIDES as CUSTOM POST
*****************************************************************/
add_action("mgps_show_slides_as_framework", "mgps_show_slides_as_framework");
function mgps_show_slides_as_framework() {
	global $mgps;

	/*
	*	Print JS
	*------------------------------------------------------------------------*/
	do_action("mgps_hook_js");
	?>

		<div id="mg-parallax-slider">
			<?php
				if( count($mgps['mg-slider-slides'])>=1 && $mgps['mg-slider-slides'][0]['title']!=""  ) {
					?>
					<div class="slider">
						<div id="da-slider" class="da-slider">

							<?php	
								foreach($mgps['mg-slider-slides'] as $slide) { ?>
										
								<div class="da-slide">
									<h2> <?php echo $slide['title']; ?> </h2>
									<p>
										<?php 
											   echo $slide['description'];
										?>	
									</p>
									
									<a href="<?php echo $slide['url']; ?>" class="da-link">Read more</a>

									<div class="da-img">
										<?php 
												echo "<img src='". $slide['image']. "' title='". $slide['title'] ."'>";
									  	?>
								  	</div>
								</div>
							<?php 	}		?>

							<nav class="da-arrows">
								<span class="da-arrows-prev"></span>
								<span class="da-arrows-next"></span>
							</nav>
						</div>
		   	 	    </div>

<?php	} else {	?>
								<div class="slider">
									<div id="da-slider" class="da-slider">
										<div class="da-slide">
											<h2>Slides Not Found!</h2>
											<p>Please insert SLIDES to activate slider. Click
												<?php 	
														if( current_user_can('edit_posts')) : ?>
															<a href="<?php  echo admin_url(); ?>admin.php?page=mgps_options" title="Create new MG Parallax Slide">Add New</a>
												<?php 	endif; ?>
											to create new slides. <br>
											NOTE: Use slide image height 275px for better usage.</p>
										</div>
									</div>
								</div>	
	<?php	}		?>
	</div>
<?php

}


// 	Init Theme Options framework via ReduxFramework
require_once('framework/core/framework.php');
require_once('framework/settings/mg-config.php');



?>