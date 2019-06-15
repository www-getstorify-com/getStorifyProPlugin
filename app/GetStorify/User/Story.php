<?php
/**
 * Author: Yusuf Shakeel
 * Date: 06-Jun-2018 Wed
 * Version: 1.0
 *
 * File: Story.php
 * Description: This file contains Story code.
 */

namespace GetStorify\User;

/**
 * Class Story
 * @package GetStorify\User
 */
class Story
{
    /**
     * This will return the story.
     *
     * @param string $appid
     * @param string $userid
     * @param string $accesstoken
     * @param array $header
     * @param null|string $storyid
     * @param int $page
     * @param int $pagelimit
     * @param null|string $storystatusin Comman separated values like PUBLISHED,GROUP_STORY or single value like PUBLISHED
     * @param null|string $category Array of integer values like: [1] or [1,2]
     * @return mixed
     */
    public function getStory($appid, $userid, $accesstoken, $header, $storyid = null, $page = 1, $pagelimit = GETSTORIFY_API_SERVICE_DB_PAGE_LIMIT, $storystatusin = null, $category = null)
    {
        $getParam = "?appid=$appid&userid=$userid&accesstoken=$accesstoken";
        $getParam .= "&page=$page";
        $getParam .= "&pagelimit=$pagelimit";

        if (isset($storyid)) {
            $getParam .= "&storyid=$storyid";
        }

        if (isset($storystatusin)) {
            $getParam .= "&storystatusin=$storystatusin";
        }

        if (isset($category)) {
            $getParam .= "&category=$category";
        }

        $url = GETSTORIFY_API_SERVICE_STORY_URL . $getParam;

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