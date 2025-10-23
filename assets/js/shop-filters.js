/**
 * ショップページ絞り込み機能 + メガメニュー制御
 */

jQuery(document).ready(function($) {
    
    // ========================================
    // カテゴリー絞り込み機能
    // ========================================
    
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
    
    // ========================================
    // メガメニュー制御
    // ========================================
    
    var $megaMenuItem = $('.menu-item-has-megamenu');
    var $megaMenu = $('.mega-menu');
    var megaMenuTimer;
    
    // メガメニュー表示
    $megaMenuItem.on('mouseenter', function() {
        clearTimeout(megaMenuTimer);
        $(this).find('.mega-menu').addClass('show');
    });
    
    // メガメニュー非表示（遅延あり）
    $megaMenuItem.on('mouseleave', function() {
        var $menu = $(this).find('.mega-menu');
        megaMenuTimer = setTimeout(function() {
            $menu.removeClass('show');
        }, 300);
    });
    
    // メガメニュー内にマウスが入ったら非表示タイマーをクリア
    $megaMenu.on('mouseenter', function() {
        clearTimeout(megaMenuTimer);
    });
    
    // メガメニュー内からマウスが出たら非表示
    $megaMenu.on('mouseleave', function() {
        var $menu = $(this);
        megaMenuTimer = setTimeout(function() {
            $menu.removeClass('show');
        }, 300);
    });
    
    // タッチデバイス対応
    if ('ontouchstart' in window) {
        $megaMenuItem.on('click', function(e) {
            var $menu = $(this).find('.mega-menu');
            
            // メガメニューが非表示なら表示、表示中なら通常のリンク動作
            if (!$menu.hasClass('show')) {
                e.preventDefault();
                $('.mega-menu').removeClass('show');
                $menu.addClass('show');
            }
        });
        
        // メガメニュー外をタップしたら閉じる
        $(document).on('click touchstart', function(e) {
            if (!$(e.target).closest('.menu-item-has-megamenu').length) {
                $('.mega-menu').removeClass('show');
            }
        });
    }
    
    // デバッグ用（動作確認用 - 本番では削除可）
    console.log('Mega menu items found:', $megaMenuItem.length);
    console.log('Mega menus found:', $megaMenu.length);
    
});
