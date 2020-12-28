<?php
/**
* The template for displaying 404 pages (not found).
*
* @link https://codex.wordpress.org/Creating_an_Error_404_Page
*
* @package GridHub WordPress Theme
* @copyright Copyright (C) 2020 ThemesDNA
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
* @author ThemesDNA <themesdna@gmail.com>
*/

get_header(); ?>

<div class='gridhub-main-wrapper gridhub-clearfix' id='gridhub-main-wrapper' itemscope='itemscope' itemtype='http://schema.org/Blog' role='main'>
<div class='theiaStickySidebar'>
<div class="gridhub-main-wrapper-inside gridhub-clearfix">

<div class='gridhub-posts-wrapper' id='gridhub-posts-wrapper'>

<div class='gridhub-posts gridhub-box'>
<div class="gridhub-box-inside">

<div class="gridhub-page-header-outside">
<header class="gridhub-page-header">
<div class="gridhub-page-header-inside">
    <?php if ( gridhub_get_option('error_404_heading') ) : ?>
    <h1 class="page-title"><?php echo esc_html( gridhub_get_option('error_404_heading') ); ?></h1>
    <?php else : ?>
    <h1 class="page-title"><?php esc_html_e( 'Oops! That page can not be found.', 'gridhub' ); ?></h1>
    <?php endif; ?>
</div>
</header><!-- .gridhub-page-header -->
</div>

<div class='gridhub-posts-content'>

    <?php if ( gridhub_get_option('error_404_message') ) : ?>
    <p><?php echo wp_kses_post( force_balance_tags( gridhub_get_option('error_404_message') ) ); ?></p>
    <?php else : ?>
    <p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'gridhub' ); ?></p>
    <?php endif; ?>

    <?php if ( !(gridhub_get_option('hide_404_search')) ) { ?><?php get_search_form(); ?><?php } ?>

</div>

</div>
</div>

</div><!--/#gridhub-posts-wrapper -->

</div>
</div>
</div><!-- /#gridhub-main-wrapper -->

<?php get_footer(); ?>