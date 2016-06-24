<?php
// enable featured images
add_theme_support("post-thumbnails");

// enable HTML5
add_theme_support("html5");

// register styles & scripts
function new_site_register_scripts() {
    // get the is_IE value
    global $is_IE;

    // register styles & scripts
    wp_register_style("font-awesome", "//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css", array(), "4.5.0");
    wp_register_style("google-fonts", "//fonts.googleapis.com/css?family=Open+Sans:400,400italic,700,700italic");
    wp_register_style("new_site-modern", get_bloginfo("template_directory") . "/assets/styles/modern.css"@@if (context.version) {, array(), "@@version"});
    wp_register_script("new_site-scripts", get_bloginfo("template_directory") . "/assets/scripts/all.js", array("jquery")@@if (context.version) {, "@@version"}@@if (!context.version) {, false}, true);

    // enqueue IE specific styles & scripts
    if ($is_IE ) {
        // register IE8 styles & scripts
        wp_register_style("new_site-legacy", get_bloginfo("template_directory") . "/assets/styles/legacy.css"@@if (context.version) {, array("new_site-modern"), "@@version"});
        wp_register_script("html5shiv", get_bloginfo("template_directory") . "/assets/scripts/fallback/html5shiv.js", array(), "3.6.2");
        wp_register_script("flexibility", get_bloginfo("template_directory") . "/assets/scripts/fallback/flexibility.js", array(), "1.0.6");
        wp_register_script("nwmatcher", get_bloginfo("template_directory") . "/assets/scripts/fallback/nwmatcher-1.3.4.min.js", array(), "1.3.4");
        wp_register_script("selectivizr", get_bloginfo("template_directory") . "/assets/scripts/fallback/selectivizr-1.0.2.min.js", array("nwmatcher"), "1.0.2");
        wp_register_script("placeholders", get_bloginfo("template_directory") . "/assets/scripts/fallback/placeholders.js", array("nwmatcher"), "1.0.2");

        // add IE8 or lower condition to IE8 styles & scripts
        $GLOBALS["wp_styles"]->add_data("new_site-legacy", "conditional", "lte IE 8");
        $GLOBALS["wp_scripts"]->add_data("html5shiv", "conditional", "lte IE 8");
        $GLOBALS["wp_scripts"]->add_data("nwmatcher", "conditional", "lte IE 8");
        $GLOBALS["wp_scripts"]->add_data("selectivizr", "conditional", "lte IE 8");
        $GLOBALS["wp_scripts"]->add_data("flexibility", "conditional", "lte IE 8");
        $GLOBALS["wp_scripts"]->add_data("placeholders", "conditional", "lte IE 8");
    }
}
add_action("init", "new_site_register_scripts");

// enqueue styles & scripts
function new_site_enqueue_scripts() {
    // get the is_IE value
    global $is_IE;

    // enqueue styles
    wp_enqueue_style("font-awesome");
    wp_enqueue_style("google_fonts");
    wp_enqueue_style("new_site-modern");
    wp_enqueue_script("new_site-scripts");

    // enqueue IE specific styles & scripts
    if ($is_IE ) {
        wp_enqueue_style("new_site-legacy");
        wp_enqueue_script("html5shiv");
        wp_enqueue_script("nwmatcher");
        wp_enqueue_script("selectivizr");
        wp_enqueue_script("flexibility");
        wp_enqueue_script("placeholders");
    }
}
add_action("wp_enqueue_scripts", "new_site_enqueue_scripts");

// remove height attributes from images
function remove_img_height_attribute($html) {
    $html = preg_replace("/(height)=\"\d*\"\s/", "", $html);
    return $html;
}
add_filter("image_send_to_editor", "remove_img_height_attribute", 10);
add_filter("post_thumbnail_html", "remove_img_height_attribute", 10);

// enable menus
function register_menus() {
	register_nav_menus(array(
		"primary" => "Navigation",
	));
}
add_action("init", "register_menus");

// add slideshow image size
add_image_size("slideshow", 1600, 900, true);

// new_site Walker
class new_site_walker extends Walker_Nav_Menu {
    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $classes = empty($item->classes) ? array() : (array) $item->classes;

        array_push($classes, "menu-list_item");

        if (in_array("menu-item-has-children", $classes)) {
            array_push($classes, "-parent");
        }

        $class_names = join(" ", apply_filters("nav_menu_css_class", array_filter($classes), $item));
        $class_names = " class='" . esc_attr($class_names) . "'";
        $target = $item->target ? " target='{$item->target}'" : "";
        $aria_haspopup = in_array("menu-list_item-has-children", $classes) ? " aria-haspopup='true'" : "";

        $output .= sprintf(
            "<li%s><a class='menu-list_link link' href='%s'%s%s>%s</a>",
            $class_names,
            $item->url,
            $target,
            $aria_haspopup,
            $item->title
        );
    }
    function start_lvl(&$output, $depth = 0, $args = array()) {
        $flyout_class = $depth > 0 ? "flyout" : "dropdown";
        $output .= "<ul class='menu-list -vertical -{$flyout_class}'>";
    }
    function end_lvl(&$output, $depth = 0, $args = array()) {
        $output .= "</ul>";
    }
    function end_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $output .= "</li>";
    }
}

// mobile new_site walker
class mobile_new_site_walker extends Walker_Nav_Menu {
    static $li_count = 0;
    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $classes = empty($item->classes) ? array() : (array) $item->classes;

