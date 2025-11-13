<?php
/**
 * Template Name: Past Events Archive
 * 過去のイベント一覧ページ（年度別 + ページネーション対応）
 *
 * @package Cavallian_Bros
 */

get_header();

// 404エラーを防ぐ
status_header(200);
global $wp_query;
$wp_query->is_404 = false;

$today = date('Y-m-d');
$current_year = isset($_GET['event_year']) ? intval($_GET['event_year']) : '';
$paged = isset($_GET['paged']) ? intval($_GET['paged']) : 1;

// デバッグ用
if (current_user_can('administrator')) {
    echo '<!-- GET event_year: ' . (isset($_GET['event_year']) ? $_GET['event_year'] : 'なし') . ' -->';
    echo '<!-- current_year: ' . $current_year . ' -->';
    echo '<!-- paged: ' . $paged . ' -->';
}

// シンプルなクエリで全イベント取得
$all_events_query = new WP_Query(array(
    'post_type'      => 'events',
    'posts_per_page' => -1,
    'orderby'        => 'meta_value',
    'meta_key'       => 'event_date_start',
    'meta_type'      => 'DATE',
    'order'          => 'DESC', // 降順に変更
));

// PHP側でフィルタリング
$events_by_year = array();
$available_years = array();

if ($all_events_query->have_posts()) {
    while ($all_events_query->have_posts()) {
        $all_events_query->the_post();
        
        $event_date_start = get_post_meta(get_the_ID(), 'event_date_start', true);
        $event_date_end = get_post_meta(get_the_ID(), 'event_date_end', true);
        $event_date = get_post_meta(get_the_ID(), 'event_date', true);
        
        $is_valid_date = function($date) {
            return !empty($date) && $date !== '0000-00-00' && $date !== '0000-00-00 00:00:00';
        };
        
        // 終了判定
        $is_finished = false;
        $event_year = '';
        $sort_date = '';
        
        if ($is_valid_date($event_date_end)) {
            $is_finished = ($event_date_end < $today);
            if ($is_valid_date($event_date_start)) {
                $event_year = date('Y', strtotime($event_date_start));
                $sort_date = $event_date_start;
            }
        } elseif ($is_valid_date($event_date_start)) {
            $is_finished = ($event_date_start < $today);
            $event_year = date('Y', strtotime($event_date_start));
            $sort_date = $event_date_start;
        } elseif ($is_valid_date($event_date)) {
            $is_finished = ($event_date < $today);
            $event_year = date('Y', strtotime($event_date));
            $sort_date = $event_date;
        }
        
        // 終了したイベントのみ年度別に分類
        if ($is_finished && $event_year) {
            if (!isset($events_by_year[$event_year])) {
                $events_by_year[$event_year] = array();
            }
            $events_by_year[$event_year][] = array(
                'post' => get_post(),
                'sort_date' => $sort_date
            );
            $available_years[$event_year] = $event_year;
        }
    }
    wp_reset_postdata();
}

// 各年度内で日付降順にソート
foreach ($events_by_year as $year => &$events) {
    usort($events, function($a, $b) {
        return strcmp($b['sort_date'], $a['sort_date']); // 降順
    });
}

krsort($available_years);

// デフォルト年度設定（最新年度）
if (empty($current_year) && !empty($available_years)) {
    $current_year = reset($available_years);
}

// 選択年度のイベント
$all_filtered_events = isset($events_by_year[$current_year]) ? $events_by_year[$current_year] : array();
$total_events = count($all_filtered_events);
$per_page = 9;
$total_pages = ceil($total_events / $per_page);

// ページネーション用にスライス
$offset = ($paged - 1) * $per_page;
$filtered_events = array_slice($all_filtered_events, $offset, $per_page);

// デバッグ用（管理者のみ表示）
if (current_user_can('administrator')) {
    echo '<!-- デバッグ情報 -->';
    echo '<!-- 現在の年度: ' . $current_year . ' -->';
    echo '<!-- 利用可能な年度: ' . implode(', ', array_keys($events_by_year)) . ' -->';
    echo '<!-- 該当イベント数: ' . count($filtered_events) . ' -->';
    if (!empty($events_by_year)) {
        foreach ($events_by_year as $year => $events) {
            echo '<!-- ' . $year . '年: ' . count($events) . '件 -->';
        }
    }
    echo '<!-- /デバッグ情報 -->';
}
?>

