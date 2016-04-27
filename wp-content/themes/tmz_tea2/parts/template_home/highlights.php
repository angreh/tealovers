<?php
$nova_consulta = new WP_Query(
    array(
        'posts_per_page'      => 3,                 // Máximo de 5 artigos
        'no_found_rows'       => true,              // Não conta linhas
        'post_status'         => 'publish',         // Somente posts publicados
        'ignore_sticky_posts' => true,              // Ignora posts fixos
        'orderby'             => '',                // Ordena pelo valor da post meta
        'order'               => 'DESC'             // Ordem decrescente
    )
);
?>

<div id="highlights">
    <div class="container">
        <?php if ( $nova_consulta->have_posts() ): ?>
            <?php while ( $nova_consulta->have_posts() ): ?>

                <?php $nova_consulta->the_post(); ?>

                <?php $imgurl = wp_get_attachment_url(get_post_thumbnail_id( get_the_ID() )); ?>

                <div class="destaques-itens" onclick="document.location.href='<?php the_permalink(); ?>';">

                    <div class="destaque-image">
                        <img src="<?php echo $imgurl; ?>" />
                    </div>

                    <div class="destaque-titulo">
                        <h4>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_title();?>
                            </a>
                        </h4>
                    </div>
                </div>

            <?php endwhile; ?>
        <?php endif; ?>

        <?php wp_reset_postdata(); ?>
    </div>
</div>