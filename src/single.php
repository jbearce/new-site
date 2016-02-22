<? get_header(); ?>
            <div class="content-wrapper">
                <main class="content-block">
                    <div class="post">
                        <?
                        // display breadcrumbs
                        if (function_exists("yoast_breadcrumb")) {
                            yoast_breadcrumb("<nav class='breadcrumb-list'><p class='text'>", "</p></nav>");
                        }
                        ?>
                        <?
                        if (have_posts()) {
                            while (have_posts()) {
                                the_post();

                                // open an article card
                                echo "<article class='article-card'>"

                                // open a header
                                echo "<header class='header'>";

                                // display the title
                                the_title("<h1 class='title'>", "</h1>");

                                // display the meta information
                                if (get_post_type() == "post") {
                                    echo "<nav class='menu-wrapper -icons'><ul class='menu-list'>";
                                    echo "<li class='menu-item'><a href='" . get_the_permalink() . "'><i class='fa fa-clock-o'></i> " . get_the_date() . "</a></li>";
                                    if (get_the_category_list()) {
                                        echo "<li class='menu-item'><i class='fa fa-fodler'></i> " . get_the_category_list(", ") . "</li>";
                                    }
                                    the_tags("<li class='menu-item'><i class='fa fa-tags'></i> ", ", ", "</li>");
                                    if (comments_open() || get_comments_number() > 0) {
                                        echo "<li class='menu-item'>";
                                        comments_popup_link("<i class='fa fa-comment-o'></i> No Comments", "<i class='fa fa-comment'></i> 1 Comment", "<i class='fa fa-comments'></i> % Comments");
                                        echo "</li>";
                                    }
                                    echo "</ul></nav>";
                                }

                                // close the header
                                echo "</header>";

                                // display the featured image
                                if (has_post_thumbnail()) {
                                    echo "<figure class='image'>" . get_the_post_thumbnail($post->ID, "large") . "</figure>";
                                }

                                // display the content
                                echo "<div class='content'><div class='user-content'>";
                                the_content();
                                echo "</div></div>";

                                // display the comemnts
                                if (comments_open() || get_comments_number() > 0) {
                                    comments_template();
                                }

                                // close the article card
                                echo "</article>";
                            }
                        }
                        ?>
                    </div><!--/.post-->
                    <? get_sidebar(); ?>
                </main><!--/.content-block-->
            </div><!--/.content-wrapper-->
<? get_footer(); ?>
