            <div class="footer-block" role="footer">
                <div class="footer_inner">
                    <p class="footer_text text _textcenter _nomargin">&copy; <?php echo date("Y"); ?> <?php bloginfo("name"); ?></p>
                    <?php if (is_front_page()): ?>
                    <p class="footer_text text _textcenter _nomargin"><a class="footer_link link" href="https://www.weblinxinc.com/" rel="noopener" target="_blank" title="Chicago Web Design">Chicago Web Design</a> | <a class="footer_link link" href="https://www.weblinxinc.com/" rel="noopener" target="_blank" title="Weblinx, Inc.">Weblinx, Inc.</a></p>
                    <?php endif; ?>
                </div><!--/.footer_inner-->
            </div><!--/.footer-block-->
            <?php
            $i = 0;
            $modals = get_field("modals");
            ?>

            <?php if ($modals): ?>
                <?php while (have_rows("modals")): ?>
                    <?php the_row(); ?>

                    <?php
                    $i++;
                    $content = get_sub_field("content");
                    ?>

                    <?php if ($content): ?>
                        <div class="modal _noncritical" data-overlay="modal<?php echo $i; ?>" aria-hidden="true" tabindex="1">

                            <div class="user-content">
                                <?php echo $content; ?>
                            </div><!--/.user-content-->

                            <button class="modal_menu-toggle menu-toggle" data-overlay="modal<?php echo $i; ?>">
                                <?php _e("Close Modal", "new_site"); ?>
                            </button><!--/.modal_menu-toggle.menu-toggle-->

                        </div><!--/.modal._noncritical-->
                    <?php endif; // if ($content) ?>

                <?php endwhile; // while (have_rows("modals")) ?>
            <?php endif; // if ($modals) ?>
        </div><!--/.page_container-->
        <?php wp_footer(); ?>
        <script defer="defer" src="<?php bloginfo("template_directory"); ?>/assets/scripts/modern.js"></script>
        <script><?php include(get_template_directory() . "/assets/scripts/critical.js"); ?></script>
        <?php if ($_COOKIE["previously_visited"] !== true): ?>
        <script>
        var httpRequest = new XMLHttpRequest();
        httpRequest.open("GET", "<?php echo home_url(); ?>?cookie=previously_visited&expiration=604800");
        httpRequest.send();
        </script>
        <?php endif; // if ($_COOKIE["previously_visited"] != true) ?>
    </body>
</html>