<!-- イベント用ヒーローセクション -->
<section class="events-hero">
    <h1>PAST EVENTS</h1>
</section>

<section class="events-archive past-events-archive">
    <div class="container">
        
        <!-- 年度セレクトボックス -->
        <?php if (!empty($available_years)) : ?>
            <div class="year-filter">
                <form method="get" action="<?php echo home_url('/events-past/'); ?>">
                    <label for="year-select">年度を選択:</label>
                    <select id="year-select" name="event_year" onchange="this.form.submit();">
                        <?php foreach ($available_years as $year) : 
                            $selected = ($year == $current_year) ? 'selected' : '';
                        ?>
                            <option value="<?php echo esc_attr($year); ?>" <?php echo $selected; ?>>
                                <?php echo esc_html($year); ?>年
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($filtered_events)) : ?>
            
            <div class="events-year-section">
                <h2 class="events-year-title"><?php echo esc_html($current_year); ?>年</h2>
                
                <div class="events-list">
                    <?php foreach ($filtered_events as $event_data) : 
                        $event = $event_data['post'];
                        setup_postdata($event);
                        
                        $event_date_start = get_post_meta($event->ID, 'event_date_start', true);
                        $event_date_end = get_post_meta($event->ID, 'event_date_end', true);
                        $event_date = get_post_meta($event->ID, 'event_date', true);
                        
                        $is_valid_date = function($date) {
                            return !empty($date) && $date !== '0000-00-00' && $date !== '0000-00-00 00:00:00';
                        };
                    ?>
                        <article class="event-card event-finished">
                            <div class="event-card-image">
                                <span class="event-finished-badge">終了</span>
                                <?php if (has_post_thumbnail($event->ID)) : ?>
                                    <a href="<?php echo get_permalink($event->ID); ?>">
                                        <?php echo get_the_post_thumbnail($event->ID, 'large'); ?>
                                    </a>
                                <?php else : ?>
                                    <a href="<?php echo get_permalink($event->ID); ?>">
                                        <div style="width:100%;height:100%;background:#f5f5f5;"></div>
                                    </a>
                                <?php endif; ?>
                            </div>
                            
                            <div class="event-card-content">
                                <div class="event-card-date">
                                    <strong>開催日: </strong>
                                    <?php
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
                                
                                <h2 class="event-card-title">
                                    <?php echo get_the_title($event->ID); ?>
                                </h2>
                                
                                <div class="event-card-excerpt">
                                    <?php echo get_the_excerpt($event->ID); ?>
                                </div>
                                
                                <a href="<?php echo get_permalink($event->ID); ?>" class="event-card-link">詳細はこちら</a>
                            </div>
                        </article>
                    <?php endforeach; 
                    wp_reset_postdata();
                    ?>
                </div>
                
                <!-- ページネーション -->
                <?php if ($total_pages > 1) : ?>
                    <div class="pagination">
                        <?php
                        $base_url = add_query_arg('event_year', $current_year, home_url('/events-past/'));
                        
                        echo '<div class="nav-links">';
                        
                        // 前へ
                        if ($paged > 1) {
                            $prev_url = add_query_arg('paged', ($paged - 1), $base_url);
                            echo '<a href="' . esc_url($prev_url) . '" class="page-numbers">前へ</a>';
                        }
                        
                        // ページ番号
                        for ($i = 1; $i <= $total_pages; $i++) {
                            if ($i == $paged) {
                                echo '<span class="page-numbers current">' . $i . '</span>';
                            } else {
                                $page_url = add_query_arg('paged', $i, $base_url);
                                echo '<a href="' . esc_url($page_url) . '" class="page-numbers">' . $i . '</a>';
                            }
                        }
                        
                        // 次へ
                        if ($paged < $total_pages) {
                            $next_url = add_query_arg('paged', ($paged + 1), $base_url);
                            echo '<a href="' . esc_url($next_url) . '" class="page-numbers">次へ</a>';
                        }
                        
                        echo '</div>';
                        ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- イベント一覧に戻るボタン -->
            <div class="past-events-button">
                <a href="<?php echo get_post_type_archive_link('events'); ?>" class="btn-past-events">
                    ← イベント一覧に戻る
                </a>
            </div>
            
        <?php else : ?>
            <div class="no-events">
                <p style="text-align: center; padding: 60px 0; color: #999;">
                    <?php echo esc_html($current_year); ?>年の過去のイベント情報はありません。
                </p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>
