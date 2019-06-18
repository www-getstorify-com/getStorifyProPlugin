<?php
/**
 * Author: Yusuf Shakeel
 * Date: 06-Jun-2018 Wed
 * Version: 1.0
 *
 * File: Post.php
 * Description: This file contains Post code.
 */

namespace GetStorify\User;

/**
 * Class Post
 * @package GetStorify\User
 */
class Post
{
    /**
     * This will return the post.
     *
     * @param string $appid
     * @param string $userid
     * @param string $accesstoken
     * @param array $header
     * @param string $storyid
     * @param null|string $postid
     * @param int $page
     * @param int $pagelimit
     * @return mixed
     */
    public function getPost($appid, $userid, $accesstoken, $header, $storyid, $postid = null, $page = 1, $pagelimit = GETSTORIFY_API_SERVICE_DB_PAGE_LIMIT)
    {
        $getParam = "?appid=$appid&userid=$userid&accesstoken=$accesstoken";
        $getParam .= "&page=$page";
        $getParam .= "&pagelimit=$pagelimit";
        $getParam .= "&storyid=$storyid";
        if (isset($postid)) {
            $getParam .= "&postid=$postid";
        }

        $url = GETSTORIFY_API_SERVICE_POST_URL . $getParam;

        $headerArr = array(
            'Origin' => $header['origin']
        );

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArr);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

        // Execute post
        $result = curl_exec($ch);

        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch);

        return json_decode($result, true);
    }

    /**
     * This will return the post location.
     *
     * @param string $appid
     * @param string $userid
     * @param string $accesstoken
     * @param array $header
     * @param string $storyid
     * @param int $page
     * @param int $pagelimit
     * @return mixed
     */
    public function getPostLocation($appid, $userid, $accesstoken, $header, $storyid, $page = 1, $pagelimit = GETSTORIFY_API_SERVICE_DB_PAGE_LIMIT)
    {
        $getParam = "?appid=$appid&userid=$userid&accesstoken=$accesstoken";
        $getParam .= "&page=$page";
        $getParam .= "&pagelimit=$pagelimit";
        $getParam .= "&storyid=$storyid";

        $url = GETSTORIFY_API_SERVICE_POST_LOCATION_URL . $getParam;

        $headerArr = array(
            'Origin' => $header['origin']
        );

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArr);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

        // Execute post
        $result = curl_exec($ch);

        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch);

        return json_decode($result, true);
    }

    /**
     * This will return the story post gallery.
     *
     * @param string $appid
     * @param string $userid
     * @param string $accesstoken
     * @param array $header
     * @param null|string $storyid
     * @param null|string $story_userid
     * @param null|string $postid
     * @param null|string $posttype
     * @param int $page
     * @param int $pagelimit
     * @param null|array $storycategory
     * @return mixed
     */
    public function getPost_Public_Gallery_Of_Story($appid, $userid, $accesstoken, $header, $storyid = null, $story_userid = null, $postid = null, $posttype = null, $page = 1, $pagelimit = GETSTORIFY_API_SERVICE_DB_PAGE_LIMIT, $storycategory = null)
    {
        $getParam = "?appid=$appid&userid=$userid&accesstoken=$accesstoken";
        $getParam .= "&page=$page";
        $getParam .= "&pagelimit=$pagelimit";

        if (isset($story_userid)) {
            $getParam .= "&story_userid=$story_userid";
        }

        if (isset($storyid)) {
            $getParam .= "&storyid=$storyid";
        }

        if (isset($postid)) {
            $getParam .= "&postid=$postid";
        }

        if (isset($posttype)) {
            $getParam .= "&posttype=$posttype";
        }

        if (isset($storycategory)) {
            $getParam .= "&storycategory=$storycategory";
        }

        $url = GETSTORIFY_API_SERVICE_POST_STORY_GALLERY_URL . $getParam;

        $headerArr = array(
            'Origin' => $header['origin']
        );

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArr);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

        // Execute post
        $result = curl_exec($ch);

        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch);

        return json_decode($result, true);
    }

    /**
     * This will return the group story post gallery.
     *
     * @param string $appid
     * @param string $userid
     * @param string $accesstoken
     * @param array $header
     * @param string $groupid
     * @param null|string $storyid
     * @param null|string $story_userid
     * @param null|string $postid
     * @param null|string $posttype
     * @param int $page
     * @param int $pagelimit
     * @param null|array $storycategory
     * @return mixed
     */
    public function getPost_Public_Gallery_Of_GroupStory($appid, $userid, $accesstoken, $header, $groupid, $storyid = null, $story_userid = null, $postid = null, $posttype = null, $page = 1, $pagelimit = GETSTORIFY_API_SERVICE_DB_PAGE_LIMIT, $storycategory = null)
    {
        $getParam = "?appid=$appid&userid=$userid&accesstoken=$accesstoken";
        $getParam .= "&page=$page";
        $getParam .= "&pagelimit=$pagelimit";
        $getParam .= "&groupid=$groupid";

        if (isset($story_userid)) {
            $getParam .= "&story_userid=$story_userid";
        }

        if (isset($storyid)) {
            $getParam .= "&storyid=$storyid";
        }

        if (isset($postid)) {
            $getParam .= "&postid=$postid";
        }

        if (isset($posttype)) {
            $getParam .= "&posttype=$posttype";
        }

        if (isset($storycategory)) {
            $getParam .= "&storycategory=$storycategory";
        }

        $url = GETSTORIFY_API_SERVICE_POST_GROUP_STORY_GALLERY_URL . $getParam;

        $headerArr = array(
            'Origin' => $header['origin']
        );

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArr);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

        // Execute post
        $result = curl_exec($ch);

        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch);

        return json_decode($result, true);
    }
}