<?php
/**
 * ヘッダーテンプレート（2行レイアウト + メガメニュー対応版）
 *
 * @package Cavallian_Bros
 */
 
// ホームページからSNS URLを取得（Pods使用）
$home_page_id = get_option('page_on_front');
$instagram = pods_field('page', $home_page_id, 'instagram_url', true);
$twitter = pods_field('page', $home_page_id, 'twitter_url', true);

// 現在のページがホームページかどうかを判定
$is_home = is_front_page();
$home_url = home_url('/');
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php bloginfo('description'); ?>">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- ヘッダー -->
<header class="header" id="header">
    <div class="header-inner">
        <h1 class="logo">
            <a href="<?php echo esc_url(home_url('/')); ?>">
                <?php echo cavallian_get_logo(); ?>
            </a>
        </h1>
        
        <!-- デスクトップナビゲーション（2行レイアウト） -->
        <nav class="nav desktop-nav">
            <!-- 上段: アイコン行 -->
            <div class="nav-icons-row">
                <?php if (class_exists('WooCommerce')) : ?>
                    <!-- マイページアイコン -->
                    <div class="icon-menu mypage-menu">
                        <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" aria-label="マイページ">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </a>
                    </div>
                    
                    <!-- カートアイコン -->
                    <div class="icon-menu cart-menu">
                        <a href="<?php echo esc_url(wc_get_cart_url()); ?>" aria-label="カート">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 2L6 9H3L5 21H19L21 9H18L15 2H9Z"/>
                                <path d="M9 9V2"/>
                                <path d="M15 9V2"/>
                            </svg>
                            <?php if (WC()->cart->get_cart_contents_count() > 0) : ?>
                                <span class="cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                            <?php endif; ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- 下段: メニュー行（メガメニュー対応） -->
            <ul class="nav-list">
                <li><a href="<?php echo esc_url(home_url('/')); ?>">Home</a></li>
                <li><a href="<?php echo $is_home ? '#about' : $home_url . '#about'; ?>">About</a></li>
                
                <!-- Shopメニュー（メガメニュー） -->
                <li class="menu-item-has-children">
                    <a href="<?php echo esc_url(get_post_type_archive_link('product')); ?>">Shop</a>
                    
                    <!-- メガメニュー -->
                    <div class="mega-menu">
                        <div class="mega-menu-inner">
                            <?php
                            // 親カテゴリーを取得（親なしのトップレベルカテゴリーのみ）
                            // uncategorized を除外
                            $parent_categories = get_terms(array(
                                'taxonomy' => 'product_cat',
                                'parent' => 0,
                                'hide_empty' => false,
                                'orderby' => 'term_id',
                                'order' => 'ASC',
                                'exclude' => array(get_option('default_product_cat'))  // uncategorized を除外
                            ));
                            
                            if (!empty($parent_categories) && !is_wp_error($parent_categories)) :
                                foreach ($parent_categories as $parent_cat) :
                                    // uncategorized スラッグもチェック（念のため）
                                    if ($parent_cat->slug === 'uncategorized') {
                                        continue;
                                    }
                                    
                                    // 子カテゴリーを取得
                                    $child_categories = get_terms(array(
                                        'taxonomy' => 'product_cat',
                                        'parent' => $parent_cat->term_id,
                                        'hide_empty' => false,
                                        'orderby' => 'term_id',
                                        'order' => 'ASC'
                                    ));
                            ?>
                                <!-- 親カテゴリー列 -->
                                <div class="mega-menu-column">
                                    <a href="<?php echo esc_url(get_term_link($parent_cat)); ?>">
                                        <?php echo esc_html($parent_cat->name); ?>
                                    </a>
                                    
                                    <?php if (!empty($child_categories) && !is_wp_error($child_categories)) : ?>
                                        <ul>
                                            <?php foreach ($child_categories as $child_cat) : ?>
                                                <li>
                                                    <a href="<?php echo esc_url(get_term_link($child_cat)); ?>">
                                                        <?php echo esc_html($child_cat->name); ?>
                                                    </a>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </div>
                            <?php
                                endforeach;
                            endif;
                            ?>
                        </div>
                    </div>
                </li>
                
                <li><a href="<?php echo esc_url(get_post_type_archive_link('events')); ?>">Events</a></li>
                <li><a href="<?php echo esc_url(home_url('/company')); ?>">Company</a></li>
            </ul>
        </nav>
        
        <!-- モバイルナビゲーション（アイコンとハンバーガー） -->
        <div class="mobile-nav-icons">
            <?php if (class_exists('WooCommerce')) : ?>
                <!-- モバイル用マイページアイコン -->
                <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="mobile-icon mypage-icon" aria-label="マイページ">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </a>
                
                <!-- モバイル用カートアイコン -->
                <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="mobile-icon cart-icon" aria-label="カート">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 2L6 9H3L5 21H19L21 9H18L15 2H9Z"/>
                        <path d="M9 9V2"/>
                        <path d="M15 9V2"/>
                    </svg>
                    <?php if (WC()->cart->get_cart_contents_count() > 0) : ?>
                        <span class="cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                    <?php endif; ?>
                </a>
            <?php endif; ?>
            
            <!-- ハンバーガーメニューボタン -->
            <button class="menu-button mobile-menu-toggle" aria-label="メニュー">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </div>
