// JavaScript Document

// Scripts written by YOURNAME @ YOURCOMPANY

jQuery("select").not("select[multiple], .select-box > select").each(function () {
	if (jQuery(this).css("display") !== "none") {
		jQuery(this).wrap("<div class='select-box'>");
	}
});
