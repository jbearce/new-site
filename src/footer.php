            <div class="footer-block">
                <footer class="footer__inner">
                    <p class="footer__text text __center">&copy; <?php echo date("Y"); ?> <?php bloginfo("name"); ?></p>
                    <?php if (is_front_page()): ?>
                    <p class="footer__text text __center"><a class="footer__link link" href="http://www.weblinxinc.com/" target="_blank" title="Chicago Web Design">Chicago Web Design</a> | <a class="footer__link link" href="http://www.weblinxinc.com/" target="_blank" title="Weblinx, Inc.">Weblinx, Inc.</a></p>
                    <?php endif; ?>
                </footer><!--/.footer__inner-->
            </div><!--/.footer-block-->
        </div><!--/.page-block-->
        <?php wp_footer(); ?>
        <?php if (is_front_page() && have_rows("slideshow")): ?>
            <script type="text/javascript">
            var swiper = new Swiper(".swiper-container");
            </script>
        <?php endif; ?>
	</body>
</html>
