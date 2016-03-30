<?php get_header(); ?>
            <div class="content-wrapper">
                <main class="content-block">
                    <?php tribe_events_before_html(); ?>
                	<?php tribe_get_view(); ?>
                	<?php tribe_events_after_html(); ?>
                </main><!--/.content-block-->
            </div><!--/.content-wrapper-->
<?php get_footer(); ?>
