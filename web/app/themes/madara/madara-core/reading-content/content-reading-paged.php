<?php
	/** Manga Reading Content - paged Style **/

	use App\Madara;

?>

<?php
	$wp_manga_functions = madara_get_global_wp_manga_functions();
	global $wp_manga;
	$post_id  = get_the_ID();
	$name     = get_query_var( 'chapter' );
	$paged    = isset( $_GET[$wp_manga->manga_paged_var] ) ? intval( $_GET[$wp_manga->manga_paged_var] ) : 1;
	$style    = isset( $_GET['style'] ) ? $_GET['style'] : 'paged';

	// For redirecting if page is invalid
	$is_valid_page = true;
	$url_redirect  = get_the_permalink();

	$manga_reading_style = Madara::getOption( 'manga_reading_style', 'paged' );
	$preload_images      = Madara::getOption( 'manga_reading_preload_images', 'on' );

	$manga_reading_navigation_by_pointer = Madara::getOption( 'manga_reading_navigation_by_pointer', 'on' );

	if ( Madara::getOption( 'lazyload', 'off' ) == 'on' ) {
		$lazyload = 'wp-manga-chapter-img img-responsive lazyload effect-fade';
	} else {
		$lazyload = 'wp-manga-chapter-img';
	}

	if ( $name !== '' ) {
		$this_chapter = madara_get_global_wp_manga_chapter()->get_chapter_by_slug( $post_id, $name );

		if ( ! $this_chapter ) {
			return;
		}

		$chapter    = $wp_manga_functions->get_single_chapter( $post_id, $this_chapter['chapter_id'] );

		if( empty( $chapter ) ){
			$is_valid_page = false;
		}else{
			$in_use     = $chapter['storage']['inUse'];

			// Check if page is valid
			$total_pages = madara_actual_total_pages( $chapter['total_page'] );

			if( $paged > $total_pages ){
				$is_valid_page = false;
				$url_redirect = $wp_manga_functions->build_chapter_url(  get_the_ID(), $name,
				'paged', null, 1 );
			}

			$alt_host = isset( $_GET['host'] ) ? $_GET['host'] : null;
			if ( $alt_host ) {
				$in_use = $alt_host;
			}
		}

	}

	$img_per_page = intval( madara_get_img_per_page() );

	if ( $manga_reading_navigation_by_pointer == 'on' && ( $manga_reading_style == 'paged' || $style == 'paged' ) ) { ?>
		<a href="javascript:void(0)" class="page-link-hover page-prev-link"></a>
	<?php }

	if ( ! empty( $img_per_page ) && $img_per_page != '1' ) {

		$paged = $img_per_page * ( $paged - 1 ) + 1;

		$need_button_fullsize = false;

		for ( $i = 1; $i <= $img_per_page; $i ++ ) {

			if ( ! isset( $chapter['storage'][ $in_use ]['page'][ $paged ] ) ) {
				break;
			}

			$host = $chapter['storage'][ $in_use ]['host'];
			$link = $chapter['storage'][ $in_use ]['page'][ $paged ]['src'];
			$src  = $host . $link;

			$madara_reading_list_total_item = count( $chapter['storage'][ $in_use ]['page'] );
			
			if($src != ''){

			?>

			<?php do_action( 'madara_before_chapter_image', $paged, $madara_reading_list_total_item ); ?>

            <img id="image-<?php echo esc_attr( $paged ); ?>" data-image-paged="<?php echo esc_attr( $paged ); ?>" src="<?php echo esc_url( $src ); ?>" class="<?php echo esc_attr( $lazyload ); ?>">
			
			<?php 
			if(!$need_button_fullsize) {
				list($width, $height, $type, $attr) = @getimagesize($src);
				
				if(isset($width) && $width > 1140) {
					$need_button_fullsize = true;
				}
			}
			?>

			<?php do_action( 'madara_after_chapter_image', $paged, $madara_reading_list_total_item ); ?>

			<?php
			}
			$paged ++;
		}
		
		if($need_button_fullsize){ ?>
			<a href="javascript:void(0)" id="btn_view_full_image"><?php esc_html_e('View Full Size Image', 'madara');?></a>
		<?php
		}
	} else {
		$host = $chapter['storage'][ $in_use ]['host'];

		if ( ! isset( $chapter['storage'][ $in_use ]['page'][ $paged ] ) ) {
			return;
		}

		$link = $chapter['storage'][ $in_use ]['page'][ $paged ]['src'];
		$src  = $host . $link;
		?>

		<?php do_action( 'madara_before_chapter_image', $paged ); ?>

        <img id="image-<?php echo esc_attr( $paged ); ?>" data-image-paged="<?php echo esc_attr( $paged ); ?>" src="<?php echo esc_url( $src ); ?>" class="<?php echo esc_attr( $lazyload ); ?>">
		
		<?php
			
			list($width, $height, $type, $attr) = getimagesize($src);
			
			if($width > 1140) {
				?>
				<a href="javascript:void(0)" id="btn_view_full_image"><?php esc_html_e('View Full Size Image', 'madara');?></a>
				<?php
			}
			?>

		<?php do_action( 'madara_after_chapter_image', $paged ); ?>

		<?php
	}

	if ( $manga_reading_navigation_by_pointer == 'on' && ( $manga_reading_style == 'paged' || $style == 'paged' ) ) { ?>
		<a href="javascript:void(0)" class="page-link-hover page-next-link"></a>
	<?php }

	if( $preload_images == 'on' && isset( $chapter['storage'][ $in_use ]['page'] ) && is_array( $chapter['storage'][ $in_use ]['page'] ) ){

		$output_images = array();

		foreach( $chapter['storage'][ $in_use ]['page'] as $index => $page ){
			$output_images[ $index ] = $chapter['storage'][ $in_use ]['host'] . $page['src'];
		}

		?>
		<script type="text/javascript">
			var chapter_preloaded_images = <?php echo json_encode( $output_images ); ?>, chapter_images_per_page = <?php echo esc_js($img_per_page); ?>;
		</script>
		<?php
	}

	if( ! $is_valid_page ){
		?>
			<script type="text/javascript">
				window.location = "<?php echo esc_url( $url_redirect ); ?>";
			</script>
		<?php
	}
