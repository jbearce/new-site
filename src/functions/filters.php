<?php
/* ------------------------------------------------------------------------ *\
 * Functions: Filters
\* ------------------------------------------------------------------------ */

// push the CSS & JS over HTTP2
function __gulp_init__namespace_http2_push() {
    header("Link: <" . get_bloginfo("template_directory") . "/assets/styles/modern.css>; rel=preload; as=style, <https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,700,700italic>; rel=preload; as=style; crossorigin");
}
add_action("init", "__gulp_init__namespace_http2_push", 10);

// set cookie when a query string gets passed
function __gulp_init__namespace_set_cookie() {
    $cookie     = isset($_GET["cookie"]) ? $_GET["cookie"] : false;
    $expiration = isset($_GET["expiration"]) ? time() + $_GET["expiration"] : time() + 604800;

    if ($cookie) {
        setcookie($cookie, "true", $expiration); // expires in 1 week by default
        exit;
    }
}
add_action("init", "__gulp_init__namespace_set_cookie", 10);

// adjust WordPress login screen styles
function __gulp_init__namespace_login_styles() {
    echo "<link href='" . get_bloginfo("template_directory") . "/assets/styles/wp-login.css' rel='stylesheet' />";
    wp_enqueue_script("__gulp_init__namespace_critical_scripts", get_bloginfo("template_directory") . "/assets/scripts/critical.js", array(), false, true);
}
add_action("login_enqueue_scripts", "__gulp_init__namespace_login_styles");

// change login logo URL
function __gulp_init__namespace_login_logo_url() {
    return get_bloginfo("url");
}
add_filter("login_headerurl", "__gulp_init__namespace_login_logo_url");

// change login logo title
function __gulp_init__namespace_login_logo_title() {
    return get_bloginfo("name");
}
add_filter("login_headertitle", "__gulp_init__namespace_login_logo_title");

// add user-content class to TinyMCE body
function __gulp_init__namespace_tinymce_settings($settings) {
    $settings["body_class"] .= " user-content";
    return $settings;
}
add_filter("tiny_mce_before_init", "__gulp_init__namespace_tinymce_settings");

// fix Ninja Forms HTML field formatting
function __gulp_init__namespace_ninja_forms_html($default_value, $field_class, $field_settings) {
    if ($field_settings["type"] === "html") {
        $default_value = apply_filters("the_content", $default_value);
    }

    return $default_value;
}
add_filter("ninja_forms_render_default_value", "__gulp_init__namespace_ninja_forms_html", 10, 3);

// fix Ninja Forms not being output when no content exists and selected via meta box
function __gulp_init__namespace_fix_ninja_forms($content) {
    return !$content && get_post_meta(get_the_ID(), "ninja_forms_form", true) ? "<!-- ninja form -->" : $content;
}
add_filter("the_content", "__gulp_init__namespace_fix_ninja_forms", 5);

// fix shortcode formatting
function __gulp_init__namespace_fix_shortcodes() {
    if (!is_admin()) {
        remove_filter("the_content", "wpautop");
        add_filter("the_content", "wpautop", 12);
    }
}
add_filter("init", "__gulp_init__namespace_fix_shortcodes", 10);

