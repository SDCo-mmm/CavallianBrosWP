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

// 背景色から適切な文字色を判定する関数
function get_text_color_from_bg($hex_color) {
    // カラーコードが空または無効な場合はデフォルト（白背景用の黒文字）
    if (empty($hex_color) || $hex_color === 'transparent') {
        return '#333';
    }
    
    // #を除去
    $hex_color = ltrim($hex_color, '#');
    
    // 短縮形（#FFF）を完全形（#FFFFFF）に変換
    if (strlen($hex_color) === 3) {
        $hex_color = $hex_color[0] . $hex_color[0] . $hex_color[1] . $hex_color[1] . $hex_color[2] . $hex_color[2];
    }
    
    // RGBに変換
    $r = hexdec(substr($hex_color, 0, 2));
    $g = hexdec(substr($hex_color, 2, 2));
    $b = hexdec(substr($hex_color, 4, 2));
    
    // 輝度を計算（YIQ方式）
    $brightness = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;
    
    // 輝度が128以上（明るい）なら黒文字、それ以下（暗い）なら白文字
    return ($brightness >= 128) ? '#333' : '#fff';
}

// ホバー時の色を生成する関数
function get_hover_colors($bg_color) {
    $text_color = get_text_color_from_bg($bg_color);
    
    // 通常時が黒文字（明るい背景）なら、ホバー時は暗い背景に白文字
    if ($text_color === '#333') {
        return array(
            'bg' => '#333',
            'text' => '#fff'
        );
    } else {
        // 通常時が白文字（暗い背景）なら、ホバー時は明るい背景に黒文字
        return array(
            'bg' => 'rgba(255, 255, 255, 0.9)',
            'text' => '#333'
        );
    }
}

?>

<!-- カスタムスタイルを出力 -->
<style>
<?php
if ($settings && $settings->exists()) {
    for ($i = 1; $i <= 5; $i++) {
        $bg_color = pods_field('page', $settings_page_id, "s{$i}_bg_color", true);
        
        // デフォルトは白背景（70%透明）
        if (empty($bg_color)) {
            $bg_color = '#ffffff';
        }
        
        // 文字色を自動判定
        $text_color = get_text_color_from_bg($bg_color);
        
        // ホバー時の色を取得
        $hover_colors = get_hover_colors($bg_color);
        
        // カラーコードをRGBAに変換（透明度70%）
        $hex_color = ltrim($bg_color, '#');
        if (strlen($hex_color) === 3) {
            $hex_color = $hex_color[0] . $hex_color[0] . $hex_color[1] . $hex_color[1] . $hex_color[2] . $hex_color[2];
        }
        $r = hexdec(substr($hex_color, 0, 2));
        $g = hexdec(substr($hex_color, 2, 2));
        $b = hexdec(substr($hex_color, 4, 2));
        $rgba_bg = "rgba($r, $g, $b, 0.7)";
        
        // スライダーごとのスタイルを出力
        ?>
.slide-caption-<?php echo $i; ?> .caption-text,
.slide-caption-<?php echo $i; ?> .caption-link {
    background-color: <?php echo $rgba_bg; ?>;
    color: <?php echo $text_color; ?>;
}

.slide-caption-<?php echo $i; ?> .caption-link:hover {
    background-color: <?php echo $hover_colors['bg']; ?>;
    color: <?php echo $hover_colors['text']; ?>;
}

<?php
    }
}
?>
</style>

