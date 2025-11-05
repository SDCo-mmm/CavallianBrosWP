/**
 * マイページモバイルメニュー - セレクトボックス化
 * 
 * モバイル表示時のナビゲーションをセレクトボックスに変換
 */

jQuery(document).ready(function($) {
    
    function convertToSelect() {
        // 768px以下でのみ動作
        if ($(window).width() <= 768) {
            const $nav = $('.woocommerce-MyAccount-navigation');
            
            // すでに変換済みならスキップ
            if ($nav.find('select').length > 0) {
                return;
            }
            
            // 元のリストを取得
            const $ul = $nav.find('ul');
            const $items = $ul.find('li');
            
            // セレクトボックスを作成
            const $select = $('<select class="mobile-account-select"></select>');
            
            // 各メニュー項目をoptionに変換
            $items.each(function() {
                const $item = $(this);
                const $link = $item.find('a');
                const text = $link.text().trim();
                const href = $link.attr('href');
                const isActive = $item.hasClass('is-active');
                
                const $option = $('<option></option>')
                    .attr('value', href)
                    .text(text);
                
                if (isActive) {
                    $option.attr('selected', 'selected');
                }
                
                $select.append($option);
            });
            
            // 選択時にページ遷移
            $select.on('change', function() {
                const url = $(this).val();
                if (url) {
                    window.location.href = url;
                }
            });
            
            // 元のリストを非表示にしてセレクトボックスを追加
            $ul.hide();
            $nav.append($select);
        } else {
            // デスクトップ表示に戻った時
            const $nav = $('.woocommerce-MyAccount-navigation');
            const $select = $nav.find('select');
            const $ul = $nav.find('ul');
            
            if ($select.length > 0) {
                $select.remove();
                $ul.show();
            }
        }
    }
    
    // 初期化
    convertToSelect();
    
    // ウィンドウリサイズ時に再チェック
    let resizeTimer;
    $(window).on('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(convertToSelect, 250);
    });
    
});
