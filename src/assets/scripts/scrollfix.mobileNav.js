// JavaScript Document

// Scripts written by YOURNAME @ YOURCOMPANY

// fix scrolling in mobileNavWrapper on iOS
if (navigator.userAgent.match(/(iPad|iPhone|iPod)/g)) {
    new ScrollFix(document.getElementById("mobile-nav-wrapper"));
}
