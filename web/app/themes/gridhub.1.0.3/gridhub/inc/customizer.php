<?php
/**
* GridHub Theme Customizer.
*
* @package GridHub WordPress Theme
* @copyright Copyright (C) 2020 ThemesDNA
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
* @author ThemesDNA <themesdna@gmail.com>
*/

if ( ! class_exists( 'WP_Customize_Control' ) ) {return NULL;}

/**
* GridHub_Customize_Static_Text_Control class
*/
class GridHub_Customize_Static_Text_Control extends WP_Customize_Control {
    public $type = 'gridhub-static-text';

    public function __construct( $manager, $id, $args = array() ) {
        parent::__construct( $manager, $id, $args );
    }

    protected function render_content() {
        if ( ! empty( $this->label ) ) :
            ?><span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span><?php
        endif;

        if ( ! empty( $this->description ) ) :
            ?><div class="description customize-control-description"><?php

        echo wp_kses_post( $this->description );

            ?></div><?php
        endif;

    }
}


/**
* GridHub_Customize_Button_Control class
*/
class GridHub_Customize_Button_Control extends WP_Customize_Control {
        public $type = 'gridhub-button';
        protected $button_tag = 'button';
        protected $button_class = 'button button-primary';
        protected $button_href = 'javascript:void(0)';
        protected $button_target = '';
        protected $button_onclick = '';
        protected $button_tag_id = '';

        public function render_content() {
        ?>
        <span class="center">
        <?php
        echo '<' . esc_html($this->button_tag);
        if (!empty($this->button_class)) {
            echo ' class="' . esc_attr($this->button_class) . '"';
        }
        if ('button' == $this->button_tag) {
            echo ' type="button"';
        }
        else {
            echo ' href="' . esc_url($this->button_href) . '"' . (empty($this->button_tag) ? '' : ' target="' . esc_attr($this->button_target) . '"');
        }
        if (!empty($this->button_onclick)) {
            echo ' onclick="' . esc_js($this->button_onclick) . '"';
        }
        if (!empty($this->button_tag_id)) {
            echo ' id="' . esc_attr($this->button_tag_id) . '"';
        }
        echo '>';
        echo esc_html($this->label);
        echo '</' . esc_html($this->button_tag) . '>';
        ?>
        </span>
        <?php
        }
}

/**
* GridHub_Customize_Recommended_Plugins class
*/

class GridHub_Customize_Recommended_Plugins extends WP_Customize_Control {
    public $type = 'gridhub-recommended-wpplugins';
    
    public function render_content() {
        $plugins = array(
        'widget-display-conditions' => array( 
            'link'  => esc_url( admin_url('plugin-install.php?tab=plugin-information&plugin=widget-display-conditions' ) ),
            'text'  => esc_html__( 'Widget Display Conditions', 'gridhub' ),
            'desc'  => esc_html__( 'Widget Display Conditions plugin helps you to control on which website page you want a particular widget to be displayed.', 'gridhub' ),
            ),
        'wordpress-seo' => array( 
            'link'  => esc_url( admin_url('plugin-install.php?tab=plugin-information&plugin=wordpress-seo' ) ),
            'text'  => esc_html__( 'Yoast SEO', 'gridhub' ),
            'desc'  => esc_html__( 'Yoast SEO plugin helps you with your search engine optimization. Yoast SEO is the favorite WordPress SEO plugin of millions of users worldwide.', 'gridhub' ),
            ),
        'wp-pagenavi' => array( 
            'link'  => esc_url( admin_url('plugin-install.php?tab=plugin-information&plugin=wp-pagenavi' ) ),
            'text'  => esc_html__( 'WP-PageNavi', 'gridhub' ),
            'desc'  => esc_html__( 'WP-PageNavi plugin helps to display numbered page navigation of this theme. Just install and activate the plugin.', 'gridhub' ),
            ),
        'regenerate-thumbnails' => array( 
            'link'  => esc_url( admin_url('plugin-install.php?tab=plugin-information&plugin=regenerate-thumbnails' ) ),
            'text'  => esc_html__( 'Regenerate Thumbnails', 'gridhub' ),
            'desc'  => esc_html__( 'Regenerate Thumbnails plugin helps you to regenerate your thumbnails to match with this theme. Install and activate the plugin. From the left hand navigation menu, click Tools &gt; Regen. Thumbnails. On the next screen, click Regenerate All Thumbnails.', 'gridhub' ),
            ),
        'widget-context' => array( 
            'link'  => esc_url( admin_url('plugin-install.php?tab=plugin-information&plugin=widget-context' ) ),
            'text'  => esc_html__( 'Widget Context', 'gridhub' ),
            'desc'  => esc_html__( 'This is an alternative to Widget Display Conditions plugin. Widget Context plugin helps you to show or hide widgets on certain sections of your site - front page, posts, pages, archives, search, etc.', 'gridhub' ),
            ),
        'loco-translate' => array( 
            'link'  => esc_url( admin_url('plugin-install.php?tab=plugin-information&plugin=loco-translate' ) ),
            'text'  => esc_html__( 'Loco Translate', 'gridhub' ),
            'desc'  => esc_html__( 'Loco Translate plugin provides in-browser editing of WordPress translation files.', 'gridhub' ),
            ),
        );
        foreach ( $plugins as $plugin) {
            echo wp_kses_post( force_balance_tags( '<p style="background:#fff;border:1px solid #ddd;color:#000;padding:10px;font-size:120%;font-style:normal;font-weight:bold;"><i class="fas fa-cog" aria-hidden="true"></i> <a style="margin-left:8px;font-weight:bold;color:#000" href="' . esc_url($plugin['link']) .'" target="_blank">' . esc_attr($plugin['text']) .' </a></p>' ) );
            echo wp_kses_post( force_balance_tags( '<p>'.$plugin['desc'].'</p>' ) );
        }
    }
}


/**
* Sanitize callback functions
*/

function gridhub_sanitize_checkbox( $input ) {
    return ( ( isset( $input ) && ( true == $input ) ) ? true : false );
}

function gridhub_sanitize_html( $input ) {
    return wp_kses_post( force_balance_tags( $input ) );
}

function gridhub_sanitize_thumbnail_link( $input, $setting ) {
    $valid = array('yes','no');
    if ( in_array( $input, $valid ) ) {
        return $input;
    } else {
        return $setting->default;
    }
}

function gridhub_sanitize_posts_navigation_type( $input, $setting ) {
    $valid = array('normalnavi','numberednavi');
    if ( in_array( $input, $valid ) ) {
        return $input;
    } else {
        return $setting->default;
    }
}

function gridhub_sanitize_email( $input, $setting ) {
    if ( '' != $input && is_email( $input ) ) {
        $input = sanitize_email( $input );
        return $input;
    } else {
        return $setting->default;
    }
}

function gridhub_sanitize_social_buttons_location( $input, $setting ) {
    $valid = array('primary-menu','secondary-menu');
    if ( in_array( $input, $valid ) ) {
        return $input;
    } else {
        return $setting->default;
    }
}

function gridhub_sanitize_read_more_length( $input, $setting ) {
    $input = absint( $input ); // Force the value into non-negative integer.
    return ( 0 < $input ) ? $input : $setting->default;
}

function gridhub_sanitize_positive_integer( $input, $setting ) {
    $input = absint( $input ); // Force the value into non-negative integer.
    return ( 0 < $input ) ? $input : $setting->default;
}

function gridhub_sanitize_positive_float( $input, $setting ) {
    $input = (float) $input;
    return ( 0 < $input ) ? $input : $setting->default;
}

