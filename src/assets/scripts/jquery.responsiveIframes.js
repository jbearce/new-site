// JavaScript Document

// Scripts written by YOURNAME @ YOURCOMPANY

// responsive iframes
jQuery("iframe").not(".iframe > iframe").each(function () {
    var src = jQuery(this).attr("src");
    if ((src.indexOf("youtube") > -1) || (src.indexOf("vimeo") > -1) || (src.indexOf("dailymotion") > -1)) {
        var height = jQuery(this).attr("height"),
            width = jQuery(this).attr("width");
        if (parseInt(height) != height || height <= 0) {
            height = 9;
        }
        if (parseInt(width) != width || width <= 0) {
            width = 16;
        }
        var aspectRatio = (height / width) * 100 + "%";
        jQuery(this).wrap("<span class='iframe' style='padding-bottom:" + aspectRatio + "'>");
    }
});
