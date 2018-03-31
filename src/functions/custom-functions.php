<?php
/* ------------------------------------------------------------------------ *\
 * Custom Functions
\* ------------------------------------------------------------------------ */

// check if critical styles should be used, and return it if true
function get_critical_css($template) {
    $critical_css      = "";
    $current_template  = explode(".", basename($template))[0];
    $critical_css_path = get_theme_file_path("assets/styles/critical/{$current_template}.css");

    if (file_exists($critical_css_path) && !isset($_COOKIE["return_visitor"]) && !(isset($_GET["disable"]) && $_GET["disable"] === "critical_css")) {
        $critical_css = file_get_contents($critical_css_path);
    }

    return $critical_css;
}

// get a nicer excerpt based on post ID
function get_better_excerpt($id = 0, $length = 55, $more = " [...]") {
    global $post;

    $post_id = $id ? $id : $post->ID;
    $post_object = get_post($post_id);
    $excerpt = $post_object->post_excerpt ? $post_object->post_excerpt : wp_trim_words(strip_shortcodes($post_object->post_content), $length, $more);

    return $excerpt;
}

// format an address
function format_address($address_1, $address_2, $city, $state, $zip_code, $break_mode = 1) {
    $address = "";

    if ($address_1 || $address_2 || $city || $state || $zip_code) {
        if ($address_1) {
            $address .= $address_1;

            if ($address_2 || $city || $state || $zip_code) {
                if ($break_mode !== 1 && !($address_2 && $break_mode === 2)) {
                    $address .= "<br />";
                } else {
                    $address .= ", ";
                }
            }
        }

        if ($address_2) {
            $address .= $address_2;

            if ($city || $state || $zip_code) {
                if ($break_mode !== 1) {
                    $address .= "<br />";
                } else {
                    $address .= ", ";
                }
            }
        }

        if ($city) {
            $address .= $city;

            if ($state) {
                $address .= ", ";
            } elseif ($zip_code) {
                $address .= " ";
            }
        }

        if ($state) {
            $address .= $state;

            if ($zip_code) {
                $address .= " ";
            }
        }

        if ($zip_code) {
            $address .= $zip_code;
        }
    }

    return $address;
}

// get a map link
function get_map_link($address, $embed = false) {
    $address_url = "";

    if ($address) {
        $apple_url = "http://maps.apple.com/?q=";
        $google_url = "https://maps.google.com/?q=";

        $address_url = preg_match("/iPod|iPhone|iPad/", $_SERVER["HTTP_USER_AGENT"]) && $embed !== true ? $apple_url . urlencode($address) : $google_url . urlencode($address);

        if ($embed === true) $address_url .= "&output=embed";
    }

    return $address_url;
}

// echo the map link;
function the_map_link($address) {
    echo get_map_link($address);
}

// function to remove the root element (see https://stackoverflow.com/a/29499398)
function remove_root_tag($DOM, $tag = "html") {
    $container = $DOM->getElementsByTagName($tag)->item(0);
    $container = $container->parentNode->removeChild($container);

    while ($DOM->firstChild) {
        $DOM->removeChild($doc->firstChild);
    }

    while ($container->firstChild ) {
        $DOM->appendChild($container->firstChild);
    }

    return $DOM;
}
