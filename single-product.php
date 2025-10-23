<?php
/**
 * 商品詳細ページ
 *
 * @package Cavallian_Bros
 */

get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">

        <?php
        while (have_posts()) :
            the_post();
            ?>

            <div class="product-detail-wrapper">
                <div class="container">
                    
                    <?php
                    /**
                     * フック: woocommerce_before_single_product
                     */
                    do_action('woocommerce_before_single_product');
                    ?>

                    <div id="product-<?php the_ID(); ?>" <?php wc_product_class('product-detail', get_the_ID()); ?>>

                        <?php
                        /**
                         * フック: woocommerce_before_single_product_summary
                         * 
                         * 商品画像ギャラリーを表示
                         */
                        do_action('woocommerce_before_single_product_summary');
                        ?>

                        <div class="summary entry-summary">
                            <?php
                            /**
                             * フック: woocommerce_single_product_summary
                             * 
                             * @hooked woocommerce_template_single_title - 5（商品名）
                             * @hooked woocommerce_template_single_rating - 10（評価）
                             * @hooked woocommerce_template_single_price - 10（価格）
                             * @hooked woocommerce_template_single_excerpt - 20（短い説明）
                             * @hooked woocommerce_template_single_add_to_cart - 30（カートに追加ボタン）
                             * @hooked woocommerce_template_single_meta - 40（カテゴリー・タグ）
                             * @hooked woocommerce_template_single_sharing - 50（シェアボタン）
                             */
                            do_action('woocommerce_single_product_summary');
                            ?>
                        </div>

                        <?php
                        /**
                         * フック: woocommerce_after_single_product_summary
                         * 
                         * 商品説明タブ、関連商品を表示
                         */
                        do_action('woocommerce_after_single_product_summary');
                        ?>

                    </div>

                    <?php
                    /**
                     * フック: woocommerce_after_single_product
                     */
                    do_action('woocommerce_after_single_product');
                    ?>

                </div>
            </div>

            <?php
        endwhile;
        ?>

    </main>
</div>

<?php
get_footer();
