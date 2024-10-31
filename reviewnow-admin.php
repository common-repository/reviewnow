<?php

defined('ABSPATH') or die('This page may not be accessed directly.');

class ReviewNow_Admin {

    private static $arrMenuPages = array();

    public static function init() {
        add_action('admin_menu', array(__CLASS__, 'add_admin_page'));
        add_action('admin_init', array(__CLASS__, 'register_settings'));
    }

    public static function add_admin_page() {
        $role = "manage_options";
        self::addMenuPage('ReviewNow', 'admin_dashboard');

        foreach (ReviewNow_Admin::$arrMenuPages as $menu) {
            $title = $menu["title"];
            $pageFunctionName = $menu["pageFunction"];
            $SnapShotBoard_menu = add_menu_page($title, $title, $role, 'reviewnow-admin', array(__CLASS__, $pageFunctionName), 'dashicons-thumbs-up');
            add_action('load-' . $SnapShotBoard_menu, array(__CLASS__, 'admin_custom_load'));
            $ssb_screens[] = $SnapShotBoard_menu;
        }
    }

    public static function admin_custom_load() {
        
    }

    public static function admin_dashboard() {
        require_once( plugin_dir_path(__FILE__) . 'views/dashboard.php' );
    }

    protected static function addMenuPage($title, $pageFunctionName) {
        self::$arrMenuPages[] = array("title" => $title, "pageFunction" => $pageFunctionName);
    }

    function register_settings() {
        register_setting('reviewnow_plugin_options', 'reviewnow_plugin_options', 'reviewnow_plugin_options_validate');
        add_settings_section('google_places_settings', 'Google Places', 'reviewnow_plugin_section_text', 'reviewnow_plugin');
        add_settings_field('reviewnow_plugin_setting_googleplaces_id', 'Google Places Id', 'reviewnow_plugin_setting_googleplaces_id', 'reviewnow_plugin', 'google_places_settings');
        add_settings_section('linkedin_settings', 'LinkedIn', 'reviewnow_plugin_section_text', 'reviewnow_plugin');
        add_settings_field('reviewnow_plugin_setting_linkedin_id', 'LinkedIn', 'reviewnow_plugin_setting_linkedin_id', 'reviewnow_plugin', 'linkedin_settings');
        add_settings_section('facebook_settings', 'Facebook', 'reviewnow_plugin_section_text', 'reviewnow_plugin');
        add_settings_field('reviewnow_plugin_setting_facebook_id', 'Facebook', 'reviewnow_plugin_setting_facebook_id', 'reviewnow_plugin', 'facebook_settings');
        add_settings_section('tripadvisor_settings', 'Tripadvisor', 'reviewnow_plugin_section_text', 'reviewnow_plugin');
        add_settings_field('reviewnow_plugin_setting_tripadvisor_id', 'Tripadvisor', 'reviewnow_plugin_setting_tripadvisor_id', 'reviewnow_plugin', 'tripadvisor_settings');
    }

}

function reviewnow_plugin_setting_googleplaces_id() {
    $options = get_option('reviewnow_plugin_options');
    echo '<p>Use a tool like <a target="_blank" href="https://www.revilodesign.de/tools/google-maps-api-place-id-finder/">Google Maps Place ID Finder</a> to find your Google Places ID and enter it below:<br /><br />';
    echo "<input id='reviewnow_plugin_setting_googleplaces_id' name='reviewnow_plugin_options[googleplaces_id]' type='text' value='". $options['googleplaces_id'] . "' />";
    if (!empty($options['googleplaces_id'])) {
        echo "<h4>". __("Your Google-Places-Review-URL", "reviewnow") . ":</h4><a target='_blank' href='".get_site_url()."/reviewnow/googleplaces/'>".get_site_url()."/reviewnow/googleplaces/</a>";
    }
}

function reviewnow_plugin_setting_linkedin_id() {
    $options = get_option('reviewnow_plugin_options');
    echo '<p>'. __("Enter your LinkedIn personal profile URL below", "reviewnow") . ':<br /><br />';
    echo "<input id='reviewnow_plugin_setting_linkedin_id' name='reviewnow_plugin_options[linkedin_id]' type='text' value='". $options['linkedin_id'] . "' />";
    if (!empty($options['linkedin_id'])) {
        echo "<h4>". __("Your LinkedIn-Recommendation-URL", "reviewnow") . ":</h4><a target='_blank' href='".get_site_url()."/reviewnow/linkedin/'>".get_site_url()."/reviewnow/linkedin/</a>";
    }
}

function reviewnow_plugin_setting_facebook_id() {
    $options = get_option('reviewnow_plugin_options');
    echo '<p>'. __("Enter your Facebook PAGE URL below", "reviewnow") . ':<br /><br />';
    echo "<input id='reviewnow_plugin_setting_facebook_id' name='reviewnow_plugin_options[facebook_id]' type='text' value='". $options['facebook_id'] . "' />";
    if (!empty($options['facebook_id'])) {
        echo "<h4>". __("Your Facebook-Reviews-URL", "reviewnow") . ":</h4><a target='_blank' href='".get_site_url()."/reviewnow/facebook/'>".get_site_url()."/reviewnow/facebook/</a>";
    }
}

function reviewnow_plugin_setting_tripadvisor_id() {
    $options = get_option('reviewnow_plugin_options');
    echo '<p>'. __("Enter your Tripadvisor URL below", "reviewnow") . ':<br /><br />';
    echo "<input id='reviewnow_plugin_setting_tripadvisor_id' name='reviewnow_plugin_options[tripadvisor_id]' type='text' value='". $options['tripadvisor_id'] . "' />";
    if (!empty($options['tripadvisor_id'])) {
        echo "<h4>". __("Your Tripadvisor-Reviews-URL", "reviewnow") . ":</h4><a target='_blank' href='".get_site_url()."/reviewnow/tripadvisor/'>".get_site_url()."/reviewnow/tripadvisor/</a>";
    }
}
