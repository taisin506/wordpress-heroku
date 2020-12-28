<?php

add_filter('wpseo_sitemaps_providers', 'madara_chapter_sitemap_provider');

function madara_chapter_sitemap_provider( $providers ){
	require WP_MANGA_DIR . '/inc/yoast/madara_wpseo_sitemap_provider.php';
	
	$madara_sitemap = new Madara_Sitemap_Provider();
	
	array_push( $providers, $madara_sitemap);
	
	return $providers;
}

add_action('init', 'madara_add_chapter_feed');
if(!function_exists('madara_add_chapter_feed')){
	function madara_add_chapter_feed(){
		add_feed('manga-chapters', 'madara_build_chapter_feed');
	}
}

if(!function_exists('madara_build_chapter_feed')){
	function madara_build_chapter_feed(){
		global $wp_manga_template;
		
		$wp_manga_template->load_template( 'manga', 'feed', true );
	}
}