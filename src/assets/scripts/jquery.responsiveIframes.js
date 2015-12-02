// JavaScript Document

// Scripts written by Jacob Bearce | jacob@bearce.me

// responsive iframes
$(document).ready(function () {
    "use strict";
	$("iframe").not(".iframe iframe").each(function () {
        var src = $(this).attr("src");
        if ((src.indexOf("youtube") > -1) || (src.indexOf("vimeo") > -1) || (src.indexOf("dailymotion") > -1)) {
            var height = $(this).attr("height"),
                width = $(this).attr("width"),
                aspectRatio = (height / width) * 100 + "%";
            $(this).wrap("<span class='iframe' style='padding-bottom:" + aspectRatio + "'>");
        }
	});
});
