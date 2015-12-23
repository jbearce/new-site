<? get_header(); ?>
            <div class="content-wrapper">
                <main class="content">
                    <div class="content-post">
                        <?
                        if (function_exists("yoast_breadcrumb")) {
                            yoast_breadcrumb("<nav class='breadcrumb'><p>", "</p></nav>");
                        }
                        ?>
				        <? woocommerce_content(); ?>
                    </div><!--/.content-post-->
				    <? get_sidebar(); ?>
                </main><!--/.content-->
            </div><!--/.content-wrapper-->
<? get_footer(); ?>