        array_push($classes, "menu-list_item");

        if (in_array("menu-item-has-children", $classes)) {
            array_push($classes, "-parent");
        }

        $class_names = join(" ", apply_filters("nav_menu_css_class", array_filter($classes), $item));
        $class_names = " class='" . esc_attr($class_names) . "'";
        $target = $item->target ? " target='{$item->target}'" : "";

        $output .= sprintf(
            "<li%s><a class='menu-list_link link' href='%s'%s>%s</a>",
            $class_names,
            $item->url,
            $target,
            $item->title
        );
    }
    function start_lvl(&$output, $depth = 0, $args = array()) {
        $output .= "<button class='menu-toggle'><i class='fa fa-chevron-down'></i><span class='_visuallyhidden'>" . __("Show More") . "</span></button>";
        $output .= "<ul class='menu-list -vertical -accordion'>";
    }
    function end_lvl(&$output, $depth = 0, $args = array()) {
        $output .= "</ul>";
    }
    function end_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $output .= "</li>";
    }
}

// enable sidebar
if (function_exists("register_sidebar")) {
	register_sidebar(array(
		"id"			=> "sidebar",
		"name" 			=> "Sidebar",
		"before_widget" => "<div class='widget'>",
		"before_title" 	=> "<header class='widget-header'><h6 class='widget-title title'>",
		"after_title" 	=> "</h6></header>",
		"after_widget" 	=> "</div>",
	));
}

// add sub_menu options to wp_nav_menu
add_filter("wp_nav_menu_objects", "my_wp_nav_menu_objects_sub_menu", 10, 2);
function my_wp_nav_menu_objects_sub_menu($sorted_menu_items, $args) {
	if (isset($args->sub_menu)) {
		$root_id = 0;
		foreach ($sorted_menu_items as $menu_item) {
			if ($menu_item->current) {
				$root_id = ($menu_item->menu_item_parent) ? $menu_item->menu_item_parent : $menu_item->ID;
				break;
			}
		}
		if (!isset($args->direct_parent)) {
			$prev_root_id = $root_id;
			while ($prev_root_id != 0) {
				foreach ($sorted_menu_items as $menu_item) {
					if ($menu_item->ID == $prev_root_id) {
						$prev_root_id = $menu_item->menu_item_parent;
						if ($prev_root_id != 0) $root_id = $menu_item->menu_item_parent;
						break;
					}
				}
			}
		}
        // new code
        if (isset($args->parent_id)) {
            $_parent = 0;
            $_sorted_copy = $sorted_menu_items;
            foreach($_sorted_copy as $key=>$item) {
                if ($item->object_id == $args->parent_id) {
                    $_parent = $item->ID;
                    break;
                }
            }
            // breaks depth argument because technically the parent isn't right
            foreach($_sorted_copy as $key=>$item) {
                if ($item->menu_item_parent != $_parent) {
                    unset($_sorted_copy[$key]);
                }
            }
            return $_sorted_copy;
        }
        // end new code
		$menu_item_parents = array();
		foreach ($sorted_menu_items as $key => $item) {
			if ($item->ID == $root_id) $menu_item_parents[] = $item->ID;
			if (in_array($item->menu_item_parent, $menu_item_parents)) {
				$menu_item_parents[] = $item->ID;
			} else if (!(isset($args->show_parent) && in_array($item->ID, $menu_item_parents))) {
				unset($sorted_menu_items[$key]);
			}
		}
		return $sorted_menu_items;
	} else {
		return $sorted_menu_items;
	}
}

/***** MEGA MENU *****/
/*
// mega menu walker
class megaMenuWalker extends Walker_Nav_Menu {
	private $column_limit = 3;
	private $column_count = 0;
    static $li_count = 0;
    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $item_id = $item->ID;
        if ($depth == 0) {
			self::$li_count = 0;
		}
		if ($depth == 1 && self::$li_count == 1) {
			$this->column_count++;
		}
        if ($depth == 1 && in_array("break", $classes) && self::$li_count != 1 && $this->column_count < $this->column_limit) {
            $output .= "</ul><ul class='menu-list -submenu'>";
			$this->column_count++;
        }
        $class_names = join(" ", apply_filters("nav_menu_css_class", array_filter($classes), $item)); // set up the classes array to be added as classes to each li
        $class_names = " class='" . esc_attr($class_names) . "'";
        $target = "";
        if ($item->target) {
            $target = " target='_blank'";
        }
        $output .= sprintf(
            "<li id='menu-list_item-%s'%s><a href='%s'%s>%s</a>",
            $item_id,
            $class_names,
            $item->url,
            $target,
            $item->title
        );
        self::$li_count++;
    }
    function start_lvl(&$output, $depth = 0, $args = array()) {
        if ($depth == 0) {
			$output .= "<div class='mega-menu'>";
        }
        $output .= "<ul class='menu-list -submenu'>";
    }
    function end_lvl(&$output, $depth = 0, $args = array()) {
        $output .= "</ul>";
        if ($depth == 0) {
            $output .= "</div>";
        }
    }
    function end_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
		if ($depth == 0 && $this->column_count > 0) {
			$this->column_count = 0;
		}
        $output .= "</li>";
    }
}
*/
/***** END MEGA MENU *****/
?>
