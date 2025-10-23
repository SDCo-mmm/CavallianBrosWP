<?php
/**
 * 商品一覧ページ（絞り込み機能付き）
 *
 * @package Cavallian_Bros
 */

get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main shop-page">
        
        <!-- ページヘッダー -->
        <div class="shop-header">
            <div class="container">
                <h1 class="shop-title">Shop</h1>
                <p class="shop-description">キャバリアとの暮らしをより豊かにするアイテムをお届けします</p>
            </div>
        </div>

        <!-- 絞り込みフィルター -->
        <div class="shop-filters">
            <div class="container">
                <form class="filters-form" method="get">
                    <div class="filter-group">
                        <label for="category-filter">CATEGORY</label>
                        <select name="product_cat" id="category-filter" class="filter-select">
                            <option value="">すべて</option>
                            <?php
                            // 親カテゴリーを取得（uncategorized除外）
                            $parent_categories = get_terms(array(
                                'taxonomy'   => 'product_cat',
                                'hide_empty' => false,
                                'parent'     => 0,
                                'exclude'    => array(get_option('default_product_cat')),
                            ));
                            
                            $current_cat = isset($_GET['product_cat']) ? $_GET['product_cat'] : '';
                            
                            if (!empty($parent_categories) && !is_wp_error($parent_categories)) :
                                foreach ($parent_categories as $category) :
                                    $selected = ($current_cat === $category->slug) ? 'selected' : '';
                            ?>
                                    <option value="<?php echo esc_attr($category->slug); ?>" <?php echo $selected; ?>>
                                        <?php echo esc_html($category->name); ?>
                                    </option>
                            <?php
                                endforeach;
                            endif;
                            ?>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="item-filter">ITEM</label>
                        <select name="product_item" id="item-filter" class="filter-select">
                            <option value="">すべて</option>
                            <?php
                            // 子カテゴリーを取得
                            $child_categories = get_terms(array(
                                'taxonomy'   => 'product_cat',
                                'hide_empty' => false,
                                'parent__not' => 0, // 親がいるもの（子カテゴリー）のみ
                                'exclude'    => array(get_option('default_product_cat')),
                            ));
                            
                            $current_item = isset($_GET['product_item']) ? $_GET['product_item'] : '';
                            
                            if (!empty($child_categories) && !is_wp_error($child_categories)) :
                                foreach ($child_categories as $child_cat) :
                                    $selected = ($current_item === $child_cat->slug) ? 'selected' : '';
                                    $parent = get_term($child_cat->parent, 'product_cat');
                            ?>
                                    <option value="<?php echo esc_attr($child_cat->slug); ?>" 
                                            data-parent="<?php echo esc_attr($parent->slug); ?>"
                                            <?php echo $selected; ?>>
                                        <?php echo esc_html($child_cat->name); ?>
                                    </option>
                            <?php
                                endforeach;
                            endif;
                            ?>
                        </select>
                    </div>
                    
                    <button type="submit" class="filter-button">検索</button>
                    <?php if ($current_cat || $current_item) : ?>
                        <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="filter-reset">リセット</a>
                    <?php endif; ?>
                </form>
            </div>
        </div>

        <div class="shop-container">
            <div class="container">
                
                <?php
                // フィルター条件を適用
                if (isset($_GET['product_cat']) && !empty($_GET['product_cat'])) {
                    // カテゴリーフィルター
                    add_filter('woocommerce_product_query_tax_query', function($tax_query) {
                        $tax_query[] = array(
                            'taxonomy' => 'product_cat',
                            'field'    => 'slug',
                            'terms'    => sanitize_text_field($_GET['product_cat']),
                        );
                        return $tax_query;
                    });
                } elseif (isset($_GET['product_item']) && !empty($_GET['product_item'])) {
                    // アイテム（子カテゴリー）フィルター
                    add_filter('woocommerce_product_query_tax_query', function($tax_query) {
                        $tax_query[] = array(
                            'taxonomy' => 'product_cat',
                            'field'    => 'slug',
                            'terms'    => sanitize_text_field($_GET['product_item']),
                        );
                        return $tax_query;
                    });
                }
                
                if (woocommerce_product_loop()) : ?>

                    <?php
                    /**
                     * フック: woocommerce_before_shop_loop
                     */
                    do_action('woocommerce_before_shop_loop');
                    ?>

                    <div class="products-wrapper">
                        <?php
                        woocommerce_product_loop_start();

                        if (wc_get_loop_prop('total')) {
                            while (have_posts()) {
                                the_post();
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
                     */
                    do_action('woocommerce_after_shop_loop');
                    ?>

                <?php else : ?>

                    <div class="no-products-message">
                        <p>該当する商品が見つかりませんでした。</p>
                    </div>

                <?php endif; ?>

            </div>
        </div>

    </main>
</div>

<?php
get_footer();
