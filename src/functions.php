<?
// enable featured images
add_theme_support("post-thumbnails");

// enable HTML5
add_theme_support("html5");

// enable jquery
function weblinx_force_jquery() {
    wp_enqueue_script("jquery");
}
add_action("wp_enqueue_scripts", "weblinx_force_jquery");

// enable menus
function register_menus() {
	register_nav_menus(array(
		"primary" => "Navigation",
	));
}
add_action("init", "register_menus");

// add slideshow image size
add_image_size("slideshow", 1600, 900, true);

// SMACSS walker
class SMACSSwalker extends Walker_Nav_Menu {
    static $li_count = 0;
    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $item_id = $item->ID;
        if ($depth == 0) {
			self::$li_count = 0;
		}
        $class_names = join(" ", apply_filters("nav_menu_css_class", array_filter($classes), $item));
        $class_names = " class='" . esc_attr($class_names) . "'";
        $target = "";
        if ($item->target) {
            $target = " target='_blank'";
        }
        $output .= sprintf(
            "<li id='menu-item-%s'%s><a href='%s'%s>%s</a>",
            $item_id,
            $class_names,
            $item->url,
            $target,
            $item->title
        );
        self::$li_count++;
    }
    function start_lvl(&$output, $depth = 0, $args = array()) {
        $output .= "<ul class='menu-list sub-menu'>";
    }
    function end_lvl(&$output, $depth = 0, $args = array()) {
        $output .= "</ul>";
    }
    function end_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $output .= "</li>";
    }
}

// mobile smacss walker
class mobileSMACSSwalker extends Walker_Nav_Menu {
    static $li_count = 0;
    function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $item_id = $item->ID;
        if ($depth == 0) {
			self::$li_count = 0;
		}
        $class_names = join(" ", apply_filters("nav_menu_css_class", array_filter($classes), $item));
        $class_names = " class='" . esc_attr($class_names) . "'";
        $target = "";
        if ($item->target) {
            $target = " target='_blank'";
        }
        $output .= sprintf(
            "<li id='menu-item-%s'%s><a href='%s'%s>%s</a>",
            $item_id,
            $class_names,
            $item->url,
            $target,
            $item->title
        );
        self::$li_count++;
    }
    function start_lvl(&$output, $depth = 0, $args = array()) {
        $output .= "<button class='menu-toggle'>More</button>";
        $output .= "<ul class='menu-list sub-menu'>";
    }
    function end_lvl(&$output, $depth = 0, $args = array()) {
        $output .= "</ul>";
    }
    function end_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $output .= "</li>";
    }
}

// hide acf from menu
/*
function remove_acf_menu() {
    // provide a list of usernames who can edit custom field definitions here
    $admins = array();
    // get the current user
    $current_user = wp_get_current_user();
    // match and remove if needed
    if (!in_array($current_user->user_login, $admins)) {
        remove_menu_page("edit.php?post_type=acf");
    }
}
add_action("admin_menu", "remove_acf_menu", 999);
*/

// enable editor style
/*
function weblinx_add_editor_styles() {
    add_editor_style("assets/css/text.css");
}
add_action("after_setup_theme", "weblinx_add_editor_styles");
*/

// enable sidebar
/*
if (function_exists("register_sidebar")) {
	register_sidebar(array(
		"id"			=> "sidebar",
		"name" 			=> "Sidebar",
		"before_widget" => "<div class='widget'>",
		"before_title" 	=> "<header class='widget-header'><h6 class='widget-title'>",
		"after_title" 	=> "</h6></header>",
		"after_widget" 	=> "</div>",
	));
}

// add sub_menu options to wp_nav_menu
/*
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
*/

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
            $output .= "</ul><ul class='sub-menu'>";
			$this->column_count++;
        }
        $class_names = join(" ", apply_filters("nav_menu_css_class", array_filter($classes), $item)); // set up the classes array to be added as classes to each li
        $class_names = " class='" . esc_attr($class_names) . "'";
        $target = "";
        if ($item->target) {
            $target = " target='_blank'";
        }
        $output .= sprintf(
            "<li id='menu-item-%s'%s><a href='%s'%s>%s</a>",
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
			$output .= "<section>";
        }
        $output .= "<ul class='sub-menu'>";
    }
    function end_lvl(&$output, $depth = 0, $args = array()) {
        $output .= "</ul>";
        if ($depth == 0) {
            $output .= "</section>";
        }
    }
    function end_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
		if ($depth == 0 && $this->column_count > 0) {
			$this->column_count = 0;
		}
        $output .= "</li>";
    }
}

// add mega-menu-columns-# classes
function add_column_number($items) {
	static $column_limit = 3;
    static $post_id = 0;
    static $x_key = 0;
    static $column_count = 0;
    static $li_count = 0;
    $tmp = array();
    foreach($items as $key => $item) {
        if (0 == $item->menu_item_parent) {
            $x_key = $key;
            $post_id = $item->ID;
            $column_count = 0;
            $li_count = 0;
        }
        if ($post_id == $item->menu_item_parent) {
        	$li_count++;
			if ($column_count < $column_limit && $li_count == 1) {
				$column_count++;
			}
            if (in_array("break", $item->classes, 1) && $li_count > 1 && $column_count < $column_limit) {
                $column_count++;
			}
            $tmp[$x_key] = $column_count;
        }
    }
    foreach($tmp as $key => $value) {
        $items[$key]->classes[] = sprintf("mega-menu-columns-%d", $value);
    }
    unset($tmp);
    return $items;
};

// add the column classes
add_filter("wp_nav_menu_args", function($args) {
    if ($args["walker"] instanceof megaMenuWalker) {
        add_filter("wp_nav_menu_objects", "add_column_number");
    }
    return $args;
});

// stop the column classes function
add_filter("wp_nav_menu", function( $nav_menu ) {
    remove_filter("wp_nav_menu_objects", "add_column_number");
    return $nav_menu;
});
*/
/***** END MEGA MENU *****/
?>
