<?php
/**
* The template for displaying the footer
*
* @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
*
* @package GridHub WordPress Theme
* @copyright Copyright (C) 2020 ThemesDNA
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
* @author ThemesDNA <themesdna@gmail.com>
*/
?>

</div>

</div><!--/#gridhub-content-wrapper -->
</div><!--/#gridhub-wrapper -->

<?php gridhub_bottom_wide_widgets(); ?>

<?php gridhub_before_footer(); ?>

<?php if ( !(gridhub_hide_footer_widgets()) ) { ?>
<?php if ( is_active_sidebar( 'gridhub-footer-1' ) || is_active_sidebar( 'gridhub-footer-2' ) || is_active_sidebar( 'gridhub-footer-3' ) || is_active_sidebar( 'gridhub-footer-4' ) || is_active_sidebar( 'gridhub-footer-5' ) || is_active_sidebar( 'gridhub-footer-6' ) || is_active_sidebar( 'gridhub-top-footer' ) || is_active_sidebar( 'gridhub-bottom-footer' ) ) : ?>
<div class='gridhub-clearfix' id='gridhub-footer-blocks' itemscope='itemscope' itemtype='http://schema.org/WPFooter' role='contentinfo'>
<div class='gridhub-container gridhub-clearfix'>
<div class="gridhub-outer-wrapper">

<?php if ( is_active_sidebar( 'gridhub-top-footer' ) ) : ?>
<div class='gridhub-clearfix'>
<div class='gridhub-top-footer-block'>
<?php dynamic_sidebar( 'gridhub-top-footer' ); ?>
</div>
</div>
<?php endif; ?>

<?php if ( is_active_sidebar( 'gridhub-footer-1' ) || is_active_sidebar( 'gridhub-footer-2' ) || is_active_sidebar( 'gridhub-footer-3' ) || is_active_sidebar( 'gridhub-footer-4' ) || is_active_sidebar( 'gridhub-footer-5' ) || is_active_sidebar( 'gridhub-footer-6' ) ) : ?>
<div class='gridhub-footer-block-cols gridhub-clearfix'>

<div class="gridhub-footer-block-col gridhub-footer-6-col" id="gridhub-footer-block-1">
<?php dynamic_sidebar( 'gridhub-footer-1' ); ?>
</div>

<div class="gridhub-footer-block-col gridhub-footer-6-col" id="gridhub-footer-block-2">
<?php dynamic_sidebar( 'gridhub-footer-2' ); ?>
</div>

<div class="gridhub-footer-block-col gridhub-footer-6-col" id="gridhub-footer-block-3">
<?php dynamic_sidebar( 'gridhub-footer-3' ); ?>
</div>

<div class="gridhub-footer-block-col gridhub-footer-6-col" id="gridhub-footer-block-4">
<?php dynamic_sidebar( 'gridhub-footer-4' ); ?>
</div>

<div class="gridhub-footer-block-col gridhub-footer-6-col" id="gridhub-footer-block-5">
<?php dynamic_sidebar( 'gridhub-footer-5' ); ?>
</div>

<div class="gridhub-footer-block-col gridhub-footer-6-col" id="gridhub-footer-block-6">
<?php dynamic_sidebar( 'gridhub-footer-6' ); ?>
</div>

</div>
<?php endif; ?>

<?php if ( is_active_sidebar( 'gridhub-bottom-footer' ) ) : ?>
<div class='gridhub-clearfix'>
<div class='gridhub-bottom-footer-block'>
<?php dynamic_sidebar( 'gridhub-bottom-footer' ); ?>
</div>
</div>
<?php endif; ?>

</div>
</div>
</div><!--/#gridhub-footer-blocks-->
<?php endif; ?>
<?php } ?>

<div class='gridhub-clearfix' id='gridhub-copyright-area'>
<div class='gridhub-copyright-area-inside gridhub-container'>
<div class="gridhub-outer-wrapper">

<div class='gridhub-copyright-area-inside-content gridhub-clearfix'>
<?php if ( gridhub_get_option('footer_text') ) : ?>
  <p class='gridhub-copyright'><?php echo esc_html(gridhub_get_option('footer_text')); ?></p>
<?php else : ?>
  <p class='gridhub-copyright'><?php /* translators: %s: Year and site name. */ printf( esc_html__( 'Copyright &copy; %s', 'gridhub' ), esc_html(date_i18n(__('Y','gridhub'))) . ' ' . esc_html(get_bloginfo( 'name' ))  ); ?></p>
<?php endif; ?>
<p class='gridhub-credit'><a href="<?php echo esc_url( 'https://themesdna.com/' ); ?>"><?php /* translators: %s: Theme author. */ printf( esc_html__( 'Design by %s', 'gridhub' ), 'ThemesDNA.com' ); ?></a></p>
</div>

</div>
</div>
</div><!--/#gridhub-copyright-area -->

<?php gridhub_after_footer(); ?>

</div>

<button class="gridhub-scroll-top" title="<?php esc_attr_e('Scroll to Top','gridhub'); ?>"><i class="fas fa-arrow-up" aria-hidden="true"></i><span class="screen-reader-text"><?php esc_html_e('Scroll to Top', 'gridhub'); ?></span></button>

<?php wp_footer(); ?>
</body>
</html>