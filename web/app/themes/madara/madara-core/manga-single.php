<?php

	/** Single template of Manga **/

	get_header();

	use App\Madara;

	$wp_manga           = madara_get_global_wp_manga();
	$wp_manga_functions = madara_get_global_wp_manga_functions();
	$thumb_size         = array( 193, 278 );
	$post_id            = get_the_ID();

	$alternative = $wp_manga_functions->get_manga_alternative( $post_id );
	$rank        = $wp_manga_functions->get_manga_rank( $post_id );
	$views       = $wp_manga_functions->get_manga_monthly_views( $post_id );
	$authors     = $wp_manga_functions->get_manga_authors( $post_id );
	$rate        = $wp_manga_functions->get_total_review( $post_id );
	$vote        = $wp_manga_functions->get_total_vote( $post_id );
	$artists     = $wp_manga_functions->get_manga_artists( $post_id );
	$genres      = $wp_manga_functions->get_manga_genres( $post_id );

	$madara_single_sidebar      = madara_get_theme_sidebar_setting();
	$madara_breadcrumb          = Madara::getOption( 'manga_single_breadcrumb', 'on' );
	$manga_profile_background   = madara_output_background_options( 'manga_profile_background' );
	$chapters_order             = Madara::getOption( 'manga_chapters_order', 'desc' );
	$manga_single_summary       = Madara::getOption( 'manga_single_summary', 'on' );
	$manga_single_chapters_list = Madara::getOption( 'manga_single_chapters_list', 'on' );

	$wp_manga_settings = get_option( 'wp_manga_settings' );
	$related_manga     = isset( $wp_manga_settings['related_manga'] ) ? $wp_manga_settings['related_manga'] : null;

?>


