<?php
/* ------------------------------------------------------------------------ *\
 * Functions: Menus
\* ------------------------------------------------------------------------ */

// register the menus
register_nav_menus(array(
    "primary" => __("Navigation", "__gulp_init_namespace__"),
));

// menu walker
class __gulp_init_namespace___menu_walker extends Walker_Nav_Menu {
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
        $features = isset($this->params["features"]) ? $this->params["features"] : array();

        if (in_array("mega", $features) && $depth === 0 && isset($children_elements[$element->ID]) && !empty($children_elements[$element->ID])) { $i = 0;
            foreach ($children_elements[$element->ID] as $child) { $i++;
                $has_columns = get_post_meta($child->ID, "_menu_item_column_start");
                $parent_id = get_post_meta($child->ID, "_menu_item_menu_item_parent");

                if ($i > 1 && $has_columns[0] === "true" && intval($parent_id[0]) === $element->ID) {
                    array_push($element->classes, "--mega"); break;
                }
            }
        }

        return parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
    }

    public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $id_prefix = isset($this->params["id_prefix"]) ? $this->params["id_prefix"] : "";
        $features  = isset($this->params["features"]) ? $this->params["features"] : array();

        // mega menu stuff
        if (in_array("mega", $features) && $depth === 1) {
            if ($depth === 0) {
                self::$li_count = 0;
            }

            if ($depth === 1 && self::$li_count === 1) {
                $this->column_count++;
            }

            if ($depth === 1 && get_post_meta($item->ID, "_menu_item_column_start", true) && self::$li_count !== 1 && $this->column_count < $this->column_limit) {
                $output .= "</ul><ul class='menu-list --vertical --child --depth-1 --mega'>";
                $this->column_count++;
            }

            self::$li_count++;
        }

        // get the current classes
        $classes = $item->classes ? $item->classes : array();

        // add the menu-list_item class if the classes array contains menu-item
        if (in_array("menu-item", $classes) && !in_array("menu-list__item", $classes))
            $classes[] = "menu-list__item";

        // add the is-viewed class if the page is currently be viewed
        if (in_array("current_page_item", $classes) && !in_array("is-viewed", $classes))
            $classes[] = "is-viewed";

        // add the is-viewed class if the page is currently be viewed
        if (in_array("current_page_item", $classes) && !in_array("is-viewed", $classes))
            $classes[] = "is-viewed";

        // add a the -parent class if the page has children
        if (in_array("menu-item-has-children", $classes) && !in_array("--parent", $classes))
            $classes[] = "--parent";

        // convert the clean_classes array in to usable string
        $class_names = " class='" . esc_attr(join(" ", apply_filters("nav_menu_css_class", array_filter($classes), $item))) . "'";

        // set up the ID
        $id = $id_prefix . $item->ID;

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
        $uniqid           = uniqid("menu-list__description__");
        $aria_describedby = $item->description ? " aria-describedby='{$uniqid}'" : "";
        $description      = $item->description ? " <span class='menu-item__description' id='{$uniqid}'>" . htmlentities($item->description, ENT_QUOTES) . "</span>" : "";

        // construct the menu item
        $output .= sprintf(
            "<li%s id='%s'><a class='menu-list__link link' href='%s'%s%s%s%s>%s</a>%s",
            $class_names,
            $id,
            $url,
            $attr_title,
            $target,
            $xfn,
            $aria_describedby,
            $title,
            $description
        );

        // mega menu stuff
        if (in_array("mega", $features) && $depth === 0 && in_array("--mega", $classes)) {
            $this->is_mega = true;

            if ($depth === 0) {
                $output .= "<button class='menu-list__toggle __visuallyhidden'>" . __("Toggle children (mega)", "__gulp_init_namespace__") . "</button>";
                $output .= "<div class='menu-list__container --mega' aria-hidden='true'>";
            }
        }
    }

    public function start_lvl(&$output, $depth = 0, $args = array()) {
        $features = isset($this->params["features"]) ? $this->params["features"] : array();

        // add a toggle button
        $toggle = "";

        if (!$this->is_mega && (in_array("accordion", $features) || in_array("hover", $features) || in_array("touch", $features))) {
            $toggle_class = "";

            if (in_array("touch", $features) && !in_array("accordion", $features)) {
                $toggle_class = " __touch";
            }

            if (in_array("hover", $features) && !in_array("accordion", $features)) {
                $toggle_class .= " __visuallyhidden" . (in_array("touch", $features) ? " __mouse" : "");
            }

            $toggle .= "<button class='menu-list__toggle{$toggle_class}'><i class='toggle__icon far fa-angle-down'></i><span class='__visuallyhidden'>" . __("Toggle children", "__gulp_init_namespace__") . "</span></button>";
        }

        // set up empty variant class
        $variant = "";

        // add a -tier class indicting the depth
        if ($depth === 0) {
            $variant .= " --depth-1";
        } elseif ($depth === 1) {
            $variant .= " --depth-2";
        } elseif ($depth > 1) {
            $variant .= " --depth-" . ($depth + 1);
        }

        // add the appropriate variant class
        if ($this->is_mega) {
            $variant .= " --mega";
        } else {
            if (in_array("accordion", $features)) {
                $variant .= " --accordion";
            } elseif (in_array("hover", $features) || in_array("touch", $features)) {
                $variant .= " --overlay" . ($depth >= 1 ? " --flyout" : "");
            }
        }

        // set up empty data attribute
        $data = "";

        // add data properties for the menu script to interact with
        if (in_array("hover", $features) && !$this->is_mega) $data .= " data-hover='true'";
        if (in_array("touch", $features) && !$this->is_mega) $data .= " data-touch='true'";

        // set up empty aria attribute
        $aria = "";

        // add aria attribute if the mega parameter is not passed
        if (!$this->is_mega && (in_array("hover", $features) || in_array("touch", $features))) {
            $aria = " aria-hidden='true'";
        }

        // construct the menu list
        $output .= "{$toggle}<ul class='menu-list --vertical --child{$variant}'{$data}{$aria}>";
    }

    public function end_lvl(&$output, $depth = 0, $args = array()) {
        // close the menu list
        $output .= "</ul>";
    }

    public function end_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $features = isset($this->params["features"]) ? $this->params["features"] : array();

        // reset the column counter
        $this->column_count = 0;

        // mega menu stuff
        if (in_array("mega", $features) && $depth === 0) {
            // get the current classes
            $classes = $item->classes ? $item->classes : array();

            if (in_array("--mega", $classes)) {
                $this->is_mega = false;

                $output .= "</div>";
            }
        }

        // close the menu item
        $output .= "</li>";
    }
}

