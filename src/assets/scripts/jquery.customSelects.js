// JavaScript Document

// Scripts written by Jacob Bearce @ Weblinx, Inc.

$("select").not("select[multiple], .select-box > select").each(function () {
	if ($(this).css("display") !== "none") {
		$(this).wrap("<div class='select-box'>");
	}
});
