<?php
/**
 * The Header for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) & !(IE 8)]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>

	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">

	<title><?php wp_title( '|', true, 'right' ); ?></title>

	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900' rel='stylesheet' type='text/css'>

    <link href="<?php echo get_template_directory_uri() . '/style.css'; ?>" rel="stylesheet" />

	<!--[if lt IE 9]>
	<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
	<![endif]-->

	<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>
<!--<div id="tea_bag"></div>-->
<!--<div id="tea_spoon"></div>-->
<div id="page">

	<header>
        <div class="content_container">

            <div id="header_logo">
                <a href="/"><img src="/wp-content/themes/tmz_tea/assets/imgs/header_topo.png" alt="Tea Lovers" /></a>
            </div>

            <div class="header-menu">

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

                <div id="search-container" class="search-box-wrapper hide">
                    <div class="search-box">
                        <?php get_search_form(); ?>
                    </div>
                </div>

            </div><!-- .header-menu -->


        </div><!-- .content_container -->
	</header><!-- #masthead -->

	<div id="main" class="site-main">
