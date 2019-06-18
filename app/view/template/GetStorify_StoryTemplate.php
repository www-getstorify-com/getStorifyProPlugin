<?php
/**
 * Author: Yusuf Shakeel
 * Date: 07-jan-2019 mon
 * Version: 2.0
 *
 * Date: 06-jun-2018 wed
 * Version: 1.0
 *
 * File: StoryTemplate.php
 * Description: This file contains story templates.
 */

require_once __DIR__ . '/../../config/constants.php';

class GetStorify_StoryTemplate
{
    /**
     * This method will return story html template.
     *
     * @param array $data
     * @return string
     */
    public static function getStoryCard_MDB($data)
    {
        $html = "";

        foreach ($data as $story) {

            $storytitle = ($story['storytitle'] == null) ? "" : $story['storytitle'];

            $storydescription = ($story['storydescription'] == null) ? "" : $story['storydescription'];
            if (strlen($storydescription) > 140) {
                $storydescription = substr($storydescription, 0, 140) . "...";
            }

            /**
             * this will hold the story slug for WordPress
             * slug example: 'story/hello-world-gs123'
             */
            $wp_story_slug = 'story/' . self::_helper_WP_getSlugFromStoryTitle($story['storyid'], $story['storytitle']);

            /**
             * cover content of the story
             */
            $covercontent = "";
            if ($story['covercontent'] != null) {

                if ($story['covertype'] == 'IMAGE') {

                    $covercontent = <<<HTML
<img class='getstorify story-covercontent view-story image card-img-top' src='{$story['covercontentthumbnail']}' alt='{$storytitle}' data-storyid='{$story['storyid']}'>
HTML;
                } else if ($story['covertype'] == 'VIDEO') {

                    $covercontent = <<<HTML
<div class='getstorify story-covercontent video' id='story-covercontent-video-storyid-{$story['storyid']}'>
<video controls controlsList='nodownload' preload='none'>
<source src='{$story['covercontent']}'>
</video>
</div>
HTML;
                }
            } else {
                $cover = self::_helper_getStoryCoverLinearGradient_HTML($story);
                $covercontent = <<<HTML
<div class='getstorify story-covercontent view-story' data-storyid='{$story['storyid']}' data-userid='{$story['userid']}'>
$cover
</div>
HTML;
            }

            $createdDate = $story['created'];

            $picture = GETSTORIFY_PLUGIN_USER_DEFAULT_PROFILE_IMAGE;
            if ($story['picture'] != null) {
                $picture = $story['picturethumbnail'];
            }

            $fullname = '';
            if ($story['firstname'] != null) {
                $fullname = $story['firstname'] . " ";
            }
            if ($story['lastname'] != null) {
                $fullname .= $story['lastname'];
            }

            $userDetail = <<<HTML
<div class='getstorify media'>
<img class='getstorify mr-1 rounded-circle img-thumbnail view-user-profile user-profile-image' style='width: 48px; height: 48px;' src='{$picture}' data-userid='{$story['userid']}'>
<div class='getstorify media-body'>
<p class='getstorify getstorify-wrap mt-0 font-weight-light mb-1'>
<strong class='getstorify view-user-profile user-fullname' data-userid='{$story['userid']}'>{$fullname}</strong>
<br><small class='getstorify story-created-date' data-created='{$createdDate}'>{$createdDate}</small>
</p>
</div><!--/ .media-body -->
</div><!--/ .media -->
HTML;

            $wp_get_parmalink = get_permalink();

            // show/hide story cover
            if (isset($GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['display_story_cover']) &&
                $GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['display_story_cover'] === 'none'
            ) {
                $covercontent = "";
            }

            // show/hide user detail from story
            if (isset($GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['display_user_detail']) &&
                $GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['display_user_detail'] === 'none'
            ) {
                $userDetail = "";
            }

            $storycontent = <<<HTML
<div class='getstorify card story-content-container'>
$covercontent
<div class='getstorify card-body story-detail-container'>
<h2 class='getstorify card-title story-title-container'><a class='getstorify story-title black-text getstorify-wrap view-story prevent-default' href='{$wp_get_parmalink}{$wp_story_slug}' data-storyid='{$story['storyid']}'>{$storytitle}</a></h2>
<p class='getstorify card-text story-description getstorify-wrap'>{$storydescription}</p>
</div><!--/ .card-body .story-detail-container -->
<div class='getstorify card-footer'>
$userDetail
<p style="margin-bottom: 20px;"><a href='{$wp_get_parmalink}{$wp_story_slug}'>Read More</a></p>
</div><!--/ .card-footer -->
</div><!--/ .card -->
HTML;

            // set up the column width
            if (isset($GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['columns']) &&
                in_array(intval($GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['columns']), [1, 2, 3, 4])
            ) {
                $col_width = 12 / intval($GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['columns']);
            } else {
                $col_width = 12;
            }

            $html .= <<<HTML
<div class='col-sm-12 col-md-{$col_width} col-lg-{$col_width} mb-3 story-card'>
{$storycontent}
</div><!--/ .col -->
HTML;

        }

        return $html;
    }

