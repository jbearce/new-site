<!doctype html>
<html lang="en-US">
	<head>
        <!-- settings -->
        <meta content="text/html;charset=utf-8" http-equiv="content-type" />
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="no" name="msapplication-tap-highlight" />
        <!-- end settings -->
        <!-- branding -->
        <link href="<?php bloginfo("template_directory"); ?>/assets/media/logo-ios.png@@if (context.version) {?v=@@version}" rel="apple-touch-icon" />
        <link href="<?php bloginfo("template_directory"); ?>/assets/media/logo-favicon.png@@if (context.version) {?v=@@version}" rel="shortcut icon" />
        <!-- Android -->
        <meta name="theme-color" content="#17AAEC">
        <!-- iOS -->
        <meta name="apple-mobile-web-app-status-bar-style" content="#17AAEC">
        <!-- Windows -->
        <meta name="msapplication-navbutton-color" content="#17AAEC">
        <!-- Windows Mobile -->
        <meta content="#17AAEC" name="msapplication-TileColor" />
        <meta content="<?php bloginfo("name"); ?>" name="application-name" />
        <meta content="<?php bloginfo("template_directory"); ?>/assets/media/logo-windows-tiny.png@@if (context.version) {?v=@@version}" name="msapplication-square70x70logo" />
        <meta content="<?php bloginfo("template_directory"); ?>/assets/media/logo-windows-square.png@@if (context.version) {?v=@@version}" name="msapplication-square150x150logo" />
        <meta content="<?php bloginfo("template_directory"); ?>/assets/media/logo-windows-wide.png@@if (context.version) {?v=@@version}" name="msapplication-wide310x150logo" />
        <meta content="<?php bloginfo("template_directory"); ?>/assets/media/logo-windows-large.png@@if (context.version) {?v=@@version}" name="msapplication-square310x310logo" />
        <meta content="frequency=30;polling-uri=http://notifications.buildmypinnedsite.com/?feed=<?php bloginfo("rss2_url"); ?>/&amp;id=1;polling-uri2=http://notifications.buildmypinnedsite.com/?feed=<?php bloginfo("rss2_url"); ?>&amp;id=2;polling-uri3=http://notifications.buildmypinnedsite.com/?feed=<?php bloginfo("rss2_url"); ?>&amp;id=3;polling-uri4=http://notifications.buildmypinnedsite.com/?feed=<?php bloginfo("rss2_url"); ?>&amp;id=4;polling-uri5=http://notifications.buildmypinnedsite.com/?feed=<?php bloginfo("rss2_url"); ?>&amp;id=5; cycle=1" name="msapplication-notification" />
        <!-- end branding -->
        <!-- SEO -->
        <title><?php wp_title("|", true, "right"); ?></title>
        <!-- end SEO -->
        <?php wp_head(); ?>
	</head>
    <body <?php body_class(); ?>>
        <div class="page-wrapper">
			<div class="mobile-nav-wrapper _mobile">
				<div class="mobile-nav-block">
					<?php
					wp_nav_menu(array(
						"container"		 => false,
						"depth"          => 3,
						"items_wrap"	 => "<nav class='menu-wrapper'><ul class='menu-list -vertical title'>%3\$s</ul></nav>",
						"theme_location" => "primary",
						"walker"         => new mobileRSCSSwalker(),
					));
					?>
				</div><!--/.mobile-nav-block-->
			</div><!--/.mobile-nav-wrapper-->
            <!--[if lt IE 10]>
            <div class="banner-wrapper" data-banner="old-browser" hidden>
                <div class="banner">
                    <h6 class="title"><i class="fa fa-internet-explorer"></i> <?php _e("Old<br /> Browser", "new-site"); ?></h6>
                    <p class="excerpt"><?php _e("It looks like you're using an old version of Internet Explorer. For the best experience, this site requires Internet Explorer 10 or higher.", "new-site"); ?> <a href="http://windows.microsoft.com/en-US/internet-explorer/download-ie" target="_blank"><?php _e("Click here to learn about upgrading.", "new-site"); ?></a></p>
                    <button class="button"><i class="fa fa-times-circle"></i> <span class="_visuallyhidden"><?php _e("Hide Notice", "new-site"); ?></span></button>
                </div>
            </div>
            <![endif]-->
            <?php if (WP_DEBUG): ?>
            <div class="banner-wrapper -notice" data-banner="under-construction" hidden>
                <div class="banner">
                    <h6 class="title"><i class="fa fa-info-circle"></i> <?php _e("Under<br /> Construction", "new-site"); ?></h6>
                    <p class="excerpt"><?php _e("Pardon our dust! If you're seeing this, it means we're working on an update to the site. You may experience some errors during this time. Please check back later if you're having trouble.", "new-sites"); ?></p>
                    <button class="button"><i class="fa fa-times-circle"></i> <span class="_visuallyhidden"><?php _e("Hide Notice", "new-site"); ?></span></button>
                </div><!--/.banner-->
            </div><!--/.banner-wrapper-->
            <?php endif; ?>
            <div class="header-wrapper">
                <header class="header-block">
                    <a class="logo" href="<?php echo home_url(); ?>">
                        <img alt="<?php bloginfo("name"); ?> | <?php bloginfo("description"); ?>" src="<?php bloginfo("template_directory"); ?>/assets/media/logo.png@@if (context.version) {?v=@@version}" srcset="<?php bloginfo("template_directory"); ?>/assets/media/logo@2x.png@@if (context.version) {?v=@@version} 2x" />
                    </a><!--/.logo-->
                    <?php get_search_form(); ?>
                    <button class="menu-button _mobile"><?php _e("Menu", "new-site"); ?></button>
                </header><!--/.header-block-->
            </div><!--/.header-wrapper-->
            <div class="nav-wrapper _tabletdesktop">
                <div class="nav-block">
                    <?php
                    wp_nav_menu(array(
                        "container"		 => false,
                        "depth"          => 3,
                        "items_wrap"	 => "<nav class='menu-wrapper'><ul class='menu-list title'>%3\$s</ul></nav>",
                        "theme_location" => "primary",
                        "walker"         => new RSCSSwalker(),
                    ));
                    ?>
                </div><!--/.nav-block-->
            </div><!--/.nav-wrapper-->
