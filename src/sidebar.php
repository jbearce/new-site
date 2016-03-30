<div class="sidebar">
    <?php
    if (is_page() || is_single()) {
        // get the sidebar field
        $sidebar = get_field("sidebar");

        // display the sidebar field
        if ($sidebar) {
            echo "<div class='widget-card'><div class='content'><div class='user-content'>{$sidebar}</div></div></div>";
        }
    }
    if (is_single() || is_archive()) {
        // get the term
        $term = get_queried_object();

        // open a widget card
        echo "<div class='widget-card'>";

        // get the title
        $widget_title = get_the_time("F, Y") . " " . __("Archive", "new-site");
        if (is_category()) {
            $widget_title = single_cat_title("", false);
        } elseif (is_tag()) {
            $widget_title = single_tag_title("", false);
        } elseif (is_tax() && $term->name) {
            $widget_title = $term->name;
        }

        // display the title
        echo "<div class='title'><h6>{$widget_title}</h6></div>";

        // display the c ategory list
        echo "<div class='content'><nav class='menu-wrapper -subnav -vertical'><ul class='menu-list'>";
        wp_list_categories("orderby=name");
        echo "</ul></nav></div>";

        // closethe widget card
        echo "</div>";
    }
    /*
    if (is_page()) {
        // get the submenu
        $sub_menu = wp_nav_menu(array(
            "container"		 => false,
            "depth"          => 2,
            "direct_parent"  => true,
            "echo"           => false,
            "items_wrap"	 => "<nav class='menu-wrapper -subnav -vertical'><ul class='menu-list'>%3\$s</ul></nav>",
            "show_parent"    => true,
            "sub_menu"		 => true,
            "theme_location" => "primary",
            "walker"         => new SMACSSwalker(),
        ));

        // display the submenu
        if ($sub_menu != "") {
            echo "<div class='widget-card'><div class='widget-card-content'>{$sub_menu}</div></div>";
        }
    }
    */
    /*
    // display the widgetized sidebar
    if (is_active_sidebar("sidebar")) {
        dynamic_sidebar("sidebar");
    }
    */
    ?>
</div><!--/.sidebar-->
