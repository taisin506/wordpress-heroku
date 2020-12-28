<?php
/**
* Template part for displaying single posts.
*
* @link https://developer.wordpress.org/themes/basics/template-hierarchy/
*
* @package GridHub WordPress Theme
* @copyright Copyright (C) 2020 ThemesDNA
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
* @author ThemesDNA <themesdna@gmail.com>
*/
?>

<?php gridhub_before_single_post(); ?>

<article id="post-<?php the_ID(); ?>" <?php post_class('gridhub-post-singular gridhub-box'); ?>>
<div class="gridhub-box-inside">

    <?php gridhub_before_single_post_title(); ?>

    <?php if ( !(gridhub_get_option('hide_post_title')) ) { ?>
    <header class="entry-header">
    <div class="entry-header-inside gridhub-clearfix">
        <?php if ( gridhub_get_option('remove_post_title_link') ) { ?>
            <?php the_title( '<h1 class="post-title entry-title">', '</h1>' ); ?>
        <?php } else { ?>
            <?php the_title( sprintf( '<h1 class="post-title entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' ); ?>
        <?php } ?>

        <?php gridhub_single_postmeta(); ?>
    </div>
    </header><!-- .entry-header -->
    <?php } ?>

    <?php gridhub_after_single_post_title(); ?>

    <div class="entry-content gridhub-clearfix">
            <?php
            gridhub_top_single_post_content();

            the_content( sprintf(
                wp_kses(
                    /* translators: %s: Name of current post. Only visible to screen readers */
                    __( 'Continue reading<span class="screen-reader-text"> "%s"</span> <span class="meta-nav">&rarr;</span>', 'gridhub' ),
                    array(
                        'span' => array(
                            'class' => array(),
                        ),
                    )
                ),
                wp_kses_post( get_the_title() )
            ) );

            wp_link_pages( array(
             'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'gridhub' ) . '</span>',
             'after'       => '</div>',
             'link_before' => '<span>',
             'link_after'  => '</span>',
             ) );

            gridhub_bottom_single_post_content();
            ?>
    </div><!-- .entry-content -->

    <?php gridhub_after_single_post_content(); ?>

    <?php if ( !(gridhub_get_option('hide_author_bio_box')) ) { echo wp_kses_post( force_balance_tags( gridhub_add_author_bio_box() ) ); } ?>

</div>
</article>

<?php gridhub_after_single_post(); ?>