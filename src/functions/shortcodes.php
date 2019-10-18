<?php
/* ------------------------------------------------------------------------ *\
 * Functions: Shortcodes
\* ------------------------------------------------------------------------ */

/**
 * Shortcode for users to add rows to the content region
 *
 * @example [row class="row--padded row--tight"]
 *
 * @param  array<string> $atts
 * @param  string $content
 *
 * @return string
 */
function __gulp_init_namespace___row_shortcode(array $atts, string $content = ""): string {
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

/**
 * Shortcode for usrs to add columns to the content region
 *
 * @example [col mobile=12 tablet=6 notebook=4 desktop=auto class="col--nogrow col--noshrink"]
 *
 * @param  array<string> $atts
 * @param  string $content
 *
 * @return string
 */
function __gulp_init_namespace___col_shortcode(array $atts , string $content = ""): string {
    extract(shortcode_atts(
        array(
            "mobile"   => "",
            "tablet"   => "",
            "notebook" => "",
            "desktop"  => "",
            "class"    => "",
        ), $atts
    ));

    $value =  $mobile   ? "col-{$mobile}"         : "col-auto";
    $value .= $tablet   ? " col-xs-{$tablet}"  : "";
    $value .= $notebook ? " col-l-{$notebook}" : "";
    $value .= $desktop  ? " col-xl-{$desktop}" : "";
    $value .= $class    ? " $class"            : "";

    return "<div class='{$value}'>" . do_shortcode($content) . "</div>";
}
add_shortcode("col", "__gulp_init_namespace___col_shortcode");

/**
 * Shortcode for users to set a number of CSS columns on tablet+ views
 *
 * @example [columns count=2]
 *
 * @param  array<string> $atts
 * @param  string $content
 *
 * @return string
 */
function __gulp_init_namespace___columns_shortcode(array $atts , string $content = ""): string {
    extract(shortcode_atts(
        array(
            "count" => "1",
        ), $atts
    ));

    return "<div class='user-content__columns columns columns--{$count}'>" . do_shortcode($content) . "</div>";
}
add_shortcode("columns", "__gulp_init_namespace___columns_shortcode");
