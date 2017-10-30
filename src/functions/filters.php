<?php
/* ------------------------------------------------------------------------ *\
 * Functions: Filters
\* ------------------------------------------------------------------------ */

// push the CSS & JS over HTTP2
function new_site_http2_push() {
    header("Link: <" . get_bloginfo("template_directory") . "/assets/styles/modern.css>; rel=preload; as=style, <https://fonts.googleapis.com/css?family=Open+Sans:400,400italic,700,700italic>; rel=preload; as=style; crossorigin");
}
add_action("init", "new_site_http2_push", 10);

// set cookie when a query string gets passed
function new_site_set_cookie() {
    $cookie     = isset($_GET["cookie"]) ? $_GET["cookie"] : false;
    $expiration = isset($_GET["expiration"]) ? time() + $_GET["expiration"] : time() + 604800;

    if ($cookie) {
        setcookie($cookie, "true", $expiration); // expires in 1 week by default
        exit;
    }
}
add_action("init", "new_site_set_cookie", 10);

// adjust WordPress login screen styles
function new_site_login_styles() {
    echo "<link href='" . get_bloginfo("template_directory") . "/assets/styles/wp-login.css' rel='stylesheet' />";
    wp_enqueue_script("new_site_critical_scripts", get_bloginfo("template_directory") . "/assets/scripts/critical.js", array(), false, true);
}
add_action("login_enqueue_scripts", "new_site_login_styles");

// change login logo URL
function new_site_login_logo_url() {
    return get_bloginfo("url");
}
add_filter("login_headerurl", "new_site_login_logo_url");

// change login logo title
function new_site_login_logo_title() {
    return get_bloginfo("name");
}
add_filter("login_headertitle", "new_site_login_logo_title");

// add user-content class to TinyMCE body
function new_site_tinymce_settings($settings) {
    $settings["body_class"] .= " user-content";
	return $settings;
}
add_filter("tiny_mce_before_init", "new_site_tinymce_settings");

// add classes to elements
function new_site_add_user_content_classes($content) {
    if ($content) {
        $DOM = new DOMDocument();
        $DOM->loadHTML($content);

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

        return $DOM->saveHTML();
    }
}
add_filter("the_content", "new_site_add_user_content_classes");
add_filter("acf_the_content", "new_site_add_user_content_classes");

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

// wrap tables in a div
function new_site_wrap_tables($content) {
    if ($content) {
        $DOM = new DOMDocument();
        $DOM->loadHTML($content);

        $tables = $DOM->getElementsByTagName("table");

        $table_container = $DOM->createElement("div");
        $table_container->setAttribute("class", "user-content_text_table_container text_table_container");

        foreach ($tables as $table) {
            $table_container_clone = $table_container->cloneNode();
            $table->parentNode->replaceChild($table_container_clone, $table);
            $table_container_clone->appendChild($table);
        }

        return $DOM->saveHTML();
    }
}
add_filter("the_content", "new_site_wrap_tables");
add_filter("acf_the_content", "new_site_wrap_tables");

// wrap frames in a div
function new_site_wrap_frames($content) {
    if ($content) {
        $DOM = new DOMDocument();
        $DOM->loadHTML($content);

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

        return $DOM->saveHTML();
    }
}
add_filter("the_content", "new_site_wrap_frames");
add_filter("acf_the_content", "new_site_wrap_frames");

// remove dimensions from thumbnails
function new_site_remove_thumbnail_dimensions($html, $post_id, $post_image_id) {
    if ($html) {
        $DOM = new DOMDocument();
        $DOM->loadHTML($html);

        $images = $DOM->getElementsByTagName("img");

        foreach ($images as $image) {
            $image->removeAttribute("height");
            $image->removeAttribute("width");
        }

        return $DOM->saveHTML();
    }
}
add_filter("post_thumbnail_html", "new_site_remove_thumbnail_dimensions", 10, 3);

// add rel="noopener" to external links
function new_site_rel_noopener($content) {
    if ($content) {
        $DOM = new DOMDocument();
        $DOM->loadHTML($content);

        $anchors = $DOM->getElementsByTagName("a");

        foreach ($anchors as $anchor) {
            $existing_rel = $anchor->getAttribute("rel");

            if (!$existing_rel) {
                $anchor->setAttribute("rel", "noopener");
            }
        }

        return $DOM->saveHTML();
    }
}
add_filter("the_content", "new_site_rel_noopener");
add_filter("acf_the_content", "new_site_rel_noopener", 12);

