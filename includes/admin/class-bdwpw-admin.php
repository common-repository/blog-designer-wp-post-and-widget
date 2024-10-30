<?php
/**
 * Admin Class
 *
 * Handles the Admin side functionality of plugin
 *
 * @package Blog Designer - WordPress Post and Widget
 * @since 1.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

class Bdwpw_Admin {
	function __construct() {

		add_filter('manage_edit-category_columns', array($this, 'bdwpw_manage_category_columns'));

		// Filter to add extra column to post category
		add_filter('manage_category_custom_column', array($this, 'bdwpw_cat_columns_data'), 10, 3);

		// Action to register admin menu
		add_action( 'admin_menu', array($this, 'bdwpw_register_menu'), 9 );
	}
	/**
	 * Admin Class
	 *
	 * Add extra column to post category
	 *
	 * @package Blog Designer - WordPress Post and Widget
	 * @since 1.0
	*/
	function bdwpw_manage_category_columns($columns) {

	    $new_columns['wpoh_shortcode'] = __( 'Category ID', 'blog-designer-for-wp-post-and-widget' );
	    
	    $columns = bdwpw_add_array( $columns, $new_columns, 2 );
	    
	    return $columns;
	}
	/**
	 * 
	 * Add data to extra column to post category
	 * 
	 * @package Blog Designer - WordPress Post and Widget
	 * @since 1.0
	*/
	function bdwpw_cat_columns_data($ouput, $column_name, $tax_id) {
	    
	    switch ($column_name) {
	        case 'wpoh_shortcode':
	            echo $tax_id;	          
	            break;
	    }
	    return $ouput;
	}
	/**
	 * Function to register admin menus
	 * 
	 * @package Blog Designer - WordPress Post and Widget
	 * @since 1.0.4
	 */
	function bdwpw_register_menu() {
		
		// Getting Started Page
		add_menu_page( __('Blog Designer', 'blog-designer-for-wp-post-and-widget'), __('Blog Designer', 'blog-designer-for-wp-post-and-widget'), 'manage_options', 'bdwpw-about', array($this, 'bdwpw_settings_page'), 'dashicons-edit', 6 );		
	}
	
	/**
	 * Function to display plugin design HTML
	 * 
	 * @package Blog Designer - WordPress Post and Widget
	 * @since 1.0.0
	 */
	function bdwpw_settings_page() {

		$wpoh_feed_tabs = $this->bdwpw_help_tabs();
		$active_tab 	= isset($_GET['tab']) ? $_GET['tab'] : 'how-it-work';
	?>			
		<div class="wrap bdwpw-wrap">
			<h2 class="nav-tab-wrapper">
				<?php
				foreach ($wpoh_feed_tabs as $tab_key => $tab_val) {
					$tab_name	= $tab_val['name'];
					$active_cls = ($tab_key == $active_tab) ? 'nav-tab-active' : '';
					$tab_link 	= add_query_arg( array('page' => 'bdwpw-about', 'tab' => $tab_key), admin_url('admin.php') );
				?>
				<a class="nav-tab <?php echo $active_cls; ?>" href="<?php echo $tab_link; ?>"><?php echo $tab_name; ?></a>

				<?php } ?>
			</h2>			
			<div class="bdwpw-tab-cnt-wrp">
			<?php
				if( isset($active_tab) && $active_tab == 'how-it-work' ) {
					$this->bdwpw_howitwork_page();
				}
				else if( isset($active_tab) && $active_tab == 'plugins-feed' ) {
					echo  $this->bdwpw_get_plugin_design( 'plugins-feed' );
				} else {
					echo  $this->bdwpw_get_plugin_design( 'offers-feed' );
				}
			?>
			</div><!-- end .bdwpw-tab-cnt-wrp -->
		</div><!-- end .bdwpw-wrap -->
	<?php
	}
	/**
	 * Gets the plugin design part feed
	 *
	 * @package Blog Designer - WordPress Post and Widget
	 * @since 1.0.0
	 */
	function bdwpw_get_plugin_design( $feed_type = '' ) {
		
		$active_tab = isset($_GET['tab']) ? $_GET['tab'] : '';
		
		// If tab is not set then return
		if( empty($active_tab) ) {
			return false;
		}

		// Taking some variables
		$wpoh_feed_tabs =  $this->bdwpw_help_tabs();
		$transient_key 	= isset($wpoh_feed_tabs[$active_tab]['transient_key']) 	? $wpoh_feed_tabs[$active_tab]['transient_key'] 	: 'bdwpw_' . $active_tab;
		$url 			= isset($wpoh_feed_tabs[$active_tab]['url']) 			? $wpoh_feed_tabs[$active_tab]['url'] 				: '';
		$transient_time = isset($wpoh_feed_tabs[$active_tab]['transient_time']) ? $wpoh_feed_tabs[$active_tab]['transient_time'] 	: 172800;
		$cache 			= get_transient( $transient_key );
		
		if ( false === $cache ) {
			
			$feed 			= wp_remote_get( esc_url_raw( $url ), array( 'timeout' => 120, 'sslverify' => false ) );
			$response_code 	= wp_remote_retrieve_response_code( $feed );
			
			if ( ! is_wp_error( $feed ) && $response_code == 200 ) {
				if ( isset( $feed['body'] ) && strlen( $feed['body'] ) > 0 ) {
					$cache = wp_remote_retrieve_body( $feed );
					set_transient( $transient_key, $cache, $transient_time );
				}
			} else {
				$cache = '<div class="error"><p>' . __( 'There was an error retrieving the data from the server. Please try again later.', 'blog-designer-for-wp-post-and-widget' ) . '</div>';
			}
		}
		return $cache;	
	}

	/**
	 * Function to get plugin feed tabs
	 *
	 *@package Blog Designer - WordPress Post and Widget
	 * @since 1.0.0
	 */
	function bdwpw_help_tabs() {
		$wpoh_feed_tabs = array(
							'how-it-work' 	=> array(
														'name' => __('How It Works', 'blog-designer-for-wp-post-and-widget'),
													),
						);
		return $wpoh_feed_tabs;
	}

	/**
	 * Function to get 'How It Works' HTML
	 *
	 * @package Blog Designer - WordPress Post and Widget
	 * @package Blog Designer - WordPress Post and Widget
	 * @package Blog Designer - WordPress Post and Widget
	 * @since 1.0.0
	 */
	function bdwpw_howitwork_page() { ?>
		
		<style type="text/css">
			.wpoh-pro-box .hndle{background-color:#0073AA; color:#fff;}
			.wpoh-pro-box .postbox{background:#dbf0fa none repeat scroll 0 0; border:1px solid #0073aa; color:#191e23;}
			.postbox-container .wpoh-list li:before{font-family: dashicons; content: "\f464"; font-size:20px; color: #0073aa; vertical-align: middle;}
			.bdwpw-wrap .wpoh-button-full{display:block; text-align:center; box-shadow:none; border-radius:0;}
			.bdwpw-shortcode-preview{background-color: #e7e7e7; font-weight: bold; padding: 2px 5px; display: inline-block; margin:0 0 2px 0;}
		</style>

		<div class="post-box-container">
			<div id="poststuff">
				<div id="post-body" class="metabox-holder columns-2">
				
					<!--How it workd HTML -->
					<div id="post-body-content">
						<div class="metabox-holder">
							<div class="meta-box-sortables ui-sortable">
								<div class="postbox">									
									<h3 class="hndle">
										<span><?php _e( 'How It Works - Display and Shortcode', 'blog-designer-for-wp-post-and-widget' ); ?></span>
									</h3>
									
									<div class="inside">
										<table class="form-table">
											<tbody>
												<tr>
													<th>
														<label><?php _e('Geeting Started', 'blog-designer-for-wp-post-and-widget'); ?></label>
													</th>
													<td>
														<ul>
															<li><?php _e('Step-1. Go to "Post --> Add New".', 'blog-designer-for-wp-post-and-widget'); ?></li>
															<li><?php _e('Step-2. Add post title, description and images', 'blog-designer-for-wp-post-and-widget'); ?></li>
															<li><?php _e('Step-3. Select Category and Tgas', 'blog-designer-for-wp-post-and-widget'); ?></li>
															
														</ul>
													</td>
												</tr>

												<tr>
													<th>
														<label><?php _e('How Shortcode Works', 'blog-designer-for-wp-post-and-widget'); ?></label>
													</th>
													<td>
														<ul>
															<li><?php _e('Step-1. Create a page like Blog', 'blog-designer-for-wp-post-and-widget'); ?></li>
															<li><?php _e('Step-2. Put below shortcode as per your need.', 'blog-designer-for-wp-post-and-widget'); ?></li>
														</ul>
													</td>
												</tr>

												<tr>
													<th>
														<label><?php _e('All Shortcodes', 'blog-designer-for-wp-post-and-widget'); ?></label>
													</th>
													<td>
														<span class="bdwpw-shortcode-preview">[wpoh_post]</span> – <?php _e('Blog Grid Shortcode', 'blog-designer-for-wp-post-and-widget'); ?> <br />
														<span class="bdwpw-shortcode-preview">[wpoh_recent_post_slider]</span> – <?php _e('Recent Post Slider Shortcode', 'blog-designer-for-wp-post-and-widget'); ?><br/>
													</td>
												</tr>

												<tr>
													<th>
														<label><?php _e('Need Support?', 'blog-designer-for-wp-post-and-widget'); ?></label>
													</th>
													<td>
														<p><?php _e('Check plugin document for shortcode parameters and demo for designs.', 'blog-designer-for-wp-post-and-widget'); ?></p> <br/>
														<a class="button button-primary" href="#" target="_blank"><?php _e('Documentation', 'blog-designer-for-wp-post-and-widget'); ?></a>									
														<a class="button button-primary" href="#" target="_blank"><?php _e('Demo for Designs', 'blog-designer-for-wp-post-and-widget'); ?></a>
													</td>
												</tr>
											</tbody>
										</table>
									</div><!-- .inside -->
								</div><!-- #general -->
							</div><!-- .meta-box-sortables ui-sortable -->
						</div><!-- .metabox-holder -->
					</div><!-- #post-body-content -->					
					<!--Upgrad to Pro HTML -->
				</div><!-- #post-body -->
			</div><!-- #poststuff -->
		</div><!-- #post-box-container -->
	<?php }
}
$bdwpw_admin = new Bdwpw_Admin();