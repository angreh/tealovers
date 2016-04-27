<?php
/*
Template Name: Default Loop
*/
?>
<?php get_header(); ?>

<?php
global $query_string;

$query_args = explode("&", $query_string);
$search_query = array();

if (strlen($query_string) > 0) {
    foreach ($query_args as $key => $string) {
        $query_split = explode("=", $string);
        $search_query[$query_split[0]] = urldecode($query_split[1]);
    } // foreach
} //if

$search_query['post_status'] = 'publish';
$search_query['post_type'] = 'product';
$search_query['posts_per_page'] = 12;

$nova_consulta = new WP_Query($search_query);
$products = $nova_consulta->posts;
?>
    <div id="products_news_wrapper">
        <div id="products">
            <div class="container">
                <hr>
                <?php

                $aux = 0;
                foreach ($products as $product) :
                    $meta = get_post_meta($product->ID);
                    $imgSrc = wp_get_attachment_image_src(get_post_thumbnail_id($product->ID), 'full');
                    $imgSrc = $imgSrc[0];

                    if (!isset($meta["_min_variation_price"])) {
                        $preco_regular = woo_money_format($meta["_regular_price"][0], 'R$ ', 'produto_moeda');
                        $preco_venda = woo_money_format($meta["_sale_price"][0], 'R$ ', 'produto_moeda');
                    } else {
                        $preco_regular = woo_money_format($meta["_min_variation_regular_price"][0], 'R$ ', 'produto_moeda');
                        $preco_venda = woo_money_format($meta["_min_variation_price"][0], 'R$ ', 'produto_moeda');
                    }

                    $woo_product = new WC_Product($product->ID);

                    //            var_dump($woo_product);

                    ?>
                    <a href="<?php echo get_permalink($product->ID); ?>"
                       class="produto-div-link<?php echo (($aux % 4) == 0) ? ' first' : ''; ?>">
                        <div class="wrap-produto">
                            <div class="produto-imagem">
                                <img src="<?php echo $imgSrc; ?>" title="<?php echo $product->post_title; ?>"
                                     class="produto-imagem"/>
                            </div>
                            <div class="produto-dados">

                                <div class="wrapper-produto-meta">

                                    <div class="produto-titulo">
                                        <div class="inner"><?php echo $product->post_title; ?></div>
                                    </div>

                                    <div class="star-rating" title="Avaliação <?php echo $woo_product->get_average_rating() ?> de 5">
                                        <span style="width:<?php echo ($woo_product->get_average_rating() * 100) / 5; ?>%"></span>
                                    </div>

                                    <span class="front_rating">
                                        (<?php echo $woo_product->get_rating_count(); ?>avaliações)
                                    </span>

                                    <!--                            <div class="produto-avaliacao"><img src="/wp-content/themes/tmz_tea/assets/imgs/stars.png" /> (12 avaliações)</div>-->
                                    <div class="dashed_hr"></div>
                                    <div class="produto-preco-normal"><?php echo $preco_regular; ?></div>

                                </div>

                                <div class="produto-bt-comprar">Comprar</div>
                            </div>
                        </div>
                        <!-- wrap-produtos -->
                    </a>

                    <?php
                    $aux++;
                endforeach;
                ?>
            </div>
        </div>
    </div>

<?php wp_reset_postdata(); ?>


<?php
global $query_string;

$query_args = explode("&", $query_string);
$search_query = array();

if (strlen($query_string) > 0) {
    foreach ($query_args as $key => $string) {
        $query_split = explode("=", $string);
        $search_query[$query_split[0]] = urldecode($query_split[1]);
    } // foreach
} //if

$search_query['post_status'] = 'publish';
$search_query['post_type'] = 'post';
$search_query['posts_per_page'] = 12;

$nova_consulta = new WP_Query($search_query);
?>

<?php
//$nova_consulta = new WP_Query(
//    array(
//        'posts_per_page'      => 12,                 // Máximo de 5 artigos
//        'no_found_rows'       => true,              // Não conta linhas
//        'post_status'         => 'publish',         // Somente posts publicados
//        'ignore_sticky_posts' => true,              // Ignora posts fixos
//        'orderby'             => '',                // Ordena pelo valor da post meta
//        'order'               => 'DESC'             // Ordem decrescente
//    )
//);
?>
    <div id="highlights">
        <div class="container">
            <hr>
            <div class="search_title">Notícias</div>
            <?php if ($nova_consulta->have_posts()): ?>
                <?php while ($nova_consulta->have_posts()): ?>

                    <?php $nova_consulta->the_post(); ?>

<?php
//                    $post = new WP_Post(get_the_ID());
//                    var_dump($post);
                    ?>

                    <?php $imgurl = wp_get_attachment_url(get_post_thumbnail_id(get_the_ID())); ?>

                    <div class="loop-itens" onclick="document.location.href='<?php the_permalink(); ?>';">

                        <div class="loop-image">
                            <img src="<?php echo $imgurl; ?>"/>
                        </div>

                        <div class="loop-titulo">
                            <h4>
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h4>
                        </div>
                        <div class="loop-content">
                            <?php the_excerpt(); ?>
                        </div>
                    </div>

                <?php endwhile; ?>
            <?php endif; ?>
            <br style="clear: both;">
            <?php wp_reset_postdata(); ?>
        </div>
    </div>

<?php get_footer(); ?>