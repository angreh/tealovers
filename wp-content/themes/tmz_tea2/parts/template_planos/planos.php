<div id="products">
    <div class="container">
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
            'posts_per_page' => -1, //numero de posts por paginas
            'post_type' => 'product',
            'product_cat' => 'teabox',
            'order' => 'ASC'
        );
        $productsQuery = new WP_Query($args);

        $products = $productsQuery->posts;

        $aux = 0;
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

            $woo_product = new WC_Product($product->ID);

//            var_dump($woo_product);

            ?>
            <a href="<?php echo get_permalink($product->ID); ?>" class="plano-div-link<?php echo (($aux % 4)==0)?' first':''; ?>">
                <div class="wrap-plano">
                    <div class="plano-imagem">
                        <img src="<?php echo $imgSrc; ?>" title="<?php echo $product->post_title; ?>" class="plano-imagem" />
                    </div>
                    <div class="plano-dados">

                        <div class="wrapper-plano-meta">
                            <div class="pre-titulo">Plano</div>
                            <div class="plano-titulo"><div class="inner"><?php echo $product->post_title; ?></div></div>

                            <div class="dashed_hr"></div>
                            <div class="plano-content">
                                <?php echo $product->post_content; ?>
                            </div>
                            <div class="dashed_hr"></div>

                            <div class="plano-preco-normal"><?php echo $preco_regular; ?></div>
                        </div>

                        <div class="plano-bt-comprar">Comprar</div>
                    </div>
                </div>
                <!-- wrap-produtos -->
            </a>

            <?php
            $aux++;
        endforeach;

        wp_reset_query();
        ?>
    </div>
</div>