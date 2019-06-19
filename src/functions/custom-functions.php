<?php
 /* ------------------------------------------------------------------------ *\
 * Custom Functions
 \* ------------------------------------------------------------------------ */

/**
 * Require a partial using hm_get_template_part, after first locating the path to the template
 *
 * @param string file  The path to the template file, relative to the theme root
 * @param array template_args  An array of values to pass to the template
 * @param array cache_args
 *
 * @return void
 */
function __gulp_init_namespace___get_template_part($file, $template_args = array(), $cache_args = array()) {
    hm_get_template_part(get_theme_file_path($file), $template_args, $cache_args);
}

/**
 * Get the path to the most recent version of a file given a glob (i.e. modern.*.css => modern.17ee0314.css)
 *
 * @param string path  Glob pattern for file to search for
 * @param boolean skip_child_theme  Optionally ignore child theme overrides
 * @param boolean full_path  Optionally return the full server path
 *
 * @return string
 */
function __gulp_init_namespace___get_theme_file_path($path, $skip_child_theme = false, $full_path = false) {
    $file_paths = array();

    /**
     * Both stylesheet_directory and template_directory need to be checked
     * in order to account for the possibilty of child theme overrides.
     */

    /**
     * Check the child theme first, unless instructed to skip
     */
    if ($child_results = !$skip_child_theme ? glob(get_stylesheet_directory() . "/{$path}") : false) {
        $file_paths = $child_results;
    }

    /**
     * If no matching files were found in the child theme, check the parent theme
     */
    if (!$file_paths && $parent_results = glob(get_template_directory() . "/{$path}")) {
        $file_paths = $parent_results;
    }

    if ($file_paths) {
        /**
         * Sort the matches by date to get the most recently modified copy
         */
        usort($file_paths, function ($a, $b) {
            return filemtime($a) < filemtime($b);
        });

        /**
         * Return the path to the most recently edited file, either as a full server path,
         * or relative to the theme directory
         */
        return $full_path ? $file_paths[0] : dirname($path) . "/" . basename($file_paths[0]);
    }

    return "";
}

/**
 * Retrieve critical styles for a given template
 *
 * @param string template  The file name of the template styles should be retrieved for
 *
 * @return string
 */
function __gulp_init_namespace___get_critical_css($template) {
    /**
     * Immediately stop if the user has visited previously or Critical CSS is explicity
     * disabled by the user via `$_GET["disable"]`, and the user hasn't requested to
     * debug the critical CSS.
     */
    if ((isset($_COOKIE["return_visitor"]) || (isset($_GET["disable"]) && $_GET["disable"] === "critical_css")) && !(isset($_GET["debug"]) && $_GET["debug"] === "critical_css")) {
        return "";
    }

    $critical_css = "";

    /**
     * Construct the potential path to the critical CSS file for the template, check if it
     * exists, and if it does, get its contents.
     */
    if ($critical_css_path = __gulp_init_namespace___get_theme_file_path("assets/styles/critical/",  explode(".", basename($template))[0] . ".css", true) && file_exists($critical_css_path)) {
        $critical_css = file_get_contents($critical_css_path);
    }

    return $critical_css;
}

/**
 * Check if a given URL points to an external website
 *
 * @param string url  URL to identify
 *
 * @return boolean
 */
function __gulp_init_namespace___is_external_url($url) {
    $components = parse_url($url);

    /**
     * Check if the URL is relative
     */
    if (empty($components["host"])) {
        return false;
    }

    /**
     * Check if the domain name matches the current domain name
     */
    if (strcasecmp($components["host"], $_SERVER["SERVER_NAME"]) === 0) {
        return false;
    }

    /**
     * Check if the domain name is a subdomain of the current domain name
     */
    if (strrpos(strtolower($components["host"]), ".{$_SERVER["SERVER_NAME"]}") === strlen($components["host"]) - strlen(".{$_SERVER["SERVER_NAME"]}")) {
        return false;
    }

    /**
     * Return true for all other cases
     */
    return true;
}

/**
 * Check if a given asset is outside of the active theme's directory
 *
 * @param string url  URL to check the location of
 *
 * @return boolean
 */
function __gulp_init_namespace___is_other_asset($url) {
    return strpos($url, get_template_directory_uri()) !== 0;
}

