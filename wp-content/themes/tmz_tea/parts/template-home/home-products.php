<?php
/**
 * Created by PhpStorm.
 * User: angre
 * Date: 3/2/2016
 * Time: 12:20 PM
 */

global $woocommerce;

//ob_start();

$args = array(
    'posts_per_page' => 6, //numero de posts por paginas
    'post_type' => 'product'
);
$productsQuery = new WP_Query($args);

$products = $productsQuery->posts;

foreach ($products as $product) :
    $meta = get_post_meta($product->ID);
    $imgSrc = wp_get_attachment_image_src( get_post_thumbnail_id($product->ID), 'full' );
    $imgSrc = $imgSrc[0];

    if(!isset($meta["_min_variation_price"])) {
        $preco_regular = woo_money_format( $meta["_regular_price"][0], 'R$ ', 'produto_moeda');
        $preco_venda = woo_money_format( $meta["_sale_price"][0], 'R$ ', 'produto_moeda');
    } else {
        $preco_regular = woo_money_format( $meta["_min_variation_regular_price"][0], 'R$ ', 'produto_moeda' );
        $preco_venda = woo_money_format( $meta["_min_variation_price"][0], 'R$ ', 'produto_moeda' );
    }

    ?>
    <a href="<?php echo get_permalink($product->ID); ?>" class="produto-div-link">
        <div class="wrap-produto">
            <div class="produto-imagem">
                <img src="<?php echo $imgSrc; ?>" title="<?php echo $product->post_title; ?>" class="produto-imagem" />
            </div>
            <div class="produto-dados">

                <div class="wrapper-produto-meta">
                    <div class="produto-titulo"><div class="inner"><?php echo $product->post_title; ?></div></div>
                    <div class="produto-avaliacao"><img src="/wp-content/themes/tmz_tea/assets/imgs/stars.png" /> (12 avaliações)</div>
                    <div class="produto-preco-normal"><?php echo $preco_regular; ?></div>
                </div>

                <div class="produto-bt-comprar">Comprar</div>
<!--                <div class="produto-preco-oferta">--><?php //echo $preco_venda; ?><!--</div>-->
<!--                <div class="produto-rating"><img src="--><?php //echo bloginfo('template_url') ?><!--/images/star--><?php //echo get_average_comments($product->ID) ?><!--.jpg" alt="" /></div>-->
            </div>
        </div>
        <!-- wrap-produtos -->
    </a>
<?php

endforeach;

wp_reset_query();
?>