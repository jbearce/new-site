// JavaScript Document

// Scripts written by Jacob Bearce | jacob@bearce.me

// fix scrolling in mobileNavWrapper on iOS
if (navigator.userAgent.match(/(iPad|iPhone|iPod)/g)) {
    new ScrollFix(document.getElementById("mobile-nav-wrapper"));
}
