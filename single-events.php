<?php
/**
 * イベント詳細ページテンプレート
 *
 * @package Cavallian_Bros
 */

get_header();
?>

<?php while (have_posts()) : the_post(); ?>
    <article class="event-single">
        <div class="container">
            <!-- イベントヘッダー -->
            <header class="entry-header">
                <div class="event-meta-top">
                    <div class="event-date-large">
                        <?php
                        /* ===== 修正: ヘッダー日付表示（範囲表示対応） ===== */
                        
                        // Podsフィールドを取得
                        $event_date_start = get_post_meta(get_the_ID(), 'event_date_start', true);
                        $event_date_end = get_post_meta(get_the_ID(), 'event_date_end', true);
                        $event_date = get_post_meta(get_the_ID(), 'event_date', true);
                        
                        // 無効な日付をチェックする関数
                        $is_valid_date = function($date) {
                            return !empty($date) && $date !== '0000-00-00' && $date !== '0000-00-00 00:00:00';
                        };
                        
                        // 複数日イベント（範囲表示）
                        if ($is_valid_date($event_date_start)) {
                            $date_timestamp = strtotime($event_date_start);
                            if ($date_timestamp !== false) {
                                echo date_i18n('Y.m.d', $date_timestamp);
                                
                                // 終了日がある場合は範囲表示
                                if ($is_valid_date($event_date_end)) {
                                    $end_timestamp = strtotime($event_date_end);
                                    if ($end_timestamp !== false) {
                                        echo '〜' . date_i18n('Y.m.d', $end_timestamp);
                                    }
                                }
                            }
                        } 
                        // 単日イベント
                        elseif ($is_valid_date($event_date)) {
                            $date_timestamp = strtotime($event_date);
                            if ($date_timestamp !== false) {
                                echo date_i18n('Y.m.d', $date_timestamp);
                            }
                        }
                        ?>
                    </div>
                </div>
                
                <h1 class="entry-title"><?php the_title(); ?></h1>
                
                <?php if (has_post_thumbnail()) : ?>
                    <div class="event-featured-image">
                        <?php the_post_thumbnail('full'); ?>
                    </div>
                <?php endif; ?>
            </header>
            
            <!-- イベント詳細情報 -->
            <div class="event-details">
                <?php
                // Podsからイベント情報を取得
                $event_date = get_post_meta(get_the_ID(), 'event_date', true);
                $event_date_start = get_post_meta(get_the_ID(), 'event_date_start', true);
                $event_date_end = get_post_meta(get_the_ID(), 'event_date_end', true);
                $event_time = get_post_meta(get_the_ID(), 'event_time', true);
                $event_location = get_post_meta(get_the_ID(), 'event_location', true);
                $event_price = get_post_meta(get_the_ID(), 'event_price', true);
                $event_capacity = get_post_meta(get_the_ID(), 'event_capacity', true);
                
                if ($event_date || $event_date_start || $event_time || $event_location || $event_price || $event_capacity) :
                ?>
                    <div class="event-info-box">
                        <?php if ($event_date || $event_date_start) : ?>
                            <div class="event-info-item">
                                <strong>開催日:</strong>
                                <?php 
                                /* ===== 修正: 詳細日付表示ロジック（0000-00-00対応） ===== */
                                
                                // 無効な日付をチェックする関数
                                $is_valid_date = function($date) {
                                    return !empty($date) && $date !== '0000-00-00' && $date !== '0000-00-00 00:00:00';
                                };
                                
                                // 複数日イベント（event_date_start + event_date_end）
                                if ($is_valid_date($event_date_start)) {
                                    $start_timestamp = strtotime($event_date_start);
                                    
                                    if ($start_timestamp !== false) {
                                        echo date_i18n('Y年m月d日', $start_timestamp);
                                        
                                        // 終了日がある場合
                                        if ($is_valid_date($event_date_end)) {
                                            $end_timestamp = strtotime($event_date_end);
                                            if ($end_timestamp !== false) {
                                                // 年度をまたぐかチェック
                                                if (date('Y', $start_timestamp) !== date('Y', $end_timestamp)) {
                                                    // 年度またぎ: 両方に年を表示
                                                    echo '〜' . date_i18n('Y年m月d日', $end_timestamp);
                                                } else {
                                                    // 同年内: 終了日は月日のみ
                                                    echo '〜' . date_i18n('m月d日', $end_timestamp);
                                                }
                                            }
                                        }
                                    }
                                }
                                // 単日イベント（event_dateのみ）
                                elseif ($is_valid_date($event_date)) {
                                    $date_timestamp = strtotime($event_date);
                                    if ($date_timestamp !== false) {
                                        echo date_i18n('Y年m月d日', $date_timestamp);
                                    }
                                }
                                ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($event_time) : ?>
                            <div class="event-info-item">
                                <strong>時間:</strong> <?php echo esc_html($event_time); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($event_location) : ?>
                            <div class="event-info-item">
                                <strong>場所:</strong> <?php echo esc_html($event_location); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($event_price !== '' && $event_price !== null) : ?>
                            <div class="event-info-item">
                                <strong>参加費:</strong> 
                                <?php 
                                if ($event_price == 0) {
                                    echo '無料';
                                } else {
                                    echo number_format($event_price) . '円';
                                }
                                ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($event_capacity) : ?>
                            <div class="event-info-item">
                                <strong>定員:</strong> <?php echo esc_html($event_capacity); ?>名
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- イベント本文 -->
            <div class="entry-content">
                <?php the_content(); ?>
            </div>
            
            <!-- イベント一覧に戻るリンク -->
            <div class="event-navigation">
                <a href="<?php echo get_post_type_archive_link('events'); ?>" class="btn-back">
                    ← イベント一覧に戻る
                </a>
            </div>
        </div>
    </article>
<?php endwhile; ?>

<?php get_footer(); ?>
