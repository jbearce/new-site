// JavaScript Document

/* Scripts written by Jacob Bearce | jacob@weblinxinc.com | jacob@bearce.me */

// fixes media querries in Internet Explorer
if (navigator.userAgent.match(/IEMobile/)) {
	var msViewportStyle = document.createElement("style")
	msViewportStyle.appendChild(
		document.createTextNode(
			"@-ms-viewport{width:auto!important}"
		)
	)
	document.getElementsByTagName("head")[0].appendChild(msViewportStyle)
}

// responsive iframes
$(document).ready(function() {
	$("iframe").each(function() {
		var height = $(this).attr("height");
		var width = $(this).attr("width");
		var aspectRatio = (height / width) * 100 + "%";
		$(this).wrap("<span class=\"iframe\" style=\"padding-bottom:" + aspectRatio + "\">");
	});
});

// possibly find this when the ul gets a max height of 0.
var mobileWidth = 639;

// fixes drop downs in Android & iOS
if (((navigator.userAgent.toLowerCase().indexOf("android") > -1) || (navigator.userAgent.match(/(iPad)/g))) && $(window).width() > mobileWidth) {
	$(document).ready(function() {
		$("header nav ul li ul, header nav ul li ul li ul").parent("li").children("a").each(function() {
			var touched = false;
			$(this).click(function(e) {
				if (touched == true) {
				} else {
					e.preventDefault();
					touched = true;
				};
			});
			$(this).mouseleave(function() {
				touched = false;
			});
		});
	});
};

// fixes drop downs in Windows (Internet Explorer)
function ariaHaspopupEnabler() {
	if (!navigator.userAgent.match(/IEMobile/)) {
		$("header nav ul li ul, header nav ul li ul li ul").each(function() {
			$(this).parent("li").children("a").attr("aria-haspopup","true");
		});
	};
}
function ariaHaspopupDisabler() {
    $("header nav ul li ul, header nav ul li ul li ul").each(function() {
        $(this).parent("li").children("a").attr("aria-haspopup","false");
    });
}
$(document).ready(function() {
    if (navigator.userAgent.match(/IEMobile/) || $(window).width() < mobileWidth) {
        ariaHaspopupDisabler();
    }
});
$(window).resize(function() {
	if ($(window).width() > mobileWidth) {
		ariaHaspopupEnabler();
	} else {
		ariaHaspopupDisabler();
	}
});

// mobile menu button pushes
$("#headerWrapper header nav > button").click(function(e) {
    e.preventDefault();
    $("html").toggleClass("navopen");
});
$("#headerWrapper header nav ul li button").click(function(e) {
    e.preventDefault();
    $(this).parent().toggleClass("open");
});
