            <div class="footer-wrapper">
                <footer class="footer-block">
                    <p class="text">&copy; <? echo date("Y"); ?> <? bloginfo("name"); ?></p>
                    <? if (is_front_page()): ?>
                    <p class="text"><a href="http://www.weblinxinc.com/" target="_blank" title="Chicago Web Design">Chicago Web Design</a> | <a href="http://www.weblinxinc.com/" target="_blank" title="Weblinx, Inc.">Weblinx, Inc.</a></p>
                    <? endif; ?>
                </footer><!--/.footer-block-->
            </div><!--/.footer-wrapper-->
        </div><!--/.page-wrapper-->
        <script src="<? bloginfo("template_directory"); ?>/assets/scripts/all.js@@if (context.version) {?v=@@version}" type="text/javascript"></script>
        <? if (is_front_page() && have_rows("slideshow")): ?>
        <script type="text/javascript">
            var swiper = new Swiper(".swiper-container");
        </script>
        <? endif; ?>
        <? wp_footer(); ?>
	</body>
</html>
