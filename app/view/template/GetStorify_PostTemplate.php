<?php
/**
 * Author: Yusuf Shakeel
 * Date: 12-jun-2018 tue
 * Version: 1.0
 *
 * File: PostTemplate.php
 * Description: This file contains post templates.
 */

class GetStorify_PostTemplate
{
    /**
     * This method will return post html template.
     *
     * @param array $data
     * @param string $showexifbtn
     * @param string $showpostdates
     * @return string
     */
    public static function getPost_MDB($data, $showexifbtn = 'no', $showpostdates = 'no')
    {
        $html = "";

        foreach ($data as $post) {

            $posttitle = ($post['posttitle'] === null) ? "" : $post['posttitle'];
            $postcontent = ($post['postcontent'] === null) ? "" : $post['postcontent'];
            $postdescription = ($post['postdescription'] === null) ? "" : $post['postdescription'];

            $postcontenthtml = '';
            $posttypeclass = '';

            switch ($post['posttype']) {

                case 'IMAGE':
                    $posttypeclass = 'image-post-container';
                    $postcontenthtml = <<<HTML
<img class='getstorify post-image img-fluid center-block' data-postcontent="{$post['postcontent']}" id="post-content-img-{$post['postid']}" src="{$post['postcontentthumbnail']}">
HTML;

                    break;

                case 'IMAGE_GIF':
                    $posttypeclass = 'image-post-container';
                    $postcontenthtml = <<<HTML
<img class='getstorify post-image post-image-gif img-fluid center-block' data-postcontent="{$post['postcontent']}" id="post-content-img-{$post['postid']}" src="{$post['postcontentthumbnail']}">
HTML;

                    break;

                case 'VIDEO':
                    $posttypeclass = 'video-post-container';
                    $postcontenthtml = <<<HTML
<div class="getstorify post-video plyr-setup" id="getstorify-post-video-{$post['postid']}">
<video controls controlsList="nodownload" preload="none">
<source src="{$post['postcontent']}">
</video>
</div>
HTML;

                    break;

                case 'AUDIO':
                    $posttypeclass = 'audio-post-container';
                    $postcontenthtml = <<<HTML
<div class="getstorify post-audio plyr-setup" id="getstorify-post-audio-{$post['postid']}">
<audio controls controlsList="nodownload" preload="none">
<source src="{$post['postcontent']}">
</audio>
</div>
HTML;

                    break;

                case 'LOCATION':
                    $posttypeclass = 'location-post-container';

                    if ($post['latitude'] !== null && $post['longitude'] !== null) {

                        $GoogleAPIKey = GETSTORIFY_API_SERVICE_GOOGLE_API_KEY_WEB_KEY;
                        $postcontenthtml = <<<HTML
<iframe
    class="getstorify post-location getstorify" 
    style="width: 100%; height: 150px;" 
    frameborder="0" 
    src="https://www.google.com/maps/embed/v1/place?key={$GoogleAPIKey}&q={$post['latitude']},{$post['longitude']}"></iframe>
HTML;

                    } else {
                        $postcontenthtml = "";
                    }

                    break;

                case 'SOCIAL_YOUTUBE':
                    $posttypeclass = 'social-post-container';
                    $matchArr = [];
//                    parse_str(parse_url($post['postcontent'], PHP_URL_QUERY), $yt_query_arr);
                    preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $post['postcontent'], $matchArr);
                    $ytvideoid = $matchArr[1];
                    if ($ytvideoid !== null) {
                        $ytembedlink = "https://www.youtube.com/embed/{$ytvideoid}";
                    } else {
                        $ytembedlink = '';
                    }

                    $postcontenthtml = <<<HTML
<div class='getstorify embed-responsive embed-responsive-16by9 thumbnail' style='clear: both; display: block;'>
<iframe class='embed-responsive-item' src="{$ytembedlink}" frameborder='0' allowfullscreen></iframe>
</div>
HTML;

                    break;

                case 'SOCIAL_TWITTER':
                    $posttypeclass = 'social-post-container';
                    $postcontenthtml = <<<HTML
<blockquote class='twitter-tweet' data-lang='en'>
    <a href="{$post['postcontent']}"></a>
</blockquote>
HTML;

                    break;

                case 'SOCIAL_INSTAGRAM':
                    $posttypeclass = 'social-post-container';
                    $postcontenthtml = <<<HTML
<blockquote 
    class='instagram-media' 
    data-lang='en' 
    data-instgrm-captioned 
    data-instgrm-version='7'>
    <a href="{$post['postcontent']}"></a>
</blockquote>
HTML;

                    break;

                case 'SOCIAL_URL':
                    $posttypeclass = 'social-post-container';

                    // if http or https not present then add http
                    $socialLink = $post['postcontent'];
                    if (strpos($socialLink, 'http://') === false) {
                        $socialLink = "http://" . $socialLink;
                    }
                    $postcontenthtml = <<<HTML
<a href="{$socialLink}" target='_blank'>{$post['postcontent']}</a>
HTML;

                    break;

                case 'HASHTAG':
                    $posttypeclass = 'hashtag-post-container';
                    $hashtagArr = explode(" ", $postcontent);
                    $postcontenthtml = "";
                    foreach ($hashtagArr as $hashtag) {
                        if (strlen($hashtag) > 0) {
                            $postcontenthtml .= <<<HTML
<span class='getstorify post-hashtag-container'><a class='post-hashtag prevent-default' href='#' data-hashtag={$hashtag}>{$hashtag}</a> </span>
HTML;

                        }
                    }

                    break;

                case 'DOCUMENT_TEXT':
                case 'DOCUMENT_PDF':
                case 'DOCUMENT_DOC':
                case 'DOCUMENT_DOCX':
                case 'DOCUMENT_PPT':
                case 'DOCUMENT_PPTX':
                case 'DOCUMENT_XLS':
                case 'DOCUMENT_XLSX':

                    $fileLogoImg = '';
                    $fileLink = '';

                    if ($post['posttype'] === 'DOCUMENT_TEXT') {
                        $posttypeclass = 'document-text-post-container document-post-container';
                        $fileLogoImg = GETSTORIFY_BASE_URL . "/image/file-icons/txt.png";
                        $fileLink = "https://docs.google.com/viewer?url={$post['postcontent']}";
                    } else if ($post['posttype'] === 'DOCUMENT_PDF') {
                        $posttypeclass = 'document-pdf-post-container document-post-container';
                        $fileLogoImg = GETSTORIFY_BASE_URL . "/image/file-icons/pdf.png";
                        $fileLink = "https://docs.google.com/viewer?url={$post['postcontent']}";
                    } else if ($post['posttype'] === 'DOCUMENT_DOC') {
                        $posttypeclass = 'document-doc-post-container document-post-container';
                        $fileLogoImg = GETSTORIFY_BASE_URL . "/image/file-icons/doc.png";
                        $fileLink = "https://docs.google.com/viewer?url={$post['postcontent']}";
                    } else if ($post['posttype'] === 'DOCUMENT_DOCX') {
                        $posttypeclass = 'document-docx-post-container document-post-container';
                        $fileLogoImg = GETSTORIFY_BASE_URL . "/image/file-icons/docx.png";
                        $fileLink = $post['postcontent'];
                    } else if ($post['posttype'] === 'DOCUMENT_PPT') {
                        $posttypeclass = 'document-ppt-post-container document-post-container';
                        $fileLogoImg = GETSTORIFY_BASE_URL . "/image/file-icons/ppt.png";
                        $fileLink = "https://docs.google.com/viewer?url={$post['postcontent']}";
                    } else if ($post['posttype'] === 'DOCUMENT_PPTX') {
                        $posttypeclass = 'document-pptx-post-container document-post-container';
                        $fileLogoImg = GETSTORIFY_BASE_URL . "/image/file-icons/pptx.png";
                        $fileLink = $post['postcontent'];
                    } else if ($post['posttype'] === 'DOCUMENT_XLS') {
                        $posttypeclass = 'document-xls-post-container document-post-container';
                        $fileLogoImg = GETSTORIFY_BASE_URL . "/image/file-icons/xls.png";
                        $fileLink = "https://docs.google.com/viewer?url={$post['postcontent']}";
                    } else if ($post['posttype'] === 'DOCUMENT_XLSX') {
                        $posttypeclass = 'document-xlsx-post-container document-post-container';
                        $fileLogoImg = GETSTORIFY_BASE_URL . "/image/file-icons/xlsx.png";
                        $fileLink = $post['postcontent'];
                    } else {
                        $posttypeclass = 'document-unknown-post-container document-post-container';
                        $fileLogoImg = GETSTORIFY_BASE_URL . "/image/file-icons/_blank.png";
                        $fileLink = $post['postcontent'];
                    }

                    $postcontenthtml = <<<HTML
<div class='getstorify media post-document'>
<img class='getstorify document-post-image d-flex mr-2' style='width: 32px; height: 32px;' src="{$fileLogoImg}">
<div class='getstorify media-body'>
<a class='getstorify getstorify-wrap' href="{$fileLink}" target='_blank'>{$postdescription}</a>
</div><!--/ .img -->
</div><!--/ .media -->
HTML;

                    break;

                default:
                    $posttypeclass = 'text-post-container';
                    $postcontenthtml = <<<HTML
<div class='getstorify post-text getstorify-wrap'>{$postcontent}</div>
HTML;

            }

            /**
             * create html for the post
             */
            $html .= <<<HTML
<div class='col-xs-12 col-sm-12 col-md-12 col-lg-12 getstorify post-container my-2'>
<div class='getstorify post-content-container {$posttypeclass}'>
{$postcontenthtml}
</div><!--/ .post-content-container -->
</div><!--/ .col -->
HTML;

        } // foreach ends here

        return $html;
    }

}