    /**
     * This method will return story html template.
     *
     * @param array $data
     * @return string
     */
    public static function getStoryCard_DisplayOnly_MDB($data)
    {
        // get the items that needs to be displayed
        $displayOnlyItemsArr = [];
        if (isset($GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['display_only'])) {
            $displayOnlyItemsArr = explode(",", $GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['display_only']);
        }


        /**
         * get the permalink of the page
         *
         * note! this is from wordpress
         */
        $wp_get_parmalink = get_permalink();


        // this will hold the html code for the story card
        $html = "";


        foreach ($data as $story) {

            /**
             * story title
             */
            $storytitle = ($story['storytitle'] == null) ? "" : $story['storytitle'];


            /**
             * story description
             */
            $storydescription = ($story['storydescription'] == null) ? "" : $story['storydescription'];


            /**
             * this will hold the story slug for WordPress
             * slug example: 'story/hello-world-gs123'
             */
            $wp_story_slug = 'story/' . self::_helper_WP_getSlugFromStoryTitle($story['storyid'], $story['storytitle']);


            /**
             * cover content of the story
             */
            $covercontent = "";
            if ($story['covercontent'] != null) {

                if ($story['covertype'] == 'IMAGE') {

                    $covercontent = <<<HTML
<img class='getstorify story-covercontent view-story image card-img-top' src='{$story['covercontentthumbnail']}' alt='{$storytitle}' data-storyid='{$story['storyid']}'>
HTML;
                } else if ($story['covertype'] == 'VIDEO') {

                    $covercontent = <<<HTML
<div class='getstorify story-covercontent video' id='story-covercontent-video-storyid-{$story['storyid']}'>
<video controls controlsList='nodownload' preload='none'>
<source src='{$story['covercontent']}'>
</video>
</div>
HTML;
                }
            } else {
                $cover = self::_helper_getStoryCoverLinearGradient_HTML($story);
                $covercontent = <<<HTML
<div class='getstorify story-covercontent view-story' data-storyid='{$story['storyid']}' data-userid='{$story['userid']}'>
$cover
</div>
HTML;
            }


            /**
             * story created date time
             */
            $createdDate = $story['created'];


            /**
             * user profile image
             */
            $picture = GETSTORIFY_PLUGIN_USER_DEFAULT_PROFILE_IMAGE;
            if ($story['picture'] != null) {
                $picture = $story['picturethumbnail'];
            }


            /**
             * prepare the user fullname
             * using the firstname and lastname
             */
            $fullname = '';
            if ($story['firstname'] != null) {
                $fullname = $story['firstname'] . " ";
            }
            if ($story['lastname'] != null) {
                $fullname .= $story['lastname'];
            }


            /**
             * prepare the user detail
             */
            $userDetail = <<<HTML
<div class='getstorify media'>
<img class='getstorify mr-1 rounded-circle img-thumbnail view-user-profile user-profile-image' style='width: 48px; height: 48px;' src='{$picture}' data-userid='{$story['userid']}' alt={$storytitle}>
<div class='getstorify media-body'>
<p class='getstorify getstorify-wrap mt-0 font-weight-light mb-1'>
<strong class='getstorify view-user-profile user-fullname' data-userid='{$story['userid']}'>{$fullname}</strong>
<br><small class='getstorify story-created-date' data-created='{$createdDate}'>{$createdDate}</small>
</p>
</div><!--/ .media-body -->
</div><!--/ .media -->
HTML;


            /**
             * read more button
             */
            $readMoreBtn = <<<HTML
<p style="margin-bottom: 20px;"><a href='{$wp_get_parmalink}{$wp_story_slug}'>Read More</a></p>
HTML;


            // show/hide story cover
            if (!in_array('STORY_COVER', $displayOnlyItemsArr)) {
                $covercontent = "";
            }


            // show/hide story title
            if (in_array('STORY_TITLE', $displayOnlyItemsArr)) {
                $storytitle = <<<HTML
<h2 class='getstorify card-title story-title-container'><a class='getstorify story-title black-text getstorify-wrap view-story prevent-default' href='{$wp_get_parmalink}{$wp_story_slug}' data-storyid='{$story['storyid']}'>{$storytitle}</a></h2>
HTML;

            } else {
                $storytitle = "";
            }


            // show/hide story description
            if (in_array('STORY_DESCRIPTION', $displayOnlyItemsArr)) {
                if (strlen($storydescription) > 140) {
                    $storydescription = substr($storydescription, 0, 140) . "...";
                }
            } else if (in_array('STORY_FULL_DESCRIPTION', $displayOnlyItemsArr)) {
//                $storydescription = $storydescription;
            } else {
                $storydescription = "";
            }
            if (strlen($storydescription) > 0) {
                $storydescription = <<<HTML
<p class='getstorify card-text story-description getstorify-wrap'>{$storydescription}</p>
HTML;

            }


            // show/hide user detail from story
            if (!in_array('STORY_USER_DETAIL', $displayOnlyItemsArr)) {
                $userDetail = "";
            }


            // show/hide read more button
            if (!in_array('STORY_READ_MORE_BTN', $displayOnlyItemsArr)) {
                $readMoreBtn = "";
            }


            $storycontent = <<<HTML
<div class='getstorify card story-content-container'>
$covercontent
<div class='getstorify card-body story-detail-container'>
$storytitle
$storydescription
</div><!--/ .card-body .story-detail-container -->
<div class='getstorify card-footer'>
$userDetail
$readMoreBtn
</div><!--/ .card-footer -->
</div><!--/ .card -->
HTML;

            // set up the column width
            if (isset($GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['columns']) &&
                in_array(intval($GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['columns']), [1, 2, 3, 4])
            ) {
                $col_width = 12 / intval($GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['columns']);
            } else {
                $col_width = 12;
            }

            $html .= <<<HTML
<div class='col-sm-12 col-md-{$col_width} col-lg-{$col_width} mb-3 story-card'>
{$storycontent}
</div><!--/ .col -->
HTML;

        }

        return $html;
    }


