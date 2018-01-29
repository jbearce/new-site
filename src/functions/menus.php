<?php
/* ------------------------------------------------------------------------ *\
 * Functions: Menus
\* ------------------------------------------------------------------------ */

// determine wether mega menu is enabled or not
define("ENABLE_MEGA_MENU", true);

// register the menus
register_nav_menus(array(
	"primary" => __("Navigation", "new_site"),
));

// menu walker
class new_site_menu_walker extends Walker_Nav_Menu {
    // set up a variable to hold the parameters passed to the walker
    private $params;

    // store the paramters in an accessible way
    public function __construct($params = "") {
        $this->params = $params;
    }

    // set up mega menu variables
    private $is_mega      = false;
	private $column_limit = 3;
	private $column_count = 0;
    static $li_count      = 0;

    function display_element ($element, &$children_elements, $max_depth, $depth = 0, $args, &$output) {
        // convert the params in to an array
        $params = explode(" ", $this->params);

        if (ENABLE_MEGA_MENU && in_array("mega", $params) && isset($children_elements[$element->ID]) && !empty($children_elements[$element->ID])) {
            $i = 0;

            foreach ($children_elements[$element->ID] as $child) {
                $has_columns = get_post_meta($child->ID, "_menu_item_column");
                $parent_id = get_post_meta($child->ID, "_menu_item_menu_item_parent");

                $i++;

                if ($i > 1) {
                    if (intval($has_columns[0]) === 1 && intval($parent_id[0]) === $element->ID) {
                        array_push($element->classes, "-mega");
                        break;
                    }
                }
            }

        }

        return parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
    }

    public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        // convert the params in to an array
        $params = explode(" ", $this->params);

        // get the current classes
        $classes = $item->classes ? $item->classes : array();

        // add the menu-list_item class if the classes array contains menu-item
        if (in_array("menu-item", $classes))
            array_push($classes, "menu-list_item");

        // add the is-viewed class if the page is currently be viewed
        if (in_array("current_page_item", $classes))
            array_push($classes, "is-viewed");

        // add the is-viewed class if the page is currently be viewed
        if (in_array("current_page_item", $classes))
            array_push($classes, "is-viewed");

        // add a the -parent class if the page has children
        if (in_array("menu-item-has-children", $classes))
            array_push($classes, "-parent");

        // convert the clean_classes array in to usable string
        $class_names = " class='" . esc_attr(join(" ", apply_filters("nav_menu_css_class", array_filter($classes), $item))) . "'";

        // retrieve the URL
        $url = $item->url;

        // retrieve and sanitize the title attribute
        $attr_title = $item->attr_title ? " title='" . htmlentities($item->attr_title, ENT_QUOTES) . "'" : "";

        // retrieve the target
        $target = $item->target ? " target='{$item->target}'" : "";

        // retrieve and sanitize the rel attribute
        $xfn = $item->xfn ? " rel='" . htmlentities($item->xfn, ENT_QUOTES) . "'" : "";

        // retrieve the title
        $title = $item->title;

        // retrieve and sanitize the description
        $uniqid           = uniqid("menu-list_description_");
        $aria_describedby = $item->description ? " aria-describedby='{$uniqid}'" : "";
        $description      = $item->description ? " <span class='menu-item_description' id='{$uniqid}'>" . htmlentities($item->description, ENT_QUOTES) . "</span>" : "";

        /* mega menu stuff */

        if (ENABLE_MEGA_MENU && in_array("mega", $params)) {
            if ($depth === 0) {
    			self::$li_count = 0;
    		}

    		if ($depth === 1 && self::$li_count === 1) {
    			$this->column_count++;
    		}

            if ($depth === 1 && get_post_meta($item->ID, "_menu_item_column", true) && self::$li_count !== 1 && $this->column_count < $this->column_limit) {
                $output .= "</ul><ul class='menu-list -vertical -child -tier1 -mega'>";
    			$this->column_count++;
            }

            self::$li_count++;
        }

        // construct the menu item
        $output .= sprintf(
            "<li%s><a class='menu-list_link link' href='%s'%s%s%s%s>%s</a>%s",
            $class_names,
            $url,
            $attr_title,
            $target,
            $xfn,
            $aria_describedby,
            $title,
            $description
        );

        /* mega menu stuff */

