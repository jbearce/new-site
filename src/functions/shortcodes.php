<?php
/* ------------------------------------------------------------------------ *\
 * Functions: Shortcodes
\* ------------------------------------------------------------------------ */

// add row shortcode
function __gulp_init_namespace___row_shortcode($atts, $content = null) {
    extract(shortcode_atts(
        array(
            "class" => false,
        ),
        $atts
    ));

    $value = $class !== false ? " {$class}" : " row--padded";

    return "<div class='user-content__row row{$value}'>" . do_shortcode($content) . "</div>";
}
add_shortcode("row", "__gulp_init_namespace___row_shortcode");

// add col shortcode
function __gulp_init_namespace___col_shortcode($atts , $content = null) {
    extract(shortcode_atts(
        array(
            "mobile"   => "",
            "tablet"   => "",
            "notebook" => "",
            "desktop"  => "",
            "class"    => "",
        ), $atts
    ));

    $value =  $mobile   ? "-{$mobile}"         : "-auto";
    $value .= $tablet   ? " col-xs-{$tablet}"  : "";
    $value .= $notebook ? " col-l-{$notebook}" : "";
    $value .= $desktop  ? " col-xl-{$desktop}" : "";
    $value .= $class    ? " $class"            : "";

    return "<div class='col{$value}'>" . do_shortcode($content) . "</div>";
}
add_shortcode("col", "__gulp_init_namespace___col_shortcode");