    /**
     * This method will return story carousel html template.
     *
     * @param array $data
     * @return string
     */
    public static function getStoryCard_StoryCoverCarousel_MDB($data)
    {
        /**
         * get the permalink of the page
         *
         * note! this is from wordpress
         */
        $wp_get_parmalink = get_permalink();


        // this will hold the html code for the story card
        $html = "";

        foreach ($data as $story) {

            $storytitle = ($story['storytitle'] == null) ? "" : $story['storytitle'];


            /**
             * this will hold the story slug for WordPress
             * slug example: 'story/hello-world-gs123'
             */
            $wp_story_slug = 'story/' . self::_helper_WP_getSlugFromStoryTitle($story['storyid'], $story['storytitle']);


            /**
             * cover content of the story
             */
            $covercontent = "";
            if ($story['covercontent'] != null) {

                if ($story['covertype'] == 'IMAGE') {

                    $covercontent = <<<HTML
<img class='getstorify story-covercontent view-story image' style="margin: 0 auto;" src='{$story['covercontentthumbnail']}' alt='{$storytitle}' data-storyid='{$story['storyid']}'>
HTML;
                } else if ($story['covertype'] == 'VIDEO') {

                    $covercontent = <<<HTML
<div class='getstorify story-covercontent video' id='story-covercontent-video-storyid-{$story['storyid']}'>
<video controls controlsList='nodownload' preload='none'>
<source src='{$story['covercontent']}'>
</video>
</div>
HTML;
                }
            } else {
                $cover = self::_helper_getStoryCoverLinearGradient_HTML($story);
                $covercontent = <<<HTML
<div class='getstorify story-covercontent view-story' data-storyid='{$story['storyid']}' data-userid='{$story['userid']}'>
$cover
</div>
HTML;
            }

            $html .= <<<HTML
<div class='getstorify-slick-carousel-item' style="text-align: center">
$covercontent
</div><!--/ .col -->
HTML;

        }

        $html = <<<HTML
<div class="col-sm-12 col-md-12 col-lg-12">
<div class="getstorify-slick-container">
$html
</div><!--/ .getstorify-slick-container -->
</div><!--/ .col -->
HTML;


        return $html;
    }


