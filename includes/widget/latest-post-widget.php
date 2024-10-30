<?php
/**
* Widget Class
*
* Latest Post List
*
* @package Blog Designer - WordPress Post and Widget
* @since 1.0.0
*/
function bdwpw_post_widget() {
    register_widget( 'WPost_Thumb_Widget' );
}
// Action to register widget
add_action( 'widgets_init', 'bdwpw_post_widget' );

class WPost_Thumb_Widget extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'wpoh_pro_post_thumb_widget', 'description' => __('Displayed Latest WPOH Post in list view ', 'blog-designer-for-wp-post-and-widget') );
        parent::__construct( 'wpoh_pro_post_thumb_widget', __('Latest Blog Post List', 'blog-designer-for-wp-post-and-widget'), $widget_ops);
    }

    /**
    * Handles updating settings for the current widget instance.
    *
    * @package Blog Designer - WordPress Post and Widget
    * @since 1.0
    */
    function update($new_instance, $old_instance) {
        $instance                       = $old_instance;
        $instance['title']              = $new_instance['title'];
        $instance['num_items']          = $new_instance['num_items'];
        $instance['date']               = !empty($new_instance['date']) ? 1 : 0;
        $instance['show_category']      = !empty($new_instance['show_category']) ? 1 : 0;
        $instance['category']           = intval( $new_instance['category'] );
        return $instance;
    }

    /**
    * Outputs the settings form for the widget.
    *
    * @package Blog Designer - WordPress Post and Widget
    * @since 1.0
    */
    function form($instance) {  
        $defaults = array(
            'limit'             => 5,
            'title'             => '',
            'date'              => 1, 
            'show_category'     => 1,
            'category'          => 0,
        );

        $instance   = wp_parse_args( (array) $instance, $defaults );
        $title      = isset($instance['title']) ? esc_attr($instance['title']) : '';
        $num_items  = isset($instance['num_items']) ? absint($instance['num_items']) : 5;
    ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"> 
                <?php esc_html_e( 'Title:', 'blog-designer-for-wp-post-and-widget' ); ?>
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
            </label>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('num_items'); ?>">
                <?php esc_html_e( 'Number of Items:', 'blog-designer-for-wp-post-and-widget' ); ?>
                <input class="widefat" id="<?php echo $this->get_field_id('num_items'); ?>" name="<?php echo $this->get_field_name('num_items'); ?>" type="text" value="<?php echo esc_attr($num_items); ?>" />
            </label>
        </p>

        <p>
            <input id="<?php echo $this->get_field_id( 'date' ); ?>" name="<?php echo $this->get_field_name( 'date' ); ?>" type="checkbox" value="1" <?php checked( $instance['date'], 1 ); ?> />
            <label for="<?php echo $this->get_field_id( 'date' ); ?>">
                <?php _e( 'Display Date', 'blog-designer-for-wp-post-and-widget' ); ?>
            </label>
        </p>

        <p>
            <input id="<?php echo $this->get_field_id( 'show_category' ); ?>" name="<?php echo $this->get_field_name( 'show_category' ); ?>" type="checkbox" value="1" <?php checked( $instance['show_category'], 1 ); ?> />
            <label for="<?php echo $this->get_field_id( 'show_category' ); ?>">
                <?php _e( 'Display Category', 'blog-designer-for-wp-post-and-widget' ); ?>
            </label>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id( 'category' ); ?>">
                <?php _e( 'Category:', 'blog-designer-for-wp-post-and-widget' ); ?>
            </label>
            <?php
            $dropdown_args = array( 
                                'taxonomy'          => BDWPW_CAT, 
                                'class'             => 'widefat', 
                                'show_option_all'   => __( 'All', 'blog-designer-for-wp-post-and-widget' ), 
                                'id'                => $this->get_field_id( 'category' ), 
                                'name'              => $this->get_field_name( 'category' ), 
                                'selected'          => $instance['category'] 
                            );
            wp_dropdown_categories( $dropdown_args ); 
            ?>
        </p>
        <?php
    }

    function get_other_options () {
        $args = array(
            'true'  => __( 'True', 'blog-designer-for-wp-post-and-widget' ),
            'false' => __( 'False', 'blog-designer-for-wp-post-and-widget' )
        );
        return $args;
    }

    /**
    * Outputs the content for the current widget instance.
    *
    * @package Blog Designer - WordPress Post and Widget
    * @since 1.0
    */
    function widget($blog_args, $instance) {

        extract($blog_args, EXTR_SKIP);

        $title              = empty($instance['title']) ? '' : apply_filters('widget_title', $instance['title']);
        $num_items          = empty($instance['num_items']) ? '5' : apply_filters('widget_title', $instance['num_items']);
        $date               = ( isset($instance['date']) && (1 == $instance['date']) ) ? "true" : "false";
        $show_category      = ( isset($instance['show_category']) && ($instance['show_category'] == 1) ) ? "true" : "false";
        $category           = $instance['category'];

        // Taking some globals
        global $post;

        // WP Query Parameters
        $blog_args = array(
            'posts_per_page'        => $num_items,
            'post_type'             => BDWPW_POST_TYPE,
            'order'                 => 'DESC',
            'ignore_sticky_posts'   => true,
            'suppress_filters'      => true,
        );

        if($category != 0) {
            $blog_args['tax_query'] = array(
                                        array( 
                                            'taxonomy'  => BDWPW_CAT,
                                            'field'     => 'term_id',
                                            'terms'     => $category
                                        ));
        }

        // WP Query
        $cust_loop  = new WP_Query($blog_args);

        echo $before_widget;

        if ( $title ) {
            echo $before_title . $title . $after_title;
        }

        // If Post is there
        if ($cust_loop->have_posts()) {
    ?>
        <div class="wpoh-pro-widget-wrp wpoh-clearfix">
            <div id="wpoh-pro-widget" class="wpoh-pro-sp-static jd_wpohpost_static wpoh-design-w3">

            <?php while ($cust_loop->have_posts()) : $cust_loop->the_post();
                    
                    $feat_image     = bdwpw_get_post_featured_image( $post->ID, array(80,80),true );
                    $terms          = get_the_terms( $post->ID, BDWPW_CAT );
                    $blog_links     = array();

                    if($terms) {
                        foreach ( $terms as $term ) {
                            $term_link      = get_term_link( $term );
                            $blog_links[]   = '<a href="' . esc_url( $term_link ) . '">'.$term->name.'</a>';
                        }
                    }
                    
                    $cate_name = join( " ", $blog_links );
                ?>

                <div class="wpoh-post-list">
                    <div class="wpoh-post-list-content">
                        <div class="wpoh-post-left-img">
                            <div class="wpoh-post-image-bg">
                               <a  href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                                    <?php if( !empty($feat_image) ) { ?>
                                        <img src="<?php echo $feat_image; ?>" alt="<?php the_title(); ?>" />
                                    <?php } ?>
                                </a>
                            </div>
                        </div>
                        <div class="wpoh-post-right-content">
                            <?php if($show_category == 'true') { 
                                if($cate_name !='') { ?>
                                    <div class="wpoh-post-categories">
                                        <?php echo $cate_name; ?>
                                    </div>
                                <?php } 
                            } ?>

                            <div class="wpoh-post-title">
                                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
                            </div>

                            <?php if($date == "true") { ?>
                                <div class="wpoh-post-date">
                                    <?php echo get_the_date(); ?>
                                </div>
                            <?php }?>
                        </div>
                    </div>
                </div>
        <?php endwhile; ?>
            </div>
        </div>
    <?php
        } // End if

        echo $after_widget;

        wp_reset_query(); // Reset WP Query
    }
}