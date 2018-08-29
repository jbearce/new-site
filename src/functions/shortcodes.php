<?php
/* ------------------------------------------------------------------------ *\
 * Functions: Shortcodes
\* ------------------------------------------------------------------------ */

// add row shortcode
function __gulp_init__namespace_row_shortcode($atts, $content = null) {
    // return the tab wrapper with the menu
    return "<div class='user-content_row row -padded'>" . do_shortcode($content) . "</div>";
}
add_shortcode("row", "__gulp_init__namespace_row_shortcode");

// add col shortcode
function __gulp_init__namespace_col_shortcode($atts , $content = null) {
    extract(shortcode_atts(
        array(
            "mobile"   => "",
            "tablet"   => "",
            "notebook" => "",
            "desktop"  => "",
            "variant"  => "",
        ), $atts)
    );

    $class =  $mobile   ? "-{$mobile}"         : "-auto";
    $class .= $tablet   ? " col-xs-{$tablet}"  : "";
    $class .= $notebook ? " col-l-{$notebook}" : "";
    $class .= $desktop  ? " col-xl-{$desktop}" : "";
    $class .= $variant  ? " $variant"          : "";

    // return the tab wrapper with the menu
    return "<div class='col{$class}'>" . do_shortcode($content) . "</div>";
}
add_shortcode("col", "__gulp_init__namespace_col_shortcode");