// add custom options to the menu editor
if (is_admin() && $pagenow === "nav-menus.php") {
    // include this so we can access Walker_Nav_Menu_Edit
    require_once ABSPATH . "wp-admin/includes/nav-menu.php";

    // Add the WordPress color picker styles & scripts
    function __gulp_init_namespace___nav_menu_color_picker() {
        wp_enqueue_style("wp-color-picker");
        wp_enqueue_script("wp-color-picker");
    }
    add_action("admin_enqueue_scripts", "__gulp_init_namespace___nav_menu_color_picker");

    class __gulp_init_namespace___create_custom_menu_options extends Walker_Nav_Menu_Edit {
        static $displayed_fields = array();

        // create an array with all the new fields
        static function get_custom_fields() {
            return array(
                array(
                    "locations"   => array("primary"),
                    "type"        => "checkbox",
                    "name"        => "column_start",
                    "label"       => __("Start a new column here", "__gulp_init_namespace__"),
                    "description" => "",
                    "scripts"     => "",
                    "styles"      => ".menu-item:not(.menu-item-depth-1) .field-column_start, .menu-item.menu-item-depth-0 + .menu-item.menu-item-depth-1 .field-column_start {display:none;}",
                    "value"       => "true",
                ),
            );
        }

        // append the new fields to the menu system
        function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
            $all_menus      = get_nav_menu_locations();
            $assigned_menus = get_the_terms($item->ID, "nav_menu");

            $custom_fields = self::get_custom_fields();

            $fields_markup = "";

            // get the menu item
            parent::start_el($item_output, $item, $depth, $args);

            // set up each new custom field
            foreach ($custom_fields as $field) {
                // if fixed locations are set, see if the menu is assigned to that location, and if not, skip the field
                if ($field["locations"]) {
                    $skip = true;

                    if ($all_menus) {
                        foreach ($field["locations"] as $location) {
                            if (isset($all_menus[$location])) {
                                foreach ($assigned_menus as $assigned_menu) {
                                    if ($assigned_menu->term_id === $all_menus[$location]) {
                                        $skip = false; continue;
                                    }
                                }
                            }

                            if ($skip === false) continue;
                        }
                    }

                    if ($skip === true) continue;
                }

                // store the displayed fields for later use
                if (!in_array($field["name"], self::$displayed_fields)) {
                    self::$displayed_fields[] = $field["name"];
                }

                // retrieve the existing value from the database
                $field["meta_value"] = get_post_meta($item->ID, "_menu_item_{$field["name"]}", true);

                $fields_markup .= "<p class='field-{$field["name"]} description description-wide'>";
                $fields_markup .= "<label for='edit-menu-item-{$field["name"]}-{$item->ID}'>";

                if ($field["type"] === "text") {
                    $fields_markup .= "{$field["label"]}<br>";
                    $fields_markup .= "<input id='edit-menu-item-{$field["name"]}-{$item->ID}' class='widefat edit-menu-item-{$field["name"]}' name='menu-item-{$field["name"]}[{$item->ID}]' value='{$field["meta_value"]}' type='text' />";
                } elseif ($field["type"] === "textarea") {
                    $fields_markup .= "{$field["label"]}<br>";
                    $fields_markup .= "<textarea id='edit-menu-item-{$field["name"]}-{$item->ID}' class='widefat edit-menu-item-{$field["name"]}' rows='3' col='20' name='menu-item-{$field["name"]}[{$item->ID}]'>{$field["meta_value"]}</textarea>";
                } elseif ($field["type"] === "select") {
                    $fields_markup .= "{$field["label"]}<br>";
                    $fields_markup .= "<select id='edit-menu-item-{$field["name"]}-{$item->ID}' class='widefat edit-menu-item-{$field["name"]}' rows='3' col='20' name='menu-item-{$field["name"]}[{$item->ID}]'" . (isset($field["multiple"]) && $field["multiple"] === true ? " multiple" : "") . ">";

                    foreach ($field["options"] as $value => $label) {
                        $fields_markup .= "<option value='{$value}'" . ($field["meta_value"] === $value ? " selected='selected'" : "") . ">{$label}</option>";
                    }

                    $fields_markup .= "</select>";
                } elseif ($field["type"] === "radio") {
                    $fields_markup .= "{$field["label"]}<br>";

                    foreach ($field["options"] as $value => $label) {
                        $fields_markup .= "<label for='edit-menu-item-{$field["name"]}-{$item->ID}-" . sanitize_title($value) . "'>";
                        $fields_markup .= "<input id='edit-menu-item-{$field["name"]}-{$item->ID}-" . sanitize_title($value) . "' name='menu-item-{$field["name"]}[{$item->ID}]' value='{$value}' type='radio'" . checked($field["meta_value"], $value, false) . " />";
                        $fields_markup .= $label;
                        $fields_markup .= "</label>&nbsp;&nbsp;";
                    }
                } elseif ($field["type"] === "checkbox") {
                    $fields_markup .= "<input id='edit-menu-item-{$field["name"]}-{$item->ID}' name='menu-item-{$field["name"]}[{$item->ID}]' value='{$field["value"]}' type='checkbox'" . checked($field["meta_value"], $field["value"], false) . " />";
                    $fields_markup .= $field["label"];
                } elseif ($field["type"] === "color") {
                    $fields_markup .= "{$field["label"]}<br>";
                    $fields_markup .= "<span><input id='edit-menu-item-{$field["name"]}-{$item->ID}' class='widefat edit-menu-item-{$field["name"]} __gulp_init_namespace__-color-picker' name='menu-item-{$field["name"]}[{$item->ID}]' value='{$field["meta_value"]}' type='text' /></span>";
                }

                if ($field["description"]) {
                    if (in_array($field["type"], array("radio", "checkbox"))) {
                        $fields_markup .= "<br>";
                    }

                    $fields_markup .= "<span class='description'>{$field["description"]}</span>";
                }

                $fields_markup .= "</label>";
                $fields_markup .= "</p>";
            }

            // insert the new markup before the fieldset tag
            $item_output = preg_replace("/(<fieldset)/", "{$fields_markup}$1", $item_output);

            // update the output
            $output .= $item_output;
        }

        // save the new fields
        static function save_field_data($post_id) {
            if (get_post_type($post_id) !== "nav_menu_item") return;

            $post_object   = get_post($post_id);
            $custom_fields = self::get_custom_fields();

            foreach ($custom_fields as $field) {
                $POST_key = "menu-item-{$field["name"]}";
                $meta_key = "_menu_item_{$field["name"]}";

                $field["value"] = isset($_POST[$POST_key][$post_id]) ? sanitize_text_field($_POST[$POST_key][$post_id]) : "";

                // validate the color picker
                if ($field["type"] === "color" && $field["value"] !== "" && !preg_match("/^#[a-f0-9]{6}$/i", $field["value"])) {
                    $field["value"] = "";

                    add_action("admin_notices", function () use ($post_object) {
                        echo "<div class='notice notice-error'><p>" . sprintf(__("Invalid HEX color code entered for '%s' [%s].", "__gulp_init_namespace__"), $post_object->post_title, $post_object->ID) . "</p></div>";
                    });
                }

                update_post_meta($post_id, $meta_key, $field["value"]);
            }
        }

        // add the save function to the save_post action
        static function setup_custom_fields() {
            add_action("save_post", array(__CLASS__, "save_field_data"));
        }

        // insert field custom scripts in to the admin footer
        static function insert_custom_scripts() {
            $custom_fields = self::get_custom_fields();

            foreach ($custom_fields as $field) {
                if ($field["scripts"] && in_array($field["name"], self::$displayed_fields)) {
                    echo "<script>{$field["scripts"]}</script>";
                }
            }
        }

        // insert field custom styles in to the admin header
        static function insert_custom_styles() {
            $custom_fields = self::get_custom_fields();

            foreach ($custom_fields as $field) {
                if ($field["styles"] && in_array($field["name"], self::$displayed_fields)) {
                    echo "<style>{$field["styles"]}</style>";
                }
            }
        }

        // insert the screen options
        static function insert_custom_screen_options($args) {
            $custom_fields = self::get_custom_fields();

            foreach ($custom_fields as $field) {
                if (in_array($field["name"], self::$displayed_fields)) {
                    $args[$field["name"]] = $field["label"];
                }
            }

            return $args;
        }
    }
    add_action("init", array("__gulp_init_namespace___create_custom_menu_options", "setup_custom_fields"));
    add_filter("wp_edit_nav_menu_walker", function () { return "__gulp_init_namespace___create_custom_menu_options"; });
    add_action("admin_footer", array("__gulp_init_namespace___create_custom_menu_options", "insert_custom_scripts"));
    add_action("admin_head", array("__gulp_init_namespace___create_custom_menu_options", "insert_custom_styles"));
    add_filter("manage_nav-menus_columns", array("__gulp_init_namespace___create_custom_menu_options", "insert_custom_screen_options"), 20);
}

