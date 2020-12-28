<?php

	class WP_MANGA_AJAX_BACKEND {

		public function __construct() {
			// get manga
			add_action( 'wp_ajax_wp-manga-get-chapter', array( $this, 'wp_manga_get_chapter' ) );

			// save manga paging ( back-end )
			add_action( 'wp_ajax_wp-manga-save-chapter-paging', array( $this, 'wp_save_chapter_paging' ) );

			// download manga chapter ( back-end )
			add_action( 'wp_ajax_wp-manga-download-chapter', array( $this, 'wp_manga_download_chapter' ) );

			// delete manga chapter ( back-end )
			add_action( 'wp_ajax_wp-manga-delete-chapter', array( $this, 'wp_manga_delete_chapter' ) );

			// create volume ( back-end )
			add_action( 'wp_ajax_wp-manga-create-volume', array( $this, 'wp_manga_create_volume' ) );

			//download manga
			add_action( 'wp_ajax_wp-download-manga', array( $this, 'wp_download_manga' ) );

			// Search chapter
			add_action( 'wp_ajax_search-chapter', array( $this, 'wp_manga_search_chapter' ) );

			add_action( 'wp_ajax_wp-update-chapters-list', array( $this, 'wp_update_chapters_list' ) );

			add_action( 'wp_ajax_update_picasa_album_dropdown', array( $this, 'update_picasa_album_dropdown' ) );

			//change volume name
			add_action( 'wp_ajax_update_volume_name', array( $this, 'update_volume_name' ) );

			//delete volume
			add_action( 'wp_ajax_wp_manga_delete_volume', array( $this, 'wp_manga_delete_volume' ) );

			add_action( 'wp_ajax_wp_manga_save_chapter_type', array( $this, 'wp_manga_save_chapter_type' ) );

			add_action( 'wp_ajax_chapter_content_upload', array( $this, 'chapter_content_upload' ) );

			// Search blogspot album by name
			add_action( 'wp_ajax_blogspot_search_album', array( $this, 'blogspot_search_album' ) );
		}

		function blogspot_search_album() {

			if ( empty( $_GET['album'] ) ) {
				wp_send_json_error( [
					'message' => esc_html__( 'Album Name cannot be empty', WP_MANGA_TEXTDOMAIN )
				] );
			}

			global $wp_manga_google_upload;

			$album_list = $wp_manga_google_upload->get_album_list();

			if ( ! empty( $album_list ) && is_array( $album_list ) ) {

				$output = array();

				foreach ( $album_list as $id => $album ) {
					if ( $album['title'] == $_GET['album'] ) {
						$output[] = array_merge( $album, array(
							'id' => (string) $id
						) );
					}
				}

				if ( ! empty( $output ) ) {
					wp_send_json_success( [
						'data' => $output
					] );
				}
			}

			wp_send_json_error( [
				'message' => esc_html__( 'Cannot find this album', WP_MANGA_TEXTDOMAIN )
			] );

		}

		function wp_manga_get_chapter() {

			global $wp_manga, $wp_manga_chapter, $wp_manga_functions;
			$postID       = isset( $_GET['postID'] ) ? $_GET['postID'] : null;
			$chapterID    = isset( $_GET['chapterID'] ) ? $_GET['chapterID'] : null;
			$chapter_type = ! empty( $_GET['type'] ) ? $_GET['type'] : 'manga';

			if ( empty( $postID ) ) {
				wp_send_json_error( __( 'Missing Post ID', WP_MANGA_TEXTDOMAIN ) );
			}

			if ( empty( $chapterID ) ) {
				wp_send_json_error( __( 'Missing Chapter ID', WP_MANGA_TEXTDOMAIN ) );
			}

			$this_chapter = $wp_manga_chapter->get_chapter_info( $postID, $chapterID );

			if ( ! $this_chapter ) {
				wp_send_json_error( __( 'Cannot find this Chapter', WP_MANGA_TEXTDOMAIN ) );
			}

			if ( $chapter_type == 'text' || $chapter_type == 'video' ) {

				/**
				 * Get text chapter and video chapter
				 */
				if ( isset( $this_chapter['chapter_id'] ) ) {

					global $wp_manga_text_type;

					//get chapter content
					$chapter_post_content = $wp_manga_text_type->get_chapter_content_post( $this_chapter['chapter_id'] );

					if ( $chapter_post_content ) {
						$chapter_data = array(
							'type'    => $chapter_type,
							'chapter' => $this_chapter,
							'data'    => $chapter_post_content->post_content,
						);

						wp_send_json_success( $chapter_data );
					} else {
						wp_send_json_error( esc_html__( 'Cannot find Chapter Content Data', WP_MANGA_TEXTDOMAIN ) );
					}
				}
			} else {

				/**
				 * Get manga chapter
				 */

				$chapter_data = $wp_manga_functions->get_single_chapter( $postID, $chapterID );

				if ( empty( $chapter_data ) ) {
					wp_send_json_error( __( 'Cannot find this Chapter in JSON file', WP_MANGA_TEXTDOMAIN ) );
				}

				$manga = array(
					'type'    => 'manga',
					'chapter' => $this_chapter,
					'data'    => $chapter_data,
				);

				$available_host = $wp_manga->get_available_host();

				foreach ( $chapter_data['storage'] as $host => $storage ) {

					//skip inUse in storage array
					if ( $host == 'inUse' || empty( $host ) ) {
						continue;
					}

					//add storage name to return data
					if(isset($available_host[ $host ])){
						$manga['data']['storage'][ $host ]['name'] = $available_host[ $host ]['text'];
						unset( $available_host[ $host ] );
					}
				}

				if ( ! empty( $available_host ) ) {
					$manga['available_host'] = $available_host;
				}

				wp_send_json_success( $manga );

			}

		}

		function wp_manga_delete_chapter() {

			global $wp_manga_storage;
			$postID    = isset( $_POST['postID'] ) ? $_POST['postID'] : null;
			$chapterID = isset( $_POST['chapterID'] ) ? $_POST['chapterID'] : null;

			$wp_manga_storage->delete_chapter( $postID, $chapterID );

			wp_send_json_success( $chapterID );

		}

		function wp_manga_create_volume() {
			global $wp_manga_storage;
			$volumeName = isset( $_POST['volumeName'] ) ? $_POST['volumeName'] : null;
			$postID     = isset( $_POST['postID'] ) ? $_POST['postID'] : null;
			if ( $postID ) {
				$volume_id = $wp_manga_storage->create_volume( $volumeName, $postID );

				wp_send_json_success( $volume_id );
			} else {
				wp_send_json_error();
			}
		}

		function wp_save_chapter_paging() {

			global $wp_manga_storage, $wp_manga_chapter;

			$_POST = stripslashes_deep( $_POST );

			$paging         = isset( $_POST['paging'] ) ? $_POST['paging'] : null;
			$postID         = isset( $_POST['postID'] ) ? $_POST['postID'] : null;
			$chapterID      = isset( $_POST['chapterID'] ) ? $_POST['chapterID'] : null;
			$storage        = isset( $_POST['storage'] ) ? $_POST['storage'] : null;
			$chapterNewName = isset( $_POST['chapterNewName'] ) ? $_POST['chapterNewName'] : null;
			$nameExtend     = isset( $_POST['chapterNameExtend'] ) ? $_POST['chapterNameExtend'] : null;
			$chapter_type   = isset( $_POST['chapterType'] ) ? $_POST['chapterType'] : 'manga';
			$chapterContent = isset( $_POST['chapterContent'] ) ? $_POST['chapterContent'] : '';

			$volume = isset( $_POST['volume'] ) ? $_POST['volume'] : '0';

			$chapter_args = array(
				'update' => array(
					'volume_id'           => $volume,
					'chapter_name'        => $chapterNewName,
					'chapter_name_extend' => $nameExtend,
				),
				'args'   => array(
					'post_id'    => $postID,
					'chapter_id' => $chapterID,
				)
			);

			if ( $chapter_type == 'manga' ) {

				if ( ! empty( $_POST['deletedImages'] ) && is_array( $_POST['deletedImages'] ) ) {
					foreach ( $_POST['deletedImages'] as $image ) {
						$image = WP_MANGA_DATA_DIR . $image;
						if ( file_exists( $image ) ) {
							unlink( $image );
						}
					}
				}

				$result['file']   = $paging;
				$result['volume'] = $volume;

				//#needcheck
				$result['host'] = $wp_manga_storage->get_host( $storage );

				$chapter_data = $wp_manga_storage->update_chapter( $chapter_args, $result, $storage );

				wp_send_json_success( $chapter_data );

			} else {

				$wp_manga_chapter->update_chapter( $chapter_args['update'], $chapter_args['args'] );

				global $wp_manga_text_type;
				//update chapter content
				$chapter_post_content = $wp_manga_text_type->get_chapter_content_post( $chapterID );

				$resp = wp_update_post( array(
					'ID'           => $chapter_post_content->ID,
					'post_content' => $chapterContent,
				) );

				wp_send_json_success( $resp );

			}
		}

		function wp_manga_download_chapter() {

			global $wp_manga_storage;
			$post_id    = isset( $_POST['postID'] ) ? $_POST['postID'] : '';
			$chapter_id = isset( $_POST['chapterID'] ) ? $_POST['chapterID'] : '';
			$storage    = isset( $_POST['storage'] ) ? $_POST['storage'] : '';

			if ( ! empty( $post_id ) && ! empty( $chapter_id ) && ! empty( $storage ) ) {
				$zip = $wp_manga_storage->zip_chapter( $post_id, $chapter_id, $storage );

				if ( $zip !== false ) {
					wp_send_json_success( $zip );
				}
			}

			wp_send_json_error();
		}

		function wp_download_manga() {
			

			$post_id = isset( $_POST['postID'] ) ? $_POST['postID'] : '';

			if ( ! empty( $post_id ) ) {
				global $wp_manga, $wp_manga_storage;

				$uniqid = $wp_manga->get_uniqid( $post_id );

				$manga_zip = $wp_manga_storage->zip_manga( $post_id, $uniqid );

				if ( $manga_zip ) {

					$response = array(
						'zip' => $manga_zip
					);

					wp_send_json_success( $response );

				}

			}

			wp_send_json_error( __( 'Something wrong happened. Please try again later', WP_MANGA_TEXTDOMAIN ) );

		}

		function wp_manga_search_chapter() {

			global $wp_manga_functions, $wp_manga_chapter;

			$post_id = ! empty( $_POST['post'] ) ? $_POST['post'] : null;
			$search  = ! empty( $_POST['chapter'] ) ? $_POST['chapter'] : null;

			$post = get_post( get_post( $post_id ) );

			$chapters = $wp_manga_functions->get_latest_chapters( $post_id, $search, 10 );

			$volumes = array();

			foreach ( $chapters as $chapter ) {

				$this_chapter_volume = $wp_manga_chapter->get_chapter_volume( $post_id, $chapter['chapter_id'] );

				if ( ! isset( $volumes[ $chapter['volume_id'] ] ) ) {
					$volumes[ $chapter['volume_id'] ] = array(
						'volume_name' => $this_chapter_volume['volume_name']
					);
				}

				$volumes[ $chapter['volume_id'] ]['chapters'][] = $chapter;

			}

			$output = '';
			if ( $chapters ) {
				$output .= $wp_manga_functions->list_chapters_by_volume( $post_id, $volumes, true );
			} else {
				$output = __( '<span> Nothing matches </span>', WP_MANGA_TEXTDOMAIN );
			}

			wp_send_json_success( $output );

		}

		function wp_update_chapters_list() {

			global $wp_manga, $wp_manga_post_type;

			$post_id = isset( $_POST['postID'] ) ? $_POST['postID'] : '';
			$output  = $wp_manga_post_type->list_all_chapters( $post_id );

			if ( ! empty( $output ) ) {
				wp_send_json_success( $output );
			}

			wp_send_json_error();

		}

		function update_picasa_album_dropdown() {

			$album         = get_option( 'google_latest_album', 'default' );
			$albums        = $GLOBALS['wp_manga_google_upload']->get_album_list();
			$current_album = isset( $_POST['current_album'] ) ? $_POST['current_album'] : '';

			$html = '';

			foreach ( $albums as $id => $album ) {
				$html .= '<option value="' . $id . '"' . selected( $id, $current_album, false ) . '>' . sprintf( __( '[Album] %s (having %d items)', WP_MANGA_TEXTDOMAIN ), $album['title'], $album['numphotos'] ) . '</option>';
			}

			wp_send_json_success( $html );

		}

		function update_volume_name() {

			$post_id     = isset( $_POST['postID'] ) ? $_POST['postID'] : '';
			$volume_id   = isset( $_POST['volumeID'] ) ? $_POST['volumeID'] : '';
			$volume_name = isset( $_POST['volumeName'] ) ? $_POST['volumeName'] : '';

			if ( empty( $volume_id ) ) {
				wp_send_json_error( __( 'Missing Volume ID', WP_MANGA_TEXTDOMAIN ) );
			}

			global $wp_manga_volume;
			$args = array(
				'volume_id' => $volume_id,
			);

			if ( ! empty( $post_id ) ) {
				$args['post_id'] = $post_id;
			}

			$result = $wp_manga_volume->update_volume( array( 'volume_name' => $volume_name ), $args );

			wp_send_json_success( $result );

		}

		function wp_manga_delete_volume() {

			$volume_id = isset( $_POST['volumeID'] ) ? $_POST['volumeID'] : '';
			$post_id   = isset( $_POST['postID'] ) ? $_POST['postID'] : '';

			if ( empty( $volume_id ) && $volume_id !== '0' ) {
				wp_send_json_error( __( 'Missing Volume ID', WP_MANGA_TEXTDOMAIN ) );
			}

			global $wp_manga_storage;
			$wp_manga_storage->delete_volume( $post_id, $volume_id );

			wp_send_json_success();

		}

		function wp_manga_save_chapter_type() {

			$post_id      = isset( $_POST['postID'] ) ? $_POST['postID'] : '';
			$chapter_type = isset( $_POST['chapterType'] ) ? $_POST['chapterType'] : '';

			if ( empty( $post_id ) ) {
				wp_send_json_error( esc_html__( 'Missing Post ID', WP_MANGA_TEXTDOMAIN ) );
			}

			if ( empty( $chapter_type ) ) {
				wp_send_json_error( esc_html__( 'Missing Chapter Type', WP_MANGA_TEXTDOMAIN ) );
			}

			$type_array = apply_filters( 'madara_manga_chapter_type_array', array( 'manga', 'text', 'video' ) );

			if ( ! in_array( $chapter_type, $type_array ) ) {
				wp_send_json_error( esc_html__( 'Invalid Chapter Type', WP_MANGA_TEXTDOMAIN ) );
			}

			do_action( 'madara_before_update_chapter_type_meta', $post_id, $chapter_type );

			if ( $chapter_type != 'manga' && $chapter_type != 'text' && $chapter_type != 'video' ) {
				$chapter_type = 'manga';
			}

			update_post_meta( $post_id, '_wp_manga_chapter_type', $chapter_type );

			wp_send_jsoN_success();

		}

		/**
		 * Handle multi chapters upload for content chapter
		 *
		 */
		function chapter_content_upload() {

			if ( empty( $_FILES['file'] ) && empty($_POST['directlink']) ) {
				wp_send_json_error( array( 'message' => esc_html__( 'Missing File', WP_MANGA_TEXTDOMAIN ) ) );
			} else {
				// Check if zip file contains invalid file
				$validation = MADARA_ZIP_VALIDATION::get_zip_structure( !empty( $_FILES['file'] ) ? $_FILES['file']['tmp_name'] : ABSPATH . $_POST['directlink']);

				do_action( 'multi_chapters_content_upload_validation', $validation );

				if ( is_wp_error( $validation ) ) {
					return wp_send_json_error( $validation->get_error_message() );
				} elseif ( isset( $validation['chapter_type'] ) && $validation['chapter_type'] == 'manga' ) {
					return wp_send_json_error( __( 'Invalid Zip file for Manga Chapter Content type upload. This should be zip file for Manga Images Chapter type upload.', WP_MANGA_TEXTDOMAIN ) );
				} elseif ( isset( $validation['zip_type'] ) && $validation['zip_type'] == 'single_chapter' ) {
					return wp_send_json_error( __( 'Invalid Zip file for Multi Chapters upload. This is Single Chapter upload zip file', WP_MANGA_TEXTDOMAIN ) );
				} elseif ( empty( $validation['zip_type'] ) || empty( $validation['chapter_type'] ) ) {
					return wp_send_json_error( __( 'Unsupported Zip File for Multi Chapters upload', WP_MANGA_TEXTDOMAIN ) );
				}

			}

			if ( empty( $_POST['postID'] ) ) {
				wp_send_json_error( array( 'message' => esc_html__( 'Missing Post ID', WP_MANGA_TEXTDOMAIN ) ) );
			}

			if ( empty( $_POST['chapterType'] ) ) {
				wp_send_json_error( array( 'message' => esc_html__( 'Missing Chapter Type', WP_MANGA_TEXTDOMAIN ) ) );
			}

			$post_id      = $_POST['postID'];
			$volume       = isset( $_POST['volume'] ) ? $_POST['volume'] : '';
			$chapter_type = $_POST['chapterType'];

			global $wp_manga_text_type, $wp_manga_functions;

			$response = $wp_manga_text_type->upload_handler( $post_id, !empty( $_FILES['file'] ) ? $_FILES['file'] : array('name' => basename($_POST['directlink']),'tmp_name' => ABSPATH . $_POST['directlink']));

			if ( $response['success'] ) {
				wp_send_json_success( $response );
			} else {
				wp_send_json_error( $response );
			}

		}
	}

	new WP_MANGA_AJAX_BACKEND();
