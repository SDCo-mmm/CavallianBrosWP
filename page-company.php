<?php
/**
 * Template Name: Company
 * 運営者情報ページテンプレート
 *
 * @package Cavallian_Bros
 */

get_header(); ?>

<!-- ヒーローセクション -->
<section class="company-hero">
    <h1>COMPANY</h1>
</section>

<!-- コンテンツ -->
<div class="company-content">
    <section class="company-section">
        <h2>Cavallian Bros. について</h2>
        <p>
            Cavallian Bros. は、キャバリア・キング・チャールズ・スパニエルと暮らす人々のためのライフスタイルブランドです。キャバリアのしあわせを第一に考え、そのぬくもりに支えられている私たちが、より豊かな時間を過ごせるようなアイテム・サービスをお届けします。
        </p>
    </section>

    <section class="company-section">
        <h2>ショップ情報</h2>
        <table class="info-table-simple">
            <tr>
                <th>ショップ名</th>
                <td>Cavallian Bros.（キャバリアン・ブロス）</td>
            </tr>
            <tr>
                <th>運営会社</th>
                <td>始終計画合同会社</td>
            </tr>
            <tr>
                <th>運営責任者</th>
                <td>久野 正喜</td>
            </tr>
            <tr>
                <th>所在地</th>
                <td>〒107-0062<br>東京都港区南青山2-2-15 ウィン青山942</td>
            </tr>
        </table>
    </section>

    <section class="company-section contact-section">
        <h2>お問い合わせ</h2>
        <p>
            商品やサービスに関するご質問、ご要望などございましたら、<br class="pc-only">
            お気軽にお問い合わせください。
        </p>
        <p>
            お問い合わせフォームより承っております。<br>
            通常、2〜3営業日以内にご返信させていただきます。
        </p>
        <p style="font-size: 0.85rem; color: #999; margin-top: 15px;">
            ※営業メールやいたずら・迷惑メールに対しては返信はいたしません。
        </p>
        <?php if (shortcode_exists('contact-form-7')) : ?>
            <a href="<?php echo esc_url(home_url('/contact')); ?>" class="contact-button">お問い合わせフォームへ</a>
        <?php else : ?>
            <p style="color: #999; font-size: 0.9rem;">（お問い合わせフォームは準備中です）</p>
        <?php endif; ?>
    </section>
</div>

<?php get_footer(); ?>