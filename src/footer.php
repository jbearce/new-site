            <div class="footer-wrapper">
                <footer class="footer">
                    <p>&copy; @@copyright_year <? bloginfo("name"); ?></p>
                </footer><!--/.footer-->
            </div><!--/.footer-wrapper-->
        </div><!--/.page-wrapper-->
        <script src="<? bloginfo("template_directory"); ?>/assets/scripts/all.js@@if (context.version) {?v=@@version}" type="text/javascript"></script>
        <? if (is_front_page() && have_rows("slideshow")): ?>
        <script type="text/javascript">
            var swiper = new Swiper(".swiper-container");
        </script>
        <? endif; ?>
	</body>
</html>
