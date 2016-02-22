<!doctype html>
<html lang="en-US">
	<head>
        <!-- settings -->
        <meta content="text/html;charset=utf-8" http-equiv="content-type" />
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta content="no" name="msapplication-tap-highlight" />
        <!-- end settings -->
        <!-- branding -->
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css@@if (context.version) {?v=@@version}" rel="stylesheet" type="text/css" />
        <link href="//fonts.googleapis.com/css?family=Open+Sans:400,400italic,700,700italic@@if (context.version) {?v=@@version}" rel="stylesheet" type="text/css" />
        <link href="<?php bloginfo("template_directory"); ?>/assets/styles/modern.css@@if (context.version) {?v=@@version}" rel="stylesheet" type="text/css" />
        <link href="<?php bloginfo("template_directory"); ?>/assets/media/logo-ios.png@@if (context.version) {?v=@@version}" rel="apple-touch-icon" />
        <link href="<?php bloginfo("template_directory"); ?>/assets/media/logo-favicon.png@@if (context.version) {?v=@@version}" rel="shortcut icon" />
        <meta content="<?php bloginfo("name"); ?>" name="application-name" />
        <meta content="@@color" name="msapplication-TileColor" />
        <meta content="<?php bloginfo("template_directory"); ?>/assets/media/logo-windows-tiny.png@@if (context.version) {?v=@@version}" name="msapplication-square70x70logo" />
        <meta content="<?php bloginfo("template_directory"); ?>/assets/media/logo-windows-square.png@@if (context.version) {?v=@@version}" name="msapplication-square150x150logo" />
        <meta content="<?php bloginfo("template_directory"); ?>/assets/media/logo-windows-wide.png@@if (context.version) {?v=@@version}" name="msapplication-wide310x150logo" />
        <meta content="<?php bloginfo("template_directory"); ?>/assets/media/logo-windows-large.png@@if (context.version) {?v=@@version}" name="msapplication-square310x310logo" />
        <meta content="frequency=30;polling-uri=http://notifications.buildmypinnedsite.com/?feed=<?php bloginfo("rss2_url"); ?>/&id=1;polling-uri2=http://notifications.buildmypinnedsite.com/?feed=<?php bloginfo("rss2_url"); ?>&id=2;polling-uri3=http://notifications.buildmypinnedsite.com/?feed=<?php bloginfo("rss2_url"); ?>&id=3;polling-uri4=http://notifications.buildmypinnedsite.com/?feed=<?php bloginfo("rss2_url"); ?>&id=4;polling-uri5=http://notifications.buildmypinnedsite.com/?feed=<?php bloginfo("rss2_url"); ?>&id=5; cycle=1" name="msapplication-notification" />
        <!-- end branding -->
        <!-- html5 fallback -->
        <!--[if lte IE 9]>
        <link href="<?php bloginfo("template_directory"); ?>/assets/styles/legacy.css@@if (context.version) {?v=@@version}" rel="stylesheet" type="text/css" />
        <script src="<?php bloginfo("template_directory"); ?>/assets/scripts/fallback/html5shiv.js@@if (context.version) {?v=@@version}" type="text/javascript"></script>
        <script src="<?php bloginfo("template_directory"); ?>/assets/scripts/fallback/nwmatcher-1.3.4.min.js@@if (context.version) {?v=@@version}" type="text/javascript"></script>
        <script src="<?php bloginfo("template_directory"); ?>/assets/scripts/fallback/selectivizr-1.0.2.min.js@@if (context.version) {?v=@@version}" type="text/javascript"></script>
        <script src="<?php bloginfo("template_directory"); ?>/assets/scripts/fallback/flexibility.js@@if (context.version) {?v=@@version}" type="text/javascript"></script>
        <![endif]-->
        <!-- end html5 fallback -->
        <!-- SEO -->
        <title><?php wp_title("|", true, "right"); ?></title>
        <!-- end SEO -->
        <?php wp_head(); ?>
        <script>var $ = jQuery.noConflict();</script>
	</head>
    <body <?php body_class(); ?>>
        <!--[if lt IE 10]>
        <div class="notification-banner-wrapper -error">
            <div class="notification-banner">
                <p><i class="fa fa-exclamation-circle"></i> It looks like you're using an old version of Internet Explorer. For the best experience, this site requires Internet Explorer 10 or higher. <a href="http://windows.microsoft.com/en-US/internet-explorer/download-ie" target="_blank">Click here to learn about upgrading.</a></p>
            </div>
        </div>
        <![endif]-->
        <?php if (WP_DEBUG): ?>
        <div class="notification-banner-wrapper">
            <div class="notification-banner">
                <p><i class="fa fa-info-circle"></i> Pardon our dust! If you're seeing this, it means we're working on an update to the site. You may experience some errors during this time. Please check back later if you're having trouble.</p>
            </div><!--/.notification-banner-->
        </div><!--/.notification-banner-wrapper-->
        <?php endif; ?>
        <div class="mobile-nav-wrapper">
            <div class="mobile-nav-block">
                <?php
                wp_nav_menu(array(
                    "container"		 => false,
                    "depth"          => 3,
                    "items_wrap"	 => "<nav class='menu-wrapper -nav -vertical'><ul class='menu-list'>%3\$s</ul></nav>",
                    "theme_location" => "primary",
                    "walker"         => new mobileRSCSSwalker(),
                ));
                ?>
            </div><!--/.mobile-nav-block-->
        </div><!--/.mobile-nav-wrapper-->
        <div class="page-wrapper">
            <div class="header-wrapper">
                <header class="header-block">
                    <a class="logo" href="<?php echo home_url(); ?>">
                        <img alt="<?php bloginfo("name"); ?> | <?php bloginfo("description"); ?>" src="<?php bloginfo("template_directory"); ?>/assets/media/logo.png@@if (context.version) {?v=@@version}" srcset="<?php bloginfo("template_directory"); ?>/assets/media/logo@2x.png@@if (context.version) {?v=@@version} 2x" />
                    </a><!--/.logo-->
                    <?php get_search_form(); ?>
                    <button class="menu-button">Menu</button>
                </header><!--/.header-block-->
            </div><!--/.header-wrapper-->
            <div class="nav-wrapper">
                <div class="nav-block">
                    <?php
                    wp_nav_menu(array(
                        "container"		 => false,
                        "depth"          => 3,
                        "items_wrap"	 => "<nav class='menu-wrapper -nav'><ul class='menu-list'>%3\$s</ul></nav>",
                        "theme_location" => "primary",
                        "walker"         => new RSCSSwalker(),
                    ));
                    ?>
                </div><!--/.nav-block-->
            </div><!--/.nav-wrapper-->
