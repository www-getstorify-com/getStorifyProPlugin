<?php
/**
 * Author: Yusuf Shakeel
 * Date: 06-Jun-2018 Wed
 * Version: 1.2
 *
 * File: GetStorify.php
 * Description: This is the main file.
 */

namespace GetStorify;

require_once __DIR__ . '/Config/Constants.php';

use GetStorify\Authentication\AccessToken;
use GetStorify\User\User;
use GetStorify\User\Story;
use GetStorify\User\Post;
use GetStorify\User\StoryLocation;
use GetStorify\User\Groups;
use GetStorify\User\GroupStories;
use GetStorify\User\GroupStoryPosts;
use GetStorify\User\GroupMembers;

/**
 * Class GetStorify
 * @package GetStorify
 */
class GetStorify
{

    /**
     * The AppID of the user app.
     *
     * @var string
     */
    private $appid = '';

    /**
     * The UserId of the user.
     *
     * @var string
     */
    private $userid = '';

    /**
     * The AppToken (this is the secret) for the user app.
     *
     * @var string
     */
    private $apptoken = '';

    /**
     * The Access Token.
     *
     * @var string
     */
    private $accesstoken = '';

    /**
     * The domain from where the request is being sent.
     *
     * @var string
     */
    private $origin = '';

    /**
     * AccessToken constructor.
     * @param string $appid
     * @param string $userid
     * @param string $apptoken
     */
    public function __construct($appid, $userid, $apptoken)
    {
        $this->appid = $appid;
        $this->userid = $userid;
        $this->apptoken = $apptoken;
    }

    /**
     * This will validate the user AppID and fetch access token.
     *
     * @return mixed
     */
    public function getAccessToken()
    {
        $obj = new AccessToken();
        $result = $obj->getAccessToken(
            $this->appid,
            $this->userid,
            $this->apptoken
        );

        if (isset($result['success'])) {
            $this->accesstoken = $result['success']['accesstoken'];
            $this->origin = $result['success']['domain'];
        }

        return $result;
    }

    /**
     * This method will return the user detail.
     *
     * @return mixed
     */
    public function getUserDetail()
    {
        $obj = new User();
        $result = $obj->getUserDetail(
            $this->appid,
            $this->userid,
            $this->accesstoken,
            ['origin' => $this->origin]
        );

        return $result;
    }

    /**
     * This method will return the story.
     *
     * @param null|string $storyid
     * @param int $page
     * @param int $pagelimit
     * @param null|string $storystatusin Comman separated values like PUBLISHED,GROUP_STORY or single value like PUBLISHED
     * @param null|string $category Array of integer values like: [1] or [1,2]
     * @return mixed
     */
    public function getStory($storyid = null, $page = 1, $pagelimit = GETSTORIFY_API_SERVICE_DB_PAGE_LIMIT, $storystatusin = null, $category = null)
    {
        $obj = new Story();
        $result = $obj->getStory(
            $this->appid,
            $this->userid,
            $this->accesstoken,
            ['origin' => $this->origin],
            $storyid,
            $page,
            $pagelimit,
            $storystatusin,
            $category
        );

        return $result;
    }

    /**
     * This method will return the post of a story.
     *
     * @param string $storyid
     * @param null|string $postid
     * @param int $page
     * @param int $pagelimit
     * @return mixed
     */
    public function getPost($storyid, $postid = null, $page = 1, $pagelimit = GETSTORIFY_API_SERVICE_DB_PAGE_LIMIT)
    {
        $obj = new Post();
        $result = $obj->getPost(
            $this->appid,
            $this->userid,
            $this->accesstoken,
            ['origin' => $this->origin],
            $storyid,
            $postid,
            $page,
            $pagelimit
        );

        return $result;
    }

    /**
     * This method will return the post location of a story.
     *
     * @param string $storyid
     * @param int $page
     * @param int $pagelimit
     * @return mixed
     */
    public function getPostLocation($storyid, $page = 1, $pagelimit = GETSTORIFY_API_SERVICE_DB_PAGE_LIMIT)
    {
        $obj = new Post();
        $result = $obj->getPostLocation(
            $this->appid,
            $this->userid,
            $this->accesstoken,
            ['origin' => $this->origin],
            $storyid,
            $page,
            $pagelimit
        );

        return $result;
    }

    /**
     * This method will return the story location.
     *
     * @param string $storyid
     * @param int $page
     * @param int $pagelimit
     * @return mixed
     */
    public function getStoryLocation($storyid, $page = 1, $pagelimit = GETSTORIFY_API_SERVICE_DB_PAGE_LIMIT)
    {
        $obj = new StoryLocation();
        $result = $obj->getStoryLocation(
            $this->appid,
            $this->userid,
            $this->accesstoken,
            ['origin' => $this->origin],
            $storyid,
            $page,
            $pagelimit
        );

        return $result;
    }

