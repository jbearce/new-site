// JavaScript Document

// Scripts written by Jacob Bearce | jacob@bearce.me

// fixes media querries in Internet Explorer
if (navigator.userAgent.match(/IEMobile/)) {
	var msViewportStyle = document.createElement("style");
	msViewportStyle.appendChild(document.createTextNode("@-ms-viewport{width:auto!important;}"));
	document.getElementsByTagName("head")[0].appendChild(msViewportStyle);
}

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

// possibly find this when the ul gets a max height of 0.
var mobileWidth = 639;

// fixes drop downs in Android & iOS
if (((navigator.userAgent.toLowerCase().indexOf("android") > -1) || (navigator.userAgent.match(/(iPad)/g))) && $(window).width() > mobileWidth) {
	$(document).ready(function () {
        "use strict";
		$("#navWrapper ul li ul").parent("li").children("a").each(function () {
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
		});
	});
}

// fixes drop downs in Windows (Internet Explorer)
function ariaHaspopupEnabler() {
    "use strict";
	if (!navigator.userAgent.match(/IEMobile/)) {
		$("header nav ul li ul, header nav ul li ul li ul").each(function () {
			$(this).parent("li").children("a").attr("aria-haspopup", "true");
		});
	}
}
function ariaHaspopupDisabler() {
    "use strict";
    $("#navWrapper ul li ul").each(function () {
        $(this).parent("li").children("a").attr("aria-haspopup", "false");
    });
}
$(document).ready(function () {
    "use strict";
    if (navigator.userAgent.match(/IEMobile/) || $(window).width() < mobileWidth) {
        ariaHaspopupDisabler();
    }
});
$(window).resize(function () {
    "use strict";
	if ($(window).width() > mobileWidth) {
		ariaHaspopupEnabler();
	} else {
		ariaHaspopupDisabler();
	}
});

// mobile menu button pushes
$("button.menu").click(function (e) {
    "use strict";
    e.preventDefault();
    $("html").toggleClass("navOpen");
    $("body").scrollTop(0);
});
$("#mobileNavWrapper li.menu-item-has-children button").click(function (e) {
    "use strict";
    e.preventDefault();
    $(this).parent().toggleClass("open");
});
