<? get_header(); ?>
            <div class="content-wrapper">
                <main class="content">
                    <div class="content-post">
                        <?
                        if (is_attachment()) {
                            echo "<article>";
                        }
                        if (has_post_thumbnail($id)) {
                            echo "<figure class='featured-image'>" . get_the_post_thumbnail($id, "large") . "</figure>";
                        }
                        the_title("<header><h1 class='hdg hdg1'>", "</h1></header>");
                        if (have_posts()) {
                            while (have_posts()) {
                                the_post();
                                echo "<div class='user-content'>";
                                the_content();
                                echo "</div>";
                                if (comments_open() || get_comments_number() > 0) {
                                    echo "<footer>";
                                    comments_template();
                                    echo "</footer>";
                                }
                            }
                        }
                        if (is_attachment()) {
                            echo "</article>";
                        }
                        ?>
                    </div><!--/.content-post-->
                </main><!--/.content-->
            </div><!--/.content-wrapper-->
<? get_footer(); ?>