// add "Download Adobe Reader" link on all pages that link to PDFs
function new_site_acrobat_link() {
    global $post;

    $has_pdf = false;
    $content = get_the_content();
    $fields  = get_fields();
    $output  = "";

    if ($content) {
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
        $output .= "<p class='content_text text _small'>" . sprintf(__("Having trouble opening PDFs? %sDownload Adobe Reader here.%s", "new_site"), "<a class='text_link link' href='https://get.adobe.com/reader/' target='_blank' rel='noopener'>", "</a>") . "</p>";
    }

    echo $output;
}
add_filter("new_site_after_content", "new_site_acrobat_link");

// disable Ninja Forms styles
function new_site_dequeue_nf_display() {
    wp_dequeue_style("nf-display");
}
add_action("ninja_forms_enqueue_scripts", "new_site_dequeue_nf_display", 999);
/*removeIf(tribe_css_js_php)*/
// force redirect 'cause tribe is stupid
function new_site_tribe_redirect($template) {
    if (is_post_type_archive("tribe_events")) {
        return TEMPLATEPATH . "/archive-tribe_events.php";
    } elseif (get_post_type() == "tribe_events") {
        return TEMPLATEPATH . "/single-tribe_events.php";
    } else {
        return $template;
    }
}
add_action("template_include", "new_site_tribe_redirect");

// dequeue Tribe styles
function new_site_tribe_events_dequeue_styles() {
    wp_dequeue_style("tribe-events-calendar-style", 999);
}
add_filter("wp_enqueue_scripts", "new_site_tribe_events_dequeue_styles");

// disable Tribe Events ical links (since I can't re-style them)
function new_site_tribe_events_list_show_ical_link() {
    return false;
}
add_filter("tribe_events_list_show_ical_link", "new_site_tribe_events_list_show_ical_link");

// add class to event list date headers
function new_site_tribe_events_list_the_date_headers($html, $event_month, $event_year) {
    $event_month = DateTime::createFromFormat("Ymd", "{$event_year}{$event_month}01");
    return "<h3 class='content_title title -divider'>{$event_month->format("F")} {$event_year}</h3>";
}
add_filter("tribe_events_list_the_date_headers", "new_site_tribe_events_list_the_date_headers", 10, 3);

// add class to event list navigation links
function new_site_tribe_the_nav_link($html) {
    return preg_replace("/<a/", "<a class='menu-list_link link'", $html);
}
add_filter("tribe_events_the_previous_month_link", "new_site_tribe_the_nav_link", 10, 1);
add_filter("tribe_events_the_next_month_link", "new_site_tribe_the_nav_link", 10, 1);
add_filter("tribe_the_prev_event_link", "new_site_tribe_the_nav_link", 10, 1);
add_filter("tribe_the_next_event_link", "new_site_tribe_the_nav_link", 10, 1);
add_filter("tribe_the_day_link", "new_site_tribe_the_nav_link", 10, 1);/*endRemoveIf(tribe_css_js_php)*/

// enable lazy loading on images
function new_site_lazy_load_images($content) {
    if (!((function_exists("tribe_is_month") && tribe_is_month()) && !is_tax())) {
        // add `<noscript>` fallback for all imges
        $content = preg_replace("/(<img[^>]+?>)/", "$1<noscript>$1</noscript>", $content);

        // replace all `src` attributes in images with `data-nomral`
        $content = preg_replace("/(<img.*?)(src=)([^>]+?><noscript)/im", "$1data-normal=$3", $content);

        // replace all `srcset` attributes in images with `data-srcset`
        $content = preg_replace("/(<img.*?)(srcset=)([^>]+?><noscript)/im", "$1data-srcset=$3", $content);

        // add ` _js ` to all `class` attributes in images
        $content = preg_replace("/(<img[^>]+class=(?:\'|\"))([^\'|\"\>]+)([^>]+?><noscript)/im", "$1_js $2$3", $content);
    }

    return $content;
}
add_filter("the_content", "new_site_lazy_load_images", 999, 1);
add_filter("acf_the_content", "new_site_lazy_load_images", 999, 1);
add_filter("post_thumbnail_html", "new_site_lazy_load_images", 999, 1);

// redirect to the home template if no front page is set
function new_site_home_template_redirect($template) {
    if (is_front_page() && get_option("show_on_front") != "page") {
        return TEMPLATEPATH . "/home.php";
    } else {
        return $template;
    }
}
add_action("template_include", "new_site_home_template_redirect");
