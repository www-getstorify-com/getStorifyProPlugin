<?php
/**
 * Author: Yusuf Shakeel
 * Date: 07-jun-2018 thu
 * Version: 1.0
 *
 * File: GroupStories.php
 * Description: This file contains GroupStories code.
 */

namespace GetStorify\User;

/**
 * Class GroupStories
 * @package GetStorify\User
 */
class GroupStories
{
    /**
     * This will return the Group stories.
     *
     * @param string $appid
     * @param string $userid
     * @param string $accesstoken
     * @param array $header
     * @param null|string $groupid
     * @param null|string $storyid
     * @param int $page
     * @param int $pagelimit
     * @return mixed
     */
    public function getGroupStories($appid, $userid, $accesstoken, $header, $groupid = null, $storyid = null, $page = 1, $pagelimit = GETSTORIFY_API_SERVICE_DB_PAGE_LIMIT)
    {
        $getParam = "?appid=$appid&userid=$userid&accesstoken=$accesstoken";
        $getParam .= "&page=$page";
        $getParam .= "&pagelimit=$pagelimit";
        $getParam .= "&groupid=$groupid";

        if (isset($storyid)) {
            $getParam .= "&storyid=$storyid";
        }

        $url = GETSTORIFY_API_SERVICE_GROUP_STORIES_URL . $getParam;

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
     * This will return the prev-next stories of the group stories.
     *
     * @param string $appid
     * @param string $userid
     * @param string $accesstoken
     * @param array $header
     * @param string $groupid
     * @param string $storyid
     * @return mixed
     */
    public function getGroupStories_PrevNextStories($appid, $userid, $accesstoken, $header, $groupid, $storyid)
    {
        $getParam = "?appid=$appid&userid=$userid&accesstoken=$accesstoken";
        $getParam .= "&groupid=$groupid";
        $getParam .= "&storyid=$storyid";

        $url = GETSTORIFY_API_SERVICE_GROUP_STORIES_PREV_NEXT_STORIES_URL . $getParam;

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