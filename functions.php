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
    // add_theme_support('wc-product-gallery-zoom');  // ホバー拡大を無効化
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

// ========================================
// イベントの抜粋文字数をカスタマイズ
// ========================================
function cavallian_custom_excerpt_length($length) {
    global $post;
    
    // イベント投稿タイプの場合のみ50文字に
    if (isset($post) && $post->post_type === 'events') {
        return 50;
    }
    
    // その他の投稿タイプはデフォルトのまま
    return $length;
}
add_filter('excerpt_length', 'cavallian_custom_excerpt_length', 999);

// 抜粋の末尾を [...] から ... に変更
function cavallian_custom_excerpt_more($more) {
    global $post;
    
    // イベント投稿タイプの場合
    if (isset($post) && $post->post_type === 'events') {
        return '...';
    }
    
    return $more;
}
add_filter('excerpt_more', 'cavallian_custom_excerpt_more');

// ========================================
// イベントの並び順をカスタマイズ
// ========================================
function cavallian_events_query_order($query) {
    // 管理画面やメインクエリ以外は除外
    if (is_admin() || !$query->is_main_query()) {
        return;
    }
    
    // イベントアーカイブページの場合
    if (is_post_type_archive('events')) {
        // 無効な日付を除外しつつ並び替え
        $query->set('meta_query', array(
            'relation' => 'OR',
            array(
                'key'     => 'event_date_start',
                'value'   => array('', '0000-00-00', '0000-00-00 00:00:00'),
                'compare' => 'NOT IN',
                'type'    => 'DATE'
            ),
            array(
                'key'     => 'event_date',
                'value'   => array('', '0000-00-00', '0000-00-00 00:00:00'),
                'compare' => 'NOT IN',
                'type'    => 'DATE'
            ),
        ));
        
        // メタキーの存在チェックと並び替え
        $query->set('meta_key', 'event_date_start');
        $query->set('orderby', 'meta_value');
        $query->set('meta_type', 'DATE');
        $query->set('order', 'ASC');
    }
}
add_action('pre_get_posts', 'cavallian_events_query_order');

// ========================================
// イベントの並び順: event_dateとevent_date_startを統合
// ========================================
function cavallian_events_orderby_clause($orderby, $query) {
    global $wpdb;
    
    // イベントアーカイブページのメインクエリのみ
    if (!is_admin() && $query->is_main_query() && is_post_type_archive('events')) {
        // COALESCEを使用してevent_date_startを優先、なければevent_dateを使用
        $orderby = "COALESCE(
            (SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id = {$wpdb->posts}.ID AND meta_key = 'event_date_start' AND meta_value NOT IN ('', '0000-00-00', '0000-00-00 00:00:00') LIMIT 1),
            (SELECT meta_value FROM {$wpdb->postmeta} WHERE post_id = {$wpdb->posts}.ID AND meta_key = 'event_date' AND meta_value NOT IN ('', '0000-00-00', '0000-00-00 00:00:00') LIMIT 1)
        ) ASC";
    }
    
    return $orderby;
}
add_filter('posts_orderby', 'cavallian_events_orderby_clause', 10, 2);

// ========================================
// カスタムフィルター機能
// ========================================

/**
 * 商品クエリをカスタムフィルターに対応させる
 */
function cavallian_custom_product_query($q) {
    if (!is_admin() && $q->is_main_query() && is_shop()) {
        
        // カテゴリーフィルター
        if (isset($_GET['product_cat']) && !empty($_GET['product_cat'])) {
            $tax_query = array(
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'slug',
                    'terms' => sanitize_text_field($_GET['product_cat']),
                ),
            );
            $q->set('tax_query', $tax_query);
        }
        
        // アイテム（子カテゴリー）フィルター
        if (isset($_GET['product_item']) && !empty($_GET['product_item'])) {
            $tax_query = array(
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'slug',
                    'terms' => sanitize_text_field($_GET['product_item']),
                ),
            );
            $q->set('tax_query', $tax_query);
        }
        
        // 並び替え
        if (isset($_GET['orderby'])) {
            $orderby = sanitize_text_field($_GET['orderby']);
            
            switch ($orderby) {
                case 'date':
                    $q->set('orderby', 'date');
                    $q->set('order', 'DESC');
                    break;
                case 'price':
                    $q->set('orderby', 'meta_value_num');
                    $q->set('meta_key', '_price');
                    $q->set('order', 'ASC');
                    break;
                case 'price-desc':
                    $q->set('orderby', 'meta_value_num');
                    $q->set('meta_key', '_price');
                    $q->set('order', 'DESC');
                    break;
                case 'popularity':
                    $q->set('orderby', 'meta_value_num');
                    $q->set('meta_key', 'total_sales');
                    $q->set('order', 'DESC');
                    break;
            }
        }
    }
}
add_action('pre_get_posts', 'cavallian_custom_product_query');