    /**
     * This method will return story html template.
     *
     * @param array $data
     * @return string
     */
    public static function getStoryCoverCard_MDB($data)
    {
        $html = "";

        $story = $data;

        $storytitle = ($story['storytitle'] == null) ? "" : $story['storytitle'];

        $storydescription = ($story['storydescription'] == null) ? "" : $story['storydescription'];

        /**
         * this will hold the story slug for WordPress
         * slug example: 'story/hello-world-gs123'
         */
        $wp_story_slug = 'story/' . self::_helper_WP_getSlugFromStoryTitle($story['storyid'], $story['storytitle']);

        /**
         * cover content of the story
         */
        $covercontent = "";
        if ($story['covercontent'] != null) {

            if ($story['covertype'] == 'IMAGE') {

                $covercontent = <<<HTML
<img class='getstorify story-covercontent view-story image card-img-top' 
     src='{$story['covercontentthumbnail']}' 
     alt='{$storytitle}' 
     data-covercontent='{$story['covercontent']}' 
     data-storyid='{$story['storyid']}'>
HTML;
            } else if ($story['covertype'] == 'VIDEO') {

                $covercontent = <<<HTML
<div class='getstorify story-covercontent video' id='story-covercontent-video-storyid-{$story['storyid']}'>
<video controls controlsList='nodownload' preload='none'>
<source src='{$story['covercontent']}'>
</video>
</div>
HTML;
            }
        } else {
            $cover = self::_helper_getStoryCoverLinearGradient_HTML($story);
            $covercontent = <<<HTML
<div class='getstorify story-covercontent view-story' data-storyid='{$story['storyid']}' data-userid='{$story['userid']}'>
$cover
</div>
HTML;
        }

        $createdDate = $story['created'];

        $picture = GETSTORIFY_PLUGIN_USER_DEFAULT_PROFILE_IMAGE;
        if ($story['picture'] != null) {
            $picture = $story['picturethumbnail'];
        }

        $fullname = '';
        if ($story['firstname'] != null) {
            $fullname = $story['firstname'] . " ";
        }
        if ($story['lastname'] != null) {
            $fullname .= $story['lastname'];
        }

        $userDetail = <<<HTML
<div class='getstorify media'>
<img class='getstorify mr-1 rounded-circle img-thumbnail view-user-profile user-profile-image' style='width: 48px; height: 48px;' src='{$picture}' data-userid='{$story['userid']}'>
<div class='getstorify media-body'>
<p class='getstorify getstorify-wrap mt-0 font-weight-light mb-1'>
<strong class='getstorify view-user-profile user-fullname' data-userid='{$story['userid']}'>{$fullname}</strong>
<br><small class='getstorify story-created-date' data-created='{$createdDate}'>{$createdDate}</small>
</p>
</div><!--/ .media-body -->
</div><!--/ .media -->
HTML;

        $wp_get_parmalink = get_permalink();

        // show/hide story cover
        if (isset($GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['display_story_cover']) &&
            $GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['display_story_cover'] === 'none'
        ) {
            $covercontent = "";
        }

        // show/hide user detail from story
        if (isset($GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['display_user_detail']) &&
            $GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['display_user_detail'] === 'none'
        ) {
            $userDetail = "";
        }

        $storycontent = <<<HTML
<div class='getstorify card story-content-container'>
$covercontent
<div class='getstorify card-body story-detail-container'>
<h2 class='getstorify card-title story-title-container'><a class='getstorify story-title black-text getstorify-wrap view-story prevent-default' href='{$wp_get_parmalink}{$wp_story_slug}' data-storyid='{$story['storyid']}'>{$storytitle}</a></h2>
<p class='getstorify card-text story-description getstorify-wrap'>{$storydescription}</p>
</div><!--/ .card-body .story-detail-container -->
<div class='getstorify card-footer'>
$userDetail
</div><!--/ .card-footer -->
</div><!--/ .card -->
HTML;

        $html .= <<<HTML
<div class='col-sm-12 col-md-12 col-lg-12 mb-3 story-card'>
{$storycontent}
</div><!--/ .col -->
HTML;

        return $html;
    }

