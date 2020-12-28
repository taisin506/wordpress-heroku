<?php

	class WP_MANGA_DATABASE {

		public $wpdb;
		
		private static $instance;

		public static function get_instance() {
			if ( null == self::$instance ) {
				self::$instance = new WP_MANGA_DATABASE();
			}

			return self::$instance;
		}

		function __construct() {

			global $wpdb;
			$this->wpdb = $wpdb;

			register_activation_hook( WP_MANGA_FILE, array( $this, 'wp_manga_create_db' ) );

			$this->volume_table_cols = apply_filters( 'manga_volumes_table_columns', array(
				'volume_id'   => 'bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY',
				'post_id'     => 'bigint(20) NOT NULL',
				'volume_name' => 'text NOT NULL',
				'date'        => 'datetime DEFAULT "0000-00-00 00:00:00" NOT NULL',
				'date_gmt'    => 'datetime DEFAULT "0000-00-00 00:00:00" NOT NULL',
			) );

			$this->chapter_table_cols = apply_filters( 'manga_chapters_table_columns', array(
				'chapter_id'          => 'bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY',
				'post_id'             => 'bigint(20) NOT NULL',
				'volume_id'           => 'bigint(20) NULL',
				'chapter_name'        => 'text NOT NULL',
				'chapter_name_extend' => 'text NOT NULL',
				'chapter_slug'        => 'text NOT NULL',
				'storage_in_use'      => 'varchar(20) NULL',
				'date'                => 'datetime DEFAULT "0000-00-00 00:00:00" NOT NULL',
				'date_gmt'            => 'datetime DEFAULT "0000-00-00 00:00:00" NOT NULL',
			) );

			$this->chapter_data_table_cols = apply_filters( 'manga_chapters_data_table_column', array(
				'data_id'    => 'bigint(20) NOT NULL AUTO_INCREMENT PRIMARY KEY',
				'chapter_id' => 'bigint(20) NOT NULL',
				'storage'    => 'varchar(20) NOT NULL',
				'data'       => 'longtext NOT NULL',
			) );

		}

		function get_wpdb(){
			if( empty( $this->wpdb ) ){
				global $wpdb;
				$this->wpdb = $wpdb;
			}

			return $this->wpdb;
		}

		function wp_manga_create_db() {
			$this->create_table( 'manga_volumes', $this->volume_table_cols );
			$this->create_table( 'manga_chapters', $this->chapter_table_cols, array('manga_chapter_index_1' => '(`post_id`)', 'manga_chapter_index_2' => '(`post_id`,`chapter_id`)','manga_chapter_index_3' => '(`post_id`,`chapter_slug`(100))') );
			$this->create_table( 'manga_chapters_data', $this->chapter_data_table_cols, array('manga_chapter_data_index_2' => '(`chapter_id`,`storage`)') );
		}

		function create_table( $name, $args, $indexs = array() ) {
			if ( ! is_array( $args ) || empty( $args ) ) {
				return false;
			}

			$charset_collate = $this->get_wpdb()->get_charset_collate();
			$table_name      = $this->get_wpdb()->prefix . $name;

			if( $this->table_exists( $table_name ) ){
				foreach( $args as $column => $data ){
					if( ! $this->column_exists( $table_name, $column ) ){
						$this->alter_add_column( $table_name, $column, $data );
					}
				}
			}else{
				$query_args = array();

				foreach( $args as $column => $data ){
					$query_args[] = "{$column} {$data}";
				}

				$sql = "CREATE TABLE $table_name (
		            " . implode( ', ', $query_args );
				
				if(count($indexs) > 0) {
					$str = '';
					foreach($indexs as $key => $val){
						$str .= ",INDEX $key $val";
					}
					$sql .= $str;
				};
				
		        $sql .= ") $charset_collate;";

				$this->maybe_create_table( $table_name, $sql );
			}
			
			if(count($indexs) > 0){
				foreach($indexs as $key => $val){
					if(!$this->is_index_exists($table_name, $key)){
						// create index
						$sql = "CREATE INDEX $key ON $table_name $val";
						$this->get_wpdb()->query($sql);
					}
				}
			}
		}
		
		public function is_index_exists($table_name, $index_name){
			global $wpdb;
			
			
			$sql = "SHOW KEYS FROM $table_name WHERE Key_name='$index_name'";
			$results = $wpdb->get_row($sql);
			if($results == null){
				// index is not exist
				return false;
			}
			
			return true;
		}

		function maybe_create_table( $table_name, $create_ddl ) {

		    global $wpdb;

			if( $this->table_exists( $table_name ) ){
				return true;
			}

		    // Didn't find it try to create it..
		    $wpdb->query($create_ddl);

		    // We cannot directly tell that whether this succeeded!
			if( $this->table_exists( $table_name ) ){
				return true;
			}

		    return false;
		}

		function insert( $table, $args ) {

			// foreach( $args as $key => $value ){
			// 	$args[ $key ] = addslashes( $value );
			// }

			$this->get_wpdb()
			     ->insert( $table, $args );

			if ( isset( $this->get_wpdb()->insert_id ) ) {
				return $this->get_wpdb()->insert_id;
			}

			return false;

		}

		function get( $table, $where, $orderBy, $order ) {

			$sort_setting = $this->get_sort_setting();

			$sort_by    = $sort_setting['sortBy'];
			$sort_order = $sort_setting['sort'];

			if( !empty( $orderBy ) ){
				$sort_by = $orderBy;
				$sort_order = !empty( $order ) ? $order : 'desc';
			}

			if( $sort_by == 'date' ){
				$sql = "
							SELECT SQL_CACHE *
							FROM $table
						";

				if( !empty( $where ) ){
					$sql .= "WHERE $where";
				}

				$sql .= "
							ORDER BY $sort_by $sort_order
						";
			}else{
				$sql = "
							SELECT SQL_CACHE *
							FROM $table
						";

				if( !empty( $where ) ){
					$sql .= "WHERE $where";
				}
			}

			$results = $this
			->get_wpdb()
			->get_results( $sql, 'ARRAY_A' );

			if( $results && $sort_by == 'name' ){

				if( strpos( $table, 'chapters' ) !== false ){
					$column = 'chapter_name';
				}elseif( strpos( $table, 'volumes' ) !== false ){
					$column = 'volume_name';
				}

				if( isset( $column ) ){

					//bring column name to be key of results array
					$names = array_column( $results, $column );

					natcasesort( $names );

					//put appropiate values to sorted position
					$output_results = array();
					foreach( $names as $key => $name ){
						$output_results[] = $results[ $key ];
					}

					if( !empty( $sort_order ) && $sort_order == 'desc' ){
						$results = array_reverse( $output_results );
					}else{
						$results = $output_results;
					}

				}
			}

			return $results;

		}

		function update( $table, $data, $where ) {
			return $this->get_wpdb()->update( $table, $data, $where );

		}

		function delete( $table, $where ) {

			return $this->get_wpdb()->delete( $table, $where );

		}

		function table_exists( $table_name ){

			$query = $this->get_wpdb()->prepare( "SHOW TABLES LIKE %s", $this->get_wpdb()->esc_like( $table_name ) );

		    if ( $this->get_wpdb()->get_var( $query ) == $table_name ) {
		        return true;
		    }

			return false;

		}

		function column_exists( $table_name, $column_name ){

			$query = $this->get_wpdb()->prepare(
				"SELECT *
				FROM INFORMATION_SCHEMA.COLUMNS
				WHERE TABLE_NAME = %s
				AND COLUMN_NAME = %s",
				$table_name,
				$column_name
			);

		    return !empty( $this->get_wpdb()->query( $query ) );

		}

		function alter_add_column( $table_name, $column_name, $column_data ){

			return !empty( $this->get_wpdb()->query( "ALTER TABLE {$table_name}
			ADD COLUMN {$column_name} {$column_data}" ) );

		}

		function get_sort_setting(){

			//get sort option
			if( class_exists( 'App\Madara' ) ){
				$sort_option = App\Madara::getOption('manga_chapters_order', 'name_desc');
			}else{
				$sort_option = 'name_desc';
			}

			if( in_array( $sort_option, array( 'name_desc', 'name_asc' ) ) ){
				$sort_option = array(
					'sortBy' => 'name',
					'sort'   => $sort_option == 'name_desc' ? 'desc' : 'asc'
				);
			}else{
				$sort_option = array(
					'sortBy' => 'date',
					'sort'   => $sort_option == 'date_desc' ? 'desc' : 'asc',
				);
			}

			return $sort_option;

		}
	}

	$GLOBALS['wp_manga_database'] = WP_MANGA_DATABASE::get_instance();
