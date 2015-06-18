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

// prevent scrolling in table from opening mobile nav
$(document).ready(function()  {
   "use strict";
    $("table").each(function () {
        "use strict";
        $(this).attr("data-snap-ignore", "true");
    });
});

// possibly find this when the ul gets a max height of 0.
var mobileWidth = 640;

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
		$("#navWrapper ul li ul").each(function () {
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

// mobile menu button
$("button.menu").click(function (e) {
    "use strict";
    e.preventDefault();
    if (!$("body").hasClass("snapjs-right")) {
        $("body").scrollTop(0);
        snapper.open("right");
    }
});

// mobile drop down buttons
$("#mobileNavWrapper li.menu-item-has-children button").click(function (e) {
    "use strict";
    e.preventDefault();
    $(this).parent().toggleClass("open");
});

// fix scrolling in mobileNavWrapper on iOS
if (navigator.userAgent.match(/(iPad|iPhone|iPod)/g)) {
    new ScrollFix(document.getElementById("mobileNavWrapper"))
};

// enable snapper
var snapper = new Snap({
    element: document.getElementById("pageWrapper"),
    disable: "left",
    hyperextensible: false,
    minPosition: -240,
    transitionSpeed: 0.15,
});
var snaperDisabled = false;
$(window).on("load resize", function () {
    if ($(window).width() < mobileWidth && snaperDisabled === true) {
        snapper.enable();
        snaperDisabled = false;
    } else if ($(window).width() >= mobileWidth && snaperDisabled === false) {
        snapper.close();
        snapper.disable();
        snaperDisabled = true;
    };
});
