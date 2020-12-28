<?php
/**
* The template for displaying search results pages.
*
* @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
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

<div class="gridhub-page-header-outside">
<header class="gridhub-page-header">
<div class="gridhub-page-header-inside">
<h1 class="page-title"><?php /* translators: %s: search query. */ printf( esc_html__( 'Search Results for: %s', 'gridhub' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
</div>
</header>
</div>

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