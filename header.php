<?php
/**
 * ヘッダーテンプレート（メガメニュー対応版）
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
        
        <!-- デスクトップナビゲーション -->
        <nav class="nav desktop-nav">
            <ul class="nav-list">
                <li><a href="<?php echo $is_home ? '#about' : $home_url . '#about'; ?>">About</a></li>
                <li><a href="<?php echo $is_home ? '#message' : $home_url . '#message'; ?>">Message</a></li>
                
                <?php if (class_exists('WooCommerce')) : ?>
                    <!-- Shopメニュー（メガメニュー） -->
                    <li class="menu-item-has-megamenu">
                        <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>">Shop</a>
                        
                        <?php
                        // 親カテゴリーを取得（uncategorized除外）
                        $parent_categories = get_terms(array(
                            'taxonomy'   => 'product_cat',
                            'hide_empty' => false,
                            'parent'     => 0,
                            'exclude'    => array(get_option('default_product_cat')),
                        ));
                        
                        if (!empty($parent_categories) && !is_wp_error($parent_categories)) :
                        ?>
                            <div class="mega-menu">
                                <div class="mega-menu-inner">
                                    <div class="mega-menu-item">
                                        <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="mega-menu-all">
                                            すべての商品
                                        </a>
                                    </div>
                                    
                                    <?php foreach ($parent_categories as $parent_cat) : ?>
                                        <div class="mega-menu-item">
                                            <h3 class="mega-menu-title">
                                                <a href="<?php echo esc_url(get_term_link($parent_cat)); ?>">
                                                    <?php echo esc_html($parent_cat->name); ?>
                                                </a>
                                            </h3>
                                            
                                            <?php
                                            // 子カテゴリーを取得
                                            $child_categories = get_terms(array(
                                                'taxonomy'   => 'product_cat',
                                                'hide_empty' => false,
                                                'parent'     => $parent_cat->term_id,
                                            ));
                                            
                                            if (!empty($child_categories) && !is_wp_error($child_categories)) :
                                            ?>
                                                <ul class="mega-menu-sub">
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
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </li>
                <?php endif; ?>
                
                <li><a href="<?php echo esc_url(get_post_type_archive_link('events')); ?>">Events</a></li>
                <li><a href="<?php echo esc_url(home_url('/company')); ?>">Company</a></li>
                
                <!-- マイページアイコン -->
                <?php if (class_exists('WooCommerce')) : ?>
                    <li class="icon-menu mypage-menu">
                        <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" aria-label="マイページ">
                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </a>
                    </li>
                    
                    <!-- カートアイコン -->
                    <li class="icon-menu cart-menu">
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
                    </li>
                <?php endif; ?>
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

<!-- モバイルスライドメニュー -->
<nav class="mobile-slide-menu" id="mobile-menu">
    <ul class="mobile-menu-list">
        <li><a href="<?php echo esc_url(home_url('/')); ?>">Home</a></li>
        <li><a href="<?php echo $is_home ? '#about' : $home_url . '#about'; ?>">About</a></li>
        <li><a href="<?php echo $is_home ? '#message' : $home_url . '#message'; ?>">Message</a></li>
        
        <?php if (class_exists('WooCommerce')) : ?>
            <li><a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>">Shop</a></li>
            
            <?php
            // モバイルメニュー用のカテゴリー（親のみ）
            $mobile_parent_cats = get_terms(array(
                'taxonomy'   => 'product_cat',
                'hide_empty' => false,
                'parent'     => 0,
                'exclude'    => array(get_option('default_product_cat')),
            ));
            
            if (!empty($mobile_parent_cats) && !is_wp_error($mobile_parent_cats)) :
                foreach ($mobile_parent_cats as $parent_cat) :
            ?>
                <li class="mobile-sub-item">
                    <a href="<?php echo esc_url(get_term_link($parent_cat)); ?>">
                        <?php echo esc_html($parent_cat->name); ?>
                    </a>
                </li>
                
                <?php
                // 子カテゴリーも表示
                $mobile_child_cats = get_terms(array(
                    'taxonomy'   => 'product_cat',
                    'hide_empty' => false,
                    'parent'     => $parent_cat->term_id,
                ));
                
                if (!empty($mobile_child_cats) && !is_wp_error($mobile_child_cats)) :
                    foreach ($mobile_child_cats as $child_cat) :
                ?>
                    <li class="mobile-sub-sub-item">
                        <a href="<?php echo esc_url(get_term_link($child_cat)); ?>">
                            <?php echo esc_html($child_cat->name); ?>
                        </a>
                    </li>
                <?php
                    endforeach;
                endif;
                endforeach;
            endif;
            ?>
        <?php endif; ?>
        
        <li><a href="<?php echo esc_url(get_post_type_archive_link('events')); ?>">Events</a></li>
        <li><a href="<?php echo esc_url(home_url('/company')); ?>">Company</a></li>
    </ul>
    
    <!-- SNSリンク -->
    <div class="mobile-menu-social">
        <?php if ($instagram) : ?>
            <a href="<?php echo esc_url($instagram); ?>" target="_blank" rel="noopener noreferrer" aria-label="Instagram">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                    <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                    <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                </svg>
            </a>
        <?php endif; ?>
        
        <?php if ($twitter) : ?>
            <a href="<?php echo esc_url($twitter); ?>" target="_blank" rel="noopener noreferrer" aria-label="X (Twitter)">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"></path>
                </svg>
            </a>
        <?php endif; ?>
    </div>
</nav>

<main id="main" class="site-main">
