<?php

	class WP_DB_CHAPTER extends WP_MANGA_DATABASE {

		public function __construct() {

			parent::__construct();

			$this->table = $this->get_wpdb()->prefix . 'manga_chapters';

			add_action( 'manga_chapter_inserted', array( $this, 'update_manga_latest_meta' ), 10, 2 );

		}

		function insert_chapter( $args ) {

			//post_id require, volume id, chapter name, chapter extend name, chapter slug

			if ( empty( $args['post_id'] ) ) {
				return false;
			}

			if( empty( $args['volume_id'] ) ){
				$args['volume_id'] = 0;
			}

			//check if chapter slug is unique
			$search = $this->get_chapter_by_slug( $args['post_id'], $args['chapter_slug'] );

			if( $search ){
				global $wp_manga_functions;
				$args['chapter_slug'] = $wp_manga_functions->unique_slug( $args['post_id'], $args['chapter_name'] );
			}

			$args['date']     = current_time( 'mysql' );
			$args['date_gmt'] = current_time( 'mysql', true );

			$chapter_id = $this->insert( $this->table, $args );

			do_action( 'manga_chapter_inserted', $chapter_id, $args );

			return $chapter_id;

		}

		function get_chapters( $args, $search = false, $orderby = '', $order = '' ) {

			$conditions = array();
			foreach ( $args as $name => $value ) {
				$value = addslashes( $value );
				$conditions[] = "$name = '$value'";
			}

			if ( $search ) {
				$conditions[] = "chapter_name LIKE '%$search%' OR chapter_name_extend LIKE '%$search%'";
			}

			$conditions = apply_filters( 'manga_get_chapters_conditions', $conditions, $args );

			$where = implode( ' AND ', $conditions );

			$results = $this->get( $this->table, $where, $orderby, $order );

			return apply_filters( 'manga_get_chapters_results', $results, $args, $where, $orderby, $order );

		}

		function get_latest_chapters( $post_id, $q, $num, $all_meta = 0, $orderby = 'name', $order = 'desc' ) {

			$chapters = $this->get_chapters( array(
				'post_id' => $post_id
			), $q, $orderby, $order );

			if ( $chapters && $all_meta == 0 ) {
				return array_slice( $chapters, 0, $num );
			}

			return $chapters;

		}

		function delete_chapter( $args ) {

			$resp = $this->delete( $this->table, $args );

			return $resp;

		}

		function update_chapter( $update, $args ) {

			if( ! isset( $update['chapter_slug'] ) && isset( $args['post_id'] ) && isset( $args['chapter_id'] ) ){
				// Get unique slug
				global $wp_manga_functions;
				$update['chapter_slug'] = $wp_manga_functions->unique_slug( $args['post_id'], $update['chapter_name'], $args['chapter_id'] );
			}

			return $this->update( $this->table, $update, $args );

		}

		function get_manga_chapters( $post_id ) {
			return $this->get_chapters( array(
				'post_id' => $post_id
			) );
		}

		function get_chapter_by_id( $post_id = null, $chapter_id ) {

			$args = array(
				'chapter_id' => $chapter_id
			);

			if( $post_id ){
				$args['post_id'] = $post_id;
			}

			$chapter = $this->get_chapters( $args );

			if ( isset( $chapter[0] ) ) {
				return $chapter[0];
			}

			return false;
		}

		function get_chapter_volume( $post_id, $chapter_id ) {

			$chapter = $this->get_chapter_by_id( $post_id, $chapter_id );

			if ( $chapter == false ) {
				return false;
			}

			if ( $chapter['volume_id'] == 0 ) {
				return false;
			}

			$volume = $GLOBALS['wp_manga_volume']->get_volumes( array(
				'post_id'   => $post_id,
				'volume_id' => $chapter['volume_id']
			) );

			if ( isset( $volume[0] ) ) {
				return $volume[0];
			}

			return false;
		}

		function get_chapter_by_slug( $post_id, $chapter_slug ) {

			$chapter = $this->get_chapters( array(
				'post_id'      => $post_id,
				'chapter_slug' => $chapter_slug
			) );

			if ( isset( $chapter[0] ) ) {
				return $chapter[0];
			}

			return false;

		}

		function get_chapter_id_by_slug( $post_id, $chapter_slug ) {

			$chapter = $this->get_chapter_by_slug( $post_id, $chapter_slug );

			if ( $chapter ) {
				return $chapter['chapter_id'];
			}

			return false;

		}

		function get_chapter_slug_by_id( $post_id, $chapter_id ) {

			$chapter = $this->get_chapter_by_id( $post_id, $chapter_id );

			if ( $chapter ) {
				return $chapter['chapter_slug'];
			}

			return false;
		}

		function get_chapter_info( $post_id, $chapter_id ) {

			$chapter = $this->get_chapter_by_id( $post_id, $chapter_id );

			if ( $chapter ) {

				$chapter['volume'] = $this->get_chapter_volume( $post_id, $chapter_id );

				if ( $chapter['volume'] == false ) {
					unset( $chapter['volume'] );
				}

			}

			return $chapter;

		}

		function update_manga_latest_meta( $chapter_id, $args ){

			if( !empty( $args['post_id'] ) ){
				global $wp_manga_functions;
				$wp_manga_functions->update_latest_meta( $args['post_id'] );
			}

		}

	}

	$GLOBALS['wp_manga_chapter'] = new WP_DB_CHAPTER();
