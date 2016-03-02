// JavaScript Document

// Scripts written by Jacob Bearce @ Weblinx, Inc.

// responsive iframes
$("iframe").not(".iframe > iframe").each(function () {
    var src = $(this).attr("src");
    if ((src.indexOf("youtube") > -1) || (src.indexOf("vimeo") > -1) || (src.indexOf("dailymotion") > -1)) {
        var height = $(this).attr("height"),
            width = $(this).attr("width");
        if (parseInt(height) != height || height <= 0) {
            height = 9;
        }
        if (parseInt(width) != width || width <= 0) {
            width = 16;
        }
        var aspectRatio = (height / width) * 100 + "%";
        $(this).wrap("<span class='iframe' style='padding-bottom:" + aspectRatio + "'>");
    }
});