function gridhub_register_theme_customizer( $wp_customize ) {

    if(method_exists('WP_Customize_Manager', 'add_panel')):
    $wp_customize->add_panel('gridhub_main_options_panel', array( 'title' => esc_html__('Theme Options', 'gridhub'), 'priority' => 10, ));
    endif;

    $wp_customize->get_section( 'title_tagline' )->panel = 'gridhub_main_options_panel';
    $wp_customize->get_section( 'title_tagline' )->priority = 20;
    $wp_customize->get_section( 'header_image' )->panel = 'gridhub_main_options_panel';
    $wp_customize->get_section( 'header_image' )->priority = 26;
    $wp_customize->get_section( 'background_image' )->panel = 'gridhub_main_options_panel';
    $wp_customize->get_section( 'background_image' )->priority = 27;
    $wp_customize->get_section( 'colors' )->panel = 'gridhub_main_options_panel';
    $wp_customize->get_section( 'colors' )->priority = 40;
      
    $wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
    $wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
    $wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
    $wp_customize->get_setting( 'background_color' )->transport = 'postMessage';
    $wp_customize->get_control( 'background_color' )->description = esc_html__('To change Background Color, need to remove background image first:- go to Appearance : Customize : Theme Options : Background Image', 'gridhub');

    if ( isset( $wp_customize->selective_refresh ) ) {
        $wp_customize->selective_refresh->add_partial( 'blogname', array(
            'selector'        => '.gridhub-site-title a',
            'render_callback' => 'gridhub_customize_partial_blogname',
        ) );
        $wp_customize->selective_refresh->add_partial( 'blogdescription', array(
            'selector'        => '.gridhub-site-description',
            'render_callback' => 'gridhub_customize_partial_blogdescription',
        ) );
    }


    // Customizer Section: Getting Started
    $wp_customize->add_section( 'gridhub_section_getting_started', array( 'title' => esc_html__( 'Getting Started', 'gridhub' ), 'description' => esc_html__( 'Thanks for your interest in GridHub! If you have any questions or run into any trouble, please visit us the following links. We will get you fixed up!', 'gridhub' ), 'panel' => 'gridhub_main_options_panel', 'priority' => 5, ) );

    $wp_customize->add_setting( 'gridhub_options[documentation]', array( 'default' => '', 'sanitize_callback' => '__return_false', ) );

    $wp_customize->add_control( new GridHub_Customize_Button_Control( $wp_customize, 'gridhub_documentation_control', array( 'label' => esc_html__( 'Documentation', 'gridhub' ), 'section' => 'gridhub_section_getting_started', 'settings' => 'gridhub_options[documentation]', 'type' => 'gridhub-button', 'button_tag' => 'a', 'button_class' => 'button button-primary', 'button_href' => esc_url( 'https://themesdna.com/gridhub-wordpress-theme/' ), 'button_target' => '_blank', ) ) );

    $wp_customize->add_setting( 'gridhub_options[contact]', array( 'default' => '', 'sanitize_callback' => '__return_false', ) );

    $wp_customize->add_control( new GridHub_Customize_Button_Control( $wp_customize, 'gridhub_contact_control', array( 'label' => esc_html__( 'Contact Us', 'gridhub' ), 'section' => 'gridhub_section_getting_started', 'settings' => 'gridhub_options[contact]', 'type' => 'gridhub-button', 'button_tag' => 'a', 'button_class' => 'button button-primary', 'button_href' => esc_url( 'https://themesdna.com/contact/' ), 'button_target' => '_blank', ) ) );


    // Customizer Section: Menus
    $wp_customize->add_section( 'gridhub_section_menu_options', array( 'title' => esc_html__( 'Menu Options', 'gridhub' ), 'panel' => 'gridhub_main_options_panel', 'priority' => 100 ) );

    $wp_customize->add_setting( 'gridhub_options[disable_primary_menu]', array( 'default' => false, 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_checkbox', ) );

    $wp_customize->add_control( 'gridhub_disable_primary_menu_control', array( 'label' => esc_html__( 'Disable Primary Menu', 'gridhub' ), 'section' => 'gridhub_section_menu_options', 'settings' => 'gridhub_options[disable_primary_menu]', 'type' => 'checkbox', ) );

    $wp_customize->add_setting( 'gridhub_options[primary_menu_mobile_text]', array( 'default' => esc_html__( 'Menu', 'gridhub' ), 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'sanitize_text_field', ) );

    $wp_customize->add_control( 'gridhub_primary_menu_mobile_text_control', array( 'label' => esc_html__( 'Mobile View Text for Primary Menu', 'gridhub' ), 'section' => 'gridhub_section_menu_options', 'settings' => 'gridhub_options[primary_menu_mobile_text]', 'type' => 'text', ) );

    $wp_customize->add_setting( 'gridhub_options[disable_secondary_menu]', array( 'default' => false, 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_checkbox', ) );

    $wp_customize->add_control( 'gridhub_disable_secondary_menu_control', array( 'label' => esc_html__( 'Disable Secondary Menu', 'gridhub' ), 'section' => 'gridhub_section_menu_options', 'settings' => 'gridhub_options[disable_secondary_menu]', 'type' => 'checkbox', ) );

    $wp_customize->add_setting( 'gridhub_options[secondary_menu_mobile_text]', array( 'default' => esc_html__( 'Menu', 'gridhub' ), 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'sanitize_text_field', ) );

    $wp_customize->add_control( 'gridhub_secondary_menu_mobile_text_control', array( 'label' => esc_html__( 'Mobile View Text for Secondary Menu', 'gridhub' ), 'section' => 'gridhub_section_menu_options', 'settings' => 'gridhub_options[secondary_menu_mobile_text]', 'type' => 'text', ) );


    // Customizer Section: Header
    $wp_customize->add_section( 'gridhub_section_header', array( 'title' => esc_html__( 'Header Options', 'gridhub' ), 'panel' => 'gridhub_main_options_panel', 'priority' => 120 ) );

    $wp_customize->add_setting( 'gridhub_options[hide_tagline]', array( 'default' => false, 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_checkbox', ) );

    $wp_customize->add_control( 'gridhub_hide_tagline_control', array( 'label' => esc_html__( 'Hide Tagline', 'gridhub' ), 'section' => 'gridhub_section_header', 'settings' => 'gridhub_options[hide_tagline]', 'type' => 'checkbox', ) );

    $wp_customize->add_setting( 'gridhub_options[hide_header_content]', array( 'default' => false, 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_checkbox', ) );

    $wp_customize->add_control( 'gridhub_hide_header_content_control', array( 'label' => esc_html__( 'Hide Header Content', 'gridhub' ), 'section' => 'gridhub_section_header', 'settings' => 'gridhub_options[hide_header_content]', 'type' => 'checkbox', ) );

    $wp_customize->add_setting( 'gridhub_options[hide_header_image]', array( 'default' => false, 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_checkbox', ) );

    $wp_customize->add_control( 'gridhub_hide_header_image_control', array( 'label' => esc_html__( 'Hide Header Image from Everywhere', 'gridhub' ), 'section' => 'header_image', 'settings' => 'gridhub_options[hide_header_image]', 'type' => 'checkbox', ) );

    $wp_customize->add_setting( 'gridhub_options[remove_header_image_link]', array( 'default' => false, 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_checkbox', ) );

    $wp_customize->add_control( 'gridhub_remove_header_image_link_control', array( 'label' => esc_html__( 'Remove Link from Header Image', 'gridhub' ), 'section' => 'header_image', 'settings' => 'gridhub_options[remove_header_image_link]', 'type' => 'checkbox', ) );

    $wp_customize->add_setting( 'gridhub_options[hide_header_image_details]', array( 'default' => false, 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_checkbox', ) );

    $wp_customize->add_control( 'gridhub_hide_header_image_details_control', array( 'label' => esc_html__( 'Hide both Title and Description from Header Image', 'gridhub' ), 'section' => 'header_image', 'settings' => 'gridhub_options[hide_header_image_details]', 'type' => 'checkbox', ) );

    $wp_customize->add_setting( 'gridhub_options[hide_header_image_description]', array( 'default' => false, 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_checkbox', ) );

    $wp_customize->add_control( 'gridhub_hide_header_image_description_control', array( 'label' => esc_html__( 'Hide Description from Header Image', 'gridhub' ), 'section' => 'header_image', 'settings' => 'gridhub_options[hide_header_image_description]', 'type' => 'checkbox', ) );

    $wp_customize->add_setting( 'gridhub_options[header_image_custom_text]', array( 'default' => false, 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_checkbox', ) );

    $wp_customize->add_control( 'gridhub_header_image_custom_text_control', array( 'label' => esc_html__( 'Add Custom Title/Custom Description to Header Image', 'gridhub' ), 'section' => 'header_image', 'settings' => 'gridhub_options[header_image_custom_text]', 'type' => 'checkbox', ) );

    $wp_customize->add_setting( 'gridhub_options[header_image_custom_title]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_html', ) );

    $wp_customize->add_control( 'gridhub_header_image_custom_title_control', array( 'label' => esc_html__( 'Header Image Custom Title', 'gridhub' ), 'section' => 'header_image', 'settings' => 'gridhub_options[header_image_custom_title]', 'type' => 'text', ) );

    $wp_customize->add_setting( 'gridhub_options[header_image_custom_description]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_html', ) );

    $wp_customize->add_control( 'gridhub_header_image_custom_description_control', array( 'label' => esc_html__( 'Header Image Custom Description', 'gridhub' ), 'section' => 'header_image', 'settings' => 'gridhub_options[header_image_custom_description]', 'type' => 'text', ) );

    $wp_customize->add_setting( 'gridhub_options[header_image_destination]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_header_image_destination_control', array( 'label' => esc_html__( 'Header Image Destination URL', 'gridhub' ), 'description' => esc_html__( 'Enter the URL a visitor should go when he/she click on the header image. If you did not enter a URL below, header image will be linked to the homepage of your website.', 'gridhub' ), 'section' => 'header_image', 'settings' => 'gridhub_options[header_image_destination]', 'type' => 'text' ) );


    // Customizer Section: Posts Grid
    $wp_customize->add_section( 'gridhub_section_posts_grid', array( 'title' => esc_html__( 'Posts Grid Options', 'gridhub' ), 'panel' => 'gridhub_main_options_panel', 'priority' => 160 ) );

    $wp_customize->add_setting( 'gridhub_options[center_grid_content]', array( 'default' => false, 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_checkbox', ) );

    $wp_customize->add_control( 'gridhub_center_grid_content_control', array( 'label' => esc_html__( 'Center the Content of Grid Items', 'gridhub' ), 'section' => 'gridhub_section_posts_grid', 'settings' => 'gridhub_options[center_grid_content]', 'type' => 'checkbox', ) );

    $wp_customize->add_setting( 'gridhub_options[hide_posts_heading]', array( 'default' => false, 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_checkbox', ) );

    $wp_customize->add_control( 'gridhub_hide_posts_heading_control', array( 'label' => esc_html__( 'Hide HomePage Posts Heading', 'gridhub' ), 'section' => 'gridhub_section_posts_grid', 'settings' => 'gridhub_options[hide_posts_heading]', 'type' => 'checkbox', ) );

    $wp_customize->add_setting( 'gridhub_options[posts_heading]', array( 'default' => esc_html__( 'Recent Posts', 'gridhub' ), 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'sanitize_text_field', ) );

    $wp_customize->add_control( 'gridhub_posts_heading_control', array( 'label' => esc_html__( 'HomePage Posts Heading', 'gridhub' ), 'section' => 'gridhub_section_posts_grid', 'settings' => 'gridhub_options[posts_heading]', 'type' => 'text', ) );

    $wp_customize->add_setting( 'gridhub_options[read_more_length]', array( 'default' => 15, 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_read_more_length' ) );

    $wp_customize->add_control( 'gridhub_read_more_length_control', array( 'label' => esc_html__( 'Auto Post Summary Length', 'gridhub' ), 'description' => esc_html__('Enter the number of words need to display in the post summary. Default is 20 words.', 'gridhub'), 'section' => 'gridhub_section_posts_grid', 'settings' => 'gridhub_options[read_more_length]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[read_more_text]', array( 'default' => esc_html__( 'read more', 'gridhub' ), 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'sanitize_text_field', ) );

    $wp_customize->add_control( 'gridhub_read_more_text_control', array( 'label' => esc_html__( 'Read More Text', 'gridhub' ), 'section' => 'gridhub_section_posts_grid', 'settings' => 'gridhub_options[read_more_text]', 'type' => 'text', ) );

    $wp_customize->add_setting( 'gridhub_options[hide_post_author_image_home]', array( 'default' => false, 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_checkbox', ) );

    $wp_customize->add_control( 'gridhub_hide_post_author_image_home_control', array( 'label' => esc_html__( 'Hide Post Author Images from Posts Grid', 'gridhub' ), 'section' => 'gridhub_section_posts_grid', 'settings' => 'gridhub_options[hide_post_author_image_home]', 'type' => 'checkbox', ) );

    $wp_customize->add_setting( 'gridhub_options[author_image_link]', array( 'default' => false, 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_checkbox', ) );

    $wp_customize->add_control( 'gridhub_author_image_link_control', array( 'label' => esc_html__( 'Link Author Image to Author Posts URL', 'gridhub' ), 'section' => 'gridhub_section_posts_grid', 'settings' => 'gridhub_options[author_image_link]', 'type' => 'checkbox', ) );

    $wp_customize->add_setting( 'gridhub_options[hide_post_title_home]', array( 'default' => false, 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_checkbox', ) );

    $wp_customize->add_control( 'gridhub_hide_post_title_home_control', array( 'label' => esc_html__( 'Hide Post Titles from Posts Grid', 'gridhub' ), 'section' => 'gridhub_section_posts_grid', 'settings' => 'gridhub_options[hide_post_title_home]', 'type' => 'checkbox', ) );

    $wp_customize->add_setting( 'gridhub_options[hide_posted_date_home]', array( 'default' => false, 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_checkbox', ) );

    $wp_customize->add_control( 'gridhub_hide_posted_date_home_control', array( 'label' => esc_html__( 'Hide Posted Dates from Posts Grid', 'gridhub' ), 'section' => 'gridhub_section_posts_grid', 'settings' => 'gridhub_options[hide_posted_date_home]', 'type' => 'checkbox', ) );

    $wp_customize->add_setting( 'gridhub_options[hide_post_author_home]', array( 'default' => false, 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_checkbox', ) );

    $wp_customize->add_control( 'gridhub_hide_post_author_home_control', array( 'label' => esc_html__( 'Hide Post Author Names from Posts Grid', 'gridhub' ), 'section' => 'gridhub_section_posts_grid', 'settings' => 'gridhub_options[hide_post_author_home]', 'type' => 'checkbox', ) );

    $wp_customize->add_setting( 'gridhub_options[hide_post_categories_home]', array( 'default' => false, 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_checkbox', ) );

    $wp_customize->add_control( 'gridhub_hide_post_categories_home_control', array( 'label' => esc_html__( 'Hide Post Categories from Posts Grid', 'gridhub' ), 'section' => 'gridhub_section_posts_grid', 'settings' => 'gridhub_options[hide_post_categories_home]', 'type' => 'checkbox', ) );

    $wp_customize->add_setting( 'gridhub_options[hide_post_tags_home]', array( 'default' => false, 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_checkbox', ) );

    $wp_customize->add_control( 'gridhub_hide_post_tags_home_control', array( 'label' => esc_html__( 'Hide Post Tags from Posts Grid', 'gridhub' ), 'section' => 'gridhub_section_posts_grid', 'settings' => 'gridhub_options[hide_post_tags_home]', 'type' => 'checkbox', ) );

    $wp_customize->add_setting( 'gridhub_options[hide_comments_link_home]', array( 'default' => false, 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_checkbox', ) );

    $wp_customize->add_control( 'gridhub_hide_comments_link_home_control', array( 'label' => esc_html__( 'Hide Comment Links from Posts Grid', 'gridhub' ), 'section' => 'gridhub_section_posts_grid', 'settings' => 'gridhub_options[hide_comments_link_home]', 'type' => 'checkbox', ) );

    $wp_customize->add_setting( 'gridhub_options[hide_thumbnail_home]', array( 'default' => false, 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_checkbox', ) );

    $wp_customize->add_control( 'gridhub_hide_thumbnail_home_control', array( 'label' => esc_html__( 'Hide Featured Images from Posts Grid', 'gridhub' ), 'section' => 'gridhub_section_posts_grid', 'settings' => 'gridhub_options[hide_thumbnail_home]', 'type' => 'checkbox', ) );

    $wp_customize->add_setting( 'gridhub_options[hide_default_thumbnail]', array( 'default' => false, 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_checkbox', ) );

    $wp_customize->add_control( 'gridhub_hide_default_thumbnail_control', array( 'label' => esc_html__( 'Hide Default Image', 'gridhub' ), 'description' => esc_html__( 'The default image is shown when there is no featured image.', 'gridhub' ), 'section' => 'gridhub_section_posts_grid', 'settings' => 'gridhub_options[hide_default_thumbnail]', 'type' => 'checkbox', ) );

    $wp_customize->add_setting( 'gridhub_options[hide_post_snippet]', array( 'default' => false, 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_checkbox', ) );

    $wp_customize->add_control( 'gridhub_hide_post_snippet_control', array( 'label' => esc_html__( 'Hide Post Snippets from Posts Grid', 'gridhub' ), 'section' => 'gridhub_section_posts_grid', 'settings' => 'gridhub_options[hide_post_snippet]', 'type' => 'checkbox', ) );


    // Customizer Section: Singular Post
    $wp_customize->add_section( 'gridhub_section_post', array( 'title' => esc_html__( 'Post Options', 'gridhub' ), 'panel' => 'gridhub_main_options_panel', 'priority' => 180 ) );

    $wp_customize->add_setting( 'gridhub_options[thumbnail_link]', array( 'default' => 'yes', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_thumbnail_link' ) );

    $wp_customize->add_control( 'gridhub_thumbnail_link_control', array( 'label' => esc_html__( 'Featured Image Link', 'gridhub' ), 'description' => esc_html__('Do you want the featured image in a single post to be linked to its post?', 'gridhub'), 'section' => 'gridhub_section_post', 'settings' => 'gridhub_options[thumbnail_link]', 'type' => 'select', 'choices' => array( 'yes' => esc_html__('Yes', 'gridhub'), 'no' => esc_html__('No', 'gridhub') ) ) );

    $wp_customize->add_setting( 'gridhub_options[hide_thumbnail]', array( 'default' => false, 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_checkbox', ) );

    $wp_customize->add_control( 'gridhub_hide_thumbnail_control', array( 'label' => esc_html__( 'Hide Featured Image from Full Post', 'gridhub' ), 'section' => 'gridhub_section_post', 'settings' => 'gridhub_options[hide_thumbnail]', 'type' => 'checkbox', ) );

    $wp_customize->add_setting( 'gridhub_options[featured_media_under_post_title]', array( 'default' => false, 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_checkbox', ) );

    $wp_customize->add_control( 'gridhub_featured_media_under_post_title_control', array( 'label' => esc_html__( 'Move Featured Image to Bottom of Full Post Title', 'gridhub' ), 'section' => 'gridhub_section_post', 'settings' => 'gridhub_options[featured_media_under_post_title]', 'type' => 'checkbox', ) );

    $wp_customize->add_setting( 'gridhub_options[hide_post_title]', array( 'default' => false, 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_checkbox', ) );

    $wp_customize->add_control( 'gridhub_hide_post_title_control', array( 'label' => esc_html__( 'Hide Post Header from Full Post', 'gridhub' ), 'section' => 'gridhub_section_post', 'settings' => 'gridhub_options[hide_post_title]', 'type' => 'checkbox', ) );

    $wp_customize->add_setting( 'gridhub_options[remove_post_title_link]', array( 'default' => false, 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_checkbox', ) );

    $wp_customize->add_control( 'gridhub_remove_post_title_link_control', array( 'label' => esc_html__( 'Remove Link from Full Post Title', 'gridhub' ), 'section' => 'gridhub_section_post', 'settings' => 'gridhub_options[remove_post_title_link]', 'type' => 'checkbox', ) );

    $wp_customize->add_setting( 'gridhub_options[hide_post_categories]', array( 'default' => false, 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_checkbox', ) );

    $wp_customize->add_control( 'gridhub_hide_post_categories_control', array( 'label' => esc_html__( 'Hide Post Categories from Full Post', 'gridhub' ), 'section' => 'gridhub_section_post', 'settings' => 'gridhub_options[hide_post_categories]', 'type' => 'checkbox', ) );

    $wp_customize->add_setting( 'gridhub_options[hide_post_author]', array( 'default' => false, 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_checkbox', ) );

    $wp_customize->add_control( 'gridhub_hide_post_author_control', array( 'label' => esc_html__( 'Hide Post Author from Full Post', 'gridhub' ), 'section' => 'gridhub_section_post', 'settings' => 'gridhub_options[hide_post_author]', 'type' => 'checkbox', ) );

    $wp_customize->add_setting( 'gridhub_options[hide_posted_date]', array( 'default' => false, 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_checkbox', ) );

    $wp_customize->add_control( 'gridhub_hide_posted_date_control', array( 'label' => esc_html__( 'Hide Posted Date from Full Post', 'gridhub' ), 'section' => 'gridhub_section_post', 'settings' => 'gridhub_options[hide_posted_date]', 'type' => 'checkbox', ) );

    $wp_customize->add_setting( 'gridhub_options[hide_comments_link]', array( 'default' => false, 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_checkbox', ) );

    $wp_customize->add_control( 'gridhub_hide_comments_link_control', array( 'label' => esc_html__( 'Hide Comment Link from Full Post', 'gridhub' ), 'section' => 'gridhub_section_post', 'settings' => 'gridhub_options[hide_comments_link]', 'type' => 'checkbox', ) );

    $wp_customize->add_setting( 'gridhub_options[hide_post_edit]', array( 'default' => false, 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_checkbox', ) );

    $wp_customize->add_control( 'gridhub_hide_post_edit_control', array( 'label' => esc_html__( 'Hide Post Edit Link', 'gridhub' ), 'section' => 'gridhub_section_post', 'settings' => 'gridhub_options[hide_post_edit]', 'type' => 'checkbox', ) );

    $wp_customize->add_setting( 'gridhub_options[hide_post_tags]', array( 'default' => false, 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_checkbox', ) );

    $wp_customize->add_control( 'gridhub_hide_post_tags_control', array( 'label' => esc_html__( 'Hide Post Tags from Full Post', 'gridhub' ), 'section' => 'gridhub_section_post', 'settings' => 'gridhub_options[hide_post_tags]', 'type' => 'checkbox', ) );

    $wp_customize->add_setting( 'gridhub_options[hide_author_bio_box]', array( 'default' => false, 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_checkbox', ) );

    $wp_customize->add_control( 'gridhub_hide_author_bio_box_control', array( 'label' => esc_html__( 'Hide Author Bio Box', 'gridhub' ), 'section' => 'gridhub_section_post', 'settings' => 'gridhub_options[hide_author_bio_box]', 'type' => 'checkbox', ) );


    // Customizer Section: Navigation
    $wp_customize->add_section( 'gridhub_section_navigation', array( 'title' => esc_html__( 'Post/Posts Navigation Options', 'gridhub' ), 'panel' => 'gridhub_main_options_panel', 'priority' => 185 ) );

    $wp_customize->add_setting( 'gridhub_options[hide_post_navigation]', array( 'default' => false, 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_checkbox', ) );

    $wp_customize->add_control( 'gridhub_hide_post_navigation_control', array( 'label' => esc_html__( 'Hide Post Navigation from Full Posts', 'gridhub' ), 'section' => 'gridhub_section_navigation', 'settings' => 'gridhub_options[hide_post_navigation]', 'type' => 'checkbox', ) );

    $wp_customize->add_setting( 'gridhub_options[hide_posts_navigation]', array( 'default' => false, 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_checkbox', ) );

    $wp_customize->add_control( 'gridhub_hide_posts_navigation_control', array( 'label' => esc_html__( 'Hide Posts Navigation from Home/Archive/Search Pages', 'gridhub' ), 'section' => 'gridhub_section_navigation', 'settings' => 'gridhub_options[hide_posts_navigation]', 'type' => 'checkbox', ) );

    $wp_customize->add_setting( 'gridhub_options[posts_navigation_type]', array( 'default' => 'numberednavi', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_posts_navigation_type' ) );

    $wp_customize->add_control( 'gridhub_posts_navigation_type_control', array( 'label' => esc_html__( 'Posts Navigation Type', 'gridhub' ), 'description' => esc_html__('Select posts navigation type you need. If you activate WP-PageNavi plugin, this navigation will be replaced by WP-PageNavi navigation.', 'gridhub'), 'section' => 'gridhub_section_navigation', 'settings' => 'gridhub_options[posts_navigation_type]', 'type' => 'select', 'choices' => array( 'normalnavi' => esc_html__('Normal Navigation', 'gridhub'), 'numberednavi' => esc_html__('Numbered Navigation', 'gridhub') ) ) );


    // Customizer Section: Singular Page
    $wp_customize->add_section( 'gridhub_section_page', array( 'title' => esc_html__( 'Page Options', 'gridhub' ), 'panel' => 'gridhub_main_options_panel', 'priority' => 190 ) );

    $wp_customize->add_setting( 'gridhub_options[thumbnail_link_page]', array( 'default' => 'yes', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_thumbnail_link' ) );

    $wp_customize->add_control( 'gridhub_thumbnail_link_page_control', array( 'label' => esc_html__( 'Featured Image Link', 'gridhub' ), 'description' => esc_html__('Do you want the featured image in a page to be linked to its page?', 'gridhub'), 'section' => 'gridhub_section_page', 'settings' => 'gridhub_options[thumbnail_link_page]', 'type' => 'select', 'choices' => array( 'yes' => esc_html__('Yes', 'gridhub'), 'no' => esc_html__('No', 'gridhub') ) ) );

    $wp_customize->add_setting( 'gridhub_options[hide_page_thumbnail]', array( 'default' => false, 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_checkbox', ) );

    $wp_customize->add_control( 'gridhub_hide_page_thumbnail_control', array( 'label' => esc_html__( 'Hide Featured Image from Single Page', 'gridhub' ), 'section' => 'gridhub_section_page', 'settings' => 'gridhub_options[hide_page_thumbnail]', 'type' => 'checkbox', ) );

    $wp_customize->add_setting( 'gridhub_options[featured_media_under_page_title]', array( 'default' => false, 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_checkbox', ) );

    $wp_customize->add_control( 'gridhub_featured_media_under_page_title_control', array( 'label' => esc_html__( 'Move Featured Image to Bottom of Page Title', 'gridhub' ), 'section' => 'gridhub_section_page', 'settings' => 'gridhub_options[featured_media_under_page_title]', 'type' => 'checkbox', ) );

    $wp_customize->add_setting( 'gridhub_options[hide_page_title]', array( 'default' => false, 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_checkbox', ) );

    $wp_customize->add_control( 'gridhub_hide_page_title_control', array( 'label' => esc_html__( 'Hide Page Header from Single Page', 'gridhub' ), 'section' => 'gridhub_section_page', 'settings' => 'gridhub_options[hide_page_title]', 'type' => 'checkbox', ) );

    $wp_customize->add_setting( 'gridhub_options[remove_page_title_link]', array( 'default' => false, 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_checkbox', ) );

    $wp_customize->add_control( 'gridhub_remove_page_title_link_control', array( 'label' => esc_html__( 'Remove Link from Single Page Title', 'gridhub' ), 'section' => 'gridhub_section_page', 'settings' => 'gridhub_options[remove_page_title_link]', 'type' => 'checkbox', ) );

    $wp_customize->add_setting( 'gridhub_options[hide_page_date]', array( 'default' => false, 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_checkbox', ) );

    $wp_customize->add_control( 'gridhub_hide_page_date_control', array( 'label' => esc_html__( 'Hide Posted Date from Single Page', 'gridhub' ), 'section' => 'gridhub_section_page', 'settings' => 'gridhub_options[hide_page_date]', 'type' => 'checkbox', ) );

    $wp_customize->add_setting( 'gridhub_options[hide_page_author]', array( 'default' => false, 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_checkbox', ) );

    $wp_customize->add_control( 'gridhub_hide_page_author_control', array( 'label' => esc_html__( 'Hide Page Author from Single Page', 'gridhub' ), 'section' => 'gridhub_section_page', 'settings' => 'gridhub_options[hide_page_author]', 'type' => 'checkbox', ) );

    $wp_customize->add_setting( 'gridhub_options[hide_page_comments]', array( 'default' => false, 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_checkbox', ) );

    $wp_customize->add_control( 'gridhub_hide_page_comments_control', array( 'label' => esc_html__( 'Hide Comment Link from Single Page', 'gridhub' ), 'section' => 'gridhub_section_page', 'settings' => 'gridhub_options[hide_page_comments]', 'type' => 'checkbox', ) );

    $wp_customize->add_setting( 'gridhub_options[hide_page_edit]', array( 'default' => false, 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_checkbox', ) );

    $wp_customize->add_control( 'gridhub_hide_page_edit_control', array( 'label' => esc_html__( 'Hide Edit Link from Single Page', 'gridhub' ), 'section' => 'gridhub_section_page', 'settings' => 'gridhub_options[hide_page_edit]', 'type' => 'checkbox', ) );


    // Customizer Section: Social Icons
    $wp_customize->add_section( 'gridhub_section_social', array( 'title' => esc_html__( 'Social Links Options', 'gridhub' ), 'panel' => 'gridhub_main_options_panel', 'priority' => 240, ));

    $wp_customize->add_setting( 'gridhub_options[social_buttons_location]', array( 'default' => 'secondary-menu', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_social_buttons_location' ) );

    $wp_customize->add_control( 'gridhub_social_buttons_location_control', array( 'label' => esc_html__( 'Social + Search + Login/Logout Buttons Location', 'gridhub' ), 'description' => esc_html__('Select where you want to display social buttons.', 'gridhub'), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[social_buttons_location]', 'type' => 'select', 'choices' => array( 'primary-menu' => esc_html__( 'Primary Menu', 'gridhub' ), 'secondary-menu' => esc_html__( 'Secondary Menu', 'gridhub' ) ) ) );

    $wp_customize->add_setting( 'gridhub_options[hide_social_buttons]', array( 'default' => false, 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_checkbox', ) );

    $wp_customize->add_control( 'gridhub_hide_social_buttons_control', array( 'label' => esc_html__( 'Hide Social + Search + Login/Logout Buttons', 'gridhub' ), 'description' => esc_html__('If you checked this option, there is no any effect from these option: "Hide Search Button", "Show Login/Logout Button".', 'gridhub'), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[hide_social_buttons]', 'type' => 'checkbox', ) );

    $wp_customize->add_setting( 'gridhub_options[hide_search_button]', array( 'default' => false, 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_checkbox', ) );

    $wp_customize->add_control( 'gridhub_hide_search_button_control', array( 'label' => esc_html__( 'Hide Search Button', 'gridhub' ), 'description' => esc_html__('This option has no effect if you checked the option: "Hide Social + Search + Login/Logout Buttons"', 'gridhub'), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[hide_search_button]', 'type' => 'checkbox', ) );

    $wp_customize->add_setting( 'gridhub_options[show_login_button]', array( 'default' => false, 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_checkbox', ) );

    $wp_customize->add_control( 'gridhub_show_login_button_control', array( 'label' => esc_html__( 'Show Login/Logout Button', 'gridhub' ), 'description' => esc_html__('This option has no effect if you checked the option: "Hide Social + Search + Login/Logout Buttons"', 'gridhub'), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[show_login_button]', 'type' => 'checkbox', ) );

    $wp_customize->add_setting( 'gridhub_options[twitterlink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_twitterlink_control', array( 'label' => esc_html__( 'Twitter URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[twitterlink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[facebooklink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_facebooklink_control', array( 'label' => esc_html__( 'Facebook URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[facebooklink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[googlelink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) ); 

    $wp_customize->add_control( 'gridhub_googlelink_control', array( 'label' => esc_html__( 'Google Plus URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[googlelink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[pinterestlink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_pinterestlink_control', array( 'label' => esc_html__( 'Pinterest URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[pinterestlink]', 'type' => 'text' ) );
    
    $wp_customize->add_setting( 'gridhub_options[linkedinlink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_linkedinlink_control', array( 'label' => esc_html__( 'Linkedin Link', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[linkedinlink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[instagramlink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_instagramlink_control', array( 'label' => esc_html__( 'Instagram Link', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[instagramlink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[vklink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_vklink_control', array( 'label' => esc_html__( 'VK Link', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[vklink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[flickrlink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_flickrlink_control', array( 'label' => esc_html__( 'Flickr Link', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[flickrlink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[youtubelink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_youtubelink_control', array( 'label' => esc_html__( 'Youtube URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[youtubelink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[vimeolink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_vimeolink_control', array( 'label' => esc_html__( 'Vimeo URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[vimeolink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[soundcloudlink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_soundcloudlink_control', array( 'label' => esc_html__( 'Soundcloud URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[soundcloudlink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[messengerlink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_messengerlink_control', array( 'label' => esc_html__( 'Messenger URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[messengerlink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[lastfmlink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_lastfmlink_control', array( 'label' => esc_html__( 'Lastfm URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[lastfmlink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[mediumlink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_mediumlink_control', array( 'label' => esc_html__( 'Medium URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[mediumlink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[githublink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_githublink_control', array( 'label' => esc_html__( 'Github URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[githublink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[bitbucketlink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_bitbucketlink_control', array( 'label' => esc_html__( 'Bitbucket URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[bitbucketlink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[tumblrlink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_tumblrlink_control', array( 'label' => esc_html__( 'Tumblr URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[tumblrlink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[digglink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_digglink_control', array( 'label' => esc_html__( 'Digg URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[digglink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[deliciouslink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_deliciouslink_control', array( 'label' => esc_html__( 'Delicious URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[deliciouslink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[stumblelink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_stumblelink_control', array( 'label' => esc_html__( 'Stumbleupon URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[stumblelink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[mixlink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_mixlink_control', array( 'label' => esc_html__( 'Mix URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[mixlink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[redditlink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_redditlink_control', array( 'label' => esc_html__( 'Reddit URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[redditlink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[dribbblelink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_dribbblelink_control', array( 'label' => esc_html__( 'Dribbble URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[dribbblelink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[flipboardlink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_flipboardlink_control', array( 'label' => esc_html__( 'Flipboard URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[flipboardlink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[bloggerlink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_bloggerlink_control', array( 'label' => esc_html__( 'Blogger URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[bloggerlink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[etsylink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_etsylink_control', array( 'label' => esc_html__( 'Etsy URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[etsylink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[behancelink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_behancelink_control', array( 'label' => esc_html__( 'Behance URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[behancelink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[amazonlink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_amazonlink_control', array( 'label' => esc_html__( 'Amazon URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[amazonlink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[meetuplink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_meetuplink_control', array( 'label' => esc_html__( 'Meetup URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[meetuplink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[mixcloudlink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_mixcloudlink_control', array( 'label' => esc_html__( 'Mixcloud URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[mixcloudlink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[slacklink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_slacklink_control', array( 'label' => esc_html__( 'Slack URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[slacklink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[snapchatlink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_snapchatlink_control', array( 'label' => esc_html__( 'Snapchat URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[snapchatlink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[spotifylink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_spotifylink_control', array( 'label' => esc_html__( 'Spotify URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[spotifylink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[yelplink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_yelplink_control', array( 'label' => esc_html__( 'Yelp URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[yelplink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[wordpresslink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_wordpresslink_control', array( 'label' => esc_html__( 'WordPress URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[wordpresslink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[twitchlink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_twitchlink_control', array( 'label' => esc_html__( 'Twitch URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[twitchlink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[telegramlink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_telegramlink_control', array( 'label' => esc_html__( 'Telegram URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[telegramlink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[bandcamplink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_bandcamplink_control', array( 'label' => esc_html__( 'Bandcamp URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[bandcamplink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[quoralink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_quoralink_control', array( 'label' => esc_html__( 'Quora URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[quoralink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[foursquarelink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_foursquarelink_control', array( 'label' => esc_html__( 'Foursquare URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[foursquarelink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[deviantartlink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_deviantartlink_control', array( 'label' => esc_html__( 'DeviantArt URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[deviantartlink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[imdblink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_imdblink_control', array( 'label' => esc_html__( 'IMDB URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[imdblink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[codepenlink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_codepenlink_control', array( 'label' => esc_html__( 'Codepen URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[codepenlink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[jsfiddlelink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_jsfiddlelink_control', array( 'label' => esc_html__( 'JSFiddle URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[jsfiddlelink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[stackoverflowlink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_stackoverflowlink_control', array( 'label' => esc_html__( 'Stack Overflow URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[stackoverflowlink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[stackexchangelink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_stackexchangelink_control', array( 'label' => esc_html__( 'Stack Exchange URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[stackexchangelink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[bsalink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_bsalink_control', array( 'label' => esc_html__( 'BuySellAds URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[bsalink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[web500pxlink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_web500pxlink_control', array( 'label' => esc_html__( '500px URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[web500pxlink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[ellolink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_ellolink_control', array( 'label' => esc_html__( 'Ello URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[ellolink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[goodreadslink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_goodreadslink_control', array( 'label' => esc_html__( 'Goodreads URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[goodreadslink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[odnoklassnikilink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_odnoklassnikilink_control', array( 'label' => esc_html__( 'Odnoklassniki URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[odnoklassnikilink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[houzzlink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_houzzlink_control', array( 'label' => esc_html__( 'Houzz URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[houzzlink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[pocketlink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_pocketlink_control', array( 'label' => esc_html__( 'Pocket URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[pocketlink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[xinglink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_xinglink_control', array( 'label' => esc_html__( 'XING URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[xinglink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[googleplaylink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_googleplaylink_control', array( 'label' => esc_html__( 'Google Play URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[googleplaylink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[slidesharelink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_slidesharelink_control', array( 'label' => esc_html__( 'SlideShare URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[slidesharelink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[dropboxlink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_dropboxlink_control', array( 'label' => esc_html__( 'Dropbox URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[dropboxlink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[paypallink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_paypallink_control', array( 'label' => esc_html__( 'PayPal URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[paypallink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[viadeolink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_viadeolink_control', array( 'label' => esc_html__( 'Viadeo URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[viadeolink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[wikipedialink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_wikipedialink_control', array( 'label' => esc_html__( 'Wikipedia URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[wikipedialink]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[skypeusername]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'sanitize_text_field' ) );

    $wp_customize->add_control( 'gridhub_skypeusername_control', array( 'label' => esc_html__( 'Skype Username', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[skypeusername]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[emailaddress]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_email' ) );

    $wp_customize->add_control( 'gridhub_emailaddress_control', array( 'label' => esc_html__( 'Email Address', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[emailaddress]', 'type' => 'text' ) );

    $wp_customize->add_setting( 'gridhub_options[rsslink]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'esc_url_raw' ) );

    $wp_customize->add_control( 'gridhub_rsslink_control', array( 'label' => esc_html__( 'RSS Feed URL', 'gridhub' ), 'section' => 'gridhub_section_social', 'settings' => 'gridhub_options[rsslink]', 'type' => 'text' ) );


    // Customizer Section: Footer
    $wp_customize->add_section( 'gridhub_section_footer', array( 'title' => esc_html__( 'Footer Options', 'gridhub' ), 'panel' => 'gridhub_main_options_panel', 'priority' => 280 ) );

    $wp_customize->add_setting( 'gridhub_options[footer_text]', array( 'default' => '', 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_html', ) );

    $wp_customize->add_control( 'gridhub_footer_text_control', array( 'label' => esc_html__( 'Footer copyright notice', 'gridhub' ), 'section' => 'gridhub_section_footer', 'settings' => 'gridhub_options[footer_text]', 'type' => 'text', ) );

    $wp_customize->add_setting( 'gridhub_options[hide_footer_widgets]', array( 'default' => false, 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_checkbox', ) );

    $wp_customize->add_control( 'gridhub_hide_footer_widgets_control', array( 'label' => esc_html__( 'Hide Footer Widgets', 'gridhub' ), 'section' => 'gridhub_section_footer', 'settings' => 'gridhub_options[hide_footer_widgets]', 'type' => 'checkbox', ) );


    // Customizer Section: Search and 404
    $wp_customize->add_section( 'gridhub_section_search_404', array( 'title' => esc_html__( 'Search and 404 Pages Options', 'gridhub' ), 'panel' => 'gridhub_main_options_panel', 'priority' => 340 ) );

    $wp_customize->add_setting( 'gridhub_options[no_search_heading]', array( 'default' => esc_html__( 'Nothing Found', 'gridhub' ), 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_html', ) );

    $wp_customize->add_control( 'gridhub_no_search_heading_control', array( 'label' => esc_html__( 'No Search Results Heading', 'gridhub' ), 'description' => esc_html__( 'Enter a heading to display when no search results are found.', 'gridhub' ), 'section' => 'gridhub_section_search_404', 'settings' => 'gridhub_options[no_search_heading]', 'type' => 'textarea' ) );

    $wp_customize->add_setting( 'gridhub_options[no_search_results]', array( 'default' => esc_html__( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'gridhub' ), 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_html', ) );

    $wp_customize->add_control( 'gridhub_no_search_results_control', array( 'label' => esc_html__( 'No Search Results Message', 'gridhub' ), 'description' => esc_html__( 'Enter a message to display when no search results are found.', 'gridhub' ), 'section' => 'gridhub_section_search_404', 'settings' => 'gridhub_options[no_search_results]', 'type' => 'textarea' ) );

    $wp_customize->add_setting( 'gridhub_options[error_404_heading]', array( 'default' => esc_html__( 'Oops! That page can not be found.', 'gridhub' ), 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_html', ) );

    $wp_customize->add_control( 'gridhub_error_404_heading_control', array( 'label' => esc_html__( '404 Error Page Heading', 'gridhub' ), 'description' => esc_html__( 'Enter the heading for the 404 error page.', 'gridhub' ), 'section' => 'gridhub_section_search_404', 'settings' => 'gridhub_options[error_404_heading]', 'type' => 'textarea' ) );

    $wp_customize->add_setting( 'gridhub_options[error_404_message]', array( 'default' => esc_html__( 'It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'gridhub' ), 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_html', ) );

    $wp_customize->add_control( 'gridhub_error_404_message_control', array( 'label' => esc_html__( 'Error 404 Message', 'gridhub' ), 'description' => esc_html__( 'Enter a message to display on the 404 error page.', 'gridhub' ), 'section' => 'gridhub_section_search_404', 'settings' => 'gridhub_options[error_404_message]', 'type' => 'textarea', ) );

    $wp_customize->add_setting( 'gridhub_options[hide_404_search]', array( 'default' => false, 'type' => 'option', 'capability' => 'edit_theme_options', 'sanitize_callback' => 'gridhub_sanitize_checkbox', ) );

    $wp_customize->add_control( 'gridhub_hide_404_search_control', array( 'label' => esc_html__( 'Hide Search Box from 404 Page', 'gridhub' ), 'section' => 'gridhub_section_search_404', 'settings' => 'gridhub_options[hide_404_search]', 'type' => 'checkbox', ) );


    // Customizer Section: Recommended Plugins
    $wp_customize->add_section( 'gridhub_section_recommended_plugins', array( 'title' => esc_html__( 'Recommended Plugins', 'gridhub' ), 'panel' => 'gridhub_main_options_panel', 'priority' => 620 ));

    $wp_customize->add_setting( 'gridhub_options[recommended_plugins]', array( 'type' => 'option', 'capability' => 'install_plugins', 'sanitize_callback' => '__return_false' ) );

    $wp_customize->add_control( new GridHub_Customize_Recommended_Plugins( $wp_customize, 'gridhub_recommended_plugins_control', array( 'label' => esc_html__( 'Recommended Plugins', 'gridhub' ), 'section' => 'gridhub_section_recommended_plugins', 'settings' => 'gridhub_options[recommended_plugins]', 'type' => 'gridhub-recommended-wpplugins', 'capability' => 'install_plugins' ) ) );


    // Customizer Section: Upgrade
    $wp_customize->add_section( 'gridhub_section_upgrade', array( 'title' => esc_html__( 'Upgrade to Pro Version', 'gridhub' ), 'priority' => 400 ) );
    
    $wp_customize->add_setting( 'gridhub_options[upgrade_text]', array( 'default' => '', 'sanitize_callback' => '__return_false', ) );
    
    $wp_customize->add_control( new GridHub_Customize_Static_Text_Control( $wp_customize, 'gridhub_upgrade_text_control', array(
        'label'       => esc_html__( 'GridHub Pro', 'gridhub' ),
        'section'     => 'gridhub_section_upgrade',
        'settings' => 'gridhub_options[upgrade_text]',
        'type' => 'gridhub-static-text',
        'description' => esc_html__( 'Do you enjoy GridHub? Upgrade to GridHub Pro now and get:', 'gridhub' ).
            '<ul class="gridhub-customizer-pro-features">' .
                '<li><i class="fas fa-check" aria-hidden="true"></i> ' . esc_html__( 'Color Options', 'gridhub' ) . '</li>' .
                '<li><i class="fas fa-check" aria-hidden="true"></i> ' . esc_html__( 'Font Options', 'gridhub' ) . '</li>' .
                '<li><i class="fas fa-check" aria-hidden="true"></i> ' . esc_html__( '1/2/3/4/5/6/7/8/9/10 Columns Options for Posts Grids', 'gridhub' ) . '</li>' .
                '<li><i class="fas fa-check" aria-hidden="true"></i> ' . esc_html__( '10+ Thumbnail Sizes Options for Posts Grids', 'gridhub' ) . '</li>' .
                '<li><i class="fas fa-check" aria-hidden="true"></i> ' . esc_html__( 'Switch between Pure CSS Grid and Masonry Grid (JavaScript based)', 'gridhub' ) . '</li>' .
                '<li><i class="fas fa-check" aria-hidden="true"></i> ' . esc_html__( 'Display Ads/Custom Contents between Posts in the Grid', 'gridhub' ) . '</li>' .
                '<li><i class="fas fa-check" aria-hidden="true"></i> ' . esc_html__( 'Switch between Boxed and Full Layout Type', 'gridhub' ) . '</li>' .
                '<li><i class="fas fa-check" aria-hidden="true"></i> ' . esc_html__( '10+ Layout Styles for Posts/Pages', 'gridhub' ) . '</li>' .
                '<li><i class="fas fa-check" aria-hidden="true"></i> ' . esc_html__( '10+ Layout Styles for Non-Singular Pages', 'gridhub' ) . '</li>' .
                '<li><i class="fas fa-check" aria-hidden="true"></i> ' . esc_html__( 'Width Change Options for Full Website/Main Content/Left Sidebar/Right Sidebar', 'gridhub' ) . '</li>' .
                '<li><i class="fas fa-check" aria-hidden="true"></i> ' . esc_html__( '10+ Custom Page Templates', 'gridhub' ) . '</li>' .
                '<li><i class="fas fa-check" aria-hidden="true"></i> ' . esc_html__( '10+ Custom Post Templates', 'gridhub' ) . '</li>' .
                '<li><i class="fas fa-check" aria-hidden="true"></i> ' . esc_html__( '2 Header Layouts with Width options - (Logo + Header Banner) / (Full Width Header)', 'gridhub' ) . '</li>' .
                '<li><i class="fas fa-check" aria-hidden="true"></i> ' . esc_html__( 'Footer with Layout Options (1/2/3/4/5/6 columns)', 'gridhub' ) . '</li>' .
                '<li><i class="fas fa-check" aria-hidden="true"></i> ' . esc_html__( 'Ability to Change Website Width/Layout Type/Layout Style/Header Style/Footer Style of any Post/Page', 'gridhub' ) . '</li>' .
                '<li><i class="fas fa-check" aria-hidden="true"></i> ' . esc_html__( 'Capability to Add Different Header Images for Each Post/Page with Unique Link, Title and Description', 'gridhub' ) . '</li>' .
                '<li><i class="fas fa-check" aria-hidden="true"></i> ' . esc_html__( 'Grid Featured Posts Widget (Recent/Categories/Tags/PostIDs based) - with capability to display posts according to Likes/Views/Comments/Dates/... and there are many options', 'gridhub' ) . '</li>' .
                '<li><i class="fas fa-check" aria-hidden="true"></i> ' . esc_html__( 'List Featured Posts Widget (Recent/Categories/Tags/PostIDs based) - with capability to display posts according to Likes/Views/Comments/Dates/... and there are many options', 'gridhub' ) . '</li>' .
                '<li><i class="fas fa-check" aria-hidden="true"></i> ' . esc_html__( 'Tabbed Widget (Recent/Categories/Tags/PostIDs based) - with capability to display posts according to Likes/Views/Comments/Dates/... and there are many options.', 'gridhub' ) . '</li>' .
                '<li><i class="fas fa-check" aria-hidden="true"></i> ' . esc_html__( 'About and Social Widget - 60+ Social Buttons', 'gridhub' ) . '</li>' .
                '<li><i class="fas fa-check" aria-hidden="true"></i> ' . esc_html__( 'News Ticker (Recent/Categories/Tags/PostIDs based) - It can display posts according to Likes/Views/Comments/Dates/... and there are many options.', 'gridhub' ) . '</li>' .
                '<li><i class="fas fa-check" aria-hidden="true"></i> ' . esc_html__( 'Settings Panel to Control Options in Each Post', 'gridhub' ) . '</li>' .
                '<li><i class="fas fa-check" aria-hidden="true"></i> ' . esc_html__( 'Settings Panel to Control Options in Each Page', 'gridhub' ) . '</li>' .
                '<li><i class="fas fa-check" aria-hidden="true"></i> ' . esc_html__( 'Built-in Posts Views Counter', 'gridhub' ) . '</li>' .
                '<li><i class="fas fa-check" aria-hidden="true"></i> ' . esc_html__( 'Built-in Posts Likes System', 'gridhub' ) . '</li>' .
                '<li><i class="fas fa-check" aria-hidden="true"></i> ' . esc_html__( 'Built-in Infinite Scroll and Load More Button', 'gridhub' ) . '</li>' .
                '<li><i class="fas fa-check" aria-hidden="true"></i> ' . esc_html__( 'Post Share Buttons Styles with Options - 25+ Social Networks are Supported', 'gridhub' ) . '</li>' .
                '<li><i class="fas fa-check" aria-hidden="true"></i> ' . esc_html__( 'Related Posts (Categories/Tags/Author/PostIDs based) with Many Options - For both single posts and post summaries', 'gridhub' ) . '</li>' .
                '<li><i class="fas fa-check" aria-hidden="true"></i> ' . esc_html__( 'Sticky Menu/Sticky Sidebars with enable/disable capability', 'gridhub' ) . '</li>' .
                '<li><i class="fas fa-check" aria-hidden="true"></i> ' . esc_html__( 'Author Bio Box with Social Buttons - 60+ Social Buttons', 'gridhub' ) . '</li>' .
                '<li><i class="fas fa-check" aria-hidden="true"></i> ' . esc_html__( 'Ability to Enable/Disable Mobile View from Primary and Secondary Menus', 'gridhub' ) . '</li>' .
                '<li><i class="fas fa-check" aria-hidden="true"></i> ' . esc_html__( 'Post Navigation with Thumbnails', 'gridhub' ) . '</li>' .
                '<li><i class="fas fa-check" aria-hidden="true"></i> ' . esc_html__( 'Ability to add Ads under Post Title and under Post Content', 'gridhub' ) . '</li>' .
                '<li><i class="fas fa-check" aria-hidden="true"></i> ' . esc_html__( 'Ability to Disable Google Fonts - for faster loading', 'gridhub' ) . '</li>' .
                '<li><i class="fas fa-check" aria-hidden="true"></i> ' . esc_html__( 'More Widget Areas', 'gridhub' ) . '</li>' .
                '<li><i class="fas fa-check" aria-hidden="true"></i> ' . esc_html__( 'Built-in Contact Form', 'gridhub' ) . '</li>' .
                '<li><i class="fas fa-check" aria-hidden="true"></i> ' . esc_html__( 'WooCommerce Compatible', 'gridhub' ) . '</li>' .
                '<li><i class="fas fa-check" aria-hidden="true"></i> ' . esc_html__( 'Yoast SEO Breadcrumbs Support', 'gridhub' ) . '</li>' .
                '<li><i class="fas fa-check" aria-hidden="true"></i> ' . esc_html__( 'Full RTL Language Support', 'gridhub' ) . '</li>' .
                '<li><i class="fas fa-check" aria-hidden="true"></i> ' . esc_html__( 'Search Engine Optimized', 'gridhub' ) . '</li>' .
                '<li><i class="fas fa-check" aria-hidden="true"></i> ' . esc_html__( 'Random Posts Button in Header', 'gridhub' ) . '</li>' .
                '<li><i class="fas fa-check" aria-hidden="true"></i> ' . esc_html__( 'Many Useful Customizer Theme options', 'gridhub' ) . '</li>' .
                '<li><i class="fas fa-check" aria-hidden="true"></i> ' . esc_html__( 'More Features...', 'gridhub' ) . '</li>' .
            '</ul>'.
            '<strong><a href="'.GRIDHUB_PROURL.'" class="button button-primary" target="_blank"><i class="fas fa-shopping-cart" aria-hidden="true"></i> ' . esc_html__( 'Upgrade To GridHub PRO', 'gridhub' ) . '</a></strong>'
    ) ) );

}

add_action( 'customize_register', 'gridhub_register_theme_customizer' );

/**
 * Render the site title for the selective refresh partial.
 *
 * @return void
 */
function gridhub_customize_partial_blogname() {
    bloginfo( 'name' );
}
/**
 * Render the site tagline for the selective refresh partial.
 *
 * @return void
 */
function gridhub_customize_partial_blogdescription() {
    bloginfo( 'description' );
}

function gridhub_customizer_js_scripts() {
    wp_enqueue_script('gridhub-theme-customizer-js', get_template_directory_uri() . '/assets/js/customizer.js', array( 'jquery', 'customize-preview' ), NULL, true);
}
add_action( 'customize_preview_init', 'gridhub_customizer_js_scripts' );