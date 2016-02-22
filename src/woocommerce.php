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
                        <article class="article-card">
                            <? woocommerce_content(); ?>
                        </article>
                    </div><!--/.post-->
				    <? get_sidebar(); ?>
                </main><!--/.content-block-->
            </div><!--/.content-wrapper-->
<? get_footer(); ?>