/**
 * AJAXで子カテゴリーを取得
 */
function cavallian_get_child_categories() {
    check_ajax_referer('cavallian_filter_nonce', 'nonce');
    
    $parent_slug = isset($_POST['parent']) ? sanitize_text_field($_POST['parent']) : '';
    
    if (empty($parent_slug)) {
        wp_send_json_success(array('categories' => array()));
        return;
    }
    
    $parent_cat = get_term_by('slug', $parent_slug, 'product_cat');
    
    if (!$parent_cat) {
        wp_send_json_error('親カテゴリーが見つかりません');
        return;
    }
    
    $child_categories = get_terms(array(
        'taxonomy' => 'product_cat',
        'parent' => $parent_cat->term_id,
        'hide_empty' => false, // 商品がなくても表示
    ));
    
    $categories = array();
    foreach ($child_categories as $child) {
        $categories[] = array(
            'slug' => $child->slug,
            'name' => $child->name,
        );
    }
    
    wp_send_json_success(array('categories' => $categories));
}
add_action('wp_ajax_get_child_categories', 'cavallian_get_child_categories');
add_action('wp_ajax_nopriv_get_child_categories', 'cavallian_get_child_categories');

/* =========================================
   functions.php に追加するコード
   
   商品詳細ページの画像ギャラリーカスタマイズ
========================================= */

// WooCommerceのギャラリーズーム機能を無効化（優先度20で実行）
add_action('after_setup_theme', 'cavallian_disable_wc_gallery_zoom', 20);
function cavallian_disable_wc_gallery_zoom() {
    remove_theme_support('wc-product-gallery-zoom');
}

