<?php
/**
 * Cavallian Bros. テーマ機能
 *
 * @package Cavallian_Bros
 */

// テーマのセットアップ
function cavallian_setup() {
    // タイトルタグのサポート
    add_theme_support('title-tag');
    
    // アイキャッチ画像のサポート
    add_theme_support('post-thumbnails');
    
    // カスタムロゴのサポート
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 300,
        'flex-height' => true,
        'flex-width'  => true,
    ));
    
    // HTML5マークアップのサポート
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
    
    // WooCommerceサポート
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
    
    // メニューの登録
    register_nav_menus(array(
        'primary' => __('プライマリーメニュー', 'cavallian-bros'),
        'footer'  => __('フッターメニュー', 'cavallian-bros'),
    ));
}
add_action('after_setup_theme', 'cavallian_setup');

// スタイルシートとスクリプトの読み込み
function cavallian_scripts() {
    // Google Fonts
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;700&family=Questrial&display=swap', array(), null);
    
    // メインスタイルシート
    wp_enqueue_style('cavallian-style', get_stylesheet_uri(), array(), '1.0.0');
    
    // カスタムスタイルシート
    wp_enqueue_style('cavallian-main', get_template_directory_uri() . '/assets/css/main.css', array('cavallian-style'), '1.0.0');
    
    // jQuery（WordPressに含まれているものを使用）
    wp_enqueue_script('jquery');
    
    // メインJavaScript
    wp_enqueue_script('cavallian-main-js', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), '1.0.0', true);
    
    // JavaScriptにPHP変数を渡す
    wp_localize_script('cavallian-main-js', 'cavallian_vars', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('cavallian_nonce'),
    ));
}
add_action('wp_enqueue_scripts', 'cavallian_scripts');

// この部分を削除またはコメントアウト
// カスタム投稿タイプ：イベント
/*
function cavallian_register_post_types() {
    register_post_type('events', array(
        'labels' => array(
            'name'               => 'イベント',
            'singular_name'      => 'イベント',
            'add_new'            => '新規追加',
            'add_new_item'       => '新規イベントを追加',
            'edit_item'          => 'イベントを編集',
            'new_item'           => '新規イベント',
            'view_item'          => 'イベントを表示',
            'search_items'       => 'イベントを検索',
            'not_found'          => 'イベントが見つかりません',
            'not_found_in_trash' => 'ゴミ箱にイベントが見つかりません',
        ),
        'public'        => true,
        'has_archive'   => true,
        'menu_icon'     => 'dashicons-calendar-alt',
        'menu_position' => 20,
        'supports'      => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'rewrite'       => array('slug' => 'events'),
    ));
}
add_action('init', 'cavallian_register_post_types');
*/

// カスタマイザー設定
function cavallian_customize_register($wp_customize) {
    // ヒーローセクション
    $wp_customize->add_section('cavallian_hero', array(
        'title'    => __('ヒーローセクション', 'cavallian-bros'),
        'priority' => 30,
    ));
    
    // メインコピー
    $wp_customize->add_setting('hero_copy_line1', array(
        'default'   => 'ほんの少しのぬくもりが、',
        'transport' => 'refresh',
    ));
    
    $wp_customize->add_control('hero_copy_line1', array(
        'label'    => __('メインコピー1行目', 'cavallian-bros'),
        'section'  => 'cavallian_hero',
        'type'     => 'text',
    ));
    
    $wp_customize->add_setting('hero_copy_line2', array(
        'default'   => '心のざわめきをほどいていく。',
        'transport' => 'refresh',
    ));
    
    $wp_customize->add_control('hero_copy_line2', array(
        'label'    => __('メインコピー2行目', 'cavallian-bros'),
        'section'  => 'cavallian_hero',
        'type'     => 'text',
    ));
    
    $wp_customize->add_setting('hero_copy_line3', array(
        'default'   => 'そんな毎日が、宝物になる。',
        'transport' => 'refresh',
    ));
    
    $wp_customize->add_control('hero_copy_line3', array(
        'label'    => __('メインコピー3行目', 'cavallian-bros'),
        'section'  => 'cavallian_hero',
        'type'     => 'text',
    ));
    
    // スライダー画像（5枚）
    for ($i = 1; $i <= 5; $i++) {
        $wp_customize->add_setting("hero_slide_$i", array(
            'transport' => 'refresh',
        ));
        
        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, "hero_slide_$i", array(
            'label'    => __("スライド画像 $i", 'cavallian-bros'),
            'section'  => 'cavallian_hero',
            'settings' => "hero_slide_$i",
        )));
    }
    
    // SNS設定
    $wp_customize->add_section('cavallian_social', array(
        'title'    => __('SNS設定', 'cavallian-bros'),
        'priority' => 40,
    ));
    
    $wp_customize->add_setting('instagram_url', array(
        'default'   => '',
        'transport' => 'refresh',
    ));
    
    $wp_customize->add_control('instagram_url', array(
        'label'    => __('Instagram URL', 'cavallian-bros'),
        'section'  => 'cavallian_social',
        'type'     => 'url',
    ));
    
    $wp_customize->add_setting('twitter_url', array(
        'default'   => '',
        'transport' => 'refresh',
    ));
    
    $wp_customize->add_control('twitter_url', array(
        'label'    => __('X (Twitter) URL', 'cavallian-bros'),
        'section'  => 'cavallian_social',
        'type'     => 'url',
    ));
}
add_action('customize_register', 'cavallian_customize_register');

