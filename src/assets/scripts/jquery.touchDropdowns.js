// JavaScript Document

// Scripts written by Jacob Bearce | jacob@bearce.me

// @TODO: possibly find this when the menu-wrapper gets display:none;
var mobileWidth = 640;

// fixes drop downs in Android & iOS
if (((navigator.userAgent.toLowerCase().indexOf("android") > -1) || (navigator.userAgent.match(/(iPad)/g))) && $(".nav").is(":visible")) {
	$(document).ready(function () {
        "use strict";
		$(".nav li.menu-item-has-children a").each(function () {
            if ($(this).parent("li.menu-item-has-children").children(".menu-list.sub-menu").length > 0) {
                var touched = false;
                $(this).click(function (e) {
                    if (touched !== true) {
                        e.preventDefault();
                        touched = true;
                    }
                });
                $(this).mouseleave(function () {
                    touched = false;
                });
            }
		});
	});
}

// fixes drop downs in Windows (Internet Explorer)
function ariaHaspopupEnabler() {
    "use strict";
	if (!navigator.userAgent.match(/IEMobile/)) {
		$(".nav li.menu-item-has-children a").each(function () {
            if ($(this).parent("li.menu-item-has-children").children(".menu-list.sub-menu").length > 0) {
                $(this).attr("aria-haspopup", "true");
            }
		});
	}
}
function ariaHaspopupDisabler() {
    "use strict";
    $(".nav li.menu-item-has-children a").each(function () {
        if ($(this).parent("li.menu-item-has-children").children(".menu-list.sub-menu").length > 0) {
            $(this).attr("aria-haspopup", "false");
        }
    });
}
$(window).on("load scroll", function() {
    "use strict";
    if (navigator.userAgent.match(/IEMobile/) || $(window).width() < mobileWidth) {
        ariaHaspopupDisabler();
    } else {
        ariaHaspopupEnabler();
    }
});
