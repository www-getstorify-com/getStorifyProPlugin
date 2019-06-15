<?php
/**
 * Author: Yusuf Shakeel
 * Date: 17-sep-2018 mon
 * Version: 1.0
 *
 * File: wp-setup-page.php
 * Description: This page contains the form to setup the plugin.
 */

require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../../getStorifyPlugin-routes.php';

// shortcode
if (!get_option(GETSTORIFY_PLUGIN_WP_OPTIONS_TABLE_OPTION_NAME_PLUGIN_SHORTCODE)) {

    update_option(
        GETSTORIFY_PLUGIN_WP_OPTIONS_TABLE_OPTION_NAME_PLUGIN_SHORTCODE,
        GETSTORIFY_PLUGIN_WP_SHORTCODE
    );

}


/** save data: getStorify plugin credentials **/
if (isset($_POST['getStorifyPluginOptions_data_save'])) {

    //Form data sent
    unset($_POST['getStorifyPluginOptions_data_save']);

    foreach ($_POST as $key => $val) {
        update_option($key, $val);
    }

    // output the success message
    echo <<<HTML
    <div class="notice notice-success is-dismissible">
        <h2>getStorify credentials saved.</h2>
        <p>Now, check the <a href="#getstorify-configured-profile">Configured Profile</a> section to see if your account is set.</p>
        <button type="button" class="notice-dismiss">
            <span class="screen-reader-text">Dismiss this notice.</span>
        </button>
    </div>
HTML;


}
// save data: getStorify plugin credentials

$app_user_id = get_option(GETSTORIFY_PLUGIN_WP_OPTIONS_TABLE_OPTION_NAME_PLUGIN_USER_ID);
$app_id = get_option(GETSTORIFY_PLUGIN_WP_OPTIONS_TABLE_OPTION_NAME_PLUGIN_APP_ID);
$app_token = get_option(GETSTORIFY_PLUGIN_WP_OPTIONS_TABLE_OPTION_NAME_PLUGIN_APP_TOKEN);
$app_shortcode = get_option(GETSTORIFY_PLUGIN_WP_OPTIONS_TABLE_OPTION_NAME_PLUGIN_SHORTCODE);

$app_isConfiguredCorrectly = false;

/** register page/post route **/
if (isset($_POST['getStorifyPluginOptions_Routes_data_save'])) {

    //Form data sent
    unset($_POST['getStorifyPluginOptions_Routes_data_save']);

    if (isset($_POST['getStorifyPluginOptions_Routes_slug'])) {

        $slug = trim($_POST['getStorifyPluginOptions_Routes_slug']);

        // add route
        if (getStorifyPlugin_Routes_AddRouteRule_func($slug) === true) {
            $isRouteAddedSuccessfully = true;
        } else {
            $isRouteAddedSuccessfully = false;
        }

    }

    if ($isRouteAddedSuccessfully) {

        echo <<<HTML
<div class="notice notice-success is-dismissible">
    <h2>Routes updated</h2>
    <p>Slug added: {$slug}</p>
    <p>Now head over to <strong>Settings > Permalinks</strong> page and click the <strong>Save Changes</strong> button.</p>
    <button type="button" class="notice-dismiss">
        <span class="screen-reader-text">Dismiss this notice.</span>
    </button>
</div>
HTML;

    } else {

        echo <<<HTML
<div class="notice notice-error is-dismissible">
    <h2>Failed to update routes</h2>
    <p>Slug: {$slug}</p>
    <p>Please try again later.</p>
    <button type="button" class="notice-dismiss">
        <span class="screen-reader-text">Dismiss this notice.</span>
    </button>
</div>
HTML;

    }

}
// register page/post routes ends here

