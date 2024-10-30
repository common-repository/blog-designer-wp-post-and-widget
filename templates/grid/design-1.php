<?php
if($gridcol == '2') {
	$post_grid = "6";
} else if($gridcol == '3') {
	$post_grid = "4";
}  else if($gridcol == '4') {
	$post_grid = "3";
} else if ($gridcol == '1') {
	$post_grid = "12";
} else {
	$post_grid = "12";
}
?>

<div class="wpoh-post-grid wpoh-medium-<?php echo $post_grid; ?> wpoh-columns <?php echo $css_class; ?>">
	
	<div class="wpoh-post-grid-content <?php if ( !has_post_thumbnail() ) { echo 'no-thumb-image'; } ?> ">

		<?php if ( has_post_thumbnail() ) { ?>
	
			<div class="wpoh-post-image-bg">
	
				<a href="<?php the_permalink(); ?>">
	
					<img src="<?php echo $feat_image; ?>" alt="<?php the_title(); ?>" />
				</a>
			</div><!-- end .wpoh-post-image-bg -->
		<?php } ?>

		<?php if($show_category == "true") { ?>
			<div class="wpoh-post-categories">
				<?php echo $cate_name; ?>
			</div><!-- end .wpoh-post-categories -->
		<?php } ?>

		<h2 class="wpoh-post-title">
		
			<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
		</h2>

		<?php if($show_date == "true" || $show_author == 'true') { ?>
		
			<div class="wpoh-post-date">
				<?php if($show_author == 'true') { ?>
					<span>
						<?php esc_html_e( 'By', 'blog-designer-for-wp-post-and-widget' ); ?> <?php the_author(); ?>
					</span>
				<?php } ?>
		
				<?php echo ($show_author == 'true' && $show_date == 'true') ? '&nbsp;/&nbsp;' : '' ?>
		
				<?php if($show_date == "true") { echo get_the_date(); } ?>
			</div><!-- end .wpoh-post-date -->
		<?php } ?>

		<?php if($show_content == "true") { ?>
		
			<div class="wpoh-post-content">
		
				<?php if($show_full_content == "false" ) { ?>
					<div>
						<?php echo bdwpw_get_post_excerpt( $post->ID, get_the_content(), $words_limit); ?>
					</div>
					<a class="wpoh-readmorebtn" href="<?php the_permalink(); ?>"><?php _e('Read More', 'blog-designer-for-post-and-widget'); ?></a>
					<?php
				} else {
					the_content();
				} ?>
			</div><!-- end .wpoh-post-content -->
		<?php } ?>
		<?php if(!empty($tags) && $show_tags == 'true') { ?>
					
			<div class="wpoh-post-tags">
			
				<?php echo $tags;  ?>
			</div>
		<?php } ?>
		
		<?php if(!empty($comments) && $show_comments == 'true') { ?>

			<div class="wpoh-post-comments">
					
				<a href="<?php the_permalink(); ?>/#comments"><?php echo $comments.' '.$reply;  ?></a>
			</div>
		<?php } ?>
	</div><!-- end .wpoh-post-grid-content -->
</div><!-- end .wpoh-post-grid -->