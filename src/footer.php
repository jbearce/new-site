            <div class="footer-block" role="footer">
                <div class="footer_inner">
                    <p class="footer_text text _textcenter _nomargin">&copy; <?php echo date("Y"); ?> <?php bloginfo("name"); ?></p>
                    <?php if (is_front_page()): ?>
                    <p class="footer_text text _textcenter _nomargin"><a class="footer_link link" href="https://www.weblinxinc.com/" target="_blank" title="Chicago Web Design">Chicago Web Design</a> | <a class="footer_link link" href="https://www.weblinxinc.com/" target="_blank" title="Weblinx, Inc.">Weblinx, Inc.</a></p>
                    <?php endif; ?>
                </div><!--/.footer_inner-->
            </div><!--/.footer-block-->
        </div><!--/.page_container-->
        <?php
        if ($modals) {
            $i = 0;

            while (have_rows("modals")) {
                the_row();

                $i++;

                $content = get_sub_field("content");

                if ($content) {
                    echo "<div class='modal _noncritical' data-overlay='modal{$i}' aria-hidden='true' tabindex='1'>";

                    echo "<div class='user-content'>{$content}</div>";

                    echo "<button class='modal_menu-toggle menu-toggle' data-overlay='modal{$i}'>" . __("Close Modal", "new_site") . "</button>";

                    echo "</div>"; // .modal._noncritical
                } // if ($content)

            } // while (have_rows("modals"))
        } // if ($modals)
        ?>
        <?php wp_footer(); ?>
        <script defer="defer" src="<?php bloginfo("template_directory"); ?>/assets/scripts/modern.js"></script>
        <script><?php include(get_template_directory() . "/assets/scripts/critical.js"); ?></script>
    </body>
</html>
