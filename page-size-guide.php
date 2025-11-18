<?php
/**
 * Template Name: サイズガイド
 * 
 * @package Cavallian_Bros
 */

get_header();
?>

<div class="size-guide-page">
    <div class="container">
        <!-- ページヘッダー -->
        <div class="page-header">
            <h1 class="page-title">サイズについて</h1>
            <p class="page-description">Size Guide</p>
        </div>

        <!-- コンテンツ -->
        <div class="size-content">
            
            <!-- サイズの測り方 -->
            <section class="size-section">
                <h2>サイズの測り方</h2>
                <p class="notice-text">採寸の際はワンちゃんを立たせた状態で測ってください。</p>
                
                <!-- サイズイラスト -->
                <div class="size-illustration">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/size_ai.png" alt="犬のサイズの測り方" />
                </div>
                
                <!-- サイズ説明 -->
                <dl class="size-list">
                    <dt><span class="size-label">NECK</span> / 首回り</dt>
                    <dd>首輪を付ける位置の周囲</dd>
                    
                    <dt><span class="size-label">CHEST</span> / 胴回り</dt>
                    <dd>胴の一番太い部分の周囲</dd>
                    
                    <dt><span class="size-label">BODY LENGTH</span> / 背丈</dt>
                    <dd>首の付け根から尾の付け根までの長さ</dd>
                    
                    <dt><span class="size-label">LEG LENGTH</span> / 足袖丈</dt>
                    <dd>脇の下から地面までの長さ</dd>
                </dl>
            </section>

            <!-- 使用上の注意事項 -->
            <section class="size-section">
                <h2>使用上の注意事項</h2>
                
                <ul class="caution-list">
                    <li>使用時には亀裂や破損、ほつれ等、各部に異常がないか毎回ご確認ください。</li>
                    <li>セット後は外れないか必ずご確認ください。</li>
                    <li>しつけが不十分、引っ張り癖がある、他のワンちゃんを見ると走り出す癖がある、噛み癖がある等のワンちゃんへのご使用は特にご注意ください。</li>
                    <li>首輪、ハーネスは指2本が入るくらいの余裕が適当です。緩すぎるとカラダが抜けてしまう場合がございますので、装着後は緩すぎないかを必ず確認してください。</li>
                    <li>商品の特性上、お客様都合による返品・交換はお受けいたしかねますため、サイズ等のご不明な点がある場合は、<a href="<?php echo esc_url(home_url('/contact')); ?>">お問い合わせページ</a>よりお問い合わせください。</li>
                    <li>犬具は消耗品のため、使用頻度や使用環境によって耐久性が大きく変わります。商品の状態に合わせて買い替えをご検討ください。</li>
                    <li>金属パーツの錆や損傷、ボルトのほつれなどが見られた場合は、事故防止のためお早めに新しいものへお取り替えください。</li>
                </ul>
            </section>

            <!-- 人間用サイズについて -->
            <section class="size-section">
                <div class="info-box">
                    <p><strong>人間用商品のサイズについて</strong><br>
                    人間用商品のサイズは、各商品ページに記載しております。</p>
                </div>
            </section>

        </div>
    </div>
</div>

<?php
get_footer();
?>
