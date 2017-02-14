<!doctype html>
<html <?php language_attributes(); ?>>
	<head>
		<!-- WordPress -->
		<?php wp_head(); ?>
        <!-- Android -->
        <link href="<?php bloginfo("template_directory"); ?>/assets/media/logo-favicon.png" rel="shortcut icon" />
        <meta name="theme-color" content="#17AAEC">
        <!-- iOS -->
        <link href="<?php bloginfo("template_directory"); ?>/assets/media/logo-ios.png" rel="apple-touch-icon" />
        <meta name="apple-mobile-web-app-status-bar-style" content="#17AAEC">
        <!-- Windows -->
        <meta content="no" name="msapplication-tap-highlight" />
        <meta name="msapplication-navbutton-color" content="#17AAEC">
        <meta content="#17AAEC" name="msapplication-TileColor" />
        <meta content="<?php bloginfo("name"); ?>" name="application-name" />
        <meta content="<?php bloginfo("template_directory"); ?>/assets/media/logo-windows-tiny.png" name="msapplication-square70x70logo" />
        <meta content="<?php bloginfo("template_directory"); ?>/assets/media/logo-windows-square.png" name="msapplication-square150x150logo" />
        <meta content="<?php bloginfo("template_directory"); ?>/assets/media/logo-windows-wide.png" name="msapplication-wide310x150logo" />
        <meta content="<?php bloginfo("template_directory"); ?>/assets/media/logo-windows-large.png" name="msapplication-square310x310logo" />
        <meta content="frequency=30;polling-uri=http://notifications.buildmypinnedsite.com/?feed=<?php bloginfo("rss2_url"); ?>/&id=1;polling-uri2=http://notifications.buildmypinnedsite.com/?feed=<?php bloginfo("rss2_url"); ?>&id=2;polling-uri3=http://notifications.buildmypinnedsite.com/?feed=<?php bloginfo("rss2_url"); ?>&id=3;polling-uri4=http://notifications.buildmypinnedsite.com/?feed=<?php bloginfo("rss2_url"); ?>&id=4;polling-uri5=http://notifications.buildmypinnedsite.com/?feed=<?php bloginfo("rss2_url"); ?>&id=5; cycle=1" name="msapplication-notification" />
        <!-- settings -->
        <meta content="text/html;charset=utf-8" http-equiv="content-type" />
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <!-- SEO -->
        <title><?php wp_title("|", true, "right"); ?></title>
		<!-- styles -->
		<style><?php include(get_template_directory() . "/assets/styles/critical.css"); ?></style>
		<link as="style" href="//fonts.googleapis.com/css?family=Open+Sans:400,400italic,700,700italic" onload="this.rel='stylesheet'" rel="preload" />
		<link as="style" href="<?php bloginfo("template_directory"); ?>/assets/styles/modern.css" onload="this.rel='stylesheet'" rel="preload" />
		<noscript>
		<link href="//fonts.googleapis.com/css?family=Open+Sans:400,400italic,700,700italic" rel="stylesheet" />
		<link href="<?php bloginfo("template_directory"); ?>/assets/styles/modern.css" rel="stylesheet" />
		</noscript>
		<!-- fallback -->
		<!--[if lte IE 9]>
		<link href="<?php bloginfo("template_directory"); ?>/assets/styles/legacy.css" rel="stylesheet" />
        <script src="<?php bloginfo("template_directory"); ?>/assets/scripts/legacy.js"></script>
		<![endif]-->
	</head>
    <body <?php body_class(); ?>>
        <?php include_once(get_template_directory() . "/assets/media/spritesheet.svg"); ?>
        <div class="page_container">
    		<button class="overlay-closer _noncritical" aria-hidden="true"><span class="_visuallyhidden"><?php _e("Close Overlay"); ?></span></button>
            <?php get_template_part("partials/login", "overlay"); ?>
            <div class="navigation-block -flyout _phone _noncritical" data-overlay="mobile-nav" data-menu="true" aria-hidden="true" role="navigation" tabindex="1">
                <div class="navigation_inner">
                    <div class="navigation_search-form_container search-form_container _nomargin">
                        <?php get_search_form(); ?>
                    </div><!--/.navigation_search-form_container.-search-form_container._nomargin-->
                    <?php
                    $menu = wp_nav_menu(array(
                        "container"		 => false,
                        "depth"          => 3,
                        "echo"           => false,
                        "items_wrap"	 => "%3\$s",
                        "theme_location" => "primary",
                        "walker"         => new new_site_walker("accordion"),
                    ));
                    ?>
                    <?php if ($menu): ?>
                    <nav class="navigation_menu-list_container menu-list_container">
                        <ul class="menu-list -navigation -accordion -vertical">
                            <?php echo $menu; ?>
                        </ul><!--/.menu-list.-navigation.-accordion.-vertical-->
                    </nav><!--/.navigation_menu-list_container.menu-list_container-->
                    <?php endif; ?>
                </div><!--/.navigation_inner.-flyout-->
    			<button class="navigation_menu-toggle menu-toggle _visuallyhidden" data-overlay="mobile-nav">
    				<?php _e("Close Menu", "new_site"); ?>
    			</button><!--/.navigation_menu-toggle.menu-toggle._visuallyhidden-->
            </div><!--/.navigation-block.-flyout._phone._noncritical-->
            <div class="header-block -fullbleed" role="banner">
                <div class="header_inner">
                    <div class="header_row row -between -vcenter">
                        <div class="col-auto -grow -shrink">
                            <a class="header_logo logo" href="<?php echo home_url(); ?>">
    							<img class="logo_image" alt="<?php bloginfo("name"); ?>" src="<?php bloginfo("template_directory"); ?>/assets/media/logo.svg" />
                            </a><!--/.header_logo.logo-->
                        </div><!--/.col-auto.-grow.-shrink-->
                        <div class="col-auto">
                            <button class="header_menu-toggle menu-toggle -rounded _phone" data-overlay="mobile-nav">
                                <?php _e("View Menu", "new_site"); ?>
                            </button><!--/.header_menu-toggle.menu-toggle.-rounded._phone-->
                            <div class="header_search_container search-form_container _nomargin _tablet _notebook _desktop" role="search">
                                <?php get_search_form(); ?>
                            </div><!--/.header_search_container.search-form_container._nomargin._tablet._notebook._desktop-->
                        </div><!--/.col-auto-->
                    </div><!--/.header_row.row.-between.-vcenter-->
                </div><!--/.header_inner-->
            </div><!--/.header-block.-fullbleed-->
            <?php
            $menu = wp_nav_menu(array(
                "container"		 => false,
                "depth"          => 4,
                "echo"           => false,
                "items_wrap"	 => "%3\$s",
                "theme_location" => "primary",
                "walker"         => new new_site_walker("mega hover touch"),
            ));
            ?>
            <?php if ($menu): ?>
            <div class="navigation-block -sticky -fullbleed _tablet _notebook _desktop" role="navigation">
                <div class="navigation_inner">
                    <nav class="navigation_menu-list_container menu-list_container">
                        <ul class="menu-list -navigation" data-hover="true" data-touch="true">
                            <?php echo $menu; ?>
                        </ul><!--/.menu-list.-navigation.-flex-->
                    </nav><!--/.navigation_menu-list_container.menu-list_container-->
                </div><!--/.navigation_inner._tablet-->
            </div><!--/.navigation-block.-sticky.-fullbleed._tablet._notebook._desktop-->
            <?php endif; ?>
