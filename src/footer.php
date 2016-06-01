            <div class="footer-container">
                <footer class="footer-block">
                    <p class="footer_text text _center">&copy; <?php echo date("Y"); ?> <?php bloginfo("name"); ?></p>
                    <?php if (is_front_page()): ?>
                    <p class="footer_text text _center"><a class="footer_link link" href="http://www.weblinxinc.com/" target="_blank" title="Chicago Web Design">Chicago Web Design</a> | <a class="footer_link link" href="http://www.weblinxinc.com/" target="_blank" title="Weblinx, Inc.">Weblinx, Inc.</a></p>
                    <?php endif; ?>
                </footer><!--/.footer-block-->
            </div><!--/.footer-container-->
        </div><!--/.page-container-->
        <?php wp_footer(); ?>
        <?php if (is_front_page() && have_rows("slideshow")): ?>
            <script type="text/javascript">
            var swiper = new Swiper(".swiper-container");
            </script>
        <?php endif; ?>
	</body>
</html>