/** remove page/post route **/
if (isset($_POST['getStorifyPluginOptions_RoutesRemove_data_save'])) {

    //Form data sent
    unset($_POST['getStorifyPluginOptions_RoutesRemove_data_save']);

    if (isset($_POST['getStorifyPluginOptions_RoutesRemove_slug'])) {

        $slug = trim($_POST['getStorifyPluginOptions_RoutesRemove_slug']);

        // remove route
        if (getStorifyPlugin_Routes_RemoveRouteRule_func($slug) === true) {
            $isRouteRemovedSuccessfully = true;
        } else {
            $isRouteRemovedSuccessfully = false;
        }

    }

    if ($isRouteRemovedSuccessfully) {

        echo <<<HTML
<div class="notice notice-success is-dismissible">
    <h2>Routes updated</h2>
    <p>Slug removed: {$slug}</p>
    <p>Now head over to <strong>Settings > Permalinks</strong> page and click the <strong>Save Changes</strong> button.</p>
    <button type="button" class="notice-dismiss">
        <span class="screen-reader-text">Dismiss this notice.</span>
    </button>
</div>
HTML;

    } else {

        echo <<<HTML
<div class="notice notice-error is-dismissible">
    <h2>Failed to update routes</h2>
    <p>Slug: {$slug}</p>
    <p>Please try again later.</p>
    <button type="button" class="notice-dismiss">
        <span class="screen-reader-text">Dismiss this notice.</span>
    </button>
</div>
HTML;

    }

}
// remove page/post routes ends here


/** get the list of routes slug added by the user **/

$customRoutesSlugAddedHTML = getStorifyPlugin_List_AddedRoutes_func();

// get the list of routes slug added by the user ends here

?>

