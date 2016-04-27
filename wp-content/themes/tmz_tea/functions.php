<?php

//exit(var_dump(get_template_directory_uri() . '/style.css'));

function tmz_basic_styles()
{
    //Registra o estilo geral do script
    wp_register_style(
        'default_style',
        get_template_directory_uri() . '/style.css'
    );
}

//add_action(
//    'wp_enqueue_scripts',
//    'tmz_basic_styles'
//);

/* Verifica se existe subcategorias */
function category_has_children( $term_id = 0, $taxonomy = 'category' ) {
    $children = get_categories( array( 'child_of' => $term_id, 'taxonomy' => $taxonomy ) );
    return ( $children );
}

/* BEGIN WOOCOMMERCE */
function woo_money_format($value, $format = "R$", $class = false) {
    if( $class !== false ){
        $format = "<span class='$class'>$format</span>";
    }
    return (!is_numeric($value)) ? 0 : $format.number_format($value, 2, ',', '.');
}

remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

add_action('woocommerce_before_main_content', 'my_theme_wrapper_start', 10);
add_action('woocommerce_after_main_content', 'my_theme_wrapper_end', 10);

function my_theme_wrapper_start() {
    echo '<section id="main">';
}

function my_theme_wrapper_end() {
    echo '</section>';
}

/* END WOOCOMMERCE */

/**
 * This theme uses post thumbnails
 */
add_theme_support( 'post-thumbnails' );

add_image_size( 'banner_padrao', 1184, 446, true );