// JavaScript Document

// Scripts written by Jacob Bearce | jacob@bearce.me

// mobile menu button
$(".menu-button").click(function (e) {
    "use strict";
    e.preventDefault();
    $("html").toggleClass("-navactive");
});

// mobile drop down buttons
$(".menu-toggle").click(function (e) {
    "use strict";
    e.preventDefault();
    $(this).closest(".menu-list.-submenu").toggleClass("-active");
});
