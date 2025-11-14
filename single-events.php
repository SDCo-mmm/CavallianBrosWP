<?php
/**
 * イベント詳細ページテンプレート
 *
 * @package Cavallian_Bros
 */

get_header();
?>

<?php while (have_posts()) : the_post(); 
    // 終了判定
    $event_date_start = get_post_meta(get_the_ID(), 'event_date_start', true);
    $event_date_end = get_post_meta(get_the_ID(), 'event_date_end', true);
    $event_date = get_post_meta(get_the_ID(), 'event_date', true);
    
    $is_valid_date = function($date) {
        return !empty($date) && $date !== '0000-00-00' && $date !== '0000-00-00 00:00:00';
    };
    
    $is_finished = false;
    $today = date('Y-m-d');
    
    // 終了日がある場合は終了日で判定
    if ($is_valid_date($event_date_end)) {
        $is_finished = ($event_date_end < $today);
    }
    // 終了日がない場合は開始日で判定
    elseif ($is_valid_date($event_date_start)) {
        $is_finished = ($event_date_start < $today);
    }
    // 単日イベント
    elseif ($is_valid_date($event_date)) {
        $is_finished = ($event_date < $today);
    }
?>
    <article class="event-single <?php echo $is_finished ? 'event-finished' : ''; ?>">
        <div class="container">
            <!-- イベントヘッダー -->
            <header class="entry-header">
                <?php if ($is_finished) : ?>
                    <div class="event-finished-badge-single">終了</div>
                <?php endif; ?>
                
                <div class="event-meta-top">
                    <div class="event-date-large">
                        <?php
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
            
            <!-- 関連商品セクション -->
            <?php
            // Pods関数を使って正しく取得
            $event_pod = pods('events', get_the_ID());
            $related_events = $event_pod->field('related_events');
            
            // デバッグ用（管理者のみ）
            if (current_user_can('administrator')) {
                echo '<!-- Related Events Debug (Pods) -->';
                echo '<!-- Raw data: ' . print_r($related_events, true) . ' -->';
                echo '<!-- Is array: ' . (is_array($related_events) ? 'Yes' : 'No') . ' -->';
                if (is_array($related_events)) {
                    echo '<!-- Count: ' . count($related_events) . ' -->';
                }
            }
            
            if (!empty($related_events)) :
                // 商品IDの配列を作成
                $product_ids = array();
                
                if (is_array($related_events)) {
                    foreach ($related_events as $product) {
                        if (isset($product['ID'])) {
                            $product_ids[] = $product['ID'];
                        } elseif (is_numeric($product)) {
                            $product_ids[] = $product;
                        }
                    }
                } elseif (is_numeric($related_events)) {
                    $product_ids[] = $related_events;
                }
                
                // デバッグ用
                if (current_user_can('administrator')) {
                    echo '<!-- Product IDs: ' . implode(', ', $product_ids) . ' -->';
                }
                
                if (!empty($product_ids)) :
                    // 商品クエリ
                    $related_products_query = new WP_Query(array(
                        'post_type' => 'product',
                        'post__in' => $product_ids,
                        'posts_per_page' => -1,
                        'orderby' => 'post__in', // 選択した順番を維持
                    ));
                    
                    // デバッグ用
                    if (current_user_can('administrator')) {
                        echo '<!-- Query found: ' . $related_products_query->found_posts . ' posts -->';
                    }
                    
                    if ($related_products_query->have_posts()) :
            ?>
                <div class="related-events-section">
                    <h2 class="related-events-title">RELATED EVENTS</h2>
                    
                    <div class="related-events-grid">
                        <?php while ($related_products_query->have_posts()) : $related_products_query->the_post(); 
                            global $product;
                        ?>
                            <div class="related-event-item">
                                <a href="<?php echo get_permalink(); ?>" class="related-event-link">
                                    <div class="related-event-image">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <?php the_post_thumbnail('woocommerce_thumbnail'); ?>
                                        <?php else : ?>
                                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/placeholder.png" alt="<?php the_title(); ?>">
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="related-event-info">
                                        <h3 class="related-event-name"><?php the_title(); ?></h3>
                                        <div class="related-event-price">
                                            <?php echo $product->get_price_html(); ?>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endwhile; 
                        wp_reset_postdata();
                        ?>
                    </div>
                </div>
            <?php 
                    endif; // $related_products_query->have_posts()
                endif; // !empty($product_ids)
            endif; // !empty($related_events)
            ?>
            
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
