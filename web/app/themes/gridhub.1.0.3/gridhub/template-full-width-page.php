<?php
/**
* The template for displaying full-width page.
*
* @package GridHub WordPress Theme
* @copyright Copyright (C) 2020 ThemesDNA
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
* @author ThemesDNA <themesdna@gmail.com>
*
* Template Name: Full Width, no sidebar
* Template Post Type: page
*/

get_header(); ?>

<div class="gridhub-main-wrapper gridhub-clearfix" id="gridhub-main-wrapper" itemscope="itemscope" itemtype="http://schema.org/Blog" role="main">
<div class="theiaStickySidebar">
<div class="gridhub-main-wrapper-inside gridhub-clearfix">

<?php gridhub_before_main_content(); ?>

<div class='gridhub-posts-wrapper' id='gridhub-posts-wrapper'>

<?php while (have_posts()) : the_post();

    get_template_part( 'template-parts/content', 'page' );

    // If comments are open or we have at least one comment, load up the comment template
    if ( comments_open() || get_comments_number() ) :
            comments_template();
    endif;

endwhile; ?>

<div class="clear"></div>
</div><!--/#gridhub-posts-wrapper -->

<?php gridhub_after_main_content(); ?>

</div>
</div>
</div><!-- /#gridhub-main-wrapper -->

<?php get_footer(); ?>