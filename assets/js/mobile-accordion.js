/**
 * モバイルメニュー - Shopアコーディオン
 * 
 * モバイルメニューのShop項目をタップでアコーディオン開閉
 */

jQuery(document).ready(function($) {
    
    // Shopメニューのアコーディオン処理
    $('.mobile-menu-list .menu-item-has-children > a.accordion-trigger').on('click', function(e) {
        e.preventDefault();  // デフォルトのリンク動作を無効化
        e.stopPropagation(); // イベント伝播を停止
        
        const $parent = $(this).parent('.menu-item-has-children');
        
        // アコーディオンのトグル
        $parent.toggleClass('active');
        
        // 他のアコーディオンを閉じる場合（単一展開）
        // $parent.siblings('.menu-item-has-children').removeClass('active');
    });
    
    // サブメニュー内のリンクは通常通り動作
    $('.mobile-sub-menu a').on('click', function(e) {
        // リンクの通常動作を許可（ページ遷移）
        e.stopPropagation(); // 親要素へのイベント伝播を停止
    });
    
});