/**
 * Check what platform a given user agent belongs to
 *
 * @param string platform  A specific platform to check against (android|chrome|edge|ie|ios|safari)
 * @param string user_agent  A user agent to compare against a given platform
 *
 * @return boolean
 */
function __gulp_init_namespace___is_platform($platform, $user_agent = null) {
    $user_agent = $user_agent ? $user_agent : $_SERVER["HTTP_USER_AGENT"];

    if ($platform === "android" || (is_array($platform) && in_array("android", $platform))) {
        if (stripos($user_agent, "Android")) {
            return true;
        }
    }

    if ($platform === "chrome" || (is_array($platform) && in_array("chrome", $platform))) {
        if (stripos($user_agent, "Chrome")) {
            return true;
        }
    }

    if ($platform === "edge" || (is_array($platform) && in_array("edge", $platform))) {
        if (stripos($user_agent, "Edge")) {
            return true;
        }
    }

    if ($platform === "ie" || (is_array($platform) && in_array("ie", $platform))) {
        if (stripos($user_agent, "MSIE") || stripos($user_agent, "Trident")) {
            return true;
        }
    }

    if ($platform === "ios" || (is_array($platform) && in_array("ios", $platform))) {
        if (stripos($user_agent, "iPod") || stripos($user_agent, "iPhone") || stripos($user_agent, "iPad")) {
            return true;
        }
    }

    if ($platform === "safari" || (is_array($platform) && in_array("safari", $platform))) {
        if (stripos($user_agent, "Safari")) {
            return true;
        }
    }

    return false;
}

/**
 * Construct markup for a lazy loaded image
 *
 * @param mixed src  The URL to a given image, or an array of URLs to a set of images, keyed with the dpi `array("1x" => "image.jpg", "2x" => "image@2x.jpg)`
 * @param array atts  A set of attributes to apply to the element
 * @param boolean lazy  Whether or not to lazy load the image
 * @param string tag
 *
 * @return string  HTML tag for displaying the image
 */
function __gulp_init_namespace___img($src, $atts = array(), $lazy = "swiper", $tag = "img") {
    $element = "<{$tag}";

    /**
     * If provided URL is an array, consturct an `src` attribute pointing
     * to the `1x` resolution, and an srcset containing each possible
     * resolution.
     */
    if (gettype($src) === "array") {
        $i = 0;
        $element .= " src='{$src["1x"]}' srcset='";

        foreach ($src as $dpi => $source) {
            $i++;
            $element .= "{$source} {$dpi}" . ($i < count($src) ? ", " : "");
        }

        $element .= "'";
    /**
     * Otherwise, simply construct a single `src` or `srcset` containing
     * provided URL.
     */
    } else {
        $source_att = $tag === "source" ? "srcset" : "src";

        $element .= " {$source_att}='{$src}'";
    }

    /**
     * Append each custom attribute to the element
     */
    if (!empty($atts)) {
        foreach ($atts as $att => $value) {
            $element .= " {$att}='{$value}'";
        }
    }

    $element .= " />";

    /**
     * Run through the lazy loader filter
     */
    if ($lazy) {
        $element = apply_filters("__gulp_init_namespace___lazy_load_images", $element, $lazy);
    }

    return $element;
}

/**
 * Get a specific number of sentences in a given string
 *
 * @param string content  Block of text of which to extract sentences from
 *
 * @return string  The sentences requested
 */
function __gulp_init_namespace___get_sentences($content, $length = 2) {
    /**
     * Remove any HTML tags from the content, and count the total number of sentences
     */
    $content   = preg_replace("/\s+/", " ", strip_tags($content));
    $sentences = preg_split("/(\.|\?|\!)(\s)/", $content);

    /**
     * Return the stripped content if less than $lenght sentences exist in the content
     */
    if (count($sentences) <= $length) {
        return $content;
    }

    /**
     * Track where the $length sentence ends
     */
    $stop_at = 0;

    /**
     * Loop through sentences to find the strpos of the $length sentence
     */
    foreach ($sentences as $i => $sentence) {
        $stop_at += strlen($sentence);

        if ($i >= $length - 1)
            break;
    }

    $stop_at += ($length * 2);

    return trim(substr($content, 0, $stop_at));
}

