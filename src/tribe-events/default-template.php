<? get_header(); ?>
            <div class="content-wrapper">
                <main class="content-block">
                    <div class="post">
                        <div id="tribe-events-pg-template">
                            <article class="article-card">
                                <? tribe_events_before_html(); ?>
                                <? tribe_get_view(); ?>
                                <? tribe_events_after_html(); ?>
                            </article><!--/.article-card-->
                        </div><!--/#tribe-events-pg-template-->
                    </div><!--/.post-->
				    <? get_sidebar(); ?>
                </main><!--/.content-block-->
            </div><!--/.content-wrapper-->
<? get_footer(); ?>