</header>

<!-- モバイルスライドメニュー（全画面 + アコーディオン対応） -->
<nav class="mobile-slide-menu" id="mobile-menu">
    <ul class="mobile-menu-list">
        <li><a href="<?php echo esc_url(home_url('/')); ?>">Home</a></li>
        <li><a href="<?php echo $is_home ? '#about' : $home_url . '#about'; ?>">About</a></li>
        
        <!-- Shopメニュー（アコーディオン） -->
        <li class="menu-item-has-children">
            <a href="#" class="accordion-trigger">Shop</a>
            
            <!-- サブメニュー -->
            <ul class="mobile-sub-menu">
                <?php
                // モバイル用：親カテゴリーを取得
                // uncategorized を除外
                $parent_categories_mobile = get_terms(array(
                    'taxonomy' => 'product_cat',
                    'parent' => 0,
                    'hide_empty' => false,
                    'orderby' => 'term_id',
                    'order' => 'ASC',
                    'exclude' => array(get_option('default_product_cat'))  // uncategorized を除外
                ));
                
                if (!empty($parent_categories_mobile) && !is_wp_error($parent_categories_mobile)) :
                    foreach ($parent_categories_mobile as $parent_cat_mobile) :
                        // uncategorized スラッグもチェック（念のため）
                        if ($parent_cat_mobile->slug === 'uncategorized') {
                            continue;
                        }
                        
                        // 子カテゴリーを取得
                        $child_categories_mobile = get_terms(array(
                            'taxonomy' => 'product_cat',
                            'parent' => $parent_cat_mobile->term_id,
                            'hide_empty' => false,
                            'orderby' => 'term_id',
                            'order' => 'ASC'
                        ));
                ?>
                    <!-- 親カテゴリー -->
                    <li class="parent-category">
                        <a href="<?php echo esc_url(get_term_link($parent_cat_mobile)); ?>">
                            <?php echo esc_html($parent_cat_mobile->name); ?>
                        </a>
                        
                        <?php if (!empty($child_categories_mobile) && !is_wp_error($child_categories_mobile)) : ?>
                            <ul>
                                <?php foreach ($child_categories_mobile as $child_cat_mobile) : ?>
                                    <li class="child-category">
                                        <a href="<?php echo esc_url(get_term_link($child_cat_mobile)); ?>">
                                            <?php echo esc_html($child_cat_mobile->name); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </li>
                <?php
                    endforeach;
                endif;
                ?>
            </ul>
        </li>
        
        <li><a href="<?php echo esc_url(get_post_type_archive_link('events')); ?>">Events</a></li>
        <li><a href="<?php echo esc_url(home_url('/company')); ?>">Company</a></li>
    </ul>
    
    <!-- SNSリンク -->
    <?php if ($instagram || $twitter) : ?>
        <div class="mobile-menu-social">
            <?php if ($instagram) : ?>
                <a href="<?php echo esc_url($instagram); ?>" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                    </svg>
                </a>
            <?php endif; ?>
            
            <?php if ($twitter) : ?>
                <a href="<?php echo esc_url($twitter); ?>" target="_blank" rel="noopener noreferrer" aria-label="Twitter">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                    </svg>
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</nav>

<!-- ここに main タグを追加 -->
<main id="main" class="site-main">
