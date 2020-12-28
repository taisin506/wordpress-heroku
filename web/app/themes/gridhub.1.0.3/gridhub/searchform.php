<?php
/**
* The file for displaying the search form
*
* @package GridHub WordPress Theme
* @copyright Copyright (C) 2020 ThemesDNA
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
* @author ThemesDNA <themesdna@gmail.com>
*/
?>

<form role="search" method="get" class="gridhub-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
<label>
    <span class="screen-reader-text"><?php echo esc_html_x( 'Search for:', 'label', 'gridhub' ); ?></span>
    <input type="search" class="gridhub-search-field" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'gridhub' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
</label>
<input type="submit" class="gridhub-search-submit" value="<?php echo esc_attr_x( '&#xf002;', 'submit button', 'gridhub' ); ?>" />
</form>