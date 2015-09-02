<!doctype html>
<html lang="en-US">
	<head>
        <!-- html5 fallback -->
        <!--[if lt IE 9]>
            <script src="<? bloginfo("template_directory"); ?>/assets/scripts/vendors/html5shiv.js" type="text/javascript"></script>
            <script src="<? bloginfo("template_directory"); ?>/assets/scripts/vendors/nwmatcher-1.3.4.min.js" type="text/javascript"></script>
            <script src="<? bloginfo("template_directory"); ?>/assets/scripts/vendors/selectivizr-1.0.2.min.js" type="text/javascript"></script>
        <![endif]-->
        <!-- end html5 fallback -->
        <!-- settings -->
        <meta content="text/html;charset=utf-8" http-equiv="content-type" />
        <meta content="width=device-width, initial-scale=1, maximum-scale=1" name="viewport" />
        <meta content="no" name="msapplication-tap-highlight" />
        <!-- end settings -->
        <!-- branding -->
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" />
        <link href="<? bloginfo("template_directory"); ?>/assets/styles/all.css" rel="stylesheet" type="text/css" />
        <link href="<? bloginfo("template_directory"); ?>/assets/media/logo-ios.png" rel="apple-touch-icon" />
        <link href="<? bloginfo("template_directory"); ?>/assets/media/logo-favicon.png" rel="shortcut icon" />
        <meta content="Site Name" name="application-name" />
        <meta content="#000000" name="msapplication-TileColor" />
        <meta content="<? bloginfo("template_directory"); ?>/assets/media/logo-windows-tiny.png" name="msapplication-square70x70logo" />
        <meta content="<? bloginfo("template_directory"); ?>/assets/media/logo-windows-square.png" name="msapplication-square150x150logo" />
        <meta content="<? bloginfo("template_directory"); ?>/assets/media/logo-windows-wide.png" name="msapplication-wide310x150logo" />
        <meta content="<? bloginfo("template_directory"); ?>/assets/media/logo-windows-large.png" name="msapplication-square310x310logo" />
        <meta content="frequency=30;polling-uri=http://notifications.buildmypinnedsite.com/?feed=<? bloginfo("rss2_url"); ?>/&id=1;polling-uri2=http://notifications.buildmypinnedsite.com/?feed=<? bloginfo("rss2_url"); ?>&id=2;polling-uri3=http://notifications.buildmypinnedsite.com/?feed=<? bloginfo("rss2_url"); ?>&id=3;polling-uri4=http://notifications.buildmypinnedsite.com/?feed=<? bloginfo("rss2_url"); ?>&id=4;polling-uri5=http://notifications.buildmypinnedsite.com/?feed=<? bloginfo("rss2_url"); ?>&id=5; cycle=1" name="msapplication-notification" />
        <!-- end branding -->
        <!-- SEO -->
        <meta content="156 characters with spaces" name="description" />
        <title><? wp_title("|", true, "right"); ?></title>
        <!-- end SEO -->
		<? wp_head(); ?>
		<script>var $ = jQuery.noConflict();</script>
	</head>
	<body <? body_class(); ?>>
        <div class="mobile-nav-wrapper xs">
            <section class="mobile-nav">
                <nav class="menu-wrapper">
                    <?
                    wp_nav_menu(array(
                        "container"		 => false,
                        "depth"          => 3,
                        "items_wrap"	 => "<ul class='menu-list l-vertical'>%3\$s</ul>",
                        "theme_location" => "primary",
                        "walker"         => new mobileSMACSSwalker(),
                    ));
                    ?>
                </nav><!--/.menu-wrapper-->
            </section><!--/.mobile-nav-->
        </div><!--/.mobile-nav-wrapper-->
        <div class="page-wrapper">
            <div class="header-wrapper">
                <header class="header">
                    <a class="logo" href="<? echo home_url(); ?>">
						<img alt="<? bloginfo("name"); ?>" src="<? bloginfo("template_directory"); ?>/assets/media/logo.png" />
                        <em><? bloginfo("description"); ?></em>
					</a>
					<? get_search_form(); ?>
                    <button class="menu-button xs">Menu</button>
                </header><!--/.header-->
            </div><!--/.header-wrapper-->
            <div class="nav-wrapper s-plus">
                <div class="nav">
                    <nav class="menu-wrapper">
                        <?
                        wp_nav_menu(array(
                            "container"		 => false,
                            "depth"          => 3,
                            "items_wrap"	 => "<ul class='menu-list'>%3\$s</ul>",
                            "theme_location" => "primary",
                            "walker"         => new SMACSSwalker(),
                        ));
                        ?>
                    </nav><!--/.menu-wrapper-->
                </div><!--/.nav-->
            </div><!--/.nav-wrapper-->
