<?php
/* ------------------------------------------------------------------------ *\
 * Functions: Shortcodes
\* ------------------------------------------------------------------------ */

// fix shortcode formatting
function new_site_fix_shortcodes($content) {
	$array = array (
		"<p>["         => "[",
		"]</p>"        => "]",
		"]<br />"      => "]",
        "<p>&#91;"     => "[",
        "&#93;</p>"    => "]",
        "&#93;<br />"  => "]",
	);
	$content = strtr($content, $array);

    return $content;
}
add_filter("the_content", "new_site_fix_shortcodes");
add_filter("acf_the_content", "new_site_fix_shortcodes", 12);

// add row shortcode
function new_site_row_shortcode($atts, $content = null) {
    // return the tab wrapper with the menu
    return "<div class='user-content_row row -padded'>" . do_shortcode(new_site_fix_shortcodes($content)) . "</div>";
}
add_shortcode("row", "new_site_row_shortcode");

// add col shortcode
function new_site_col_shortcode($atts , $content = null) {
    extract(shortcode_atts(
		array(
			"default_width"  => "",
			"tablet_width"   => "",
            "notebook_width" => "",
			"desktop_width"  => "",
            "variant"        => "",
		), $atts)
	);

    $class =  $default_width  ? "-{$default_width}" :        "";
    $class .= $tablet_width   ? " col-xs-{$tablet_width}" :  "";
    $class .= $notebook_width ? " col-l-{$notebook_width}" : "";
    $class .= $desktop_width  ? " col-xl-{$desktop_width}" : "";
    $class .= $variant ? " $variant" : "";

    // return the tab wrapper with the menu
    return "<div class='col{$class}'>" . do_shortcode(new_site_fix_shortcodes($content)) . "</div>";
}
add_shortcode("col", "new_site_col_shortcode");
