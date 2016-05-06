<?php get_header(); ?>
            <div class="content-block">
                <main class="content__inner">
                    <?php tribe_events_before_html(); ?>
                	<?php tribe_get_view(); ?>
                	<?php tribe_events_after_html(); ?>
                </main><!--/.content__inner-->
            </div><!--/.content-block-->
<?php get_footer(); ?>
