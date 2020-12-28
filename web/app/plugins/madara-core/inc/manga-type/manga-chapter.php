<?php

	/**
	 * Text Chapter for WP Manga
	 **/

	class WP_MANGA_CHAPTER {

		function __construct() {

		}

		/**
		 * Parse Manga Nav for Manga Chapter
		 */

		function manga_nav( $args ) {

			global $wp_manga_functions, $wp_manga_template, $wp_manga_chapter, $wp_manga, $wp_manga_setting;

			extract( $args );

			if( !empty( $_GET['style'] ) ){
				$style = $_GET['style'];
			}else{
				$style = get_post_meta( get_the_ID(), 'manga_reading_style', true );
				if( empty( $style ) || $style !== 'list' ){
					$style = 'paged';
				}
			}

			$single_chap = $wp_manga_functions->get_single_chapter( get_the_ID(), $chapter['chapter_id'] );
			$inUse       = $single_chap['storage']['inUse'];

			$hosting_selection = $wp_manga_setting->get_manga_option( 'hosting_selection', true );

			$hosting_anonymous_name = $wp_manga_setting->get_manga_option( 'hosting_anonymous_name', true );

			$s_host            = isset( $_GET['host'] ) && $hosting_selection ? $_GET['host'] : null;

			global $wp_manga_volume, $wp_manga_storage;
			$all_vols = $wp_manga_volume->get_manga_volumes( get_the_ID() );
			$cur_vol  = get_query_var( 'volume' );

			$using_ajax = function_exists( 'madara_page_reading_ajax' ) && madara_page_reading_ajax();

			?>
            <div class="wp-manga-nav">
                <div class="select-view">

					<?php
						if ( $hosting_selection ) { ?>
                            <!-- select host -->
                            <div class="c-selectpicker selectpicker_version">
                                <label>
									<?php
										$host_arr = $wp_manga_functions->get_chapter_hosts( get_the_ID(), $chapter['chapter_id'] );
									?>
                                    <select class="selectpicker host-select">
										<?php

											if ( $s_host ) {
												$inUse = $s_host;
											}

											$idx = 1;

											foreach ( $host_arr as $h ) {
												$host_link = $wp_manga_functions->build_chapter_url( get_the_ID(), $cur_chap, $style, $h );
												?>
                                                <option class="short" data-limit="40" value="<?php echo $h ?>" data-redirect="<?php echo esc_url( $host_link ); ?>" <?php selected( $h, $inUse, true ) ?>><?php echo !$hosting_anonymous_name ? sprintf(__("Host: %s",WP_MANGA_TEXTDOMAIN),$h) : sprintf(__("Server %s", WP_MANGA_TEXTDOMAIN), $idx); ?></option>
											<?php
												$idx++;
											}
										?>
                                    </select> </label>
                            </div>
						<?php }
					?>

                    <!-- select volume -->
					<?php

						if ( ! empty( $all_vols ) ) {
							$all_vols = array_reverse( $all_vols );
							?>
                            <div class="c-selectpicker selectpicker_volume">
                                <label> <select class="selectpicker volume-select">
										<?php foreach ( $all_vols as $vol ) { ?><?php
											$vol_slug = $wp_manga_storage->slugify( $vol['volume_name'] );
											if ( $vol_slug == $cur_vol ) {
												$cur_vol_id = $vol['volume_id'];
											}
											?>
                                            <option class="short" data-limit="40" value="<?php echo $vol['volume_id'] ?>" <?php selected( $vol_slug, $cur_vol, true ) ?>>
												<?php echo esc_attr( $vol['volume_name'] ); ?>
                                            </option>
										<?php } ?>
                                    </select> </label>
                            </div>
							<?php
						}
					?>

                    <!-- select chapter -->
                    <div class="chapter-selection">
						<?php
							if ( ! in_array( $chapter['volume_id'], array_column( $all_vols, 'volume_id' ) ) ) {
								array_push( $all_vols, array(
									'volume_id' => $chapter['volume_id']
								) );
							}

							$this_vol_all_chaps = $all_chaps;
							$cur_vol_index      = null;
							$prev_vol_all_chaps = null;
							$next_vol_all_chaps = null;
						?>
						<?php foreach ( $all_vols as $index => $vol ) { ?><?php

							if ( $vol['volume_id'] == $chapter['volume_id'] ) {
								if ( $index !== 0 ) {
									// If this is current volume, then the old $all_chaps will be $prev_vol_all_chaps
									$prev_vol_all_chaps = $all_chaps;
								}

								$all_chaps     = $this_vol_all_chaps;
								$cur_vol_index = $index;
							} else {

								$all_chaps = $wp_manga_volume->get_volume_chapters( get_the_ID(), $vol['volume_id'], 'name', 'asc' );

								// Get next all chaps of next volume
								if ( $cur_vol_index !== null && $index == ( $cur_vol_index + 1 ) ) {
									$next_vol_all_chaps = $all_chaps;
								}
							}

							if ( empty( $all_chaps ) ) {
								continue;
							}

							$is_current_vol = $chapter['volume_id'] == $vol['volume_id'] ? true : false;
							?>
                            <div class="c-selectpicker selectpicker_chapter" for="volume-id-<?php echo esc_attr( $vol['volume_id'] ); ?>" <?php echo ! $is_current_vol ? 'style="display:none;"' : ''; ?> >
                                <label> <select class="selectpicker single-chapter-select">

										<?php if ( ! $is_current_vol ) { ?>
                                            <option><?php esc_html_e( 'Select Chapter', WP_MANGA_TEXTDOMAIN ); ?></option>
										<?php } ?>

										<?php
											foreach ( $all_chaps as $chap ) {
												$link            = $wp_manga_functions->build_chapter_url( get_the_ID(), $chap, $style );
												$data_navigation = $using_ajax ? $this->chapter_navigate_ajax_params( get_the_ID(), $chap['chapter_slug'], 1 ) : '';
												?>
                                                <option class="short" data-limit="40" value="<?php echo $chap['chapter_slug'] ?>" data-redirect="<?php echo esc_url( $link ) ?>" data-navigation="<?php echo $data_navigation; ?>" <?php selected( $chap['chapter_slug'], $cur_chap, true ) ?>>
													<?php echo esc_attr( $chap['chapter_name'] . $wp_manga_functions->filter_extend_name( $chap['chapter_name_extend'] ) ); ?>
                                                </option>
											<?php }
										?>
                                    </select> </label>
                            </div>
						<?php } ?>
                    </div>

                    <!-- select page style -->
                    <div class="c-selectpicker selectpicker_load">
						<?php
							$list_link = $wp_manga_functions->build_chapter_url( get_the_ID(), $cur_chap, 'list', $s_host );

							$paged_link = $wp_manga_functions->build_chapter_url( get_the_ID(), $cur_chap, 'paged', $s_host );
						?>
                        <label> <select class="selectpicker reading-style-select">
                                <option data-redirect="<?php echo esc_url( $list_link ); ?>" <?php selected( 'list', $style ); ?>><?php esc_html_e( 'List style', WP_MANGA_TEXTDOMAIN ); ?></option>
                                <option data-redirect="<?php echo esc_url( $paged_link ); ?>" <?php selected( 'paged', $style ); ?>><?php esc_html_e( 'Paged style', WP_MANGA_TEXTDOMAIN ); ?></option>
                            </select> </label>
                    </div>

                </div>
				<?php
					if ( 'paged' == $style ) {
						$current_page = isset( $_GET[$wp_manga->manga_paged_var] ) ? $_GET[$wp_manga->manga_paged_var] : 1;
						$total_page   = isset( $single_chap['total_page'] ) ? $single_chap['total_page'] : '';
						$this->manga_pager( $current_page, $single_chap['total_page'], $style, $this_vol_all_chaps, $prev_vol_all_chaps, $next_vol_all_chaps );
					} elseif ( $style == 'list' ) {
						$this->manga_list_navigation( $cur_chap, $this_vol_all_chaps, $prev_vol_all_chaps, $next_vol_all_chaps );
					}
				?>
            </div>

			<?php
		}

		function manga_list_navigation( $cur_chap, $all_chaps, $prev_vol_all_chaps, $next_vol_all_chaps ) {

			global $wp_manga_functions;

			$page_style = 'list';

			$cur_chap_index = array_search( $cur_chap, array_column( $all_chaps, 'chapter_slug' ) );

			if ( isset( $all_chaps[ $cur_chap_index - 1 ] ) ) {
				$prev_chap = $all_chaps[ $cur_chap_index - 1 ];
			} else if ( ! empty( $prev_vol_all_chaps ) ) {
				$prev_chap = $prev_vol_all_chaps[ count( $prev_vol_all_chaps ) - 1 ];
			} else {
				$prev_chap = null;
			}

			if ( isset( $all_chaps[ $cur_chap_index + 1 ] ) ) {
				$next_chap = $all_chaps[ $cur_chap_index + 1 ];
			} elseif ( ! empty( $next_vol_all_chaps ) ) {
				$next_chap = $next_vol_all_chaps[ key( $next_vol_all_chaps ) ];
			} else {
				$next_chap = null;
			}

			?>
            <div class="select-pagination">
                <div class="nav-links">
					<?php if ( $prev_chap ): ?><?php $prev_link = $wp_manga_functions->build_chapter_url( get_the_ID(), $prev_chap['chapter_slug'], $page_style ); ?>
                        <div class="nav-previous">
                            <a href="<?php echo $prev_link; ?>" class="btn prev_page">
								<?php esc_html_e( 'Prev', WP_MANGA_TEXTDOMAIN ); ?>
                            </a>
                        </div>
					<?php endif ?>
					<?php if ( $next_chap ): ?><?php $next_link = $wp_manga_functions->build_chapter_url( get_the_ID(), $next_chap['chapter_slug'], $page_style ); ?>
                        <div class="nav-next">
                            <a href="<?php echo $next_link ?>" class="btn next_page">
								<?php esc_html_e( 'Next', WP_MANGA_TEXTDOMAIN ); ?>
                            </a>
                        </div>
					<?php endif ?>
                </div>
            </div>
			<?php

			//put prev and next link to global variable, so other function from other place can get it
			if ( ! empty( $next_link ) ) {
				$GLOBALS['madara_next_page_link'] = $next_link;
			}
			if ( ! empty( $prev_link ) ) {
				$GLOBALS['madara_prev_page_link'] = $prev_link;
			}

		}

		function manga_pager(
			$cur_page,
			$total_page,
			$style,
			$all_chaps,
			$prev_vol_all_chaps = null,
			$next_vol_all_chaps = null
		) {

			global $wp_manga_functions, $wp_manga;

			$cur_host   = isset( $_GET['host'] ) ? $_GET['host'] : null;
			$cur_chap   = get_query_var( 'chapter' );
			$link       = $wp_manga_functions->build_chapter_url( get_the_ID(), $cur_chap, $style, $cur_host );
			$using_ajax = function_exists( 'madara_page_reading_ajax' ) && madara_page_reading_ajax();

			//get prev and next chap url
			$cur_chap_index = array_search( $cur_chap, array_column( $all_chaps, 'chapter_slug' ) );

			// Next Chap
			if ( isset( $all_chaps[ $cur_chap_index + 1 ] ) ) {
				$next_chap = $all_chaps[ $cur_chap_index + 1 ];
			} else if ( ! empty( $next_vol_all_chaps ) && is_array( $next_vol_all_chaps ) ) {
				$next_chap = $next_vol_all_chaps[ key( $next_vol_all_chaps ) ];
			}

			if ( ! empty( $next_chap ) ) {
				$next_chap_link = $wp_manga_functions->build_chapter_url( get_the_ID(), $next_chap['chapter_slug'], $style, $cur_host );
			}

			// Prev Chap
			if ( isset( $all_chaps[ $cur_chap_index - 1 ] ) ) { // If there is prev chap in current volume
				$prev_chap = $all_chaps[ $cur_chap_index - 1 ];
			} elseif ( ! empty( $prev_vol_all_chaps ) && is_array( $prev_vol_all_chaps ) ) { // or get the latest chap of prev volume
				$prev_chap = $prev_vol_all_chaps[ count( $prev_vol_all_chaps ) - 1 ];
			}

			if ( ! empty( $prev_chap ) ) {
				$prev_chap_link = $wp_manga_functions->build_chapter_url( get_the_ID(), $prev_chap['chapter_slug'], $style, $cur_host );

				// use for get the last page of previous chapter
				$prev_chap_data = $wp_manga_functions->get_single_chapter( get_the_ID(), $prev_chap['chapter_id'] );

				if ( ! empty( $prev_chap_data ) ) {
					$prev_chap_last_page = madara_actual_total_pages( $prev_chap_data['total_page'] );
				}
			}

			$prev_page = intval( $cur_page ) - 1;
			if ( $prev_page != 0 ) {
				$prev_link = add_query_arg( array( $wp_manga->manga_paged_var => $prev_page ), $link );

				if ( $using_ajax ) {
					$prev_ajax_params = $this->chapter_navigate_ajax_params( get_the_ID(), $cur_chap, $prev_page );
				}
			} else {
				if ( isset( $prev_chap_link ) && isset( $prev_chap ) ) {
					$prev_link = add_query_arg( array( $wp_manga->manga_paged_var => $prev_chap_last_page ), $prev_chap_link );
				}

				if ( $using_ajax && ! empty( $prev_chap ) ) {
					$prev_ajax_params = $this->chapter_navigate_ajax_params( get_the_ID(), $prev_chap['chapter_slug'], $prev_chap_last_page );
				}
			}

			$total_page = madara_actual_total_pages( $total_page );

			$next_page = intval( $cur_page ) + 1;

			if ( intval( $next_page ) <= intval( $total_page ) ) {

				$next_link = add_query_arg( array( $wp_manga->manga_paged_var => $next_page ), $link );

				if ( $using_ajax ) {
					$next_ajax_params = $this->chapter_navigate_ajax_params( get_the_ID(), $cur_chap, $next_page );
				}
			} else {
				if ( isset( $next_chap_link ) ) {
					$next_link = $next_chap_link;
				}

				if ( $using_ajax && ! empty( $next_chap ) ) {
					$next_ajax_params = $this->chapter_navigate_ajax_params( get_the_ID(), $next_chap['chapter_slug'], 1 );
				}
			}

			if ( $using_ajax ) {

				$params = array(
					'chapter' => $cur_chap,
					'postID'  => get_the_ID()
				);

				if ( isset( $prev_chap_link ) ) {
					$params['prev_chap_url'] = $prev_chap_link;
				}

				if ( isset( $next_chap_link ) ) {
					$params['next_chap_url'] = $next_chap_link;
				}
			}

			wp_localize_script( 'wp-manga', 'mangaReadingAjax', array( 'isEnable' => $using_ajax ) );

			?>

			<?php if ( ! empty( $prev_chap_last_page ) ) { //for preload navigation ?>
                <script type="text/javascript">
					var prevChapLastPage = <?php echo intval( $prev_chap_last_page ); ?>;
                </script>
			<?php } ?>

            <div class="select-pagination">
                <div class="c-selectpicker selectpicker_page">
                    <label>

                        <select id="single-pager" class="selectpicker">
							<?php for ( $i = 1; $i <= intval( $total_page ); $i ++ ) { ?>

								<?php
								$data_redirect   = 'data-redirect="' . add_query_arg( array( $wp_manga->manga_paged_var => $i ), $link ) . '"';
								$data_navigation = $using_ajax ? 'data-navigation="' . $this->chapter_navigate_ajax_params( get_the_ID(), $cur_chap, $i ) . '"' : '';
								?>
                                <option value="<?php echo $i ?>" <?php echo $data_redirect; ?> <?php echo $data_navigation; ?> <?php selected( $i, $cur_page, true ) ?>>
									<?php echo $i . '/' . $total_page; ?>
                                </option>
							<?php } ?>
                        </select>

                    </label>
                </div>
                <div class="nav-links">

                    <i class="mobile-nav-btn ion-navicon"></i>

					<?php if ( ! empty( $prev_link ) ): ?>
                        <div class="nav-previous">
                            <a href="<?php echo $prev_link; ?>" class="btn prev_page" <?php echo isset( $prev_ajax_params ) ? 'data-navigation="' . esc_attr( $prev_ajax_params ) . '"' : ''; ?>>
								<?php esc_html_e( 'Prev', 'madara' ); ?>
                            </a>
                        </div>
					<?php else : ?>
                        <div class="nav-previous">
                            <a href="" style="display: none;" class="btn prev_page" <?php echo isset( $prev_ajax_params ) ? 'data-navigation="' . esc_attr( $prev_ajax_params ) . '"' : ''; ?>>
								<?php esc_html_e( 'Prev', 'madara' ); ?>
                            </a>
                        </div>
					<?php endif ?>

					<?php if ( ! empty( $next_link ) ): ?>
                        <div class="nav-next">
                            <a href="<?php echo $next_link ?>" class="btn next_page" data-navigation="<?php echo isset( $next_ajax_params ) ? esc_attr( $next_ajax_params ) : ''; ?>">
								<?php esc_html_e( 'Next', 'madara' ); ?>
                            </a>
                        </div>
					<?php else : ?>
                        <div class="nav-next">
                            <a href="" style="display: none;" class="btn next_page" data-navigation="<?php echo isset( $next_ajax_params ) ? esc_attr( $next_ajax_params ) : ''; ?>">
								<?php esc_html_e( 'Next', 'madara' ); ?>
                            </a>
                        </div>
					<?php endif ?>
                </div>
            </div>
			<?php

			//put prev and next link to global variable, so other function from other place can get it
			if ( ! empty( $next_link ) ) {
				$GLOBALS['madara_next_page_link'] = $next_link;
			}
			if ( ! empty( $prev_link ) ) {
				$GLOBALS['madara_prev_page_link'] = $prev_link;
			}

		}

		function chapter_navigate_ajax_params( $post_id, $chap, $paged ) {
			global $wp_manga;

			$params = array(
				'postID'      => $post_id,
				'chapter'     => $chap,
				$wp_manga->manga_paged_var => $paged,
				'style'       => 'paged',
				'action'      => 'chapter_navigate_page'
			);

			return http_build_query( $params );
		}

	}

	$GLOBALS['wp_manga_chapter_type'] = new WP_MANGA_CHAPTER();
