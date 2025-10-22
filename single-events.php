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
                        $event_date = get_post_meta(get_the_ID(), 'event_date', true);
                        if ($event_date) {
                            echo date_i18n('Y.m.d', strtotime($event_date));
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
                $event_capacity = get_post_meta(get_the_ID(), 'event_capacity', true);
                
                if ($event_date || $event_date_start || $event_time || $event_location || $event_capacity) :
                ?>
                    <div class="event-info-box">
                        <?php if ($event_date || $event_date_start) : ?>
                            <div class="event-info-item">
                                <strong>開催日:</strong>
                                <?php 
                                // 複数日イベント（event_date_start + event_date_end）
                                if ($event_date_start && !empty($event_date_start)) {
                                    $start_timestamp = strtotime($event_date_start);
                                    echo date_i18n('Y年m月d日', $start_timestamp);
                                    
                                    if ($event_date_end && !empty($event_date_end)) {
                                        $end_timestamp = strtotime($event_date_end);
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
                                // 単日イベント（event_dateのみ）
                                elseif ($event_date && !empty($event_date)) {
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
