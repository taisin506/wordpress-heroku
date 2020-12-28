<?php
/**
* Theme Functions
*
* @package GridHub WordPress Theme
* @copyright Copyright (C) 2020 ThemesDNA
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
* @author ThemesDNA <themesdna@gmail.com>
*/

/**
* Layout functions
*/

function gridhub_hide_footer_widgets() {
    $hide_footer_widgets = FALSE;
    if ( gridhub_get_option('hide_footer_widgets') ) {
        $hide_footer_widgets = TRUE;
    }
    return apply_filters( 'gridhub_hide_footer_widgets', $hide_footer_widgets );
}

function gridhub_is_header_content_active() {
    $header_content_active = TRUE;
    if ( gridhub_get_option('hide_header_content') ) {
        $header_content_active = FALSE;
    }
    return apply_filters( 'gridhub_is_header_content_active', $header_content_active );
}

function gridhub_is_primary_menu_active() {
    $primary_menu_active = TRUE;
    if ( gridhub_get_option('disable_primary_menu') ) {
        $primary_menu_active = FALSE;
    }
    return apply_filters( 'gridhub_is_primary_menu_active', $primary_menu_active );
}

function gridhub_is_secondary_menu_active() {
    $secondary_menu_active = TRUE;
    if ( gridhub_get_option('disable_secondary_menu') ) {
        $secondary_menu_active = FALSE;
    }
    return apply_filters( 'gridhub_is_secondary_menu_active', $secondary_menu_active );
}

function gridhub_social_buttons_location() {
    $social_buttons_location = 'secondary-menu';
    if ( gridhub_get_option('social_buttons_location') ) {
        $social_buttons_location = gridhub_get_option('social_buttons_location');
    }
    return apply_filters( 'gridhub_social_buttons_location', $social_buttons_location );
}

function gridhub_is_social_buttons_active() {
    $social_buttons_active = TRUE;
    if ( gridhub_get_option('hide_social_buttons') ) {
        $social_buttons_active = FALSE;
    }
    return apply_filters( 'gridhub_is_social_buttons_active', $social_buttons_active );
}

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function gridhub_content_width() {
    $content_width = 936;

    if ( is_singular() ) {
        if ( is_page_template( array( 'template-full-width-page.php', 'template-full-width-post.php' ) ) ) {
           $content_width = 1292;
        }
    } else {
        if ( is_404() ) {
            $content_width = 1292;
        }
    }

    $GLOBALS['content_width'] = apply_filters( 'gridhub_content_width', $content_width ); /* phpcs:ignore WPThemeReview.CoreFunctionality.PrefixAllGlobals.NonPrefixedVariableFound */
}
add_action( 'template_redirect', 'gridhub_content_width', 0 );


/**
* Register widget areas.
*
* @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
*/

function gridhub_widgets_init() {

register_sidebar(array(
    'id' => 'gridhub-sidebar-one',
    'name' => esc_html__( 'Sidebar 1 Widgets', 'gridhub' ),
    'description' => esc_html__( 'This widget area is located on the left-hand side of your web page.', 'gridhub' ),
    'before_widget' => '<div id="%1$s" class="gridhub-side-widget widget gridhub-widget-box %2$s"><div class="gridhub-widget-box-inside">',
    'after_widget' => '</div></div>',
    'before_title' => '<div class="gridhub-widget-header"><div class="gridhub-widget-header-inside"><h2 class="gridhub-widget-title"><span class="gridhub-widget-title-inside">',
    'after_title' => '</span></h2></div></div>'));

register_sidebar(array(
    'id' => 'gridhub-home-fullwidth-widgets',
    'name' => esc_html__( 'Top Full Width Widgets (Default HomePage)', 'gridhub' ),
    'description' => esc_html__( 'This full-width widget area is located after the header of your website. Widgets of this widget area are displayed on the default homepage of your website (when you are showing your latest posts on homepage).', 'gridhub' ),
    'before_widget' => '<div id="%1$s" class="gridhub-main-widget widget gridhub-widget-box %2$s"><div class="gridhub-widget-box-inside">',
    'after_widget' => '</div></div>',
    'before_title' => '<div class="gridhub-widget-header"><div class="gridhub-widget-header-inside"><h2 class="gridhub-widget-title"><span class="gridhub-widget-title-inside">',
    'after_title' => '</span></h2></div></div>'));

register_sidebar(array(
    'id' => 'gridhub-fullwidth-widgets',
    'name' => esc_html__( 'Top Full Width Widgets (Everywhere)', 'gridhub' ),
    'description' => esc_html__( 'This full-width widget area is located after the header of your website. Widgets of this widget area are displayed on every page of your website.', 'gridhub' ),
    'before_widget' => '<div id="%1$s" class="gridhub-main-widget widget gridhub-widget-box %2$s"><div class="gridhub-widget-box-inside">',
    'after_widget' => '</div></div>',
    'before_title' => '<div class="gridhub-widget-header"><div class="gridhub-widget-header-inside"><h2 class="gridhub-widget-title"><span class="gridhub-widget-title-inside">',
    'after_title' => '</span></h2></div></div>'));

register_sidebar(array(
    'id' => 'gridhub-home-top-widgets',
    'name' => esc_html__( 'Above Content Widgets (Default HomePage)', 'gridhub' ),
    'description' => esc_html__( 'This widget area is located at the top of the main content (above posts) of your website. Widgets of this widget area are displayed on the default homepage of your website (when you are showing your latest posts on homepage).', 'gridhub' ),
    'before_widget' => '<div id="%1$s" class="gridhub-main-widget widget gridhub-widget-box %2$s"><div class="gridhub-widget-box-inside">',
    'after_widget' => '</div></div>',
    'before_title' => '<div class="gridhub-widget-header"><div class="gridhub-widget-header-inside"><h2 class="gridhub-widget-title"><span class="gridhub-widget-title-inside">',
    'after_title' => '</span></h2></div></div>'));

register_sidebar(array(
    'id' => 'gridhub-top-widgets',
    'name' => esc_html__( 'Above Content Widgets (Everywhere)', 'gridhub' ),
    'description' => esc_html__( 'This widget area is located at the top of the main content (above posts) of your website. Widgets of this widget area are displayed on every page of your website.', 'gridhub' ),
    'before_widget' => '<div id="%1$s" class="gridhub-main-widget widget gridhub-widget-box %2$s"><div class="gridhub-widget-box-inside">',
    'after_widget' => '</div></div>',
    'before_title' => '<div class="gridhub-widget-header"><div class="gridhub-widget-header-inside"><h2 class="gridhub-widget-title"><span class="gridhub-widget-title-inside">',
    'after_title' => '</span></h2></div></div>'));

register_sidebar(array(
    'id' => 'gridhub-home-bottom-widgets',
    'name' => esc_html__( 'Below Content Widgets (Default HomePage)', 'gridhub' ),
    'description' => esc_html__( 'This widget area is located at the bottom of the main content (below posts) of your website. Widgets of this widget area are displayed on the default homepage of your website (when you are showing your latest posts on homepage).', 'gridhub' ),
    'before_widget' => '<div id="%1$s" class="gridhub-main-widget widget gridhub-widget-box %2$s"><div class="gridhub-widget-box-inside">',
    'after_widget' => '</div></div>',
    'before_title' => '<div class="gridhub-widget-header"><div class="gridhub-widget-header-inside"><h2 class="gridhub-widget-title"><span class="gridhub-widget-title-inside">',
    'after_title' => '</span></h2></div></div>'));

register_sidebar(array(
    'id' => 'gridhub-bottom-widgets',
    'name' => esc_html__( 'Below Content Widgets (Everywhere)', 'gridhub' ),
    'description' => esc_html__( 'This widget area is located at the bottom of the main content (below posts) of your website. Widgets of this widget area are displayed on every page of your website.', 'gridhub' ),
    'before_widget' => '<div id="%1$s" class="gridhub-main-widget widget gridhub-widget-box %2$s"><div class="gridhub-widget-box-inside">',
    'after_widget' => '</div></div>',
    'before_title' => '<div class="gridhub-widget-header"><div class="gridhub-widget-header-inside"><h2 class="gridhub-widget-title"><span class="gridhub-widget-title-inside">',
    'after_title' => '</span></h2></div></div>'));

register_sidebar(array(
    'id' => 'gridhub-home-fullwidth-bottom-widgets',
    'name' => esc_html__( 'Bottom Full Width Widgets (Default HomePage)', 'gridhub' ),
    'description' => esc_html__( 'This full-width widget area is located before the footer of your website. Widgets of this widget area are displayed on the default homepage of your website (when you are showing your latest posts on homepage).', 'gridhub' ),
    'before_widget' => '<div id="%1$s" class="gridhub-main-widget widget gridhub-widget-box %2$s"><div class="gridhub-widget-box-inside">',
    'after_widget' => '</div></div>',
    'before_title' => '<div class="gridhub-widget-header"><div class="gridhub-widget-header-inside"><h2 class="gridhub-widget-title"><span class="gridhub-widget-title-inside">',
    'after_title' => '</span></h2></div></div>'));

register_sidebar(array(
    'id' => 'gridhub-fullwidth-bottom-widgets',
    'name' => esc_html__( 'Bottom Full Width Widgets (Everywhere)', 'gridhub' ),
    'description' => esc_html__( 'This full-width widget area is located before the footer of your website. Widgets of this widget area are displayed on every page of your website.', 'gridhub' ),
    'before_widget' => '<div id="%1$s" class="gridhub-main-widget widget gridhub-widget-box %2$s"><div class="gridhub-widget-box-inside">',
    'after_widget' => '</div></div>',
    'before_title' => '<div class="gridhub-widget-header"><div class="gridhub-widget-header-inside"><h2 class="gridhub-widget-title"><span class="gridhub-widget-title-inside">',
    'after_title' => '</span></h2></div></div>'));

register_sidebar(array(
    'id' => 'gridhub-single-post-bottom-widgets',
    'name' => esc_html__( 'Single Post Bottom Widgets', 'gridhub' ),
    'description' => esc_html__( 'This widget area is located at the bottom of single post of any post type (except attachments and pages).', 'gridhub' ),
    'before_widget' => '<div id="%1$s" class="gridhub-main-widget widget gridhub-widget-box %2$s"><div class="gridhub-widget-box-inside">',
    'after_widget' => '</div></div>',
    'before_title' => '<div class="gridhub-widget-header"><div class="gridhub-widget-header-inside"><h2 class="gridhub-widget-title"><span class="gridhub-widget-title-inside">',
    'after_title' => '</span></h2></div></div>'));

register_sidebar(array(
    'id' => 'gridhub-top-footer',
    'name' => esc_html__( 'Footer Top Widgets', 'gridhub' ),
    'description' => esc_html__( 'This widget area is located on the top of the footer of your website.', 'gridhub' ),
    'before_widget' => '<div id="%1$s" class="gridhub-footer-widget widget %2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h2 class="gridhub-widget-title"><span class="gridhub-widget-title-inside">',
    'after_title' => '</span></h2>'));

register_sidebar(array(
    'id' => 'gridhub-footer-1',
    'name' => esc_html__( 'Footer 1 Widgets', 'gridhub' ),
    'description' => esc_html__( 'This widget area is the column 1 of the footer of your website.', 'gridhub' ),
    'before_widget' => '<div id="%1$s" class="gridhub-footer-widget widget %2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h2 class="gridhub-widget-title"><span class="gridhub-widget-title-inside">',
    'after_title' => '</span></h2>'));

register_sidebar(array(
    'id' => 'gridhub-footer-2',
    'name' => esc_html__( 'Footer 2 Widgets', 'gridhub' ),
    'description' => esc_html__( 'This widget area is the column 2 of the footer of your website.', 'gridhub' ),
    'before_widget' => '<div id="%1$s" class="gridhub-footer-widget widget %2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h2 class="gridhub-widget-title"><span class="gridhub-widget-title-inside">',
    'after_title' => '</span></h2>'));

register_sidebar(array(
    'id' => 'gridhub-footer-3',
    'name' => esc_html__( 'Footer 3 Widgets', 'gridhub' ),
    'description' => esc_html__( 'This widget area is the column 3 of the footer of your website.', 'gridhub' ),
    'before_widget' => '<div id="%1$s" class="gridhub-footer-widget widget %2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h2 class="gridhub-widget-title"><span class="gridhub-widget-title-inside">',
    'after_title' => '</span></h2>'));

register_sidebar(array(
    'id' => 'gridhub-footer-4',
    'name' => esc_html__( 'Footer 4 Widgets', 'gridhub' ),
    'description' => esc_html__( 'This widget area is the column 4 of the footer of your website.', 'gridhub' ),
    'before_widget' => '<div id="%1$s" class="gridhub-footer-widget widget %2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h2 class="gridhub-widget-title"><span class="gridhub-widget-title-inside">',
    'after_title' => '</span></h2>'));

register_sidebar(array(
    'id' => 'gridhub-footer-5',
    'name' => esc_html__( 'Footer 5 Widgets', 'gridhub' ),
    'description' => esc_html__( 'This widget area is the column 5 of the footer of your website.', 'gridhub' ),
    'before_widget' => '<div id="%1$s" class="gridhub-footer-widget widget %2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h2 class="gridhub-widget-title"><span class="gridhub-widget-title-inside">',
    'after_title' => '</span></h2>'));

register_sidebar(array(
    'id' => 'gridhub-footer-6',
    'name' => esc_html__( 'Footer 6 Widgets', 'gridhub' ),
    'description' => esc_html__( 'This widget area is the column 6 of the footer of your website.', 'gridhub' ),
    'before_widget' => '<div id="%1$s" class="gridhub-footer-widget widget %2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h2 class="gridhub-widget-title"><span class="gridhub-widget-title-inside">',
    'after_title' => '</span></h2>'));

register_sidebar(array(
    'id' => 'gridhub-bottom-footer',
    'name' => esc_html__( 'Footer Bottom Widgets', 'gridhub' ),
    'description' => esc_html__( 'This widget area is located on the bottom of the footer of your website.', 'gridhub' ),
    'before_widget' => '<div id="%1$s" class="gridhub-footer-widget widget %2$s">',
    'after_widget' => '</div>',
    'before_title' => '<h2 class="gridhub-widget-title"><span class="gridhub-widget-title-inside">',
    'after_title' => '</span></h2>'));

}
add_action( 'widgets_init', 'gridhub_widgets_init' );


