<?php
/**
* The main template file.
*
* This is the most generic template file in a WordPress theme
* and one of the two required files for a theme (the other being style.css).
* It is used to display a page when nothing more specific matches a query.
* E.g., it puts together the home page when no home.php file exists.
*
* @link https://developer.wordpress.org/themes/basics/template-hierarchy/
*
* @package GridHub WordPress Theme
* @copyright Copyright (C) 2020 ThemesDNA
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
* @author ThemesDNA <themesdna@gmail.com>
*/

get_header(); ?>

<div class="gridhub-main-wrapper gridhub-clearfix" id="gridhub-main-wrapper" itemscope="itemscope" itemtype="http://schema.org/Blog" role="main">
<div class="theiaStickySidebar">
<div class="gridhub-main-wrapper-inside gridhub-clearfix">

<?php gridhub_before_main_content(); ?>

<div class="gridhub-posts-wrapper" id="gridhub-posts-wrapper">

<?php if ( !(gridhub_get_option('hide_posts_heading')) ) { ?>
<?php if(is_home() && !is_paged()) { ?>
<?php if ( gridhub_get_option('posts_heading') ) : ?>
<div class="gridhub-posts-header"><div class="gridhub-posts-header-inside"><h2 class="gridhub-posts-heading"><span class="gridhub-posts-heading-inside"><?php echo esc_html( gridhub_get_option('posts_heading') ); ?></span></h2></div></div>
<?php else : ?>
<div class="gridhub-posts-header"><div class="gridhub-posts-header-inside"><h2 class="gridhub-posts-heading"><span class="gridhub-posts-heading-inside"><?php esc_html_e( 'Recent Posts', 'gridhub' ); ?></span></h2></div></div>
<?php endif; ?>
<?php } ?>
<?php } ?>

<div class="gridhub-posts-content">

<?php if (have_posts()) : ?>

    <div class="gridhub-posts gridhub-posts-grid">
    <?php while (have_posts()) : the_post(); ?>

        <?php get_template_part( 'template-parts/content-grid' ); ?>

    <?php endwhile; ?>
    </div>
    <div class="clear"></div>

    <?php gridhub_posts_navigation(); ?>

<?php else : ?>

  <?php get_template_part( 'template-parts/content', 'none' ); ?>

<?php endif; ?>

</div>

</div><!--/#gridhub-posts-wrapper -->

<?php gridhub_after_main_content(); ?>

</div>
</div>
</div><!-- /#gridhub-main-wrapper -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>