        if (ENABLE_MEGA_MENU && in_array("mega", $params)) {
            if (in_array("-mega", $classes)) {
                $this->is_mega = true;

                $output .= "<button class='menu-list_toggle _visuallyhidden'>" . __("Click to toggle children", "new_site") . "</button>";
                $output .= "<div class='menu-list_container -mega' aria-hidden='true'>";
            }
        }
    }

    public function start_lvl(&$output, $depth = 0, $args = array()) {
        // convert the params in to an array
        $params = explode(" ", $this->params);

        // add a toggle button
        $toggle = "";

        if (in_array("accordion", $params) && !(ENABLE_MEGA_MENU && $this->is_mega) && !in_array("touch", $params) && !in_array("hover", $params)) {
            $toggle .= "<button class='menu-list_toggle'><icon use='angle-down' /><span class='_visuallyhidden'>" . __("Toggle children", "new_site") . "</span></button>";
        }

        if (in_array("touch", $params) && !(ENABLE_MEGA_MENU && $this->is_mega) && !in_array("accordion", $params)) {
            $toggle .= "<button class='menu-list_toggle _touch'><icon use='angle-down' /><span class='_visuallyhidden'>" . __("Toggle children", "new_site") . "</span></button>";
        }

        if (in_array("hover", $params) && !(ENABLE_MEGA_MENU && $this->is_mega) && !in_array("accordion", $params)) {
            $toggle .= "<button class='menu-list_toggle _visuallyhidden" . (in_array("touch", $params) ? " _mouse" : "") . "'>" . __("Toggle children", "new_site") . "</button>";
        }

        // set up empty variant class
        $variant = "";

        // add a -tier class indicting the depth
        if ($depth === 0) {
            $variant .= "-tier1";
        } elseif ($depth === 1) {
            $variant .= "-tier2";
        } elseif ($depth > 1) {
            $variant .= "-tier2 -tier" . ($depth + 1);
        }

        // add the appropriate variant class
        if (in_array("accordion", $params) && !(ENABLE_MEGA_MENU && $this->is_mega)) {
            $variant .= " -accordion";
        } elseif ((in_array("hover", $params) || in_array("touch", $params)) && !(ENABLE_MEGA_MENU && $this->is_mega)) {
            $variant .= " -overlay";
        } elseif (ENABLE_MEGA_MENU && $this->is_mega) {
            $variant .= " -mega";
        }

        // add data properties for the menu script to interact with
        $data = "";
        if (in_array("hover", $params) && !(ENABLE_MEGA_MENU && $this->is_mega)) $data .= " data-click='true'";
        if (in_array("touch", $params) && !(ENABLE_MEGA_MENU && $this->is_mega)) $data   .= " data-touch='true'";

        // add aria attribute if the mega parameter is not passed
        $aria = (ENABLE_MEGA_MENU && $this->is_mega) ? "" : (in_array("hover", $params) || in_array("touch", $params) ? " aria-hidden='true'" : "");

        // construct the menu list
        $output .= "{$toggle}<ul class='menu-list -vertical -child {$variant}'{$data}{$aria}>";
    }

    public function end_lvl(&$output, $depth = 0, $args = array()) {
        // close the menu list
        $output .= "</ul>";
    }

    public function end_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        // convert the params in to an array
        $params = explode(" ", $this->params);

        // reset the column counter
        $this->column_count = 0;

        /* mega menu stuff */

        if (ENABLE_MEGA_MENU && in_array("mega", $params)) {
            // get the current classes
            $classes = $item->classes ? $item->classes : array();

            if (in_array("-mega", $classes)) {
                $this->is_mega = false;

                $output .= "</div>";
            }
        }

        // close the menu item
        $output .= "</li>";
    }
}

