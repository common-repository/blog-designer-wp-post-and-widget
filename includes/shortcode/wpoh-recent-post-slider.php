<?php
/**
 * 'wpoh_recent_post_slider' Shortcode
 * 
 * @package WP Stylish Post
 * @since 1.0.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Function to handle the 'wpoh_recent_post_slider' shortcode
 * 
 * @package Blog Designer - WordPress Post and Widget
 * @since 1.0
 */
function bdwpw_recent_post_slider( $atts, $content = null ) {

    // Shortcode Parameters
	extract(shortcode_atts(array(
		'limit' 				=> '20',
		'design'                => 'design-1',
		'category' 				=> '',
		'show_author' 			=> 'true',
		'show_date' 			=> 'true',
		'show_category_name' 	=> 'true',
		'show_content' 			=> 'true',
		'content_words_limit' 	=> '20',
		'show_tags'				=> 'true',
		'slides_column' 		=> '1',
		'slides_scroll' 		=> '1',
		'dots' 					=> 'true',
		'arrows'				=> 'true',
		'autoplay' 				=> 'true',
		'autoplay_interval' 	=> '2000',
		'speed' 				=> '300',
		'show_comments'			=> 'true',
	), $atts));

	$posts_per_page 		= !empty($limit) 						? $limit 						: '20';
	$design 	            = !empty($design) 					? $design 						: 'design-1';
	$cat 					= (!empty($category))					? explode(',',$category) 		: '';
	$show_date 				= ( $show_date == 'false' ) 			? 'false'						: 'true';
	$show_category 			= ( $show_category_name == 'false' )	? 'false' 						: 'true';
	$show_content 			= ( $show_content == 'false' ) 			? 'false' 						: 'true';
	$words_limit 			= !empty( $content_words_limit ) 		? $content_words_limit	 		: 20;
	$show_tags 				= ( $show_tags == 'false' ) 			? 'false'						: 'true';
	$slides_column 			= !empty( $slides_column ) 				? $slides_column 				: 1;
	$slides_scroll 			= !empty( $slides_scroll ) 				? $slides_scroll 				: 1;
	$dots 					= ( $dots == 'false' )					? 'false' 						: 'true';
	$arrows 				= ( $arrows == 'false' )				? 'false' 						: 'true';
	$autoplay 				= ( $autoplay == 'false' )				? 'false' 						: 'true';
	$autoplay_interval 		= !empty( $autoplay_interval ) 			? $autoplay_interval 			: 2000;
	$speed 					= !empty( $speed ) 						? $speed 						: 300;
	$show_author 			= ($show_author == 'false')				? 'false'						: 'true';
	$show_comments 			= ( $show_comments == 'false' ) 		? 'false'						: 'true';
	
	// Taking some globals
	global $post;

	// Enqueue required script
	wp_enqueue_script( 'wpoh-slick-jquery' );
    wp_enqueue_script( 'bdwpw-public-js' );

	// Slider configuration
	$slider_conf = compact('slides_column', 'slides_scroll', 'dots', 'arrows', 'autoplay', 'autoplay_interval', 'speed');
	
	// Taking some variables
	$unique	= bdwpw_get_unique();

	$args = array ( 
		'post_type'     	 	=> BDWPW_POST_TYPE,
		'post_status' 			=> array('publish'),
		'orderby'        		=> 'date', 
		'order'          		=> 'DESC',
		'posts_per_page' 		=> $posts_per_page,
		'ignore_sticky_posts'	=> true,
	);
	
	// Category Parameter
	if($cat != "") {

		$args['tax_query'] = array(
								array(
									'taxonomy' 	=> BDWPW_CAT,
									'field' 	=> 'term_id',
									'terms' 	=> $cat
								));

	}

	// WP Query
	$query = new WP_Query($args);

	ob_start();

	// If post is there
	if ( $query->have_posts() ) {
?>
	<div class="wpoh-slider-wrp">
		<div id="wpoh-slider-<?php echo $unique; ?>" class="jd_wpohpost_slider wpoh-<?php echo $design; ?>">
			
			<?php while ( $query->have_posts() ) : $query->the_post();
				
				$terms 		= get_the_terms( $post->ID, BDWPW_CAT );
				$blog_links = array();
				
				if($terms) {
					foreach ( $terms as $term ) {
						$term_link 		= get_term_link( $term );
						$blog_links[] 	= '<a href="' . esc_url( $term_link ) . '">'.$term->name.'</a>';
					}
				}
				
				$cate_name 		= join( " ", $blog_links );
				$feat_image 	= bdwpw_get_post_featured_image( $post->ID );
				$terms 			= get_the_terms( $post->ID, BDWPW_CAT );

				$tags 			= get_the_tag_list( __('Tags: ','blog-designer-for-wp-post-and-widget'),', ');
				$comments 		= get_comments_number( $post->ID );
				$reply			= ($comments <= 1)  ? 'Reply' : 'Replies';
				
				// Include shortcode html file
				include( BDWPW_DIR.'/templates/slider/'."$design".'.php' );
				
			endwhile; ?>
		</div>
		<div class="wpoh-slider-conf"><?php echo json_encode( $slider_conf ); ?></div>
	</div><!-- end .wpoh-slider-wrp -->

<?php
	} // End if

	wp_reset_query(); // Reset Query
	
	$content .= ob_get_clean();
	return $content; 
}

// 'wpoh_recent_post_slider' Shortcode
add_shortcode('wpoh_recent_post_slider', 'bdwpw_recent_post_slider');