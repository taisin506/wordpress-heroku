<?php

	use App\Madara;

	$wp_query           = madara_get_global_wp_query();
	$wp_manga           = madara_get_global_wp_manga();
	$wp_manga_setting   = madara_get_global_wp_manga_setting();
	$wp_manga_functions = madara_get_global_wp_manga_functions();


	//get ready
	$thumb_size              = array( 110, 150 );
	$madara_loop_index   = get_query_var( 'madara_loop_index' );
	$madara_total_posts  = get_query_var( 'madara_post_count' );
	$madara_page_sidebar = get_query_var( 'sidebar' );

	$manga_hover_details     = Madara::getOption( 'manga_hover_details', 'off' );

	$alternative             = $wp_manga_functions->get_manga_alternative( get_the_ID() );

	$authors                 = $wp_manga_functions->get_manga_authors( get_the_ID() );
	$chapter_type = get_post_meta( get_the_ID(), '_wp_manga_chapter_type', true );

	$manga_archives_item_layout = get_query_var('manga_archives_item_layout');
	$item_columns = 3;
	
	if ( $madara_page_sidebar == 'full' ) {
		if($manga_archives_item_layout == 'default' || $manga_archives_item_layout == 'small_thumbnail'){
			$main_col_class = 'col-12 col-md-4';
		} elseif($manga_archives_item_layout == 'big_thumbnail'){
			// big thumbnail layout
			$thumb_size              = 'madara_manga_big_thumb';
			$main_col_class = 'col-6 col-md-2';
			$item_columns = 6;
		} elseif($manga_archives_item_layout == 'simple') {
			$main_col_class = 'col-12';
			$item_columns = 12;
		}
	} else {
		if($manga_archives_item_layout == 'default' || $manga_archives_item_layout == 'small_thumbnail'){
			$main_col_class = 'col-12 col-md-6';
			$item_columns = 2;
		} elseif($manga_archives_item_layout == 'big_thumbnail') {
			// big thumbnail layout
			$thumb_size              = 'madara_manga_big_thumb';
			$main_col_class = 'col-6 col-md-3';
			$item_columns = 4;
		} elseif($manga_archives_item_layout == 'simple') {
			$main_col_class = 'col-12';
			$item_columns = 12;
		}
	}

	if ( $madara_loop_index % $item_columns == 1 ) {
?>
<div class="page-listing-item">
    <div class="row row-eq-height">
		<?php
			}
		?>

        <div class="<?php echo esc_attr( $main_col_class ); ?> ">
            <div class="page-item-detail <?php echo esc_html($chapter_type);?>">
				<?php 
				if($manga_archives_item_layout == 'simple'){
					get_template_part( 'madara-core/content/content-archive-simple' );
				} else { ?>
                <div id="manga-item-<?php echo esc_attr( get_the_ID() ); ?>" class="item-thumb <?php echo esc_attr($manga_hover_details == 'off' ? '' : 'hover-details'); ?> c-image-hover" data-post-id="<?php echo get_the_ID(); ?>">
					<?php
						if ( has_post_thumbnail() ) {
							?>
                            <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
								<?php echo madara_thumbnail( $thumb_size ); ?>
                            </a>
							<?php
						}
					?>
                </div>
                <div class="item-summary">
                    <div class="post-title font-title">
                        <h5>
							<?php madara_manga_title_badges_html( get_the_ID(), 1 ); ?>
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h5>
                    </div>
                    <div class="meta-item rating">
						<?php
							$wp_manga_functions->manga_rating_display( get_the_ID() );
						?>
                    </div>
                    <div class="list-chapter">
						<?php
							$wp_manga_functions->manga_meta( get_the_ID() );
						?>
                    </div>
                </div>
				
				<?php } ?>
            </div>

        </div>
		<?php
			if ( ($madara_loop_index % $item_columns == 0 ) || ( $madara_loop_index == $madara_total_posts ) ) {
		?>
    </div>
</div>
<?php
	}

?>