// 商品ギャラリーカスタマイズのJavaScriptを読み込む
function cavallian_enqueue_gallery_scripts() {
    // 商品詳細ページのみで読み込む
    if (is_product()) {
        wp_enqueue_script(
            'cavallian-product-gallery',
            get_template_directory_uri() . '/assets/js/product-gallery.js',
            array('jquery', 'photoswipe-ui-default'), // PhotoSwipeの後に読み込む
            '1.0.0',
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'cavallian_enqueue_gallery_scripts', 20);

/* =========================================
   関連商品を確実に表示する（強制版）
   
   functions.php に追加
========================================= */

// 既存の関連商品表示を削除
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);

// 新しい関連商品表示を追加
add_action('woocommerce_after_single_product_summary', 'custom_output_related_products', 20);

function custom_output_related_products() {
    global $product;
    
    if (!$product) {
        return;
    }
    
    // Custom Related Products プラグインから関連商品IDを取得
    $related_ids = get_post_meta($product->get_id(), '_related_ids', true);
    
    // デバッグ出力
    echo '<!-- Custom Related Function: ';
    echo 'Product ID: ' . $product->get_id() . ' | ';
    echo 'Related IDs: ' . print_r($related_ids, true);
    echo ' -->';
    
    if (empty($related_ids) || !is_array($related_ids)) {
        // プラグインで設定されていない場合は通常の関連商品を表示
        woocommerce_related_products(array(
            'posts_per_page' => 4,
            'columns' => 4,
            'orderby' => 'rand'
        ));
        return;
    }
    
    // プラグインで設定された商品のみ表示
    $args = array(
        'post_type' => 'product',
        'post__in' => $related_ids,
        'posts_per_page' => count($related_ids), // 設定したすべてを表示
        'orderby' => 'post__in', // 設定した順番を保持
        'ignore_sticky_posts' => 1
    );
    
    $related_query = new WP_Query($args);
    
    if ($related_query->have_posts()) {
        ?>
        <section class="related products">
            <h2><?php esc_html_e('Related products', 'woocommerce'); ?></h2>
            <ul class="products columns-4">
                <?php while ($related_query->have_posts()) : $related_query->the_post(); ?>
                    <?php wc_get_template_part('content', 'product'); ?>
                <?php endwhile; ?>
            </ul>
        </section>
        <?php
    }
    
    wp_reset_postdata();
}

// マイページでのみJavaScriptを読み込む
function cavallian_myaccount_mobile_select_script() {
    // マイページのみで読み込む
    if (is_account_page()) {
        wp_enqueue_script(
            'cavallian-myaccount-mobile-select',
            get_template_directory_uri() . '/assets/js/myaccount-mobile-select.js',
            array('jquery'),
            '1.0.0',
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'cavallian_myaccount_mobile_select_script', 20);

/**
 * WooCommerce登録フォームにユーザー名フィールドを追加（強化版）
 * functions.phpに追加してください
 */

// 方法1: ユーザー名自動生成を無効化
add_filter( 'woocommerce_registration_generate_username', '__return_false' );

// 方法2: 登録フォームにユーザー名フィールドを強制追加
add_action( 'woocommerce_register_form_start', 'add_username_field_to_registration' );
function add_username_field_to_registration() {
    ?>
    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="reg_username"><?php esc_html_e( 'ユーザー名', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" required />
    </p>
    <?php
}

// 方法3: ユーザー名のバリデーション
add_filter( 'woocommerce_registration_errors', 'validate_username_field', 10, 3 );
function validate_username_field( $errors, $username, $email ) {
    if ( isset( $_POST['username'] ) ) {
        $username = sanitize_user( $_POST['username'] );
        
        if ( empty( $username ) ) {
            $errors->add( 'username_error', __( 'ユーザー名を入力してください。', 'woocommerce' ) );
        } elseif ( strlen( $username ) < 3 ) {
            $errors->add( 'username_error', __( 'ユーザー名は3文字以上で入力してください。', 'woocommerce' ) );
        } elseif ( username_exists( $username ) ) {
            $errors->add( 'username_error', __( 'このユーザー名は既に使用されています。', 'woocommerce' ) );
        }
    }
    
    return $errors;
}

// 方法4: 登録処理時にユーザー名を使用
add_filter( 'woocommerce_new_customer_data', 'custom_new_customer_data' );
function custom_new_customer_data( $data ) {
    if ( isset( $_POST['username'] ) && ! empty( $_POST['username'] ) ) {
        $data['user_login'] = sanitize_user( $_POST['username'] );
    }
    return $data;
}

/**
 * ユーザー名のリアルタイム重複チェック機能
 * functions.phpに追加してください
 */

// Ajax用のエンドポイントを作成（ログインしていないユーザー向け）
add_action( 'wp_ajax_nopriv_check_username_availability', 'check_username_availability' );
add_action( 'wp_ajax_check_username_availability', 'check_username_availability' );

function check_username_availability() {
    // セキュリティチェック
    check_ajax_referer( 'username_check_nonce', 'nonce' );
    
    $username = sanitize_user( $_POST['username'] );
    
    if ( empty( $username ) ) {
        wp_send_json_error( array( 'message' => 'ユーザー名を入力してください' ) );
    }
    
    if ( strlen( $username ) < 3 ) {
        wp_send_json_error( array( 'message' => 'ユーザー名は3文字以上で入力してください' ) );
    }
    
    if ( ! validate_username( $username ) ) {
        wp_send_json_error( array( 'message' => 'ユーザー名に使用できない文字が含まれています' ) );
    }
    
    if ( username_exists( $username ) ) {
        wp_send_json_error( array( 'message' => 'このユーザー名は既に使用されています' ) );
    }
    
    wp_send_json_success( array( 'message' => 'このユーザー名は利用可能です' ) );
}

// JavaScriptをマイアカウントページに読み込む
add_action( 'wp_footer', 'add_username_check_script' );
function add_username_check_script() {
    // マイアカウントページでのみ実行
    if ( ! is_account_page() ) {
        return;
    }
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        var typingTimer;
        var doneTypingInterval = 500; // 入力停止後500ms待つ
        var $usernameInput = $('#reg_username');
        var $feedbackDiv = $('<div class="username-feedback"></div>');
        
        // フィードバック表示用のdivを追加
        $usernameInput.after($feedbackDiv);
        
        // 入力中
        $usernameInput.on('keyup', function() {
            clearTimeout(typingTimer);
            var username = $(this).val();
            
            // 入力が空の場合は何も表示しない
            if (username.length === 0) {
                $feedbackDiv.removeClass('checking available taken error').text('');
                return;
            }
            
            // チェック中表示
            $feedbackDiv.removeClass('available taken error').addClass('checking').text('確認中...');
            
            // 入力停止後にチェック実行
            typingTimer = setTimeout(function() {
                checkUsername(username);
            }, doneTypingInterval);
        });
        
        // 入力開始時
        $usernameInput.on('keydown', function() {
            clearTimeout(typingTimer);
        });
        
        // Ajax通信でユーザー名をチェック
        function checkUsername(username) {
            $.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'POST',
                data: {
                    action: 'check_username_availability',
                    username: username,
                    nonce: '<?php echo wp_create_nonce('username_check_nonce'); ?>'
                },
                success: function(response) {
                    if (response.success) {
                        $feedbackDiv.removeClass('checking taken error').addClass('available').text('✓ ' + response.data.message);
                    } else {
                        $feedbackDiv.removeClass('checking available').addClass('taken').text('✗ ' + response.data.message);
                    }
                },
                error: function() {
                    $feedbackDiv.removeClass('checking available taken').addClass('error').text('エラーが発生しました');
                }
            });
        }
    });
    </script>
    
    <style>
    .username-feedback {
        margin-top: 8px;
        font-size: 14px;
        font-family: 'Noto Sans JP', sans-serif;
        font-weight: 400;
        letter-spacing: 0.05em;
        min-height: 20px;
        transition: all 0.3s ease;
    }
    
    .username-feedback.checking {
        color: #666;
    }
    
    .username-feedback.available {
        color: #28a745;
        font-weight: 500;
    }
    
    .username-feedback.taken,
    .username-feedback.error {
        color: #c62828;
        font-weight: 500;
    }
    
    /* ユーザー名入力欄のボーダー色も変更 */
    #reg_username.username-available {
        border-color: #28a745 !important;
    }
    
    #reg_username.username-taken {
        border-color: #c62828 !important;
    }
    </style>
    <?php
}

