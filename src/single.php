<? get_header(); ?>
            <div class="content-wrapper">
                <main class="content">
                    <div class="post">
                        <?
                        if (have_posts()) {
                            while (have_posts()) {
                                the_post();
                                echo "<article>";
                                if (has_post_thumbnail($id)) {
                                    echo "<figure>" . get_the_post_thumbnail($id, "large") . "</figure>";
                                }
                                echo "<header>";
                                the_title("<h1>", "</h1>");
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
                                echo "</header>";
                                the_content();
                                if (comments_open() || get_comments_number() > 0) {
                                    echo "<footer>";
                                    comments_template();
                                    echo "</footer>";
                                }
                                echo "</article>";
                            }
                        }
                        ?>
                    </div><!--/.post-->
                    <? get_sidebar(); ?>
                </main><!--/.content-->
            </div><!--/.content-wrapper-->
<? get_footer(); ?>