function gridhub_sidebar_one_widgets() {
    dynamic_sidebar( 'gridhub-sidebar-one' );
}

function gridhub_top_wide_widgets() { ?>

<?php if ( is_active_sidebar( 'gridhub-home-fullwidth-widgets' ) || is_active_sidebar( 'gridhub-fullwidth-widgets' ) ) : ?>
<div class="gridhub-outer-wrapper">
<div class="gridhub-top-wrapper-outer gridhub-clearfix">
<div class="gridhub-featured-posts-area gridhub-top-wrapper gridhub-clearfix">
<?php if ( is_front_page() && is_home() && !is_paged() ) { ?>
<?php dynamic_sidebar( 'gridhub-home-fullwidth-widgets' ); ?>
<?php } ?>

<?php dynamic_sidebar( 'gridhub-fullwidth-widgets' ); ?>
</div>
</div>
</div>
<?php endif; ?>

<?php }


function gridhub_bottom_wide_widgets() { ?>

<?php if ( is_active_sidebar( 'gridhub-home-fullwidth-bottom-widgets' ) || is_active_sidebar( 'gridhub-fullwidth-bottom-widgets' ) ) : ?>
<div class="gridhub-outer-wrapper">
<div class="gridhub-bottom-wrapper-outer gridhub-clearfix">
<div class="gridhub-featured-posts-area gridhub-bottom-wrapper gridhub-clearfix">
<?php if ( is_front_page() && is_home() && !is_paged() ) { ?>
<?php dynamic_sidebar( 'gridhub-home-fullwidth-bottom-widgets' ); ?>
<?php } ?>

<?php dynamic_sidebar( 'gridhub-fullwidth-bottom-widgets' ); ?>
</div>
</div>
</div>
<?php endif; ?>

<?php }


function gridhub_top_widgets() { ?>

<?php if ( is_active_sidebar( 'gridhub-home-top-widgets' ) || is_active_sidebar( 'gridhub-top-widgets' ) ) : ?>
<div class="gridhub-featured-posts-area gridhub-featured-posts-area-top gridhub-clearfix">
<?php if ( is_front_page() && is_home() && !is_paged() ) { ?>
<?php dynamic_sidebar( 'gridhub-home-top-widgets' ); ?>
<?php } ?>

<?php dynamic_sidebar( 'gridhub-top-widgets' ); ?>
</div>
<?php endif; ?>

<?php }


function gridhub_bottom_widgets() { ?>

<?php if ( is_active_sidebar( 'gridhub-home-bottom-widgets' ) || is_active_sidebar( 'gridhub-bottom-widgets' ) ) : ?>
<div class='gridhub-featured-posts-area gridhub-featured-posts-area-bottom gridhub-clearfix'>
<?php if ( is_front_page() && is_home() && !is_paged() ) { ?>
<?php dynamic_sidebar( 'gridhub-home-bottom-widgets' ); ?>
<?php } ?>

<?php dynamic_sidebar( 'gridhub-bottom-widgets' ); ?>
</div>
<?php endif; ?>

<?php }


function gridhub_post_bottom_widgets() {
    if ( is_singular() ) {
        if ( is_active_sidebar( 'gridhub-single-post-bottom-widgets' ) ) : ?>
            <div class="gridhub-featured-posts-area gridhub-clearfix">
            <?php dynamic_sidebar( 'gridhub-single-post-bottom-widgets' ); ?>
            </div>
        <?php endif;
    }
}


/**
* Header social buttons
*/

