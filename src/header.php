<!doctype html>
<html <?php language_attributes(); ?>>
	<head>
        <!-- Android -->
        <link href="<?php bloginfo("template_directory"); ?>/assets/media/logo-favicon.png@@if (context.version) {?v=@@version}" rel="shortcut icon" />
        <meta name="theme-color" content="#17AAEC">
        <!-- iOS -->
        <link href="<?php bloginfo("template_directory"); ?>/assets/media/logo-ios.png@@if (context.version) {?v=@@version}" rel="apple-touch-icon" />
        <meta name="apple-mobile-web-app-status-bar-style" content="#17AAEC">
        <!-- Windows -->
        <meta content="no" name="msapplication-tap-highlight" />
        <meta name="msapplication-navbutton-color" content="#17AAEC">
        <meta content="#17AAEC" name="msapplication-TileColor" />
        <meta content="<?php bloginfo("name"); ?>" name="application-name" />
        <meta content="<?php bloginfo("template_directory"); ?>/assets/media/logo-windows-tiny.png@@if (context.version) {?v=@@version}" name="msapplication-square70x70logo" />
        <meta content="<?php bloginfo("template_directory"); ?>/assets/media/logo-windows-square.png@@if (context.version) {?v=@@version}" name="msapplication-square150x150logo" />
        <meta content="<?php bloginfo("template_directory"); ?>/assets/media/logo-windows-wide.png@@if (context.version) {?v=@@version}" name="msapplication-wide310x150logo" />
        <meta content="<?php bloginfo("template_directory"); ?>/assets/media/logo-windows-large.png@@if (context.version) {?v=@@version}" name="msapplication-square310x310logo" />
        <meta content="frequency=30;polling-uri=http://notifications.buildmypinnedsite.com/?feed=<?php bloginfo("rss2_url"); ?>/&id=1;polling-uri2=http://notifications.buildmypinnedsite.com/?feed=<?php bloginfo("rss2_url"); ?>&id=2;polling-uri3=http://notifications.buildmypinnedsite.com/?feed=<?php bloginfo("rss2_url"); ?>&id=3;polling-uri4=http://notifications.buildmypinnedsite.com/?feed=<?php bloginfo("rss2_url"); ?>&id=4;polling-uri5=http://notifications.buildmypinnedsite.com/?feed=<?php bloginfo("rss2_url"); ?>&id=5; cycle=1" name="msapplication-notification" />
        <!-- settings -->
        <meta content="text/html;charset=utf-8" http-equiv="content-type" />
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <!-- SEO -->
        <title><?php wp_title("|", true, "right"); ?></title>
        <!-- WordPress -->
        <?php wp_head(); ?>
	</head>
    <body <?php body_class(); ?>>
		<div class="page-container">
			<button class="overlay-closer"><span class="_visuallyhidden"><?php _e("Close Overlay"); ?></span></button>
	        <div class="navigation-container -flyout -right _mobile" data-menu="mobile-nav" aria-hidden="true">
	            <div class="navigation-block">
	                <div class="navigation_search-form-container search-form-container">
	                    <?php get_search_form(); ?>
	                </div><!--/.navigation_search-form-container.-search-form-container-->
	                <?php
	                $menu = wp_nav_menu(array(
	                    "container"		 => false,
	                    "depth"          => 3,
	                    "echo"           => false,
	                    "items_wrap"	 => "%3\$s",
	                    "theme_location" => "primary",
	                    "walker"         => new new_site_walker("collapsible"),
	                ));
	                ?>
	                <?php if ($menu): ?>
	                <nav class="navigation_menu-container menu-container">
	                    <ul class="menu-list -navigation -vertical">
	                        <?php echo $menu; ?>
	                    </ul><!--/.menu-list.-navigation.-vertical-->
	                </nav><!--/.navigation_menu-container.menu-container-->
	                <?php endif; ?>
	            </div><!--/.navigation-container.-flyout._mobile-->
	        </div><!--/.navigation-container.-flyout.-right._mobile-->
	        <div class="header-container">
	            <div class="header-block">
	                <div class="header_row row -between -mobile">
	                    <div class="header_col col">
	                        <a class="header_logo logo" href="<?php echo home_url(); ?>">
	                            <img class="logo_image" alt="<?php bloginfo("name"); ?>" src="<?php bloginfo("template_directory"); ?>/assets/media/logo.svg" />
	                        </a><!--/.header_logo.logo-->
	                    </div><!--/.header_col.col-->
	                    <div class="header_col col">
	                        <button class="header_menu-toggle menu-toggle -labeled _mobile" data-menu="mobile-nav">
	                            <?php _e("View Menu", "new_site"); ?>
	                        </button><!--/.header_menu-toggle.menu-toggle._mobile-->
	                        <div class="header_serach-container search-form-container _tablet _desktop">
	                            <?php get_search_form(); ?>
	                        </div><!--/.header_serach-container.search-form-container._tablet._desktop-->
	                        <?php
	                        $menu = wp_nav_menu(array(
	                            "container"		 => false,
	                            "depth"          => 3,
	                            "echo"           => false,
	                            "items_wrap"	 => "%3\$s",
	                            "theme_location" => "primary",
	                            "walker"         => new new_site_walker(),
	                        ));
	                        ?>
	                        <?php if ($menu): ?>
	                        <nav class="header_menu-container menu-container _tablet _desktop">
	                            <ul class="menu-list -navigation">
	                                <?php echo $menu; ?>
	                            </ul><!--/.menu-list.-navigation-->
	                        </nav><!--/.header_menu-container.menu-container._tablet._desktop-->
	                        <?php endif; ?>
	                    </div><!--/.header_col.col-->
	                </div><!--/.header_row.row.-between.-mobile-->
	            </div><!--/.header-block-->
	        </div><!--/.header-container-->
	        <?php
	        $menu = wp_nav_menu(array(
	            "container"		 => false,
	            "depth"          => 3,
	            "echo"           => false,
	            "items_wrap"	 => "%3\$s",
	            "theme_location" => "primary",
	            "walker"         => new new_site_walker(),
	        ));
	        ?>
	        <?php if ($menu): ?>
	        <div class="navigation-container _tablet _desktop">
	            <div class="navigation-block">
	                <nav class="navigation_menu-container menu-container">
	                    <ul class="menu-list -navigation">
	                        <?php echo $menu; ?>
	                    </ul><!--/.menu-list.-navigation-->
	                </nav><!--/.navigation_menu-container.menu-container-->
	            </div><!--/.navigation-container._tablet._desktop-->
	        </div><!--/.navigation-container._tablet._desktop-->
	        <?php endif; ?>
