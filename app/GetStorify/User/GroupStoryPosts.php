<?php
/**
 * Author: Yusuf Shakeel
 * Date: 20-dec-2018 thu
 * Version: 1.0
 *
 * File: GroupStoryPosts.php
 * Description: This file contains GroupStoryPosts code.
 */

namespace GetStorify\User;

/**
 * Class GroupStoryPosts
 * @package GetStorify\User
 */
class GroupStoryPosts
{
    /**
     * This will return the group story posts.
     *
     * @param string $appid
     * @param string $userid
     * @param string $accesstoken
     * @param array $header
     * @param string $groupid
     * @param string $storyid
     * @param null|string $grouphandle
     * @param null|string $postid
     * @param null|string $posttype
     * @param int $page
     * @param int $pagelimit
     * @return mixed
     */
    public function getGroupStoryPosts($appid, $userid, $accesstoken, $header, $groupid, $storyid, $grouphandle = null, $postid = null, $posttype = null, $page = 1, $pagelimit = GETSTORIFY_API_SERVICE_DB_PAGE_LIMIT)
    {
        $getParam = "?appid=$appid&userid=$userid&accesstoken=$accesstoken";
        $getParam .= "&page=$page";
        $getParam .= "&pagelimit=$pagelimit";

        $getParam .= "&groupid=$groupid";

        if (isset($grouphandle)) {
            $getParam .= "&grouphandle=$grouphandle";
        }

        $getParam .= "&storyid=$storyid";

        if (isset($postid)) {
            $getParam .= "&postid=$postid";
        }

        if (isset($posttype)) {
            $getParam .= "&posttype=$posttype";
        }

        $url = GETSTORIFY_API_SERVICE_GROUP_STORY_POSTS_URL . $getParam;

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