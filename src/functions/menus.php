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
    /**
     * Set up a variable to hold the parameters passed to the walker
     */
    private $params;

    /**
     * Store parameters in a more accessible way
     */
    public function __construct($params = "") {
        $this->params = $params;
    }

    /**
     * Set up variables for mega menu features
     */
    private $is_mega      = false;
    private $column_limit = 3;
    private $column_count = 0;
    private $item_count   = 0;

    /**
     * Check if the current item contains mega menu columns
     */
    function display_element($element, &$children_elements, $max_depth, $depth = 0, $args, &$output) {
        $features = isset($this->params["features"]) ? $this->params["features"] : array();

        if (in_array("mega", $features) && $depth === 0 && isset($children_elements[$element->ID]) && !empty($children_elements[$element->ID])) { $i = 0;
            foreach ($children_elements[$element->ID] as $child) { $i++;
                /**
                 * Only check meta keys past the first item to (slightly) improve performance
                 */
                if ($i > 1) {
                    $has_columns = get_post_meta($child->ID, "_menu_item_column_start", true);
                    $parent_id   = get_post_meta($child->ID, "_menu_item_menu_item_parent", true);

                    if ($has_columns === "true" && intval($parent_id) === $element->ID) {
                        $this->is_mega = true;
                    }
                }
            }
        }

        /**
         * Pass on the element as is
         */
        return parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
    }

    /**
     * Construct a menu item
     */
    public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $id_prefix = isset($this->params["id_prefix"]) ? $this->params["id_prefix"] : "menu-item-";
        $features  = isset($this->params["features"]) ? $this->params["features"] : array();

        /**
         * Get the array of classes for the current menu item
         */
        $classes = $item->classes ? $item->classes : array();

        /**
         * Handle splitting drop downs for mega menus
         */
        if (in_array("mega", $features) && $this->is_mega) {
            /**
             * Reset the item counter if it's reached the top
             */
            if ($depth === 0) $this->item_count = 0;

            /**
             * Reset the column counter if it's the first li in a drop down
             */
            if ($depth === 1 && $this->item_count === 1) $this->column_count = 0;

            /**
             * Add the `menu-list__item--mega` class if it's the top
             */
            if ($depth === 0) $classes[] = "menu-list__item--mega";

            /**
             * Split the menu if it's a drop down, a split has been requested, it's
             * not the first li, and the column limit hasn't been reached
             */
            if ($depth === 1 && get_post_meta($item->ID, "_menu_item_column_start", true) && $this->item_count > 1 && $this->column_count < $this->column_limit) {
                $output .= "</ul><ul class='menu-list menu-list--vertical menu-list--child menu-list--depth-1 menu-list--mega'>";

                /**
                 * Increment the column counter
                 */
                $this->column_count++;
            }

            /**
             * Increment the item counter
             */
            $this->item_count++;
        }

        /**
         * Set up custom classes for "transpiling"
         */
        $custom_classes = array(
            array(
                "original" => "menu-item",
                "custom"   => "menu-list__item",
            ),
            array(
                "original" => "current_page_item",
                "custom"   => "is-viewed",
            ),
            array(
                "original" => "menu-item-has-children",
                "custom"   => "menu-list__item--parent",
            ),
        );

        /**
         * "Transpile" WordPress classes to custom classes
         */
        foreach ($custom_classes as $class) {
            if (in_array($class["original"], $classes) && !in_array($class["custom"], $classes)) {
                $classes[] = $class["custom"];
            }
        }

        /**
         * Construct a class attribute
         */
        $class_names = " class='" . esc_attr(join(" ", apply_filters("nav_menu_css_class", array_filter($classes), $item))) . "'";

        /**
         * Set up the ID in such as way as to prevent conflicts
         */
        $item_id = "{$id_prefix}{$item->ID}";

        /**
         * Construct a title attribute if specified
         */
        $attr_title = $item->attr_title ? " title='" . htmlentities($item->attr_title, ENT_QUOTES) . "'" : "";

        /**
         * Construct a target attribute if specified
         */
        $target = $item->target ? " target='{$item->target}'" : "";

        /**
         * Construct a rel attribute if specified
         */
        $xfn = $item->xfn ? " rel='" . htmlentities($item->xfn, ENT_QUOTES) . "'" : "";

        /**
         * Construct an aria-description attribute if enabled and specified
         */
        $aria = array(
            "describedby" => in_array("description", $features) && $item->description ? " aria-describedby='{$item_id}_description'" : "",
            "description" => in_array("description", $features) && $item->description ? " <span class='menu-item__description' id='{$item_id}_description'>" . htmlentities($item->description, ENT_QUOTES) . "</span>" : "",
        );

        /**
         * Construct the menu item
         */
        $output .= sprintf(
            "<li%s id='%s'><a class='menu-list__link link' href='%s'%s%s%s%s>%s</a>%s",
            $class_names,
            $item_id,
            $item->url,
            $attr_title,
            $target,
            $xfn,
            $aria["describedby"],
            $item->title,
            $aria["description"]
        );
    }

    /**
     * Construct the sub-menu ul
     */
    public function start_lvl(&$output, $depth = 0, $args = array()) {
        $features = isset($this->params["features"]) ? $this->params["features"] : array();

        /**
         * Set up a variable to contain a toggle button
         */
        $toggle = "";

        if (in_array("accordion", $features) || in_array("hover", $features) || in_array("touch", $features)) {
            $toggle_class = "";

            /**
             * Add the __visuallyhidden class if it's a hover-based menu
             */
            if (in_array("hover", $features) && !in_array("accordion", $features)) {
                $toggle_class .= " __visuallyhidden";
            }

            /**
             * Construct a toggle
             */
            $toggle .= "<button class='menu-list__toggle{$toggle_class}'><i class='toggle__icon far fa-angle-down' aria-hidden='true'></i><span class='__visuallyhidden'>" . __("Show Children", "__gulp_init_namespace__") . "</span></button>";
        }

        /**
         * Add a class based on depth
         */
        $variant = " menu-list--depth-" . ($depth + 1);

        /**
         * Add classes based on features
         */
        if ($this->is_mega) {
            $variant .= " menu-list--mega";
        } else {
            if (in_array("accordion", $features)) {
                $variant .= " menu-list--accordion";
            } elseif (in_array("hover", $features) || in_array("touch", $features)) {
                $variant .= " menu-list--overlay" . ($depth >= 1 ? " menu-list--flyout" : "");
            }
        }

        /**
         * Set up a variable to contain custom attributes
         */
        $attr = "";

        /**
         * Construct data attributes for the menu script to read
         */
        if (in_array("accordion", $features)) $attr .= " data-accordion='true'";
        if (in_array("hover", $features)) $attr .= " data-hover='true'";
        if (in_array("touch", $features)) $attr .= " data-touch='true'";

        /**
         * Construct an aria-hidden if appropriate
         */
        $attr .= in_array("hover", $features) || in_array("touch", $features) ? " aria-hidden='true'" : "";

        /**
         * Construct a container for mega menus at depth 0
         */
        if (in_array("mega", $features) && $this->is_mega && $depth === 0) {
            /**
             * Append the container to the toggle
             */
            $toggle .= "<div class='menu-list__container menu-list__container--mega'{$attr}>";

            /**
             * Reset data and aria as they should not be applied to lists within a mega menu
             */
            $attr = "";
        }

        /**
         * Custruct the ul
         */
        $output .= "{$toggle}<ul class='menu-list menu-list--vertical menu-list--child{$variant}'{$attr}>";
    }

    /**
     * Construct the closing sub-menu ul
     */
    public function end_lvl(&$output, $depth = 0, $args = array()) {
        $features = isset($this->params["features"]) ? $this->params["features"] : array();

        /**
         * Close the list
         */
        $output .= "</ul>";

        /**
         * Close the container for mega menus
         */
        if (in_array("mega", $features) && $this->is_mega && $depth === 0) {
            $output .= "</div>";

            /**
             * Reset mega menu status
             */
            $this->is_mega = false;
        }
    }

    /**
     * Construct the closing li
     */
    public function end_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        /**
         * Close the menu item
         */
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

        // get a specific custom field template
        static function get_custom_field($field, $item = null) {
            $templates = array(
                "label"         => "<p class='field-{{ field_name }} description description-wide hidden-field' data-locations='{{ field_locations }}'>" .
                                   "<label for='edit-menu-item-{{ field_name }}-{{ item_id }}'>" .
                                   "{{ field_markup }}" .
                                   "</label>" .
                                   "</p>",
                "description"   => "<span class='description'>{{ field_description }}</span>",
                "checkbox"      => "<input id='edit-menu-item-{{ field_name }}-{{ item_id }}' name='menu-item-{{ field_name }}[{{ item_id }}]' value='{{ field_value }}' type='checkbox'{{ item_checked }} />" .
                                   "{{ field_label }}",
                "color"         => "{{ field_label }}<br>" .
                                   "<span><input id='edit-menu-item-{{ field_name }}-{{ item_id }}' class='widefat edit-menu-item-{{ field_name }} __gulp_init_namespace__-color-picker' name='menu-item-{{ field_name }}[{{ item_id }}]' value='{{ item_value }}' type='text' /></span>",
                "radio"         => "{{ field_options }}",
                "radio_option"  => "<label for='edit-menu-item-{{ field_name }}-{{ item_id }}-{{ option_value_sanitized }}'>" .
                                   "<input id='edit-menu-item-{{ field_name }}-{{ item_id }}-{{ option_value_sanitized }}' name='menu-item-{{ field_name }}[{{ item_id }}]' value='{{ option_value }}' type='radio'{{ option_checked }} />" .
                                   "{{ option_label }}" .
                                   "</label>&nbsp;&nbsp;",
                "select"        => "{{ field_label }}<br>" .
                                   "<select id='edit-menu-item-{{ field_name }}-{{ item_id }}' class='widefat edit-menu-item-{{ field_name }}' rows='3' col='20' name='menu-item-{{ field_name }}[{{ item_id }}]'{{ field_multiple }}>" .
                                   "{{ field_options }}" .
                                   "</select>",
                "select_option" => "<option value='{{ option_value }}'{{ option_selected }}>{{ option_label }}</option>",
                "text"          => "{{ field_label }}<br>" .
                                   "<input id='edit-menu-item-{{ field_name }}-{{ item_id }}' class='widefat edit-menu-item-{{ field_name }}' name='menu-item-{{ field_name }}[{{ item_id }}]' value='{{ item_value }}' type='text' />",
                "textarea"      => "{{ field_label }}<br>" .
                                   "<textarea id='edit-menu-item-{{ field_name }}-{{ item_id }}' class='widefat edit-menu-item-{{ field_name }}' rows='3' col='20' name='menu-item-{{ field_name }}[{{ item_id }}]'>{{ item_value }}</textarea>",
            );

            // retrieve the existing value from the database
            $item_value = $item !== null ? get_post_meta($item->ID, "_menu_item_{$field["name"]}", true) : null;

            // duplicate the template
            $markup = $templates[$field["type"]];

            // replace shared placeholdres
            if ($item !== null) {
                $markup = str_replace("{{ item_id }}", $item->ID, $markup);
                $markup = str_replace("{{ item_value }}", $item_value, $markup);
                $markup = str_replace("{{ item_checked }}", checked($item_value, isset($field["value"]) ? $field["value"] : false, false), $markup);
            }

            $markup = str_replace("{{ field_label }}", $field["label"], $markup);
            $markup = str_replace("{{ field_name }}", $field["name"], $markup);
            $markup = str_replace("{{ field_multiple }}", (isset($field["multiple"]) && $field["multiple"] === "true" ? "multiple" : ""), $markup);
            $markup = str_replace("{{ field_value }}", isset($field["value"]) ? $field["value"] : null, $markup);

            // apply special replacements for `radio` and `select` fields
            if ($field["type"] === "radio" || $field["type"] === "select") {
                $template_option = $templates["{$field["type"]}_option"];

                $field_options = "";

                foreach ($field["options"] as $value => $label) {
                    // duplicate the template
                    $markup_option = $template_option;

                    // replace placeholders with actual values
                    $markup_option = $item !== null ? str_replace("{{ item_id }}", $item->ID, $markup_option) : $markup_option;
                    $markup_option = str_replace("{{ field_name }}", $field["name"], $markup_option);
                    $markup_option = str_replace("{{ option_label }}", $label, $markup_option);
                    $markup_option = str_replace("{{ option_value }}", $value, $markup_option);
                    $markup_option = str_replace("{{ option_value_sanitized }}", sanitize_title($value), $markup_option);
                    $markup_option = str_replace("{{ option_checked }}", checked($item_value, $value, false), $markup_option);
                    $markup_option = str_replace("{{ option_selected }}", ($item_value === $value ? " selected" : ""), $markup_option);

                    // append the option
                    $field_options .= $markup_option;
                }

                // replace the {{ field_options }} placeholder with `<option>` elements
                $markup = str_replace("{{ field_options }}", $field_options, $markup);
            }

            // apply special replacements for fields with `description` values
            if ($field["description"]) {
                // retrieve the template for descriptions
                $template_description = $templates["description"];

                // add a line break after radio or checkbox fields
                if (in_array($field["type"], array("radio", "checkbox"))) {
                    $markup .= "<br>";
                }

                // duplicate the template
                $markup_description = $template_description;

                // replace the placeholder
                $markup_description = str_replace("{{ field_description }}", $field["description"], $markup_description);

                // append to the markup
                $markup .= $markup_description;
            }

            // duplicate the label template
            $markup_label = $templates["label"];

            // replace the placeholders
            $markup_label = str_replace("{{ field_name }}", $field["name"], $markup_label);
            $markup_label = str_replace("{{ field_locations }}", json_encode($field["locations"]), $markup_label);
            $markup_label = $item !== null ? str_replace("{{ item_id }}", $item->ID, $markup_label) : $markup_label;

            // replace the final placeholder and return the value
            return str_replace("{{ field_markup }}", $markup, $markup_label);
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
                    $hidden = " hidden-field";

                    if ($all_menus) {
                        foreach ($field["locations"] as $location) {
                            if (isset($all_menus[$location])) {
                                foreach ($assigned_menus as $assigned_menu) {
                                    if ($assigned_menu->term_id === $all_menus[$location]) {
                                        $hidden = ""; break;
                                    }
                                }
                            }

                            if ($hidden === "") break;
                        }
                    }
                }

                // store the displayed fields for later use
                if ($hidden === "" && !in_array($field["name"], self::$displayed_fields)) {
                    self::$displayed_fields[] = $field["name"];
                }

                // append to the fields markup
                $fields_markup .= str_replace(" hidden-field", $hidden, self::get_custom_field($field, $item));
            }

            // insert the new markup before the fieldset tag
            $item_output = preg_replace("/(<fieldset)/", "<div class='custom-fields-container'>{$fields_markup}</div>$1", $item_output);

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

        // localize the custom fields to wp-admin.js
        static function localize_custom_fields() {
            $all_custom_fields = self::get_custom_fields();

            $l10n = array(
                "custom_fields" => array(),
            );

            foreach ($all_custom_fields as $field) {
                $l10n["custom_fields"][] = self::get_custom_field($field);
            }

            wp_localize_script("__gulp_init_namespace__-scripts-wp-admin", "l10n", $l10n);
        }

        // insert field custom scripts in to the admin footer
        static function insert_custom_scripts() {
            $custom_fields = self::get_custom_fields();

            foreach ($custom_fields as $field) {
                if ($field["scripts"]) {
                    echo "<script>{$field["scripts"]}</script>";
                }
            }
        }

        // insert field custom styles in to the admin header
        static function insert_custom_styles() {
            $custom_fields = self::get_custom_fields();

            foreach ($custom_fields as $field) {
                if ($field["styles"]) {
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
    add_action("admin_footer", array("__gulp_init_namespace___create_custom_menu_options", "localize_custom_fields"));
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
