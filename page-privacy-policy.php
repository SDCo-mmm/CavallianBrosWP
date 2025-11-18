<?php
/**
 * Template Name: プライバシーポリシー
 * 
 * @package Cavallian_Bros
 */

get_header();
?>

<div class="privacy-policy-page">
    <div class="container">
        <!-- ページヘッダー -->
        <div class="page-header">
            <h1 class="page-title">プライバシーポリシー</h1>
            <p class="page-description">個人情報保護方針</p>
        </div>

        <!-- コンテンツ -->
        <div class="privacy-content">
            
            <!-- 前文 -->
            <section class="privacy-section">
                <p>始終計画合同会社（以下「当社」といいます）は、Cavallian Bros.（以下「当サイト」といいます）をご利用いただくお客様の個人情報の取り扱いについて、以下のとおりプライバシーポリシー（以下「本ポリシー」といいます）を定めます。</p>
            </section>

            <!-- 1. 事業者情報 -->
            <section class="privacy-section">
                <h2>1. 事業者情報</h2>
                <dl class="info-list">
                    <dt>サイト名</dt>
                    <dd>Cavallian Bros.（キャバリアン・ブロス）</dd>
                    
                    <dt>運営会社</dt>
                    <dd>始終計画合同会社</dd>
                    
                    <dt>所在地</dt>
                    <dd>〒107-0062<br>東京都港区南青山2-2-15 ウィン青山942</dd>
                    
                    <dt>代表者</dt>
                    <dd>久野 正喜</dd>
                    
                    <dt>お問い合わせ</dt>
                    <dd>当サイトへのお問い合わせは、<a href="<?php echo esc_url(home_url('/contact')); ?>">専用フォーム</a>よりお願いいたします。</dd>
                </dl>
            </section>

            <!-- 2. 個人情報の定義 -->
            <section class="privacy-section">
                <h2>2. 個人情報の定義</h2>
                <p>本ポリシーにおいて、個人情報とは、個人情報保護法第2条第1項により定義された個人情報、すなわち、生存する個人に関する情報であって、当該情報に含まれる氏名、生年月日その他の記述等により特定の個人を識別することができるもの（他の情報と容易に照合することができ、それにより特定の個人を識別することができることとなるものを含みます）を指します。</p>
            </section>

            <!-- 3. 個人情報の収集 -->
            <section class="privacy-section">
                <h2>3. 個人情報の収集</h2>
                <p>当社は、以下の場合に個人情報を収集することがあります。</p>
                
                <h3>3.1 会員登録時</h3>
                <ul>
                    <li>氏名</li>
                    <li>メールアドレス</li>
                    <li>ユーザーネーム</li>
                    <li>パスワード</li>
                </ul>

                <h3>3.2 商品購入時</h3>
                <ul>
                    <li>上記会員登録情報に加えて</li>
                    <li>配送先住所</li>
                    <li>電話番号</li>
                </ul>

                <h3>3.3 お問い合わせ時</h3>
                <ul>
                    <li>氏名</li>
                    <li>メールアドレス</li>
                    <li>お問い合わせ内容</li>
                </ul>
            </section>

            <!-- 4. 個人情報の利用目的 -->
            <section class="privacy-section">
                <h2>4. 個人情報の利用目的</h2>
                <p>当社は、収集した個人情報を以下の目的で利用します。</p>
                <ul>
                    <li>商品の発送及びサービスの提供</li>
                    <li>お客様からのお問い合わせへの対応</li>
                    <li>商品・サービスに関する情報のご案内</li>
                    <li>メールマガジンの配信（ご登録いただいた方のみ）</li>
                    <li>キャンペーン・イベント等の情報提供</li>
                    <li>商品やサービスの改善・開発のための分析</li>
                    <li>不正利用の防止及びセキュリティの確保</li>
                    <li>その他、上記利用目的に付随する目的</li>
                </ul>
            </section>

            <!-- 5. 個人情報の第三者提供 -->
            <section class="privacy-section">
                <h2>5. 個人情報の第三者提供</h2>
                <p>当社は、以下の場合を除き、お客様の個人情報を第三者に提供することはありません。</p>
                <ul>
                    <li>お客様の同意がある場合</li>
                    <li>法令に基づく場合</li>
                    <li>人の生命、身体又は財産の保護のために必要がある場合であって、お客様の同意を得ることが困難である場合</li>
                    <li>公衆衛生の向上又は児童の健全な育成の推進のために特に必要がある場合であって、お客様の同意を得ることが困難である場合</li>
                </ul>

                <h3>5.1 業務委託先への提供</h3>
                <p>当社は、利用目的の達成に必要な範囲内において、個人情報の取扱いを外部に委託する場合があります。この場合、当社は委託先と機密保持契約を締結し、個人情報の適切な管理を義務付けます。</p>
                
                <p><strong>主な委託先：</strong></p>
                <ul>
                    <li><strong>決済処理：</strong> Stripe, Inc.（クレジットカード決済処理）</li>
                    <li><strong>配送業者：</strong> 商品配送のための運送会社</li>
                    <li><strong>メール配信：</strong> メールマガジン配信サービス（導入時）</li>
                </ul>
            </section>

            <!-- 6. Cookieの使用 -->
            <section class="privacy-section">
                <h2>6. Cookieおよび類似技術の使用</h2>
                <p>当サイトでは、サービスの利便性向上及びサイトの改善のために、Cookie及び類似技術を使用しています。</p>

                <h3>6.1 Cookieとは</h3>
                <p>Cookieとは、ウェブサイトがお客様のコンピューターやスマートフォンに一時的にデータを保存する仕組みです。</p>

                <h3>6.2 使用目的</h3>
                <ul>
                    <li>ログイン状態の維持</li>
                    <li>カート情報の保持</li>
                    <li>サイトの利用状況の分析</li>
                    <li>お客様に合わせたコンテンツの表示</li>
                    <li>スパム対策（Cloudflare Turnstile）</li>
                </ul>

                <h3>6.3 使用しているサービス</h3>
                <ul>
                    <li>WordPress（サイト管理システム）</li>
                    <li>WooCommerce（ECシステム）</li>
                    <li>Google Analytics（アクセス解析・導入予定）</li>
                    <li>Cloudflare Turnstile（スパム対策）</li>
                </ul>

                <h3>6.4 無効化について</h3>
                <p>お客様のブラウザ設定により、Cookieの受け入れを拒否することが可能です。ただし、Cookieを無効にした場合、当サイトの一部機能がご利用いただけなくなる可能性があります。</p>
            </section>

            <!-- 7. アクセス解析ツール -->
            <section class="privacy-section">
                <h2>7. アクセス解析ツール</h2>
                <p>当サイトでは、サイトの利用状況を把握するため、Google Analyticsを使用しています（導入予定含む）。Google Analyticsは、Cookieを使用して、お客様の当サイトへのアクセス情報を収集します。</p>
                <p>収集される情報は匿名で収集されており、個人を特定するものではありません。この機能はCookieを無効にすることで収集を拒否することができます。詳細は、<a href="https://policies.google.com/technologies/partner-sites?hl=ja" target="_blank" rel="noopener noreferrer">Googleのプライバシーポリシー</a>をご確認ください。</p>
            </section>

            <!-- 8. セキュリティ -->
            <section class="privacy-section">
                <h2>8. 個人情報の安全管理</h2>
                <p>当社は、個人情報の紛失、破壊、改ざん及び漏洩などのリスクに対して、合理的な安全対策を講じます。</p>
                <ul>
                    <li>SSL/TLS暗号化通信の使用</li>
                    <li>アクセス権限の管理</li>
                    <li>セキュリティソフトウェアの導入</li>
                    <li>従業員への教育・研修の実施</li>
                </ul>
            </section>

            <!-- 9. 個人情報の開示・訂正・削除 -->
            <section class="privacy-section">
                <h2>9. 個人情報の開示・訂正・削除</h2>
                <p>お客様は、当社が保有するご自身の個人情報について、以下の権利を有します。</p>
                <ul>
                    <li>個人情報の開示請求</li>
                    <li>個人情報の訂正・追加・削除の請求</li>
                    <li>個人情報の利用停止・消去の請求</li>
                    <li>個人情報の第三者提供の停止の請求</li>
                </ul>
                <p>これらのご請求については、<a href="<?php echo esc_url(home_url('/contact')); ?>">お問い合わせフォーム</a>よりご連絡ください。ご本人確認の後、合理的な期間内に対応いたします。</p>
            </section>

            <!-- 10. 未成年者の個人情報 -->
            <section class="privacy-section">
                <h2>10. 未成年者の個人情報</h2>
                <p>未成年者が当サイトをご利用になる場合は、必ず保護者の同意を得た上でご利用ください。</p>
            </section>

            <!-- 11. プライバシーポリシーの変更 -->
            <section class="privacy-section">
                <h2>11. プライバシーポリシーの変更</h2>
                <p>当社は、法令の変更や事業内容の変更等に伴い、本ポリシーを予告なく変更することがあります。変更後のプライバシーポリシーは、当サイトに掲載した時点から効力を生じるものとします。</p>
            </section>

            <!-- 制定日・改定日 -->
            <section class="privacy-section policy-date">
                <p>制定日：2025年11月17日</p>
            </section>

        </div>
    </div>
</div>

<?php
get_footer();
?>
