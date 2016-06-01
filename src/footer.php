            <div class="l-footer-container">
                <footer class="l-footer-block">
                    <p class="footer-text text _center">&copy; <?php echo date("Y"); ?> <?php bloginfo("name"); ?></p>
                    <?php if (is_front_page()): ?>
                    <p class="footer-text text _center"><a class="footer-link link" href="http://www.weblinxinc.com/" target="_blank" title="Chicago Web Design">Chicago Web Design</a> | <a class="footer-link link" href="http://www.weblinxinc.com/" target="_blank" title="Weblinx, Inc.">Weblinx, Inc.</a></p>
                    <?php endif; ?>
                </footer><!--/.l-footer-block-->
            </div><!--/.l-footer-container-->
        </div><!--/.l-page-container-->
        <?php wp_footer(); ?>
        <?php if (is_front_page() && have_rows("slideshow")): ?>
            <script type="text/javascript">
            var swiper = new Swiper(".swiper-container");
            </script>
        <?php endif; ?>
	</body>
</html>