/**
 * Get an excerpt of a specific length, and append it with a 'read more' string
 *
 * @param integer id  The post ID of which to get the excerpt of
 * @param array options  List of options describing how the excerpt should be determined
 *
 * @return string  The post excerpt
 */
function __gulp_init_namespace___get_the_excerpt($id = 0, $options = array()) {
    global $post;

    $defaults = array(
        "truncate" => array(
            "count" => 55,
            "mode"  => "words",
        ),
        "suffix"   => array(
            "value"    => " [...]",
            "optional" => false,
        ),
    );

    /**
     * Merge the defaults with the input
     */
    $options = array_replace_recursive($defaults, $options);

    /**
     * @todo
     * - combine $length and $mode in to one variable
     * - adjust $more so that it can be excluded (optionally) if the trimmed excerpt is the same as the original excerpt
     */
    $post_id     = $id ? $id : ($post ? $post->ID : false);
    $post_object = $post_id ? get_post($post_id) : false;

    /**
     * Return false if no post could be found
     */
    if (!$post_object) {
        return false;
    }

    /**
     * Get the excerpt and content, stripping them both of shortcodes
     */
    $excerpt = strip_shortcodes($post_object->post_excerpt);
    $content = strip_shortcodes($post_object->post_content);

    /**
     * If excerpt is empty, create one from the content
     */
    if (!$excerpt) {
        if ($options["truncate"]["mode"] === "words") {
            $excerpt = wp_trim_words($content, $options["truncate"]["count"], "");
        } elseif ($options["truncate"]["mode"] === "sentences") {
            $excerpt = __gulp_init_namespace___get_sentences($content, $options["truncate"]["count"]);
        }
    }

    /**
     * Append the suffix, unless it's optional and the excerpt matches the content
     */
    if ($options["suffix"]["value"] && !($options["suffix"]["optional"] && $excerpt === $content)) {
        $excerpt .= $options["suffix"]["value"];
    }

    return $excerpt;
}

/**
 * Format an address in to a human readable format
 *
 * @param array address  An array keyed with `line_1`, `line_2`, `city`, `state`, and `zip_code`
 * @param int lines  Number of lines between 1 and 3 to format the address in to
 *
 * @return string Huamn readable address
 */
function __gulp_init_namespace___format_address($address = array(), $lines = 1) {
    $output = "";

    /**
     * Immediately return empty if no address provided
     */
    if (empty($address)) {
        return $output;
    }

    /**
     * Add missing keys
     */
    if (!isset($address["line_1"])) {
        $address["line_1"] = "";
    }

    if (!isset($address["line_2"])) {
        $address["line_2"] = "";
    }

    if (!isset($address["city"])) {
        $address["city"] = "";
    }

    if (!isset($address["state"])) {
        $address["state"] = "";
    }

    if (!isset($address["zip_code"])) {
        $address["zip_code"] = "";
    }

    if ($address["line_1"]) {
        $output .= $address["line_1"];

        if ($address["line_2"] || $address["city"] || $address["state"] || $address["zip_code"]) {
            if ($lines !== 1 && !($address["line_2"] && $lines === 2)) {
                $output .= "<br />";
            } else {
                $output .= ", ";
            }
        }
    }

    if ($address["line_2"]) {
        $output .= $address["line_2"];

        if ($address["city"] || $address["state"] || $address["zip_code"]) {
            if ($lines !== 1) {
                $output .= "<br />";
            } else {
                $output .= ", ";
            }
        }
    }

    if ($address["city"]) {
        $output .= $address["city"];

        if ($address["state"]) {
            $output .= ", ";
        } elseif ($address["zip_code"]) {
            $output .= " ";
        }
    }

    if ($address["state"]) {
        $output .= $address["state"];

        if ($address["zip_code"]) {
            $output .= " ";
        }
    }

    if ($address["zip_code"]) {
        $output .= $address["zip_code"];
    }

    return $output;
}

/**
 * Given an address, return a URL to a map, appropriate for the users platform
 *
 * @param string address  A single line, human readable address.
 * @param boolean embed  Return an embeddable Google Maps URL for an iframe
 *
 * @return string  The URL to the address
 */