// add "Start New Column" checkboxes to the editor for a mega menu
if (ENABLE_MEGA_MENU && is_admin()) {
    // @TODO figure out how to only do this on the menu editor page
    // require nav-menu.php so we can hook Walker_Nav_Menu_Edit
    require_once ABSPATH . "wp-admin/includes/nav-menu.php";

    class new_site_mega_menu_column_checkbox_setup extends Walker_Nav_Menu_Edit {
        static $field = array("name" => "column");

        // add a new checkbox to each menu item
        function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
            $item_output = "";

            // get the parent item
            parent::start_el($item_output, $item, $depth, $args);

            self::$field["value"] = get_post_meta($item->ID, "_menu_item_" . self::$field["name"], true);
            self::$field["checked"] = "value='1' " . checked(self::$field["value"], 1, false);

            $new_field = "<p class='field-" . self::$field["name"] . " description'><label for='edit-menu-item-" . self::$field["name"] . "-{$item->ID}'>";
            $new_field .= "<input type='checkbox' id='edit-menu-item-" . self::$field["name"] . "-{$item->ID}' class='widefat code edit-menu-item-" . self::$field["name"] . "' name='menu-item-" . self::$field["name"] . "[{$item->ID}]'" . self::$field["checked"] . " />";
            $new_field .= __("Start new column here", "new_site");
            $new_field .= "</label></p>";

            $output .= preg_replace("/(?=<p[^>]+class=\"[^\"]*field-css-classes)/", $new_field, $item_output);
        }

        // function to save the new field
        static function _save_post($post_id) {
            if (get_post_type($post_id) !== "nav_menu_item") {
                return;
            }

            $form_field_name = "menu-item-" . self::$field["name"];
            $key = "_menu_item_" . self::$field["name"];
            $value = isset($_POST[$form_field_name][$post_id]) ? stripslashes($_POST[$form_field_name][$post_id]) : "";

            update_post_meta($post_id, $key, $value);
        }

        // add the save function to the save_post action
        static function setup() {
            add_action("save_post", array(__CLASS__, "_save_post"));
        }
    }
    add_action("init", array("new_site_mega_menu_column_checkbox_setup", "setup"));
    add_filter("wp_edit_nav_menu_walker", function () {
        return "new_site_mega_menu_column_checkbox_setup";
    });

    // hide the checkbox except on depth 1
    function new_site_hide_column_checkbox_except_on_depth_1() {
        $current_screen = get_current_screen();

        if ($current_screen->base === "nav-menus") {
            echo "<style>.menu-item:not(.menu-item-depth-1) .field-column, .menu-item.menu-item-depth-0 + .menu-item.menu-item-depth-1 .field-column {display:none;}</style>";
        }
    }
    add_action("admin_head", "new_site_hide_column_checkbox_except_on_depth_1");
}

