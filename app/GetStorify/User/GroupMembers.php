<?php
/**
 * Author: Yusuf Shakeel
 * Date: 06-sep-2018 thu
 * Version: 1.0
 *
 * File: GroupMembers.php
 * Description: This file contains group members code.
 */

namespace GetStorify\User;

/**
 * Class GroupMembers
 * @package GetStorify\User
 */
class GroupMembers
{
    /**
     * This will return the Group stories.
     *
     * @param string $appid
     * @param string $userid
     * @param string $accesstoken
     * @param array $header
     * @param string $groupid
     * @param null|string $memberid
     * @param null|string $membertype
     * @param null|string $firstname
     * @param null|string $lastname
     * @param int $page
     * @param int $pagelimit
     * @return mixed
     */
    public function getGroupMembers($appid, $userid, $accesstoken, $header, $groupid = null, $memberid = null, $membertype = null, $firstname = null, $lastname = null, $page = 1, $pagelimit = GETSTORIFY_API_SERVICE_DB_PAGE_LIMIT)
    {
        $getParam = "?appid=$appid&userid=$userid&accesstoken=$accesstoken";
        $getParam .= "&page=$page";
        $getParam .= "&pagelimit=$pagelimit";
        $getParam .= "&groupid=$groupid";

        if (isset($memberid)) {
            $getParam .= "&memberid=$memberid";
        }

        if (isset($membertype)) {
            $getParam .= "&membertype=$membertype";
        }

        if (isset($firstname)) {
            $getParam .= "&firstname=$firstname";
        }

        if (isset($lastname)) {
            $getParam .= "&lastname=$lastname";
        }

        $url = GETSTORIFY_API_SERVICE_GROUP_MEMBERS_URL . $getParam;

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