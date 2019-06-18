<?php
/**
 * Author: Yusuf Shakeel
 * Date: 06-Jun-2018 Wed
 * Version: 1.0
 *
 * File: User.php
 * Description: This file contains User code.
 */

namespace GetStorify\User;

/**
 * Class User
 * @package GetStorify\User
 */
class User
{
    /**
     * This will return the user detail.
     *
     * @param string $appid
     * @param string $userid
     * @param string $accesstoken
     * @param array $header
     * @return mixed
     */
    public function getUserDetail($appid, $userid, $accesstoken, $header)
    {
        $getParam = "?appid=$appid&userid=$userid&accesstoken=$accesstoken";

        $url = GETSTORIFY_API_SERVICE_USER_DETAIL_URL . $getParam;

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