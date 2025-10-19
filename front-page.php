<?php
/**
 * トップページテンプレート（Pods対応版）
 *
 * @package Cavallian_Bros
 */

get_header(); 

// 現在表示されているページ（ホーム）のIDを自動取得
$settings_page_id = get_the_ID(); // 自動的にホームページのIDを取得
$settings = pods('page', $settings_page_id);

// デバッグ用（本番環境では削除してください）
$debug_mode = false; // trueにするとデバッグ情報が表示されます
if ($debug_mode && current_user_can('administrator')) {
    echo '<!-- Debug: Current Page ID = ' . $settings_page_id . ' -->';
    echo '<!-- Debug: Page Title = ' . get_the_title($settings_page_id) . ' -->';
    if ($settings && $settings->exists()) {
        echo '<!-- Debug: Pods object exists -->';
    } else {
        echo '<!-- Debug: Pods object NOT found -->';
    }
}

?>

<!-- ヒーローセクション / スライダー -->
<section class="hero">
    <div class="hero-slider">
        <?php 
        $has_slides = false;
        
        if ($settings && $settings->exists()) {
            // Podsからスライダー画像を取得（アンダースコアなし）
            for ($i = 1; $i <= 5; $i++) {
                $slide_field = pods_field('page', $settings_page_id, "slider_image_$i", true);
                
                if (!empty($slide_field)) {
                    $has_slides = true;
                    $slide_url = '';
                    
                    // 画像URLを取得（様々な形式に対応）
                    if (is_array($slide_field)) {
                        if (isset($slide_field['ID'])) {
                            // 添付ファイルIDから画像URLを取得
                            $slide_url = wp_get_attachment_url($slide_field['ID']);
                        } elseif (isset($slide_field['guid'])) {
                            $slide_url = $slide_field['guid'];
                        } elseif (isset($slide_field['url'])) {
                            $slide_url = $slide_field['url'];
                        }
                    } elseif (is_numeric($slide_field)) {
                        // IDの場合
                        $slide_url = wp_get_attachment_url($slide_field);
                    } else {
                        // 文字列（URL）の場合
                        $slide_url = $slide_field;
                    }
                    
                    if ($slide_url) :
                    ?>
                        <div class="slide <?php echo $i === 1 ? 'active' : ''; ?>">
                            <img src="<?php echo esc_url($slide_url); ?>" alt="スライド<?php echo $i; ?>">
                        </div>
                    <?php
                    endif;
                }
            }
        }
        
        // デフォルト画像（Podsに画像が設定されていない場合）
        if (!$has_slides) :
            // assets/imagesフォルダに画像がある場合
            for ($i = 1; $i <= 5; $i++) :
                $default_image = get_template_directory_uri() . "/assets/images/slide$i.jpg";
            ?>
                <div class="slide <?php echo $i === 1 ? 'active' : ''; ?>">
                    <img src="<?php echo esc_url($default_image); ?>" alt="スライド<?php echo $i; ?>">
                </div>
            <?php 
            endfor;
        endif;
        ?>
    </div>
    <!-- メインコピーを削除（下のアバウトセクションに移動） -->
</section>

