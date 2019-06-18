<?php
/**
 * Author: Yusuf Shakeel
 * Date: 07-jan-2019 mon
 * Version: 1.0
 *
 * File: getStorifyPlugin-routes.php
 * Description: This page contains the routes of this plugin.
 */

/**
 * This function holds the routes handling logic.
 */
function getStorifyPlugin_Routes_func()
{
    $url = $_SERVER['REQUEST_URI'];

    if (isset($url)) {

        /**
         * remove the leading/trailing forward slashes
         *
         * example:
         * input: '/sample/story/hello-world-gs123/'
         * output: 'sample/story/hello-world-gs123'
         *
         */
        $url = trim($url, '/');

        /**
         * create an array of url slug parts
         *
         * example:
         * input: 'sample/story/hello-world-gs123'
         * output: ['sample', 'story', 'hello-world-gs123']
         *
         */
        $urlParts = explode("/", $url);

        /**
         * find the total number of url slug parts
         *
         * example:
         * input: ['sample', 'story', 'hello-world-gs123']
         * output: 3
         *
         */
        $urlPartsArrLen = count($urlParts);

        /**
         * set the $_GET
         *
         * note!
         * the 2nd last element is the key and the last element is the value.
         *
         * example:
         * input: ['sample', 'story', 'hello-world-gs123']
         * output:
         *   key: 2nd last elem of the arr = 'story'
         *   value: last elem of the arr = 'hello-world-gs123'
         *
         */
        switch ($urlParts[$urlPartsArrLen - 2]) {

            /**
             * if requesting a specific story
             */
            case 'story':
                /**
                 * get the story id
                 *
                 * 1. trim the last element of the $urlParts array
                 * 2. convert the value from step 1 in to an array of strings using '-' as delimiter
                 * 3. pop the last item of the array from step 2
                 *
                 */
                $_GET['gs-storyid'] = array_pop(explode("-", trim($urlParts[$urlPartsArrLen - 1])));
                break;

            /**
             * if requesting a specific page
             */
            case 'page':
                $_GET['gs-page'] = intval($urlParts[$urlPartsArrLen - 1]);
                break;
        }

    }

}

/**
 * This function will add new route rule.
 *
 * @param string $slug This is the page, post that has the plugin shortcode.
 *                     Example: If page, post URL: https://example.com/helloworld then, slug = helloworld
 * @return bool
 */
function getStorifyPlugin_Routes_AddRouteRule_func($slug)
{
    // plugin routes
    $routes = get_option(GETSTORIFY_PLUGIN_WP_OPTIONS_TABLE_OPTION_NAME_PLUGIN_ROUTES);

    // if routes exists
    if ($routes !== false) {
        $routes = json_decode($routes, true);
    }

    if (!is_array($routes)) {
        $routes = [];
    }

    // if adding a new slug
    if (isset($slug) && is_string($slug)) {

        // if slug not yet added then add it
        if (!in_array($slug, $routes)) {

            array_push($routes, $slug);

//            // create rewrite rules
//            foreach ($routes as $route) {
//
//                add_rewrite_rule(
//                    "$route/([0-9a-z-A-Z\_\-]+)/([0-9a-zA-Z\_\-]+)/?$",
//                    "index.php/$route",
//                    "top"
//                );
//
//            }
//
//            // save routes in db
//            update_option(
//                GETSTORIFY_PLUGIN_WP_OPTIONS_TABLE_OPTION_NAME_PLUGIN_ROUTES,
//                json_encode($routes)
//            );
//
//            flush_rewrite_rules();

        }
//        else if (in_array($slug)) {
//            return true;
//        }

        sort($routes);

        // create rewrite rules
        foreach ($routes as $route) {

            getStorifyPlugin_WP_add_rewrite_rule_func($route);

        }

        // save routes in db
        update_option(
            GETSTORIFY_PLUGIN_WP_OPTIONS_TABLE_OPTION_NAME_PLUGIN_ROUTES,
            json_encode($routes)
        );

//        flush_rewrite_rules();

    } else {

        return false;

    }

    return true;
}

