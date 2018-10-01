<!doctype html>
<html <?php language_attributes(); ?>>
    <head>
        <!-- settings -->
        <meta content="text/html;charset=utf-8" http-equiv="content-type" />
        <meta content="width=device-width, initial-scale=1" name="viewport" />

        <!-- WordPress -->
        <?php wp_head(); ?>

        <!-- Android -->
        <link href="<?php bloginfo("template_directory"); ?>/assets/media/logo-favicon.png" rel="shortcut icon" />
        <meta name="theme-color" content="<%= pwa_theme_color %>" />

        <!-- Safari -->
        <link rel="mask-icon" href="<?php bloginfo("template_directory"); ?>/assets/media/logo-safari.svg" color="<%= pwa_theme_color %>" />

        <!-- iOS -->
        <link href="<?php bloginfo("template_directory"); ?>/assets/media/logo-ios.png" rel="apple-touch-icon" />
        <meta name="apple-mobile-web-app-status-bar-style" content="<%= pwa_theme_color %>" />

        <!-- Windows -->
        <meta content="no" name="msapplication-tap-highlight" />
        <meta name="msapplication-navbutton-color" content="<%= pwa_theme_color %>">
        <meta content="<%= pwa_theme_color %>" name="msapplication-TileColor" />
        <meta content="<?php bloginfo("name"); ?>" name="application-name" />
        <meta content="<?php bloginfo("template_directory"); ?>/assets/media/logo-windows-128x128.png" name="msapplication-square70x70logo" />
        <meta content="<?php bloginfo("template_directory"); ?>/assets/media/logo-windows-270x270.png" name="msapplication-square150x150logo" />
        <meta content="<?php bloginfo("template_directory"); ?>/assets/media/logo-windows-558x270.png" name="msapplication-wide310x150logo" />
        <meta content="<?php bloginfo("template_directory"); ?>/assets/media/logo-windows-558x558.png" name="msapplication-square310x310logo" />
        <meta content="frequency=30;polling-uri=http://notifications.buildmypinnedsite.com/?feed=<?php bloginfo("rss2_url"); ?>/&id=1;polling-uri2=http://notifications.buildmypinnedsite.com/?feed=<?php bloginfo("rss2_url"); ?>&id=2;polling-uri3=http://notifications.buildmypinnedsite.com/?feed=<?php bloginfo("rss2_url"); ?>&id=3;polling-uri4=http://notifications.buildmypinnedsite.com/?feed=<?php bloginfo("rss2_url"); ?>&id=4;polling-uri5=http://notifications.buildmypinnedsite.com/?feed=<?php bloginfo("rss2_url"); ?>&id=5; cycle=1" name="msapplication-notification" />

        <!-- PWA -->
        <link href="<?php bloginfo("template_directory"); ?>/manifest.json" rel="manifest" />

        <!-- fallback -->
        <noscript>
            <style>._js {display: none !important;}</style>
        </noscript>
    </head>
    <body <?php body_class(); ?>>
        <div class="page__container" id="page-container">
            <div class="header-block --fullbleed" role="banner">
                <div class="header__inner">
                    <div class="header__row row -padded --tight --between --vcenter">
                        <div class="col-0 __hidden-xs">
                            <?php if (has_nav_menu("primary")): ?>
                                <button class="header__panel-toggle panel-toggle" data-toggle="mobile-menu">
                                    <i class="panel-toggle__icon fas fa-fw fa-bars"></i>
                                    <span class="__visuallyhidden"><?php _e("View Menu", "__gulp_init_namespace__"); ?></span>
                                </button><!--/.header__panel-toggle.panel-toggle-->
                            <?php endif; ?>
                        </div><!--/.col-0.__hidden-xs-->
                        <div class="col-auto --nogrow --noshrink">
                            <a class="header__logo logo" href="<?php echo home_url(); ?>">
                                <img class="logo__image" alt="<?php bloginfo("name"); ?>" src="<?php bloginfo("template_directory"); ?>/assets/media/logo.svg" />
                            </a><!--/.header__logo.logo-->
                        </div><!--/.col-auto.--nogrow.--noshrink-->
                        <div class="col-0 __hidden-xs">
                            <!-- spacer -->
                        </div><!--/.col-0.__hidden-xs-->
                        <div class="col-xs-auto --nogrow --noshrink __visible-xs">
                            <div class="header__search-form__container search-form__container __nomargin __visible-xs" role="search">
                                <?php get_search_form(); ?>
                            </div><!--/.header__search-form__container.search-form__container.__nomargin.__visible-xs-->
                        </div><!--/.col-auto.--nogrow.--noshrink.__visible-xs-->
                    </div><!--/.header__row.row.--padded.--tight.--between.--vcenter-->
                </div><!--/.header__inner-->
            </div><!--/.header-block.--fullbleed-->
            <?php if (has_nav_menu("primary")): ?>
                <div class="navigation-block --fullbleed __visible-xs __noprint" role="navigation">
                    <div class="navigation__inner">
                        <nav class="navigation__menu-list_container menu-list__container">
                            <?php
                            wp_nav_menu(array(
                                "container"      => false,
                                "depth"          => 3,
                                "items_wrap"     => "<ul class='menu-list --navigation' data-hover='true' data-touch='true'>%3\$s</ul>",
                                "theme_location" => "primary",
                                "walker"         => new __gulp_init_namespace___menu_walker("mega hover touch"),
                            ));
                            ?>
                        </nav><!--/.navigation__menu-list__container.menu-list__container-->
                    </div><!--/.navigation__inner-->
                </div><!--/.navigation-block.--fullbleed.__visible-xs.__noprint-->
            <?php endif; ?>
