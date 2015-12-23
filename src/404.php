<? get_header(); ?>
            <div class="content-wrapper">
                <main class="content">
                    <div class="content-post">
                        <?
                        if (function_exists("yoast_breadcrumb")) {
                            yoast_breadcrumb("<nav class='breadcrumb'><p>", "</p></nav>");
                        }
                        ?>
                        <article>
                            <header>
                                <h1>404: Page Not Found</h1>
                            </header>
                            <div class="user-content">
                                <p>This page could not be found. It may have been moved or deleted.</p>
                            </div><!--/.user-content-->
                        </article>
                    </div><!--/.content-post-->
                </main><!--/.content-->
            </div><!--/.content-wrapper-->
<? get_footer(); ?>
