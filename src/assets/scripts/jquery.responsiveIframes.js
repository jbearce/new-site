// JavaScript Document

// Scripts written by Jacob Bearce | jacob@bearce.me

// responsive iframes
$(document).ready(function () {
    "use strict";
	$("iframe").each(function () {
		var height = $(this).attr("height"),
		    width = $(this).attr("width"),
		    aspectRatio = (height / width) * 100 + "%";
		$(this).wrap("<span class='iframe' style='padding-bottom:" + aspectRatio + "'>");
	});
});
