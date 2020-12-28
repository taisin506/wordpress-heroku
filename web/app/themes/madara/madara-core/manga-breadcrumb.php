<?php
	/*
	*  Manga Breadcrumb
	*/

	use App\Madara;

	$wp_query = madara_get_global_wp_query();
	$object   = $wp_query->queried_object;

	$madara_breadcrumb_bg = is_manga_archive() ? madara_output_background_options( 'manga_archive_breadcrumb_bg', '' ) : '';

	$madara_genres_block       = Madara::getOption( 'manga_archive_genres', 'on' );
	$manga_archive_genres_collapse = Madara::getOption( 'manga_archive_genres_collapse', 'on' );
	$manga_archive_genres_title    = Madara::getOption( 'manga_archive_genres_title', 'GENRES' );

	$overwrite_genres_collapse = isset( $_GET['genres_collapse'] ) && $_GET['genres_collapse'] != '' ? $_GET['genres_collapse'] : '';

	if ( $overwrite_genres_collapse != '' && $overwrite_genres_collapse == 'on' ) {
		$manga_archive_genres_collapse = $overwrite_genres_collapse;
	}

	if ( is_post_type_archive( 'wp-manga' ) || is_home() || is_front_page() ) {

		$object = null;

	} elseif ( is_manga_archive() ) {

		if(get_class($object) == 'WP_Post'){
			$obj_url = get_permalink($object);
			$obj_title = $object->post_title;
		} else {		
			$obj_title = $object->name;		
			$obj_url   = get_term_link( $object );
		}

	} elseif ( is_manga_single() || is_manga_reading_page() ) {

		$obj_title = $object->post_title;
		$obj_url   = get_the_permalink( $object->ID );

	}

	$breadcrumb_bg_html = '';
	if ( is_manga_archive() && ! is_manga_search_page() ) {
		$breadcrumb_bg_html .= 'style="';

		$breadcrumb_bg_html .= $madara_breadcrumb_bg != '' ? $madara_breadcrumb_bg : 'background-image: url(' . get_parent_theme_file_uri( '/images/bg-search.jpg' );

		$breadcrumb_bg_html .= '"';
	}
	global $wp_manga_setting, $wp_manga_functions;
	$breadcrumb_all_manga_link = $wp_manga_setting->get_manga_option( 'breadcrumb_all_manga_link', true );
	$breadcrumb_first_genre_link = $wp_manga_setting->get_manga_option( 'breadcrumb_first_genre_link', true );

	if ( ! is_page_template() || ! is_home() && ! is_front_page() ) {

		?>

        <div class="c-breadcrumb-wrapper" <?php echo wp_kses_post( $breadcrumb_bg_html ); ?>>

			<?php if ( is_manga_archive() ) { ?>
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
						<?php } ?>

                        <div class="c-breadcrumb">
                            <ol class="breadcrumb">
                                <li>
                                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
										<?php esc_html_e( 'Home', 'madara' ); ?>
                                    </a>
                                </li>
								<?php if($breadcrumb_all_manga_link){?>
                                <li>
                                    <a href="<?php echo esc_url( $wp_manga_functions->get_manga_archive_link() ); ?>">
										<?php esc_html_e( 'All Mangas', 'madara' ); ?>
                                    </a>
                                </li>
								<?php } ?>
								
								<?php
									if($breadcrumb_first_genre_link){
									$middle = madara_get_global_wp_manga()->wp_manga_breadcrumb_middle( $object );

									if ( ! empty( $middle ) ) {
										$middle = array_reverse( $middle );

										foreach ( $middle as $name => $link ) { ?>
                                            <li>
                                                <a href="<?php echo esc_url( $link ); ?>">
													<?php echo esc_html( $name ); ?>
                                                </a>
                                            </li>
										<?php }
									}
									}
								?>

								<?php if ( $object !== null && (($breadcrumb_all_manga_link && !is_manga_archive()) || !$breadcrumb_all_manga_link) ) { ?>
                                    <li>
                                        <a href="<?php echo esc_url( $obj_url ); ?>">
											<?php echo esc_html( $obj_title ); ?>
                                        </a>
                                    </li>
								<?php } ?>

								<?php if ( is_manga_reading_page() && class_exists( 'WP_MANGA' ) ) {
									$chapter_slug = get_query_var( 'chapter' );

									if ( ! empty( $chapter_slug ) ) {
										$wp_manga_functions = madara_get_global_wp_manga_functions();
										$wp_manga_chapter   = madara_get_global_wp_manga_chapter();
										$chapter_json       = $wp_manga_functions->get_chapter( get_the_ID() );

										$chapter_db = $wp_manga_chapter->get_chapter_by_slug( get_the_ID(), $chapter_slug );

										$c_name   = isset( $chapter_db['chapter_name'] ) ? $chapter_db['chapter_name'] : '';
										$c_extend = $wp_manga_functions->filter_extend_name( $chapter_db['chapter_name_extend'] );

										if ( isset( $c_name ) ) {
											?>
                                            <li class="active">
												<?php echo esc_html( $c_name . $c_extend ); ?>
                                            </li>
											<?php
										}
									}
								} ?>

                            </ol>
                        </div>

						<?php if ( is_manga_reading_page() ) { ?>
                            <div class="action-icon">
                                <ul class="action_list_icon list-inline">
                                    <li>
										<?php echo madara_get_global_wp_manga_functions()->bookmark_link_e(); ?>
                                    </li>
									<?php do_action('madara_chapter_reading_actions_list_items'); ?>
                                </ul>
                            </div>
						<?php } ?>
						<?php if ( ! is_manga_single() && ! is_manga_reading_page() && $madara_genres_block == 'on' && ! is_manga_search_page() ) {

							//genre query
							$genre_args = array(
								'taxonomy'   => 'wp-manga-genre',
								'hide_empty' => false,
							);
							$genres     = get_terms( $genre_args );
							if ( ! empty( $genres ) && ! is_wp_error( $genres ) && is_manga_archive() ) {
								?>

                                <div class="c-genres-block archive-page">
                                    <div class="genres_wrap">

                                        <div class="c-blog__heading style-3 font-heading <?php echo esc_attr($manga_archive_genres_collapse == 'on' ? 'active' : ''); ?>">
                                            <h5><?php echo esc_html( $manga_archive_genres_title ); ?></h5>
                                        </div>
                                        <a class="btn btn-genres ion-arrow-down-b float-right <?php echo esc_attr($manga_archive_genres_collapse == 'on' ? 'active' : ''); ?>"></a>
                                        <div class="genres__collapse" style="<?php echo esc_attr($manga_archive_genres_collapse == 'on' ? 'display: block' : 'display: none'); ?>">
											<?php

												if ( ! empty( $genres ) && ! is_wp_error( $genres ) ) { ?>
                                                    <div class="row genres">
                                                        <ul class="list-unstyled">
															<?php
																foreach ( $genres as $genre ) {
																	?>
                                                                    <li class="col-6 col-sm-4 col-md-2">
                                                                        <a href="<?php echo esc_url( get_term_link( $genre ) ); ?>">
																			<?php echo esc_html( $genre->name ); ?>

                                                                            <span class="count">
                                                                        (<?php echo esc_html( $genre->count ); ?>)
                                                                    </span>

                                                                        </a>
                                                                    </li>
																	<?php
																}
															?>
                                                        </ul>
                                                    </div>

												<?php } ?>
                                        </div>

                                    </div>
                                </div>
							<?php }
						}
						?>
						<?php if ( is_manga_archive() ) { ?>
                    </div>
                </div>
            </div>
		<?php } ?>
        </div>

	<?php }
