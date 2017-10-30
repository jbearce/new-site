            <div class="footer-block -fullbleed" role="contentinfo">
                <div class="footer_inner">
                    <p class="footer_text text _textcenter _nomargin">&copy; <?php echo date("Y"); ?> <?php bloginfo("name"); ?></p>
                    <?php if (is_front_page()): ?>
                    <p class="footer_text text _textcenter _nomargin"><a class="footer_link link" href="https://www.weblinxinc.com/" rel="noopener" target="_blank" title="Chicago Web Design">Chicago Web Design</a> | <a class="footer_link link" href="https://www.weblinxinc.com/" rel="noopener" target="_blank" title="Weblinx, Inc.">Weblinx, Inc.</a></p>
                    <?php endif; ?>
                </div><!--/.footer_inner-->
            </div><!--/.footer-block.-fullbleed-->
        </div><!--/.page_container-->
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
