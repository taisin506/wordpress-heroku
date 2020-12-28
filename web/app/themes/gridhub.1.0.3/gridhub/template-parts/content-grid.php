<?php
/**
* Template part for displaying posts.
*
* @link https://developer.wordpress.org/themes/basics/template-hierarchy/
*
* @package GridHub WordPress Theme
* @copyright Copyright (C) 2020 ThemesDNA
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
* @author ThemesDNA <themesdna@gmail.com>
*/
?>

<?php $gridhub_grid_post_content = get_the_content(); ?>
<div id="post-<?php the_ID(); ?>" class="gridhub-grid-post gridhub-5-col">
<div class="gridhub-grid-post-inside">

    <?php gridhub_media_content_grid(); ?>

    <?php if ( !(gridhub_get_option('hide_post_author_image_home')) || !(gridhub_get_option('hide_post_title_home')) || !(gridhub_get_option('hide_post_snippet')) || gridhub_is_grid_postmeta_active() ) { ?>

    <div class="gridhub-grid-post-details gridhub-grid-post-block">
    <?php if ( !(gridhub_get_option('hide_post_author_image_home')) ) { ?><?php echo wp_kses_post( gridhub_author_image() ); ?><?php } ?>

    <?php if ( !(gridhub_get_option('hide_post_title_home')) ) { ?><?php the_title( sprintf( '<h3 class="gridhub-grid-post-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h3>' ); ?><?php } ?>

    <?php gridhub_grid_postmeta(); ?>

    <?php if ( !(gridhub_get_option('hide_post_snippet')) ) { ?><?php if ( !empty( $gridhub_grid_post_content ) ) { ?><div class="gridhub-grid-post-snippet"><div class="gridhub-grid-post-snippet-inside"><?php the_excerpt(); ?></div></div><?php } ?><?php } ?>

    <?php gridhub_grid_postfooter(); ?>
    </div>

    <?php } ?>

</div>
</div>