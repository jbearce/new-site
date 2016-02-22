// JavaScript Document

// Scripts written by Jacob Bearce | jacob@bearce.me

$("select").not("select[multiple], .select-box > select").each(function () {
	if ($(this).css("display") !== "none") {
		$(this).wrap("<div class='select-box'>");
	}
});
