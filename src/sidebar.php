<div class="col -third">
    <div class="content-sidebar">
        <?php
        if (is_page() || is_single()) {
            // get the sidebar field
            $sidebar = get_field("sidebar");

            // display the sidebar field
            if ($sidebar) {
                echo "<div class='widget'><div class='widget-content user-content'>{$sidebar}</div></div>";
            }
        }
        ?>
        <?php
        if (is_single() || is_archive()) {
            // get the term
            $term = get_queried_object();

            // open a widget
            echo "<div class='widget'>";

            // get the title
            if (is_category()) {
                $widget-title = single_cat_title("", false);
            } elseif (is_tag()) {
                $widget-title = single_tag_title("", false);
            } elseif (is_tax() && $term->name) {
                $widget-title = $term->name;
            } else {
                $widget-title = get_the_time("F, Y") . " " . __("Archive", "new-site");
            }

            // display the title
            echo "<div class='widget-header'><h6 class='widget-title title'>{$widget-title}</h6></div>";

            // display the category list
            echo "<div class='widget-content'><nav class='widget-menu-container menu-container'><ul class='widget-menu-list menu-list -vertical'>";
            wp_list_categories("orderby=name");
            echo "</ul></nav></div>";

            // close the widget
            echo "</div>";
        }
        ?>
        <?php
        if (is_page()) {
            // get the submenu
            $sub_menu = wp_nav_menu(array(
                "container"		 => false,
                "depth"          => 2,
                "direct_parent"  => true,
                "echo"           => false,
                "items_wrap"	 => "<nav class='widget-menu-container menu-container'><ul class='widget-menu-list menu-list -vertical'>%3\$s</ul></nav>",
                "show_parent"    => true,
                "sub_menu"		 => true,
                "theme_location" => "primary",
                "walker"         => new weblinxWalker(),
            ));

            // display the submenu
            if ($sub_menu != "") {
                echo "<div class='widget'><div class='widget-content'>{$sub_menu}</div></div>";
            }
        }
        ?>
        <?php
        // display the widgetized sidebar
        if (is_active_sidebar("sidebar")) {
            dynamic_sidebar("sidebar");
        }
        ?>
    </div><!--/.content-sidebar-->
</div><!--/.col.-third-->