/**
 * パスワード確認フィールドを追加
 * functions.phpに追加してください
 */

// パスワード確認フィールドを登録フォームに追加
add_action( 'woocommerce_register_form', 'add_password_confirmation_field' );
function add_password_confirmation_field() {
    ?>
    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="reg_password2"><?php esc_html_e( 'パスワード(確認)', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
        <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password2" id="reg_password2" autocomplete="new-password" value="<?php echo ( ! empty( $_POST['password2'] ) ) ? esc_attr( wp_unslash( $_POST['password2'] ) ) : ''; ?>" required />
        <span class="password-match-feedback"></span>
    </p>
    <?php
}

// パスワード一致チェックのバリデーション
add_filter( 'woocommerce_registration_errors', 'validate_password_confirmation', 10, 3 );
function validate_password_confirmation( $errors, $username, $email ) {
    if ( isset( $_POST['password'] ) && isset( $_POST['password2'] ) ) {
        $password = $_POST['password'];
        $password2 = $_POST['password2'];
        
        if ( empty( $password2 ) ) {
            $errors->add( 'password2_error', __( 'パスワード(確認)を入力してください。', 'woocommerce' ) );
        } elseif ( $password !== $password2 ) {
            $errors->add( 'password_mismatch', __( 'パスワードが一致しません。もう一度確認してください。', 'woocommerce' ) );
        }
    }
    
    return $errors;
}

// リアルタイムパスワード一致チェックのJavaScript
add_action( 'wp_footer', 'add_password_match_check_script' );
function add_password_match_check_script() {
    if ( ! is_account_page() ) {
        return;
    }
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        var $password1 = $('#reg_password');
        var $password2 = $('#reg_password2');
        var $feedback = $('.password-match-feedback');
        
        // パスワード確認欄の入力時にチェック
        $password2.on('keyup blur', function() {
            checkPasswordMatch();
        });
        
        // 元のパスワード欄が変更された時もチェック
        $password1.on('keyup', function() {
            if ($password2.val().length > 0) {
                checkPasswordMatch();
            }
        });
        
        function checkPasswordMatch() {
            var pass1 = $password1.val();
            var pass2 = $password2.val();
            
            // パスワード確認欄が空の場合は何も表示しない
            if (pass2.length === 0) {
                $feedback.removeClass('match mismatch').text('');
                $password2.removeClass('password-match password-mismatch');
                return;
            }
            
            // パスワードが一致するかチェック
            if (pass1 === pass2) {
                $feedback.removeClass('mismatch').addClass('match').text('✓ パスワードが一致しています');
                $password2.removeClass('password-mismatch').addClass('password-match');
            } else {
                $feedback.removeClass('match').addClass('mismatch').text('✗ パスワードが一致しません');
                $password2.removeClass('password-match').addClass('password-mismatch');
            }
        }
    });
    </script>
    
    <style>
    /* パスワード確認フィールドのフィードバック */
    .password-match-feedback {
        display: block;
        margin-top: 8px;
        font-size: 14px;
        font-family: 'Noto Sans JP', sans-serif;
        font-weight: 400;
        letter-spacing: 0.05em;
        min-height: 20px;
        transition: all 0.3s ease;
    }
    
    .password-match-feedback.match {
        color: #28a745;
        font-weight: 500;
    }
    
    .password-match-feedback.mismatch {
        color: #c62828;
        font-weight: 500;
    }
    
    /* パスワード入力欄のボーダー色変更 */
    #reg_password2.password-match {
        border-color: #28a745 !important;
    }
    
    #reg_password2.password-mismatch {
        border-color: #c62828 !important;
    }
    </style>
    <?php
}

