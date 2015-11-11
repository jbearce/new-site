// JavaScript Document

// Scripts written by Jacob Bearce | jacob@bearce.me

$(".gallery-thumbnail button").click(function(e) {
    // stop any possible forms from submitting
    e.preventDefault();

    // check what image is targeted
    var img = $(this).data("img");

    // remove active class from previous active thumbnail
    $(this).closest(".gallery-thumbnails").find(".active").removeClass("active");

    // add active class to clicked thumbnail
    $(this).closest(".gallery-thumbnail").addClass("active");

    // remove active class from previous active image
    $(this).closest(".gallery").find(".gallery-image.active").removeClass("active");

    // add active class to targeted image
    $(this).closest(".gallery").find(".gallery-image[data-img=" + img + "]").addClass("active");
});