function __gulp_init_namespace___get_map_url($address, $embed = false) {
    $address_url = "";

    if ($address) {
        $apple_url = "http://maps.apple.com/?q=";
        $google_url = "https://maps.google.com/?q=";

        $address_url = preg_match("/iPod|iPhone|iPad/", $_SERVER["HTTP_USER_AGENT"]) && $embed !== true ? $apple_url . urlencode($address) : $google_url . urlencode($address);

        if ($embed === true) $address_url .= "&output=embed";
    }

    return $address_url;
}

/**
 * Compare two dates to see if one comes immediately after the other
 *
 * @param string date_start  The first date to compare against
 * @param string date_end  The second date to compare against
 *
 * @return boolean  `true` if dates are sequential, `false` otherwise
 */
function __gulp_init_namespace___are_dates_sequential($date_start, $date_end = null) {
    $date_start = date("Ymd", strtotime($date_start));
    $date_end   = $date_end ? date("Ymd", strtotime($date_end)) : false;

    if ($date_start && $date_end && $date_start > $date_end) {
        return false;
    } else {
        return true;
    }
}

/**
 * Remove extra tags added for use with DOMDocument
 *
 * @see https://stackoverflow.com/a/6406139/654480
 *
 * @param object DOM  DOMDocument object
 *
 * @return string  Formatted HTML
 */
function __gulp_init_namespace___remove_extra_tags($DOM) {
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

/**
 * Get a unique "No posts found" mesage for various types of pages
 *
 * @param object queried_object  The result of get_queried_object()
 *
 * @return string  A message detailing that no posts could be found for the current context
 */
function __gulp_init_namespace___get_no_posts_message($queried_object) {
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
        $post_type_label = __("posts", "__gulp_init_namespace__");
    }

    if (!isset($post_taxonomy_label)) {
        $post_taxonomy_label = __("taxonomy", "__gulp_init_namespace__");
    }

    if (is_post_type_archive()) {
        $error_message = sprintf(__("Sorry, no %s could be found.", "__gulp_init_namespace__"), $post_type_label);
    } elseif (is_archive()) {
        $error_message = sprintf(__("Sorry, no %s could be found in this %s.", "__gulp_init_namespace__"), $post_type_label, $post_taxonomy_label);
    } elseif (is_search()) {
        $error_message = sprintf(__("Sorry, no %s could be found for the search phrase %s%s.%s", "__gulp_init_namespace__"), $post_type_label, "&ldquo;", get_search_query(), "&rdquo;");
    } else {
        $error_message = sprintf(__("Sorry, no %s could be found matching this criteria.", "__gulp_init_namespace__"), $post_type_label);
    }

    return $error_message;
}

/**
 * Construct an array of metadata for display on posts
 *
 * @param int post_id  An ID for a post
 * @param array meta  An array keyed with `date`, `author`, `comments`, and `taxonomies`, used to determine which meta to return
 *
 * @return array  An array containing metadata and related icons and labels
 */
function __gulp_init_namespace___get_article_meta($post_id, $meta = array()) {
    // grab the date
    if (isset($meta["date"])) {
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
    if (isset($meta["author"])) {
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
    if (isset($meta["comments"])) {
        $comment_count = get_comments_number($post_id);

        $meta["comments"] = array(
            "icon"  => "fa-comment",
            "links" => array(
                array(
                    "url"    => get_comments_link($post_id),
                    "title"  => sprintf(_n("%s comment", "%s comments", $comment_count, "__gulp_init_namespace__"), $comment_count),
                    "target" => "",
                )
            ),
            "count" => $comment_count,
        );
    }

    // grab the taxonomy terms
    if (isset($meta["taxonomies"])) {
        foreach ($meta["taxonomies"] as $tax) {
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

/**
 * Wrapper around ACF's `get_field` to ensure errors don't occur if ACF isn't active
 *
 * @param string name  ACF field name
 * @param int post_id  An ID for a post
 *
 * @return array  The field value
 */
function __gulp_init_namespace___get_field($name, $post_id = null) {
    if (function_exists("get_field")) {
        return get_field($name, $post_id);
    } else {
        return false;
    }
}

/**
 * Retrieve a menu tiel from the database given the location the menu is assigned to
 *
 * @param string location  Menu location
 *
 * @return string  The name of the menu
 */
function __gulp_init_namespace___get_menu_title($location) {
    $locations = get_nav_menu_locations();
    $menu      = get_term($locations[$location], "nav_menu");

    return $menu->name;
}
