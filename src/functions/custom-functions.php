<?php
/* ------------------------------------------------------------------------ *\
 * Custom Functions
\* ------------------------------------------------------------------------ */

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
function get_map_link($address) {
    $address_url = "";

    if ($address) {
        $address_base_url = preg_match("/iPod|iPhone|iPad/", $_SERVER["HTTP_USER_AGENT"]) ? "http://maps.apple.com/?q=" : "https://www.google.com/maps?q=";
        $address_url = $address_base_url . urlencode($address);
    }

    return $address_url;
}

// echo the map link;
function the_map_link($address) {
    echo get_map_link($address);
}
