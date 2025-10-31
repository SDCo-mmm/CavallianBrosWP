/**
 * 商品ギャラリー（PhotoSwipe）カスタマイズ
 * 
 * スマホでも背景タップで閉じられるようにする
 */

jQuery(document).ready(function($) {
    'use strict';
    
    // PhotoSwipeが開いたら背景クリックを設定
    var setupCloseOnBackground = function() {
        // PhotoSwipe要素を取得
        var $pswp = $('.pswp');
        
        if (!$pswp.length || !$pswp.hasClass('pswp--open')) {
            return false;
        }
        
        console.log('PhotoSwipe opened - setting up background close');
        
        // クリック/タップハンドラー
        var handleClick = function(e) {
            var target = e.target;
            var $target = $(target);
            
            console.log('Clicked:', target.className);
            
            // 画像自体がクリックされた場合は何もしない
            if ($target.hasClass('pswp__img') || $target.closest('.pswp__img').length > 0) {
                console.log('Image clicked - do nothing');
                return;
            }
            
            // ボタンがクリックされた場合は何もしない
            if ($target.hasClass('pswp__button') || $target.closest('.pswp__button').length > 0) {
                console.log('Button clicked - do nothing');
                return;
            }
            
            // それ以外（背景）がクリックされた場合は閉じる
            console.log('Background clicked - closing');
            e.preventDefault();
            e.stopPropagation();
            
            // 閉じるボタンを探してクリック
            var closeButton = document.querySelector('.pswp__button--close');
            if (closeButton) {
                closeButton.click();
            }
        };
        
        // 既存のイベントを削除
        $pswp.off('click.closeOnBg touchend.closeOnBg');
        
        // 新しいイベントを追加
        $pswp.on('click.closeOnBg touchend.closeOnBg', handleClick);
        
        return true;
    };
    
    // ギャラリー画像がクリックされたときに実行
    $(document).on('click', '.woocommerce-product-gallery__image a', function(e) {
        console.log('Gallery image clicked');
        
        // PhotoSwipeが開くのを待つ
        var attempts = 0;
        var maxAttempts = 20; // 2秒間試行
        
        var checkInterval = setInterval(function() {
            attempts++;
            
            if (setupCloseOnBackground()) {
                console.log('Setup successful');
                clearInterval(checkInterval);
            } else if (attempts >= maxAttempts) {
                console.log('Setup failed - timeout');
                clearInterval(checkInterval);
            }
        }, 100);
    });
    
    // サムネイルクリック時も同様に設定
    $(document).on('click', '.flex-control-thumbs a', function(e) {
        console.log('Thumbnail clicked');
        setTimeout(setupCloseOnBackground, 300);
    });
});