    /**
     * This method will return the group detail.
     *
     * @param null|string $groupid
     * @param null|string $grouphandle
     * @return mixed
     */
    public function getGroupDetail($groupid = null, $grouphandle = null)
    {
        $obj = new Groups();
        $result = $obj->getGroupDetail(
            $this->appid,
            $this->userid,
            $this->accesstoken,
            ['origin' => $this->origin],
            $groupid,
            $grouphandle
        );

        return $result;
    }

    /**
     * This method will return the group stories.
     *
     * @param string $groupid
     * @param null|string $storyid
     * @param int $page
     * @param int $pagelimit
     * @return mixed
     */
    public function getGroupStories($groupid, $storyid = null, $page = 1, $pagelimit = GETSTORIFY_API_SERVICE_DB_PAGE_LIMIT)
    {
        $obj = new GroupStories();
        $result = $obj->getGroupStories(
            $this->appid,
            $this->userid,
            $this->accesstoken,
            ['origin' => $this->origin],
            $groupid,
            $storyid,
            $page,
            $pagelimit
        );

        return $result;
    }

    /**
     * This method will return the prev-next stories of the group stories.
     *
     * @param string $groupid
     * @param string $storyid
     * @return mixed
     */
    public function getGroupStories_PrevNextStories($groupid, $storyid)
    {
        $obj = new GroupStories();
        $result = $obj->getGroupStories_PrevNextStories(
            $this->appid,
            $this->userid,
            $this->accesstoken,
            ['origin' => $this->origin],
            $groupid,
            $storyid
        );

        return $result;
    }

    /**
     * This will return the public details of the members of a particular group.
     *
     * @param string $groupid
     * @param null|string $memberid
     * @param null|string $membertype
     * @param null|string $firstname
     * @param null|string $lastname
     * @param int $page
     * @param int $pagelimit
     * @return mixed
     */
    public function getGroupMembers($groupid, $memberid = null, $membertype = null, $firstname = null, $lastname = null, $page = 1, $pagelimit = GETSTORIFY_API_SERVICE_DB_PAGE_LIMIT)
    {
        $obj = new GroupMembers();
        $result = $obj->getGroupMembers(
            $this->appid,
            $this->userid,
            $this->accesstoken,
            ['origin' => $this->origin],
            $groupid,
            $memberid,
            $membertype,
            $firstname,
            $lastname,
            $page,
            $pagelimit
        );

        return $result;
    }

    /**
     * This method will return the group story post.
     *
     * @param string $storyid
     * @param null $groupid
     * @param null|string $grouphandle
     * @param null|string $postid
     * @param null|string $posttype
     * @param int $page
     * @param int $pagelimit
     * @return mixed
     */
    public function getGroupStoryPost($storyid, $groupid, $grouphandle = null, $postid = null, $posttype = null, $page = 1, $pagelimit = GETSTORIFY_API_SERVICE_DB_PAGE_LIMIT)
    {
        $obj = new GroupStoryPosts();
        $result = $obj->getGroupStoryPosts(
            $this->appid,
            $this->userid,
            $this->accesstoken,
            ['origin' => $this->origin],
            $groupid,
            $storyid,
            $grouphandle,
            $postid,
            $posttype,
            $page,
            $pagelimit
        );

        return $result;
    }

    /**
     * This method will return the group story post gallery.
     *
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
    public function getPost_Public_Gallery_Of_GroupStory($groupid, $storyid = null, $story_userid = null, $postid = null, $posttype = null, $page = 1, $pagelimit = GETSTORIFY_API_SERVICE_DB_PAGE_LIMIT, $storycategory = null)
    {
        $obj = new Post();
        $result = $obj->getPost_Public_Gallery_Of_GroupStory(
            $this->appid,
            $this->userid,
            $this->accesstoken,
            ['origin' => $this->origin],
            $groupid,
            $storyid,
            $story_userid,
            $postid,
            $posttype,
            $page,
            $pagelimit,
            $storycategory
        );

        return $result;
    }

    /**
     * This method will return the group story post gallery.
     *
     * @param string $storyid
     * @param null|string $story_userid
     * @param null|string $postid
     * @param null|string $posttype
     * @param int $page
     * @param int $pagelimit
     * @param null|array $storycategory
     * @return mixed
     */
    public function getPost_Public_Gallery_Of_Story($storyid = null, $story_userid = null, $postid = null, $posttype = null, $page = 1, $pagelimit = GETSTORIFY_API_SERVICE_DB_PAGE_LIMIT, $storycategory = null)
    {
        $obj = new Post();
        $result = $obj->getPost_Public_Gallery_Of_Story(
            $this->appid,
            $this->userid,
            $this->accesstoken,
            ['origin' => $this->origin],
            $storyid,
            $story_userid,
            $postid,
            $posttype,
            $page,
            $pagelimit,
            $storycategory
        );

        return $result;
    }

}