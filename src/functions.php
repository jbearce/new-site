<?php
// WIP

// register styles & scripts
function new_site_register_scripts() {
    wp_register_style("new_site-font-awesome", "//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css", array(), "4.5.0");
    wp_register_style("new_site-open-sans", "//fonts.googleapis.com/css?family=Open+Sans:400,400italic,700,700italic");
    wp_register_style("new_site-modern", get_bloginfo("template_directory") . "/assets/styles/modern.css"@@if (context.version) {, array(), "@@version"});
    wp_register_script("new_site-scripts", get_bloginfo("template_directory") . "/assets/scripts/all.js", array("jquery")@@if (context.version) {, "@@version"}@@if (!context.version) {, false}, true);
}
add_action("init", "new_site_register_scripts");

// enqueue styles & scripts
function new_site_enqueue_scripts() {
    wp_enqueue_style("new_site-font-awesome");
    wp_enqueue_style("new_site-open-sans");
    wp_enqueue_style("new_site-modern");
    wp_enqueue_script("new_site-scripts");
}
add_action("wp_enqueue_scripts", "new_site_enqueue_scripts");

// register the menus
register_nav_menus(array(
	"primary" => "Navigation",
));

// menu walker
class new_site_walker extends Walker_Nav_Menu {
    // set up a variable to hold the parameters passed to the walker
    private $params;

    // store the paramters in an accessible way
    public function __construct($params = "") {
        $this->params = $params;
    }

    public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
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
        $description = $item->description ? " <span class='menu-item_description'>" . htmlentities($item->description, ENT_QUOTES) . "</span>" : "";

        // construct the menu item
        $output .= sprintf(
            "<li%s><a class='menu-list_link' href='%s'%s%s%s>%s</a>",
            $class_names,
            $url,
            $attr_title,
            $target,
            $xfn,
            $title,
            $description
        );
    }

    public function start_lvl(&$output, $depth = 0, $args = array()) {
        // convert the params in to an array
        $params = explode(" ", $this->params);

        // add a toggle button if the buttons paramater is passed
        $toggle = in_array("accordion", $params) ? "<button class='menu-list_toggle'><i class='fa fa-chevron-down'></i><span class='_visuallyhidden'>" . __("Click to toggle children", "new_site") . "</span></button>" : "<button class='menu-list_toggle _visuallyhidden'>" . __("Click to toggle children", "new_site") . "</button>";

        // add a -tier class indicting the depth
        $variant = "-tier1";

        if ($depth > 0) {
            if ($depth > 1) {
                $variant = "-tier" . ($depth + 1);
            } else {
                $variant = "-tier2";
            }
        }

        // add a -accordion class if the accordion parameter is passed
        $variant .= in_array("accordion", $params) ? " -accordion" : " -overlay";

        // construct the menu list
        $output .= "{$toggle}<ul class='menu-list -vertical -child {$variant}' aria-hidden='true'>";
    }

    public function end_lvl(&$output, $depth = 0, $args = array()) {
        // close the menu list
        $output .= "</ul>";
    }

    public function end_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        // close the menu item
        $output .= "</li>";
    }
}
