<?php

	/**
	 * MadaraShortcodeMangaListing
	 */
	class MadaraShortcodeMangaListing extends MadaraShortcode {
		public function __construct( $params = null, $content = '' ) {
			parent::__construct( 'manga_listing', $params, $content );
		}

		/**
		 * @param $atts
		 * @param $content
		 *
		 * @return string
		 */
		public function renderShortcode( $atts, $content ) {

			$title         = !empty( $atts['heading'] ) ? $atts['heading'] : '';
			$heading_icon  = !empty( $atts['heading_icon'] ) ? $atts['heading_icon'] : function_exists( 'madara_default_heading_icon' ) ? madara_default_heading_icon( false ) : '';
			$orderby       = !empty( $atts['orderby'] ) ? $atts['orderby'] : 'latest';
			$count         = !empty( $atts['count'] ) ? $atts['count'] : '';
			$order         = !empty( $atts['order'] ) ? $atts['order'] : 'DESC';
			$genres        = !empty( $atts['genres'] ) ? $atts['genres'] : null;
			$tags          = !empty( $atts['tags'] ) ? $atts['tags'] : null;
			$ids           = !empty( $atts['ids'] ) ? $atts['ids'] : '';
			$items_per_row = !empty( $atts['items_per_row'] ) ? $atts['items_per_row'] : 2;
			$chapter_type  = !empty( $atts['chapter_type'] ) ? $atts['chapter_type'] : 'all';

			if( empty( $ids ) ){
				$shortcode_args = array(
					'post_type'      => 'wp-manga',
					'posts_per_page' => $count,
					'order'          => $order,
					'orderby'        => $orderby,
				);

				if ( $chapter_type == 'manga' ) {
					$shortcode_args['meta_query'] = array(
						'relation' => 'OR',
						array(
							'key'     => '_wp_manga_chapter_type',
							'value'   => '',
							'compare' => 'NOT EXISTS'
						),
						array(
							'key'   => '_wp_manga_chapter_type',
							'value' => 'manga',
						)
					);
				} elseif ( $chapter_type == 'text' || $chapter_type == 'video' ) {
					$shortcode_args['meta_query'] = array(
						array(
							'key'     => '_wp_manga_chapter_type',
							'value'   => $chapter_type,
							'compare' => '='
						),
					);
				}

				if( !empty( $tags ) || !empty( $genres ) ){
					$shortcode_args['tax_query'] = array(
						'realtion' => 'OR'
					);

					if( !empty( $tags ) ){
						$tags = explode( ',', $tags );

						if( !empty( $tags ) ){
							$shortcode_args['tax_query'][] = array(
								'taxonomy' => 'wp-manga-tag',
								'field'    => 'slug',
								'terms'    => $tags
							);
						}
					}

					if( !empty( $genres ) ){
						$genres = explode( ',', $genres );

						if( !empty( $genres ) ){
							$shortcode_args['tax_query'][] = array(
								'taxonomy' => 'wp-manga-genre',
								'field'    => 'slug',
								'terms'    => $genres
							);
						}
					}
				}

				if( !function_exists( 'madara_manga_query' ) ){
					return;
				}

				$shortcode_query = madara_manga_query( $shortcode_args );

			}else{
				$shortcode_args = array(
					'post__in'       => explode( ',', $ids ),
					'post_type'      => 'wp-manga',
				);

				$shortcode_query = new WP_Query( $shortcode_args );
			}
			
			$item_layout = isset($atts['item_layout']) ? $atts['item_layout'] : 'default';

			ob_start();

			if ( $shortcode_query->have_posts() ) {
				?>
                <div class="c-page">
                    <div class="c-page__content">

						<?php if ( ! empty( $title ) ) { ?>
                            <div class="tab-wrap">
                                <div class="c-blog__heading style-2 font-heading">

                                    <h4>
                                        <i class="<?php echo esc_attr( $heading_icon ); ?>"></i>
										<?php echo esc_html( $title ); ?>
                                    </h4>
                                </div>
                            </div>
						<?php } ?>

                        <!-- Tab panes -->
                        <div class="tab-content-wrap">
                            <div role="tabpanel" class="c-tabs-item">
                                <div class="page-content-listing item-<?php echo esc_attr($item_layout);?>">
									<?php
										if ( $shortcode_query->have_posts() ) {

											global $wp_query;
											$index = 1;
											$wp_query->set( 'madara_post_count', madara_get_post_count( $shortcode_query ) );

											if ( $items_per_row == 3 ) {
												$wp_query->set( 'sidebar', 'full' );
											}
											
											$wp_query->set('manga_archives_item_layout', $item_layout);

											while ( $shortcode_query->have_posts() ) {

												$wp_query->set( 'madara_loop_index', $index );
												$index ++;

												$shortcode_query->the_post();
												get_template_part( 'madara-core/content/content', 'archive' );
											}

										} else {
											get_template_part( 'madara-core/content/content-none' );
										}

										wp_reset_postdata();

									?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
				<?php
			}
			$output = ob_get_contents();
			ob_end_clean();

			return $output;
		}

	}

	$madara_button = new MadaraShortcodeMangaListing();

	/**
	 * add button to visual composer
	 */
	add_action( 'after_setup_theme', 'reg_manga_listing' );

	function reg_manga_listing() {
		if ( function_exists( 'vc_map' ) ) {
			$params = array(
				array(
					"admin_label" => true,
					"type"        => "textfield",
					"heading"     => esc_html__( "Heading", "madara" ),
					"param_name"  => "heading",
					"value"       => "",
					"description" => esc_html__( 'Title for Manga Listing section', "madara" )
				),

				array(
					"admin_label" => true,
					"type"        => "iconpicker",
					"heading"     => esc_html__( "Heading Icon", "madara" ),
					"param_name"  => "heading_icon",
					"value"       => "",
					"description" => esc_html__( 'Icon on Heading for Manga Listing section', "madara" )
				),

				array(
					"admin_label" => true,
					"type"        => "dropdown",
					"heading"     => esc_html__( "Manga Chapter Type", "madara" ),
					"param_name"  => "chapter_type",
					"value"       => array(
						esc_html__( "All", "madara" )   => 'all',
						esc_html__( "Image", "madara" ) => 'manga',
						esc_html__( "Text", "madara" )  => 'text',
						esc_html__( "Video", "madara" ) => 'video',
					),
					"std"         => "all",
					"description" => esc_html__( "Type of Manga Chapter to query", "madara" )
				),

				array(
					"admin_label" => true,
					"type"        => "textfield",
					"heading"     => esc_html__( "Count", "madara" ),
					"param_name"  => "count",
					"value"       => "",
					"description" => esc_html__( 'number of items to query', "madara" )
				),
				
				array(
					"admin_label" => true,
					"type"        => "dropdown",
					"heading"     => esc_html__( "Item Layout", "madara" ),
					"param_name"  => "item_layout",
					"value"       => array(
						esc_html__( "Default", "madara" ) => 'default',
						esc_html__( "Big Thumbnail", "madara" ) => 'big_thumbnail',
						esc_html__( "Simple", "madara" ) => 'simple'
					),
					"description" => esc_html__( "Choose Item Layout", "madara" )
				),

				array(
					"admin_label" => true,
					"type"        => "dropdown",
					"heading"     => esc_html__( "Items Per Row", "madara" ),
					"param_name"  => "items_per_row",
					"value"       => array(
						esc_html__( "2 items per row", "madara" ) => 2,
						esc_html__( "3 items per row", "madara" ) => 3,
					),
					"description" => esc_html__( "Type of Manga Chapter to query", "madara" )
				),

				array(
					"admin_label" => true,
					"type"        => "dropdown",
					"heading"     => esc_html__( "Oder By", "madara" ),
					"param_name"  => "orderby",
					"value"       => array(
						esc_html__( "Latest", "madara" )                                          => 'latest',
						esc_html__( "A-Z", "madara" )                                             => 'alphabet',
						esc_html__( "Rating", "madara" )                                          => 'rating',
						esc_html__( "Trending", "madara" )                                        => "trending",
						esc_html__( "Most Views", "madara" )                                      => "views",
						esc_html__( "New Manga", "madara" )                                       => "new-manga",
						esc_html__( "Input (only available when using ids parameter)", "madara" ) => "input",
						esc_html__( "Random", "madara" )                                          => "random"
					),
					"description" => esc_html__( "condition to query items", "madara" )
				),

				array(
					"admin_label" => true,
					"type"        => "dropdown",
					"heading"     => esc_html__( "Order", "madara" ),
					"param_name"  => "order",
					"value"       => array(
						esc_html__( "Descending", "madara" ) => "DESC",
						esc_html__( "Ascending", "madara" )  => "ASC"
					),
				),

				array(
					"type"        => "textfield",
					"heading"     => esc_html__( "Manga Genres", "madara" ),
					"param_name"  => "genres",
					"value"       => "",
					"description" => esc_html__( 'List of Manga Genres slug to query items from, separated by a comma.', "madara" )
				),

				array(
					"type"        => "textfield",
					"heading"     => esc_html__( "Manga Tags", "madara" ),
					"param_name"  => "tags",
					"value"       => "",
					"description" => esc_html__( 'List of Manga Tags slug to query items from, separated by a comma.', "madara" )
				),

				array(
					"type"        => "textfield",
					"heading"     => esc_html__( "IDs", "madara" ),
					"param_name"  => "ids",
					"value"       => "",
					"description" => esc_html__( 'list of post IDs to query, separated by a comma. If this value is not empty, cats, tags and featured are omitted', "madara" )
				),
			);
			vc_map( array(
				'name'     => esc_html__( 'Madara Manga Listing', 'madara' ),
				'base'     => 'manga_listing',
				'icon'     => CT_SHORTCODE_PLUGIN_URL . '/shortcodes/img/c_post_slider.png',
				'category' => esc_html__( 'Madara Shortcodes', 'madara' ),
				'params'   => $params,
			) );
		}
	}
