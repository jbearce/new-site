<? get_header(); ?>
            <div class="content-wrapper">
                <main class="content">
                    <div class="post">
				        <nav class="menu-wrapper">
							<ul class="menu-list">
								<li class="menu-item"><a href="<? echo tribe_get_events_link() ?>">Calendar</a></li>
							</ul><!--/.menu-list-->
						</nav><!--/.menu-wrapper-->
                        <div id="tribe-events-pg-template">
                            <? tribe_events_before_html(); ?>
                            <? tribe_get_view(); ?>
                            <? tribe_events_after_html(); ?>
                        </div><!--/#tribe-events-pg-template-->
                    </div><!--/.post-->
				    <? get_sidebar(); ?>
                </main><!--/.content-->
            </div><!--/.content-wrapper-->
<? get_footer(); ?>
