<?php

	class WP_MANGA_AJAX_FRONTEND {

		public function __construct() {

			// search manga
			add_action( 'wp_ajax_wp-manga-search-manga', array( $this, 'wp_manga_search_manga' ) );
			add_action( 'wp_ajax_nopriv_wp-manga-search-manga', array( $this, 'wp_manga_search_manga' ) );

			// delete bookmark manga
			add_action( 'wp_ajax_wp-manga-delete-bookmark', array( $this, 'wp_manga_delete_bookmark' ) );
			add_action( 'wp_ajax_nopriv_wp-manga-delete-bookmark', array( $this, 'wp_manga_delete_bookmark' ) );

			add_action( 'wp_ajax_wp-manga-delete-multi-bookmark', array( $this, 'wp_manga_delete_multi_bookmark' ) );
			add_action( 'wp_ajax_nopriv_wp-manga-delete-multi-bookmark', array(
				$this,
				'wp_manga_delete_multi_bookmark'
			) );

			// bookmark manga
			add_action( 'wp_ajax_wp-manga-user-bookmark', array( $this, 'wp_manga_user_bookmark' ) );
			add_action( 'wp_ajax_nopriv_wp-manga-user-bookmark', array( $this, 'wp_manga_user_bookmark' ) );

			// get next manga in list page ( front-end )
			add_action( 'wp_ajax_wp-manga-get-next-manga', array( $this, 'wp_manga_get_next_manga' ) );
			add_action( 'wp_ajax_nopriv_wp-manga-get-next-manga', array( $this, 'wp_manga_get_next_manga' ) );

			// save rating when user click ( front-end )
			add_action( 'wp_ajax_wp-manga-save-rating', array( $this, 'wp_manga_save_rating' ) );
			add_action( 'wp_ajax_nopriv_wp-manga-save-rating', array( $this, 'wp_manga_save_rating' ) );

			// User upload avatar
			add_action( 'wp_ajax_wp-manga-upload-avatar', array( $this, 'wp_manga_upload_avatar' ) );
			add_action( 'wp_ajax_nopriv_wp-manga-upload-avatar', array( $this, 'wp_manga_upload_avatar' ) );

			// Get User after login
			add_action( 'wp_ajax_wp-manga-get-user-section', array( $this, 'wp_manga_get_user_section' ) );
			add_action( 'wp_ajax_nopriv_wp-manga-get-user-section', array( $this, 'wp_manga_get_user_section' ) );

			// Chapter content when using ajax pagination
			add_action( 'wp_ajax_chapter_navigate_page', array( $this, 'chapter_navigate_page' ) );
			add_action( 'wp_ajax_nopriv_chapter_navigate_page', array( $this, 'chapter_navigate_page' ) );

			// Save user player id for web push
			add_action( 'wp_ajax_save_user_player_id', array( $this, 'save_user_player_id' ) );
		}

		function save_user_player_id() {

			if ( empty( $_POST['userID'] ) || empty( $_POST['playerID'] ) ) {
				die();
			}

			$cur_player_ids = get_post_meta( $_POST['userID'], '_onesignal_player_id', true );

			if ( empty( $cur_player_ids ) && ! is_array( $cur_player_ids ) ) {
				$cur_player_ids = array( $_POST['playerID'] );
			} else {
				$cur_player_ids[] = $_POST['playerID'];
			}

			$resp = update_user_meta( $_POST['userID'], '_onesignal_player_id', $cur_player_ids );

			wp_send_json_success( $resp );

		}

		function wp_manga_delete_bookmark() {

			global $wp_manga_functions;
			$post_id         = isset( $_POST['postID'] ) ? $_POST['postID'] : '';
			$is_manga_single = $_POST['isMangaSingle'];

			$user_id        = get_current_user_id();
			$bookmark_manga = get_user_meta( $user_id, '_wp_manga_bookmark', true );

			if ( empty( $post_id ) || empty( $bookmark_manga ) ) {
				wp_send_json_error();
			}

			// Remove from user bookmark
			foreach ( $bookmark_manga as $index => $manga ) {
				if ( $manga['id'] == $post_id ) {
					unset( $bookmark_manga[ $index ] );
				}
			}

			// Remove from manga user bookmark
			$user_bookmarked = get_post_meta( $post_id, '_wp_user_bookmarked', true );

			if ( ! empty( $user_bookmarked ) ) {
				$index = array_search( $user_id, $user_bookmarked );

				if ( $index !== false ) {
					unset( $user_bookmarked[ $index ] );
					update_post_meta( $post_id, '_wp_user_bookmarked', $user_bookmarked );
				}
			}

			$resp = update_user_meta( $user_id, '_wp_manga_bookmark', $bookmark_manga );

			if ( $resp == true ) {
				if ( empty( $bookmark_manga ) && ! $is_manga_single ) {
					wp_send_json_success( array(
						'is_empty' => true,
						'msg'      => wp_kses( __( '<span>You haven\'t bookmark any manga yet</span>', WP_MANGA_TEXTDOMAIN ), array( 'span' => array() ) )
					) );
				};
				$link = $wp_manga_functions->create_bookmark_link( $post_id, $is_manga_single );
				wp_send_json_success( $link );
			}

			wp_send_json_error();
		}

		function wp_manga_delete_multi_bookmark() {

			$bookmark_ids = isset( $_POST['bookmark'] ) ? $_POST['bookmark'] : null;

			$user_id        = get_current_user_id();
			$bookmark_manga = get_user_meta( $user_id, '_wp_manga_bookmark', true );

			if ( $bookmark_ids ) {
				if ( is_user_logged_in() ) {

					foreach ( $bookmark_manga as $index => $manga ) {
						if ( in_array( $manga['id'], $bookmark_ids ) ) {
							unset( $bookmark_manga[ $index ] );
						}

						// Remove from manga user bookmark
						$user_bookmarked = get_post_meta( $manga['id'], '_wp_user_bookmarked', true );
						$index           = array_search( $user_id, $user_bookmarked );

						if ( $index !== false ) {
							unset( $user_bookmarked[ $index ] );
							update_post_meta( $manga['id'], '_wp_user_bookmarked', $user_bookmarked );
						}

					}

					$resp = update_user_meta( $user_id, '_wp_manga_bookmark', $bookmark_manga );

					if ( $resp == true ) {
						if ( empty( $bookmark_manga ) ) {
							wp_send_json_success( array(
								'is_empty' => true,
								'msg'      => wp_kses( __( '<span>You haven\'t bookmark any manga yet</span>', WP_MANGA_TEXTDOMAIN ), array( 'span' => array() ) )
							) );
						};
						wp_send_json_success();
					}

					wp_send_json_error();
				}
			} else {
				wp_send_json_error( array( 'message' => __( 'Eh, try to cheat ahh !?', WP_MANGA_TEXTDOMAIN ) ) );
			}
			die( 0 );
		}

		function wp_manga_user_bookmark() {
			global $wp_manga_login, $wp_manga, $wp_manga_functions;

			if ( is_user_logged_in() ) {

				$post_id    = isset( $_POST['postID'] ) ? $_POST['postID'] : '';
				$chapter_id = isset( $_POST['chapter'] ) ? $_POST['chapter'] : null;
				$paged      = isset( $_POST['page'] ) ? $_POST['page'] : '';
				$user_id    = get_current_user_id();

				if ( empty( $post_id ) || empty( $user_id ) ) {
					wp_send_json_error();
				}

				$this_bookmark = array(
					'id'       => $post_id,
					'c'        => $chapter_id,
					'p'        => $paged,
					'unread_c' => [] // number of unread chapter
				);

				$current_bookmark = get_user_meta( $user_id, '_wp_manga_bookmark', true );

				if ( ! empty( $current_bookmark ) && is_array( $current_bookmark ) ) {

					//check if current manga is existed
					$index = array_search( $post_id, array_column( $current_bookmark, 'id' ) );

					if ( $index !== false ) {
						$this_bookmark['unread_c']  = $current_bookmark[ $index ]['unread_c'];
						$current_bookmark[ $index ] = $this_bookmark;
						$manga_existed              = true;
					} else {
						$current_bookmark[] = $this_bookmark;
					}

				} else {
					$current_bookmark = array( $this_bookmark );
				}

				$response = update_user_meta( $user_id, '_wp_manga_bookmark', $current_bookmark );

				if ( $response == true ) {

					if ( empty( $manga_existed ) ) {
						// Update user id to manga bookmarked meta
						$users_bookmarked = get_post_meta( $post_id, '_wp_user_bookmarked', true );

						if ( empty( $users_bookmarked ) ) {
							$users_bookmarked = array();
						}

						if ( is_array( $users_bookmarked ) && in_array( $user_id, $users_bookmarked ) === false ) {
							$users_bookmarked[] = $user_id;
						}

						update_post_meta( $post_id, '_wp_user_bookmarked', $users_bookmarked );
					}

					if ( empty( $chapter ) ) {
						$is_manga_single = true;
					}

					$link = $wp_manga_functions->create_bookmark_link( $post_id, $is_manga_single );
					wp_send_json_success( $link );
				}

				wp_send_json_error( $response );


			} else {
				wp_send_json_error( array( 'code' => 'login_error' ) );
			}
		}

		function wp_manga_get_next_manga() {

			global $wp_manga_functions;

			$paged    = isset( $_POST['paged'] ) ? $_POST['paged'] : null;
			$term     = isset( $_POST['term'] ) ? $_POST['term'] : null;
			$taxonomy = isset( $_POST['taxonomy'] ) ? $_POST['taxonomy'] : null;
			$orderby  = isset( $_POST['orderby'] ) ? $_POST['orderby'] : 'latest';

			if ( $paged ) {
				$args = array(
					'post_type'   => 'wp-manga',
					'post_status' => 'publish',
					'paged'       => $paged,
				);

				if ( $term && $taxonomy ) {
					$args['tax_query'] = array(
						array(
							'taxonomy' => $taxonomy,
							'field'    => 'slug',
							'terms'    => $term,
						),
					);
				}

				if ( $orderby ) {
					switch ( $orderby ) {
						case 'latest':
							$args['orderby']  = 'meta_value_num';
							$args['meta_key'] = '_latest_update';
							break;
						case 'alphabet':
							$args['orderby'] = 'post_title';
							$args['order']   = 'ASC';
							break;
						case 'rating':
							$args['orderby']  = 'meta_value_num';
							$args['meta_key'] = '_manga_avarage_reviews';
							break;
						case 'trending':
							$args['orderby']  = 'meta_value_num';
							$args['meta_key'] = '_wp_manga_week_views';
							break;
						case 'most-views':
							$args['orderby']  = 'meta_value_num';
							$args['meta_key'] = '_wp_manga_views';
							break;
						case 'new-manga':
							$args['orderby'] = 'date';
							$args['order']   = 'DESC';
							break;
						default:
							$args['orderby'] = 'date';
							$args['order']   = 'DESC';
							break;
					}
				}

				$manga = new WP_Query( $args );

				if ( $manga->posts ) {
					$max_page = $manga->max_num_pages;
					$result   = array();
					foreach ( $manga->posts as $post ) {
						$html              = $wp_manga_functions->get_html( $post->ID );
						$result['posts'][] = $html;
					}

					if ( intval( $max_page ) == intval( $paged ) ) {
						$result['next'] = null;
					} else {
						$result['next'] = intval( $paged ) + 1;
					}
					wp_send_json_success( $result );
				} else {
					wp_send_json_error( array( 'code' => 'no-post' ) );
				}
			} else {
				wp_send_json_error( array( 'code' => 'no-page' ) );
			}
		}

		function wp_manga_save_rating() {

			global $wp_manga_functions;

			$postID = isset( $_POST['postID'] ) ? $_POST['postID'] : null;
			$rating = isset( $_POST['star'] ) ? $_POST['star'] : null;
			if ( $postID ) {
				$key          = '_manga_reviews';
				$prev_reviews = get_post_meta( $postID, $key, true );

				if ( '' == $prev_reviews ) {
					$prev_reviews = array();
				}

				if ( is_user_logged_in() ) {
					$new_reviews                          = $prev_reviews;
					$new_reviews[ get_current_user_id() ] = $rating;
				} else {
					$ipaddress                 = $wp_manga_functions->get_client_ip();
					$new_reviews               = $prev_reviews;
					$new_reviews[ $ipaddress ] = $rating;
				}

				update_post_meta( $postID, $key, $new_reviews, $prev_reviews );
				$review = $wp_manga_functions->get_total_review( $postID, $new_reviews );
				update_post_meta( $postID, '_manga_avarage_reviews', $review );

				$rating_html = $wp_manga_functions->manga_rating( $postID, true );

				wp_send_json_success( array(
					'rating_html' => $rating_html,
					'text'        => sprintf( _n( 'Average %1s / %2s out of %3s total vote.', 'Average %1s / %2s out of %3s total votes.', count( $new_reviews ), WP_MANGA_TEXTDOMAIN ), $review, '5', count( $new_reviews ) ),
				) );
			}
		}

		function wp_manga_upload_avatar() {

			$avatar_file = $_FILES['userAvatar'];
			$user_id     = isset( $_POST['userID'] ) ? $_POST['userID'] : '';

			if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], '_wp_manga_save_user_settings' ) || empty( $user_id ) ) {
				wp_send_json_error( array( 'msg' => __( 'I smell some cheating here', WP_MANGA_TEXTDOMAIN ) ) );
			}

			//handle upload
			require_once( ABSPATH . 'wp-admin/includes/admin.php' );
			$avatar = wp_handle_upload( $avatar_file, array( 'test_form' => false ) );

			if ( isset( $avatar['error'] ) || isset( $avatar['upload_error_handler'] ) ) {
				wp_send_json_error( array( 'msg' => __( 'Upload failed! Please try again later', WP_MANGA_TEXTDOMAIN ) ) );
			}

			//resize avatar
			$avatar_editor = wp_get_image_editor( $avatar['file'] );
			if ( ! is_wp_error( $avatar_editor ) ) {
				$avatar_editor->resize( 195, 195, false );
				$avatar_editor->save( $avatar['file'] );
			}

			//media upload
			$avatar_media = array(
				'post_mime_type' => $avatar['type'],
				'post_title'     => '_wp_user_' . $user_id . '_avatar',
				'post_content'   => '',
				'post_status'    => 'inherit',
				'guid'           => $avatar['url'],
				'post_author'    => $user_id,
			);

			$avatar_id = wp_insert_attachment( $avatar_media, $avatar['url'] );

			if ( $avatar_id == 0 ) {
				wp_send_json_error( array( 'msg' => __( 'Upload failed! Please try again later', WP_MANGA_TEXTDOMAIN ) ) );
			}

			//update metadata
			$user_meta   = update_user_meta( $user_id, '_wp_manga_user_avt_id', $avatar_id );
			$avatar_meta = update_post_meta( $avatar_id, '_wp_manga_user_id', $user_id );

			if ( ! empty( $user_meta ) && ! empty( $avatar_meta ) ) {
				wp_send_json_success( get_avatar( $user_id, 195 ) );
			}

		}

		function wp_manga_get_user_section() {

			if ( ! is_user_logged_in() ) {
				wp_send_json_error();
			}

			global $wp_manga_user_actions;
			$user_section = $wp_manga_user_actions->get_user_section();

			if ( $user_section !== false ) {
				wp_send_json_success( $user_section );
			}

			wp_send_json_error();

		}

		function chapter_navigate_page() {
			global $wp_manga_template, $wp_manga_chapter_type, $wp_manga_chapter, $wp_manga_volume, $wp_manga, $wp_manga_storage, $post, $wp_query;
			
			if ( empty( $_GET['postID'] ) ) {
				$this->send_json( 'error', esc_html__( 'Missing post ID', WP_MANGA_TEXTDOMAIN ) );
			}

			if ( empty( $_GET[$wp_manga->manga_paged_var] ) ) {
				$this->send_json( 'error', esc_html__( 'Missing Query Page', WP_MANGA_TEXTDOMAIN ) );
			}

			if ( empty( $_GET['chapter'] ) ) {
				$this->send_json( 'error', esc_html__( 'Missing Chapter param', WP_MANGA_TEXTDOMAIN ) );
			}

			$this_post = get_post( $_GET['postID'] );

			$post = $this_post;
			$wp_query->set( 'chapter', $_GET['chapter'] );

			$chapter = $wp_manga_chapter->get_chapter_by_slug( $_GET['postID'], $_GET['chapter'] );

			if ( empty( $chapter ) ) {
				$this->send_json( 'error', esc_html__( 'Chapter not found', WP_MANGA_TEXTDOMAIN ) );
			}

			$volume = $wp_manga_chapter->get_chapter_volume( $_GET['postID'], $chapter['chapter_id'] );

			if ( ! empty( $volume ) ) {
				$volume_slug = $wp_manga_storage->slugify( $volume['volume_name'] );
				$wp_query->set( 'volume', $volume_slug );
			}

			$output = array();
			
			ob_start();

			$paged = ! empty( $_GET[$wp_manga->manga_paged_var] ) ? $_GET[$wp_manga->manga_paged_var] : 1;
			$style = ! empty( $_GET['style'] ) ? $_GET['style'] : 'paged';

			?>

			<?php echo apply_filters( 'madara_ads_before_content', madara_ads_position( 'ads_before_content', 'body-top-ads' ) ); ?>

            <div class="reading-content">

				<?php 
				
				/**
				 * If alternative_content is empty, show default content
				 **/
				$alternative_content = apply_filters('wp_manga_chapter_content_alternative', '');
				
				if(!$alternative_content){
					if ( $wp_manga->is_content_manga( $_GET['postID'] ) ) {
						$GLOBALS['wp_manga_template']->load_template( 'reading-content/content', 'reading-content', true );
					} else {
						$GLOBALS['wp_manga_template']->load_template( 'reading-content/content', 'reading-' . $style, true );
					}
				} else {
					echo $alternative_content;
				}
				?>

            </div>

			<?php echo apply_filters( 'madara_ads_after_content', madara_ads_position( 'ads_after_content', 'body-bottom-ads' ) ); ?>

			<?php
			$output['content'] = ob_get_contents();

			ob_end_clean();

			ob_start();

			$wp_manga->manga_nav( 'footer' );

			$output['nav'] = ob_get_contents();

			$output = apply_filters( 'madara_ajax_next_page_content', $output );

			ob_end_clean();

			$this->send_json( 'success', '', $output );

		}

		function wp_manga_search_manga() {

			$title = isset( $_POST['title'] ) ? $_POST['title'] : null;
			if ( ! $title ) {
				wp_send_json_error( array(
					array(
						'error'   => 'empty title',
						'message' => __( 'No manga found', WP_MANGA_TEXTDOMAIN ),
					)
				) );
			}

			$search     = $title;
			$args_query = array(
				'post_type'      => 'wp-manga',
				'posts_per_page' => 6,
				'post_status'    => 'publish',
				's'              => $title,
			);

			$args_query = apply_filters( 'madara_manga_query_args', $args_query );

			$query = new WP_Query( $args_query );

			$query = apply_filters( 'madara_manga_query_filter', $query, $args_query );

			$results = array();
			if ( $query->have_posts() ) {
				$html = '';
				while ( $query->have_posts() ) {
					$query->the_post();
					$results[] = array(
						'title' => get_post_field( 'post_title', get_the_ID() ),
						'url'   => get_permalink( get_the_ID() )
					);
				}
				wp_reset_query();
				wp_send_json_success( $results );
			} else {
				wp_reset_query();
				wp_send_json_error( array(
					array(
						'error'   => 'not found',
						'message' => __( 'No Manga found', WP_MANGA_TEXTDOMAIN )
					)
				) );
			}

			die( 0 );
		}


		function send_json( $type, $msg, $data = null ) {

			$response = array(
				'message' => $msg
			);

			if ( $data ) {
				$response['data'] = $data;
			}

			if ( $type == 'success' ) {
				wp_send_json_success( $response );
			} else {
				wp_send_json_error( $response );
			}

		}
	}

	new WP_MANGA_AJAX_FRONTEND();