<div class="wrap">

    <h2>getStorify Pro Plugin Configurations</h2>

    <div class="gtst-shortcode-form-container">

        <h2>Upload API Service Config JSON file</h2>

        <form id="form-api-service-config-file">
            <input type="file"
                   id="form-api-service-config-file-index"
                   accept=".json" style="font-size: 16px;">

            <div id="form-api-service-config-file-msg-container" style="margin-top: 15px; margin-bottom: 15px;"></div>
        </form>

    </div>

    <h4>--- OR ---</h4>

    <div class="gtst-shortcode-form-container">

        <form name="getStorifyPluginOptions_form"
              method="post"
              action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>">

            <h2>Enter the following details:</h2>

            <p>
                <label>User ID:</label>
                <input required
                       type="text"
                       maxlength="64"
                       name="getStorifyPluginOptions_user_id"
                       id="getStorifyPluginOptions_user_id"
                       value="<?php echo $app_user_id; ?>">
            </p>

            <p>
                <label>App ID:</label>
                <input required
                       type="text"
                       maxlength="64"
                       name="getStorifyPluginOptions_app_id"
                       id="getStorifyPluginOptions_app_id"
                       value="<?php echo $app_id; ?>">
            </p>

            <p>
                <label>App Token:</label>
                <input required
                       type="text"
                       maxlength="500"
                       name="getStorifyPluginOptions_app_token"
                       id="getStorifyPluginOptions_app_token"
                       value="<?php echo $app_token; ?>">
            </p>

            <p class="submit">
                <input type="submit"
                       class="gs-btn-save"
                       name="getStorifyPluginOptions_data_save"
                       value="Save"/>


                <input type="reset"
                       class="gs-btn-reset"
                       value="Reset"
                       onclick="window.location.reload();"/>
            </p>

        </form><!--/ .form -->

    </div>

    <?php if ($app_id && $app_token && $app_user_id) {

        require_once __DIR__ . '/../../GetStorify/autoload.php';

        /**
         * create object
         */
        $GetStorifyAPIServiceObj = new \GetStorify\GetStorify(
            $app_id,
            $app_user_id,
            $app_token
        );

        /**
         * get access token
         */
        $result = $GetStorifyAPIServiceObj->getAccessToken();

        /**
         * is access token issued
         */
        if (isset($result['success'])) {

            // get user profile detail
            $result = $GetStorifyAPIServiceObj->getUserDetail();

            if (isset($result['success'])) {

                $app_isConfiguredCorrectly = true;

                $result = $result['success'];

                $picture = GETSTORIFY_PLUGIN_USER_DEFAULT_PROFILE_IMAGE;
                if ($result['picture'] != null) {
                    $picture = $result['picturethumbnail'];
                }

                $fullname = '';
                if ($result['firstname'] != null) {
                    $fullname = $result['firstname'] . " ";
                }
                if ($result['lastname'] != null) {
                    $fullname .= $result['lastname'];
                }

                $resultHTML = <<<HTML
<hr>

<a name="getstorify-configured-profile"></a>
<h1>Configured Profile:</h1>

<div class="gtst-shortcode-configured-profile-container">

<div class='getstorify media'>
<img class='getstorify mr-1 rounded-circle img-thumbnail view-user-profile user-profile-image' style='width: 64px; height: 64px;' src='{$picture}' data-userid='{$result['userid']}'>
<div class='getstorify media-body'>
<h1 class='getstorify getstorify-wrap mt-0 font-weight-light mb-1'>
<strong class='getstorify view-user-profile user-fullname' data-userid='{$result['userid']}'>{$fullname}</strong>
</h1>
</div><!--/ .media-body -->
</div><!--/ .media -->

</div>
HTML;

            } else {

                $resultHTML = <<<HTML
<hr>
<div style="background-color: crimson; padding: 10px;">
<h2 style="color: #fff;">Failed to fetch profile details. Please check the UserID, AppID and AppToken.</h2>
</div>
HTML;

            }

        } else {

            $resultHTML = <<<HTML
<hr>
<div style="background-color: crimson; padding: 10px;">
<h2 style="color: #fff;">Invalid credential. Please check the UserID, AppID and AppToken.</h2>
</div>
HTML;

        }

        echo $resultHTML;

    } ?>

    <hr/>

    <?php if ($app_id && $app_token && $app_user_id && $app_isConfiguredCorrectly) { ?>


        <h1>Register page where you are using the shortcode</h1>

        <div class="gtst-shortcode-attribute-container">

            <div class="gtst-shortcode-form-container">

                <form name="getStorifyPluginOptions_Routes_form"
                      method="post"
                      action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>">

                    <h2>Enter page slug:</h2>

                    <p>Example:</p>

                    <p>If the page were you have used the shortcode is https://example.com/hello then slug = hello</p>

                    <p>
                        <label>Slug:</label>
                        <input required
                               type="text"
                               maxlength="200"
                               name="getStorifyPluginOptions_Routes_slug"
                               id="getStorifyPluginOptions_Routes_slug"
                    </p>

                    <p class="submit">
                        <input type="submit"
                               class="gs-btn-save"
                               name="getStorifyPluginOptions_Routes_data_save"
                               value="Add Route Rule"/>


                        <input type="reset"
                               class="gs-btn-reset"
                               value="Reset"
                               onclick="window.location.reload();"/>
                    </p>

                </form><!--/ .form -->

                <div class="custom-route-slug-container">
                    <p><strong>Added routes:</strong></p>
                    <?php echo $customRoutesSlugAddedHTML; ?>
                </div>

                <p><strong>Note!</strong></p>
                <p>To refresh the routes go to <strong>Settings > Permalinks</strong> page and click the <strong>Save
                        Changes</strong> button.</p>

            </div>

        </div>


        <h1>Remove page routes where you are using the shortcode</h1>

        <div class="gtst-shortcode-attribute-container">

            <div class="gtst-shortcode-form-container">

                <form name="getStorifyPluginOptions_RoutesRemove_form"
                      method="post"
                      action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>">

                    <h2>Enter page slug:</h2>

                    <p>Example:</p>

                    <p>If the page were you have used the shortcode is https://example.com/hello then slug = hello</p>

                    <p>
                        <label>Slug:</label>
                        <input required
                               type="text"
                               maxlength="200"
                               name="getStorifyPluginOptions_RoutesRemove_slug"
                               id="getStorifyPluginOptions_RoutesRemove_slug"
                    </p>

                    <p class="submit">
                        <input type="submit"
                               class="gs-btn-save"
                               name="getStorifyPluginOptions_RoutesRemove_data_save"
                               value="Remove Route Rule"/>


                        <input type="reset"
                               class="gs-btn-reset"
                               value="Reset"
                               onclick="window.location.reload();"/>
                    </p>

                </form><!--/ .form -->

                <p><strong>Note!</strong></p>
                <p>To refresh the routes go to <strong>Settings > Permalinks</strong> page and click the <strong>Save
                        Changes</strong> button.</p>

            </div>

        </div>


        <h1>How to use the shortcode?</h1>


        <div class="gtst-shortcode-attribute-container">
            <p>Place the shortcode <b style="font-size: 18px;">[<?php echo $app_shortcode; ?>]</b>
                on the page where you want to display stories from getStorify.
            </p>
        </div>

        <hr>


        <h1>Shortcode configurations</h1>

        <div class="gtst-shortcode-attribute-container">
            <div class="getstorify table-responsive">

                <table class="getstorify table">

                    <tr>
                        <th>Task</th>
                        <th>Shortcode</th>
                        <th>Example</th>
                    </tr>

                    <tr>
                        <td>Fetch all stories owned by the user</td>
                        <td>[<?php echo $app_shortcode; ?>]</td>
                        <td>[<?php echo $app_shortcode; ?>]</td>
                    </tr>

                    <tr>
                        <td>Fetch all stories owned by the user</td>
                        <td>[<?php echo $app_shortcode; ?> view="STORY"]</td>
                        <td>[<?php echo $app_shortcode; ?> view="STORY"]</td>
                    </tr>

                    <tr>
                        <td>Fetch specific story by storyid owned by the user</td>
                        <td>[<?php echo $app_shortcode; ?> storyid="{storyid}"]</td>
                        <td>[<?php echo $app_shortcode; ?> storyid="gs123"]</td>
                    </tr>

                    <tr>
                        <td>Fetch all stories of a particular group owned by the user</td>
                        <td>[<?php echo $app_shortcode; ?> view="GROUP" groupid="{groupid}"]</td>
                        <td>[<?php echo $app_shortcode; ?> view="GROUP" groupid="gs123"]</td>
                    </tr>

                    <tr>
                        <td>Gallery - Stories</td>
                        <td>[<?php echo $app_shortcode; ?> view="GALLERY_MASONRY_STORIES" posttype="type"]</td>
                        <td>[<?php echo $app_shortcode; ?> view="GALLERY_MASONRY_STORIES" posttype="IMAGE"]</td>
                    </tr>

                    <tr>
                        <td>Gallery - Group Stories</td>
                        <td>[<?php echo $app_shortcode; ?> view="GALLERY_MASONRY_GROUP_STORIES" posttype="type"
                            groupid="{groupid}"]
                        </td>
                        <td>[<?php echo $app_shortcode; ?> view="GALLERY_MASONRY_GROUP_STORIES" posttype="IMAGE"
                            groupid="gsG123"]
                        </td>
                    </tr>

                </table>

            </div>
        </div>

        <hr>

        <h1>Shortcode doc</h1>

        <div class="gtst-shortcode-attribute-container">
            <h3>Gallery - Stories</h3>

            <p>Use this to render gallery of posts of your public stories.</p>

            <h4>Attributes</h4>

            <p><code>posttype</code> (required) - The post type for the gallery.</p>
            <p>Values: <strong>IMAGE</strong>, <strong>IMAGE_GIF</strong>, <strong>VIDEO</strong></p>
            <p>Example: <code>posttype="IMAGE"</code></p>

            <p><code>gallery_item_per_page</code> (optional) - This is the total number of items to be displayed per
                page.</p>
            <p>Default: 10 <br>
                Max: 20
            </p>

            <p><code>storyid</code> (optional) - This will fetch posts of a particular story.</p>

            <p><code>story_category</code> (optional) - This will fetch posts of a particular story catgory.</p>
            <p>Value: Array of category id.</p>
            <p>Example: <code>story_category=[1]</code> or <code>story_category=[1,2,3]</code></p>

            <h3>Example</h3>
            <p>In the following setup we are rendering gallery of images.</p>
            <p><code>[<?php echo $app_shortcode; ?> view="GALLERY_MASONRY_STORIES" posttype="IMAGE"
                    gallery_item_per_page="15"]</code></p>

        </div>

        <div class="gtst-shortcode-attribute-container">
            <h3>Gallery - Group Stories</h3>

            <p>Use this to render gallery of posts of your public group stories.</p>

            <h4>Attributes</h4>

            <p><code>groupid</code> (required) - Id of a public group</p>

            <p><code>posttype</code> (required) - The post type for the gallery.</p>
            <p>Values: <strong>IMAGE</strong>, <strong>IMAGE_GIF</strong>, <strong>VIDEO</strong></p>
            <p>Example: <code>posttype="IMAGE"</code></p>

            <p><code>gallery_item_per_page</code> (optional) - This is the total number of items to be displayed per
                page.</p>
            <p>Default: 10 <br>
                Max: 20
            </p>

            <p><code>storyid</code> (optional) - This will fetch posts of a particular story.</p>

            <p><code>story_category</code> (optional) - This will fetch posts of a particular story catgory.</p>
            <p>Value: Array of category id.</p>
            <p>Example: <code>story_category=[1]</code> or <code>story_category=[1,2,3]</code></p>

            <h3>Example</h3>
            <p>In the following setup we are rendering gallery of images.</p>
            <p><code>[<?php echo $app_shortcode; ?> view="GALLERY_MASONRY_GROUP_STORIES" posttype="IMAGE"
                    groupid="gsG123" gallery_item_per_page="15"]</code></p>

        </div>

        <hr>

        <h2>Attributes you can add to the shortcode.</h2>

        <div class="gtst-shortcode-attribute-container">
            <h3><code>story_cover_carousel="yes"</code></h3>

            <p>This will render the cover of the stories as carousel.</p>

            <p>Tips: You can use this to show story covers as offers.</p>

            <p>Example:</p>

            <p><code>[<?php echo $app_shortcode; ?> view="STORY" story_cover_carousel="yes"]</code></p>
            <p><code>[<?php echo $app_shortcode; ?> view="GROUP" groupid="gs123" story_cover_carousel="yes"]</code></p>

            <p><strong>NOTE!</strong></p>
            <p>If you use <code>story_cover_carousel</code> attribute then you can't use any other attributes like
                <code>display_only</code>, etc.</p>
        </div>

        <div class="gtst-shortcode-attribute-container">
            <h3><code>display_only="ITEM_1,ITEM_2,..."</code></h3>

            <p>This gives granular control over stories.</p>

            <p><code>ITEM_n</code> represents the items you want to show for every story card.</p>

            <p>Values that you can use for the <code>ITEM_n</code>.</p>

            <ul>
                <li><code>STORY_COVER</code> - If present, will render the story cover.</li>
                <li><code>STORY_TITLE</code> - If present, will render the story title.</li>
                <li><code>STORY_DESCRIPTION</code> - If present, will render the truncated story description.</li>
                <li><code>STORY_FULL_DESCRIPTION</code> - If present, will render the full story description.</li>
                <li><code>STORY_USER_DETAIL</code> - If present, will render the user detail who created the story.</li>
                <li><code>STORY_READ_MORE_BTN</code> - If present, will render the read more button.</li>
            </ul>

            <p>Example:</p>

            <p><code>[<?php echo $app_shortcode; ?> view="STORY" display_only="STORY_COVER,STORY_READ_MORE_BTN"]</code>
            </p>
            <p><code>[<?php echo $app_shortcode; ?> view="GROUP" groupid="gs123" columns="2"
                    display_only="STORY_COVER,STORY_READ_MORE_BTN"]</code></p>

            <p><strong>Don't add space between the values of display_only.</strong></p>

            <p><strong>NOTE!</strong></p>
            <p>If this attribute is present then the following attributes are ignored.</p>
            <ul>
                <li><code>display_story_cover</code></li>
                <li><code>display_user_detail</code></li>
                <li><code>display_story_masthead</code></li>
            </ul>
        </div>

        <div class="gtst-shortcode-attribute-container">
            <h3><code>columns={no_of_cols}</code></h3>

            <p>This will render the stories in columns.</p>

            <p>columns can have values = 1, 2, 3 and 4.</p>

            <p>Example:</p>

            <p><code>[<?php echo $app_shortcode; ?> view="STORY" columns="2"]</code></p>
            <p><code>[<?php echo $app_shortcode; ?> view="GROUP" groupid="gs123" columns="2"]</code></p>
        </div>

        <div class="gtst-shortcode-attribute-container">
            <h3><code>display_story_cover="none"</code></h3>

            <p>This will hide the story cover.</p>

            <p>Example:</p>

            <p><code>[<?php echo $app_shortcode; ?> storyid="gs123" display_story_cover="none"]</code></p>
        </div>

        <div class="gtst-shortcode-attribute-container">
            <h3><code>display_user_detail="none"</code></h3>

            <p>This will hide the user detail from the story.</p>

            <p>Example:</p>

            <p><code>[<?php echo $app_shortcode; ?> storyid="gs123" display_user_detail="none"]</code></p>
        </div>

        <div class="gtst-shortcode-attribute-container">
            <h3><code>display_story_masthead="none"</code></h3>

            <p>This will hide the story cover, title, description and user details.</p>

            <p>Example:</p>

            <p><code>[<?php echo $app_shortcode; ?> storyid="gs123" display_story_masthead="none"]</code></p>
        </div>

        <div class="gtst-shortcode-attribute-container">
            <h3><code>story_per_page="{no_of_stories}"</code></h3>

            <p>This sets the total number of stories to fetch per page.</p>

            <p>Min value: 1 <br>
                Max value: 10
            </p>

            <p>Example:</p>

            <p><code>[<?php echo $app_shortcode; ?> story_per_page="3"]</code></p>
        </div>

    <?php } ?>

