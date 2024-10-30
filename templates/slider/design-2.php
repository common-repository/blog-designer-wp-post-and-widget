<div class="wpoh-post-slides">	
	<div class="wpoh-post-content-position">
		<div class="wpoh-post-details-wrapper">
		<div class="wpoh-post-content-left">	
			<?php if($show_category == "true") { ?>
				<div class="wpoh-post-categories">
					<?php echo $cate_name; ?>			
				</div> <!-- end .wpoh-post-categories -->
			<?php } ?>			
			<h2 class="wpoh-post-title">	
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</h2>			
			<?php if($show_date == "true" || $show_author == 'true')    {  ?>	
				<div class="wpoh-post-date">
					<?php if($show_author == 'true') { ?> <span><?php  esc_html_e( 'By', 'wp-stylish-post' ); ?> <?php the_author(); ?></span><?php } ?>				
					<?php echo ($show_author == 'true' && $show_date == 'true') ? '&nbsp;/&nbsp;' : '' ?>
					<?php if($show_date == "true") { echo get_the_date(); } ?>
				</div><!-- end .wpoh-post-date -->
			<?php } ?>
			<?php if(!empty($tags) && $show_tags == 'true') { ?>
				<div class="wpoh-post-tags">
					<?php echo $tags;  ?>
				</div>
			<?php } ?>
			<?php if($show_content == "true") { ?>
				
				<div class="wpoh-post-content">
					<div><?php echo bdwpw_get_post_excerpt( $post->ID, get_the_content(), $words_limit); ?></div>
					<a class="wpoh-readmorebtn" href="<?php the_permalink(); ?>"><?php _e('Read More', 'blog-designer-for-post-and-widget'); ?></a>
				</div><!-- end .wpoh-post-content -->

			<?php } ?>
			<?php if(!empty($comments) && $show_comments == 'true') { ?>
				<div class="wpoh-post-comments">
					<a href="<?php the_permalink(); ?>/#comments"><?php echo $comments.' '.$reply;  ?></a>
				</div>
			<?php } ?>
		</div>
		</div><!-- end .wpoh-post-details-wrapper -->	
		<div class="wpoh-post-image-bg">			
			<?php if( !empty($feat_image) ) { ?>			
				<a href="<?php the_permalink(); ?>">
					<img src="<?php echo $feat_image; ?>" alt="<?php the_title(); ?>" />
				</a>
			<?php } ?>
		</div><!-- end .wpoh-post-image-bg -->
	</div><!-- end .wpoh-post-content-position -->
</div><!-- end .wpoh-post-slides -->