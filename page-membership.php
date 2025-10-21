<?php
/**
 * Template Name: メンバーシップ登録
 * Description: 会員登録ページ用のカスタムテンプレート
 */

get_header(); ?>

<style>
/* ページタイトル非表示 */
.entry-title { display: none; }

/* カスタムスタイル */
.membership-wrapper {
    max-width: 980px;
    margin: 0 auto;
    padding: 40px 20px;
}

.membership-header {
    text-align: center;
    margin-bottom: 50px;
}

.membership-title {
    font-family: 'Questrial', sans-serif;
    font-size: 2.5rem;
    font-weight: 300;
    letter-spacing: 0.08em;
    color: #333;
    margin-bottom: 15px;
}

.membership-subtitle {
    font-size: 1rem;
    color: #666;
}

/* 会員ステータス表示 */
.member-status-box {
    background: #f8f8f8;
    border-left: 3px solid #8f601e;
    padding: 20px 30px;
    margin-bottom: 40px;
    border-radius: 4px;
}

.member-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.member-badge {
    display: inline-block;
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 500;
    background: #fff;
    border: 1px solid #ddd;
}

.member-badge.premium {
    background: #8f601e;
    color: #fff;
    border-color: #8f601e;
}

/* 特典セクション */
.benefits-section {
    margin-bottom: 60px;
}

.section-title {
    text-align: center;
    font-size: 1.8rem;
    font-weight: 400;
    margin-bottom: 40px;
    padding-bottom: 15px;
    border-bottom: 1px solid #e5e5e5;
}

.benefit-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 25px;
}

.benefit-item {
    text-align: center;
    padding: 25px 20px;
    background: #fff;
    border: 1px solid #e5e5e5;
    border-radius: 6px;
}

.benefit-icon {
    margin-bottom: 15px;
    color: #666;
}

.benefit-item h3 {
    font-size: 1rem;
    margin-bottom: 10px;
    color: #333;
    font-weight: 500;
}

.benefit-item p {
    color: #666;
    line-height: 1.5;
    font-size: 0.85rem;
}

/* プラン選択 */
.pricing-section {
    margin-bottom: 60px;
    padding: 40px;
    background: #fafafa;
    border-radius: 6px;
}

/* フォームカスタマイズ */
#rcp_registration_form {
    margin-top: 30px;
}

.rcp_subscription_levels {
    display: grid !important;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-bottom: 30px;
}

.rcp_subscription_level {
    list-style: none;
    border: 1px solid #ddd;
    border-radius: 6px;
    padding: 20px;
    background: #fff;
    margin: 0 !important;
}

/* FAQ */
.faq-section {
    margin-bottom: 40px;
}

.faq-list {
    max-width: 700px;
    margin: 0 auto;
}

.faq-item {
    padding: 20px 0;
    border-bottom: 1px solid #e5e5e5;
}

.faq-item h4 {
    color: #333;
    margin-bottom: 8px;
    font-size: 0.95rem;
    font-weight: 500;
}

.faq-item p {
    color: #666;
    line-height: 1.5;
    font-size: 0.9rem;
}

/* レスポンシブ */
@media (max-width: 768px) {
    .benefit-grid,
    .rcp_subscription_levels {
        grid-template-columns: 1fr !important;
    }
    
    .member-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
}
</style>

<div class="membership-wrapper">
    
    <!-- ヘッダー -->
    <div class="membership-header">
        <h1 class="membership-title">メンバーシップ</h1>
        <p class="membership-subtitle">Cavallian Bros.の会員プログラムにご参加ください</p>
    </div>
    
    <?php if (is_user_logged_in()) : ?>
        <?php 
        $current_user = wp_get_current_user();
        $membership_status = '通常会員';
        $badge_class = '';
        
        if (function_exists('rcp_get_customer')) {
            $customer = rcp_get_customer();
            if ($customer) {
                $membership = rcp_get_customer_single_membership($customer->get_id());
if ($membership && $membership->is_active()) {
    $level_id = $membership->get_object_id();
    if ($level_id >= 1) {
        $membership_status = 'プレミアム会員';
        $badge_class = 'premium';
    }
}
            }
        }
        ?>
        <div class="member-status-box">
            <div class="member-info">
                <span>こんにちは、<?php echo esc_html($current_user->display_name); ?>さん</span>
                <span class="member-badge <?php echo $badge_class; ?>">
                    <?php echo $membership_status; ?>
                </span>
            </div>
        </div>
    <?php endif; ?>
    
    <!-- 特典 -->
    <div class="benefits-section">
        <h2 class="section-title">プレミアム会員特典</h2>
        <div class="benefit-grid">
            <div class="benefit-item">
                <div class="benefit-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <rect x="3" y="7" width="18" height="14" rx="2"/>
                        <path d="M7 7V5a2 2 0 012-2h6a2 2 0 012 2v2"/>
                    </svg>
                </div>
                <h3>限定商品</h3>
                <p>プレミアム会員だけが購入できる特別な商品</p>
            </div>
            <div class="benefit-item">
                <div class="benefit-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <rect x="3" y="4" width="18" height="16" rx="2"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                </div>
                <h3>限定イベント</h3>
                <p>会員限定の交流会やオフ会への参加</p>
            </div>
            <div class="benefit-item">
                <div class="benefit-icon">
                    <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="12" cy="12" r="10"/>
                        <polyline points="12 6 12 12 16 14"/>
                    </svg>
                </div>
                <h3>優先販売＆予約</h3>
                <p>新商品やイベントの優先的な申し込み</p>
            </div>
        </div>
    </div>
    
    <!-- プラン選択 -->
    <div class="pricing-section">
        <h2 class="section-title">プランをお選びください</h2>
        <?php echo do_shortcode('[register_form]'); ?>
    </div>
    
    <!-- FAQ -->
    <div class="faq-section">
        <h2 class="section-title">よくあるご質問</h2>
        <div class="faq-list">
            <div class="faq-item">
                <h4>Q. いつでも解約できますか？</h4>
                <p>A. はい、マイページからいつでも解約可能です。</p>
            </div>
            <div class="faq-item">
                <h4>Q. プランの変更はできますか？</h4>
                <p>A. 月額から年額への変更はいつでも可能です。</p>
            </div>
            <div class="faq-item">
                <h4>Q. 支払い方法は？</h4>
                <p>A. クレジットカードがご利用いただけます。</p>
            </div>
        </div>
    </div>
    
</div>

<?php get_footer(); ?>