</div><!--/ .wrap -->

<style>
    .gtst-shortcode-configured-profile-container,
    .gtst-shortcode-form-container,
    .gtst-shortcode-attribute-container {
        padding: 15px;
        background-color: #fff;
        border-top-color: #999;
        border-top-width: 5px;
        margin-bottom: 15px;
    }

    label {
        width: 200px;
        float: left;
    }

    input[type="text"], select {
        padding: 7px 7px;
        width: 44%;
    }

    .gs-btn-reset,
    .gs-btn-save {
        border-radius: 4px;
        padding: 10px 20px;
        font-size: 24px;
        cursor: pointer;
    }

    .getstorify.table-responsive {
        overflow-x: scroll;
    }

    table.getstorify.table {
        width: 100%;
        margin-bottom: 20px;
    }

    table.getstorify.table tr td,
    table.getstorify.table tr th {
        padding: 10px 5px;
        border-bottom: 1px solid black;
    }

    .media {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-align: start;
        align-items: flex-start;
    }

    .media-body {
        flex: 1;
    }

    .mr-1 {
        margin-right: 5px;
    }

    img.rounded-circle.img-thumbnail {
        border: 2px solid #eee;
        border-radius: 50%;
        background-color: #fff;
    }
</style>

<script type="text/javascript">
    window.onload = function () {

        // check json form input field change
        document
            .getElementById('form-api-service-config-file-index')
            .addEventListener('change', function (event) {

                var reader = new FileReader();

                if (typeof event.target.files[0] !== 'undefined') {

                    reader.onload = function (event) {
                        var jsonObj = JSON.parse(event.target.result);

                        if (typeof jsonObj.appid !== 'undefined') {

                            // set the config fields
                            document
                                .getElementById('getStorifyPluginOptions_app_id')
                                .value = jsonObj.appid;

                            document
                                .getElementById('getStorifyPluginOptions_user_id')
                                .value = jsonObj.userid;

                            document
                                .getElementById('getStorifyPluginOptions_app_token')
                                .value = jsonObj.apptoken;


                            // reset form
                            document
                                .getElementById("form-api-service-config-file")
                                .reset();

                            var html = "<div class='notice notice-info is-dismissible' id='form-api-service-config-file-notification'>" +
                                "<h4>Configuration added</h4>" +
                                "<p>Click the <strong>SAVE</strong> button to save.</p>" +
                                "<button type='button' class='notice-dismiss'>" +
                                "<span class='screen-reader-text'>Dismiss this notice.</span>" +
                                "</button>" +
                                "</div>";

                            document
                                .getElementById('form-api-service-config-file-msg-container')
                                .innerHTML = html;

                            setTimeout(function () {
                                document
                                    .getElementById('form-api-service-config-file-msg-container')
                                    .innerHTML = "";
                            }, 5000);

                        }
                    };

                    reader.readAsText(event.target.files[0]);

                }

            });

    };
</script>