    /**
     * This method will return story html template.
     *
     * @param array $data
     * @return string
     */
    public static function getStoryCoverCard_DisplayOnly_MDB($data)
    {
        // get the items that needs to be displayed
        $displayOnlyItemsArr = [];
        if (isset($GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['display_only'])) {
            $displayOnlyItemsArr = explode(",", $GLOBALS[GETSTORIFY_PLUGIN_WP_SHORTCODE_PARAMS]['display_only']);
        }


        /**
         * get the permalink of the page
         *
         * note! this is from wordpress
         */
        $wp_get_parmalink = get_permalink();

        $html = "";

        $story = $data;

        $storytitle = ($story['storytitle'] == null) ? "" : $story['storytitle'];

        $storydescription = ($story['storydescription'] == null) ? "" : $story['storydescription'];

        /**
         * this will hold the story slug for WordPress
         * slug example: 'story/hello-world-gs123'
         */
        $wp_story_slug = 'story/' . self::_helper_WP_getSlugFromStoryTitle($story['storyid'], $story['storytitle']);

        /**
         * cover content of the story
         */
        $covercontent = "";
        if ($story['covercontent'] != null) {

            if ($story['covertype'] == 'IMAGE') {

                $covercontent = <<<HTML
<img class='getstorify story-covercontent view-story image card-img-top' 
     src='{$story['covercontentthumbnail']}' 
     alt='{$storytitle}' 
     data-covercontent='{$story['covercontent']}' 
     data-storyid='{$story['storyid']}'>
HTML;
            } else if ($story['covertype'] == 'VIDEO') {

                $covercontent = <<<HTML
<div class='getstorify story-covercontent video' id='story-covercontent-video-storyid-{$story['storyid']}'>
<video controls controlsList='nodownload' preload='none'>
<source src='{$story['covercontent']}'>
</video>
</div>
HTML;
            }
        } else {
            $cover = self::_helper_getStoryCoverLinearGradient_HTML($story);
            $covercontent = <<<HTML
<div class='getstorify story-covercontent view-story' data-storyid='{$story['storyid']}' data-userid='{$story['userid']}'>
$cover
</div>
HTML;
        }

        $createdDate = $story['created'];

        $picture = GETSTORIFY_PLUGIN_USER_DEFAULT_PROFILE_IMAGE;
        if ($story['picture'] != null) {
            $picture = $story['picturethumbnail'];
        }

        $fullname = '';
        if ($story['firstname'] != null) {
            $fullname = $story['firstname'] . " ";
        }
        if ($story['lastname'] != null) {
            $fullname .= $story['lastname'];
        }

        $userDetail = <<<HTML
<div class='getstorify media'>
<img class='getstorify mr-1 rounded-circle img-thumbnail view-user-profile user-profile-image' style='width: 48px; height: 48px;' src='{$picture}' data-userid='{$story['userid']}'>
<div class='getstorify media-body'>
<p class='getstorify getstorify-wrap mt-0 font-weight-light mb-1'>
<strong class='getstorify view-user-profile user-fullname' data-userid='{$story['userid']}'>{$fullname}</strong>
<br><small class='getstorify story-created-date' data-created='{$createdDate}'>{$createdDate}</small>
</p>
</div><!--/ .media-body -->
</div><!--/ .media -->
HTML;

        // show/hide story cover
        if (!in_array('STORY_COVER', $displayOnlyItemsArr)) {
            $covercontent = "";
        }


        // show/hide story title
        if (in_array('STORY_TITLE', $displayOnlyItemsArr)) {
            $storytitle = <<<HTML
<h2 class='getstorify card-title story-title-container'><a class='getstorify story-title black-text getstorify-wrap view-story prevent-default' href='{$wp_get_parmalink}{$wp_story_slug}' data-storyid='{$story['storyid']}'>{$storytitle}</a></h2>
HTML;

        } else {
            $storytitle = "";
        }


        // show/hide story description
        if (!in_array('STORY_DESCRIPTION', $displayOnlyItemsArr) &&
            !in_array('STORY_FULL_DESCRIPTION', $displayOnlyItemsArr)) {
            $storydescription = "";
        }
        if (strlen($storydescription) > 0) {
            $storydescription = <<<HTML
<p class='getstorify card-text story-description getstorify-wrap'>{$storydescription}</p>
HTML;

        }


        // show/hide user detail from story
        if (!in_array('STORY_USER_DETAIL', $displayOnlyItemsArr)) {
            $userDetail = "";
        }


        $storycontent = <<<HTML
<div class='getstorify card story-content-container'>
$covercontent
<div class='getstorify card-body story-detail-container'>
$storytitle
$storydescription
</div><!--/ .card-body .story-detail-container -->
<div class='getstorify card-footer'>
$userDetail
</div><!--/ .card-footer -->
</div><!--/ .card -->
HTML;

        $html .= <<<HTML
<div class='col-sm-12 col-md-12 col-lg-12 mb-3 story-card'>
{$storycontent}
</div><!--/ .col -->
HTML;

        return $html;
    }

