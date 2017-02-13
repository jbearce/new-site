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
function new_site_row_shortcode($content = null) {
    // return the tab wrapper with the menu
    return "<div class='row -padded{$class}'>" . do_shortcode(new_site_fix_shortcodes($content)) . "</div>";
}
add_shortcode("row", "new_site_row_shortcode");

// add col shortcode
function new_site_col_shortcode($atts , $content = null) {
    extract(shortcode_atts(
		array(
			"width" => "",
		), $atts)
	);

    $class = $width ? "-{$width}" : "";

    // return the tab wrapper with the menu
    return "<div class='col{$class} -shortcode'>" . do_shortcode(new_site_fix_shortcodes($content)) . "</div>";
}
add_shortcode("col", "new_site_col_shortcode");
