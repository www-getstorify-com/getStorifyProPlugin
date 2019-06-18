<?php
/**
 * Author: Yusuf Shakeel
 * Date: 11-sep-2018
 * Version: 1.0
 *
 * File: footer.php
 * Description: This page contains the footer.
 */

?>
<!-- getstorify-twbs custom bootstrap -->
<link rel="stylesheet"
      href="<?php echo GETSTORIFY_PLUGIN_DIR_URL; ?>/app/plugin/getstorify-twbs-4.1.3/dist/css/bootstrap-grid.min.css">

<!-- plyr -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/plyr/2.0.18/plyr.css"
      integrity="sha256-fZCJMY30eNC8ftYfOWmEXhSd41kVy5RDrZOK9dlQnqg=" crossorigin="anonymous"/>

<!-- plyr -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/plyr/2.0.18/plyr.js"
        integrity="sha256-d3zbxocNfS7I0jy36Z8prRbhIPcXZabOIJFDpnO2RNs=" crossorigin="anonymous"></script>

<!-- momentjs -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.3/moment.min.js"
        integrity="sha256-/As5lS2upX/fOCO/h/5wzruGngVW3xPs3N8LN4FkA5Q=" crossorigin="anonymous"></script>

<!-- imageviewer -->
<link rel="stylesheet"
      href="<?php echo GETSTORIFY_PLUGIN_DIR_URL; ?>/app/plugin/imageviewer/imageviewer.css">

<!-- imageviewer -->
<script src="<?php echo GETSTORIFY_PLUGIN_DIR_URL; ?>/app/plugin/imageviewer/imageviewer.min.js"></script>


<!-- twitter -->
<script>window.twttr = (function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0],
            t = window.twttr || {};
        if (d.getElementById(id)) return t;
        js = d.createElement(s);
        js.id = id;
        js.src = "https://platform.twitter.com/widgets.js";
        fjs.parentNode.insertBefore(js, fjs);

        t._e = [];
        t.ready = function (f) {
            t._e.push(f);
        };

        return t;
    }(document, "script", "twitter-wjs"));</script>

<!-- instagram -->
<script async defer src="//platform.instagram.com/en_US/embeds.js"></script>

<!-- setup plyr -->
<script>
    // set cover video
    jQuery(".getstorify.story-covercontent.video").each(function () {
        var id = jQuery(this).attr('id');
        plyr.setup("#" + id);
    });

    // set post video/audio
    jQuery(".getstorify.plyr-setup").each(function () {
        var id = jQuery(this).attr('id');
        plyr.setup("#" + id);
    });

    // story date
    jQuery(".getstorify.story-created-date").each(function () {
        var created = jQuery(this).data('created');
        var formattedDate = moment(moment.utc(created).toDate()).format("ddd, MMM Do YYYY, h:mm:ss a");
        jQuery(this).html(formattedDate);
    });

    /**
     * view story cover image
     */
    jQuery("body").on("click", ".getstorify.story-covercontent.image", function (e) {
        e.preventDefault();

        var viewer = ImageViewer();
        jQuery(this).click(function () {
            var imgSrc = this.src,
                highResolutionImage = jQuery(this).data('covercontent');

            // viewer.show(highResolutionImage);
            viewer.show(highResolutionImage + (new Date()).getTime());

        });
    });

    /**
     * view post image
     */
    jQuery("body").on("click", ".getstorify.post-image", function (e) {
        e.preventDefault();

        var viewer = ImageViewer();
        jQuery(this).click(function () {
            var imgSrc = this.src,
                highResolutionImage = jQuery(this).data('postcontent');

            // viewer.show(highResolutionImage);
            viewer.show(highResolutionImage + (new Date()).getTime());

        });
    });
</script>
<!-- setup plyr ends here -->


<!-- slick -->
<script type="text/javascript"
        src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

<script type="text/javascript">
    window.onload = function () {

        jQuery(".getstorify-slick-container").slick({
            dots: true,
            centerMode: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 2000,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1,
                        infinite: true,
                        dots: true
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }
            ]
        });

    };
</script>
<!-- slick ends here -->