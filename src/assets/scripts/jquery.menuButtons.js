// JavaScript Document

// Scripts written by YOURNAME @ YOURCOMPANY

// mobile menu button
jQuery(".menu-button").click(function (e) {
    e.preventDefault();

    // mark the mobile nav as open
    jQuery("html").toggleClass("-navactive");
});

// page-wrapper (when nav is active)
jQuery(".page-container").click(function(e) {
    // skip clicks on children
    if (e.target != this) return;

    // remove the class
    jQuery("html").removeClass("-navactive");
});

// mobile drop down buttons
jQuery(".menu-list_toggle").click(function (e) {
    e.preventDefault();

    // mark thsi button as active
    jQuery(this).toggleClass("-active");

    // mark the next drop down as active
    jQuery(this).next(".menu-list.-accordion").toggleClass("-active");
});
