<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href='https://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500italic,700,500,700italic,900,900italic' rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="<?php echo get_template_directory_uri() . '/style.css'; ?>" rel="stylesheet" />
    <link href="<?php echo get_template_directory_uri() . '/tempcss/header.css'; ?>" rel="stylesheet" />
    <link href="<?php echo get_template_directory_uri() . '/tempcss/body.css'; ?>" rel="stylesheet" />
    <link href="<?php echo get_template_directory_uri() . '/tempcss/footer.css'; ?>" rel="stylesheet" />

    <link rel="profile" href="http://gmpg.org/xfn/11">
    <?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <?php endif; ?>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="mobile_bg"></div>
<div id="mobile_menu">
    <div class="mobile_container">
        <a href="/"><img src="/wp-content/themes/tmz_tea2/assets/imgs/topo_logo.png"></a>
        <hr>
        <div id="header_search_mobile">
            <form action="/" method="get">
                <input  type="text" name="s" placeholder="pesquisar">
                <button type="submit" id="header_search_button_mobile">pesquisar</button>
            </form>
        </div>
        <hr>
        <?php

        wp_nav_menu(
            array(
                'theme_location' => 'primary',
                'menu_class' => 'nav-menu-mobile',
                'menu' => 'main-menu'
            )
        );
        ?>
    </div>
</div>
<div id="header">
    <div class="container">

        <div id="header_logo" onclick="document.location.href = '/'"></div>

        <div id="header_menu">
            <nav id="primary-navigation" class="site-navigation primary-navigation" role="navigation">

                <?php

                wp_nav_menu(
                    array(
                        'theme_location' => 'primary',
                        'menu_class' => 'nav-menu',
                        'menu' => 'main-menu'
                    )
                );
                ?>

            </nav>
        </div>
        <?php showSubMenu($wp_query); ?>

        <div id="header_search">
            <form action="/" method="get">
                <input  type="text" name="s" placeholder="pesquisar">
                <button type="submit" id="header_search_button">pesquisar</button>
            </form>
        </div>

        <div id="header_login_ctl">
            <ul>
                <li><a href="/signup">Cadastre-se</a></li>
                <li><a href="/my-account" id="header_login">Login</a></li>
                <li><a href="/cart" id="header_carrinho">&nbsp;</a></li>
            </ul>
            <div id="cart_counter" onclick="document.location.href='/cart'"><?php echo WC()->cart->cart_contents_count; ?></div>
        </div>

        <div id="header_mobile_menu">
            <i class="material-icons">menu</i>
        </div>

    </div>
</div>