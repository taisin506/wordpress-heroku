<?php
	/** Manga paged **/

	$name = get_query_var( 'chapter' );

	if ( $name == '' ) {
		get_template_part( 404 );
		exit();
	}

	get_header();

	use App\Madara;

	$wp_manga           = madara_get_global_wp_manga();
	$wp_manga_functions = madara_get_global_wp_manga_functions();
	$post_id  = get_the_ID();
	$paged    = ! empty( $_GET[$wp_manga->manga_paged_var] ) ? $_GET[$wp_manga->manga_paged_var] : 1;
	$style    = ! empty( $_GET['style'] ) ? $_GET['style'] : Madara::getOption( 'manga_reading_style', 'paged' );

	$chapters = $wp_manga_functions->get_chapter( get_the_ID() );
	$cur_chap = get_query_var( 'chapter' );

	$wp_manga_settings = get_option( 'wp_manga_settings' );
	$related_manga     = isset( $wp_manga_settings['related_manga'] ) ? $wp_manga_settings['related_manga'] : null;

	$madara_single_sidebar      = madara_get_theme_sidebar_setting();
	$madara_breadcrumb          = Madara::getOption( 'manga_single_breadcrumb', 'on' );
	$manga_reading_discussion   = Madara::getOption( 'manga_reading_discussion', 'on' );
	$manga_reading_social_share = Madara::getOption( 'manga_reading_social_share', 'off' );
	
	$chapter_type = get_post_meta( get_the_ID(), '_wp_manga_chapter_type', true );
	$is_text_chapter_right_sidebar = ($madara_single_sidebar != 'full' && $chapter_type == 'text' && Madara::getOption( 'manga_reading_text_sidebar', 'on' ) == 'on') ? true : false;
	
	if ( $madara_single_sidebar == 'full' || $is_text_chapter_right_sidebar ) {
		$main_col_class = 'sidebar-hidden col-12 col-sm-12 col-md-12 col-lg-12';
	} else {
		$main_col_class = 'main-col col-12 col-sm-8 col-md-8 col-lg-8';
	}

?>

    <div class="c-page-content style-1 reading-content-wrap" data-site-url="<?php echo home_url( '/' ); ?>">
        <div class="content-area">
            <div class="container">
                <div class="row">
                    <div class="main-col <?php echo $is_text_chapter_right_sidebar ? "col-md-8" : "col-md-12";?> col-sm-12 sidebar-hidden">
                        <!-- container & no-sidebar-->
                        <div class="main-col-inner">
                            <div class="c-blog-post">
                                <div class="entry-header">
									<?php $wp_manga->manga_nav( 'header' ); ?>
                                </div>
                                <div class="entry-content">
                                    <div class="entry-content_wrap">

                                        <div class="read-container">

											<?php echo apply_filters( 'madara_ads_before_content', madara_ads_position( 'ads_before_content', 'body-top-ads' ) ); ?>

                                            <div class="reading-content">

												<?php 
												
												/**
												 * If alternative_content is empty, show default content
												 **/
												$alternative_content = apply_filters('wp_manga_chapter_content_alternative', '');
												
												if(!$alternative_content){
													if ( $wp_manga->is_content_manga( get_the_ID() ) ) {
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

                                        </div>


                                    </div>
                                </div>
                            </div>
                            <div class="c-select-bottom">
								<?php $wp_manga->manga_nav( 'footer' ); ?>
                            </div>

							<?php if ( class_exists( 'APSS_Class' ) && $manga_reading_social_share == 'on' ) {

								$madara_sharing_text     = apply_filters( 'manga_reading_sharing_text', esc_html__( 'SHARE THIS MANGA', 'madara' ) );
								$madara_sharing_networks = 'facebook, twitter, google-plus, pinterest, linkedin, digg';
								$madara_sharing_networks = apply_filters( 'manga_reading_sharing_networkds', $madara_sharing_networks );
								echo do_shortcode( "[apss_share share_text='$madara_sharing_text' networks='$madara_sharing_networks' counter='1' total_counter='1' http_count='1']" );

							} ?>

							<?php if ( $manga_reading_discussion == 'on' && !$is_text_chapter_right_sidebar ) { ?>
                                <div class="row <?php echo esc_attr( $madara_single_sidebar == 'left' ? 'sidebar-left' : ''); ?>">
                                    <div class="<?php echo esc_attr( $main_col_class ); ?>">
                                        <!-- comments-area -->
										<?php do_action( 'wp_manga_discussion' ); ?>
										<!-- END comments-area -->
                                    </div>

									<?php
										if ( $madara_single_sidebar != 'full' ) {
											?>
                                            <div class="sidebar-col col-md-4 col-sm-4">
												<?php get_sidebar(); ?>
                                            </div>
										<?php }
									?>

                                </div>
							<?php } ?>

							<?php
								if ( $related_manga == 1 ) {
									get_template_part( '/madara-core/manga', 'related' );
								}

								if ( class_exists( 'WP_Manga' ) ) {
									$GLOBALS['wp_manga']->wp_manga_get_tags();
								}

							?>
                        </div>
                    </div>
					<?php
					if ( $madara_single_sidebar != 'full' && $is_text_chapter_right_sidebar ) {
						?>
						<div class="sidebar-col text-sidebar col-md-4 col-sm-12">
							<?php get_sidebar(); ?>
							
							<!-- comments-area -->
							<?php 
							if($manga_reading_discussion == 'on') 
								do_action( 'wp_manga_discussion' ); ?>
							<!-- END comments-area -->
						</div>
					<?php }
					?>
                </div>
            </div>
        </div>
    </div>

<?php

	get_footer();
