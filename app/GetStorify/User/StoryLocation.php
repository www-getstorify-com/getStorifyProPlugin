<?php
/**
 * Author: Yusuf Shakeel
 * Date: 06-Jun-2018 Wed
 * Version: 1.0
 *
 * File: StoryLocation.php
 * Description: This file contains Story Location code.
 */

namespace GetStorify\User;

/**
 * Class StoryLocation
 * @package GetStorify\User
 */
class StoryLocation
{
    /**
     * This will return the story location.
     *
     * @param string $appid
     * @param string $userid
     * @param string $accesstoken
     * @param array $header
     * @param null|string $storyid
     * @param int $page
     * @param int $pagelimit
     * @return mixed
     */
    public function getStoryLocation($appid, $userid, $accesstoken, $header, $storyid = null, $page = 1, $pagelimit = GETSTORIFY_API_SERVICE_DB_PAGE_LIMIT)
    {
        $getParam = "?appid=$appid&userid=$userid&accesstoken=$accesstoken";
        $getParam .= "&page=$page";
        $getParam .= "&pagelimit=$pagelimit";
        $getParam .= "&storyid=$storyid";

        $url = GETSTORIFY_API_SERVICE_STORY_LOCATION_URL . $getParam;

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