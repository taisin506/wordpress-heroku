<?php

	class WP_DB_CHAPTER_DATA extends WP_MANGA_DATABASE {

		public function __construct() {

			parent::__construct();

			$this->table = $this->get_wpdb()->prefix . 'manga_chapters_data';
            $this->chapter_table = $this->get_wpdb()->prefix . 'manga_chapters';

            add_action( 'chapter_deleted', array( $this, 'delete_chapter_data' ) );

		}

		/**
		 * Get all chapters data in single manga
		 */
		function get_manga_chapters_data( $post_id ){
			if(empty($post_id)){
				return false;
			}
			$results = $this->select( array( 'post_id' => $post_id ) );

			if( !empty( $results ) ){
				$output = array();

				foreach( $results as $storage ){

					$pages = json_decode( $storage['data'], true );

					if( !empty( $pages ) && is_array( $pages ) ){

						if( ! isset( $output[ $storage['chapter_id'] ] ) || ! is_array( $output[ $storage['chapter_id'] ] ) ){
							$output[ $storage['chapter_id'] ] = array(
								'total_page' => apply_filters( 'manga_chapter_data_total_page', count( $pages ), $pages, $storage['chapter_id'], $post_id ),
								'storage'    => array(
									'inUse' => !empty( $storage['storage_in_use'] ) ? $storage['storage_in_use'] : $storage['storage']
								)
							);
						}

						$output[ $storage['chapter_id'] ]['storage'][ $storage['storage'] ] = array(
							'host' => $storage['storage'] === 'local' ? WP_MANGA_DATA_URL : '',
							'page' => $pages
						);

					}

				}

				if( !empty( $output ) ){
					return $output;
				}
			}

			return false;

		}

		/**
		 * Get single chapter data
		 */
		function get_manga_chapter_data( $chapter_id, $post_id = null ){

			$results = $this->select( array(
				'chapter_id' => $chapter_id,
				'post_id'    => $post_id
			) );

			if( !empty( $results ) ){
				$output = array();

				foreach( $results as $storage ){

					$pages = json_decode( $storage['data'], true );

					if( ! isset( $output[ 'total_page' ] ) ){
						$output[ 'total_page' ] = apply_filters( 'manga_chapter_data_total_page', count( $pages ), $pages, $chapter_id, $post_id );
					}

					if( ! isset( $output['storage']['inUse'] ) ){
						$output['storage']['inUse'] = !empty( $storage['storage_in_use'] ) ? $storage['storage_in_use'] : $storage['storage'];
					}

					if( !empty( $pages ) ){
						$output['storage'][ $storage['storage'] ] = array(
							'host' => $storage['storage'] === 'local' ? WP_MANGA_DATA_URL : '',
							'page' => $pages,
						);
					}

				}

				if( !empty( $output ) ){
					return $output;
				}
			}

			return false;

		}

		/**
		 * Get data of specific storage of single chapter
		 */
		function get_manga_chapter_storage_data( $chapter_id, $storage ){

			$data = $this->select(
				array(
					'chapter_id' => $chapter_id,
					'storage'    => $storage
				),
				array(
					'data'
				)
			);

			if( !empty( $data[0]['data'] ) ){
				return json_decode( $data[0]['data'], true );
			}

			return false;

		}

		/**
		 * Custom select results fom chapter data table
		 */
		function select( $where = array(), $selects = array() ){

			if( empty( $where ) || ! is_array( $where ) ){
				return false;
			}

			if( !empty( $selects ) && is_array( $selects ) ){
				$selects = implode( ', ', $selects );
			}else{
				$selects = '*';
			}

			$conditions = array();
			foreach ( $where as $name => $value ) {

				if( empty( $value ) ){
					continue;
				}

				if( !empty( $where['post_id'] ) ){
					// make sure the column name came from correct table on join query
					if( isset( $this->table_cols[ $name ] ) ){
						$name = "D.{$name}";
					}elseif( isset( $this->chapter_table_cols[ $name ] ) ){
						$name = "C.{$name}";
					}
				}

				if( is_numeric( $value ) ){
					$conditions[] = "$name = $value";
				}else{
					$value = addslashes( $value );
					$conditions[] = "$name = '$value'";
				}
			}

			if( !empty( $conditions ) ){
				$sql_where = implode( ' AND ', $conditions );
			}

			if( isset( $where['post_id'] ) ){
				$sql = "SELECT {$selects}
						FROM {$this->chapter_table} as C
						JOIN {$this->table} as D
						ON D.chapter_id = C.chapter_id";
			}else{
				$sql = "SELECT {$selects}
						FROM {$this->table}";
			}

			if( !empty( $sql_where ) ){
				$sql .= " WHERE $sql_where";
			}

			if( !empty( $chapter_id ) ){
				if( is_array( $chapter_id ) ){
					$sql .= " AND C.chapter_id IN %s";
					$chapter_id = '(' . implode( ',', $chapter_id ) . ')';
				}else{
					$sql .= " AND C.chapter_id = %d";
				}
			}

			$results = $this
			->get_wpdb()
			->get_results(
				$sql,
				'ARRAY_A'
			);

			if( !empty( $results ) ){
				return $results;
			}

			return false;

		}

        function update( $update, $args, $dummy_arg = null ) {

			return parent::update( $this->table, $update, $args );

		}

		function delete( $args, $dummy_arg = null ) {

			return parent::delete( $this->table, $args );

		}

        function insert( $args, $dummy_arg = null ){

            if( empty( $args['chapter_id'] ) || empty( $args['data'] ) || empty( $args['storage'] ) ){
                return false;
            }

			// find if this chapter already has this storage data record
			$record = $this->select(
				array(
					'chapter_id' => $args['chapter_id'],
					'storage'    => $args['storage']
				),
				array(
					'data_id'
				)
			);

			if( empty( $record ) ){ // if update failed since there is no record
				return parent::insert( $this->table, $args );
			}else{
				return $this->update(
					array(
						'data'       => $args['data']
					),
					array(
						'data_id' => $record[0]['data_id']
					)
				);
			}

            return true;

        }

        function delete_chapter_data( $args ){

            if( !empty( $args['chapter_id'] ) ){
                return $this->delete( array(
                    'chapter_id' => $args['chapter_id']
                ) );
            }elseif( !empty( $args['post_id'] ) ){
                return $this->get_wpdb()->query(
                    $this->get_wpdb()->prepare(
                        "DELETE {$this->table}
                        FROM {$this->table} as D
                        JOIN {$this->chapter_table} as C
                        ON D.chapter_id = C.chapter_id
                        WHERE C.post_id = %d",
                        $args['post_id']
                    )
                );
            }

        }


	}

	$GLOBALS['wp_manga_chapter_data'] = new WP_DB_CHAPTER_DATA();
