<?php
/**
* The file for displaying the sidebars.
*
* @package GridHub WordPress Theme
* @copyright Copyright (C) 2020 ThemesDNA
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
* @author ThemesDNA <themesdna@gmail.com>
*/
?>

<?php if ( is_singular() ) { ?>

<?php if(!is_page_template(array( 'template-full-width-page.php', 'template-full-width-post.php' ))) { ?>
<div class="gridhub-sidebar-one-wrapper gridhub-sidebar-widget-areas gridhub-clearfix" id="gridhub-sidebar-one-wrapper" itemscope="itemscope" itemtype="http://schema.org/WPSideBar" role="complementary">
<div class="theiaStickySidebar">
<div class="gridhub-sidebar-one-wrapper-inside gridhub-clearfix">

<?php gridhub_sidebar_one(); ?>

</div>
</div>
</div><!-- /#gridhub-sidebar-one-wrapper-->
<?php } ?>

<?php } ?>