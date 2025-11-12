<?php
/**
 * 商品アーカイブページテンプレート(カスタムフィルター対応 - カテゴリー連動版)
 *
 * @package Cavallian_Bros
 */

get_header();

// 現在のカテゴリー情報を取得
$current_cat_slug = '';
$current_parent_slug = '';
$current_child_slug = '';

if (is_product_category()) {
    $current_cat = get_queried_object();
    $current_cat_slug = $current_cat->slug;
    
    // 親カテゴリーか子カテゴリーかを判定
    if ($current_cat->parent == 0) {
        // 親カテゴリーの場合
        $current_parent_slug = $current_cat_slug;
    } else {
        // 子カテゴリーの場合
        $current_child_slug = $current_cat_slug;
        $parent_term = get_term($current_cat->parent, 'product_cat');
        if ($parent_term && !is_wp_error($parent_term)) {
            $current_parent_slug = $parent_term->slug;
        }
    }
}
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
            <!-- CATEGORY(親カテゴリー) -->
            <div class="filter-item">
                <label for="filter-category">CATEGORY</label>
                <select id="filter-category" name="product_cat">
                    <option value="">ALL</option>
                    <?php
                    $parent_categories = get_terms(array(
                        'taxonomy' => 'product_cat',
                        'parent' => 0,
                        'hide_empty' => false, // 商品がなくても表示
                    ));
                    
                    foreach ($parent_categories as $category) :
                        if ($category->slug === 'uncategorized') continue;
                        $selected = ($current_parent_slug === $category->slug) ? 'selected' : '';
                    ?>
                        <option value="<?php echo esc_attr($category->slug); ?>" <?php echo $selected; ?> data-term-id="<?php echo $category->term_id; ?>">
                            <?php echo esc_html($category->name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <!-- ITEM(子カテゴリー) -->
            <div class="filter-item">
                <label for="filter-item">ITEM</label>
                <select id="filter-item" name="product_item">
                    <option value="">ALL</option>
                    <?php
                    // 現在親カテゴリーが選択されている場合は子カテゴリーを表示
                    if ($current_parent_slug) {
                        $parent_term = get_term_by('slug', $current_parent_slug, 'product_cat');
                        if ($parent_term) {
                            $child_categories = get_terms(array(
                                'taxonomy' => 'product_cat',
                                'parent' => $parent_term->term_id,
                                'hide_empty' => false, // 商品がなくても表示
                            ));
                            
                            foreach ($child_categories as $child) :
                                $selected = ($current_child_slug === $child->slug) ? 'selected' : '';
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
            
            <!-- SORT(並び替え) -->
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
    
    // ページ読み込み時: 選択されたカテゴリーに応じて子カテゴリーを読み込む
    const currentCategory = categorySelect.value;
    if (currentCategory) {
        loadChildCategories(currentCategory);
    }
    
    // カテゴリー選択時の処理
    if (categorySelect) {
        categorySelect.addEventListener('change', function() {
            const selectedCategory = this.value;
            
            // 子カテゴリーをリセット
            itemSelect.innerHTML = '<option value="">ALL</option>';
            
            // 選択されたカテゴリーに応じて子カテゴリーを読み込む
            if (selectedCategory) {
                loadChildCategories(selectedCategory);
            }
            
            // フィルター更新
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
    
    /**
     * 子カテゴリーを動的に読み込む関数
     */
    function loadChildCategories(parentSlug) {
        // 選択されたoptionのterm_idを取得
        const selectedOption = categorySelect.querySelector('option[value="' + parentSlug + '"]');
        if (!selectedOption) return;
        
        const termId = selectedOption.getAttribute('data-term-id');
        if (!termId) return;
        
        // 現在選択中の子カテゴリーを保持
        const currentItemValue = itemSelect.value;
        
        // 既にPHPで子カテゴリーが表示されている場合は、AJAX読み込みをスキップ
        const existingOptions = itemSelect.querySelectorAll('option');
        if (existingOptions.length > 1) {
            // ALLオプション以外が既に存在する場合は何もしない
            return;
        }
        
        // AJAXで子カテゴリーを取得
        fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'action=get_child_categories&parent=' + encodeURIComponent(parentSlug) + '&nonce=<?php echo wp_create_nonce('cavallian_filter_nonce'); ?>'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data.categories) {
                // 子カテゴリーのオプションを追加
                data.data.categories.forEach(function(cat) {
                    const option = document.createElement('option');
                    option.value = cat.slug;
                    option.textContent = cat.name;
                    
                    // 現在のページが子カテゴリーページの場合は選択状態にする
                    if (cat.slug === currentItemValue) {
                        option.selected = true;
                    }
                    
                    itemSelect.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('子カテゴリーの読み込みエラー:', error);
        });
    }
    
    /**
     * フィルター更新関数
     */
    function updateFilters() {
        const category = categorySelect.value;
        const item = itemSelect.value;
        const sort = sortSelect.value;
        
        // カテゴリーページへのURLを構築
        let newUrl = '<?php echo home_url('/shop/'); ?>';
        
        // 子カテゴリーが選択されている場合は子カテゴリーページへ
        if (item) {
            newUrl = '<?php echo home_url('/product-category/'); ?>' + encodeURIComponent(item) + '/';
        }
        // 親カテゴリーのみ選択されている場合は親カテゴリーページへ
        else if (category) {
            newUrl = '<?php echo home_url('/product-category/'); ?>' + encodeURIComponent(category) + '/';
        }
        
        // ソートパラメータを追加
        if (sort && sort !== 'menu_order') {
            const separator = newUrl.includes('?') ? '&' : '?';
            newUrl += separator + 'orderby=' + encodeURIComponent(sort);
        }
        
        // ページ遷移
        window.location.href = newUrl;
    }
})();
</script>

<?php
get_footer();
