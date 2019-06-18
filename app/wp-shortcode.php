<?php
/**
 * Author: Yusuf Shakeel
 * Date: 17-sep-2018 mon
 * Version: 1.0
 *
 * File: wp-shortcode.php
 * Description: This page contains the WP shortcode of this plugin.
 */

function getStorifyPlatformProPlugin_ShortCode_getResultHTML_func()
{
    /**
     * constants
     */
    require_once __DIR__ . '/config/constants.php';

    /**
     * Template
     */
    require_once __DIR__ . '/view/template/GetStorify_StoryTemplate.php';
    require_once __DIR__ . '/view/template/GetStorify_PostTemplate.php';

    /**
     * Autoload getStorify API Service SDK
     */
    require_once __DIR__ . '/GetStorify/autoload.php';

    /**
     * set this with necessary credentials.
     */
    $app_id = get_option('getStorifyPluginOptions_app_id');
    $app_token = get_option('getStorifyPluginOptions_app_token');
    $app_user_id = get_option('getStorifyPluginOptions_user_id');

    define('GETSTORIFY_API_SERVICE_AUTH_APPID', $app_id);
    define('GETSTORIFY_API_SERVICE_AUTH_USERID', $app_user_id);
    define('GETSTORIFY_API_SERVICE_AUTH_APPTOKEN', $app_token);

    /**
     * short code result
     */
    $shortCodeResultHTML = '';

    /**
     * create object
     */
    $GetStorifyAPIServiceObj = new \GetStorify\GetStorify(
        GETSTORIFY_API_SERVICE_AUTH_APPID,
        GETSTORIFY_API_SERVICE_AUTH_USERID,
        GETSTORIFY_API_SERVICE_AUTH_APPTOKEN
    );

    /**
     * get access token
     */
    $result = $GetStorifyAPIServiceObj->getAccessToken();

    /**
     * is access token issued
     */
    if (isset($result['success'])) {

        /**
         * =================================================
         * get the shortcode params
         * =================================================
         */

        // get from the shortcode: view
        if (isset($GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['view'])) {
            $shortcode_view = $GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['view'];
        } else {

            // default view
            $shortcode_view = "STORY";
        }

        // get from the shortcode: total number of stories to fetch per page
        if (isset($GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['story_per_page'])) {
            $pagelimit = intval($GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['story_per_page']);
        } else {
            $pagelimit = GETSTORIFY_API_SERVICE_DB_PAGE_LIMIT;
        }
        // correction of pagelimit if required
        if ($pagelimit > 10 || $pagelimit < 1) {
            $pagelimit = GETSTORIFY_API_SERVICE_DB_PAGE_LIMIT;
        }

        // get from the shortcode: storyid
        $isShowingBackButton = true;
        if (isset($GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]) && isset($GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['storyid'])) {
            $storyid = $GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['storyid'];
            $isShowingBackButton = false;
        } else {
            $storyid = null;
        }

        // get from the shortcode: postid
        if (isset($GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['postid'])) {
            $postid = $GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['postid'];
        } else {
            $postid = null;
        }

        // get from the shortcode: posttype
        if (isset($GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['posttype'])) {
            $posttype = $GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['posttype'];
        } else {
            $posttype = null;
        }

        // get from the shortcode: story_category
        if (isset($GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['story_category'])) {
            $story_category = $GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['story_category'];
        } else {
            $story_category = null;
        }

        // get from the shortcode: gallery_item_per_page
        if (isset($GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['gallery_item_per_page'])) {
            $gallery_item_per_page = intval($GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['gallery_item_per_page']);

            if ($gallery_item_per_page < 1 || $gallery_item_per_page > 20) {
                $gallery_item_per_page = 20;
            }

        } else {
            $gallery_item_per_page = 10;
        }

        /**
         * =================================================
         * get the shortcode params ends here
         * =================================================
         */

        /**
         * =================================================
         * get data from $_GET
         * =================================================
         */

        // get storyid
        if (!isset($storyid) && isset($_GET['gs-storyid'])) {
            $storyid = $_GET['gs-storyid'];
        } else {
            $storyid = null;
        }

        // get page
        if (isset($_GET['gs-page'])) {
            $page = intval($_GET['gs-page']);
            if ($page < 1) {
                $page = 1;
            }
        } else {
            $page = 1;
        }

        /**
         * =================================================
         * get data from $_GET ends here
         * =================================================
         */


        // handle the view
        switch ($shortcode_view) {

            /**
             * show the story view
             */
            case 'STORY':

                /**
                 * ========================================================
                 * fetch specific story by id
                 * ========================================================
                 */
                if (isset($storyid)) {

                    // get story
                    $result = $GetStorifyAPIServiceObj->getStory($storyid, 1, 1);

                    /**
                     * prepare the view
                     */
                    if (isset($result['success'])) {

                        $result = $result['success'][0];

                        $userid = $result['userid'];

                        // get html
                        if (isset($GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['display_only'])) {

                            $html = GetStorify_StoryTemplate::getStoryCoverCard_DisplayOnly_MDB($result);

                        } else {

                            // show/hide story masthead - story cover, title, description, user details
                            if (isset($GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['display_story_masthead']) &&
                                $GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['display_story_masthead'] === 'none'
                            ) {
                                $html = "";
                            } else {
                                // get html
                                $html = GetStorify_StoryTemplate::getStoryCoverCard_MDB($result);
                            }

                        }

                        // story cover card html
                        $shortCodeResultHTML .= $html;

                        // get posts
                        $postHTML = "";
                        $postResult = [];
                        $isAllPostFetched = false;
                        $postPage = 1;
                        while (!$isAllPostFetched) {

                            $post_pagelimit = 10;

                            $postData = $GetStorifyAPIServiceObj->getPost($storyid, null, $postPage, $post_pagelimit);

                            if (isset($postData['success'])) {

                                foreach ($postData['success'] as $postRow) {
                                    array_push($postResult, $postRow);
                                }

                            } else {
                                $isAllPostFetched = true;
                            }

                            $postPage++;

                        }
                        if (count($postResult) > 0) {
                            $postHTML = GetStorify_PostTemplate::getPost_MDB($postResult, 'no', 'no');
                        }

                        // posts html
                        $shortCodeResultHTML .= $postHTML;

                        // back button
                        if ($isShowingBackButton) {

                            $permaLink = get_permalink();

                            $shortCodeResultHTML .= <<<HTML
<div class="getstorify prev-next-btn-container">
<a href="{$permaLink}" class="getstorify prev-next-btn"> &larr; Back </a>
</div>
HTML;

                        }

                    }

                }
                /**
                 * ========================================================
                 * fetch specific story by id ends here
                 * ========================================================
                 */


                /**
                 * ========================================================
                 * fetch all stories
                 * ========================================================
                 */
                else {

                    // get all PUBLISHED stories
                    $result = $GetStorifyAPIServiceObj->getStory(null, $page, $pagelimit, GETSTORIFY_PLUGIN_STORY_STATUS_PUBLISHED);

                    /**
                     * prepare the view
                     */
                    if (isset($result['success'])) {

                        $result = $result['success'];

                        // get html
                        if (isset($GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['display_only'])) {

                            $html = GetStorify_StoryTemplate::getStoryCard_DisplayOnly_MDB($result);

                        } else if (isset($GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['story_cover_carousel'])) {

                            $html = GetStorify_StoryTemplate::getStoryCard_StoryCoverCarousel_MDB($result);

                        } else {

                            $html = GetStorify_StoryTemplate::getStoryCard_MDB($result);

                        }

                        // story card html
                        $shortCodeResultHTML .= <<<HTML
<div class="getstorify-twbs">
<div class="container-fluid">
<div class="row">
{$html}
</div>
</div>
</div>
HTML;

                        // get nav html
                        $html = GetStorify_StoryTemplate::getPrevNextButtons(count($result), $page, $pagelimit);

                        // echo prev next button
                        $shortCodeResultHTML .= $html;

                    } else if (isset($result['error'])) {

                        if (isset($result['message']) && $result['message'] === 'No match found') {

                            $shortCodeResultHTML .= <<<HTML
<p>You have reached the end.</p>
HTML;

                        } else {

                            $shortCodeResultHTML .= <<<HTML
<h3>An error occurred while processing your request.</h3>
<p>Error: {$result['message']}</p>
HTML;


                        }

                    } else {

                        $shortCodeResultHTML .= <<<HTML
<h3>Failed to fetch data. Please try again later.</h3>
HTML;

                    }

                }
                /**
                 * ========================================================
                 * fetch all stories ends here
                 * ========================================================
                 */

                break;


            /**
             * showing the group view
             */
            case 'GROUP':

                /**
                 * ========================================================
                 * fetch specific group story by id
                 * ========================================================
                 */
                if (isset($storyid)) {

                    if (isset($GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['grouphandle']) || isset($GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['groupid'])) {

                        $groupid = isset($GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['groupid']) ? $GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['groupid'] : null;
                        $grouphandle = isset($GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['grouphandle']) ? $GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['grouphandle'] : null;

                    } else {

                        return <<<HTML
<h3>"groupid" missing in the shortcode for the "GROUP" view.</h3>
HTML;

                    }

                    // get specific story of a group
                    $result = $GetStorifyAPIServiceObj->getGroupStories($groupid, $storyid, 1, 1);

                    // get prev-next stories of the selected story of the group
                    $resultPrevNextStory_Group = null;
                    if ($result['success']) {
                        $resultPrevNextStory_Group = $GetStorifyAPIServiceObj->getGroupStories_PrevNextStories($groupid, $storyid);
                    }

                    /**
                     * prepare the view
                     */
                    if (isset($result['success'])) {

                        $result = $result['success'][0];

                        $userid = $result['userid'];

                        // get html
                        if (isset($GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['display_only'])) {

                            $html = GetStorify_StoryTemplate::getStoryCoverCard_DisplayOnly_MDB($result);

                        } else {

                            // show/hide story masthead - story cover, title, description, user details
                            if (isset($GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['display_story_masthead']) &&
                                $GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['display_story_masthead'] === 'none'
                            ) {
                                $html = "";
                            } else {
                                // get html
                                $html = GetStorify_StoryTemplate::getStoryCoverCard_MDB($result);
                            }

                        }


                        // story cover card html
                        $shortCodeResultHTML .= $html;

                        // get posts
                        $postHTML = "";
                        $postResult = [];
                        $isAllPostFetched = false;
                        $postPage = 1;
                        while (!$isAllPostFetched) {

                            $post_pagelimit = 10;

                            $postData = $GetStorifyAPIServiceObj->getGroupStoryPost($storyid, $groupid, $grouphandle, null, null, $postPage, $post_pagelimit);

                            if (isset($postData['success'])) {

                                foreach ($postData['success'] as $postRow) {
                                    array_push($postResult, $postRow);
                                }

                            } else {
                                $isAllPostFetched = true;
                            }

                            $postPage++;

                        }
                        if (count($postResult) > 0) {
                            $postHTML = GetStorify_PostTemplate::getPost_MDB($postResult, 'no', 'no');
                        }

                        // individual posts
                        $shortCodeResultHTML .= $postHTML;


                        /**
                         * add prev-next button for group story
                         */

                        $permaLink = get_permalink();

                        // show prev next button
                        $prevBtn = "";
                        $nextBtn = "";

                        /**
                         * Note!
                         *
                         * If following are the group stories.
                         *
                         * S10
                         * S9
                         * S8
                         * S7
                         * S6
                         * S5
                         * S4
                         * .
                         *
                         * Stories are listed in latest first order.
                         *
                         * If S7 is the current story
                         * then, S6 is the prev story and S8 is the next story.
                         *
                         * S10
                         * S9
                         * S8  <--- next
                         * S7  <--- curr
                         * S6  <--- prev
                         * S5
                         * S4
                         * .
                         */

                        if (isset($resultPrevNextStory_Group['success'])) {

                            if (isset($resultPrevNextStory_Group['success']['prev'])) {

                                $wp_story_slug = GetStorify_StoryTemplate::getWPStorySlugLink(
                                    $resultPrevNextStory_Group['success']['prev']['storyid'],
                                    $resultPrevNextStory_Group['success']['prev']['storytitle']
                                );

                                $prevBtn = <<<HTML
<a href="{$permaLink}{$wp_story_slug}" class="getstorify prev-next-btn"> &larr; Prev </a>
HTML;

                            }

                            if (isset($resultPrevNextStory_Group['success']['next'])) {

                                $wp_story_slug = GetStorify_StoryTemplate::getWPStorySlugLink(
                                    $resultPrevNextStory_Group['success']['next']['storyid'],
                                    $resultPrevNextStory_Group['success']['next']['storytitle']
                                );

                                $nextBtn = <<<HTML
<a href="{$permaLink}{$wp_story_slug}" class="getstorify prev-next-btn float-right"> Next &rarr; </a>
HTML;

                            }

                            // append the button on the page
                            $shortCodeResultHTML .= <<<HTML
<div class="col-sm-12 col-md-12 col-lg-12">
<div class="getstorify prev-next-btn-container">
{$prevBtn}
{$nextBtn}
</div>
</div>
HTML;

                        }

                    }

                }
                /**
                 * ========================================================
                 * fetch specific group story by id ends here
                 * ========================================================
                 */


                /**
                 * ========================================================
                 * fetch all group stories
                 * ========================================================
                 */
                else {

                    if (isset($GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['grouphandle']) || isset($GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['groupid'])) {

                        $groupid = isset($GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['groupid']) ? $GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['groupid'] : null;
                        $grouphandle = isset($GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['grouphandle']) ? $GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['grouphandle'] : null;

                        // get group
                        $groupDetailResult = $GetStorifyAPIServiceObj->getGroupDetail($groupid, $grouphandle);

                    } else {
                        return <<<HTML
<h3>"groupid" missing in the shortcode for the "GROUP" view.</h3>
HTML;
                    }

                    if (isset($groupDetailResult['success'])) {

                        $groupid = $groupDetailResult['success'][0]['groupid'];
                        $result = $GetStorifyAPIServiceObj->getGroupStories($groupid, null, $page, $pagelimit);

                    } else {
                        return <<<HTML
<h3>Group not found! Check the "groupid".</h3>
HTML;
                    }

                    /**
                     * prepare the view
                     */
                    if (isset($result['success'])) {

                        $result = $result['success'];

                        // get html
                        if (isset($GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['display_only'])) {

                            $html = GetStorify_StoryTemplate::getStoryCard_DisplayOnly_MDB($result);

                        } else if (isset($GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['story_cover_carousel'])) {

                            $html = GetStorify_StoryTemplate::getStoryCard_StoryCoverCarousel_MDB($result);

                        } else {

                            $html = GetStorify_StoryTemplate::getStoryCard_MDB($result);

                        }

                        // echo story card html
                        $shortCodeResultHTML .= <<<HTML
<div class="getstorify-twbs">
<div class="container-fluid">
<div class="row">
{$html}
</div>
</div>
</div>
HTML;

                        // get nav html
                        $html = GetStorify_StoryTemplate::getPrevNextButtons(count($result), $page, $pagelimit);

                        // prev next button
                        $shortCodeResultHTML .= $html;

                    } else if (isset($result['error'])) {

                        if (isset($result['message']) && $result['message'] === 'No match found') {

                            $shortCodeResultHTML .= <<<HTML
<p>You have reached the end.</p>
HTML;

                        } else {

                            $shortCodeResultHTML .= <<<HTML
<h3>An error occurred while processing your request.</h3>
<p>Error: {$result['message']}</p>
HTML;


                        }

                    } else {

                        $shortCodeResultHTML .= <<<HTML
<h3>Failed to fetch data. Please try again later.</h3>
HTML;

                    }

                }
                /**
                 * ========================================================
                 * fetch all group stories ends here
                 * ========================================================
                 */

                break;


            /**
             * gallery of posts that belongs to public stories
             */
            case 'GALLERY_MASONRY_STORIES':

                // fetch data
                $result = $GetStorifyAPIServiceObj->getPost_Public_Gallery_Of_Story(
                    $storyid,
                    $story_userid = null,
                    $postid,
                    $posttype,
                    $page,
                    $pagelimit = $gallery_item_per_page,
                    $story_category
                );

                if (isset($result['success'])) {

                    $posts = $result['success'];

                    $postHTML = '';

                    foreach ($posts as $post) {

                        switch ($post['posttype']) {
                            case 'IMAGE':

                                if (isset($post['postcontentthumbnail'])) {

                                    $postHTML .= <<<HTML
<div class="gtst-masonry-grid-item">
    <img class="gtst-masonry-image" src="{$post['postcontentthumbnail']}" data-src="{$post['postcontent']}" />
</div>
HTML;

                                }

                                break;

                            case 'IMAGE_GIF':

                                if (isset($post['postcontentthumbnail'])) {

                                    $postHTML .= <<<HTML
<div class="gtst-masonry-grid-item">
    <img class="gtst-masonry-image" src="{$post['postcontentthumbnail']}" data-src="{$post['postcontent']}" />
</div>
HTML;

                                }

                                break;

                            case 'VIDEO':

                                if (isset($post['postcontent'])) {

                                    $postHTML .= <<<HTML
<div class="getstorify post-video plyr-setup gtst-masonry-grid-item" id="getstorify-post-video-{$post['postid']}">
<video controls controlsList="nodownload" preload="none">
<source src="{$post['postcontent']}">
</video>
</div>
HTML;

                                }

                                break;

                        }

                    }

                    $pluginDir = GETSTORIFY_PLUGIN_DIR_URL;

                    $shortCodeResultHTML .= <<<HTML
<script src="{$pluginDir}/app/plugin/masonry/masonry-4.2.2.min.js"></script>
<script src="{$pluginDir}/app/plugin/imagesloaded/imagesloaded-4.1.4.min.js"></script>
<script src="{$pluginDir}/app/plugin/viewerjs/viewerjs-1.3.3.min.js"></script>
<link rel="stylesheet" href="{$pluginDir}/app/plugin/viewerjs/viewerjs-1.3.3.min.css">

<!-- gallery -->
<div class="gtst-masonry-grid" id="gtst-masonry-grid">
  <div class="gtst-masonry-grid-sizer"></div>
  {$postHTML}
</div><!--/ .gtst-masonry-grid -->


<style type="text/css">
/* ---- gtst-masonry-grid ---- */

.gtst-masonry-grid {
  /*background: #DDD;*/
  /*border: 1px solid #333;*/
}

/* clear fix */
.gtst-masonry-grid:after {
  content: '';
  display: block;
  clear: both;
}

/* ---- .gtst-masonry-grid-item ---- */

.gtst-masonry-grid-sizer,
.gtst-masonry-grid-item {
  width: 33.333%;
}

.gtst-masonry-grid-item {
  float: left;
}

.gtst-masonry-grid-item img,
.gtst-masonry-grid-item video {
  display: block;
  max-width: 100%;
}
</style>

<script>
window.onload = function() {
var grid = document.querySelector('.gtst-masonry-grid');
var msnry;
imagesLoaded( grid, function() {
  // init Isotope after all images have loaded
  msnry = new Masonry( grid, {
    itemSelector: '.gtst-masonry-grid-item',
    columnWidth: '.gtst-masonry-grid-sizer',
    percentPosition: true
  });
});

// set post video/audio
jQuery(".getstorify.plyr-setup").each(function () {
    var id = jQuery(this).attr('id');
    plyr.setup("#" + id);
});

// viewer
var viewer = new Viewer(document.getElementById('gtst-masonry-grid'), {});
};
</script>
HTML;

                    // get nav html
                    $html = GetStorify_StoryTemplate::getPrevNextButtons(count($posts), $page, $pagelimit = $gallery_item_per_page);

                    // echo prev next button
                    $shortCodeResultHTML .= $html;


                } else if (isset($result['error']) && $result['message'] == 'No match found') {

                    return <<<HTML
<p>You have reached the end.</p>
HTML;

                } else {

                    return <<<HTML
<p>An error occurred while processing your request.</p>
HTML;

                }

                break;


            /**
             * gallery of posts that belongs to stories of a public group
             */
            case 'GALLERY_MASONRY_GROUP_STORIES':

                // get group id
                if (isset($GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['groupid'])) {
                    $groupid = $GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['groupid'];
                } else {
                    return <<<HTML
<h3>"groupid" missing in the shortcode.</h3>
HTML;
                }

                // fetch data
                $result = $GetStorifyAPIServiceObj->getPost_Public_Gallery_Of_GroupStory(
                    $groupid,
                    $storyid,
                    $story_userid = null,
                    $postid,
                    $posttype,
                    $page,
                    $pagelimit = $gallery_item_per_page,
                    $story_category
                );

                if (isset($result['success'])) {

                    $posts = $result['success'];

                    $postHTML = '';

                    foreach ($posts as $post) {

                        switch ($post['posttype']) {
                            case 'IMAGE':

                                if (isset($post['postcontentthumbnail'])) {

                                    $postHTML .= <<<HTML
<div class="gtst-masonry-grid-item">
    <img class="gtst-masonry-image" src="{$post['postcontentthumbnail']}" data-src="{$post['postcontent']}" />
</div>
HTML;

                                }

                                break;

                            case 'IMAGE_GIF':

                                if (isset($post['postcontentthumbnail'])) {

                                    $postHTML .= <<<HTML
<div class="gtst-masonry-grid-item">
    <img class="gtst-masonry-image" src="{$post['postcontentthumbnail']}" data-src="{$post['postcontent']}" />
</div>
HTML;

                                }

                                break;

                            case 'VIDEO':

                                if (isset($post['postcontent'])) {

                                    $postHTML .= <<<HTML
<div class="getstorify post-video plyr-setup gtst-masonry-grid-item" id="getstorify-post-video-{$post['postid']}">
<video controls controlsList="nodownload" preload="none">
<source src="{$post['postcontent']}">
</video>
</div>
HTML;

                                }

                                break;

                        }

                    }

                    $pluginDir = GETSTORIFY_PLUGIN_DIR_URL;

                    $shortCodeResultHTML .= <<<HTML
<script src="{$pluginDir}/app/plugin/masonry/masonry-4.2.2.min.js"></script>
<script src="{$pluginDir}/app/plugin/imagesloaded/imagesloaded-4.1.4.min.js"></script>

<script src="{$pluginDir}/app/plugin/viewerjs/viewerjs-1.3.3.min.js"></script>
<link rel="stylesheet" href="{$pluginDir}/app/plugin/viewerjs/viewerjs-1.3.3.min.css">

<!-- gallery -->
<div class="gtst-masonry-grid" id="gtst-masonry-grid">
  <div class="gtst-masonry-grid-sizer"></div>
  {$postHTML}
</div><!--/ .gtst-masonry-grid -->


<style type="text/css">
/* ---- gtst-masonry-grid ---- */

.gtst-masonry-grid {
  /*background: #DDD;*/
  /*border: 1px solid #333;*/
}

/* clear fix */
.gtst-masonry-grid:after {
  content: '';
  display: block;
  clear: both;
}

/* ---- .gtst-masonry-grid-item ---- */

.gtst-masonry-grid-sizer,
.gtst-masonry-grid-item {
  width: 33.333%;
}

.gtst-masonry-grid-item {
  float: left;
}

.gtst-masonry-grid-item img,
.gtst-masonry-grid-item video {
  display: block;
  max-width: 100%;
}
</style>

<script>
window.onload = function() {
var grid = document.querySelector('.gtst-masonry-grid');
var msnry;
imagesLoaded( grid, function() {
  // init Isotope after all images have loaded
  msnry = new Masonry( grid, {
    itemSelector: '.gtst-masonry-grid-item',
    columnWidth: '.gtst-masonry-grid-sizer',
    percentPosition: true
  });
});

// set post video/audio
jQuery(".getstorify.plyr-setup").each(function () {
    var id = jQuery(this).attr('id');
    plyr.setup("#" + id);
});

// viewer
var viewer = new Viewer(document.getElementById('gtst-masonry-grid'), {});
};
</script>
HTML;

                    // get nav html
                    $html = GetStorify_StoryTemplate::getPrevNextButtons(count($posts), $page, $pagelimit = $gallery_item_per_page);

                    // echo prev next button
                    $shortCodeResultHTML .= $html;


                } else if (isset($result['error']) && $result['message'] == 'No match found') {

                    return <<<HTML
<p>You have reached the end.</p>
HTML;

                } else {

                    return <<<HTML
<p>An error occurred while processing your request.</p>
HTML;

                }

                break;


            /**
             *
             */
            default:

                $shortCodeResultHTML = <<<HTML
<h3>Invalid value set for the "view".</h3>
HTML;

        }

    } else {

        $shortCodeResultHTML = <<<HTML
<h1>Failed to authenticate your getStorify plugin credentials.</h1>
HTML;

    }

    /**
     * include other UI files
     */
    require_once __DIR__ . '/view/common/style_css.php';
    require_once __DIR__ . '/view/common/footer.php';

    return $shortCodeResultHTML;
}
