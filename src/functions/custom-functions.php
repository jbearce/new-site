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

    $post_id     = $id ? $id : $post->ID;
    $post_object = get_post($post_id);
    $excerpt     = $post_object->post_excerpt ? $post_object->post_excerpt : wp_trim_words(strip_shortcodes($post_object->post_content), $length, "");

    if ($excerpt) {
        $excerpt .= $more;
    }

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

// get a map url
function get_map_url($address, $embed = false) {
    $address_url = "";

    if ($address) {
        $apple_url = "http://maps.apple.com/?q=";
        $google_url = "https://maps.google.com/?q=";

        $address_url = preg_match("/iPod|iPhone|iPad/", $_SERVER["HTTP_USER_AGENT"]) && $embed !== true ? $apple_url . urlencode($address) : $google_url . urlencode($address);

        if ($embed === true) $address_url .= "&output=embed";
    }

    return $address_url;
}

// echo the map url;
function the_map_url($address) {
    echo get_map_url($address);
}

// compare two dates to make sure they're sequential
function are_dates_sequential($date_start, $date_end = null) {
    $date_start = date("Ymd", strtotime($date_start));
    $date_end   = $date_end ? date("Ymd", strtotime($date_end)) : false;

    if ($date_start && $date_end && $date_start > $date_end) {
        return false;
    } else {
        return true;
    }
}

// function to remove extra tags (see https://stackoverflow.com/a/6406139/654480)
function remove_extra_tags($DOM) {
    $XPath = new DOMXPath($DOM);

    $body_contents = $XPath->query("//body/node()");

    $html = "";

    if ($body_contents) {
        foreach ($body_contents as $element) {
            $html .= $DOM->saveHTML($element);
        }
    }

    return $html;
}

// function to get a "no posts found" message
function get_no_posts_message($queried_object) {
    if (is_post_type_archive() && isset($queried_object->labels->name)) {
        $post_type_label = strtolower($queried_object->labels->name);
    } elseif (is_archive() && isset($queried_object->taxonomy)) {
        global $wp_taxonomies;

        $post_types = isset($wp_taxonomies[$queried_object->taxonomy]) ? $wp_taxonomies[$queried_object->taxonomy]->object_type : "";

        if (isset($post_types[0])) {
            $post_type = get_post_type_object($post_types[0]);

            if (isset($post_type->label)) {
                $post_type_label = strtolower($post_type->label);
            }
        }

        if (!isset($post_taxonomy_label)) {
            $taxonomy_labels = get_taxonomy_labels(get_taxonomy($queried_object->taxonomy));
            if (isset($taxonomy_labels->singular_name)) {
                $post_taxonomy_label = strtolower($taxonomy_labels->singular_name);
            }
        }
    }

    if (!isset($post_type_label)) {
        $post_type_label = __("posts", "__gulp_init__namespace");
    }

    if (!isset($post_taxonomy_label)) {
        $post_taxonomy_label = __("taxonomy", "__gulp_init__namespace");
    }

    if (is_post_type_archive()) {
        $error_message = sprintf(__("Sorry, no %s could be found.", "__gulp_init__namespace"), $post_type_label);
    } elseif (is_archive()) {
        $error_message = sprintf(__("Sorry, no %s could be found in this %s.", "__gulp_init__namespace"), $post_type_label, $post_taxonomy_label);
    } elseif (is_search()) {
        $error_message = sprintf(__("Sorry, no %s could be found for the search phrase %s%s.%s", "__gulp_init__namespace"), $post_type_label, "&ldquo;", get_search_query(), "&rdquo;");
    } else {
        $error_message = sprintf(__("Sorry, no %s could be found matching this criteria.", "__gulp_init__namespace"), $post_type_label);
    }

    return $error_message;
}

// function to retrieve a bunch of article metadata
function get_article_meta($post_id, $taxonomies = array(), $meta = array()) {
    // set up some default taxonomies
    if (empty($taxonomies)) {
        $taxonomies = array(
            array(
                "icon" => "fa-folder",
                "name" => "category"
            ),
            array(
                "icon" => "fa-tag",
                "name" => "post_tag"
            ),
        );
    }

    // grab the date
    if (!isset($meta["date"])) {
        $meta["date"] = array(
            "icon"     => "fa-clock",
            "links"    => array(
                array(
                    "url"    => get_permalink($post_id),
                    "title"  => get_the_time(get_option("date_format"), $post_id),
                    "target" => "",
                )
            ),
            "datetime" => get_the_time("c", $post_id),
        );
    }

    // grab the author
    if (!isset($meta["author"])) {
        $author_id = get_post_field("post_author", $post_id);

        $meta["author"] = array(
            "icon"   => "fa-user-circle",
            "links" => array(
                array(
                    "url"    => get_author_posts_url($author_id),
                    "title"  => get_the_author_meta("display_name", $author_id),
                    "target" => "",
                )
            ),
            "avatar" => get_avatar_url($author_id, array("size" => 150)),
        );
    }

    // grab the comments count
    if (!isset($meta["comments"])) {
        $comment_count = get_comments_number($post_id);

        $meta["comments"] = array(
            "icon"  => "fa-comment",
            "links" => array(
                array(
                    "url"    => get_comments_link($post_id),
                    "title"  => sprintf(__("%s comment%s", "__gulp_init__namespace"), $comment_count, ((int) $comment_count !== 1 ? __("s", "__gulp_init__namespace") : "")),
                    "target" => "",
                )
            ),
            "count" => $comment_count,
        );
    }

    // grab the taxonomy terms
    if ($taxonomies) {
        foreach ($taxonomies as $tax) {
            if (!isset($meta["tax_{$tax["name"]}"])) {

                $terms = get_the_terms($post_id, $tax["name"]);

                if ($terms) {
                    $meta["tax_{$tax["name"]}"] = array(
                        "icon"  => $tax["icon"],
                        "links" => array(),
                        "terms" => $terms,
                    );

                    foreach ($terms as $term) {
                        $meta["tax_{$tax["name"]}"]["links"][] = array(
                            "url"    => get_term_link($term->term_id, $term->taxonomy),
                            "title"  => sanitize_text_field($term->name),
                            "target" => "",
                        );
                    }
                }
            }
        }
    }

    return $meta;
}

// function to construct an image to make srcsets and lazy loading simpler
function __gulp_init__namespace_img($src, $atts = array(), $lazy = true, $tag = "img") {
    $element = "<{$tag}";

    // build an srcset
    if (gettype($src) === "array") { $i = 0;
        $element .= " src='{$src["1x"]}' srcset='";

        foreach ($src as $dpi => $source) { $i++;
            $element .= "{$source} {$dpi}" . ($i < count($src) ? ", " : "");
        }

        $element .= "'";
    } else {
        $source_att = $tag === "source" ? "srcset" : "src";

        $element .= " {$source_att}='{$src}'";
    }

    // add attributes
    if (!empty($atts)) {
        foreach ($atts as $att => $value) {
            $element .= " {$att}='{$value}'";
        }
    }

    $element .= " />";

    // lazy load the image
    if ($lazy && $tag === "img") {
        $element = apply_filters("__gulp_init__namespace_lazy_load_images", $element);
    }

    return $element;
}

// make calls to hm_get_templtae_part easier
function __gulp_init__namespace_get_template_part($file, $template_args = array(), $cache_args = array()) {
    hm_get_template_part(get_theme_file_path($file), $template_args, $cache_args);
}