/**
 * Cloudflare Turnstileの表示位置を調整（安全版）
 */
add_action('wp_loaded', 'adjust_turnstile_position_safe', 999);
function adjust_turnstile_position_safe() {
    global $wp_filter;
    
    if (isset($wp_filter['woocommerce_register_form'])) {
        $hooks = $wp_filter['woocommerce_register_form']->callbacks;
        
        if (isset($hooks[10])) {
            foreach ($hooks[10] as $key => $hook) {
                if (is_array($hook['function']) || 
                    (is_string($hook['function']) && 
                     (strpos($hook['function'], 'turnstile') !== false || 
                      strpos($hook['function'], 'captcha') !== false))) {
                    
                    remove_action('woocommerce_register_form', $hook['function'], 10);
                    add_action('woocommerce_register_form', $hook['function'], 20);
                    break;
                }
            }
        }
    }
}

/**
 * 表示名フィールドに注釈を追加
 */
add_action('woocommerce_edit_account_form', 'add_display_name_notice');
function add_display_name_notice() {
    ?>
    <style>
    /* 表示名の注釈スタイル */
    .display-name-notice {
        display: block;
        margin-top: 8px;
        font-size: 13px;
        color: #999;
        line-height: 1.6;
        font-family: 'Noto Sans JP', sans-serif;
        font-weight: 300;
    }
    </style>
    
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        // 表示名フィールドを探す
        var $displayNameField = $('#account_display_name').closest('.form-row');
        
        // 既存の説明文の後に注釈を追加
        if ($displayNameField.length) {
            var $existingNote = $displayNameField.find('em');
            if ($existingNote.length) {
                // 既存の説明文の後に追加
                $existingNote.after('<span class="display-name-notice">※表示名はユーザー名ではありません。こちらを変更してもユーザー名は変更されません。</span>');
            } else {
                // 説明文がない場合は入力欄の後に追加
                $('#account_display_name').after('<span class="display-name-notice">※表示名はユーザー名ではありません。こちらを変更してもユーザー名は変更されません。</span>');
            }
        }
    });
    </script>
    <?php
}

/**
 * アカウント詳細ページにユーザー名フィールドを追加（読み取り専用）
 * functions.phpに追加してください
 */
add_filter('woocommerce_save_account_details_required_fields', 'add_username_to_account_details');
function add_username_to_account_details($required_fields) {
    // ユーザー名は必須ではないが、フィールドとして追加
    return $required_fields;
}

