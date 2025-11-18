<?php
/**
 * Template Name: 特定商取引法
 * 
 * @package Cavallian_Bros
 */

get_header();
?>

<div class="tokutei-page">
    <div class="container">
        <!-- ページヘッダー -->
        <div class="page-header">
            <h1 class="page-title">特定商取引法に基づく表記</h1>
            <p class="page-description">Legal Information</p>
        </div>

        <!-- 目次ナビゲーション -->
        <nav class="tokutei-nav">
            <ul>
                <li><a href="#tokutei">特定商取引法に基づく表記</a></li>
                <li><a href="#shipping">配送・送料について</a></li>
                <li><a href="#returns">返品について</a></li>
                <li><a href="#payment">支払い方法について</a></li>
            </ul>
        </nav>

        <!-- コンテンツ -->
        <div class="tokutei-content">
            
            <!-- 特定商取引法に基づく表記 -->
            <section id="tokutei" class="tokutei-section">
                <h2>特定商取引法に基づく表記</h2>
                
                <dl class="info-list">
                    <dt>販売業者</dt>
                    <dd>始終計画合同会社</dd>
                    
                    <dt>運営責任者</dt>
                    <dd>久野 正喜</dd>
                    
                    <dt>所在地</dt>
                    <dd>〒107-0062<br>東京都港区南青山2-2-15 ウィン青山942</dd>
                    
                    <dt>電話番号</dt>
                    <dd>お問い合わせフォームよりご連絡ください</dd>
                    
                    <dt>メールアドレス</dt>
                    <dd>お問い合わせフォームよりご連絡ください</dd>
                    
                    <dt>お問い合わせ</dt>
                    <dd><a href="<?php echo esc_url(home_url('/contact')); ?>">お問い合わせフォーム</a></dd>
                    
                    <dt>販売価格</dt>
                    <dd>各商品ページに表示（消費税込み価格）</dd>
                    
                    <dt>商品代金以外の必要料金</dt>
                    <dd>送料、銀行振込手数料（銀行振込の場合）</dd>
                    
                    <dt>支払い方法</dt>
                    <dd>クレジットカード決済（Stripe）、銀行振込<br>
                    詳細は<a href="#payment">支払い方法について</a>をご確認ください。</dd>
                    
                    <dt>支払い時期</dt>
                    <dd>
                        <strong>クレジットカード決済：</strong>ご注文時<br>
                        <strong>銀行振込：</strong>ご注文後7日以内
                    </dd>
                    
                    <dt>商品の引き渡し時期</dt>
                    <dd>ご入金確認後、通常約1週間ほどでお届けいたします。<br>
                    受注生産品の場合は、商品ページに詳細を記載しております。</dd>
                    
                    <dt>返品・交換について</dt>
                    <dd>商品の不良・破損・欠陥または商品間違いがある場合に限り、商品到着後7日以内にご連絡の上、返品を承ります。<br>
                    詳細は<a href="#returns">返品について</a>をご確認ください。</dd>
                </dl>
            </section>

            <!-- 配送・送料について -->
            <section id="shipping" class="tokutei-section">
                <h2>配送・送料について</h2>
                
                <h3>配送方法</h3>
                <p>ヤマト運輸または日本郵便にて配送いたします。</p>
                
                <h3>送料</h3>
                <p>全国一律料金でお届けいたします。</p>
                <ul>
                    <li><strong>宅急便：</strong> 1,100円（税込）</li>
                    <li><strong>クリックポスト：</strong> 330円（税込）</li>
                </ul>
                
                <div class="notice-box">
                    <p><strong>送料無料</strong><br>
                    税込15,000円以上のお買い上げで送料無料</p>
                </div>
                
                <h3>配送エリア</h3>
                <p>日本全国へ配送いたします。</p>
                
                <h3>お届け日数</h3>
                <p>ご入金確認後、通常約1週間ほどでお届けいたします。<br>
                受注生産品の場合は、商品ページに詳細を記載しております。</p>
                
                <h3>配送日時指定</h3>
                <p>配送日時の指定は承っておりません。あらかじめご了承ください。</p>
            </section>

            <!-- 返品について -->
            <section id="returns" class="tokutei-section">
                <h2>返品について</h2>
                
                <h3>返品が可能な場合</h3>
                <p>発送前に必ず検品をし発送しておりますが、万が一以下の場合に限り返品を承ります。</p>
                <ul>
                    <li>商品の不良がある場合</li>
                    <li>商品の破損・欠陥がある場合</li>
                    <li>ご注文内容と異なる商品が届いた場合</li>
                </ul>
                
                <h3>返品期限</h3>
                <p>商品到着後<strong>7日以内</strong>にお問い合わせフォームよりご連絡ください。</p>
                
                <h3>返品方法</h3>
                <ol>
                    <li><a href="<?php echo esc_url(home_url('/contact')); ?>">お問い合わせフォーム</a>より返品のご連絡をお願いいたします。</li>
                    <li>返送先住所をお知らせいたします。</li>
                    <li>着払いにて商品をご返送ください。</li>
                </ol>
                
                <h3>返品送料</h3>
                <p>不良品・破損・商品間違いの場合は、当社が負担いたします（着払い）。</p>
                
                <div class="notice-box warning">
                    <h4>返品・交換をお受けできない場合</h4>
                    <ul>
                        <li>イメージと違うなどのお客様のご都合によるキャンセル・返品・交換</li>
                        <li>受注生産品</li>
                        <li>SALE品、アウトレット品</li>
                        <li>商品到着後7日を過ぎた場合</li>
                        <li>ご使用後の商品（ペット用品は衛生上、未使用品のみ対象）</li>
                    </ul>
                    <p><strong>※ PCモニター、ブラウザーによってカラーバランスが異なるため、実際の商品と若干異なる場合がございますが、不良品ではございませんのでご返品対象にはなりません。</strong></p>
                    <p><strong>※ ペット用品は衛生上、初期不良品であっても未使用品のみとさせていただきますので、商品到着後使用前に必ず確認をお願いいたします。</strong></p>
                </div>
            </section>

            <!-- 支払い方法について -->
            <section id="payment" class="tokutei-section">
                <h2>支払い方法について</h2>
                
                <h3>ご利用可能な決済方法</h3>
                
                <div class="payment-method">
                    <h4>クレジットカード決済（Stripe）</h4>
                    <p>各種クレジットカードがご利用いただけます。</p>
                    <p>また、Apple Pay・Google Payにも対応しております。</p>
                    <p><strong>決済タイミング：</strong> ご注文時に決済が完了いたします。</p>
                    <p><strong>セキュリティ：</strong> 3Dセキュア2.0に対応しており、SSL暗号化通信により、お客様のクレジットカード情報は安全に保護されます。</p>
                </div>
                
                <div class="notice-box">
                    <h4>3Dセキュア2.0に関する注意事項</h4>
                    <p>当サイトではクレジットカード決済に3Dセキュア2.0を導入しております。<br>
                    ご利用には、事前にカード発行会社での登録が必要な場合がございます。</p>
                    <ul>
                        <li>登録方法や必要な情報（携帯電話番号、メールアドレスなど）は、各カード発行会社によって異なります。</li>
                        <li>詳しい登録方法は、ご利用のカード発行会社の公式サイトまたはアプリをご確認ください。</li>
                        <li>本人認証の方法や表示される画面は、カード発行会社によって異なります。</li>
                        <li>カード発行会社によっては、3Dセキュア2.0に対応していない場合がございます。</li>
                        <li>本人認証が完了しても、クレジットカードのご利用状況によっては決済できない場合がございます。</li>
                    </ul>
                </div>
                
                <div class="payment-method">
                    <h4>銀行振込</h4>
                    <p>ご注文後、振込先口座をメールにてお知らせいたします。</p>
                    <p><strong>お支払い期限：</strong> ご注文後7日以内</p>
                    <p><strong>振込手数料：</strong> お客様負担</p>
                    <p><strong>ご注意：</strong> ご入金確認後の発送となります。</p>
                </div>
                
                <h3>領収書について</h3>
                <p>領収書が必要な場合は、お問い合わせフォームよりご連絡ください。</p>
            </section>

        </div>
    </div>
</div>

<?php
get_footer();
?>