<!-- ヒーローセクション / スライダー -->
<section class="hero">
    <div class="hero-slider">
        <?php 
        $has_slides = false;
        
        if ($settings && $settings->exists()) {
            // Podsからスライダー画像を取得（アンダースコアなし）
            for ($i = 1; $i <= 5; $i++) {
                $slide_field = pods_field('page', $settings_page_id, "slider_image_$i", true);
                
                // キャプション情報を取得(短縮版フィールド名)
                $caption_text = pods_field('page', $settings_page_id, "s{$i}_caption", true);
                $link_url = pods_field('page', $settings_page_id, "s{$i}_url", true);
                $link_text = pods_field('page', $settings_page_id, "s{$i}_text", true);
                $link_target = pods_field('page', $settings_page_id, "s{$i}_target", true); // 別ウィンドウで開くか
                
                // 背景色を取得
                $bg_color = pods_field('page', $settings_page_id, "s{$i}_bg_color", true);
                
                // デバッグ出力(管理者のみ表示)
                if (current_user_can('administrator')) {
                    echo "<!-- Slider $i Debug: -->";
                    echo "<!-- Caption: " . esc_html($caption_text) . " -->";
                    echo "<!-- URL: " . esc_html($link_url) . " -->";
                    echo "<!-- Text: " . esc_html($link_text) . " -->";
                    echo "<!-- Target: " . ($link_target ? '_blank' : '_self') . " -->";
                    echo "<!-- BG Color: " . esc_html($bg_color ?: 'default') . " -->";
                }
                
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
                            
                            <?php if ($caption_text || ($link_url && $link_text)) : ?>
                            <div class="slide-caption slide-caption-<?php echo $i; ?>">
                                <?php if ($caption_text) : ?>
                                    <p class="caption-text"><?php echo esc_html($caption_text); ?></p>
                                <?php endif; ?>
                                
                                <?php if ($link_url && $link_text) : ?>
                                    <a href="<?php echo esc_url($link_url); ?>" 
                                       class="caption-link"
                                       <?php if ($link_target) : ?>
                                       target="_blank" 
                                       rel="noopener noreferrer"
                                       <?php endif; ?>
                                    >
                                        <?php echo esc_html($link_text); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
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
<?php
// Podsからタイトルと本文を取得
$coming_soon_title = pods_field('page', $settings_page_id, 'coming_soon_title', true);
$coming_soon_text = pods_field('page', $settings_page_id, 'coming_soon_text', true);

// タイトルまたは本文のどちらかが入力されている場合のみセクションを表示
if (!empty($coming_soon_title) || !empty($coming_soon_text)) :
?>
<section class="section coming-soon">
    <div class="container">
        <?php if (!empty($coming_soon_title)) : ?>
            <h3 class="section-title"><?php echo esc_html($coming_soon_title); ?></h3>
        <?php endif; ?>
        
        <?php if (!empty($coming_soon_text)) : ?>
            <div class="coming-soon-content">
                <p class="coming-soon-text">
                    <?php 
                    // 改行を<br>タグに変換
                    echo nl2br(esc_html($coming_soon_text)); 
                    ?>
                </p>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<!-- Coming Soon セクション終了 -->

<!-- イベント情報セクション -->
<section class="section coming-soon">
    <div class="container">
        <!-- イベント情報 -->
        <?php
        global $wpdb;
        $today = date('Y-m-d');
        
        $events = new WP_Query(array(
            'post_type'      => 'events',
            'posts_per_page' => -1, // 全件取得してPHPでソート
            'meta_query'     => array(
                'relation' => 'OR',
                // パターン1: 終了日があるイベント → 終了日が今日以降
                array(
                    'relation' => 'AND',
                    array(
                        'key'     => 'event_date_end',
                        'value'   => array('', '0000-00-00', '0000-00-00 00:00:00'),
                        'compare' => 'NOT IN',
                    ),
                    array(
                        'key'     => 'event_date_end',
                        'value'   => $today,
                        'compare' => '>=',
                        'type'    => 'DATE'
                    ),
                ),
                // パターン2: 終了日がないイベント → 開始日が今日以降
                array(
                    'relation' => 'AND',
                    array(
                        'key'     => 'event_date_end',
                        'value'   => array('', '0000-00-00', '0000-00-00 00:00:00'),
                        'compare' => 'IN',
                    ),
                    array(
                        'key'     => 'event_date_start',
                        'value'   => $today,
                        'compare' => '>=',
                        'type'    => 'DATE'
                    ),
                ),
                // パターン3: 単日イベント → 開催日が今日以降
                array(
                    'relation' => 'AND',
                    array(
                        'key'     => 'event_date_start',
                        'value'   => array('', '0000-00-00', '0000-00-00 00:00:00'),
                        'compare' => 'IN',
                    ),
                    array(
                        'key'     => 'event_date',
                        'value'   => $today,
                        'compare' => '>=',
                        'type'    => 'DATE'
                    ),
                ),
            ),
        ));
        
        // 並び替え用にカスタムソート
        if ($events->have_posts()) {
            $sorted_events = array();
            while ($events->have_posts()) {
                $events->the_post();
                $event_date_start = get_post_meta(get_the_ID(), 'event_date_start', true);
                $event_date = get_post_meta(get_the_ID(), 'event_date', true);
                
                $is_valid_date = function($date) {
                    return !empty($date) && $date !== '0000-00-00' && $date !== '0000-00-00 00:00:00';
                };
                
                $sort_date = '';
                if ($is_valid_date($event_date_start)) {
                    $sort_date = $event_date_start;
                } elseif ($is_valid_date($event_date)) {
                    $sort_date = $event_date;
                }
                
                $sorted_events[] = array(
                    'post' => get_post(),
                    'sort_date' => $sort_date
                );
            }
            wp_reset_postdata();
            
            // 日付順にソート
            usort($sorted_events, function($a, $b) {
                return strcmp($a['sort_date'], $b['sort_date']);
            });
            
            // 最初の3件のみ
            $sorted_events = array_slice($sorted_events, 0, 3);
            
            // COMING SOONセクションが表示されているかチェック
            $has_coming_soon = (!empty($coming_soon_title) || !empty($coming_soon_text));
        ?>
            <div class="upcoming-events<?php echo $has_coming_soon ? ' has-coming-soon' : ''; ?>">
                <h4 class="events-title">UPCOMING EVENTS!</h4>
                <div class="events-grid">
                    <?php foreach ($sorted_events as $event_data) : 
                        $event_post = $event_data['post'];
                        setup_postdata($event_post);
                    ?>
                        <div class="event-item">
                            <?php if (has_post_thumbnail($event_post->ID)) : ?>
                                <div class="event-item-image">
                                    <a href="<?php echo get_permalink($event_post->ID); ?>">
                                        <?php echo get_the_post_thumbnail($event_post->ID, 'medium'); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <div class="event-item-content">
                                <div class="event-date">
                                    <?php
                                    $event_date_start = get_post_meta($event_post->ID, 'event_date_start', true);
                                    $event_date_end = get_post_meta($event_post->ID, 'event_date_end', true);
                                    $event_date = get_post_meta($event_post->ID, 'event_date', true);
                                    
                                    $is_valid_date = function($date) {
                                        return !empty($date) && $date !== '0000-00-00' && $date !== '0000-00-00 00:00:00';
                                    };
                                    
                                    echo '<strong>開催日: </strong>';
                                    
                                    if ($is_valid_date($event_date_start)) {
                                        $start_timestamp = strtotime($event_date_start);
                                        
                                        if ($start_timestamp !== false) {
                                            echo date_i18n('Y.m.d', $start_timestamp);
                                            
                                            if ($is_valid_date($event_date_end)) {
                                                $end_timestamp = strtotime($event_date_end);
                                                if ($end_timestamp !== false) {
                                                    echo '〜' . date_i18n('Y.m.d', $end_timestamp);
                                                }
                                            }
                                        }
                                    } elseif ($is_valid_date($event_date)) {
                                        $date_timestamp = strtotime($event_date);
                                        if ($date_timestamp !== false) {
                                            echo date_i18n('Y.m.d', $date_timestamp);
                                        }
                                    }
                                    ?>
                                </div>
                                <h5 class="event-title">
                                    <?php echo get_the_title($event_post->ID); ?>
                                </h5>
                                <div class="event-excerpt">
                                    <?php echo get_the_excerpt($event_post->ID); ?>
                                </div>
                                <a href="<?php echo get_permalink($event_post->ID); ?>" class="event-item-link">詳細はこちら</a>
                            </div>
                        </div>
                    <?php endforeach; 
                    wp_reset_postdata();
                    ?>
                </div>
                <div class="events-more">
                    <a href="<?php echo get_post_type_archive_link('events'); ?>" class="btn-more">
                        すべてのイベントを見る
                    </a>
                </div>
            </div>
        <?php } else { ?>
            <div class="no-upcoming-events">
                <p>現在、予定されているイベントはありません。</p>
            </div>
        <?php } ?>
    </div>
</section>

<?php get_footer(); ?>