add_action('woocommerce_edit_account_form_start', 'display_username_field_readonly');
function display_username_field_readonly() {
    $user = wp_get_current_user();
    ?>
    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide form-row-username">
        <label for="account_username">
            <?php esc_html_e('ユーザー名', 'woocommerce'); ?>
            <span class="username-notice">(変更できません)</span>
        </label>
        <input 
            type="text" 
            class="woocommerce-Input woocommerce-Input--text input-text" 
            name="account_username" 
            id="account_username" 
            value="<?php echo esc_attr($user->user_login); ?>" 
            readonly 
            disabled
        />
    </p>
    
    <style>
    /* ユーザー名フィールドのスタイル */
    .form-row-username {
        margin-bottom: 20px;
    }
    
    .form-row-username label {
        font-family: 'Noto Sans JP', sans-serif;
        font-size: 14px;
        font-weight: 400;
        color: #333;
        letter-spacing: 0.05em;
        margin-bottom: 8px;
        display: block;
    }
    
    .form-row-username .username-notice {
        color: #999;
        font-size: 13px;
        font-weight: 300;
    }
    
    .form-row-username input {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #ddd;
        border-radius: 0;
        font-size: 16px;
        font-family: 'Noto Sans JP', sans-serif;
        font-weight: 300;
        background-color: #f5f5f5 !important;  /* グレー背景 */
        color: #999 !important;  /* グレーテキスト */
        cursor: not-allowed;
    }
    
    /* Flexboxで順序制御（姓名の前に配置） */
    .form-row-username {
        order: -2;  /* 姓名より前に */
        flex: 0 0 100%;
    }
    </style>
    <?php
}

/**
 * パンくずリスト用CSSの読み込み
 */
function cavallian_enqueue_breadcrumbs_css() {
    wp_enqueue_style(
        'cavallian-breadcrumbs',
        get_template_directory_uri() . '/assets/css/layout/breadcrumbs.css',
        array(),
        '1.0.2'
    );
}
add_action('wp_enqueue_scripts', 'cavallian_enqueue_breadcrumbs_css');

/**
 * 「お買い物かごに追加」を「ADD TO CART」に変更
 */
add_filter('woocommerce_product_add_to_cart_text', 'custom_add_to_cart_text');
add_filter('woocommerce_product_single_add_to_cart_text', 'custom_add_to_cart_text');
function custom_add_to_cart_text() {
    return 'ADD TO CART';
}

// ========================================
// 過去のイベント用エンドポイント
// ========================================
function cavallian_past_events_endpoint() {
    add_rewrite_endpoint('year', EP_PAGES);
}
add_action('init', 'cavallian_past_events_endpoint');

function cavallian_past_events_template($template) {
    global $wp_query;
    
    if (is_page('events-past')) {
        $new_template = locate_template(array('archive-events-past.php'));
        if ($new_template) {
            return $new_template;
        }
    }
    
    return $template;
}
add_filter('template_include', 'cavallian_past_events_template');

// ========================================
// イベント一覧ページで終了イベントを除外
// ========================================
function cavallian_exclude_past_events($query) {
    if (!is_admin() && $query->is_main_query() && is_post_type_archive('events') && !get_query_var('past_events')) {
        $today = date('Y-m-d');
        
        $query->set('meta_query', array(
            'relation' => 'OR',
            // パターン1: 終了日があるイベント → 終了日が今日以降
            array(
                'relation' => 'AND',
                array(
                    'key'     => 'event_date_end',
                    'value'   => array('', '0000-00-00', '0000-00-00 00:00:00'),
                    'compare' => 'NOT IN',
                ),
                array(
                    'key'     => 'event_date_end',
                    'value'   => $today,
                    'compare' => '>=',
                    'type'    => 'DATE'
                ),
            ),
            // パターン2: 終了日がないイベント → 開始日が今日以降
            array(
                'relation' => 'AND',
                array(
                    'key'     => 'event_date_end',
                    'value'   => array('', '0000-00-00', '0000-00-00 00:00:00'),
                    'compare' => 'IN',
                ),
                array(
                    'key'     => 'event_date_start',
                    'value'   => $today,
                    'compare' => '>=',
                    'type'    => 'DATE'
                ),
            ),
            // パターン3: 単日イベント → 開催日が今日以降
            array(
                'relation' => 'AND',
                array(
                    'key'     => 'event_date_start',
                    'value'   => array('', '0000-00-00', '0000-00-00 00:00:00'),
                    'compare' => 'IN',
                ),
                array(
                    'key'     => 'event_date',
                    'value'   => $today,
                    'compare' => '>=',
                    'type'    => 'DATE'
                ),
            ),
        ));
    }
}
add_action('pre_get_posts', 'cavallian_exclude_past_events');

