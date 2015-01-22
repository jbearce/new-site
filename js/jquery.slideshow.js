// JavaScript Document

/* Scripts written by Jacob Bearce | jacob@weblinxinc.com | jacob@bearce.me */

var slideTime = 5000;
var nextSlide = 1;
var numberOfSlides = 0;
$(document).ready(function() {
	$("#slideshow figure").each(function() {
		numberOfSlides++;
		$(this).attr("data-slide",numberOfSlides);
	});
	$("#slideshow figure").first().addClass("active");
});
function autoRotate() {
	if (nextSlide != numberOfSlides) {
		nextSlide = nextSlide + 1
	} else {
		nextSlide = 1;
	}
	$("#slideshow figure.active").removeClass("active");
	$("#slideshow figure[data-slide=" + nextSlide + "]").addClass("active");
}
var slideLoop;
slideLoop = setInterval(function() {
	autoRotate();
}, slideTime);
$("#slideshow").hover(function() {
	clearInterval(slideLoop);
}, function() {
	slideLoop = setInterval(function() {
		autoRotate()
	}, slideTime);
});
$("#slideshow > button").click(function() {
	var direction = $(this).attr("data-direction");
	if (direction == "next") {
		nextSlide = parseInt($("#slideshow figure.active").attr("data-slide")) + 1;
		if (nextSlide == numberOfSlides + 1) {
			nextSlide = 1;
		}
	} else {
		nextSlide = parseInt($("#slideshow figure.active").attr("data-slide")) - 1;
		if (nextSlide == 0) {
			nextSlide = numberOfSlides;
		}
	}
	$("#slideshow figure.active").removeClass("active");
	$("#slideshow figure[data-slide=" + nextSlide + "]").addClass("active");
	clearInterval(slideLoop);
	slideLoop = setInterval(function() {
		autoRotate()
	}, slideTime);
});