            <div class="footer-block -fullbleed" role="contentinfo">
                <div class="footer_inner">
                    <p class="footer_text text _textcenter _nomargin">&copy; <?php echo date("Y"); ?> <?php bloginfo("name"); ?></p>
                    <?php if (is_front_page()): ?>
                    <p class="footer_text text _textcenter _nomargin"><a class="footer_link link" href="https://www.weblinxinc.com/" rel="noopener" target="_blank" title="Chicago Web Design">Chicago Web Design</a> | <a class="footer_link link" href="https://www.weblinxinc.com/" rel="noopener" target="_blank" title="Weblinx, Inc.">Weblinx, Inc.</a></p>
                    <?php endif; ?>
                </div><!--/.footer_inner-->
            </div><!--/.footer-block.-fullbleed-->
            <?php if (has_nav_menu("primary")): ?>
                <div class="navigation-block -flyout _phone _noncritical" role="navigation" aria-hidden="true">
                    <button class="navigation_background"><span class="_visuallyhidden"><?php _e("Close Menu", "__gulp_init__namespace"); ?></span></button>
                    <div class="navigation_inner">
                        <div class="navigation_scroller">
                            <div class="navigation_search-form_container search-form_container _nomargin">
                                <?php get_search_form(); ?>
                            </div><!--/.navigation_search-form_container.-search-form_container._nomargin-->
                            <?php
                            wp_nav_menu(array(
                                "container"         => false,
                                "depth"          => 3,
                                "items_wrap"     => "<nav class='navigation_menu-list_container menu-list_container'><ul class='menu-list -navigation -accordion -vertical'>%3\$s</ul></nav>",
                                "theme_location" => "primary",
                                "walker"         => new __gulp_init__namespace_menu_walker("accordion"),
                            ));
                            ?>
                        </div><!--/.navigation_scroller-->
                    </div><!--/.navigation_inner-->
                </div><!--/.navigation-block.-flyout._phone._noncritical-->
            <?php endif; // has_nav_menu("primary") ?>
        </div><!--/.page_container-->
        <div style="display:none;"><?php include_once(get_template_directory() . "/assets/media/spritesheet.svg"); ?></div>
        <?php wp_footer(); ?>
        <script defer="defer" src="<?php bloginfo("template_directory"); ?>/assets/scripts/modern.js"></script>
        <script><?php include(get_template_directory() . "/assets/scripts/critical.js"); ?></script>

        <?php if (!(isset($_COOKIE["previously_visited"]) && $_COOKIE["previously_visited"] === "true")): ?>
            <script>
                var httpRequest = new XMLHttpRequest();
                httpRequest.open("GET", "<?php echo home_url(); ?>?cookie=previously_visited&expiration=604800");
                httpRequest.send();
            </script>
        <?php endif; // !(isset($_COOKIE["previously_visited"]) && $_COOKIE["previously_visited"] === "true") ?>
    </body>
</html>