    /**
     * This method will return the prev next buttons.
     *
     * @param int $count This represents the number of stories fetched in the given page.
     * @param int $page
     * @param int $pagelimit
     * @return string
     */
    public static function getPrevNextButtons($count, $page, $pagelimit = GETSTORIFY_PLATFORM_API_SERVICE_DB_PAGE_LIMIT)
    {
        $prevNextNavButtons = "";

        $homeLink = get_permalink();

        // add prev-next nav button
        if ($count == $pagelimit) {

            // if no. of stories fetched equals the page limit

            // on page 1 just return next button
            if ($page === 1) {

                $nextPage = $page + 1;

                $prevNextNavButtons = <<<HTML
<div class="getstorify prev-next-btn-container">
<a href="{$homeLink}page/{$nextPage}" class="getstorify prev-next-btn float-right"> Next &rarr; </a>
</div>
HTML;
            } else {
                // on page > 1 return both prev next buttons

                $prevPage = $page - 1;
                $nextPage = $page + 1;

                $prevNextNavButtons = <<<HTML
<div class="getstorify prev-next-btn-container">
<a href="{$homeLink}page/{$prevPage}" class="getstorify prev-next-btn"> &larr; Prev </a>
<a href="{$homeLink}page/{$nextPage}" class="getstorify prev-next-btn float-right"> Next &rarr; </a>
</div>
HTML;
            }

        } else {

            // no. of stories fetched is less than the page limit

            if ($page > 1) {

                // on page > 1 return prev button

                $prevPage = $page - 1;

                $prevNextNavButtons = <<<HTML
<div class="getstorify prev-next-btn-container">
<a href="{$homeLink}page/{$prevPage}" class="getstorify prev-next-btn"> &larr; Prev </a>
</div>
HTML;
            }

        }

        return $prevNextNavButtons;
    }

