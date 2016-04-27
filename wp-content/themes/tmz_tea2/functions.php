<?php

add_theme_support( 'post-thumbnails' );

function woo_money_format($value, $format = "R$", $class = false) {
    if( $class !== false ){
        $format = "<span class='$class'>$format</span>";
    }
    return (!is_numeric($value)) ? 0 : $format . number_format($value, 2, ',', '.');
}

add_filter('woocommerce_product_get_rating_html', 'your_get_rating_html', 10, 2);

function your_get_rating_html($rating_html, $rating) {
    if ( $rating > 0 ) {
        $title = sprintf( __( 'Rated %s out of 5', 'woocommerce' ), $rating );
    } else {
        $title = 'Not yet rated';
        $rating = 0;
    }

    $rating_html  = '<div class="star-rating" title="' . $title . '">';
    $rating_html .= '<span style="width:' . ( ( $rating / 5 ) * 100 ) . '%"><strong class="rating">' . $rating . '</strong> ' . __( 'out of 5', 'woocommerce' ) . '</span>';
    $rating_html .= '</div>';

    return $rating_html . '<div class="dashed_hr"></div>';
}

/* Verifica se existe subcategorias */
function category_has_children( $term_id = 0, $taxonomy = 'category' )
{
    $children = get_categories( array( 'child_of' => $term_id, 'taxonomy' => $taxonomy ) );
    return ( $children );
}

/* mostra submenu */
function showSubMenu($query, $classdiv = 'product_submenu'){
    $page = $query->get_queried_object();

    if($page->taxonomy == 'product_cat')
    {
        //verifica se o pai também product_cat
        $parentpage = get_term($page->parent);
        //se for pega os filhos do pai
        if($parentpage->taxonomy == 'product_cat')
        {
            $term_id_menu = $parentpage->term_id;
        } else
        {
            $term_id_menu = $page->term_id;
        }

        $has = category_has_children( $term_id_menu, 'product_cat' );

        if (!empty($has))
        {
            $submenu = '<div class="'. $classdiv .'"><ul>';

            foreach ($has as $submenu_item)
            {
                $current = '';
                if($page->term_id == $submenu_item->term_id)
                {
                    $current = ' class="current-menu-item"';
                }
                $term_id = $submenu_item->term_id;
                $submenu .= '<li'. $current . '><a href="'. get_term_link($term_id) .'">'.$submenu_item->name.'</a>';
            }

            $submenu .= '</ul></div>';

            echo $submenu;
        }
    }
}
function custom_excerpt_length( $length ) {
    return 20;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );
add_filter( 'loop_shop_per_page', create_function( '$cols', 'return 12;' ), 20 );

function custom_columns_order($columns){
    $new_columns = (is_array($columns)) ? $columns : array();
    unset( $new_columns['order_actions'] );

    //edit this for you column(s)
    //all of your columns will be added before the actions column
    $new_columns['custom_column'] = 'Recorrência';
    //stop editing

    $new_columns['order_actions'] = $columns['order_actions'];
    return $new_columns;
} add_filter( 'manage_edit-shop_order_columns', 'custom_columns_order', 11 );

function custom_columns_order_value($column){
    global $post;

    $post_meta = get_post_meta( $post->ID, '_recorrencia', true );

    if ($post_meta == 'recorrente') {
        $data = 'Pedido recorrente';
    } else {
        $data = 'Primeiro pedido';
    }
    //start editing, I was saving my fields for the orders as custom post meta
    //if you did the same, follow this code
    if ( $column == 'custom_column' ) {
        echo $data;
    }
    //stop editing
} add_action( 'manage_shop_order_posts_custom_column', 'custom_columns_order_value', 2 );