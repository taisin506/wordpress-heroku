<?php

	/**
	 * 1.0
	 * @package    Madara
	 * @author     Mangabooth <mangabooth@gmail.com>
	 * @copyright  Copyright (C) 2018 mangabooth.com. All Rights Reserved
	 *
	 * Websites: https://mangabooth.com/
	 */

	namespace App;

	// Prevent direct access to this file
	defined( 'ABSPATH' ) || die( 'Direct access to this file is not allowed.' );

	require( get_template_directory() . '/app/core.php' );

	require( 'lib/walker_mobile_menu.class.php' );

	if ( class_exists( 'WP_MANGA' ) ) {
		/*
		 * check plugin wp-manga active or not.
		 * */
		require( get_template_directory() . '/madara-core/manga-functions.php' );

	}

	/**
	 * Core class.
	 *
	 * @package  Madara
	 * @since    1.0
	 */
	class MadaraStarter extends Madara {

		private static $instance;

		public static function getInstance() {
			if ( null == self::$instance ) {
				self::$instance = new MadaraStarter();
			}

			return self::$instance;
		}

		/**
		 * Initialize Madara Core.
		 *
		 * @return  void
		 */
		public function initialize() {
			add_action( 'template_redirect', array( $this, 'set_content_width' ), 0 );

			parent::initialize();

			if ( class_exists( 'woocommerce' ) ) {
				Plugins\madara_WooCommerce\WooCommerce::initialize();
			}

			$this->registerWidgets();

			/**
			 * Custom template tags and functions for this theme.
			 */
			require( get_template_directory() . '/inc/template-tags.php' );
			require( get_template_directory() . '/inc/extras.php' );

			add_action( 'after_setup_theme', array( $this, 'addThemeSupport' ) );
			add_action( 'widgets_init', array( $this, 'registerSidebar' ) );
			add_action( 'after_setup_theme', array( $this, 'registerNavMenus' ) );


			add_action( 'wp_enqueue_scripts', array( $this, 'enqueueScripts' ) );

			add_action( 'madara_release_logs', array( $this, 'release_logs' ) );
			add_filter( 'theme_page_templates', array( $this, 'makewp_exclude_page_templates' ) );

		}

		/**
		 * Set the content width in pixels, based on the theme's design and stylesheet.
		 *
		 * Priority 0 to make it available to lower priority callbacks.
		 *
		 * @global int $content_width
		 */
		function set_content_width() {

			$content_width = 980;

			$GLOBALS['content_width'] = apply_filters( 'madara_content_width', $content_width );
		}

		/**
		 * Hides the custom post template for pages on WordPress 4.6 and older
		 *
		 * @param array $post_templates Array of page templates. Keys are filenames, values are translated names.
		 *
		 * @return array Filtered array of page templates.
		 */
		function makewp_exclude_page_templates( $post_templates ) {
			if ( version_compare( $GLOBALS['wp_version'], '4.7', '<' ) ) {
				// unset( $post_templates['page-templates/my-full-width-post-template.php'] );
			}

			return $post_templates;
		}

		/**
		 * Add Theme Support
		 *
		 * @return void
		 */
		function addThemeSupport() {

			load_theme_textdomain( 'madara', get_template_directory() . '/languages' );

			add_theme_support( 'automatic-feed-links' );

			add_theme_support( "title-tag" );

			add_theme_support( 'post-thumbnails' );

			add_theme_support( 'custom-background' );

			add_theme_support( 'custom-header' );

			add_theme_support( 'html5', array(
				'comment-form',
				'comment-list',
				'search-form',
				'gallery',
				'caption',
			) );

			// register thumb sizes
			do_action( 'madara_reg_thumbnail' );
		}

		/**
		 * Madara Sidebar Init
		 *
		 * @since Madara Alpha 1.0
		 */
		function registerSidebar() {
			/*
			 * register WP Manga Main Top Sidebar & WP Manga Main Top Second Sidebar when plugin wp-manga activated.
			 * */
			do_action( 'madara_add_manga_sidebar' );

			$main_sidebar_before_widget = apply_filters( 'madara_main_sidebar_before_widget', '<div class="row"><div id="%1$s" class="widget %2$s"><div class="widget__inner %2$s__inner c-widget-wrap">' );
			$main_sidebar_after_widget  = apply_filters( 'madara_main_sidebar_after_widget', '</div></div></div>' );

			$before_widget = apply_filters( 'madara_sidebar_before_widget', '<div id="%1$s" class="widget %2$s"><div class="widget__inner %2$s__inner c-widget-wrap">' );
			$after_widget  = apply_filters( 'madara_sidebar_after_widget', '</div></div>' );

			$before_title = apply_filters( 'madara_sidebar_before_title', '<div class="widget-heading font-nav"><h5 class="heading">' );
			$after_title  = apply_filters( 'madara_sidebar_after_title', '</h5></div>' );

			register_sidebar( array(
				'name'          => esc_html__( 'Main Sidebar', 'madara' ),
				'id'            => 'main_sidebar',
				'description'   => esc_html__( 'Main Sidebar used by all pages', 'madara' ),
				'before_widget' => $main_sidebar_before_widget,
				'after_widget'  => $main_sidebar_after_widget,
				'before_title'  => $before_title,
				'after_title'   => $after_title,
			) );

			register_sidebar( array(
				'name'          => esc_html__( 'Single Post Sidebar', 'madara' ),
				'id'            => 'single_post_sidebar',
				'description'   => esc_html__( 'Appear in Single Post', 'madara' ),
				'before_widget' => $main_sidebar_before_widget,
				'after_widget'  => $main_sidebar_after_widget,
				'before_title'  => $before_title,
				'after_title'   => $after_title,
			) );

			register_sidebar( array(
				'name'          => esc_html__( 'Search Sidebar', 'madara' ),
				'id'            => 'search_sidebar',
				'description'   => esc_html__( 'Search Sidebar in header', 'madara' ),
				'before_widget' => $before_widget,
				'after_widget'  => $after_widget,
				'before_title'  => $before_title,
				'after_title'   => $after_title,
			) );

			register_sidebar( array(
				'name'          => esc_html__( 'Main Top Sidebar', 'madara' ),
				'id'            => 'top_sidebar',
				'description'   => esc_html__( 'Appear before main content', 'madara' ),
				'before_widget' => $before_widget,
				'after_widget'  => $after_widget,
				'before_title'  => $before_title,
				'after_title'   => $after_title,
			) );

			register_sidebar( array(
				'name'          => esc_html__( 'Main Top Second Sidebar', 'madara' ),
				'id'            => 'top_second_sidebar',
				'description'   => esc_html__( 'Appear before main content', 'madara' ),
				'before_widget' => $before_widget,
				'after_widget'  => $after_widget,
				'before_title'  => $before_title,
				'after_title'   => $after_title,
			) );

			register_sidebar( array(
				'name'          => esc_html__( 'Body Top Sidebar', 'madara' ),
				'id'            => 'body_top_sidebar',
				'description'   => esc_html__( 'Appear before body content', 'madara' ),
				'before_widget' => $before_widget,
				'after_widget'  => $after_widget,
				'before_title'  => $before_title,
				'after_title'   => $after_title,
			) );

			register_sidebar( array(
				'name'          => esc_html__( 'Body Bottom Sidebar', 'madara' ),
				'id'            => 'body_bottom_sidebar',
				'description'   => esc_html__( 'Appear after body content', 'madara' ),
				'before_widget' => '<div id="%1$s" class="widget %2$s"><div class="widget__inner %2$s__inner c-widget-wrap">',
				'after_widget'  => '</div></div>',
				'before_title'  => '<div class="widget-title"><div class="c-blog__heading style-2 font-heading"><h4>',
				'after_title'   => '</h4></div></div>',
			) );

			register_sidebar( array(
				'name'          => esc_html__( 'Main Bottom Sidebar', 'madara' ),
				'id'            => 'bottom_sidebar',
				'description'   => esc_html__( 'Appear after main content', 'madara' ),
				'before_widget' => $before_widget,
				'after_widget'  => $after_widget,
				'before_title'  => $before_title,
				'after_title'   => $after_title,
			) );
			
			register_sidebar( array(
				'name'          => esc_html__( 'Footer Sidebar', 'madara' ),
				'id'            => 'footer_sidebar',
				'description'   => esc_html__( 'Appear in Footer', 'madara' ),
				'before_widget' => $before_widget,
				'after_widget'  => $after_widget,
				'before_title'  => $before_title,
				'after_title'   => $after_title,
			) );
		}

		/**
		 * Register Menu Location
		 *
		 * @since Madara Alpha 1.0
		 */
		function registerNavMenus() {
			register_nav_menus( array(
				'primary_menu'   => esc_html__( 'Primary Menu', 'madara' ),
				'secondary_menu' => esc_html__( 'Secondary Menu', 'madara' ),
				'mobile_menu'    => esc_html__( 'Mobile Menu', 'madara' ),
				'user_menu'      => esc_html__( 'User Menu', 'madara' ),
				'footer_menu'    => esc_html__( 'Footer Menu', 'madara' ),
			) );
		}

		/**
		 * Enqueue needed scripts
		 */
		function enqueueScripts() {
			wp_enqueue_style( 'google-fonts', 'https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i' );

			if ( $this->getOption( 'loading_fontawesome', 'on' ) == 'on' ) {
				wp_enqueue_style( 'fontawesome', get_parent_theme_file_uri( '/app/lib/fontawesome/web-fonts-with-css/css/all.min.css' ), array(), '5.2.0' );
			}
			if ( $this->getOption( 'loading_ionicons', 'on' ) == 'on' ) {
				wp_enqueue_style( 'ionicons', get_parent_theme_file_uri( '/css/fonts/ionicons/css/ionicons.min.css' ) );
			}
			if ( $this->getOption( 'loading_ct_icons', 'on' ) == 'on' ) {
				wp_enqueue_style( 'madara-icons', get_parent_theme_file_uri( '/css/fonts/ct-icon/ct-icon.css' ) );
			}

			wp_enqueue_style( 'bootstrap', get_parent_theme_file_uri( '/css/bootstrap.min.css' ) );
			wp_enqueue_style( 'slick', get_parent_theme_file_uri( '/js/slick/slick.css' ) );
			wp_enqueue_style( 'slick-theme', get_parent_theme_file_uri( '/js/slick/slick-theme.css' ) );
			//Temporary
			wp_enqueue_style( 'loaders', get_parent_theme_file_uri( '/css/loaders.min.css' ) );
			wp_enqueue_style( 'madara-css', get_stylesheet_uri() );

			wp_enqueue_script( 'imagesloaded' );
			wp_enqueue_script( 'slick', get_parent_theme_file_uri( '/js/slick/slick.min.js' ), array( 'jquery' ), '1.7.1', true );
			wp_enqueue_script( 'aos', get_parent_theme_file_uri( '/js/aos.js' ), array(), '', true );
			wp_enqueue_script( 'madara-js', get_parent_theme_file_uri( '/js/template.js' ), array(
				'jquery',
				'bootstrap',
				'shuffle'
			), '', true );

			if ( $this->getOption( 'archive_navigation', 'default' ) == 'ajax' ) {
				wp_enqueue_script( 'madara-ajax', get_parent_theme_file_uri( '/js/ajax.js' ), array( 'jquery' ), '', true );
			}

			$js_params = array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) );

			global $wp_query, $wp;

			$js_params['query_vars']  = $wp_query->query_vars;
			$js_params['current_url'] = home_url( $wp->request );

			wp_localize_script( 'madara-js', 'madara', apply_filters( 'madara_js_params', $js_params ) );

			/**
			 * Add Custom CSS
			 */
			require( get_template_directory() . '/css/custom.css.php' );
			$custom_css = madara_custom_CSS();
			wp_add_inline_style( 'madara-css', $custom_css );
		}

		/**
		 * Include required widgets for each theme
		 *
		 * @return void
		 */
		public function registerWidgets() {

			/**
			 * Madara Posts Widget Init
			 *
			 */
			$madara_Posts = new Plugins\Widgets\Posts();

			if ( class_exists( 'WP_MANGA' ) ) {
				$mangaHistoryWidget  = new Plugins\Widgets\MangaHistory();
				$mangaBookmarkWidget = new Plugins\Widgets\MangaBookmark();
			}
		}

		public function release_logs() {
			// sample release logs
			?>
            <ul>
				<li>Version 1.5.1 - 2018.10.01<br/>
					<ul>
						<li>#Add: option to upload chapters via direct URL</li>
						<li>#Add: Footer sidebar</li>
						<li>#Add: option to change Author, Artist, Release Year, Manga Tag slug</li>
						<li>#Add: option to move Single Reading sidebar to side column for Text Chapter</li>
						<li>#Add: Light on/off button for video chapter</li>
						<li>#Add: support multi-servers for Video Chapter</li>
						<li>#Improve: show Play icon on thumbnail of Video Manga, and link directly to latest chapter</li>
						<li>#Fix: some minor issues</li>
						<li>#Fix: some CSS issues</li>
					</ul>
				</li>
				<li>Version 1.5.0.5 - 2018.09.26<br/>
					<ul>
						<li>#Improve: update Bootstrap 4.+, improve responsiveness of Big Thumbnail layout</li>
						<li>#Improve: support Add-ons WP Manga Custom Fields & WP Manga Chapter Permissions</li>
					</ul>
				</li>
				<li>Version 1.5.0.4 - 2018.09.20<br/>
					<ul>
						<li>#Add: option to hide Genre link in breadcrumbs</li>
						<li>#Improve: cache query "Calculate View Rank" for faster loading</li>
						<li>#Improve: responsiveness of Big Thumbnail layout</li>
						<li>#Fix: bookmark issue</li>
						<li>#Fix: Text Chapter's content is missing line breaks in Edit mode</li>
						<li>#Fix: download chapter does not work</li>
						<li>#Fix: disable User Login/Register if "Anyone Can Register" option is turned off</li>
					</ul>
				</li>
				<li>Version 1.5.0.3 - 2018.09.01<br/>
					<ul>
						<li>#Improve: improvements for faster database query</li>
						<li>#Fix: some minor bugs</li>
						<li>#Fix: invalid MACOSX zip file when uploading single chapter</li>
						<li>#Improve: CSS</li>
						<li>#Add: show Genre description</li>
						<li>#Add: option to insert URL for Manga Badge</li>
						<li>#Add: new Manga Archives layout - Simple List for Front Page</li>
						<li>#Add: new Item Layout property for Manga Listing shortcode</li>
					</ul>
				</li>
				<li>Version 1.5.0.2 - 2018.08.10<br/>
					<ul>
						<li>#Improve: great improvement in performance</li>
					</ul>
				</li>
				<li>Version 1.5.0.1 - 2018.08.06<br/>
					<ul>
						<li>#Fix: bug with YOAST Chapters sitemap</li>
						<li>#Fix: warning error in chapter reading page</li>
					</ul>
				</li>
                <li>Version 1.5 - 2018.08.03<br/>
	<ul>
		<li>#Add: option to turn off Hosting Sever select box from front-end, and use anonymous name for server</li>
		<li>#Add: option to hide word "Manga" (link to All Mangas page) from breadcrumbs</li>
		<li>#Add: option to change mobile header color (in Theme Options > Custom Colors)</li>
		<li>#Add: option for Big Thumbnail layout item</li>
		<li>#Add: RSS Feed link for Chapter</li>
		<li>#Add: option to change "manga-paged" variable when reading chapter</li>
		<li>#Add: option to view larger image when reading chapter</li>
		<li>#Add: option to switch Dark/Light mode for users when reading chapter</li>
		<li>#Add: option to list specific Manga Type (Comic, Novel, Drama) in Front-Page template, shortcode and widgets</li>
		<li>#Improve: support chapter sitemap by YOAST</li>
		<li>#Improve: support manga and chapter meta tags by YOAST. Use "%summary%" placeholder for Novel description</li>
		<li>#Improve: breadcrumbs and Manga Archives slug setting</li>
		<li>#Improve: showing number of people who have bookmarked manga</li>
		<li>#Improve: language translation</li>
		<li>#Improve: disable indexing duplicated chapter paged</li>
		<li>#Improve: convert external data file to database, improve site performance</li>
		<li>#Fix: custom color for half-star and active menu item</li>
		<li>#Fix: minor bugs</li>
	</ul>
</li>
<li>Version 1.4.2 - 2018.06.29<br/>
	<ul>
		<li>#Add: option to change Manga Genre slug</li>
		<li>#Add: option to change Reading Style (Paged or List) in different mangas</li>
		<li>#Add: option to mark "New Chapters" automatically (Theme Options >Manga General Layout > Manga New Chapter Tag)</li>
		<li>#Update: add various hooks and filters for custom content (see <a href="http://demo.mangabooth.com/doc/docs/manga-actions-and-filters/">document</a></li>
		<li>#Update: pre-load images in chapter to improve loading</li>
		<li>#Update: validate image extension when uploading</li>
		<li>#Update: WooCommerce 3.4 template</li>
		<li>#Update: support YOAST SEO title format %chapter%</li>
		<li>#Fix: various issues</li>
		<li>#Fix: Custom Fonts settings</li>
		<li>#Fix: reset password doesn't work</li>
	</ul>
</li>
<li>Version 1.4.1 - 2018.05.16<br/>
	<ul>
		<li>#Fix: bugs when Disqus plugin is not installed</li>
		<li>#Fix: navigation does not work in Front-Page template</li>
		<li>#Fix: search by Manga Status. Improve Search</li>
		<li>#Fix: select Volume and Ajax Next Chapter bug</li>
	</ul>
</li>
<li>Version 1.4 - 2018.05.14<br>
	<ul>
		<li>#Add: option to add chapter image via URL</li>
		<li>#Add: support Flickr storage</li>
		<li>#Add: New Chapter notification</li>
		<li>#Add: support OneSignal Web Push notification when there is new chapter. Require: https://wordpress.org/plugins/onesignal-free-web-push-notifications/</li>
		<li>#Add: option to use both WP Comments and Disqus Comments</li>
		<li>#Add: option to mark Manga 18+ and display a content warning</li>
		<li>#Add: option to turn off "Show More" in Manga summary</li>
		<li>#Add: more Manga Status "On Hold" and "Canceled"</li>
		<li>#Add: new Menu Location for Logged-in User</li>
		<li>#Add: option to manual configure for Chapter SEO meta tags</li>
		<li>#Add: option to turn off "Show More" in Chapter List</li>
		<li>#Add: add various hooks to allow theme customization. Check: http://demo.mangabooth.com/doc/docs/manga-actions-and-filters/</li>
		<li>#Add: option to hide meta tags in single Page & Post</li>

		<li>#Update: improve description meta for chapter reading page</li>
		<li>#Update: better UX for reading page</li>
		<li>#Update: schedule to delete temp folder, to save server storage</li>
		<li>#Update: search Manga by alternative name</li>
		<li>#Update: support Genre & Tag condition for Manga Listing shortcode</li>
		<li>#Update: language file</li>
		<li>#Update: option to choose a Page for User Profile link on menu</li>

		<li>#Fix: option disable Related Manga doesn't work</li>
		<li>#Fix: support UTF-8 content for text chapter</li>
		<li>#Fix: chapter link is incorrect when using multi-language</li>
		<li>#Fix: incorrect Chapter Volume when updating chapter</li>
		<li>#Fix: incorrect chapter order in Video and Text manga</li>
		<li>#Fix: FontAwesome 5-related issues</li>
		<li>#Fix: support Login No Captcha reCaptcha plugin</li>
		<li>#Fix: various bugs</li>
		<li>#Fix: check max file size for big value (GB) </li>
	</ul>
</li>
<li>Version 1.3 - 2018.02.28<br>
	<ul>
		<li>#Add: option for Sticky Chapter Navigation</li>
		<li>#Add: option for turning on Dark mode for Reading page</li>
		<li>#Add: allow to upload multiple Video &amp; Text chapters in back-end</li>
		<li>#Add: option for Ajax Loading Next Image in chapter</li>
		<li>#Add: support Blogger Storage</li>
		<li>#Add: widget Bookmark List</li>
<li>#Add: option to disable comments in Reading page</li>
		<li>#Update: WooCommerce support</li>
		<li>#Update: [for developer] add some filters for managing Ads</li>
		<li>#Update: import meta tags for Chapter</li>
		<li>#Update: link to next chapter when reaching last page in previous chapter</li>
		<li>#Update: able to select Volumn in Reading page</li>
		<li>#Update: show Chapter storages in Chapter List management</li>
		<li>#Update: able to search for Alternative Name</li>
		<li>#Update: FontAwesome 5</li>
		<li>#Update: able to configure heading in Archive page</li>
		<li>#Fix: chapter Content Type does not work correctly with Text tab</li>
		<li>#Fix: special character in Chapter extended name</li>
		<li>#Fix: Manga name in different language</li>
		<li>#Fix: some minor bugs</li>
	</ul>
</li>
<li>Version 1.2 - 2018.02.13<br>
<ul>
<li>#Add: Custom Badge feature (ex. Spoiler tag)</li>
<li>#Add: floating/side ads</li>
<li>#Add: shortcode to display all chapters for a single manga</li>
<li>#Update: option to allow comments on chapter, instead of manga</li>
<li>#Update: support Disqus comments</li>
<li>#Update: add text domain for translation in plugins</li>
<li>#Update: option to remove Select Hosting when there is only 1 option</li>
<li>#Update: support .gif feature image</li>
<li>#Update: navigate to next page when clicking on chapter image</li>
<li>#Update: redirect to login page if visitors go to User Settings page</li>
<li>#Update: upload images to different folder on Amazon storage</li>
<li>#Fix: next &amp; prev chapter buttons in Manga Reading - List Style</li>
<li>#Fix: chapter order by Name</li>
<li>#Fix: orderby option in Front-page template</li>
<li>#Fix: login &amp; register form layout on mobile</li>
</ul>
</li>
<li>Version 1.1 - 2018.01.12<br>
<ul>
<li>#Add: Support using Text &amp; Video iframe in Chapter</li>
<li>#Add: option to change Manga slug</li>
<li>#Add: shortcode Latest/Popular Manga</li>
<li>#Update: URL Friendly for Chapter</li>
<li>#Update: Navigate using keyboard</li>
<li>#Fix: correct language file</li>
</ul>
</li>
<li>Version 1.0 - First release</li>
            </ul>
			<?php
		}
	}


	$madara = MadaraStarter::getInstance();
	$madara->initialize();
