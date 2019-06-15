<?php
/**
 * Author: Yusuf Shakeel
 * Date: 07-jun-2018 thu
 * Version: 1.0
 *
 * File: Groups.php
 * Description: This file contains Groups code.
 */

namespace GetStorify\User;

/**
 * Class Groups
 * @package GetStorify\User
 */
class Groups
{
    /**
     * This will return the Groups detail.
     *
     * @param string $appid
     * @param string $userid
     * @param string $accesstoken
     * @param array $header
     * @param null|string $groupid
     * @param null|string $grouphandle
     * @return mixed
     */
    public function getGroupDetail($appid, $userid, $accesstoken, $header, $groupid = null, $grouphandle = null)
    {
        $getParam = "?appid=$appid&userid=$userid&accesstoken=$accesstoken";
        if (isset($groupid)) {
            $getParam .= "&groupid=$groupid";
        }
        if (isset($grouphandle)) {
            $getParam .= "&grouphandle=$grouphandle";
        }

        $url = GETSTORIFY_API_SERVICE_GROUPS_URL . $getParam;

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