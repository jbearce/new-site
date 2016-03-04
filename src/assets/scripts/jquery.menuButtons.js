// JavaScript Document

// Scripts written by Jacob Bearce @ Weblinx, Inc.

// mobile menu button
$(".menu-button").click(function (e) {
    e.preventDefault();

    // mark the mobile nav as open
    $("html").toggleClass("-navactive");
});

// mobile drop down buttons
$(".menu-toggle").click(function (e) {
    e.preventDefault();

    // mark thsi button as active
    $(this).toggleClass("-active");

    // mark the next drop down as active
    $(this).next(".menu-list.-submenu").toggleClass("-active");
});
