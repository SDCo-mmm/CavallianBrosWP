<?php
/**
 * Template Name: Contact
 * お問い合わせページテンプレート
 *
 * @package Cavallian_Bros
 */

get_header(); ?>

<!-- ヒーローセクション -->
<section class="contact-hero">
    <h1>CONTACT</h1>
    <p class="contact-hero-subtitle">お問い合わせ</p>
</section>

<!-- コンテンツ -->
<div class="contact-content">
    <section class="contact-intro">
        <p>
            商品やサービスに関するご質問、ご要望などございましたら、<br class="pc-only">
            お気軽にお問い合わせください。
        </p>
        <p class="contact-note">
            通常、2〜3営業日以内にご返信させていただきます。
        </p>
    </section>

    <!-- お問い合わせフォーム -->
    <section class="contact-form-section">
        <?php
        // ログイン状態を確認
        $current_user = wp_get_current_user();
        $is_logged_in = is_user_logged_in();
        ?>

        <?php if ($is_logged_in) : ?>
            <!-- ログイン時: ユーザーネーム表示 -->
            <div class="logged-in-info">
                <p class="username-display">
                    <span class="label">ユーザーネーム:</span>
                    <span class="value"><?php echo esc_html($current_user->user_login); ?></span>
                </p>
            </div>
        <?php endif; ?>

        <!-- Contact Form 7 ショートコード -->
        <!-- 注意: [contact-form-7 id="xxx"] の id は、後ほど管理画面で作成したフォームIDに置き換えてください -->
        <?php echo do_shortcode('[contact-form-7 id="11a95e4" title="お問い合わせフォーム"]'); ?>
    </section>

    <section class="contact-info-section">
        <p class="privacy-note">
            送信いただいた個人情報は、お問い合わせへの回答のみに使用し、<br class="pc-only">
            適切に管理いたします。詳しくは<a href="<?php echo esc_url(home_url('/privacy-policy')); ?>">プライバシーポリシー</a>をご確認ください。
        </p>
    </section>
</div>

<?php get_footer(); ?>
