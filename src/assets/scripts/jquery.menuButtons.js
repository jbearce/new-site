// JavaScript Document

// Scripts written by Jacob Bearce | jacob@bearce.me

// mobile menu button
$(".menu-button").click(function (e) {
    "use strict";
    e.preventDefault();
    $("html").toggleClass("is-navopen");
});

// mobile drop down buttons
$(".menu-list .menu-item-has-children .menu-toggle").click(function (e) {
    "use strict";
    e.preventDefault();
    $(this).parent().toggleClass("is-open");
});
