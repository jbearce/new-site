<?php
/* ------------------------------------------------------------------------ *\
 * Functions: Shortcodes
\* ------------------------------------------------------------------------ */

// add row shortcode
function __gulp_init_namespace___row_shortcode($atts, $content = null) {
    // return the tab wrapper with the menu
    return "<div class='user-content_row row -padded'>" . do_shortcode($content) . "</div>";
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
add_shortcode("col", "__gulp_init_namespace___col_shortcode");

// override caption shortcode
function __gulp_init_namespace___caption_shortcode($atts, $content) {
    $output = "";

    extract(shortcode_atts(
        array(
            "align" => "",
            "id"    => "",
            "width" => "",
        ), $atts)
    );

    if ($content) {
        $image_id      = $id ? " id='{$id}'" : "";
        $image_class   = $align ? " {$align}" : "";
        $image_width   = $width ? " style='width: {$width}px'" : "";
        $image_caption = false;

        // isolate top level parent of images
        $DOM = new DOMDocument();
        $DOM->loadHTML(mb_convert_encoding("<html><body>{$content}</body></html>", "HTML-ENTITIES", "UTF-8"), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $images = $DOM->getElementsByTagName("img");

        if ($images) {
            $caption_elements = array();

            // find the first caption element by cycling up through the DOM from an image
            foreach ($images as $image) {
                if ($image->parentNode->tagName !== "noscript") { $i = 0;
                    $parent = $image;

                    while (!$image_caption) { $i++;
                        // if a sibling exists, it's a caption!
                        if ($parent->nextSibling) {
                            $caption_elements[] = $parent->nextSibling; break;
                        }

                        $parent = $parent->parentNode;

                        // prevent infinite loops
                        if ($i >= 1000) break;
                    }
                }
            }

            // find remaining caption elements by checking for next siblings recursively
            foreach ($caption_elements as $element) { $i = 0;
                $next_sibling = $element;

                while ($next_sibling) { $i++;
                    $next_sibling = $next_sibling->nextSibling;

                    if ($next_sibling) {
                        $caption_elements[] = $next_sibling;
                    }

                    // prevent infinite loops
                    if ($i >= 1000) break;
                }
            }

            echo "<pre>";
            print_r($caption_elements);
            echo "</pre>";
        }

        echo "<pre>";
        print_r($image_caption);
        echo "</pre>";

        // remove unneeded tags (inserted for parsing reasons)
        $content = __gulp_init_namespace___remove_extra_tags($DOM);

        // construct the output
        $output .= "<figure{$image_id}{$image_width} class='wp-caption{$image_class}'>";
        $output .= "[wip]";
        $output .= "</figure>";
    }

    return $output;
}

function __gulp_init_namespace___caption_shortcode_override() {
    remove_shortcode("caption");
    add_shortcode("caption", "__gulp_init_namespace___caption_shortcode");
}
add_action("init", "__gulp_init_namespace___caption_shortcode_override");
