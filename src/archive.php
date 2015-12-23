<? get_header(); ?>
            <div class="content-wrapper">
                <main class="content">
                    <div class="content-post">
                        <?
                        if (function_exists("yoast_breadcrumb")) {
                            yoast_breadcrumb("<nav class='breadcrumb'><p>", "</p></nav>");
                        }
                        ?>
                        <header>
                            <?
                            $term = get_queried_object();
                            if (is_category()) {
                                echo "<h1>" . single_cat_title("", false) . "</h1>";
                            } elseif (is_tag()) {
                                echo "<h1>" . single_tag_title("", false) . "</h1>";
                            } elseif (is_tax() && $term->name) {
                                echo "<h1>" . $term->name . "</h1>";
                            } else {
                                echo "<h1>" . get_the_time("F, Y") . " Archive</h1>";
                            }
                            ?>
                            <?
                            if ($term->description) {
                                echo "<div class='user-content'>" . wpautop($term->description) . "</div>";
                            }
                            ?>
                        </header>
                        <?
                        if (have_posts()) {
                            while (have_posts()) {
                                the_post();
                                echo "<article class='mini-article'>";
                                if (has_post_thumbnail()) {
                                    echo "<figure class='mini-article-image'><a href='" . get_permalink() . "'>" . get_the_post_thumbnail($post->ID, "medium") . "</a></figure>";
                                    echo "<div class='mini-article-content'>";
                                }
                                echo "<header>";
                                echo "<h2><a href='" . get_permalink() . "'>" . get_the_title() . "</a></h2>";
                                if (get_post_type() == "post") {
                                    echo "<ul class='meta-list'>";
                                    echo "<li class='time'><a href='" . get_the_permalink() . "'>" . get_the_date() . "</a></li>";
                                    if (get_the_category_list()) {
                                        echo "<li class='categories'>" . get_the_category_list(", ") . "</li>";
                                    }
                                    the_tags("<li class='tags'>", ", ", "</li>");
                                    if (comments_open() || get_comments_number() > 0) {
                                        echo "<li class='comments'>";
                                        comments_popup_link("No Comments", "1 Comment", "% Comments");
                                        echo "</li>";
                                    }
                                    echo "</ul>";
                                }
                                echo "</header>";
                                echo "<div class='user-content'>";
                                the_excerpt();
                                echo "</div>";
                                if (has_post_thumbnail()) {
                                    echo "</div>";
                                }
                                echo "</article>";

                            }
                        }
                        ?>
                        <?
                        if (get_adjacent_post(false, "", false) || get_adjacent_post(false, "", true)) {
                            echo "<footer><p style='overflow:hidden;'>";
                            if (get_adjacent_post(false, "", false)) {
                                previous_posts_link("<span style='float:left;'>&larr; Previous Page</span>");
                            }
                            if (get_adjacent_post(false, "", true)) {
                                next_posts_link("<span style='float:right;'>Next Page &rarr;</span>");
                            }
                            echo "</p></footer>";
                        }
                        ?>
                        <?
                        if (is_front_page() || is_attachment()) {
                            echo "</article>";
                        }
                        ?>
                    </div><!--/.content-post-->
                    <? get_sidebar(); ?>
                </main><!--/.content-->
            </div><!--/.content-wrapper-->
<? get_footer(); ?>
