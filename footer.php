<?php
/**
 * フッターテンプレート
 *
 * @package Cavallian_Bros
 */
 
// ホームページからSNS URLを取得（Pods使用）
$home_page_id = get_option('page_on_front');
$instagram = pods_field('page', $home_page_id, 'instagram_url', true);
$twitter = pods_field('page', $home_page_id, 'twitter_url', true);
?>

</main><!-- #main -->

<!-- フッター -->
<footer class="footer">
    <div class="container">
        <div class="footer-content">
            <div class="footer-logo">
                <a href="<?php echo esc_url(home_url('/')); ?>">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo-white.svg" alt="<?php bloginfo('name'); ?>">
                </a>
            </div>
            
            <div class="footer-social">
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
            
            <div class="footer-nav">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'footer',
                    'menu_class'     => 'footer-nav-list',
                    'container'      => false,
                    'fallback_cb'    => function() {
                        ?>
                        <ul class="footer-nav-list">
                            <li><a href="<?php echo esc_url(home_url('/company')); ?>">運営者情報</a></li>
                            <li><a href="<?php echo esc_url(home_url('/privacy-policy')); ?>">プライバシーポリシー</a></li>
                            <li><a href="<?php echo esc_url(home_url('/terms')); ?>">利用規約</a></li>
                            <?php if (class_exists('WooCommerce')) : ?>
                                <li><a href="<?php echo esc_url(home_url('/shipping-info')); ?>">配送について</a></li>
                                <li><a href="<?php echo esc_url(home_url('/returns')); ?>">返品・交換について</a></li>
                            <?php endif; ?>
                        </ul>
                        <?php
                    }
                ));
                ?>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p class="copyright">
                &copy; <span id="year"><?php echo date('Y'); ?></span> <?php bloginfo('name'); ?>. All rights reserved.
            </p>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>

</body>
</html>