// WooCommerce: 商品ループのカスタマイズ
function cavallian_woocommerce_setup() {
    // 商品一覧の列数を変更
    add_filter('loop_shop_columns', function() {
        return 3;
    });
    
    // 1ページあたりの商品数
    add_filter('loop_shop_per_page', function() {
        return 12;
    });
    
    // 商品サムネイルサイズ
    add_image_size('cavallian-product', 400, 400, true);
}
add_action('after_setup_theme', 'cavallian_woocommerce_setup');

// WooCommerce: デフォルトのスタイルを無効化
add_filter('woocommerce_enqueue_styles', function($enqueue_styles) {
    unset($enqueue_styles['woocommerce-general']);
    unset($enqueue_styles['woocommerce-layout']);
    return $enqueue_styles;
});

// ACF設定（ACFプラグインがインストールされている場合）
if (function_exists('acf_add_options_page')) {
    acf_add_options_page(array(
        'page_title' => 'サイト設定',
        'menu_title' => 'サイト設定',
        'menu_slug'  => 'cavallian-settings',
        'capability' => 'edit_posts',
        'icon_url'   => 'dashicons-admin-generic',
        'position'   => 30,
    ));
    
    // サブページ
    acf_add_options_sub_page(array(
        'page_title'  => 'トップページ設定',
        'menu_title'  => 'トップページ',
        'parent_slug' => 'cavallian-settings',
    ));
    
    acf_add_options_sub_page(array(
        'page_title'  => 'ショップ設定',
        'menu_title'  => 'ショップ',
        'parent_slug' => 'cavallian-settings',
    ));
}

// ウィジェットエリアの登録
function cavallian_widgets_init() {
    register_sidebar(array(
        'name'          => __('サイドバー', 'cavallian-bros'),
        'id'            => 'sidebar-1',
        'description'   => __('サイドバーに表示されるウィジェット', 'cavallian-bros'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    
    register_sidebar(array(
        'name'          => __('ショップサイドバー', 'cavallian-bros'),
        'id'            => 'shop-sidebar',
        'description'   => __('ショップページのサイドバー', 'cavallian-bros'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'cavallian_widgets_init');

// カスタム関数：ロゴの取得（白バージョン対応）
function cavallian_get_logo($type = 'default') {
    $custom_logo_id = get_theme_mod('custom_logo');
    
    if ($custom_logo_id) {
        if ($type === 'white') {
            // 白ロゴ用の設定がある場合
            $white_logo = get_theme_mod('white_logo');
            if ($white_logo) {
                return wp_get_attachment_image($white_logo, 'full', false, array('class' => 'custom-logo white-logo'));
            }
        }
        return wp_get_attachment_image($custom_logo_id, 'full', false, array('class' => 'custom-logo'));
    }
    
    // デフォルトロゴ
    $logo_path = $type === 'white' ? '/assets/images/logo-white.svg' : '/assets/images/logo.svg';
    return '<img src="' . get_template_directory_uri() . $logo_path . '" alt="' . get_bloginfo('name') . '" class="custom-logo">';
}

// Ajax: Coming Soon商品の読み込み
function cavallian_load_coming_soon() {
    check_ajax_referer('cavallian_nonce', 'nonce');
    
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => 6,
        'meta_query'     => array(
            array(
                'key'     => '_coming_soon',
                'value'   => 'yes',
                'compare' => '='
            )
        )
    );
    
    $query = new WP_Query($args);
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            wc_get_template_part('content', 'product');
        }
    }
    
    wp_die();
}
add_action('wp_ajax_cavallian_load_coming_soon', 'cavallian_load_coming_soon');
add_action('wp_ajax_nopriv_cavallian_load_coming_soon', 'cavallian_load_coming_soon');

// 管理画面のカスタマイズ
function cavallian_admin_styles() {
    echo '<style>
        #adminmenu .dashicons-admin-generic:before { content: "\f487"; }
        #adminmenu .toplevel_page_cavallian-settings .wp-menu-image:before { color: #8f601e; }
    </style>';
}
add_action('admin_head', 'cavallian_admin_styles');
// ========================================
// カートアイコンをメニューに自動追加
// ========================================
function cavallian_add_cart_icon_to_menu($items, $args) {
    // プライマリーメニューのみに追加
    if ($args->theme_location == 'primary') {
        if (class_exists('WooCommerce')) {
            $cart_count = WC()->cart->get_cart_contents_count();
            $cart_url = wc_get_cart_url();
            
            // カートアイコンのHTML
            $cart_icon = '<li class="menu-item cart-menu-icon">';
            $cart_icon .= '<a href="' . esc_url($cart_url) . '" aria-label="カート">';
            $cart_icon .= '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">';
            $cart_icon .= '<path d="M9 2L6 9H3L5 21H19L21 9H18L15 2H9Z"/>';
            $cart_icon .= '<path d="M9 9V2"/>';
            $cart_icon .= '<path d="M15 9V2"/>';
            $cart_icon .= '</svg>';
            
            if ($cart_count > 0) {
                $cart_icon .= '<span class="cart-count">' . $cart_count . '</span>';
            }
            
            $cart_icon .= '</a>';
            $cart_icon .= '</li>';
            
            // メニューの最後にカートアイコンを追加
            $items .= $cart_icon;
        }
    }
    
    return $items;
}
add_filter('wp_nav_menu_items', 'cavallian_add_cart_icon_to_menu', 10, 2);

// ========================================
// Ajaxでカート数を更新
// ========================================
function cavallian_cart_count_fragments($fragments) {
    if (class_exists('WooCommerce')) {
        $cart_count = WC()->cart->get_cart_contents_count();
        
        ob_start();
        ?>
        <span class="cart-count"><?php echo $cart_count; ?></span>
        <?php
        $fragments['.cart-count'] = ob_get_clean();
    }
    
    return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'cavallian_cart_count_fragments');