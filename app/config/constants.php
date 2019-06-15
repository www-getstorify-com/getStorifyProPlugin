<?php
/**
 * Author: Yusuf Shakeel
 * Date: 12-sep-2018 wed
 * Version: 1.0
 *
 * File: constants.php
 * Description: This page contains constants.
 */

define('GETSTORIFY_PLUGIN_WP_SITE_URL', get_home_url());
define('GETSTORIFY_PLUGIN_DIR', 'getStorifyProPlugin');
define('GETSTORIFY_PLUGIN_DIR_URL', GETSTORIFY_PLUGIN_WP_SITE_URL . '/wp-content/plugins/' . GETSTORIFY_PLUGIN_DIR);
//define('GETSTORIFY_PLUGIN_WP_SHORTCODE', 'getStorifyPlugin_shortcode');

// for wordpress version 5.0+
define('GETSTORIFY_PLUGIN_WP_SHORTCODE', 'getstorifyplugin_shortcode');

define('GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS', 'getStorifyPlugin_ShortCode_Params');

// WP_OPTIONS Name
// this goes inside the wp_options table
// and represents the value of the option_name column of the table
define('GETSTORIFY_PLUGIN_WP_OPTIONS_TABLE_OPTION_NAME_PLUGIN_SHORTCODE', 'getStorifyPluginOptions_shortcode');
define('GETSTORIFY_PLUGIN_WP_OPTIONS_TABLE_OPTION_NAME_PLUGIN_ROUTES', 'getStorifyPluginOptions_routes');
define('GETSTORIFY_PLUGIN_WP_OPTIONS_TABLE_OPTION_NAME_PLUGIN_USER_ID', 'getStorifyPluginOptions_user_id');
define('GETSTORIFY_PLUGIN_WP_OPTIONS_TABLE_OPTION_NAME_PLUGIN_APP_ID', 'getStorifyPluginOptions_app_id');
define('GETSTORIFY_PLUGIN_WP_OPTIONS_TABLE_OPTION_NAME_PLUGIN_APP_TOKEN', 'getStorifyPluginOptions_app_token');

define('GETSTORIFY_PLUGIN_WP_OPTIONS_TABLE_OPTION_NAME_PLUGIN_GOOGLE_API_KEY_WEB_KEY', 'getStorifyPluginOptions_googleApiKey');

// story
define('GETSTORIFY_PLUGIN_STORY_STATUS_PUBLISHED', 'PUBLISHED');
define('GETSTORIFY_PLUGIN_STORY_STATUS_GROUP_STORY', 'GROUP_STORY');

define('GETSTORIFY_PLUGIN_USER_DEFAULT_PROFILE_IMAGE', 'https://getstorify.com/storify/image/user/default-1.png');