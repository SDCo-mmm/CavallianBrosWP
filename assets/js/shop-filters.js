/**
 * ショップページ絞り込み機能
 */

jQuery(document).ready(function($) {
    
    // カテゴリー選択時にアイテムをフィルタリング
    $('#category-filter').on('change', function() {
        var selectedCategory = $(this).val();
        var $itemFilter = $('#item-filter');
        var $itemOptions = $itemFilter.find('option');
        
        if (selectedCategory === '') {
            // すべて選択時は全アイテムを表示
            $itemOptions.show();
        } else {
            // 選択されたカテゴリーの子カテゴリーのみ表示
            $itemOptions.each(function() {
                var $option = $(this);
                var parentCategory = $option.data('parent');
                
                if ($option.val() === '' || parentCategory === selectedCategory) {
                    $option.show();
                } else {
                    $option.hide();
                }
            });
            
            // 最初のオプション（すべて）を選択
            $itemFilter.val('');
        }
    });
    
    // アイテム選択時にカテゴリーを自動選択
    $('#item-filter').on('change', function() {
        var $selectedOption = $(this).find('option:selected');
        var parentCategory = $selectedOption.data('parent');
        
        if (parentCategory) {
            $('#category-filter').val(parentCategory);
        }
    });
    
});
