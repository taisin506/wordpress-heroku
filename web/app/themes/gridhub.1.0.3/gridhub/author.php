<?php
/**
* The template for displaying author archive pages.
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

<?php if ( !(gridhub_get_option('hide_author_title')) ) { ?>
<div class="gridhub-page-header-outside">
<header class="gridhub-page-header">
<div class="gridhub-page-header-inside">
<?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
<?php if ( !(gridhub_get_option('hide_author_description')) ) { ?><?php the_archive_description( '<div class="taxonomy-description">', '</div>' ); ?><?php } ?>
</div>
</header>
</div>
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