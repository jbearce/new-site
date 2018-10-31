<!doctype html>
<html <?php language_attributes(); ?>>
    <head>
        <!-- settings -->
        <meta content="text/html;charset=utf-8" http-equiv="content-type" />
        <meta content="width=device-width, initial-scale=1" name="viewport" />

        <!-- WordPress -->
        <?php wp_head(); ?>

        <!-- Android -->
        <link href="<?php echo get_theme_file_uri("assets/media/logo-favicon.png"); ?>" rel="shortcut icon" />
        <meta name="theme-color" content="<%= pwa_theme_color %>" />

        <!-- iOS -->
        <link href="<?php echo get_theme_file_uri("assets/media/ios/ios-appicon-180-180.png") ?>" rel="apple-touch-icon" />

        <!-- iPhone 4 -->
        <link rel="apple-touch-startup-image" href="assets/media/ios/ios-launchimage-640-960.png" media="(device-width: 320px) and (device-height: 480px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)" />
        <link rel="apple-touch-startup-image" href="assets/media/ios/ios-launchimage-960-640.png" media="(device-width: 320px) and (device-height: 480px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)" />

        <!-- GOOD iPhone 5 -->
        <link rel="apple-touch-startup-image" href="assets/media/ios/ios-launchimage-640-1136.png" media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)" />
        <link rel="apple-touch-startup-image" href="assets/media/ios/ios-launchimage-1136-640.png" media="(device-width: 320px) and (device-height: 568px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)" />

        <!-- iPhone 6 -->
        <link rel="apple-touch-startup-image" href="assets/media/ios/ios-launchimage-750-1294.png" media="(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2) and (orientation: portrait)" />
        <link rel="apple-touch-startup-image" href="assets/media/ios/ios-launchimage-1294-750.png" media="(device-width: 375px) and (device-height: 667px) and (-webkit-device-pixel-ratio: 2) and (orientation: landscape)" />

        <!-- iPhone 6+ -->
        <link rel="apple-touch-startup-image" href="assets/media/ios/ios-launchimage-1242-2148.png" media="(device-width: 414px) and (device-height: 736px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)" />
        <link rel="apple-touch-startup-image" href="assets/media/ios/ios-launchimage-2148-1242.png" media="(device-width: 414px) and (device-height: 736px) and (-webkit-device-pixel-ratio: 3) and (orientation: landscape)" />

        <!-- iPhone 8 -->
        <link rel="apple-touch-startup-image" href="assets/media/ios/ios-launchimage-1125-2436.png" media="(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3) and (orientation: portrait)" />
        <link rel="apple-touch-startup-image" href="assets/media/ios/ios-launchimage-2436-1125.png" media="(device-width: 375px) and (device-height: 812px) and (-webkit-device-pixel-ratio: 3) and (orientation: landscape)" />

        <!-- iPad 9.7 (2018) -->
        <link rel="apple-touch-startup-image" href="assets/media/ios/ios-launchimage-1536-2048.png" media="(min-device-width: 768px) and (max-device-width: 1024px) and (-webkit-min-device-pixel-ratio: 2) and (orientation: portrait)" />
        <link rel="apple-touch-startup-image" href="assets/media/ios/ios-launchimage-2048-1536.png" media="(min-device-width: 768px) and (max-device-width: 1024px) and (-webkit-min-device-pixel-ratio: 2) and (orientation: landscape)" />

        <!-- iPad Pro 10.5 (2017) -->
        <link rel="apple-touch-startup-image" href="assets/media/ios/ios-launchimage-1668-2224.png" media="(min-device-width: 834px) and (max-device-width: 834px) and (-webkit-min-device-pixel-ratio: 2) and (orientation: portrait)">

        <!-- iPad Pro 12.9 (2017) -->
        <link rel="apple-touch-startup-image" href="assets/media/ios/ios-launchimage-2048-2732.png" media="(min-device-width: 1024px) and (max-device-width: 1024px) and (-webkit-min-device-pixel-ratio: 2) and (orientation: portrait)">

        <meta name="apple-mobile-web-app-status-bar-style" content="<%= pwa_theme_color %>" />

        <!-- Windows -->
        <meta name="msapplication-navbutton-color" content="<%= pwa_theme_color %>">

        <!-- Safari -->
        <link rel="mask-icon" href="<?php echo get_theme_file_uri("assets/media/logo-safari.svg"); ?>" color="<%= pwa_theme_color %>" />

        <!-- PWA -->
        <link href="<?php echo get_theme_file_uri("manifest.json"); ?>" rel="manifest" />

        <!-- fallback -->
        <noscript>
            <style>.__js {display: none !important;}</style>
        </noscript>
    </head>
    <body <?php body_class(); ?>>
        <div class="page__container" id="page-container">
            <div class="header-block header-block--fullbleed" role="banner">
                <div class="header__inner">
                    <div class="header__row row row--padded row--tight row--vcenter">
                        <div class="col-auto col--nogrow col--noshrink __hidden-xs">
                            <button class="header__panel-toggle panel-toggle" data-toggle="mobile-menu"<?php if (!has_nav_menu("primary")): ?> style="pointer-events:none;visibility:hidden;"<?php endif; ?>>
                                <i class="panel-toggle__icon fas fa-fw fa-bars"></i>
                                <span class="__visuallyhidden"><?php _e("View Menu", "__gulp_init_namespace__"); ?></span>
                            </button>
                        </div>
                        <div class="col-0">
                            <a class="header__logo logo" href="<?php echo home_url(); ?>">
                                <img class="logo__image" alt="<?php bloginfo("name"); ?>" src="<?php echo get_theme_file_uri("assets/media/logo.svg"); ?>" />
                            </a>
                        </div>
                        <div class="col-auto col--nogrow col--noshrnk __hidden-xs">
                            <button class="header__panel-toggle panel-toggle" data-toggle="mobile-search">
                                <i class="panel-toggle__icon fas fa-fw fa-search"></i>
                                <span class="__visuallyhidden"><?php _e("View Search", "__gulp_init_namespace__"); ?></span>
                            </button>
                            <?php if (!is_search()): ?>
                                <div class="header__search-form__container search-form__container search-form__container--expandable __nomargin __hidden-xs" role="search" id="mobile-search">
                                    <?php get_search_form(); ?>
                                </div>
                            <?php endif; ?>
                        </div><!--/.col-auto-->
                        <div class="col-xs-auto col--nogrow col--noshrink __visible-xs">
                            <div class="header__search-form__container search-form__container __nomargin __visible-xs" role="search">
                                <?php get_search_form(); ?>
                            </div>
                        </div>
                    </div><!--/.header__row.row-->
                </div><!--/.header__inner-->
            </div><!--/.header-block-->
            <?php if (has_nav_menu("primary")): ?>
                <div class="navigation-block navigation-block--fullbleed __visible-xs __noprint" role="navigation">
                    <div class="navigation__inner">
                        <nav class="navigation__menu-list_container menu-list__container">
                            <?php
                            wp_nav_menu(array(
                                "container"      => false,
                                "depth"          => 3,
                                "items_wrap"     => "<ul class='menu-list menu-list--navigation' data-hover='true' data-touch='true'>%3\$s</ul>",
                                "theme_location" => "primary",
                                "walker"         => new __gulp_init_namespace___menu_walker(array(
                                    "id_prefix" => "desktop-nav_",
                                    "features"  => array(
                                        "mega",
                                        "hover",
                                        "touch",
                                    ),
                                )),
                            ));
                            ?>
                        </nav><!--/.navigation__menu-list__container.menu-list__container-->
                    </div><!--/.navigation__inner-->
                </div><!--/.navigation-block-->
            <?php endif; ?>
