<?php
	/** Manga Reading Content - Text type **/

?>

<?php

	$wp_manga     = madara_get_global_wp_manga();
	$post_id      = get_the_ID();
	$name         = get_query_var( 'chapter' );
	$chapter_type = get_post_meta( get_the_ID(), '_wp_manga_chapter_type', true );
	if ( $name == '' ) {
		get_template_part( 404 );
		exit();
	}

	$this_chapter = madara_get_global_wp_manga_chapter()->get_chapter_by_slug( get_the_ID(), $name );

	if ( ! $this_chapter ) {
		return;
	}

	$chapter_content = new WP_Query( array(
		'post_parent' => $this_chapter['chapter_id'],
		'post_type'   => 'chapter_text_content'
	) );
	
	$server = isset($_GET['host']) ? $_GET['host'] : '';
	
	if ( $chapter_content->have_posts() ) {
		$posts = $chapter_content->posts;

		$post = $posts[0];
		?>

		<?php if ( $chapter_type == 'text' ) { ?>

			<?php do_action( 'madara_before_text_chapter_content' ); ?>

            <div class="text-left">
				<?php echo apply_filters('the_content', $post->post_content); ?>
            </div>

			<?php do_action( 'madara_after_text_chapter_content' ); ?>

		<?php } elseif ( $chapter_type == 'video' ) { ?>

			<?php do_action( 'madara_before_video_chapter_content' ); ?>

            <div class="chapter-video-frame">
				<?php $wp_manga->chapter_video_content($post, $server); ?>
            </div>

			<?php do_action( 'madara_after_video_chapter_content' ); ?>

		<?php } ?>

		<?php

	}

	wp_reset_postdata();
