<?php get_header(); ?>
            <div class="content-wrapper">
                <main class="content-block">
                    <div class="post">
                        <div id="tribe-events-pg-template">
                            <article class="article-card">
                                <?php tribe_events_before_html(); ?>
                                <?php tribe_get_view(); ?>
                                <?php tribe_events_after_html(); ?>
                            </article><!--/.article-card-->
                        </div><!--/#tribe-events-pg-template-->
                    </div><!--/.post-->
				    <?php get_sidebar(); ?>
                </main><!--/.content-block-->
            </div><!--/.content-wrapper-->
<?php get_footer(); ?>
