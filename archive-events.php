<?php
/**
 * イベントアーカイブページテンプレート
 *
 * @package Cavallian_Bros
 */

get_header();
?>

<!-- イベント用ヒーローセクション -->
<section class="events-hero">
    <h1>EVENTS</h1>
</section>

<section class="events-archive">
    <div class="container">
        
        <?php if (have_posts()) : ?>
            <div class="events-list">
                <?php while (have_posts()) : the_post(); ?>
                    <article class="event-card">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="event-card-image">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('large'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="event-card-content">
                            <div class="event-card-date">
                                <?php
                                // Podsフィールドを取得
                                $event_date_start = get_post_meta(get_the_ID(), 'event_date_start', true);
                                $event_date_end = get_post_meta(get_the_ID(), 'event_date_end', true);
                                $event_date = get_post_meta(get_the_ID(), 'event_date', true);
                                
                                // 無効な日付をチェックする関数
                                $is_valid_date = function($date) {
                                    return !empty($date) && $date !== '0000-00-00' && $date !== '0000-00-00 00:00:00';
                                };
                                
                                // 「開催日:」ラベルを追加
                                echo '<strong>開催日: </strong>';
                                
                                // 複数日イベント（event_date_start + event_date_end）
                                if ($is_valid_date($event_date_start)) {
                                    $start_timestamp = strtotime($event_date_start);
                                    
                                    if ($start_timestamp !== false) {
                                        echo date_i18n('Y.m.d', $start_timestamp);
                                        
                                        // 終了日がある場合は範囲表示
                                        if ($is_valid_date($event_date_end)) {
                                            $end_timestamp = strtotime($event_date_end);
                                            if ($end_timestamp !== false) {
                                                echo '〜' . date_i18n('Y.m.d', $end_timestamp);
                                            }
                                        }
                                    }
                                }
                                // 単日イベント（event_dateのみ）
                                elseif ($is_valid_date($event_date)) {
                                    $date_timestamp = strtotime($event_date);
                                    if ($date_timestamp !== false) {
                                        echo date_i18n('Y.m.d', $date_timestamp);
                                    }
                                }
                                ?>
                            </div>
                            
                            <h2 class="event-card-title">
                                <?php the_title(); ?>
                            </h2>
                            
                            <div class="event-card-excerpt">
                                <?php the_excerpt(); ?>
                            </div>
                            
                            <a href="<?php the_permalink(); ?>" class="event-card-link">詳細はこちら</a>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
            
            <?php
            // ページネーション
            the_posts_pagination(array(
                'prev_text' => __('前へ', 'cavallian-bros'),
                'next_text' => __('次へ', 'cavallian-bros'),
            ));
            ?>
            
            <!-- 過去のイベントボタン -->
            <div class="past-events-button">
                <a href="<?php echo home_url('/events-past/'); ?>" class="btn-past-events">
                    過去のイベント
                </a>
            </div>
            
        <?php else : ?>
            <div class="no-events">
                <p style="text-align: center; padding: 60px 0; color: #999;">
                    現在、イベント情報はありません。
                </p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
