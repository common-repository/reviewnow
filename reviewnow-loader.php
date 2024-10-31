<?php

class ReviewNowLoader {

    public static function Enable() {
        
    }

    /**
     * Handled the plugin activation on installation
     *
     * @uses ReviewNowLoader::ActivateRewrite
     */
    public static function ActivatePlugin() {
        self::SetupRewriteHooks();
        self::ActivateRewrite();
    }

    /**
     * Handled the plugin deactivation
     *
     * @uses ReviewNowLoader::ActivateRewrite
     */
    public static function DeactivatePlugin() {
        delete_option("reviewnow_rewrite_done");
    }

    public static function ActivateTranslattion() {
        load_plugin_textdomain('reviewnow', FALSE, basename(dirname(__FILE__)) . '/languages/');
    }

    public static function ActivateRewrite() {
        /** @var $wp_rewrite WP_Rewrite */
        global $wp_rewrite;
        $wp_rewrite->flush_rules(false);
    }

    public static function AddRewriteRules($wpRules) {
        $smRules = array(
            'reviewnow/k/([a-zA-Z0-9_-]+)$' => 'index.php?reviewnow_review_key=$matches[1]',
            'reviewnow/([a-zA-Z0-9_-]+)$' => 'index.php?reviewnow_service=$matches[1]'
        );
        return array_merge($smRules, $wpRules);
    }

    public static function SetupRewriteHooks() {
        add_filter('rewrite_rules_array', array(__CLASS__, 'AddRewriteRules'), 1, 1);
    }

    public static function SetupQueryVars() {
        add_filter('query_vars', array(__CLASS__, 'RegisterQueryVars'), 1, 1);
        add_filter('template_redirect', array(__CLASS__, 'DoTemplateRedirect'), 1, 0);
    }

    public static function RegisterQueryVars($vars) {
        array_push($vars, 'reviewnow_service');
        array_push($vars, 'reviewnow_service_id');
        array_push($vars, 'reviewnow_review_key');
        return $vars;
    }

    public static function DoTemplateRedirect() {
        /** @var $wp_query WP_Query */
        global $wp_query;
        if (!empty($wp_query->query_vars["reviewnow_service"])) {
            $wp_query->is_404 = false;
            $wp_query->is_feed = false;
            self::CallShowRedirect($wp_query->query_vars["reviewnow_service"]);
        } else if (!empty($wp_query->query_vars["reviewnow_review_key"])) {
            $wp_query->is_404 = false;
            $wp_query->is_feed = false;
            self::CallShowReview($wp_query->query_vars["reviewnow_review_key"]);
        }
    }

    public static function CallShowReview($service) {
        die("Not implemented!");
    }

    public static function CallShowRedirect($service) {
        $options = get_option('reviewnow_plugin_options');
        $url = get_site_url();
        switch ($service) {
            case "googleplaces":
                if (!empty($options['googleplaces_id'])) {
                    $url = "https://search.google.com/local/writereview?placeid=" . $options['googleplaces_id'];
                }
                break;
            case "linkedin":
            case "facebook":
            case "tripadvisor":
                if (!empty($options[$service.'_id'])) {
                    $urljson = file_get_contents("https://api.reviewnow.io/reviewurl?plattform=" . $service . "&plattformid=" . urlencode($options[$service.'_id']));
                    if (!empty($urljson)) {
                        $urldata = json_decode($urljson);
                        if (!empty($urldata->url)) {
                            $url = $urldata->url;
                        }
                    }
                }
                break;
            default :
                break;
        }
        header("Location: " . $url);
        die();
    }

    public static function isAdmin() {
        if (is_admin()) {
            return true;
        }
        return false;
    }

    public static function isWPCLI() {
        if (defined('WP_CLI') && WP_CLI) {
            return true;
        }
        return false;
    }

}

//Enable the plugin for the init hook, but only if WP is loaded. Calling this php file directly will do nothing.
if (defined('ABSPATH') && defined('WPINC')) {
    add_action("init", array("ReviewNowLoader", "Enable"), 15, 0);
    add_action('plugins_loaded', array("ReviewNowLoader", 'ActivateTranslattion'));
    register_activation_hook(reviewnow_GetInitFile(), array('ReviewNowLoader', 'ActivatePlugin'));
    register_deactivation_hook(reviewnow_GetInitFile(), array('ReviewNowLoader', 'DeactivatePlugin'));
    //Set up hooks for adding permalinks, query vars.
    //Don't wait until init with this, since other plugins might flush the rewrite rules in init already...
    ReviewNowLoader::SetupQueryVars();
    ReviewNowLoader::SetupRewriteHooks();

    if (ReviewNowLoader::isAdmin() || ReviewNowLoader::isWPCLI()) {
        require_once( plugin_dir_path(__FILE__) . 'reviewnow-admin.php' );
        add_action('init', array('ReviewNow_Admin', 'init'));
    }
}