/**
 * This function will remove route rule.
 *
 * @param string $slug This is the page, post that has the plugin shortcode.
 *                     Example: If page, post URL: https://example.com/helloworld then, slug = helloworld
 * @return bool
 */
function getStorifyPlugin_Routes_RemoveRouteRule_func($slug)
{
    // plugin routes
    $routes = get_option(GETSTORIFY_PLUGIN_WP_OPTIONS_TABLE_OPTION_NAME_PLUGIN_ROUTES);

    // if routes exists
    if ($routes !== false) {
        $routes = json_decode($routes, true);
    }

    if (!is_array($routes)) {
        $routes = [];
    }

    // if adding a new slug
    if (isset($slug) && is_string($slug)) {

        // if slug in routes
        if (in_array($slug, $routes)) {

            array_splice($routes, array_search($slug, $routes), 1);

//            // create rewrite rules
//            foreach ($routes as $route) {
//
//                add_rewrite_rule(
//                    "$route/([0-9a-z-A-Z\_\-]+)/([0-9a-zA-Z\_\-]+)/?$",
//                    "index.php/$route",
//                    "top"
//                );
//
//            }

            sort($routes);

            // create rewrite rules
            foreach ($routes as $route) {

                getStorifyPlugin_WP_add_rewrite_rule_func($route);

            }

            // save routes in db
            update_option(
                GETSTORIFY_PLUGIN_WP_OPTIONS_TABLE_OPTION_NAME_PLUGIN_ROUTES,
                json_encode($routes)
            );

//            flush_rewrite_rules();

        }

    } else {

        return false;

    }

    return true;
}

/**
 * This function will reload route rule.
 */
function getStorifyPlugin_Routes_ReloadRouteRule_func()
{
    // plugin routes
    $routes = get_option(GETSTORIFY_PLUGIN_WP_OPTIONS_TABLE_OPTION_NAME_PLUGIN_ROUTES);

    // if routes exists
    if ($routes !== false) {
        $routes = json_decode($routes, true);
    }

    if (!is_array($routes)) {
        $routes = [];
    }

    // create rewrite rules
    foreach ($routes as $route) {

        getStorifyPlugin_WP_add_rewrite_rule_func($route);

    }
}

/**
 * This will add the rewrite rule using wp add_rewrite_rule function.
 *
 * @param string $route
 */
function getStorifyPlugin_WP_add_rewrite_rule_func($route)
{
    /**
     * this will handle the story
     */
    add_rewrite_rule(
        $route . '/story/([0-9a-zA-Z\_\-]+)/?$',
        'index.php?pagename=' . $route . '&gs-storyid=$matches[1]',
        'top'
    );

    /**
     * this will handle the page
     */
    add_rewrite_rule(
        $route . '/page/([0-9]+)/?$',
        'index.php?pagename=' . $route . '&gs-page=$matches[1]',
        'top'
    );
}

/**
 * This function will return the list of routes slug added by the user.
 *
 * @return string
 */
function getStorifyPlugin_List_AddedRoutes_func()
{
    // plugin routes
    $routes = get_option(GETSTORIFY_PLUGIN_WP_OPTIONS_TABLE_OPTION_NAME_PLUGIN_ROUTES);

    // if routes exists
    if ($routes !== false) {
        $routes = json_decode($routes, true);
    }

    if (!is_array($routes)) {
        $routes = [];
    }

    $siteurl = get_site_url();

    // create routes list
    $html = "";
    foreach ($routes as $route) {

        $href = $siteurl . "/" . $route;

        $html .= <<<HTML
<span style="padding: 10px; display: inline-block; margin-right: 15px; margin-bottom: 15px; font-size: 18px; border: 1px solid #87cefa; border-left: 5px solid #87cefa;">
<a href="{$href}">{$route}</a>
</span>
HTML;
    }

    return $html;

}