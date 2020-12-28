<?php
	/** Manga Reading Content - List Style **/

	use App\Madara;

?>

<?php
	$wp_manga_functions = madara_get_global_wp_manga_functions();
	$post_id  = get_the_ID();
	$name     = get_query_var( 'chapter' );
	global $wp_manga;
	$paged    = isset( $_GET[$wp_manga->manga_paged_var] ) ? $_GET[$wp_manga->manga_paged_var] : 1;
	$style    = isset( $_GET['style'] ) ? $_GET['style'] : 'paged';

	$manga_reading_content_gaps = Madara::getOption( 'manga_reading_content_gaps', 'on' );

	if ( Madara::getOption( 'lazyload', 'off' ) == 'on' ) {
		$lazyload = 'wp-manga-chapter-img img-responsive lazyload effect-fade';
	} else {
		$lazyload = 'wp-manga-chapter-img';
	}

	if ( $name == '' ) {
		return;
	}

	$this_chapter = madara_get_global_wp_manga_chapter()->get_chapter_by_slug( get_the_ID(), $name );

	if ( ! $this_chapter ) {
		return;
	}

	$chapter  = $wp_manga_functions->get_single_chapter( $post_id, $this_chapter['chapter_id'] );
	$in_use   = $chapter['storage']['inUse'];
	$alt_host = isset( $_GET['host'] ) ? $_GET['host'] : null;
	if ( $alt_host ) {
		$in_use = $alt_host;
	}

	if ( ! isset( $chapter['storage'][ $in_use ] ) && ! is_array( $chapter['storage'][ $in_use ]['page'] ) ) {
		return;
	}

	$madara_reading_list_total_item = 0;
	
	$need_button_fullsize = false;

	foreach ( $chapter['storage'][ $in_use ]['page'] as $page => $link ) {

		$madara_reading_list_total_item = count( $chapter['storage'][ $in_use ]['page'] );

		$host = $chapter['storage'][ $in_use ]['host'];
		$src  = $host . $link['src'];
		
		if($src != ''){

		?>

		<?php do_action( 'madara_before_chapter_image_wrapper', $page, $madara_reading_list_total_item ); ?>

        <div class="page-break <?php echo( esc_attr($manga_reading_content_gaps == 'off' ? 'no-gaps' : '' )); ?>">

			<?php do_action( 'madara_before_chapter_image', $page, $madara_reading_list_total_item ); ?>

            <img id="image-<?php echo esc_attr( $page ); ?>" src="<?php echo esc_url( $src ); ?>" class="<?php echo esc_attr( $lazyload ); ?>">
			
			<?php 
			if(!$need_button_fullsize) {
				list($width, $height, $type, $attr) = @getimagesize($src);
				
				if(isset($width) && $width > 1140) {
					$need_button_fullsize = true;
				}
			}
			?>

			<?php do_action( 'madara_after_chapter_image', $page, $madara_reading_list_total_item ); ?>
        </div>

		<?php do_action( 'madara_after_chapter_image_wrapper', $page, $madara_reading_list_total_item ); 
		
		}
	}
	
	if($need_button_fullsize){ ?>
			<a href="javascript:void(0)" id="btn_view_full_image"><?php esc_html_e('View Full Size Image', 'madara');?></a>
		<?php
		}