// add classes to elements
function __gulp_init__namespace_add_user_content_classes($content) {
    global $post;

    if ($content && !(isset($GLOBALS["tribe_hooked_template"]) && $GLOBALS["tribe_hooked_template"])) {
        $DOM = new DOMDocument();
        $DOM->loadHTML(mb_convert_encoding("<html>{$content}</html>", "HTML-ENTITIES", "UTF-8"), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $anchors = $DOM->getElementsByTagName("a");

        foreach ($anchors as $anchor) {
            $existing_classes = $anchor->getAttribute("class") ? $anchor->getAttribute("class") : "";

            if (preg_match("/button/i", $existing_classes)) {
                $anchor->setAttribute("class", "user-content_button {$existing_classes}");
            } else {
                $anchor->setAttribute("class", "user-content_link link {$existing_classes}");
            }
        }

        $h1s = $DOM->getElementsByTagName("h1");

        foreach ($h1s as $h1) {
            $h1->setAttribute("class", "user-content_title title -h1 {$h1->getAttribute("class")}");
        }

        $h2s = $DOM->getElementsByTagName("h2");

        foreach ($h2s as $h2) {
            $h2->setAttribute("class", "user-content_title title -h2 {$h2->getAttribute("class")}");
        }

        $h3s = $DOM->getElementsByTagName("h3");

        foreach ($h3s as $h3) {
            $h3->setAttribute("class", "user-content_title title -h3 {$h3->getAttribute("class")}");
        }

        $h4s = $DOM->getElementsByTagName("h4");

        foreach ($h4s as $h4) {
            $h4->setAttribute("class", "user-content_title title -h4 {$h4->getAttribute("class")}");
        }

        $h5s = $DOM->getElementsByTagName("h5");

        foreach ($h5s as $h5) {
            $h5->setAttribute("class", "user-content_title title -h5 {$h5->getAttribute("class")}");
        }

        $h6s = $DOM->getElementsByTagName("h6");

        foreach ($h6s as $h6) {
            $h6->setAttribute("class", "user-content_title title -h6 {$h6->getAttribute("class")}");
        }

        $paragraphs = $DOM->getElementsByTagName("p");

        foreach ($paragraphs as $paragraph) {
            $paragraph->setAttribute("class", "user-content_text text {$paragraph->getAttribute("class")}");
        }

        $ordered_lists = $DOM->getElementsByTagName("ol");

        foreach ($ordered_lists as $ordered_list) {
            $ordered_list->setAttribute("class", "user-content_text text -list -ordered {$ordered_list->getAttribute("class")}");
        }

        $unordered_lists = $DOM->getElementsByTagName("ul");

        foreach ($unordered_lists as $unordered_list) {
            $unordered_list->setAttribute("class", "user-content_text text -list -unordered {$unordered_list->getAttribute("class")}");
        }

        $list_items = $DOM->getElementsByTagName("li");

        foreach ($list_items as $list_item) {
            $list_item->setAttribute("class", "text_list-item {$list_item->getAttribute("class")}");
        }

        $tables = $DOM->getElementsByTagName("table");

        foreach ($tables as $table) {
            $table->setAttribute("class", "user-content_text text -table {$table->getAttribute("class")}");
        }

        $table_headers = $DOM->getElementsByTagName("thead");

        foreach ($table_headers as $table_header) {
            $table_header->setAttribute("class", "text_header {$table_header->getAttribute("class")}");
        }

        $table_bodies = $DOM->getElementsByTagName("tbody");

        foreach ($table_bodies as $tbody) {
            $tbody->setAttribute("class", "text_body {$tbody->getAttribute("class")}");
        }

        $table_footers = $DOM->getElementsByTagName("tfoot");

        foreach ($table_footers as $table_footer) {
            $table_footer->setAttribute("class", "text_footer {$table_footer->getAttribute("class")}");
        }

        $table_rows = $DOM->getElementsByTagName("tr");

        foreach ($table_rows as $table_row) {
            $table_row->setAttribute("class", "text_row {$table_row->getAttribute("class")}");
        }

        $table_cell_headers = $DOM->getElementsByTagName("th");

        foreach ($table_cell_headers as $table_cell_header) {
            $table_cell_header->setAttribute("class", "text_cell -header {$table_cell_header->getAttribute("class")}");
        }

        $table_cells = $DOM->getElementsByTagName("td");

        foreach ($table_cells as $table_cell) {
            $table_cell->setAttribute("class", "text_cell {$table_cell->getAttribute("class")}");
        }

        $blockquotes = $DOM->getElementsByTagName("blockquote");

        foreach ($blockquotes as $blockquote) {
            $blockquote->setAttribute("class", "user-content_blockquote blockquote {$blockquote->getAttribute("class")}");
        }

        $horizontal_rules = $DOM->getElementsByTagName("hr");

        foreach ($horizontal_rules as $horizontal_rule) {
            $horizontal_rule->setAttribute("class", "user-content_divider divider {$horizontal_rule->getAttribute("class")}");
        }

        $figures = $DOM->getElementsByTagName("figure");

        foreach ($figures as $figure) {
            $figure->setAttribute("class", "user-content_figure figure {$figure->getAttribute("class")}");
        }

        $figcaptions = $DOM->getElementsByTagName("figcaption");

        foreach ($figcaptions as $figcaption) {
            $figcaption->setAttribute("class", "user-content_text text {$figcaption->getAttribute("class")}");
        }

        // remove unneeded HTML tag
        $DOM = remove_root_tag($DOM);

        $content = $DOM->saveHTML();
    }

    return $content;
}
add_filter("the_content", "__gulp_init__namespace_add_user_content_classes", 10);
add_filter("acf_the_content", "__gulp_init__namespace_add_user_content_classes", 10);
add_filter("tribe_events_get_the_excerpt", "__gulp_init__namespace_add_user_content_classes", 10);

// wrap tables in a div
function __gulp_init__namespace_wrap_tables($content) {
    global $post;

    if ($content && !(isset($GLOBALS["tribe_hooked_template"]) && $GLOBALS["tribe_hooked_template"])) {
        $DOM = new DOMDocument();
        $DOM->loadHTML(mb_convert_encoding("<html>{$content}</html>", "HTML-ENTITIES", "UTF-8"), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $tables = $DOM->getElementsByTagName("table");

        $table_container = $DOM->createElement("div");
        $table_container->setAttribute("class", "user-content_text_table_container text_table_container");

        foreach ($tables as $table) {
            $table_container_clone = $table_container->cloneNode();
            $table->parentNode->replaceChild($table_container_clone, $table);
            $table_container_clone->appendChild($table);
        }

        // remove unneeded HTML tag
        $DOM = remove_root_tag($DOM);

        $content = $DOM->saveHTML();
    }

    return $content;
}
add_filter("the_content", "__gulp_init__namespace_wrap_tables", 10);
add_filter("acf_the_content", "__gulp_init__namespace_wrap_tables", 10);
add_filter("tribe_events_get_the_excerpt", "__gulp_init__namespace_wrap_tables", 10);

// wrap frames in a div
function __gulp_init__namespace_wrap_frames($content) {
    global $post;

    if ($content && !(isset($GLOBALS["tribe_hooked_template"]) && $GLOBALS["tribe_hooked_template"])) {
        $DOM = new DOMDocument();
        $DOM->loadHTML(mb_convert_encoding("<html>{$content}</html>", "HTML-ENTITIES", "UTF-8"), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $iframes = $DOM->getElementsByTagName("iframe");

        $iframe_container = $DOM->createElement("div");
        $iframe_container->setAttribute("class", "user-content_iframe_container iframe_container");

        foreach ($iframes as $iframe) {
            $iframe->setAttribute("class", "iframe {$iframe->getAttribute("class")}");

            $aspect_ratio = "56.25%";

            $height = $iframe->getAttribute("height");
            $width  = $iframe->getAttribute("width");

            $iframe->removeAttribute("height");
            $iframe->removeAttribute("width");

            if ($height && $width) {
                $height = (int) preg_replace("/[^0-9]/", "", $height);
                $width  = (int) preg_replace("/[^0-9]/", "", $width);

                $aspect_ratio = ($height / $width * 100) . "%";
            }

            $iframe_container_clone = $iframe_container->cloneNode();

            $iframe_container_clone->setAttribute("style", "padding-bottom:{$aspect_ratio};");

            $iframe_parent_node = $iframe->parentNode;

            if ($iframe_parent_node->tagName === "p") {
                $iframe_parent_node->parentNode->replaceChild($iframe_container_clone, $iframe_parent_node);
            } else {
                $iframe->parentNode->replaceChild($iframe_container_clone, $iframe);
            }

            $iframe_container_clone->appendChild($iframe);
        }

        // remove unneeded HTML tag
        $DOM = remove_root_tag($DOM);

        $content = $DOM->saveHTML();
    }

    return $content;
}
add_filter("the_content", "__gulp_init__namespace_wrap_frames", 10);
add_filter("acf_the_content", "__gulp_init__namespace_wrap_frames", 10);
add_filter("tribe_events_get_the_excerpt", "__gulp_init__namespace_wrap_frames", 10);

// add rel="noopener" to external links
function __gulp_init__namespace_rel_noopener($content) {
    global $post;

    if ($content && !(isset($GLOBALS["tribe_hooked_template"]) && $GLOBALS["tribe_hooked_template"])) {
        $DOM = new DOMDocument();
        $DOM->loadHTML(mb_convert_encoding("<html>{$content}</html>", "HTML-ENTITIES", "UTF-8"), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $anchors = $DOM->getElementsByTagName("a");

        foreach ($anchors as $anchor) {
            $existing_rel = $anchor->getAttribute("rel");

            if (!$existing_rel) {
                $anchor->setAttribute("rel", "noopener");
            }
        }

        // remove unneeded HTML tag
        $DOM = remove_root_tag($DOM);

        $content = $DOM->saveHTML();
    }

    return $content;
}
add_filter("the_content", "__gulp_init__namespace_rel_noopener", 10);
add_filter("acf_the_content", "__gulp_init__namespace_rel_noopener", 10);
add_filter("tribe_events_get_the_excerpt", "__gulp_init__namespace_rel_noopener", 10);

// enable lazy loading on images
function __gulp_init__namespace_lazy_load_images($content) {
    global $post;

    if ($content && !(isset($GLOBALS["tribe_hooked_template"]) && $GLOBALS["tribe_hooked_template"])) {
        $DOM   = new DOMDocument();

        $DOM->loadHTML("<html>{$content}</html>", LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $XPath = new DOMXPath($DOM);

        $images = $XPath->query("//img");

        foreach ($images as $image) {
            if ($image->parentNode->nodeName !== "noscript") {
                $existing_src    = $image->getAttribute("src");
                $existing_srcset = $image->getAttribute("srcset");

                // add noscript before images
                $noscript = $DOM->createElement("noscript");
                $noscript->appendChild($image->cloneNode());
                $image->parentNode->insertBefore($noscript, $image);

                // change src to data-normal
                if ($existing_src) {
                    $image->removeAttribute("src");
                    $image->setAttribute("data-normal", $existing_src);
                }

                // change srcset to data-srcset
                if ($existing_srcset) {
                    $image->removeAttribute("srcset");
                    $image->setAttribute("data-srcset", $existing_srcset);
                }

                // add _js class
                $image->setAttribute("class", "_js {$image->getAttribute("class")}");
            }
        }

        // remove unneeded HTML tag
        $DOM = remove_root_tag($DOM);

        $content = $DOM->saveHTML();
    }

    return $content;
}
add_filter("the_content", "__gulp_init__namespace_lazy_load_images", 10);
add_filter("acf_the_content", "__gulp_init__namespace_lazy_load_images", 10);
add_filter("tribe_events_get_the_excerpt", "__gulp_init__namespace_lazy_load_images", 10);
add_filter("post_thumbnail_html", "__gulp_init__namespace_lazy_load_images", 10);

// remove dimensions from thumbnails
function __gulp_init__namespace_remove_thumbnail_dimensions($html, $post_id, $post_image_id) {
    global $post;

    if ($html && !(isset($GLOBALS["tribe_hooked_template"]) && $GLOBALS["tribe_hooked_template"])) {
        $DOM = new DOMDocument();
        $DOM->loadHTML(mb_convert_encoding("<html>{$html}</html>", "HTML-ENTITIES", "UTF-8"), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $images = $DOM->getElementsByTagName("img");

        foreach ($images as $image) {
            $image->removeAttribute("height");
            $image->removeAttribute("width");
        }

        // remove unneeded HTML tag
        $DOM = remove_root_tag($DOM);

        $html = $DOM->saveHTML();
    }

    return $html;
}
add_filter("post_thumbnail_html", "__gulp_init__namespace_remove_thumbnail_dimensions", 10, 3);

// add "Download Adobe Reader" link on all pages that link to PDFs
function __gulp_init__namespace_acrobat_link() {
    global $post;

    $has_pdf = false;
    $content = get_the_content();
    $fields  = get_fields();
    $output  = "";

    if ($content && !is_post_type_archive("tribe_events")) {
        preg_match("/\.pdf(?:\'|\")/im", $content, $matches);

        if ($matches) {
            $has_pdf = true;
        }
    }

    if ($fields && !$has_pdf) {
        foreach ($fields as $field) {
            preg_match("/\.pdf(?:\'|\"|$)/im", json_encode($field), $matches);

            if ($matches) {
                $has_pdf = true;
                break;
            }
        }
    }

    if ($has_pdf === true) {
        $output .= "<hr class='divider' />";
        $output .= "<p class='content_text text _small'>" . sprintf(__("Having trouble opening PDFs? %sDownload Adobe Reader here.%s", "__gulp_init__namespace"), "<a class='text_link link' href='https://get.adobe.com/reader/' target='_blank' rel='noopener'>", "</a>") . "</p>";
    }

    echo $output;
}
add_filter("__gulp_init__namespace_after_content", "__gulp_init__namespace_acrobat_link");

// disable Ninja Forms styles
function __gulp_init__namespace_dequeue_nf_display() {
    wp_dequeue_style("nf-display");
}
add_action("ninja_forms_enqueue_scripts", "__gulp_init__namespace_dequeue_nf_display", 999);

// redirect to the home template if no front page is set
function __gulp_init__namespace_home_template_redirect($template) {
    if (is_front_page() && get_option("show_on_front") != "page") {
        return TEMPLATEPATH . "/home.php";
    } else {
        return $template;
    }
}
add_action("template_include", "__gulp_init__namespace_home_template_redirect");

// decode HTML entities in bloginfo("description")
function __gulp_init__namespace_decode_html_entities_in_blog_description($value, $field) {
    if ($field === "description") {
        $value = html_entity_decode($value);
    }

    return $value;
}
add_filter("bloginfo", "__gulp_init__namespace_decode_html_entities_in_blog_description", 10, 2);

// create a local copy of Google Analytics instead and serve that for caching purposes
function __gulp_init__cache_google_analytics($url) {
    $local_path  = "{$_SERVER["DOCUMENT_ROOT"]}/analytics.js";

    if (!file_exists($local_path) || date("Ymd", filemtime($local_path)) <= date("Ymd", strtotime("-2 weeks"))) {
        file_put_contents("{$_SERVER["DOCUMENT_ROOT"]}/analytics.js", fopen($url, "r"));
    }

    return home_url("/analytics.js");
}
add_filter("gadwp_analytics_script_path", "__gulp_init__cache_google_analytics");

/*removeIf(tribe_css_js_php)*/// force redirect 'cause tribe is stupid
function __gulp_init__namespace_redirect_tribe_templates($template) {
    if (is_post_type_archive("tribe_events")) {
        return locate_template("archive-tribe_events.php");
    } elseif (is_singular("tribe_events")) {
        return locate_template("single-tribe_events.php");
    } else {
        return $template;
    }
}
add_filter("template_include", "__gulp_init__namespace_redirect_tribe_templates", 10, 1);

// add proper link classes to next/previous event links
function __gulp_init__namespace_tribe_add_pagination_class($anchor) {
    return preg_replace("/<a /", "<a class='menu-list_link link' ", $anchor);
}
add_filter("tribe_the_next_event_link", "__gulp_init__namespace_tribe_add_pagination_class");
add_filter("tribe_the_prev_event_link", "__gulp_init__namespace_tribe_add_pagination_class");
add_filter("tribe_events_the_previous_month_link", "__gulp_init__namespace_tribe_add_pagination_class");
add_filter("tribe_events_the_next_month_link", "__gulp_init__namespace_tribe_add_pagination_class");

// add proper title classes to month $table_headers
function __gulp_init__namespace_tribe_add_month_title_class($html) {
    if ($html) {
        $html = "<h5 class='tribe-events_title title -divider'>{$html}</h5>";
    }

    return $html;
}
add_filter("tribe_events_list_the_date_headers", "__gulp_init__namespace_tribe_add_month_title_class");/*endRemoveIf(tribe_css_js_php)*/
