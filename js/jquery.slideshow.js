// JavaScript Document

/* Scripts written by Jacob Bearce | jacob@weblinxinc.com | jacob@bearce.me */

var slideTime = 5000;
var nextSlide = 1;
var numberOfSlides = 0;
$(document).ready(function() {
	$("#slideshow #slideshowWindow figure").each(function() {
		numberOfSlides++;
		$(this).attr("data-slide",numberOfSlides);
	});
	$("#slideshow #slideshowWindow figure").first().addClass("active");
});
function autoRotate() {
	if (nextSlide != numberOfSlides) {
		nextSlide = nextSlide + 1
	} else {
		nextSlide = 1;
	}
	$("#slideshow #slideshowWindow figure.active").removeClass("active");
	$("#slideshow #slideshowWindow figure[data-slide=" + nextSlide + "]").addClass("active");
}
var slideLoop;
slideLoop = setInterval(function() {
	autoRotate();
}, slideTime);
$("#slideshowWindow").hover(function() {
	clearInterval(slideLoop);
}, function() {
	slideLoop = setInterval(function() {
		autoRotate()
	}, slideTime);
});
$("#slideshow > button").click(function() {
	var direction = $(this).attr("data-direction");
	if (direction == "next") {
		nextSlide = parseInt($("#slideshow #slideshowWindow figure.active").attr("data-slide")) + 1;
		if (nextSlide == numberOfSlides + 1) {
			nextSlide = 1;
		}
	} else {
		nextSlide = parseInt($("#slideshow #slideshowWindow figure.active").attr("data-slide")) - 1;
		if (nextSlide == 0) {
			nextSlide = numberOfSlides;
		}
	}
	$("#slideshow #slideshowWindow figure.active").removeClass("active");
	$("#slideshow #slideshowWindow figure[data-slide=" + nextSlide + "]").addClass("active");
	clearInterval(slideLoop);
	slideLoop = setInterval(function() {
		autoRotate()
	}, slideTime);
});