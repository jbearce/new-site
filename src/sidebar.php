<div class="sidebar">
    <?
    if (is_page() || is_single()) {
        if (get_field("sidebar")) {
            echo "<div class='widget-card'><div class='content'>";
            echo "<div class='user-content'>";
            the_field("sidebar");
            echo "</div>";
            echo "</div></div>";
        }
    }
    if (is_single() || is_archive()) {
        echo "<div class='widget-card'>";
        $term = get_queried_object();
        if (is_category()) {
            $widget_title = single_cat_title("", false);
        } elseif (is_tag()) {
            $widget_title = single_tag_title("", false);
        } elseif (is_tax() && $term->name) {
            $widget_title = $term->name;
        } else {
            $widget_title = get_the_time("F, Y") . " Archive";
        }
        echo "<div class='title'><h6>{$widget_title}</h6></div>";
        echo "<div class='content'>";
        echo "<nav class='menu-wrapper -subnav -vertical'><ul class='menu-list'>";
        wp_list_categories("orderby=name");
        echo "</ul></nav>";
        echo "</div>";
        echo "</div>";
    }
    /*
    if (is_page()) {
        $sub_menu = wp_nav_menu(array(
            "container"		 => false,
            "depth"          => 2,
            "direct_parent"  => true,
            "echo"           => false,
            "sub_menu"		 => true,
            // "parent_id"      => $parent_id,
            "theme_location" => "primary",
        ));
        if ($sub_menu != "") {
            echo "<div class='widget-card'><div class='content'><nav class='menu-wrapper -subnav -vertical'>{$sub_menu}</nav></div></div>";
        }
    }
    */
    /*
    if (is_active_sidebar("sidebar")) {
        dynamic_sidebar("sidebar");
    }
    */
    ?>
</div><!--/.sidebar-->