<?php do_action( 'before_manga_single' ); ?>
<div class="profile-manga" style="<?php echo esc_attr( $manga_profile_background != '' ? $manga_profile_background : 'background-image: url(' . get_parent_theme_file_uri( '/images/bg-search.jpg' ) . ');' ); ?>">
    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-12 col-md-12">
				<?php
					if ( $madara_breadcrumb == 'on' ) {
						get_template_part( 'madara-core/manga', 'breadcrumb' );
					}
				?>
                <div class="post-title">
                    <h3>
						<?php madara_manga_title_badges_html( get_the_ID(), 1 ); ?>

						<?php echo esc_html( get_the_title() ); ?>
                    </h3>
                </div>
                <div class="tab-summary <?php echo has_post_thumbnail() ? '' : esc_attr( 'no-thumb' ); ?>">

					<?php if ( has_post_thumbnail() ) { ?>
                        <div class="summary_image">
                            <a href="<?php echo get_the_permalink(); ?>">
								<?php echo madara_thumbnail( $thumb_size ); ?>
                            </a>
                        </div>
					<?php } ?>
                    <div class="summary_content_wrap">
                        <div class="summary_content">
                            <div class="post-content">
								<?php get_template_part( 'html/ajax-loading/ball-pulse' ); ?>
                                <div class="post-rating">
									<?php
										$wp_manga_functions->manga_rating_display( get_the_ID(), true );
									?>
                                </div>
                                <div class="post-content_item">
                                    <div class="summary-heading">
                                        <h5><?php echo esc_attr__( 'Rating', 'madara' ); ?></h5>
                                    </div>
                                    <div class="summary-content vote-details">
										<?php echo sprintf( _n( 'Average %1s / %2s out of %3s total vote.', 'Average %1s / %2s out of %3s total votes.', $vote, 'madara' ), $rate, '5', $vote ); ?>
                                    </div>
                                </div>
                                <div class="post-content_item">
                                    <div class="summary-heading">
                                        <h5>
											<?php echo esc_attr__( 'Rank', 'madara' ); ?>
                                        </h5>
                                    </div>
                                    <div class="summary-content">
										<?php echo sprintf( _n( ' %1s, it has %2s monthly view', ' %1s, it has %2s monthly views', $views, 'madara' ), $rank, $views ); ?>
                                    </div>
                                </div>
								
								<?php
								
								if($alternative != '') {?>

                                <div class="post-content_item">
                                    <div class="summary-heading">
                                        <h5>
											<?php echo esc_attr__( 'Alternative', 'madara' ); ?>
                                        </h5>
                                    </div>
                                    <div class="summary-content">
										<?php echo wp_kses_post( $alternative ); ?>
                                    </div>
                                </div>
								
								<?php } ?>
								
								<?php
								
								if($authors != '') {?>
                                <div class="post-content_item">
                                    <div class="summary-heading">
                                        <h5>
											<?php echo esc_attr__( 'Author(s)', 'madara' ); ?>
                                        </h5>
                                    </div>
                                    <div class="summary-content">
                                        <div class="author-content">
											<?php echo wp_kses_post( $authors ); ?>
                                        </div>
                                    </div>
                                </div>
								<?php }?>

								<?php
								
								if($artists != '') {?>
                                <div class="post-content_item">
                                    <div class="summary-heading">
                                        <h5>
											<?php echo esc_attr__( 'Artist(s)', 'madara' ); ?>
                                        </h5>
                                    </div>
                                    <div class="summary-content">
                                        <div class="artist-content">
											<?php echo wp_kses_post( $artists ); ?>
                                        </div>
                                    </div>
                                </div>
								
								<?php } ?>

								<?php
								
								if($genres != '') {?>
                                <div class="post-content_item">
                                    <div class="summary-heading">
                                        <h5>
											<?php echo esc_attr__( 'Genre(s)', 'madara' ); ?>
                                        </h5>
                                    </div>
                                    <div class="summary-content">
                                        <div class="genres-content">
											<?php echo wp_kses_post( $genres ); ?>
                                        </div>
                                    </div>
                                </div>
								
								<?php } ?>
								
								

								<?php $type = $wp_manga_functions->get_manga_type( get_the_ID() ); ?>
								
								<?php
								
								if($type != '') {?>
                                <div class="post-content_item">
                                    <div class="summary-heading">
                                        <h5>
											<?php echo esc_attr__( 'Type', 'madara' ); ?>
                                        </h5>
                                    </div>
                                    <div class="summary-content">
										<?php echo wp_kses_post( $type ); ?>
                                    </div>
                                </div>
								<?php } ?>
								
								<?php do_action('wp-manga-after-manga-properties', get_the_ID());?>
                            </div>
                            <div class="post-status">
                                <div class="post-content_item">
                                    <div class="summary-heading">
                                        <h5>
											<?php echo esc_attr__( 'Release', 'madara' ); ?>
                                        </h5>
                                    </div>
                                    <div class="summary-content">
										<?php
											echo wp_kses_post( $wp_manga_functions->get_manga_release( get_the_ID() ) );
										?>
                                    </div>
                                </div>

                                <div class="post-content_item">
                                    <div class="summary-heading">
                                        <h5>
											<?php echo esc_attr__( 'Status', 'madara' ); ?>
                                        </h5>
                                    </div>
                                    <div class="summary-content">
										<?php
											echo wp_kses_post( $wp_manga_functions->get_manga_status( get_the_ID() ) );
										?>
                                    </div>
                                </div>

                                <div class="manga-action">
                                    <div class="count-comment">
                                        <div class="action_icon">
                                            <a href="#manga-discission"><i class="ion-chatbubble-working"></i></a>
                                        </div>
                                        <div class="action_detail">
											<?php $comments_count = wp_count_comments( get_the_ID() ); ?>
                                            <span><?php printf( _n( '%s comment', '%s comments', $comments_count->approved, 'madara' ), $comments_count->approved ); ?></span>
                                        </div>
                                    </div>
                                    <div class="add-bookmark">
										<?php
											$wp_manga_functions->bookmark_link_e();
										?>
                                    </div>
									<?php do_action( 'madara_single_manga_action' ); ?>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="c-page-content style-1">
    <div class="content-area">
        <div class="container">
            <div class="row <?php echo esc_attr( $madara_single_sidebar == 'left' ? 'sidebar-left' : '' ) ?>">
                <div class="main-col <?php echo esc_attr( $madara_single_sidebar !== 'full' && ( is_active_sidebar( 'manga_single_sidebar' ) || is_active_sidebar( 'main_sidebar' ) ) ? ' col-md-8 col-sm-8' : 'col-md-12 col-sm-12 sidebar-hidden' ) ?>">
                    <!-- container & no-sidebar-->
                    <div class="main-col-inner">
                        <div class="c-page">
                            <!-- <div class="c-page__inner"> -->
                            <div class="c-page__content">

								<?php if ( get_the_content() != '' ) { ?>

                                    <div class="c-blog__heading style-2 font-heading">

                                        <h4>
                                            <i class="<?php madara_default_heading_icon(); ?>"></i>
											<?php echo esc_attr__( 'Summary', 'madara' ); ?>
                                        </h4>
                                    </div>

                                    <div class="description-summary">

                                        <div class="summary__content <?php echo( esc_attr($manga_single_summary == 'on' ? 'show-more' : '' )); ?>">
											<?php the_content(); ?>
                                        </div>

										<?php if ( $manga_single_summary == 'on' ) { ?>
                                            <div class="c-content-readmore">
                                                <span class="btn btn-link content-readmore">
                                                    <?php echo esc_html__( 'Show more  ', 'madara' ); ?>
                                                </span>
                                            </div>
										<?php } ?>

                                    </div>

								<?php } ?>

                                <div class="c-blog__heading style-2 font-heading">

                                    <h4>
                                        <i class="<?php madara_default_heading_icon(); ?>"></i>
										<?php echo esc_attr__( 'LATEST MANGA RELEASES', 'madara' ); ?>
                                    </h4>
                                </div>
                                <div class="page-content-listing single-page">
                                    <div class="listing-chapters_wrap <?php echo( esc_attr($manga_single_chapters_list == 'on' ? 'show-more' : '' )); ?>">

										<?php 
										
										$manga_id = get_the_ID();
										$manga = $wp_manga_functions->get_all_chapters( $manga_id ); ?>

										<?php if ( $manga ) : ?>

											<?php do_action( 'madara_before_chapter_listing' ) ?>

                                            <ul class="main version-chap">
												<?php
													$single = isset( $manga['0']['chapters'] ) ? $manga['0']['chapters'] : null;

													if ( $single ) { ?>

														<?php foreach ( $single as $chapter ) {

															$style     = $wp_manga_functions->get_reading_style();
															$link      = $wp_manga_functions->build_chapter_url( get_the_ID(), $chapter, $style );
															$time_diff = $wp_manga_functions->get_time_diff( $chapter['date'] );
															$time_diff = apply_filters( 'madara_archive_chapter_date', '<i>' . $time_diff . '</i>', $chapter['chapter_id'], $chapter['date'], $link );

															?>

                                                            <li class="wp-manga-chapter">
                                                                <a href="<?php echo esc_url( $link ); ?>">
																	<?php echo isset( $chapter['chapter_name'] ) ? esc_attr( $chapter['chapter_name'] . $wp_manga_functions->filter_extend_name( $chapter['chapter_name_extend'] ) ) : ''; ?>
                                                                </a>

																<?php if ( $time_diff ) { ?>
                                                                    <span class="chapter-release-date">
																		<?php echo wp_kses_post( $time_diff ); ?>
                                                                    </span>
																<?php } ?>
																
																<?php do_action('wp_manga_after_chapter_name',$chapter, $manga_id);?>

                                                            </li>

														<?php } //endforeach ?>

														<?php unset( $manga['0'] );
													}//endif;
												?>

												<?php

													if ( ! empty( $manga ) ) {

														if ( $chapters_order == 'desc' ) {
															$manga = array_reverse( $manga );
														}

														foreach ( $manga as $vol_id => $vol ) {

															$chapters = isset( $vol['chapters'] ) ? $vol['chapters'] : null;

															$chapters_parent_class = $chapters ? 'parent has-child' : 'parent no-child';
															$chapters_child_class  = $chapters ? 'has-child' : 'no-child';
															$first_volume_class    = isset( $first_volume ) ? '' : ' active';
															?>

                                                            <li class="<?php echo esc_attr( $chapters_parent_class . ' ' . $first_volume_class ); ?>">

																<?php echo isset( $vol['volume_name'] ) ? '<a href="javascript:void(0)" class="' . $chapters_child_class . '">' . $vol['volume_name'] . '</a>' : ''; ?>
																<?php

																	if ( $chapters ) { ?>
                                                                        <ul class="sub-chap list-chap" <?php echo isset( $first_volume ) ? '' : ' style="display: block;"'; ?> >

																			<?php if ( $chapters_order == 'desc' ) {
																				$chapters = array_reverse( $chapters );
																			} ?>

																			<?php 
																			
																			$manga_id = get_the_ID();
																			
																			foreach ( $chapters as $chapter ) {

																				$style = $wp_manga_functions->get_reading_style();

																				$link          = $wp_manga_functions->build_chapter_url( $manga_id, $chapter, $style );
																				$c_extend_name = madara_get_global_wp_manga_functions()->filter_extend_name( $chapter['chapter_name_extend'] );
																				$time_diff     = $wp_manga_functions->get_time_diff( $chapter['date'] );
																				$time_diff     = apply_filters( 'madara_archive_chapter_date', '<i>' . $time_diff . '</i>', $chapter['chapter_id'], $chapter['date'], $link );

																				?>

                                                                                <li class="wp-manga-chapter">
                                                                                    <a href="<?php echo esc_url( $link ); ?>">
																						<?php echo esc_attr( $chapter['chapter_name'] . $c_extend_name ) ?>
                                                                                    </a>

																					<?php if ( $time_diff ) { ?>
                                                                                        <span class="chapter-release-date">
                                                                                            <?php echo wp_kses_post( $time_diff ); ?>
                                                                                        </span>
																					<?php } ?>
																					
																					<?php do_action('wp_manga_after_chapter_name',$chapter, $manga_id);?>

                                                                                </li>

																			<?php } ?>
                                                                        </ul>
																	<?php } else { ?>

                                                                        <span class="no-chapter"><?php echo esc_html__( 'There is no chapters', 'madara' ); ?></span>
																	<?php } ?>
                                                            </li>
															<?php $first_volume = false; ?>

														<?php } //endforeach; ?>

													<?php } //endif-empty( $volume);
												?>
                                            </ul>

											<?php do_action( 'madara_after_chapter_listing' ) ?>

										<?php else : ?>

											<?php echo esc_html__( 'Manga has no chapter yet.', 'madara' ); ?>

										<?php endif; ?>

										<?php if ( $manga_single_chapters_list == 'on' ) { ?>
                                            <div class="c-chapter-readmore">
                                                <span class="btn btn-link chapter-readmore">
                                                    <?php echo esc_html__( 'Show more ', 'madara' ); ?>
                                                </span>
                                            </div>
										<?php } ?>

                                    </div>
                                </div>
                            </div>
                            <!-- </div> -->
                        </div>
                        <!-- comments-area -->
						<?php do_action( 'wp_manga_discussion' ); ?>
                        <!-- END comments-area -->

						<?php

							if ( $related_manga == 1 ) {
								get_template_part( '/madara-core/manga', 'related' );
							}

							if ( class_exists( 'WP_Manga' ) ) {
								$GLOBALS['wp_manga']->wp_manga_get_tags();
							}
						?>

                    </div>
                </div>

				<?php
					if ( $madara_single_sidebar != 'full' && ( is_active_sidebar( 'main_sidebar' ) || is_active_sidebar( 'manga_single_sidebar' ) ) ) {
						?>
                        <div class="sidebar-col col-md-4 col-sm-4">
							<?php get_sidebar(); ?>
                        </div>
					<?php }
				?>

            </div>
        </div>
    </div>
</div>

<?php do_action( 'after_manga_single' ); ?><?php get_footer(); ?>