// add sub_menu options to wp_nav_menu
// @param  direct_parent  {true|false}
// @param  parent_id      {int}
// @param  show_parent    {true|false}
// @param  sub_menu       {true|false}
// @param  tree_mode      {"all"|"related"|"viewed"}
function __gulp_init_namespace___nav_menu_sub_menu($menu_items, $args) {
    $root_item_id = 0;
    $post_id_map  = array();
    $loop_limit   = 1000;

    // store the arguments in an easy to reference way
    $settings = array(
        "direct_parent" => isset($args->direct_parent) && $args->direct_parent === true ? true : false,
        "parent_id"     => isset($args->parent_id) && $args->parent_id !== false ? (int) $args->parent_id : false,
        "show_parent"   => isset($args->show_parent) && $args->show_parent === true ? true : false,
        "sub_menu"      => isset($args->sub_menu) && $args->sub_menu === true ? true : false,
        "tree_mode"     => isset($args->tree_mode) && in_array($args->tree_mode, array("all", "related", "viewed")) ? $args->tree_mode : "all",
    );

    // check if the submenu argument is set and is true
    if ($settings["sub_menu"]) {
        // create an array containing menu_item_id => post_id
        foreach ($menu_items as $menu_item) {
            $post_id_map[$menu_item->ID] = (int) $menu_item->object_id;
        }

        // determine the root_item_id
        foreach ($menu_items as $menu_item) {
            // if a parent ID is set, just use that
            if ($settings["parent_id"]) {
                if ($post_id_map[$menu_item->ID] === $settings["parent_id"]) {
                    $root_item_id = $menu_item->ID; break;
                }
            } elseif ($menu_item->current) {
                $root_item_id = $menu_item->menu_item_parent ? $menu_item->menu_item_parent : $menu_item->ID; break;
            }
        }

        // if tree_mode is not all, remove any menu_items that aren't in the viewed tree
        if ($settings["tree_mode"] !== "all" || !$settings["show_parent"] || $settings["direct_parent"]) {
            $viewed_ancestor_ids   = array();
            $viewed_descendant_ids = array();
            $viewed_related_ids    = array();
            $viewed_item_id        = 0;

            // find the viewed item
            foreach ($menu_items as $menu_item) {
                if (
                    // if parent_id is false and it's the current menu_item or...
                    (!$settings["parent_id"] && $menu_item->current) ||
                    // if parent_id is true and the current menu_item->ID matches the parent_id
                    ($settings["parent_id"] && $post_id_map[$menu_item->ID] === $settings["parent_id"])
                ) {
                    $viewed_item_id        = $menu_item->ID;
                    $viewed_ancestor_ids[] = $viewed_item_id;

                    // prevent 0 (root) from being added to viewed_ancestor_ids
                    if ((int) $menu_item->menu_item_parent !== 0) {
                        $viewed_ancestor_ids[] = (int) $menu_item->menu_item_parent;
                    }

                    break;
                }
            }

            // only return the top level menu items if the viewed page isn't in the menu
            if ($viewed_item_id === 0) {
                foreach ($menu_items as $key => $menu_item) {
                    if ((int) $menu_item->menu_item_parent !== 0) {
                        unset($menu_items[$key]);
                    }
                }

                return $menu_items;
            }

            $parent_item_id = $viewed_item_id;

            // build a complete list of ancestor menu_items (if direct_parent is false)
            if (!$settings["direct_parent"]) { $i = 0;
                // continue looping until we hit the top
                while ($parent_item_id !== 0 && $i < $loop_limit) { $i++;
                    foreach ($menu_items as $menu_item) {
                        // find the menu_item currently set as the parent_item_id
                        if ($menu_item->ID === $parent_item_id) {
                            $parent_item_id = (int) $menu_item->menu_item_parent;

                            // prevent 0 (root) from being added to viewed_ancestor_ids
                            if ($parent_item_id === 0) break;

                            // add the parent item id to viewed_ancestor_ids and break the loop
                            if (!in_array($parent_item_id, $viewed_ancestor_ids)) {
                                $viewed_ancestor_ids[] =  $parent_item_id;
                            }

                            break;
                        }
                    }
                }
            }

            // build a complete list of decendant menu_items
            $parent_item_id          = $viewed_item_id;
            $viewed_descendant_ids[] = $parent_item_id;

            foreach ($menu_items as $menu_item) {
                // if the menu items parent is a descendant, the menu item is also a descendant
                if (in_array($menu_item->menu_item_parent, $viewed_descendant_ids) && !in_array($menu_item->ID, $viewed_descendant_ids)) {
                    $viewed_descendant_ids[] = $menu_item->ID;
                }

                // if the menu items parent is an ancestor, the menu item is related
                if (in_array($menu_item->menu_item_parent, $viewed_ancestor_ids) && !in_array($menu_item->ID, $viewed_related_ids)) {
                    $viewed_related_ids[] = $menu_item->ID;
                }

                // if the menu items parent is related, the menu item is also related
                if (in_array($menu_item->menu_item_parent, $viewed_related_ids) && !in_array($menu_item->ID, $viewed_related_ids)) {
                    $viewed_related_ids[] = $menu_item->ID;
                }
            }

            if ($settings["tree_mode"] !== "all") {
                // remove any menu_items that aren't ancestors or descendants of the viewed page
                foreach ($menu_items as $key => $menu_item) {
                    if (
                        // if the menu_item is the currently viewed page
                        $menu_item->ID !== $viewed_item_id &&
                        // if the menu_item->ID is NOT in viewed_ancestor_ids
                        !in_array((int) $menu_item->ID, $viewed_ancestor_ids) &&
                        // if the menu_item_parent is NOT in viewed_ancestor_ids
                        !in_array((int) $menu_item->ID, $viewed_descendant_ids) &&
                        // if the menu_item->ID is NOT in $viewed_descendant_ids
                        !in_array((int) $menu_item->menu_item_parent, $viewed_ancestor_ids) &&
                        // if the menu_item_parent is NOT in $viewed_descendant_ids
                        !in_array((int) $menu_item->menu_item_parent, $viewed_descendant_ids) &&
                        // if the tree_mode is NOT "related" and menu_item->ID is NOT in $viewed_related_ids
                        !($settings["tree_mode"] === "related" && in_array((int) $menu_item->ID, $viewed_related_ids))
                    ) {
                        unset($menu_items[$key]);
                    }
                }
            }

            // if show_parent is false unset the parent menu_item
            if (!$settings["show_parent"] && $parent_item_id) {
                // edge case where the parent id item got reset to the viewed item id
                if ($parent_item_id === $viewed_item_id) {
                    foreach ($menu_items as $key => $menu_item) {
                        if ($menu_item->ID === $viewed_item_id) {
                            $parent_item_id = $menu_item->menu_item_parent;
                            break;
                        }
                    }
                }

                foreach ($menu_items as $key => $menu_item) {
                    if ($menu_item->ID === $parent_item_id) {
                        unset($menu_items[$key]);
                    }
                }
            }
        }
    } // if (isset($args->sub_menu))

    return $menu_items;
}
add_filter("wp_nav_menu_objects", "__gulp_init_namespace___nav_menu_sub_menu", 10, 2);