    /**
     * This method will return the story slug link.
     *
     * @param string $storyid
     * @param string $storytitle
     * @return string
     */
    public static function getWPStorySlugLink($storyid, $storytitle)
    {
        $wp_story_slug = "story/" . self::_helper_WP_getSlugFromStoryTitle($storyid, $storytitle);

        return $wp_story_slug;
    }

    /**
     * This will return an array containing color code.
     *
     * paletteArr structure
     * [colorHexCode, colorHexCode, direction]
     *
     * @return mixed
     */
    private static function _getStoryCoverPaletteColor()
    {
        $paletteArr = [
            ['from' => '#FFAFBD', 'to' => '#FFC3A0', 'direction' => 'to right'],
            ['from' => '#0078FF', 'to' => '#69717D', 'direction' => 'to right'],
            ['from' => '#04BEFE', 'to' => '#4481EB', 'direction' => 'to right'],
            ['from' => '#473B7B', 'to' => '#30D2BE', 'direction' => 'to right'],
            ['from' => '#FF7EB3', 'to' => '#FF758C', 'direction' => 'to right']
        ];

        return $paletteArr[rand(0, count($paletteArr) - 1)];
    }

    /**
     * This will return the story cover linear gradient html.
     *
     * @param null|array $payload
     * @return string
     */
    private static function _helper_getStoryCoverLinearGradient_HTML($payload = null)
    {
        $colorPalette = self::_getStoryCoverPaletteColor();

        $storytitle = "";
        if (isset($payload['storytitle'])) {
            $storytitle = <<<HTML
<p class="getstorify-wrap" style="font-size: 150%; color: #fff; padding: 80px 20px; font-weight: bold; text-align: center;">{$payload['storytitle']}</p>
HTML;
        }

        return <<<HTML
<div style="min-height: 200px; width: 100%; background-image: linear-gradient({$colorPalette['direction']}, {$colorPalette['from']}, {$colorPalette['to']}); background-color: {$colorPalette['from']}">
{$storytitle}
</div>
HTML;
    }

    /**
     * This will create a slug from the story title.
     *
     * Example:
     * $storyid: gs123
     * $storytitle: This is 2 awesome slug!!!
     *
     * Slug: this-is-2-awesome-slug-gs123
     *
     * Strip rule: Any non-alnum characters will be stripped and replaced by '-'.
     *
     * @param $storyid string
     * @param $title string
     * @return string|null
     */
    private static function _helper_WP_getSlugFromStoryTitle($storyid, $storytitle)
    {
        $slug = null;

        if (isset($storytitle)) {
            // prepare slug
            // 1. trim title
            // 2. transform to lowercase
            // 3. take max 100 characters from the title
            // 4. replace non-alnum characters with '-'
            $slug = preg_replace("/[^A-Za-z0-9]/", '-', substr(strtolower(trim($storytitle)), 0, 100));

            // 5. replace two or more consecutive '-' with one '-'
            $slug = preg_replace("/[-]+/", '-', $slug);

            // 6. trim leading/trailing '-'
            $slug = trim($slug, '-');
        }

        if (is_null($slug)) {
            return $storyid;
        }

        return $slug . '-' . $storyid;
    }

}