// add sub_menu options to wp_nav_menu
// @param  {Boolean}  direct_parent - Set to true to show the direct parent of the currently viewed page, instead of the top level ancestor
// @param  {Boolean}  only_viewed - Set to true to show all child menus
// @param  {Number}   parent_id - Set to a Post ID to use as the parent
// @param  {Boolean}  show_parent - Set to true to show the parent menu item
// @param  {Boolean}  sub_menu - Set to true to make a menu behave as a sub menu
function new_site_nav_menu_sub_menu($menu_items, $args) {
    $root_id = 0;

    // check if the submenu argument is set and is true
    if (isset($args->sub_menu) && $args->sub_menu === true) {
        // if a parent exists, store the current item's parent as the root id, otherwise, store the current menu item instead
        foreach ($menu_items as $menu_item) {
            // find the currently active menu item, but exclude custom links for override purposes
            if ($menu_item->current && $menu_item->type !== "custom") {
                $root_id = ($menu_item->menu_item_parent) ? $menu_item->menu_item_parent : $menu_item->ID;
                break;
            }
        }

        // if direct_parent is set, show all links from the top level down, otherwise only display the closest parent
        if (!isset($args->direct_parent) || (isset($args->direct_parent) && $args->direct_parent === false)) {
            $prev_root_id = $root_id;

            while ($prev_root_id != 0) {
                foreach ($menu_items as $menu_item) {
                    if ($menu_item->ID == $prev_root_id) {
                        $prev_root_id = $menu_item->menu_item_parent;

                        // change the root id if the top of the menu has not been reached
                        $root_id = $prev_root_id != 0 ? $prev_root_id : $root_id;

                        break;
                    }
                }
            }
        } // if (!isset($args->direct_parent) || (isset($args->direct_parent) && $args->direct_parent === false))

        // if only_viewed is set, only show children of the currently viewed page, otherwise show siblings children
        if (isset($args->only_viewed) && $args->only_viewed === true) {
            $viewed_id          = 0;
            $viewed_parent_id   = 0;
            $top_parent_id      = 0;

            // get the ID of the currently viewed page and its parent
            foreach ($menu_items as $menu_item) {
                if (in_array("current-menu-item", $menu_item->classes)) {
                    $viewed_id        = $menu_item->ID;
                    $viewed_parent_id = $menu_item->menu_item_parent;
                    break;
                }
            }

            if (isset($args->parent_id) && $args->parent_id !== false) {
                // get the menu ID of the specified parent ID
                foreach ($menu_items as $menu_item) {
                    if ($menu_item->object_id == $args->parent_id) {
                        $top_parent_id = $menu_item->ID;
                    }
                }
            } else {
                // get the ID of the top-level parent of the currently viewed page
                foreach ($menu_items as $menu_item) {
                    if ($menu_item->menu_item_parent == 0) {
                        $top_parent_id = $menu_item->ID;
                        break;
                    }
                }
            }

            // remove any page that's not a child or sibling of the currently viewed page
            foreach ($menu_items as $key => $menu_item) {
                if (
                    $menu_item->menu_item_parent != $viewed_id &&
                    $menu_item->menu_item_parent != $viewed_parent_id &&
                    $menu_item->menu_item_parent != $top_parent_id &&
                    $menu_item->menu_item_parent != 0 &&
                    $menu_item->ID != $viewed_parent_id
                ) {
                    unset($menu_items[$key]);
                }
            }
        } // (!isset($args->only_viewed) || (isset($args->only_viewed) && $args->only_viewed === false))

        // display a specific section of links if parent_id is set
        if (isset($args->parent_id) && $args->parent_id !== false) {
            $parent_id          = 0;
            $menu_items_removed = false;
            $menu_items_copy    = $menu_items;

            // find the matching menu item
            foreach ($menu_items_copy as $key => $menu_item) {
                if ($menu_item->object_id == $args->parent_id) {
                    $parent_id = $menu_item->ID;
                    break;
                }
            }

            // check each menu item
            foreach ($menu_items_copy as $key => $menu_item) {
                // store the current menu item parents
                $current_menu_item_parent  = $menu_item->menu_item_parent;
                $current_menu_item_parents = array($current_menu_item_parent);

                while ($current_menu_item_parent != $parent_id && $current_menu_item_parent != 0) {
                    // loop through menu items (not menu items copy, because stuff gets removed from copy before the loop finishes!)
                    foreach ($menu_items as $key_2 => $menu_item_2) {
                        // update the current menu item parents
                        if ($current_menu_item_parent == $menu_item_2->ID) {
                            $current_menu_item_parent = $menu_item_2->menu_item_parent;
                            array_push($current_menu_item_parents, $current_menu_item_parent);
                        // stop the loop when we reach the top level
                        } elseif ($current_menu_item_parent == $parent_id || $current_menu_item_parent == 0) {
                            break;
                        }
                    }
                }

                // remove menu items that aren't children of the specified parent
                if (!in_array($parent_id, $current_menu_item_parents) && !(isset($args->show_parent) && $parent_id == $menu_item->ID)) {
                    $menu_items_removed = true;
                    unset($menu_items_copy[$key]);
                }
            } // foreach ($menu_items_copy as $key => $menu_item)

            // show a notice if no nenu items got removed
            if ($menu_items_removed === false) {
                trigger_error("No menu item with an ID matching " . $args->parent_id . " could be found. If using a post ID, try using a menu item ID instead.");
            }

            $menu_items = $menu_items_copy;
        } else { // if (isset($args->parent_id))
            $parent_ids = array();

            foreach ($menu_items as $key => $menu_item) {
                // store the top level menu item in the array
                if ($menu_item->ID == $root_id) {
                    $parent_ids[] = $menu_item->ID;
                }

                // store each subsequent level of menu items in the array
                if (in_array($menu_item->menu_item_parent, $parent_ids)) {
                    $parent_ids[] = $menu_item->ID;
                // remove the menu item if it's not a child
                } else if (
                    !(
                        isset($args->show_parent) &&
                        $args->show_parent === true &&
                        in_array($menu_item->ID, $parent_ids)
                    )
                ) {
                    unset($menu_items[$key]);
                }
            }
        } // if (isset($args->parent_id)) else
    } // if (isset($args->sub_menu))

    return $menu_items;
}
add_filter("wp_nav_menu_objects", "new_site_nav_menu_sub_menu", 10, 2);
