<?php
/**
 * Template Name: Contact Thanks
 * お問い合わせ送信完了ページテンプレート
 *
 * @package Cavallian_Bros
 */

get_header(); ?>

<!-- ヒーローセクション -->
<section class="contact-thanks-hero">
    <div class="thanks-icon">
        <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
            <polyline points="22 4 12 14.01 9 11.01"></polyline>
        </svg>
    </div>
    <h1>送信完了</h1>
</section>

<!-- コンテンツ -->
<div class="contact-thanks-content">
    <div class="thanks-message">
        <p class="main-message">
            お問い合わせありがとうございました。
        </p>
        <p>
            お問い合わせ内容を確認の上、なるべくお早めにご返信いたします。<br>
            しばらくお待ちください。
        </p>
        <p>
            送信されたことの確認のメールを<br class="sp-only">
            入力いただいたメール宛に送信しておりますのでご確認ください。
        </p>
    </div>

    <div class="thanks-actions">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="btn-home">Homeへ戻る</a>
    </div>
</div>

<?php get_footer(); ?>
