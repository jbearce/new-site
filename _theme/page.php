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
                                the_title("<header><h1>", "</h1></header>");
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