<!-- アバウトセクション -->
<section id="about" class="section about">
    <div class="container">
        <div class="about-content">
            <div class="about-title">
                <?php 
                // Podsからアバウト画像を取得（アンダースコアなし）
                $about_image = pods_field('page', $settings_page_id, 'about_image', true);
                $about_image_url = '';
                
                if ($about_image) {
                    if (is_array($about_image)) {
                        if (isset($about_image['ID'])) {
                            $about_image_url = wp_get_attachment_url($about_image['ID']);
                        } elseif (isset($about_image['guid'])) {
                            $about_image_url = $about_image['guid'];
                        } elseif (isset($about_image['url'])) {
                            $about_image_url = $about_image['url'];
                        }
                    } elseif (is_numeric($about_image)) {
                        $about_image_url = wp_get_attachment_url($about_image);
                    } else {
                        $about_image_url = $about_image;
                    }
                }
                
                if ($about_image_url) : ?>
                    <img src="<?php echo esc_url($about_image_url); ?>" alt="Cavallian Bros.">
                <?php else : ?>
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/about-title.png" alt="Cavallian Bros.">
                <?php endif; ?>
            </div>
            <div class="about-text">
                <?php 
                // メインコピーをここに表示
                $copy1 = pods_field('page', $settings_page_id, 'hero_copy_line1', true);
                $copy2 = pods_field('page', $settings_page_id, 'hero_copy_line2', true);
                $copy3 = pods_field('page', $settings_page_id, 'hero_copy_line3', true);
                ?>
                <p class="main-copy">
                    <?php echo esc_html($copy1 ?: 'ほんの少しのぬくもりが、'); ?><br>
                    <?php echo esc_html($copy2 ?: '心のざわめきをほどいていく。'); ?><br>
                    <?php echo esc_html($copy3 ?: 'そんな毎日が、宝物になる。'); ?>
                </p>
            </div>
        </div>
    </div>
</section>

