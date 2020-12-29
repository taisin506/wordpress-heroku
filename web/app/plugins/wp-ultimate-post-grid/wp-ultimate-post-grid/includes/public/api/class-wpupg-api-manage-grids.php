<?php
/**
 * API for managing the grids.
 *
 * @link       https://bootstrapped.ventures
 * @since      3.0.0
 *
 * @package    WP_Ultimate_Post_Grid
 * @subpackage WP_Ultimate_Post_Grid/includes/public/api
 */

/**
 * API for managing the grids.
 *
 * @since      3.0.0
 * @package    WP_Ultimate_Post_Grid
 * @subpackage WP_Ultimate_Post_Grid/includes/public/api
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class WPUPG_API_Manage_Grids {

	/**
	 * Register actions and filters.
	 *
	 * @since    3.0.0
	 */
	public static function init() {
		add_action( 'rest_api_init', array( __CLASS__, 'api_register_data' ) );
	}

	/**
	 * Register data for the REST API.
	 *
	 * @since    3.0.0
	 */
	public static function api_register_data() {
		if ( function_exists( 'register_rest_field' ) ) {
			register_rest_route( 'wp-ultimate-post-grid/v1', '/manage/grids', array(
				'callback' => array( __CLASS__, 'api_manage_grids' ),
				'methods' => 'POST',
				'permission_callback' => array( __CLASS__, 'api_required_permissions' ),
			) );
		}
	}

	/**
	 * Required permissions for the API.
	 *
	 * @since    3.0.0
	 */
	public static function api_required_permissions() {
		return current_user_can( WPUPG_Settings::get( 'features_manage_access' ) );
	}

	/**
	 * Handle manage grids call to the REST API.
	 *
	 * @since    3.0.0
	 * @param    WP_REST_Request $request Current request.
	 */
	public static function api_manage_grids( $request ) {
		// Parameters.
		$params = $request->get_params();

		$page = isset( $params['page'] ) ? intval( $params['page'] ) : 0;
		$page_size = isset( $params['pageSize'] ) ? intval( $params['pageSize'] ) : 25;
		$sorted = isset( $params['sorted'] ) ? $params['sorted'] : array( array( 'id' => 'id', 'desc' => true ) );
		$filtered = isset( $params['filtered'] ) ? $params['filtered'] : array();

		// Starting query args.
		$args = array(
			'post_type' => WPUPG_POST_TYPE,
			'post_status' => 'any',
			'posts_per_page' => $page_size,
			'offset' => $page * $page_size,
			'meta_query' => array(
				'relation' => 'AND',
			),
			'tax_query' => array(),
		);

		// Order.
		$args['order'] = $sorted[0]['desc'] ? 'DESC' : 'ASC';
		switch( $sorted[0]['id'] ) {
			case 'date':
				$args['orderby'] = 'date';
				break;
			case 'name':
				$args['orderby'] = 'title';
				break;
			case 'shortlink':
				$args['orderby'] = 'name';
				break;
			case 'text':
				$args['orderby'] = 'meta_value';
				$args['meta_key'] = 'wpupg_text';
				break;
			case 'cloak':
				$args['orderby'] = 'meta_value';
				$args['meta_key'] = 'wpupg_cloak';
				break;
			case 'target':
				$args['orderby'] = 'meta_value';
				$args['meta_key'] = 'wpupg_target';
				break;
			case 'redirect_type':
				$args['orderby'] = 'meta_value';
				$args['meta_key'] = 'wpupg_redirect_type';
				break;
			case 'nofollow':
				$args['orderby'] = 'meta_value';
				$args['meta_key'] = 'wpupg_nofollow';
				break;
			case 'clicks':
				$args['orderby'] = 'meta_value_num';
				$args['meta_key'] = 'wpupg_clicks_total';
				break;
			case 'url':
				$args['orderby'] = 'meta_value';
				$args['meta_key'] = 'wpupg_url';
				break;
			case 'status':
				$args['orderby'] = 'meta_value';
				$args['meta_key'] = 'wpupg_status_type';
				break;
			case 'status_timestamp':
				$args['orderby'] = 'meta_value_num';
				$args['meta_key'] = 'wpupg_status_timestamp';
				break;
			default:
			 	$args['orderby'] = 'ID';
		}

		// Filter.
		if ( $filtered ) {
			foreach ( $filtered as $filter ) {
				$value = trim( $filter['value'] );
				switch( $filter['id'] ) {
					case 'id':
						$args['wpupg_search_id'] = $value;
						break;
					case 'date':
						$args['wpupg_search_date'] = $value;
						break;
					case 'name':
						$args['wpupg_search_title'] = $value;
						break;
					case 'shortlink':
						$args['wpupg_search_slug'] = $value;
						break;
					case 'text':
						if ( $value ) {
							$args['meta_query'][] = array(
								'key' => 'wpupg_text',
								'compare' => 'LIKE',
								'value' => $value,
							);
						}
						break;
					case 'cloak':
						if ( 'all' !== $value ) {
							$args['meta_query'][] = array(
								'key' => 'wpupg_cloak',
								'compare' => '=',
								'value' => $value,
							);
						}
						break;
					case 'target':
						if ( 'all' !== $value ) {
							$args['meta_query'][] = array(
								'key' => 'wpupg_target',
								'compare' => '=',
								'value' => $value,
							);
						}
						break;
					case 'redirect_type':
						if ( 'all' !== $value ) {
							$args['meta_query'][] = array(
								'key' => 'wpupg_redirect_type',
								'compare' => '=',
								'value' => $value,
							);
						}
						break;
					case 'nofollow':
						if ( 'all' !== $value ) {
							$args['meta_query'][] = array(
								'key' => 'wpupg_nofollow',
								'compare' => '=',
								'value' => $value,
							);
						}
						break;
					case 'clicks':
						if ( $value ) {
							$args['meta_query'][] = array(
								'key' => 'wpupg_clicks_total',
								'compare' => 'LIKE',
								'value' => $value,
							);
						}
						break;
					case 'url':
						if ( $value ) {
							$args['meta_query'][] = array(
								'key' => 'wpupg_url',
								'compare' => 'LIKE',
								'value' => $value,
							);
						}
						break;
					case 'categories':
						if ( 'all' !== $value ) {
							if ( 'none' === $value ) {
								$args['tax_query'][] = array(
									'taxonomy' => 'wpupg_category',
									'operator' => 'NOT EXISTS',
								);
							} elseif ( 'any' === $value ) {
								$args['tax_query'][] = array(
									'taxonomy' => 'wpupg_category',
									'operator' => 'EXISTS',
								);
							} else {
								$args['tax_query'][] = array(
									'taxonomy' => 'wpupg_category',
									'field' => 'term_id',
									'terms' => intval( $value ),
								);
							}
						}
					case 'status':
						if ( 'all' !== $value ) {
							if ( 'all-good' === $value ) {
								$args['meta_query'][] = array(
									'key' => 'wpupg_status_type',
									'compare' => 'IN',
									'value' => array( 'ok', 'redirect-ok' ),
								);
							} elseif ( 'all-bad' === $value ) {
								$args['meta_query'][] = array(
									'key' => 'wpupg_status_type',
									'compare' => 'NOT IN',
									'value' => array( 'unknown', 'ok', 'redirect-ok' ),
								);
							} else { 
								$args['meta_query'][] = array(
									'key' => 'wpupg_status_type',
									'compare' => '=',
									'value' => $value,
								);
							}
						}
				}
			}
		}

		add_filter( 'posts_where', array( __CLASS__, 'api_manage_grids_query_where' ), 10, 2 );
		$query = new WP_Query( $args );
		remove_filter( 'posts_where', array( __CLASS__, 'api_manage_grids_query_where' ), 10, 2 );

		$grids = array();
		$posts = $query->posts;
		foreach ( $posts as $post ) {
			$grid = WPUPG_Grid_Manager::get_grid( $post );

			if ( ! $grid ) {
				continue;
			}

			$grids[] = $grid->get_data_manage();
		}

		// Got total number of grids.
		$total = (array) wp_count_posts( WPUPG_POST_TYPE );
		unset( $total['trash'] );

		return array(
			'rows' => array_values( $grids ),
			'total' => array_sum( $total ),
			'filtered' => intval( $query->found_posts ),
			'pages' => ceil( $query->found_posts / $page_size ),
		);
	}

	/**
	 * Filter the where query.
	 *
	 * @since    3.0.0
	 */
	public static function api_manage_grids_query_where( $where, $wp_query ) {
		global $wpdb;

		$id_search = $wp_query->get( 'wpupg_search_id' );
		if ( $id_search ) {
			$where .= ' AND ' . $wpdb->posts . '.ID LIKE \'%' . esc_sql( like_escape( $id_search ) ) . '%\'';
		}

		$date_search = $wp_query->get( 'wpupg_search_date' );
		if ( $date_search ) {
			$where .= ' AND ' . $wpdb->posts . '.post_date LIKE \'%' . esc_sql( like_escape( $date_search ) ) . '%\'';
		}

		$title_search = $wp_query->get( 'wpupg_search_title' );
		if ( $title_search ) {
			$where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( like_escape( $title_search ) ) . '%\'';
		}

		$slug_search = $wp_query->get( 'wpupg_search_slug' );
		if ( $slug_search ) {
			$where .= ' AND ' . $wpdb->posts . '.post_name LIKE \'%' . esc_sql( like_escape( $slug_search ) ) . '%\'';
		}

		return $where;
	}
}

WPUPG_API_Manage_Grids::init();
