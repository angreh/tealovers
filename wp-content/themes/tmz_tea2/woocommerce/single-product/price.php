<?php
/**
 * Single Product Price, including microdata for SEO
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/price.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you (the theme developer).
 * will need to copy the new files to your theme to maintain compatibility. We try to do this.
 * as little as possible, but it does happen. When this occurs the version of the template file will.
 * be bumped and the readme will list any important changes.
 *
 * @see     http://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.4.9
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

$price = $product->get_price_html();
$price = strip_tags($price);
$price = explode( ';', $price );
$price = $price[2];
$price = '<span class="amount"><span class="produto-moeda">R$ </span>' . $price . '</span>';

//exit(var_dump($price));


?>
<div class="dashed_hr"></div>
<div itemprop="offers" class="tmz_adjust_inblock" itemscope itemtype="http://schema.org/Offer">
	<p class="price"><?php echo $price; ?></p>

	<meta itemprop="price" content="<?php echo esc_attr( $price ); ?>" />
	<meta itemprop="priceCurrency" content="<?php echo esc_attr( get_woocommerce_currency() ); ?>" />
	<link itemprop="availability" href="http://schema.org/<?php echo $product->is_in_stock() ? 'InStock' : 'OutOfStock'; ?>" />

</div>