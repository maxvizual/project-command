<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/png" href="<?php echo get_template_directory_uri(); ?>/images/favicon.png" />
    <?php wp_head(); ?> 
</head>

<body <?php body_class(); ?> <?php body_attributes(); ?>>

<?php do_action('wp_body'); ?>

<nav class="navbar bg-white fixed-top navbar-expand-xl">
    <div class="wrapper d-block position-relative w-100">
        <div class="header-notice-wrapper">
            <div class="bg-gradient1-gradient2">
                <div class="container-fluid">
                    <div class="row justify-content-center">
                        <div class="col-12">
                            <div class="header-notice text-center">
                                <a href="tel:611100692" class="header-notice-text text-white">zadzwoń 61 110 06 92</a>
                                lub
                                <a href="<?php echo home_url(); ?>/kontakt#formularz" class="header-notice-text text-white" target="_self">napisz do nas</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-11 col-md-10 col-xxl-8 d-flex justify-content-between align-items-center">
                    <a class="navbar-logo" href="<?php echo home_url('/'); ?>"></a>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTopMenu" aria-controls="navbarTopMenu" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="hamburger"><span></span><span></span><span></span></span>
                    </button>
                    <div class="nav-wrapper d-none d-xl-block position-relative w-100">
                        <div id="navbarTopMenu" class="collapse navbar-collapse">
                            <?php wp_nav_menu( array(
                                'theme_location' => 'top',
                                'depth'             => 2,
                                'container'         => 'd-none',
                                'menu_id'        => false,
                                'menu_class'     => 'navbar-nav w-100',
                                'fallback_cb'       => 'WP_Bootstrap_Navwalker::fallback',
                                'walker'            => new WP_Bootstrap_Navwalker()
                            ) ); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="navbarTopMenu" class="collapse navbar-collapse navbar-mobile">
            <?php wp_nav_menu( array(
                'theme_location' => 'top',
                'depth'             => 2,
                'container'         => 'd-lg-none',
                'menu_id'        => false,
                'menu_class'     => 'navbar-nav',
                'fallback_cb'       => 'WP_Bootstrap_Navwalker::fallback',
                'walker'            => new WP_Bootstrap_Navwalker()
            ) ); ?>
        </div>
    </div>
</nav>

