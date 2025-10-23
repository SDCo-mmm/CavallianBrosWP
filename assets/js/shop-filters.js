/**
 * ショップページ絞り込み機能 + メガメニュー制御 + モバイルメニュー全画面表示
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
            $itemOptions.show();
        } else {
            $itemOptions.each(function() {
                var $option = $(this);
                var parentCategory = $option.data('parent');
                
                if ($option.val() === '' || parentCategory === selectedCategory) {
                    $option.show();
                } else {
                    $option.hide();
                }
            });
            
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
        }, 200);
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
        }, 200);
    });
    
    // タッチデバイス対応
    if ('ontouchstart' in window) {
        $megaMenuItem.on('click', function(e) {
            var $menu = $(this).find('.mega-menu');
            
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
    
    // ========================================
    // モバイルメニュー全画面表示制御
    // ========================================
    
    var $mobileMenu = $('#mobile-menu');
    var $mobileMenuToggle = $('.mobile-menu-toggle');
    var $body = $('body');
    
    // ハンバーガーメニューをクリック
    $mobileMenuToggle.on('click', function() {
        $(this).toggleClass('active');
        $mobileMenu.toggleClass('active');
        
        // スクロールを制御
        if ($mobileMenu.hasClass('active')) {
            $body.css('overflow', 'hidden'); // スクロール無効化
        } else {
            $body.css('overflow', ''); // スクロール有効化
        }
    });
    
    // メニュー内のリンクをクリックしたらメニューを閉じる
    $mobileMenu.find('a').on('click', function(e) {
        // ハッシュリンク（#で始まる）以外の場合のみ閉じる
        var href = $(this).attr('href');
        if (href && !href.startsWith('#')) {
            $mobileMenuToggle.removeClass('active');
            $mobileMenu.removeClass('active');
            $body.css('overflow', '');
        }
    });
    
    // メニュー外をクリックしたら閉じる
    $(document).on('click', function(e) {
        if ($mobileMenu.hasClass('active') && 
            !$(e.target).closest('#mobile-menu').length && 
            !$(e.target).closest('.mobile-menu-toggle').length) {
            $mobileMenuToggle.removeClass('active');
            $mobileMenu.removeClass('active');
            $body.css('overflow', '');
        }
    });
    
    // ウィンドウリサイズ時の処理
    $(window).on('resize', function() {
        if ($(window).width() > 768) {
            // デスクトップサイズになったらモバイルメニューを閉じる
            $mobileMenuToggle.removeClass('active');
            $mobileMenu.removeClass('active');
            $body.css('overflow', '');
        }
    });
    
});
