<? get_header(); ?>
            <div class="content-wrapper">
                <main class="content">
                    <div class="post">
                        <?
                        if (is_attachment()) {
                            echo "<article>";
                        }
                        ?>
                        <?
                        if (has_post_thumbnail($id)) {
                            echo "<figure>" . get_the_post_thumbnail($id, "large") . "</figure>";
                        }
                        ?>
                        <header>
                            <? the_title("<h1>", "</h1>"); ?>
                        </header>
                        <?
                        if (have_posts()) {
                            while (have_posts()) {
                                the_post();
                                the_content();
                                if (comments_open() || get_comments_number() > 0) {
                                    echo "<footer>";
                                    comments_template();
                                    echo "</footer>";
                                }
                            }
                        }
                        ?>
                        <?
                        if (is_attachment()) {
                            echo "</article>";
                        }
                        ?>
                    </div><!--/.post-->
                </main><!--/.content-->
            </div><!--/.content-wrapper-->
<? get_footer(); ?>
