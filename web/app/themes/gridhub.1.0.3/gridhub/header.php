<?php
/**
* The header for GridHub theme.
*
* @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
*
* @package GridHub WordPress Theme
* @copyright Copyright (C) 2020 ThemesDNA
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
* @author ThemesDNA <themesdna@gmail.com>
*/

?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="profile" href="http://gmpg.org/xfn/11">
<?php wp_head(); ?>
</head>

<body <?php body_class('gridhub-animated gridhub-fadein'); ?> id="gridhub-site-body" itemscope="itemscope" itemtype="http://schema.org/WebPage">
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#gridhub-content-wrapper"><?php esc_html_e( 'Skip to content', 'gridhub' ); ?></a>

<div class="gridhub-site-wrapper">

<?php gridhub_header_image(); ?>

<?php gridhub_before_header(); ?>

<div id="gridhub-search-overlay-wrap" class="gridhub-search-overlay">
  <div class="gridhub-search-overlay-content">
    <?php get_search_form(); ?>
  </div>
  <button class="gridhub-search-closebtn" aria-label="<?php esc_attr_e( 'Close Search', 'gridhub' ); ?>" title="<?php esc_attr_e('Close Search','gridhub'); ?>">&#xD7;</button>
</div>

<div class="gridhub-site-header gridhub-container" id="gridhub-header" itemscope="itemscope" itemtype="http://schema.org/WPHeader" role="banner">
<div class="gridhub-head-content gridhub-clearfix" id="gridhub-head-content">

<?php if ( gridhub_is_header_content_active() ) { ?>
<div class="gridhub-header-inside gridhub-clearfix">
<div class="gridhub-header-inside-content gridhub-clearfix">
<div class="gridhub-outer-wrapper">
<div class="gridhub-header-inside-container">

<div class="gridhub-logo">
<?php if ( has_custom_logo() ) : ?>
    <div class="site-branding site-branding-full">
    <div class="gridhub-custom-logo-image">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="gridhub-logo-img-link">
        <img src="<?php echo esc_url( gridhub_custom_logo() ); ?>" alt="" class="gridhub-logo-img"/>
    </a>
    </div>
    <div class="gridhub-custom-logo-info"><?php gridhub_site_title(); ?></div>
    </div>
<?php else: ?>
    <div class="site-branding">
      <?php gridhub_site_title(); ?>
    </div>
<?php endif; ?>
</div>

</div>
</div>
</div>
</div>
<?php } else { ?>
<div class="gridhub-no-header-content">
  <?php gridhub_site_title(); ?>
</div>
<?php } ?>

</div><!--/#gridhub-head-content -->
</div><!--/#gridhub-header -->

<?php gridhub_after_header(); ?>

<?php if ( gridhub_is_primary_menu_active() ) { ?>
<div class="gridhub-container gridhub-primary-menu-container gridhub-clearfix">
<div class="gridhub-primary-menu-container-inside gridhub-clearfix">
<nav class="gridhub-nav-primary" id="gridhub-primary-navigation" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement" role="navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'gridhub' ); ?>">
<div class="gridhub-outer-wrapper">
<button class="gridhub-primary-responsive-menu-icon" aria-controls="gridhub-menu-primary-navigation" aria-expanded="false"><?php echo esc_html( gridhub_primary_menu_mobile_text() ); ?></button>
<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'gridhub-menu-primary-navigation', 'menu_class' => 'gridhub-primary-nav-menu gridhub-menu-primary', 'fallback_cb' => 'gridhub_fallback_menu', 'container' => '', ) ); ?>

<?php if ( 'primary-menu' === gridhub_social_buttons_location() ) { ?><?php if ( gridhub_is_social_buttons_active() ) { ?><?php gridhub_header_social_buttons(); ?><?php } ?><?php } ?>
</div>
</nav>
</div>
</div>
<?php } ?>

<?php gridhub_top_wide_widgets(); ?>

<div class="gridhub-outer-wrapper" id="gridhub-wrapper-outside">

<div class="gridhub-container gridhub-clearfix" id="gridhub-wrapper">
<div class="gridhub-content-wrapper gridhub-clearfix" id="gridhub-content-wrapper">