function gridhub_header_social_buttons() { ?>

<div class='gridhub-social-icons'>
    <?php if ( gridhub_get_option('twitterlink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('twitterlink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-twitter" aria-label="<?php esc_attr_e('Twitter Button','gridhub'); ?>"><i class="fab fa-twitter" aria-hidden="true" title="<?php esc_attr_e('Twitter','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('facebooklink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('facebooklink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-facebook" aria-label="<?php esc_attr_e('Facebook Button','gridhub'); ?>"><i class="fab fa-facebook-f" aria-hidden="true" title="<?php esc_attr_e('Facebook','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('googlelink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('googlelink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-google-plus" aria-label="<?php esc_attr_e('Google Plus Button','gridhub'); ?>"><i class="fab fa-google-plus-g" aria-hidden="true" title="<?php esc_attr_e('Google Plus','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('pinterestlink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('pinterestlink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-pinterest" aria-label="<?php esc_attr_e('Pinterest Button','gridhub'); ?>"><i class="fab fa-pinterest" aria-hidden="true" title="<?php esc_attr_e('Pinterest','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('linkedinlink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('linkedinlink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-linkedin" aria-label="<?php esc_attr_e('Linkedin Button','gridhub'); ?>"><i class="fab fa-linkedin-in" aria-hidden="true" title="<?php esc_attr_e('Linkedin','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('instagramlink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('instagramlink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-instagram" aria-label="<?php esc_attr_e('Instagram Button','gridhub'); ?>"><i class="fab fa-instagram" aria-hidden="true" title="<?php esc_attr_e('Instagram','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('flickrlink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('flickrlink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-flickr" aria-label="<?php esc_attr_e('Flickr Button','gridhub'); ?>"><i class="fab fa-flickr" aria-hidden="true" title="<?php esc_attr_e('Flickr','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('youtubelink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('youtubelink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-youtube" aria-label="<?php esc_attr_e('Youtube Button','gridhub'); ?>"><i class="fab fa-youtube" aria-hidden="true" title="<?php esc_attr_e('Youtube','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('vimeolink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('vimeolink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-vimeo" aria-label="<?php esc_attr_e('Vimeo Button','gridhub'); ?>"><i class="fab fa-vimeo-v" aria-hidden="true" title="<?php esc_attr_e('Vimeo','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('soundcloudlink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('soundcloudlink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-soundcloud" aria-label="<?php esc_attr_e('SoundCloud Button','gridhub'); ?>"><i class="fab fa-soundcloud" aria-hidden="true" title="<?php esc_attr_e('SoundCloud','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('messengerlink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('messengerlink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-messenger" aria-label="<?php esc_attr_e('Messenger Button','gridhub'); ?>"><i class="fab fa-facebook-messenger" aria-hidden="true" title="<?php esc_attr_e('Messenger','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('lastfmlink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('lastfmlink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-lastfm" aria-label="<?php esc_attr_e('Lastfm Button','gridhub'); ?>"><i class="fab fa-lastfm" aria-hidden="true" title="<?php esc_attr_e('Lastfm','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('mediumlink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('mediumlink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-medium" aria-label="<?php esc_attr_e('Medium Button','gridhub'); ?>"><i class="fab fa-medium-m" aria-hidden="true" title="<?php esc_attr_e('Medium','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('githublink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('githublink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-github" aria-label="<?php esc_attr_e('Github Button','gridhub'); ?>"><i class="fab fa-github" aria-hidden="true" title="<?php esc_attr_e('Github','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('bitbucketlink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('bitbucketlink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-bitbucket" aria-label="<?php esc_attr_e('Bitbucket Button','gridhub'); ?>"><i class="fab fa-bitbucket" aria-hidden="true" title="<?php esc_attr_e('Bitbucket','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('tumblrlink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('tumblrlink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-tumblr" aria-label="<?php esc_attr_e('Tumblr Button','gridhub'); ?>"><i class="fab fa-tumblr" aria-hidden="true" title="<?php esc_attr_e('Tumblr','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('digglink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('digglink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-digg" aria-label="<?php esc_attr_e('Digg Button','gridhub'); ?>"><i class="fab fa-digg" aria-hidden="true" title="<?php esc_attr_e('Digg','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('deliciouslink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('deliciouslink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-delicious" aria-label="<?php esc_attr_e('Delicious Button','gridhub'); ?>"><i class="fab fa-delicious" aria-hidden="true" title="<?php esc_attr_e('Delicious','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('stumblelink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('stumblelink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-stumbleupon" aria-label="<?php esc_attr_e('Stumbleupon Button','gridhub'); ?>"><i class="fab fa-stumbleupon" aria-hidden="true" title="<?php esc_attr_e('Stumbleupon','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('mixlink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('mixlink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-mix" aria-label="<?php esc_attr_e('Mix Button','gridhub'); ?>"><i class="fab fa-mix" aria-hidden="true" title="<?php esc_attr_e('Mix','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('redditlink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('redditlink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-reddit" aria-label="<?php esc_attr_e('Reddit Button','gridhub'); ?>"><i class="fab fa-reddit" aria-hidden="true" title="<?php esc_attr_e('Reddit','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('dribbblelink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('dribbblelink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-dribbble" aria-label="<?php esc_attr_e('Dribbble Button','gridhub'); ?>"><i class="fab fa-dribbble" aria-hidden="true" title="<?php esc_attr_e('Dribbble','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('flipboardlink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('flipboardlink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-flipboard" aria-label="<?php esc_attr_e('Flipboard Button','gridhub'); ?>"><i class="fab fa-flipboard" aria-hidden="true" title="<?php esc_attr_e('Flipboard','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('bloggerlink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('bloggerlink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-blogger" aria-label="<?php esc_attr_e('Blogger Button','gridhub'); ?>"><i class="fab fa-blogger" aria-hidden="true" title="<?php esc_attr_e('Blogger','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('etsylink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('etsylink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-etsy" aria-label="<?php esc_attr_e('Etsy Button','gridhub'); ?>"><i class="fab fa-etsy" aria-hidden="true" title="<?php esc_attr_e('Etsy','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('behancelink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('behancelink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-behance" aria-label="<?php esc_attr_e('Behance Button','gridhub'); ?>"><i class="fab fa-behance" aria-hidden="true" title="<?php esc_attr_e('Behance','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('amazonlink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('amazonlink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-amazon" aria-label="<?php esc_attr_e('Amazon Button','gridhub'); ?>"><i class="fab fa-amazon" aria-hidden="true" title="<?php esc_attr_e('Amazon','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('meetuplink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('meetuplink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-meetup" aria-label="<?php esc_attr_e('Meetup Button','gridhub'); ?>"><i class="fab fa-meetup" aria-hidden="true" title="<?php esc_attr_e('Meetup','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('mixcloudlink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('mixcloudlink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-mixcloud" aria-label="<?php esc_attr_e('Mixcloud Button','gridhub'); ?>"><i class="fab fa-mixcloud" aria-hidden="true" title="<?php esc_attr_e('Mixcloud','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('slacklink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('slacklink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-slack" aria-label="<?php esc_attr_e('Slack Button','gridhub'); ?>"><i class="fab fa-slack" aria-hidden="true" title="<?php esc_attr_e('Slack','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('snapchatlink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('snapchatlink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-snapchat" aria-label="<?php esc_attr_e('Snapchat Button','gridhub'); ?>"><i class="fab fa-snapchat" aria-hidden="true" title="<?php esc_attr_e('Snapchat','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('spotifylink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('spotifylink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-spotify" aria-label="<?php esc_attr_e('Spotify Button','gridhub'); ?>"><i class="fab fa-spotify" aria-hidden="true" title="<?php esc_attr_e('Spotify','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('yelplink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('yelplink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-yelp" aria-label="<?php esc_attr_e('Yelp Button','gridhub'); ?>"><i class="fab fa-yelp" aria-hidden="true" title="<?php esc_attr_e('Yelp','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('wordpresslink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('wordpresslink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-wordpress" aria-label="<?php esc_attr_e('WordPress Button','gridhub'); ?>"><i class="fab fa-wordpress" aria-hidden="true" title="<?php esc_attr_e('WordPress','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('twitchlink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('twitchlink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-twitch" aria-label="<?php esc_attr_e('Twitch Button','gridhub'); ?>"><i class="fab fa-twitch" aria-hidden="true" title="<?php esc_attr_e('Twitch','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('telegramlink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('telegramlink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-telegram" aria-label="<?php esc_attr_e('Telegram Button','gridhub'); ?>"><i class="fab fa-telegram" aria-hidden="true" title="<?php esc_attr_e('Telegram','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('bandcamplink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('bandcamplink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-bandcamp" aria-label="<?php esc_attr_e('Bandcamp Button','gridhub'); ?>"><i class="fab fa-bandcamp" aria-hidden="true" title="<?php esc_attr_e('Bandcamp','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('quoralink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('quoralink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-quora" aria-label="<?php esc_attr_e('Quora Button','gridhub'); ?>"><i class="fab fa-quora" aria-hidden="true" title="<?php esc_attr_e('Quora','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('foursquarelink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('foursquarelink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-foursquare" aria-label="<?php esc_attr_e('Foursquare Button','gridhub'); ?>"><i class="fab fa-foursquare" aria-hidden="true" title="<?php esc_attr_e('Foursquare','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('deviantartlink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('deviantartlink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-deviantart" aria-label="<?php esc_attr_e('DeviantArt Button','gridhub'); ?>"><i class="fab fa-deviantart" aria-hidden="true" title="<?php esc_attr_e('DeviantArt','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('imdblink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('imdblink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-imdb" aria-label="<?php esc_attr_e('IMDB Button','gridhub'); ?>"><i class="fab fa-imdb" aria-hidden="true" title="<?php esc_attr_e('IMDB','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('vklink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('vklink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-vk" aria-label="<?php esc_attr_e('VK Button','gridhub'); ?>"><i class="fab fa-vk" aria-hidden="true" title="<?php esc_attr_e('VK','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('codepenlink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('codepenlink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-codepen" aria-label="<?php esc_attr_e('Codepen Button','gridhub'); ?>"><i class="fab fa-codepen" aria-hidden="true" title="<?php esc_attr_e('Codepen','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('jsfiddlelink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('jsfiddlelink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-jsfiddle" aria-label="<?php esc_attr_e('JSFiddle Button','gridhub'); ?>"><i class="fab fa-jsfiddle" aria-hidden="true" title="<?php esc_attr_e('JSFiddle','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('stackoverflowlink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('stackoverflowlink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-stackoverflow" aria-label="<?php esc_attr_e('Stack Overflow Button','gridhub'); ?>"><i class="fab fa-stack-overflow" aria-hidden="true" title="<?php esc_attr_e('Stack Overflow','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('stackexchangelink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('stackexchangelink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-stackexchange" aria-label="<?php esc_attr_e('Stack Exchange Button','gridhub'); ?>"><i class="fab fa-stack-exchange" aria-hidden="true" title="<?php esc_attr_e('Stack Exchange','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('bsalink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('bsalink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-buysellads" aria-label="<?php esc_attr_e('BuySellAds Button','gridhub'); ?>"><i class="fab fa-buysellads" aria-hidden="true" title="<?php esc_attr_e('BuySellAds','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('web500pxlink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('web500pxlink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-web500px" aria-label="<?php esc_attr_e('500px Button','gridhub'); ?>"><i class="fab fa-500px" aria-hidden="true" title="<?php esc_attr_e('500px','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('ellolink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('ellolink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-ello" aria-label="<?php esc_attr_e('Ello Button','gridhub'); ?>"><i class="fab fa-ello" aria-hidden="true" title="<?php esc_attr_e('Ello','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('goodreadslink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('goodreadslink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-goodreads" aria-label="<?php esc_attr_e('Goodreads Button','gridhub'); ?>"><i class="fab fa-goodreads" aria-hidden="true" title="<?php esc_attr_e('Goodreads','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('odnoklassnikilink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('odnoklassnikilink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-odnoklassniki" aria-label="<?php esc_attr_e('Odnoklassniki Button','gridhub'); ?>"><i class="fab fa-odnoklassniki" aria-hidden="true" title="<?php esc_attr_e('Odnoklassniki','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('houzzlink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('houzzlink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-houzz" aria-label="<?php esc_attr_e('Houzz Button','gridhub'); ?>"><i class="fab fa-houzz" aria-hidden="true" title="<?php esc_attr_e('Houzz','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('pocketlink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('pocketlink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-pocket" aria-label="<?php esc_attr_e('Pocket Button','gridhub'); ?>"><i class="fab fa-get-pocket" aria-hidden="true" title="<?php esc_attr_e('Pocket','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('xinglink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('xinglink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-xing" aria-label="<?php esc_attr_e('XING Button','gridhub'); ?>"><i class="fab fa-xing" aria-hidden="true" title="<?php esc_attr_e('XING','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('googleplaylink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('googleplaylink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-googleplay" aria-label="<?php esc_attr_e('Google Play Button','gridhub'); ?>"><i class="fab fa-google-play" aria-hidden="true" title="<?php esc_attr_e('Google Play','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('slidesharelink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('slidesharelink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-slideshare" aria-label="<?php esc_attr_e('SlideShare Button','gridhub'); ?>"><i class="fab fa-slideshare" aria-hidden="true" title="<?php esc_attr_e('SlideShare','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('dropboxlink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('dropboxlink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-dropbox" aria-label="<?php esc_attr_e('Dropbox Button','gridhub'); ?>"><i class="fab fa-dropbox" aria-hidden="true" title="<?php esc_attr_e('Dropbox','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('paypallink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('paypallink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-paypal" aria-label="<?php esc_attr_e('PayPal Button','gridhub'); ?>"><i class="fab fa-paypal" aria-hidden="true" title="<?php esc_attr_e('PayPal','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('viadeolink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('viadeolink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-viadeo" aria-label="<?php esc_attr_e('Viadeo Button','gridhub'); ?>"><i class="fab fa-viadeo" aria-hidden="true" title="<?php esc_attr_e('Viadeo','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('wikipedialink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('wikipedialink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-wikipedia" aria-label="<?php esc_attr_e('Wikipedia Button','gridhub'); ?>"><i class="fab fa-wikipedia-w" aria-hidden="true" title="<?php esc_attr_e('Wikipedia','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('skypeusername') ) : ?>
            <a href="skype:<?php echo esc_html( gridhub_get_option('skypeusername') ); ?>?chat" class="gridhub-social-icon-skype" aria-label="<?php esc_attr_e('Skype Button','gridhub'); ?>"><i class="fab fa-skype" aria-hidden="true" title="<?php esc_attr_e('Skype','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('emailaddress') ) : ?>
            <a href="mailto:<?php echo esc_html( gridhub_get_option('emailaddress') ); ?>" class="gridhub-social-icon-email" aria-label="<?php esc_attr_e('Email Us Button','gridhub'); ?>"><i class="far fa-envelope" aria-hidden="true" title="<?php esc_attr_e('Email Us','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('rsslink') ) : ?>
            <a href="<?php echo esc_url( gridhub_get_option('rsslink') ); ?>" target="_blank" rel="nofollow" class="gridhub-social-icon-rss" aria-label="<?php esc_attr_e('RSS Button','gridhub'); ?>"><i class="fas fa-rss" aria-hidden="true" title="<?php esc_attr_e('RSS','gridhub'); ?>"></i></a><?php endif; ?>
    <?php if ( gridhub_get_option('show_login_button') ) { ?><?php if (is_user_logged_in()) : ?><a href="<?php echo esc_url( wp_logout_url( get_permalink() ) ); ?>" class="gridhub-social-icon-login" aria-label="<?php esc_attr_e( 'Logout Button', 'gridhub' ); ?>"><i class="fas fa-sign-out-alt" aria-hidden="true" title="<?php esc_attr_e( 'Logout Button', 'gridhub' ); ?>"></i></a><?php else : ?><a href="<?php echo esc_url( wp_login_url( get_permalink() ) ); ?>" class="gridhub-social-icon-login" aria-label="<?php esc_attr_e( 'Login / Register', 'gridhub' ); ?>"><i class="fas fa-sign-in-alt" aria-hidden="true" title="<?php esc_attr_e( 'Login / Register Button', 'gridhub' ); ?>"></i></a><?php endif;?><?php } ?>
    <?php if ( !(gridhub_get_option('hide_search_button')) ) { ?><a href="<?php echo esc_url( '#' ); ?>" class="gridhub-social-icon-search" aria-label="<?php esc_attr_e('Search Button','gridhub'); ?>"><i class="fas fa-search" aria-hidden="true" title="<?php esc_attr_e('Search','gridhub'); ?>"></i></a><?php } ?>
</div>

<?php }


/**
* Author bio box
*/

function gridhub_add_author_bio_box() {
    $content='';
    if (is_single()) {
        $content .= '
            <div class="gridhub-author-bio">
            <div class="gridhub-author-bio-inside">
            <div class="gridhub-author-bio-top">
            <span class="gridhub-author-bio-gravatar">
                '. get_avatar( get_the_author_meta('email') , 80 ) .'
            </span>
            <div class="gridhub-author-bio-text">
                <div class="gridhub-author-bio-name">'.esc_html__( 'Author: ', 'gridhub' ).'<span>'. get_the_author_link() .'</span></div><div class="gridhub-author-bio-text-description">'. wp_kses_post( get_the_author_meta('description',get_query_var('author') ) ) .'</div>
            </div>
            </div>
            </div>
            </div>
        ';
    }
    return apply_filters( 'gridhub_add_author_bio_box', $content );
}


/**
* Post meta functions
*/

if ( ! function_exists( 'gridhub_post_tags' ) ) :
/**
 * Prints HTML with meta information for the tags.
 */
function gridhub_post_tags() {
    if ( 'post' == get_post_type() ) {
        /* translators: used between list items, there is a space after the comma */
        $tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'gridhub' ) );
        if ( $tags_list ) {
            /* translators: 1: list of tags. */
            printf( '<span class="gridhub-entry-meta-single-tags"><i class="fas fa-tags" aria-hidden="true"></i> ' . esc_html__( 'Tagged %1$s', 'gridhub' ) . '</span>', $tags_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }
    }
}
endif;


if ( ! function_exists( 'gridhub_author_image' ) ) :
function gridhub_author_image() {
    global $post;
    $gravatar_size = 32;
    $author_email   = get_the_author_meta( 'user_email' );
    $gravatar_args  = apply_filters(
        'gridhub_gravatar_args',
        array(
            'size' => $gravatar_size,
        )
    );

    $avatar_url = get_avatar_url( $author_email, $gravatar_args );

    if ( gridhub_get_option('author_image_link') ) {
        $avatar_markup  = '<a href="'.esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ).'" title="'.esc_attr( get_the_author() ).'"><img class="gridhub-grid-post-author-image" alt="' . esc_attr( get_the_author() ) . '" src="' . esc_url( $avatar_url ) . '" /></a>';
    } else {
        $avatar_markup  = '<img class="gridhub-grid-post-author-image" alt="' . esc_attr( get_the_author() ) . '" src="' . esc_url( $avatar_url ) . '" />';
    }
    return apply_filters( 'gridhub_author_image', $avatar_markup );
}
endif;


if ( ! function_exists( 'gridhub_grid_postmeta' ) ) :
function gridhub_grid_postmeta() { ?>
    <?php global $post; ?>
    <?php if ( !(gridhub_get_option('hide_post_author_home')) || !(gridhub_get_option('hide_posted_date_home')) ) { ?>
    <div class="gridhub-grid-post-footer gridhub-grid-post-block">
    <div class="gridhub-grid-post-footer-inside">
    <?php if ( !(gridhub_get_option('hide_post_author_home')) ) { ?><span class="gridhub-grid-post-author gridhub-grid-post-meta"><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php echo esc_html( get_the_author() ); ?></a></span><?php } ?>
    <?php if ( !(gridhub_get_option('hide_posted_date_home')) ) { ?><span class="gridhub-grid-post-date gridhub-grid-post-meta"><?php echo esc_html( get_the_date() ); ?></span><?php } ?>
    </div>
    </div>
    <?php } ?>
<?php }
endif;


if ( ! function_exists( 'gridhub_grid_postfooter' ) ) :
 /**
  * Prints HTML with meta information for the categories, tags and comments.
  */
function gridhub_grid_postfooter() {
    global $post;
    if ( !(gridhub_get_option('hide_post_categories_home')) || !(gridhub_get_option('hide_post_tags_home')) || !(gridhub_get_option('hide_comments_link_home')) ) { ?>
        <div class="gridhub-grid-post-bottom gridhub-clearfix">
        <div class="gridhub-grid-post-bottom-inside gridhub-clearfix">
        <?php
        if ( !(gridhub_get_option('hide_comments_link_home')) ) {
            if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
                echo '<span class="gridhub-grid-post-bottom-comments-link gridhub-grid-post-bottom-meta"><i class="fas fa-comments" aria-hidden="true"></i>&nbsp;';
                comments_popup_link( esc_html__( 'Leave a comment', 'gridhub' ), esc_html__( '1 Comment', 'gridhub' ), esc_html__( '% Comments', 'gridhub' ) );
                echo '&nbsp;&nbsp;&nbsp;</span>';
            }
        }
        if ( !(gridhub_get_option('hide_post_categories_home')) ) {
            if ( 'post' == get_post_type() ) {
                /* translators: used between list items, there is a space after the comma */
                $categories_list = get_the_category_list( esc_html__( ', ', 'gridhub' ) );
                if ( $categories_list ) {
                    /* translators: 1: list of categories. */ printf( '<span class="gridhub-grid-post-bottom-cat-links gridhub-grid-post-bottom-meta"><i class="fas fa-folder-open" aria-hidden="true"></i>&nbsp;' . __( '<span class="screen-reader-text">Posted in </span>%1$s', 'gridhub' ) . '</span>', $categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                }
            }
        }
        if ( !(gridhub_get_option('hide_post_tags_home')) ) {
            if ( 'post' == get_post_type() ) {
                /* translators: used between list items, there is a space after the comma */
                $tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'gridhub' ) );
                if ( $tags_list ) {
                    /* translators: 1: list of tags. */ printf( '<span class="gridhub-grid-post-bottom-tags-links gridhub-grid-post-bottom-meta"><i class="fas fa-tags" aria-hidden="true"></i>&nbsp;' . __( '<span class="screen-reader-text">Tagged </span>%1$s', 'gridhub' ) . '</span>', $tags_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                }
            }
        }
        ?>
        </div>
        </div>
        <?php
    }
}
endif;


function gridhub_is_grid_postmeta_active() {
    $grid_postmeta_active = FALSE;
    if ( !(gridhub_get_option('hide_post_author_home')) || !(gridhub_get_option('hide_posted_date_home')) ) {
        $grid_postmeta_active = TRUE;
    }
    return apply_filters( 'gridhub_is_grid_postmeta_active', $grid_postmeta_active );
}


if ( ! function_exists( 'gridhub_single_cats' ) ) :
function gridhub_single_cats() {
    if ( 'post' == get_post_type() ) {
        /* translators: used between list items, there is a space */
        $categories_list = get_the_category_list( esc_html__( ', ', 'gridhub' ) );
        if ( $categories_list ) {
            /* translators: 1: list of categories. */
            printf( '<span class="gridhub-entry-meta-single-cats"><i class="far fa-folder-open" aria-hidden="true"></i>&nbsp;' . __( '<span class="screen-reader-text">Posted in </span>%1$s', 'gridhub' ) . '</span>', $categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        }
    }
}
endif;


if ( ! function_exists( 'gridhub_single_postmeta' ) ) :
function gridhub_single_postmeta() { ?>
    <?php global $post; ?>
    <?php if ( !(gridhub_get_option('hide_post_author')) || !(gridhub_get_option('hide_posted_date')) || !(gridhub_get_option('hide_comments_link')) || !(gridhub_get_option('hide_post_categories')) || !(gridhub_get_option('hide_post_tags')) || !(gridhub_get_option('hide_post_edit')) ) { ?>
    <div class="gridhub-entry-meta-single">
    <div class="gridhub-entry-meta-single-inside">
    <?php if ( !(gridhub_get_option('hide_post_author')) ) { ?><span class="gridhub-entry-meta-single-author"><i class="far fa-user-circle" aria-hidden="true"></i>&nbsp;<span class="author vcard" itemscope="itemscope" itemtype="http://schema.org/Person" itemprop="author"><a class="url fn n" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php echo esc_html( get_the_author() ); ?></a></span></span><?php } ?>
    <?php if ( !(gridhub_get_option('hide_posted_date')) ) { ?><span class="gridhub-entry-meta-single-date"><i class="far fa-clock" aria-hidden="true"></i>&nbsp;<?php echo esc_html( get_the_date() ); ?></span><?php } ?>
    <?php if ( !(gridhub_get_option('hide_comments_link')) ) { ?><?php if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) { ?>
    <span class="gridhub-entry-meta-single-comments"><i class="far fa-comments" aria-hidden="true"></i>&nbsp;<?php comments_popup_link( sprintf( wp_kses( /* translators: %s: post title */ __( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'gridhub' ), array( 'span' => array( 'class' => array(), ), ) ), wp_kses_post( get_the_title() ) ) ); ?></span>
    <?php } ?><?php } ?>
    <?php if ( !(gridhub_get_option('hide_post_categories')) ) { ?><?php gridhub_single_cats(); ?><?php } ?>
    <?php if ( !(gridhub_get_option('hide_post_tags')) ) { ?><?php if ( has_tag() ) { ?><?php gridhub_post_tags(); ?><?php } ?><?php } ?>
    <?php if ( !(gridhub_get_option('hide_post_edit')) ) { ?><?php edit_post_link( sprintf( wp_kses( /* translators: %s: Name of current post. Only visible to screen readers */ __( 'Edit<span class="screen-reader-text"> %s</span>', 'gridhub' ), array( 'span' => array( 'class' => array(), ), ) ), wp_kses_post( get_the_title() ) ), '<span class="edit-link">&nbsp;&nbsp;<i class="far fa-edit" aria-hidden="true"></i> ', '</span>' ); ?><?php } ?>
    </div>
    </div>
    <?php } ?>
<?php }
endif;


if ( ! function_exists( 'gridhub_page_postmeta' ) ) :
function gridhub_page_postmeta() { ?>
    <?php global $post; ?>
    <?php if ( !(gridhub_get_option('hide_page_author')) || !(gridhub_get_option('hide_page_date')) || !(gridhub_get_option('hide_page_comments')) ) { ?>
    <div class="gridhub-entry-meta-single">
    <div class="gridhub-entry-meta-single-inside">
    <?php if ( !(gridhub_get_option('hide_page_author')) ) { ?><span class="gridhub-entry-meta-single-author"><i class="far fa-user-circle" aria-hidden="true"></i>&nbsp;<span class="author vcard" itemscope="itemscope" itemtype="http://schema.org/Person" itemprop="author"><a class="url fn n" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php echo esc_html( get_the_author() ); ?></a></span></span><?php } ?>
    <?php if ( !(gridhub_get_option('hide_page_date')) ) { ?><span class="gridhub-entry-meta-single-date"><i class="far fa-clock" aria-hidden="true"></i>&nbsp;<?php echo esc_html( get_the_date() ); ?></span><?php } ?>
    <?php if ( !(gridhub_get_option('hide_page_comments')) ) { ?><?php if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) { ?>
    <span class="gridhub-entry-meta-single-comments"><i class="far fa-comments" aria-hidden="true"></i>&nbsp;<?php comments_popup_link( sprintf( wp_kses( /* translators: %s: post title */ __( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'gridhub' ), array( 'span' => array( 'class' => array(), ), ) ), wp_kses_post( get_the_title() ) ) ); ?></span>
    <?php } ?><?php } ?>
    </div>
    </div>
    <?php } ?>
<?php }
endif;


/**
* Posts navigation functions
*/

if ( ! function_exists( 'gridhub_wp_pagenavi' ) ) :
function gridhub_wp_pagenavi() {
    ?>
    <nav class="navigation posts-navigation gridhub-clearfix" role="navigation">
        <?php wp_pagenavi(); ?>
    </nav><!-- .navigation -->
    <?php
}
endif;


if ( ! function_exists( 'gridhub_posts_navigation' ) ) :
function gridhub_posts_navigation() {
    if ( !(gridhub_get_option('hide_posts_navigation')) ) {
        if ( function_exists( 'wp_pagenavi' ) ) {
            gridhub_wp_pagenavi();
        } else {
            if ( gridhub_get_option('posts_navigation_type') === 'normalnavi' ) {
                the_posts_navigation(array('prev_text' => esc_html__( 'Older posts', 'gridhub' ), 'next_text' => esc_html__( 'Newer posts', 'gridhub' )));
            } else {
                the_posts_pagination(array('mid_size' => 2, 'prev_text' => esc_html__( '&larr; Newer posts', 'gridhub' ), 'next_text' => esc_html__( 'Older posts &rarr;', 'gridhub' )));
            }
        }
    }
}
endif;


if ( ! function_exists( 'gridhub_post_navigation' ) ) :
function gridhub_post_navigation() {
global $post;
    if ( !(gridhub_get_option('hide_post_navigation')) ) {
        the_post_navigation(array('prev_text' => esc_html__( '%title &rarr;', 'gridhub' ), 'next_text' => esc_html__( '&larr; %title', 'gridhub' )));
    }
}
endif;


/**
* Menu Functions
*/

function gridhub_primary_menu_mobile_text() {
   $primary_menu_mobile_text = esc_html__( 'Menu', 'gridhub' );
    if ( gridhub_get_option('primary_menu_mobile_text') ) {
            $primary_menu_mobile_text = gridhub_get_option('primary_menu_mobile_text');
    }
   return apply_filters( 'gridhub_read_more_text', $primary_menu_mobile_text );
}

function gridhub_secondary_menu_mobile_text() {
   $secondary_menu_mobile_text = esc_html__( 'Menu', 'gridhub' );
    if ( gridhub_get_option('secondary_menu_mobile_text') ) {
            $secondary_menu_mobile_text = gridhub_get_option('secondary_menu_mobile_text');
    }
   return apply_filters( 'gridhub_read_more_text', $secondary_menu_mobile_text );
}

// Get our wp_nav_menu() fallback, wp_page_menu(), to show a "Home" link as the first item
function gridhub_page_menu_args( $args ) {
    $args['show_home'] = true;
    return $args;
}
add_filter( 'wp_page_menu_args', 'gridhub_page_menu_args' );

function gridhub_top_fallback_menu() {
   wp_page_menu( array(
        'sort_column'  => 'menu_order, post_title',
        'menu_id'      => 'gridhub-menu-secondary-navigation',
        'menu_class'   => 'gridhub-secondary-nav-menu gridhub-menu-secondary',
        'container'    => 'ul',
        'echo'         => true,
        'link_before'  => '',
        'link_after'   => '',
        'before'       => '',
        'after'        => '',
        'item_spacing' => 'discard',
        'walker'       => '',
    ) );
}

function gridhub_fallback_menu() {
   wp_page_menu( array(
        'sort_column'  => 'menu_order, post_title',
        'menu_id'      => 'gridhub-menu-primary-navigation',
        'menu_class'   => 'gridhub-primary-nav-menu gridhub-menu-primary',
        'container'    => 'ul',
        'echo'         => true,
        'link_before'  => '',
        'link_after'   => '',
        'before'       => '',
        'after'        => '',
        'item_spacing' => 'discard',
        'walker'       => '',
    ) );
}

function gridhub_secondary_menu_area() {
if ( gridhub_is_secondary_menu_active() ) { ?>
<div class="gridhub-container gridhub-secondary-menu-container gridhub-clearfix">
<div class="gridhub-secondary-menu-container-inside gridhub-clearfix">
<nav class="gridhub-nav-secondary" id="gridhub-secondary-navigation" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement" role="navigation" aria-label="<?php esc_attr_e( 'Secondary Menu', 'gridhub' ); ?>">
<div class="gridhub-outer-wrapper">
<button class="gridhub-secondary-responsive-menu-icon" aria-controls="gridhub-menu-secondary-navigation" aria-expanded="false"><?php echo esc_html( gridhub_secondary_menu_mobile_text() ); ?></button>
<?php wp_nav_menu( array( 'theme_location' => 'secondary', 'menu_id' => 'gridhub-menu-secondary-navigation', 'menu_class' => 'gridhub-secondary-nav-menu gridhub-menu-secondary', 'fallback_cb' => 'gridhub_top_fallback_menu', 'container' => '', ) ); ?>
<?php if ( 'secondary-menu' === gridhub_social_buttons_location() ) { ?><?php if ( gridhub_is_social_buttons_active() ) { ?><?php gridhub_header_social_buttons(); ?><?php } ?><?php } ?>
</div>
</nav>
</div>
</div>
<?php }
}


/**
* Header Functions
*/

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function gridhub_pingback_header() {
    if ( is_singular() && pings_open() ) {
        echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
    }
}
add_action( 'wp_head', 'gridhub_pingback_header' );

// Get custom-logo URL
function gridhub_custom_logo() {
    if ( ! has_custom_logo() ) {return;}
    $gridhub_custom_logo_id = get_theme_mod( 'custom_logo' );
    $gridhub_logo = wp_get_attachment_image_src( $gridhub_custom_logo_id , 'full' );
    $gridhub_logo_src = $gridhub_logo[0];
    return apply_filters( 'gridhub_custom_logo', $gridhub_logo_src );
}

// Site Title
function gridhub_site_title() {
    if ( is_front_page() && is_home() ) { ?>
            <h1 class="gridhub-site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
            <?php if ( !(gridhub_get_option('hide_tagline')) ) { ?><p class="gridhub-site-description"><?php bloginfo( 'description' ); ?></p><?php } ?>
    <?php } elseif ( is_home() ) { ?>
            <h1 class="gridhub-site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
            <?php if ( !(gridhub_get_option('hide_tagline')) ) { ?><p class="gridhub-site-description"><?php bloginfo( 'description' ); ?></p><?php } ?>
    <?php } elseif ( is_singular() ) { ?>
            <p class="gridhub-site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
            <?php if ( !(gridhub_get_option('hide_tagline')) ) { ?><p class="gridhub-site-description"><?php bloginfo( 'description' ); ?></p><?php } ?>
    <?php } elseif ( is_category() ) { ?>
            <p class="gridhub-site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
            <?php if ( !(gridhub_get_option('hide_tagline')) ) { ?><p class="gridhub-site-description"><?php bloginfo( 'description' ); ?></p><?php } ?>
    <?php } elseif ( is_tag() ) { ?>
            <p class="gridhub-site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
            <?php if ( !(gridhub_get_option('hide_tagline')) ) { ?><p class="gridhub-site-description"><?php bloginfo( 'description' ); ?></p><?php } ?>
    <?php } elseif ( is_author() ) { ?>
            <p class="gridhub-site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
            <?php if ( !(gridhub_get_option('hide_tagline')) ) { ?><p class="gridhub-site-description"><?php bloginfo( 'description' ); ?></p><?php } ?>
    <?php } elseif ( is_archive() && !is_category() && !is_tag() && !is_author() ) { ?>
            <p class="gridhub-site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
            <?php if ( !(gridhub_get_option('hide_tagline')) ) { ?><p class="gridhub-site-description"><?php bloginfo( 'description' ); ?></p><?php } ?>
    <?php } elseif ( is_search() ) { ?>
            <p class="gridhub-site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
            <?php if ( !(gridhub_get_option('hide_tagline')) ) { ?><p class="gridhub-site-description"><?php bloginfo( 'description' ); ?></p><?php } ?>
    <?php } elseif ( is_404() ) { ?>
            <p class="gridhub-site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
            <?php if ( !(gridhub_get_option('hide_tagline')) ) { ?><p class="gridhub-site-description"><?php bloginfo( 'description' ); ?></p><?php } ?>
    <?php } else { ?>
            <h1 class="gridhub-site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
            <?php if ( !(gridhub_get_option('hide_tagline')) ) { ?><p class="gridhub-site-description"><?php bloginfo( 'description' ); ?></p><?php } ?>
    <?php }
}

function gridhub_header_image_destination() {
    $url = home_url( '/' );
    if ( gridhub_get_option('header_image_destination') ) {
        $url = gridhub_get_option('header_image_destination');
    }
    return apply_filters( 'gridhub_header_image_destination', $url );
}

function gridhub_header_image_markup() {
    if ( get_header_image() ) {
        if ( gridhub_get_option('remove_header_image_link') ) {
            the_header_image_tag( array( 'class' => 'gridhub-header-img', 'alt' => '' ) );
        } else { ?>
            <a href="<?php echo esc_url( gridhub_header_image_destination() ); ?>" rel="home" class="gridhub-header-img-link"><?php the_header_image_tag( array( 'class' => 'gridhub-header-img', 'alt' => '' ) ); ?></a>
        <?php }
    }
}

function gridhub_header_image_details() {
    $header_image_custom_title = '';
    if ( gridhub_get_option('header_image_custom_title') ) {
        $header_image_custom_title = gridhub_get_option('header_image_custom_title');
    }

    $header_image_custom_description = '';
    if ( gridhub_get_option('header_image_custom_description') ) {
        $header_image_custom_description = gridhub_get_option('header_image_custom_description');
    }

    if ( !(gridhub_get_option('hide_header_image_details')) ) {
    if ( gridhub_get_option('header_image_custom_text') ) {
        if ( $header_image_custom_title || $header_image_custom_description ) { ?>
            <div class="gridhub-header-image-info">
            <div class="gridhub-header-image-info-inside">
                <?php if ( $header_image_custom_title ) { ?><p class="gridhub-header-image-site-title gridhub-header-image-block"><?php echo wp_kses_post( force_balance_tags( do_shortcode($header_image_custom_title) ) ); ?></p><?php } ?>
                <?php if ( !(gridhub_get_option('hide_header_image_description')) ) { ?><?php if ( $header_image_custom_description ) { ?><p class="gridhub-header-image-site-description gridhub-header-image-block"><?php echo wp_kses_post( force_balance_tags( do_shortcode($header_image_custom_description) ) ); ?></p><?php } ?><?php } ?>
            </div>
            </div>
        <?php }
    } else { ?>
        <div class="gridhub-header-image-info">
        <div class="gridhub-header-image-info-inside">
            <p class="gridhub-header-image-site-title gridhub-header-image-block"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
            <?php if ( !(gridhub_get_option('hide_header_image_description')) ) { ?><p class="gridhub-header-image-site-description gridhub-header-image-block"><?php bloginfo( 'description' ); ?></p><?php } ?>
        </div>
        </div>
    <?php }
    }
}

function gridhub_header_image_wrapper() { ?>
    <div class="gridhub-header-image gridhub-clearfix">
    <?php gridhub_header_image_markup(); ?>
    <?php gridhub_header_image_details(); ?>
    </div>
    <?php
}

function gridhub_header_image() {
    if ( gridhub_get_option('hide_header_image') ) { return; }
    if ( get_header_image() ) {
        gridhub_header_image_wrapper();
    }
}


/**
* Css Classes Functions
*/

// Category ids in post class
function gridhub_category_id_class($classes) {
    global $post;
    foreach((get_the_category($post->ID)) as $category) {
        $classes[] = 'wpcat-' . $category->cat_ID . '-id';
    }
    return apply_filters( 'gridhub_category_id_class', $classes );
}
add_filter('post_class', 'gridhub_category_id_class');


// Adds custom classes to the array of body classes.
function gridhub_body_classes( $classes ) {
    // Adds a class of group-blog to blogs with more than 1 published author.
    if ( is_multi_author() ) {
        $classes[] = 'gridhub-group-blog';
    }

    $classes[] = 'gridhub-theme-is-active';

    if ( get_header_image() ) {
        $classes[] = 'gridhub-header-image-active';
    }

    if ( has_custom_logo() ) {
        $classes[] = 'gridhub-custom-logo-active';
    }

    $classes[] = 'gridhub-header-full-active';

    $classes[] = 'gridhub-masonry-inactive';

    $classes[] = 'gridhub-flexbox-grid';

    if ( gridhub_get_option('center_grid_content') ) {
        $classes[] = 'gridhub-centered-grid';
    }

    if ( is_singular() ) {

        if( is_single() ) {
            if ( gridhub_get_option('featured_media_under_post_title') ) {
                $classes[] = 'gridhub-single-media-under-title';
            }
        }
        if( is_page() ) {
            if ( gridhub_get_option('featured_media_under_page_title') ) {
                $classes[] = 'gridhub-single-media-under-title';
            }
        }

        if ( is_page_template() ) {
            if ( is_page_template( array( 'template-full-width-page.php', 'template-full-width-post.php' ) ) ) {
               $classes[] = 'gridhub-layout-full-width';
            } else {
                $classes[] = 'gridhub-layout-c-s1';
            }
        } else {
            $classes[] = 'gridhub-layout-c-s1';
        }

    } else {

        $classes[] = 'gridhub-layout-full-width';

    }

    if ( gridhub_get_option('hide_tagline') ) {
        $classes[] = 'gridhub-tagline-inactive';
    }

    if ( gridhub_is_primary_menu_active() ) {
        $classes[] = 'gridhub-primary-menu-active';
    }
    $classes[] = 'gridhub-primary-mobile-menu-active';

    if ( gridhub_is_secondary_menu_active() ) {
        $classes[] = 'gridhub-secondary-menu-active';
    }
    $classes[] = 'gridhub-secondary-mobile-menu-active';

    if ( 'primary-menu' === gridhub_social_buttons_location() ) {
        $classes[] = 'gridhub-primary-social-icons';
    } else {
        $classes[] = 'gridhub-secondary-social-icons';
    }

    return apply_filters( 'gridhub_body_classes', $classes );
}
add_filter( 'body_class', 'gridhub_body_classes' );



function gridhub_read_more_text() {
   $readmoretext = esc_html__( 'read more', 'gridhub' );
    if ( gridhub_get_option('read_more_text') ) {
            $readmoretext = gridhub_get_option('read_more_text');
    }
   return apply_filters( 'gridhub_read_more_text', $readmoretext );
}

// Change excerpt length
function gridhub_excerpt_length($length) {
    if ( is_admin() ) {
        return $length;
    }
    $read_more_length = 15;
    if ( gridhub_get_option('read_more_length') ) {
        $read_more_length = gridhub_get_option('read_more_length');
    }
    return apply_filters( 'gridhub_excerpt_length', $read_more_length );
}
add_filter('excerpt_length', 'gridhub_excerpt_length');

// Change excerpt more word
function gridhub_excerpt_more($more) {
    if ( is_admin() ) {
        return $more;
    }
    return '...';
}
add_filter('excerpt_more', 'gridhub_excerpt_more');


if ( ! function_exists( 'wp_body_open' ) ) :
    /**
     * Fire the wp_body_open action.
     *
     * Added for backwards compatibility to support pre 5.2.0 WordPress versions.
     */
    function wp_body_open() { // phpcs:ignore WPThemeReview.CoreFunctionality.PrefixAllGlobals.NonPrefixedFunctionFound
        /**
         * Triggered after the opening <body> tag.
         */
        do_action( 'wp_body_open' ); // phpcs:ignore WPThemeReview.CoreFunctionality.PrefixAllGlobals.NonPrefixedHooknameFound
    }
endif;



/**
* Custom Hooks
*/

function gridhub_before_header() {
    do_action('gridhub_before_header');
}

function gridhub_after_header() {
    do_action('gridhub_after_header');
}

function gridhub_before_main_content() {
    do_action('gridhub_before_main_content');
}
add_action('gridhub_before_main_content', 'gridhub_top_widgets', 20 );

function gridhub_after_main_content() {
    do_action('gridhub_after_main_content');
}
add_action('gridhub_after_main_content', 'gridhub_bottom_widgets', 10 );

function gridhub_sidebar_one() {
    do_action('gridhub_sidebar_one');
}
add_action('gridhub_sidebar_one', 'gridhub_sidebar_one_widgets', 10 );

function gridhub_sidebar_two() {
    do_action('gridhub_sidebar_two');
}

function gridhub_before_single_post() {
    do_action('gridhub_before_single_post');
}

function gridhub_before_single_post_title() {
    do_action('gridhub_before_single_post_title');
}

function gridhub_after_single_post_title() {
    do_action('gridhub_after_single_post_title');
}

function gridhub_top_single_post_content() {
    do_action('gridhub_top_single_post_content');
}

function gridhub_bottom_single_post_content() {
    do_action('gridhub_bottom_single_post_content');
}

function gridhub_after_single_post_content() {
    do_action('gridhub_after_single_post_content');
}

function gridhub_after_single_post() {
    do_action('gridhub_after_single_post');
}

function gridhub_before_single_page() {
    do_action('gridhub_before_single_page');
}

function gridhub_before_single_page_title() {
    do_action('gridhub_before_single_page_title');
}

function gridhub_after_single_page_title() {
    do_action('gridhub_after_single_page_title');
}

function gridhub_after_single_page_content() {
    do_action('gridhub_after_single_page_content');
}

function gridhub_after_single_page() {
    do_action('gridhub_after_single_page');
}

function gridhub_before_comments() {
    do_action('gridhub_before_comments');
}

function gridhub_after_comments() {
    do_action('gridhub_after_comments');
}

function gridhub_before_footer() {
    do_action('gridhub_before_footer');
}

function gridhub_after_footer() {
    do_action('gridhub_after_footer');
}

function gridhub_before_nongrid_post_title() {
    do_action('gridhub_before_nongrid_post_title');
}

function gridhub_after_nongrid_post_title() {
    do_action('gridhub_after_nongrid_post_title');
}

add_action('gridhub_before_header', 'gridhub_secondary_menu_area', 50 );



/**
* Media functions
*/

function gridhub_media_content_grid() {
    global $post; ?>
    <?php if ( !(gridhub_get_option('hide_thumbnail_home')) ) { ?>
    <?php if ( has_post_thumbnail($post->ID) ) { ?>
    <div class="gridhub-grid-post-thumbnail gridhub-grid-post-block">
        <a href="<?php echo esc_url( get_permalink() ); ?>" class="gridhub-grid-post-thumbnail-link" title="<?php /* translators: %s: post title. */ echo esc_attr( sprintf( __( 'Permanent Link to %s', 'gridhub' ), the_title_attribute( 'echo=0' ) ) ); ?>"><?php the_post_thumbnail('gridhub-480w-360h-image', array('class' => 'gridhub-grid-post-thumbnail-img', 'title' => the_title_attribute('echo=0'))); ?></a>
        <span class="gridhub-post-more-icon"><i class="fas fa-plus" aria-hidden="true"></i></span>
    </div>
    <?php } else { ?>
    <?php if ( !(gridhub_get_option('hide_default_thumbnail')) ) { ?>
    <div class="gridhub-grid-post-thumbnail gridhub-grid-post-thumbnail-default gridhub-grid-post-block">
        <a href="<?php echo esc_url( get_permalink() ); ?>" class="gridhub-grid-post-thumbnail-link" title="<?php /* translators: %s: post title. */ echo esc_attr( sprintf( __( 'Permanent Link to %s', 'gridhub' ), the_title_attribute( 'echo=0' ) ) ); ?>"><img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/no-image-480-360.jpg' ); ?>" class="gridhub-grid-post-thumbnail-img"/></a>
        <span class="gridhub-post-more-icon"><i class="fas fa-plus" aria-hidden="true"></i></span>
    </div>
    <?php } ?>
    <?php } ?>
    <?php } ?>
<?php }


function gridhub_media_content_single() {
    global $post;
    if ( has_post_thumbnail() ) {
        if ( !(gridhub_get_option('hide_thumbnail')) ) {
            if ( gridhub_get_option('thumbnail_link') == 'no' ) { ?>
                <div class="gridhub-post-thumbnail-single">
                <?php
                if ( is_page_template( array( 'template-full-width-post.php' ) ) ) {
                    the_post_thumbnail('gridhub-1292w-autoh-image', array('class' => 'gridhub-post-thumbnail-single-img', 'title' => the_title_attribute('echo=0')));
                } else {
                    the_post_thumbnail('gridhub-936w-autoh-image', array('class' => 'gridhub-post-thumbnail-single-img', 'title' => the_title_attribute('echo=0')));
                }
                ?>
                </div>
            <?php } else { ?>
                <div class="gridhub-post-thumbnail-single">
                <?php if ( is_page_template( array( 'template-full-width-post.php' ) ) ) { ?>
                    <a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php /* translators: %s: post title. */ echo esc_attr( sprintf( __( 'Permanent Link to %s', 'gridhub' ), the_title_attribute( 'echo=0' ) ) ); ?>" class="gridhub-post-thumbnail-single-link"><?php the_post_thumbnail('gridhub-1292w-autoh-image', array('class' => 'gridhub-post-thumbnail-single-img', 'title' => the_title_attribute('echo=0'))); ?></a>
                <?php } else { ?>
                    <a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php /* translators: %s: post title. */ echo esc_attr( sprintf( __( 'Permanent Link to %s', 'gridhub' ), the_title_attribute( 'echo=0' ) ) ); ?>" class="gridhub-post-thumbnail-single-link"><?php the_post_thumbnail('gridhub-936w-autoh-image', array('class' => 'gridhub-post-thumbnail-single-img', 'title' => the_title_attribute('echo=0'))); ?></a>
                <?php } ?>
                </div>
    <?php   }
        }
    }
}


function gridhub_media_content_single_location() {
    if( gridhub_get_option('featured_media_under_post_title') ) {
        add_action('gridhub_after_single_post_title', 'gridhub_media_content_single', 10 );
    } else {
        add_action('gridhub_before_single_post_title', 'gridhub_media_content_single', 10 );
    }
}
add_action('template_redirect', 'gridhub_media_content_single_location', 100 );


function gridhub_media_content_page() {
    global $post; ?>
    <?php
    if ( has_post_thumbnail() ) {
        if ( !(gridhub_get_option('hide_page_thumbnail')) ) {
            if ( gridhub_get_option('thumbnail_link_page') == 'no' ) { ?>
                <div class="gridhub-post-thumbnail-single">
                <?php
                if ( is_page_template( array( 'template-full-width-page.php' ) ) ) {
                    the_post_thumbnail('gridhub-1292w-autoh-image', array('class' => 'gridhub-post-thumbnail-single-img', 'title' => the_title_attribute('echo=0')));
                } else {
                    the_post_thumbnail('gridhub-936w-autoh-image', array('class' => 'gridhub-post-thumbnail-single-img', 'title' => the_title_attribute('echo=0')));
                }
                ?>
                </div>
            <?php } else { ?>
                <div class="gridhub-post-thumbnail-single">
                <?php if ( is_page_template( array( 'template-full-width-page.php' ) ) ) { ?>
                    <a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php /* translators: %s: post title. */ echo esc_attr( sprintf( __( 'Permanent Link to %s', 'gridhub' ), the_title_attribute( 'echo=0' ) ) ); ?>" class="gridhub-post-thumbnail-single-link"><?php the_post_thumbnail('gridhub-1292w-autoh-image', array('class' => 'gridhub-post-thumbnail-single-img', 'title' => the_title_attribute('echo=0'))); ?></a>
                <?php } else { ?>
                    <a href="<?php echo esc_url( get_permalink() ); ?>" title="<?php /* translators: %s: post title. */ echo esc_attr( sprintf( __( 'Permanent Link to %s', 'gridhub' ), the_title_attribute( 'echo=0' ) ) ); ?>" class="gridhub-post-thumbnail-single-link"><?php the_post_thumbnail('gridhub-936w-autoh-image', array('class' => 'gridhub-post-thumbnail-single-img', 'title' => the_title_attribute('echo=0'))); ?></a>
                <?php } ?>
                </div>
    <?php   }
        }
    }
    ?>
<?php }


function gridhub_media_content_page_location() {
    if( gridhub_get_option('featured_media_under_page_title') ) {
        add_action('gridhub_after_single_page_title', 'gridhub_media_content_page', 10 );
    } else {
        add_action('gridhub_before_single_page_title', 'gridhub_media_content_page', 10 );
    }
}
add_action('template_redirect', 'gridhub_media_content_page_location', 110 );



/**
* Enqueue scripts and styles
*/

function gridhub_scripts() {
    wp_enqueue_style('gridhub-maincss', get_stylesheet_uri(), array(), NULL);
    wp_enqueue_style('fontawesome', get_template_directory_uri() . '/assets/css/all.min.css', array(), NULL );
    wp_enqueue_style('gridhub-webfont', '//fonts.googleapis.com/css?family=Playfair+Display:400,400i,700,700i|Domine:400,700|Oswald:400,700|Pridi:400,700|Merriweather:400,400i,700,700i&amp;display=swap', array(), NULL);
    wp_enqueue_script('fitvids', get_template_directory_uri() .'/assets/js/jquery.fitvids.min.js', array( 'jquery' ), NULL, true);

    $gridhub_primary_menu_active = FALSE;
    if ( gridhub_is_primary_menu_active() ) {
        $gridhub_primary_menu_active = TRUE;
    }
    $gridhub_secondary_menu_active = FALSE;
    if ( gridhub_is_secondary_menu_active() ) {
        $gridhub_secondary_menu_active = TRUE;
    }

    $gridhub_sticky_menu_active = TRUE;
    $gridhub_sticky_mobile_menu_active = FALSE;

    $gridhub_sticky_sidebar_active = TRUE;
    if ( is_singular() ) {
        if ( is_page_template( array( 'template-full-width-page.php', 'template-full-width-post.php' ) ) ) {
           $gridhub_sticky_sidebar_active = FALSE;
        }
    } else {
        if ( is_404() ) {
            $gridhub_sticky_sidebar_active = FALSE;
        }
    }
    if ( $gridhub_sticky_sidebar_active ) {
        wp_enqueue_script('ResizeSensor', get_template_directory_uri() .'/assets/js/ResizeSensor.min.js', array( 'jquery' ), NULL, true);
        wp_enqueue_script('theia-sticky-sidebar', get_template_directory_uri() .'/assets/js/theia-sticky-sidebar.min.js', array( 'jquery' ), NULL, true);
    }

    wp_enqueue_script('gridhub-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array(), NULL, true );
    wp_enqueue_script('gridhub-skip-link-focus-fix', get_template_directory_uri() . '/assets/js/skip-link-focus-fix.js', array(), NULL, true );
    wp_enqueue_script('gridhub-customjs', get_template_directory_uri() .'/assets/js/custom.js', array( 'jquery', 'imagesloaded' ), NULL, true);

    wp_localize_script( 'gridhub-customjs', 'gridhub_ajax_object',
        array(
            'ajaxurl' => esc_url_raw( admin_url( 'admin-ajax.php' ) ),
            'primary_menu_active' => $gridhub_primary_menu_active,
            'secondary_menu_active' => $gridhub_secondary_menu_active,
            'sticky_menu_active' => $gridhub_sticky_menu_active,
            'sticky_mobile_menu_active' => $gridhub_sticky_mobile_menu_active,
            'sticky_sidebar_active' => $gridhub_sticky_sidebar_active,
        )
    );

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }

    wp_enqueue_script('gridhub-html5shiv-js', get_template_directory_uri() .'/assets/js/html5shiv.js', array('jquery'), NULL, true);

    wp_localize_script('gridhub-html5shiv-js','gridhub_custom_script_vars',array(
        'elements_name' => esc_html__('abbr article aside audio bdi canvas data datalist details dialog figcaption figure footer header hgroup main mark meter nav output picture progress section summary template time video', 'gridhub'),
    ));
}
add_action( 'wp_enqueue_scripts', 'gridhub_scripts' );

/**
 * Enqueue IE compatible scripts and styles.
 */
function gridhub_ie_scripts() {
    wp_enqueue_script( 'respond', get_template_directory_uri(). '/assets/js/respond.min.js', array(), NULL, false );
    wp_script_add_data( 'respond', 'conditional', 'lt IE 9' );
}
add_action( 'wp_enqueue_scripts', 'gridhub_ie_scripts' );

/**
 * Enqueue customizer styles.
 */
function gridhub_enqueue_customizer_styles() {
    wp_enqueue_style( 'gridhub-customizer-styles', get_template_directory_uri() . '/assets/css/customizer-style.css', array(), NULL );
    wp_enqueue_style('fontawesome', get_template_directory_uri() . '/assets/css/all.min.css', array(), NULL );
}
add_action( 'customize_controls_enqueue_scripts', 'gridhub_enqueue_customizer_styles' );