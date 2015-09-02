<div class="sidebar">
    <?
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
        echo "<div class='widget'><div class='widget-content'><nav class='menu-wrapper'>{$sub_menu}</nav></div></div>";
    }
    ?>
	<div class="widget">
		<header class="widget-header">
			<h6>Search Our Site</h6>
		</header><!--/.widget-header-->
        <div class="widget-content">
		  <? get_search_form(); ?>
        </div><!--/.widget-content-->
	</div><!--/.widget-->
	<?
	if (!is_home()) {
		$temp_query = $wp_query;
		$recent_posts = new WP_Query(array(
			"order"			 => "DESC",
			"orderby"		 => "title",
			"posts_per_page" => 5,
			"post_type"		 => "post",
		));
		if ($recent_posts->have_posts()) {
			echo "<div class='widget'>";
			if (get_option("show_on_front") == "page") {
				$page_for_posts = get_permalink(get_option("page_for_posts"));
			} else {
				$page_for_posts = home_url();
			}
			echo "<header class='widget-header'><h6>Recent Posts</h6></header>";
            echo "<div class='widget-content'>";
			while ($recent_posts->have_posts()) {
				$recent_posts->the_post();
				echo "<article>";
				echo "<header><h6><a href='" . get_the_permalink() . "'>" . get_the_title() . "</a></h6></header>";
				the_excerpt();
				echo "<p><a class='button' href='" . get_the_permalink() . "'>Read More</a></p>";
				echo "</article>";
			}
            echo "</div>";
            echo "<footer class='widget-footer'><p><a href='{$page_for_posts}'>View All Posts</a></p></footer>";
			echo "</div>";
		}
		wp_reset_postdata();
		$wp_query = $temp_query;
	}
	?>
	<?
	$temp_query = $wp_query;
	$upcoming_events = new WP_Query(array(
		"eventDisplay"	 => "future",
        "meta_key"		 => "_EventStartDate",
        "order"			 => "DESC",
        "orderby"		 => "_EventStartDate",
        "posts_per_page" => 3,
        "post_status"	 => "publish",
        "post_type"		 => "tribe_events",
		/*
		"tax_query"		 => array(array(
			"field"	   => "slug",
			"operator" => "IN",
			"taxonomy" => "tribe_events_cat",
			"terms"	   => "featured",
		));
		*/
	));
	if ($upcoming_events->have_posts()) {
		echo "<div class='widget'>";
		echo "<header class='widget-title'><h6>Upcoming Events</h6></header>";
        echo "<div class='widget-content'>";
		while ($upcoming_events->have_posts()) {
			$upcoming_events->the_post();
			echo "<article>";
			echo "<header>";
	 		echo "<h6><a href='" . get_the_permalink() . "'>". get_the_title() . "</a></h6>";
			if (tribe_get_start_date() !== tribe_get_end_date()) {
				echo "<p><time>" . tribe_get_start_date() . "</time> - <time>" . tribe_get_end_date() . "</time></p>";
			} else {
				echo "<p><time>" . tribe_get_start_date() . "</time></p>";
			}
			echo "</header>";
			the_excerpt();
			echo "<p><a class='button' href='" . get_the_permalink() . "'>Read More</a></p>";
			echo "</article>";
		}
        echo "</div>";
        echo "<footer class='widget-footer'><p><a class='button' href='" . tribe_get_events_link() . "View All Events</a></p></footer>";
		echo "</div>";
	}
	wp_reset_postdata();
	$wp_query = $temp_query;
	?>
	<?
	if (is_active_sidebar("sidebar")) {
		dynamic_sidebar("sidebar");
	}
	?>
</div><!--/.sidebar-->
