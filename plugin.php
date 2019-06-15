<?php
/**
 * @package getStorifyProPlugin
 */

/*
Plugin Name: getStorify Pro Plugin
Plugin URI: https://getstorify.com
Description: This is the getStorify Pro Plugin for WordPress. Use this to show your getStorify stories in your WordPress posts and pages.
Version: 2.4.0
Author: getStorify
Author URI: https://getstorify.com
License: MIT License
Text domain: getStorify Pro Plugin
*/

require_once __DIR__ . '/app/config/constants.php';
require_once __DIR__ . '/app/getStorifyPlugin-routes.php';

/**
 * ====================================================================
 * getStorify Pro Plugin - reload route rules if they exists
 *
 * check the app/getStorifyPlugin-routes.php file for the
 * getStorifyPlugin_Routes_ReloadRouteRule_func() function.
 *
 */
add_action('init', 'getStorifyPlugin_Routes_ReloadRouteRule_func');
//========= getstorify pro plugin add rewrite rule ends here ==========

/**
 * ====================================================================
 * getStorify Pro Plugin activation code
 */
function getStorifyProPlugin_pluginActivate_func()
{
    add_option('getStorifyPlugin_Activated_Plugin', 'getStorifyPlugin_Activated');
}

register_activation_hook(__FILE__, 'getStorifyProPlugin_pluginActivate_func');

//========= getstorify pro plugin activation code ends here ===========

/**
 * ====================================================================
 * Add getStorify Pro Plugin to admin_menu
 */
function getStorifyProPluginSetupPageHTML_func()
{
    require_once __DIR__ . '/app/view/page/wp-setup-page.php';
}

function getStorifyProPlugin_AdminMenuAction_func()
{
    add_options_page("getStorify Pro Plugin", "getStorify Pro Plugin", 1, "getStorifyProPlugin", "getStorifyProPluginSetupPageHTML_func");

    /* do stuff once right after activation */
    if (get_option('getStorifyPlugin_Activated_Plugin') == 'getStorifyPlugin_Activated') {

        delete_option('getStorifyPlugin_Activated_Plugin');

        // if router options does not exists then create it
        if (!get_option(GETSTORIFY_PLUGIN_WP_OPTIONS_TABLE_OPTION_NAME_PLUGIN_ROUTES)) {
            update_option(
                GETSTORIFY_PLUGIN_WP_OPTIONS_TABLE_OPTION_NAME_PLUGIN_ROUTES,
                ''
            );
        }

        // set the shortcode
        update_option(
            GETSTORIFY_PLUGIN_WP_OPTIONS_TABLE_OPTION_NAME_PLUGIN_SHORTCODE,
            GETSTORIFY_PLUGIN_WP_SHORTCODE
        );

        // reload routes
        getStorifyPlugin_Routes_ReloadRouteRule_func();

    }
}

add_action('admin_menu', 'getStorifyProPlugin_AdminMenuAction_func');

//========= add getStorify Pro plugin to admin_menu ends here =========

/**
 * ====================================================================
 * Add getStorify Pro Plugin shortcode
 */
function getStorifyProPlugin_ShortCode_func($atts = [])
{
    // load and handle the routes
    require_once __DIR__ . '/app/getStorifyPlugin-routes.php';
    getStorifyPlugin_Routes_func();

    // save the shortcode attributes
    $GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS] = $atts;

    // require the shortcode
    require_once __DIR__ . '/app/wp-shortcode.php';

    echo getStorifyPlatformProPlugin_ShortCode_getResultHTML_func();
}

add_shortcode(get_option(GETSTORIFY_PLUGIN_WP_OPTIONS_TABLE_OPTION_NAME_PLUGIN_SHORTCODE), 'getStorifyProPlugin_ShortCode_func');

//========= add getStorify Pro plugin shortcode ends here =============