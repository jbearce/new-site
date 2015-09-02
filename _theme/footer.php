            <div class="footer-wrapper">
                <footer class="footer">
                    <div class="footer-left">
                        <h6>Site Map</h6>
                        <nav class="menu-wrapper">
                            <?
                            wp_nav_menu(array(
                                "container"		 => false,
                                "depth"			 => 1,
                                "items_wrap"	 => "<ul class='menu-list'>%3\$s</ul>",
                                "theme_location" => "primary",
                            ));
                            ?>
                        </nav><!--/.menu-wrapper-->
                        <p>&copy; <? echo date("Y"); ?> <? bloginfo("name"); ?></p>
                    </div><!--/.footer-left-->
                    <div class="footer-right">
                        <?
                        $address_1 = get_field("address_1", "option");
                        $address_2 = get_field("address_2", "option");
                        $city      = get_field("city", "option");
                        $state     = get_field("state", "option");
                        $zip_code  = get_field("zip_code", "option");
                        $phone     = get_field("phone", "option");
                        $fax       = get_field("fax", "option");
                        $email     = get_field("email", "option");
                        if ($address_1 || $address_2 || $city || $state || $zip_code || $phone || $fax || $email) {
                            echo "<address class='address'>";
                            if ($address_1 || $address_2 || $city || $state || $zip_code) {
                                echo "<p>";
                                if ($address_1) {
                                    echo $address_1;
                                    if ($address_2 || $city || $state || $zip_code) {
                                        echo "<br />";
                                    }
                                }
                                if ($address_2) {
                                    echo $address_2;
                                    if ($city || $state || $zip_code) {
                                        echo "<br />";
                                    }
                                }
                                if ($city) {
                                    echo $city;
                                    if ($state) {
                                        echo ", ";
                                    } elseif ($zip_code) {
                                        echo " ";
                                    }
                                }
                                if ($state) {
                                    echo $state;
                                    if ($zip_code) {
                                        echo " ";
                                    }
                                }
                                echo "</p>";
                            }
                            if ($phone || $fax || $email) {
                                echo "<p>";
                                if ($phone) {
                                    echo "p: {$phone}";
                                    if ($fax) {
                                        echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                    } elseif ($email) {
                                        echo "<br />";
                                    }
                                }
                                if ($fax) {
                                    echo "f: {$fax}";
                                    if ($email) {
                                        echo "<br />";
                                    }
                                }
                                if ($email) {
                                    echo "e: <a href='mailto:{$email}'>{$email}</a>";
                                }
                                echo "</p>";
                            }
                            echo "</address>";
                        }
                        ?>
						<? if (is_front_page()): ?>
						<p><a href="http://www.weblinxinc.com/" title="Chicago Web Design">Weblinx, Inc.</a> - <a href="http://www.weblinxinc.com/" title="Chicago Website Design Company">Chicago Web Design</a></p>
						<? endif; ?>
                    </div><!--/.footer-right-->
                </footer><!--/.footer-->
            </div><!--/.footer-wrapper-->
        </div><!--/.page-wrapper-->
        <script src="<? bloginfo("template_directory"); ?>/assets/scripts/all.js" type="text/javascript"></script>
        <? if (have_rows("slideshow")): ?>
        <script src="<? bloginfo("template_directory"); ?>/assets/scripts/vendors/swiper.jquery.min.js"></script>
        <script type="text/javascript">
            var swiper = new Swiper(".swiper-container");
        </script>
        <? endif; ?>
		<? wp_footer(); ?>
	</body>
</html>