<!-- メッセージセクション -->
<section id="message" class="section message">
    <div class="container">
        <div class="message-grid">
            <div class="message-image">
                <?php
                // Podsからメッセージ画像を取得（アンダースコアなし）
                $message_image = pods_field('page', $settings_page_id, 'message_image', true);
                $message_image_url = '';
                
                if ($message_image) {
                    if (is_array($message_image)) {
                        if (isset($message_image['ID'])) {
                            $message_image_url = wp_get_attachment_url($message_image['ID']);
                        } elseif (isset($message_image['guid'])) {
                            $message_image_url = $message_image['guid'];
                        } elseif (isset($message_image['url'])) {
                            $message_image_url = $message_image['url'];
                        }
                    } elseif (is_numeric($message_image)) {
                        $message_image_url = wp_get_attachment_url($message_image);
                    } else {
                        $message_image_url = $message_image;
                    }
                }
                
                if ($message_image_url) : ?>
                    <img src="<?php echo esc_url($message_image_url); ?>" alt="キャバリアと飼い主">
                <?php else : ?>
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/message-image.jpg" alt="キャバリアと飼い主">
                <?php endif; ?>
            </div>
            <div class="message-content">
                <div class="message-text">
                    <?php
                    // Podsからメッセージテキストを取得（アンダースコアなし）
                    $message_text = pods_field('page', $settings_page_id, 'message_text', true);
                    
                    if ($message_text) {
                        // WYSIWYGフィールドの内容を表示
                        // wpautopとnl2brを使って改行を確実に処理
                        echo '<div class="message-text-inner">';
                        // HTMLタグが含まれているかチェック
                        if (strpos($message_text, '<p>') !== false || strpos($message_text, '<br') !== false) {
                            // すでにHTMLフォーマットされている場合
                            echo wp_kses_post($message_text);
                        } else {
                            // プレーンテキストの場合は改行を処理
                            echo wp_kses_post(wpautop($message_text));
                        }
                        echo '</div>';
                    } else {
                        // デフォルトテキスト
                        ?>
                        <p>こっちが忙しいときは、<br>
                        小さな王様は、ちゃんと静かにしてくれる。</p>
                        
                        <p>気持ちが沈んでる日は、<br>
                        なにも言わず、そっと隣にいてくれる。</p>
                        
                        <p>言葉も、態度もないのに、<br>
                        どうしてこんなにやさしいんだろうと思う。</p>
                        
                        <p>そして、<br>
                        そのやさしさに甘えてしまっている自分に気づく。</p>
                        
                        <p>つい忘れてしまうけど、<br>
                        あの子の心にも波があって、<br>
                        疲れる日だってきっとあるのに、<br>
                        なにかを求めてくることは、ほとんどない。</p>
                        
                        <p><strong>キャバリアは、見返りを求めない。<br>
                        ただ、今日もそばにいることを選んでくれる。</strong></p>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Coming Soon セクション -->
<section class="section coming-soon">
    <div class="container">
        <h3 class="section-title">Coming Soon</h3>
        <div class="coming-soon-content">
            <?php if (class_exists('WooCommerce')) : ?>
                <?php
                $args = array(
                    'post_type'      => 'product',
                    'posts_per_page' => 6,
                    'meta_query'     => array(
                        'relation' => 'OR',
                        array(
                            'key'     => '_stock_status',
                            'value'   => 'onbackorder',
                            'compare' => '='
                        ),
                        array(
                            'key'     => '_coming_soon',
                            'value'   => 'yes',
                            'compare' => '='
                        )
                    ),
                    'orderby'        => 'date',
                    'order'          => 'DESC'
                );
                
                $products = new WP_Query($args);
                
                if ($products->have_posts()) : ?>
                    <div class="products-grid coming-soon-products">
                        <?php while ($products->have_posts()) : $products->the_post(); ?>
                            <div class="product-item">
                                <a href="<?php the_permalink(); ?>">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <?php the_post_thumbnail('medium'); ?>
                                    <?php else : ?>
                                        <div class="no-image">Coming Soon</div>
                                    <?php endif; ?>
                                    <h4><?php the_title(); ?></h4>
                                </a>
                            </div>
                        <?php endwhile; ?>
                    </div>
                    <?php wp_reset_postdata(); ?>
                <?php else : ?>
                    <p class="coming-soon-text">
                        Cavallian Bros.では、愛犬との暮らしをより豊かにするオリジナルアイテムと<br>
                        厳選したセレクトブランドの展開を準備中です。<br>
                        <br>
                        また、キャバリアオーナーの集いや他犬種との交流イベント、<br>
                        アットホームなオフ会、愛犬の誕生日会など、<br>
                        みんなで楽しめる特別な時間もご用意してまいります。
                    </p>
                <?php endif; ?>
            <?php else : ?>
                <p class="coming-soon-text">
                    Cavallian Bros.では、愛犬との暮らしをより豊かにするオリジナルアイテムと<br>
                    厳選したセレクトブランドの展開を準備中です。<br>
                    <br>
                    また、キャバリアオーナーの集いや他犬種との交流イベント、<br>
                    アットホームなオフ会、愛犬の誕生日会など、<br>
                    みんなで楽しめる特別な時間もご用意してまいります。
                </p>
            <?php endif; ?>
        </div>
        
        <!-- イベント情報 -->
        <?php
        $events = new WP_Query(array(
            'post_type'      => 'events',
            'posts_per_page' => 3,
            'orderby'        => 'meta_value',
            'meta_key'       => 'event_date',
            'meta_type'      => 'DATE',
            'order'          => 'ASC',
            'meta_query'     => array(
                array(
                    'key'     => 'event_date',
                    'value'   => date('Y-m-d'),
                    'compare' => '>=',
                    'type'    => 'DATE'
                )
            )
        ));
        
        if ($events->have_posts()) : ?>
            <div class="upcoming-events">
                <h4 class="events-title">Upcoming Events</h4>
                <div class="events-grid">
                    <?php while ($events->have_posts()) : $events->the_post(); ?>
                        <div class="event-item">
                            <div class="event-date">
                                <?php
                                $event_date = get_post_meta(get_the_ID(), 'event_date', true);
                                if ($event_date) {
                                    echo date_i18n('Y.m.d', strtotime($event_date));
                                }
                                ?>
                            </div>
                            <h5 class="event-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h5>
                            <div class="event-excerpt">
                                <?php the_excerpt(); ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
                <div class="events-more">
                    <a href="<?php echo get_post_type_archive_link('events'); ?>" class="btn-more">
                        すべてのイベントを見る
                    </a>
                </div>
            </div>
            <?php wp_reset_postdata(); ?>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>