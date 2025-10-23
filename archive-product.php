<?php
/**
 * 商品一覧ページ（Shop）
 *
 * @package Cavallian_Bros
 */

get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">
        
        <!-- ページヘッダー -->
        <div class="shop-header">
            <div class="container">
                <h1 class="shop-title">Shop</h1>
                <p class="shop-description">キャバリアとの暮らしをより豊かにするアイテムをお届けします</p>
            </div>
        </div>

        <div class="shop-container">
            <div class="container">
                
                <?php if (woocommerce_product_loop()) : ?>

                    <?php
                    /**
                     * フック: woocommerce_before_shop_loop
                     * 
                     * 並び替えや表示件数の切り替えをここに表示
                     */
                    do_action('woocommerce_before_shop_loop');
                    ?>

                    <div class="products-wrapper">
                        <?php
                        woocommerce_product_loop_start();

                        if (wc_get_loop_prop('total')) {
                            while (have_posts()) {
                                the_post();

                                /**
                                 * フック: woocommerce_shop_loop
                                 */
                                do_action('woocommerce_shop_loop');

                                wc_get_template_part('content', 'product');
                            }
                        }

                        woocommerce_product_loop_end();
                        ?>
                    </div>

                    <?php
                    /**
                     * フック: woocommerce_after_shop_loop
                     * 
                     * ページネーションをここに表示
                     */
                    do_action('woocommerce_after_shop_loop');
                    ?>

                <?php else : ?>

                    <div class="no-products-message">
                        <p>現在、商品の準備中です。しばらくお待ちください。</p>
                    </div>

                <?php endif; ?>

            </div>
        </div>

    </main>
</div>

<?php
get_footer();
