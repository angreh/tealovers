<?php
/*
Template Name: Default Loop
*/
?>
<?php get_header(); ?>

<?php
$nova_consulta = new WP_Query(
    array(
        'posts_per_page'      => 12,                 // Máximo de 5 artigos
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
        <hr>
        <?php if ( $nova_consulta->have_posts() ): ?>
            <?php while ( $nova_consulta->have_posts() ): ?>

                <?php $nova_consulta->the_post(); ?>

                <?php $imgurl = wp_get_attachment_url(get_post_thumbnail_id( get_the_ID() )); ?>

                <div class="loop-itens" onclick="document.location.href='<?php the_permalink(); ?>';">

                    <div class="loop-image">
                        <img src="<?php echo $imgurl; ?>" />
                    </div>

                    <div class="loop-titulo">
                        <h4>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_title();?>
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