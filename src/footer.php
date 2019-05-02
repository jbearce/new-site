            </div><!--/#content-->
            <div class="footer-block footer-block--fullbleed" role="contentinfo" data-turbolinks-permanent>
                <div class="footer__inner">
                    <p class="footer__text text __textcenter __nomargin">&copy; <?php echo date("Y"); ?> <?php bloginfo("name"); ?></p>
                    <?php if (is_front_page()): ?>
                    <p class="footer__text text __textcenter __nomargin"><a class="footer__link link link--inherit" href="https://www.weblinxinc.com/" rel="noopener" target="_blank" title="Chicago Web Design">Chicago Web Design</a> | <a class="footer__link link link--inherit" href="https://www.weblinxinc.com/" rel="noopener" target="_blank" title="Weblinx, Inc.">Weblinx, Inc.</a></p>
                    <?php endif; ?>
                </div>
            </div>
        </div><!--/.page__container-->
        <?php if (has_nav_menu("primary")): ?>
            <div class="navigation-block navigation-block--flyout __hidden-xs __noncritical" role="navigation" aria-hidden="true" id="mobile-menu" tabindex="0" data-turbolinks-permanent>
                <div class="navigation__inner">
                    <figure class="navigation__figure">
                        <img class="navigation__image" src="<?php echo get_theme_file_uri("assets/media/navigation-banner.jpg"); ?>" alt="" />
                    </figure>
                    <nav class="navigation__menu-list__container menu-list_container">
                        <?php
                        wp_nav_menu(array(
                            "container"      => false,
                            "depth"          => 3,
                            "items_wrap"     => "<ul class='menu-list menu-list--navigation menu-list--accordion menu-list--vertical'>%3\$s</ul>",
                            "theme_location" => "primary",
                            "walker"         => new __gulp_init_namespace___menu_walker(array(
                                "id_prefix" => "mobile-nav_",
                                "features"  => array(
                                    "accordion",
                                    "touch",
                                ),
                            )),
                        ));
                        ?>
                    </nav><!--/.navigation__menu-list_container.menu-list__container-->
                </div><!--/.navigation__inner-->
            </div><!--/.navigation-block-->
        <?php endif; ?>
        <?php __gulp_init_namespace___get_template_part("partials/vendor/photoswipe.php"); ?>
        <?php __gulp_init_namespace___get_template_part("partials/vendor/pwa-install-prompt.php"); ?>
        <?php wp_footer(); ?>
    </body>
</html>
