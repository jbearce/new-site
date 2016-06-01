<?php get_header(); ?>
            <div class="content-container">
                <main class="l-content-block">
                    <?php tribe_events_before_html(); ?>
                	<?php tribe_get_view(); ?>
                	<?php tribe_events_after_html(); ?>
                </main><!--/.l-content-block-->
            </div><!--/.content-container-->
<?php get_footer(); ?>