/**
 * Contact Form 7 送信後のリダイレクト
 */
add_action('wp_footer', 'cavallian_contact_redirect_script');
function cavallian_contact_redirect_script() {
    // お問い合わせページのみで実行
    if (is_page('contact')) {
        ?>
        <script type="text/javascript">
        document.addEventListener('wpcf7mailsent', function(event) {
            // 送信完了ページへリダイレクト
            location = '<?php echo esc_url(home_url('/contact-thanks/')); ?>';
        }, false);
        </script>
        <?php
    }
}

/**
 * Contact Form 7 - ユーザーネーム表示のカスタマイズ
 */
// 管理者用: 空の場合は【ゲスト】を表示
add_filter('wpcf7_special_mail_tags', 'custom_username_admin_tag', 10, 3);
function custom_username_admin_tag($output, $name, $html) {
    if ($name == 'username-admin') {
        $submission = WPCF7_Submission::get_instance();
        if ($submission) {
            $posted_data = $submission->get_posted_data();
            $username = isset($posted_data['username']) ? $posted_data['username'] : '';
            $output = !empty($username) ? $username : '【ゲスト】';
        }
    }
    return $output;
}

// 顧客用: 空の場合は何も表示しない（改行も含めて）
add_filter('wpcf7_special_mail_tags', 'custom_username_customer_tag', 10, 3);
function custom_username_customer_tag($output, $name, $html) {
    if ($name == 'username-customer') {
        $submission = WPCF7_Submission::get_instance();
        if ($submission) {
            $posted_data = $submission->get_posted_data();
            $username = isset($posted_data['username']) ? $posted_data['username'] : '';
            $output = !empty($username) ? "ユーザーネーム: " . $username . " さま\n" : '';
        }
    }
    return $output;
}

// Checkout Blocksの読み込み完了後にスクリプトを出力
add_action( 'wp_footer', 'custom_auto_hyphen_postal_code_script' );

function custom_auto_hyphen_postal_code_script() {
    // チェックアウトページ以外では読み込まないようにする
    if ( ! is_checkout() ) {
        return;
    }
    ?>
    <script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        // イベント委譲を使って動的に生成されるCheckout Blocksの要素を監視
        document.body.addEventListener('input', function(e) {
            
            // ターゲットが郵便番号の入力フィールドか判定
            // Checkout Blocksの仕様変更に対応するため、IDの一部一致やautocomplete属性で判定
            if (e.target.id && (e.target.id.includes('postal_code') || e.target.autocomplete === 'postal-code')) {
                
                let input = e.target;
                let val = input.value;

                // 1. 数字以外を削除
                let numbers = val.replace(/[^0-9]/g, '');

                // 2. 7桁以上入力できないように制限（日本の郵便番号の場合）
                if (numbers.length > 7) {
                    numbers = numbers.substring(0, 7);
                }

                // 3. 3桁を超えたらハイフンを挿入
                if (numbers.length > 3) {
                    input.value = numbers.substring(0, 3) + '-' + numbers.substring(3);
                } else {
                    input.value = numbers;
                }
            }
        });
    });
    </script>
    <?php
}

// メールに追加CSSを挿入して2つ目以降の銀行口座詳細を非表示
add_filter('woocommerce_email_styles', 'hide_duplicate_bank_details_css');
function hide_duplicate_bank_details_css($css) {
    $css .= '
        /* 2つ目以降の銀行口座詳細を非表示 */
        .order_details.bankjp_details ~ .order_details.bankjp_details {
            display: none !important;
        }
        h2 + .order_details.bankjp_details ~ h2 {
            display: none !important;
        }
    ';
    return $css;
}