<?php
/**
 * 商品アーカイブページテンプレート（カスタムフィルター対応）
 *
 * @package Cavallian_Bros
 */

get_header();
?>

<!-- Shop Header -->
<section class="shop-header">
    <div class="container">
        <h1 class="shop-title">SHOP</h1>
        <p class="shop-description">キャバリアと暮らす人のためのアイテム</p>
    </div>
</section>

<!-- Shop Container -->
<div class="shop-container">
    <div class="container">
        
        <!-- カスタムフィルター -->
        <div class="product-filters">
            <!-- CATEGORY（親カテゴリー） -->
            <div class="filter-item">
                <label for="filter-category">CATEGORY</label>
                <select id="filter-category" name="product_cat">
                    <option value="">ALL</option>
                    <?php
                    $parent_categories = get_terms(array(
                        'taxonomy' => 'product_cat',
                        'parent' => 0,
                        'hide_empty' => true,
                    ));
                    
                    foreach ($parent_categories as $category) :
                        if ($category->slug === 'uncategorized') continue;
                        $selected = (isset($_GET['product_cat']) && $_GET['product_cat'] === $category->slug) ? 'selected' : '';
                    ?>
                        <option value="<?php echo esc_attr($category->slug); ?>" <?php echo $selected; ?>>
                            <?php echo esc_html($category->name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <!-- ITEM（子カテゴリー） -->
            <div class="filter-item">
                <label for="filter-item">ITEM</label>
                <select id="filter-item" name="product_item">
                    <option value="">ALL</option>
                    <?php
                    $current_cat = isset($_GET['product_cat']) ? $_GET['product_cat'] : '';
                    if ($current_cat) {
                        $parent_cat = get_term_by('slug', $current_cat, 'product_cat');
                        if ($parent_cat) {
                            $child_categories = get_terms(array(
                                'taxonomy' => 'product_cat',
                                'parent' => $parent_cat->term_id,
                                'hide_empty' => true,
                            ));
                            
                            foreach ($child_categories as $child) :
                                $selected = (isset($_GET['product_item']) && $_GET['product_item'] === $child->slug) ? 'selected' : '';
                            ?>
                                <option value="<?php echo esc_attr($child->slug); ?>" <?php echo $selected; ?>>
                                    <?php echo esc_html($child->name); ?>
                                </option>
                            <?php endforeach;
                        }
                    }
                    ?>
                </select>
            </div>
            
            <!-- SORT（並び替え） -->
            <div class="filter-item">
                <label for="filter-sort">SORT</label>
                <select id="filter-sort" name="orderby">
                    <option value="menu_order" <?php selected(isset($_GET['orderby']) ? $_GET['orderby'] : '', 'menu_order'); ?>>デフォルト</option>
                    <option value="popularity" <?php selected(isset($_GET['orderby']) ? $_GET['orderby'] : '', 'popularity'); ?>>人気順</option>
                    <option value="date" <?php selected(isset($_GET['orderby']) ? $_GET['orderby'] : '', 'date'); ?>>新着順</option>
                    <option value="price" <?php selected(isset($_GET['orderby']) ? $_GET['orderby'] : '', 'price'); ?>>価格が安い順</option>
                    <option value="price-desc" <?php selected(isset($_GET['orderby']) ? $_GET['orderby'] : '', 'price-desc'); ?>>価格が高い順</option>
                </select>
            </div>
        </div>
        
        <?php
        if (woocommerce_product_loop()) {
            woocommerce_product_loop_start();
            
            if (wc_get_loop_prop('total')) {
                while (have_posts()) {
                    the_post();
                    wc_get_template_part('content', 'product');
                }
            }
            
            woocommerce_product_loop_end();
            
            // ページネーション
            woocommerce_pagination();
        } else {
            echo '<div class="no-products-message">';
            echo '<p>商品が見つかりませんでした。</p>';
            echo '</div>';
        }
        ?>
        
    </div>
</div>

<!-- フィルター用JavaScript -->
<script>
(function() {
    const categorySelect = document.getElementById('filter-category');
    const itemSelect = document.getElementById('filter-item');
    const sortSelect = document.getElementById('filter-sort');
    
    // カテゴリー選択時の処理
    if (categorySelect) {
        categorySelect.addEventListener('change', function() {
            updateFilters();
        });
    }
    
    // アイテム選択時の処理
    if (itemSelect) {
        itemSelect.addEventListener('change', function() {
            updateFilters();
        });
    }
    
    // ソート選択時の処理
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            updateFilters();
        });
    }
    
    function updateFilters() {
        const params = new URLSearchParams();
        
        const category = categorySelect.value;
        const item = itemSelect.value;
        const sort = sortSelect.value;
        
        if (category) params.set('product_cat', category);
        if (item) params.set('product_item', item);
        if (sort && sort !== 'menu_order') params.set('orderby', sort);
        
        const queryString = params.toString();
        const newUrl = window.location.pathname + (queryString ? '?' + queryString : '');
        window.location.href = newUrl;
    }
})();
</script>

<?php
get_footer();
