<?php
/**
 * Template Name: Pods Debug Test
 * Podsフィールド診断ページ
 */

get_header();

// 管理者のみアクセス可能
if (!current_user_can('administrator')) {
    echo '<div class="container" style="padding: 100px 20px;"><h1>アクセス権限がありません</h1></div>';
    get_footer();
    exit;
}

?>

<div class="container" style="padding: 100px 20px; max-width: 1200px; margin: 0 auto;">
    <h1>Pods フィールド診断</h1>
    
    <?php
    // 設定ページのID
    $page_id = 120;
    echo '<h2>ページID: ' . $page_id . '</h2>';
    
    // ページが存在するか確認
    $page = get_post($page_id);
    if ($page) {
        echo '<p style="color: green;">✓ ページが見つかりました: 「' . $page->post_title . '」</p>';
    } else {
        echo '<p style="color: red;">✗ ページが見つかりません</p>';
    }
    
    echo '<hr>';
    
    // 方法1: pods() 関数を使う
    echo '<h3>方法1: pods() 関数</h3>';
    $pod = pods('page', $page_id);
    
    if ($pod && $pod->exists()) {
        echo '<p style="color: green;">✓ Podsオブジェクトが取得できました</p>';
        
        // すべてのフィールドを表示
        echo '<h4>利用可能なフィールド:</h4>';
        $fields = $pod->fields();
        echo '<pre style="background: #f5f5f5; padding: 10px; overflow-x: auto;">';
        print_r(array_keys($fields));
        echo '</pre>';
        
        // 各フィールドの値を取得
        echo '<h4>フィールド値の取得テスト:</h4>';
        $test_fields = array(
            'hero_copy_line1',
            'hero_copy_line2', 
            'hero_copy_line3',
            'slider_image_1',
            'slider_image_2',
            'about_text',
            'about_image',
            'message_text',
            'message_image',
            'instagram_url',
            'twitter_url'
        );
        
        echo '<table border="1" cellpadding="5" cellspacing="0" style="width: 100%; border-collapse: collapse;">';
        echo '<tr><th>フィールド名</th><th>値（pod->field）</th><th>値の型</th></tr>';
        
        foreach ($test_fields as $field_name) {
            $value = $pod->field($field_name);
            echo '<tr>';
            echo '<td><strong>' . $field_name . '</strong></td>';
            echo '<td>';
            if (is_array($value)) {
                echo '<pre style="margin: 0; font-size: 11px;">' . print_r($value, true) . '</pre>';
            } elseif (is_string($value) && strlen($value) > 100) {
                echo substr(htmlspecialchars($value), 0, 100) . '...';
            } elseif ($value) {
                echo htmlspecialchars($value);
            } else {
                echo '<span style="color: #999;">(空)</span>';
            }
            echo '</td>';
            echo '<td>' . gettype($value) . '</td>';
            echo '</tr>';
        }
        echo '</table>';
        
    } else {
        echo '<p style="color: red;">✗ Podsオブジェクトが取得できません</p>';
    }
    
    echo '<hr>';
    
    // 方法2: pods_field() 関数を使う
    echo '<h3>方法2: pods_field() 関数</h3>';
    echo '<table border="1" cellpadding="5" cellspacing="0" style="width: 100%; border-collapse: collapse;">';
    echo '<tr><th>フィールド名</th><th>値（pods_field）</th><th>値の型</th></tr>';
    
    $test_fields = array(
        'hero_copy_line1',
        'hero_copy_line2',
        'hero_copy_line3',
        'slider_image_1',
        'about_text',
        'message_text'
    );
    
    foreach ($test_fields as $field_name) {
        $value = pods_field('page', $page_id, $field_name, true);
        echo '<tr>';
        echo '<td><strong>' . $field_name . '</strong></td>';
        echo '<td>';
        if (is_array($value)) {
            echo '<pre style="margin: 0; font-size: 11px;">' . print_r($value, true) . '</pre>';
        } elseif (is_string($value) && strlen($value) > 100) {
            echo substr(htmlspecialchars($value), 0, 100) . '...';
        } elseif ($value) {
            echo htmlspecialchars($value);
        } else {
            echo '<span style="color: #999;">(空)</span>';
        }
        echo '</td>';
        echo '<td>' . gettype($value) . '</td>';
        echo '</tr>';
    }
    echo '</table>';
    
    echo '<hr>';
    
    // 方法3: get_post_meta() を使う
    echo '<h3>方法3: get_post_meta() 関数（WordPress標準）</h3>';
    echo '<table border="1" cellpadding="5" cellspacing="0" style="width: 100%; border-collapse: collapse;">';
    echo '<tr><th>メタキー</th><th>値</th><th>値の型</th></tr>';
    
    $all_meta = get_post_meta($page_id);
    
    foreach ($all_meta as $key => $values) {
        // Podsフィールドのみ表示（_pods_で始まるものは除外）
        if (strpos($key, '_') !== 0 || strpos($key, '_pods_') === 0) {
            continue;
        }
        
        echo '<tr>';
        echo '<td><strong>' . $key . '</strong></td>';
        echo '<td>';
        $value = $values[0] ?? '';
        if (is_serialized($value)) {
            $value = unserialize($value);
        }
        if (is_array($value)) {
            echo '<pre style="margin: 0; font-size: 11px;">' . print_r($value, true) . '</pre>';
        } elseif (is_string($value) && strlen($value) > 100) {
            echo substr(htmlspecialchars($value), 0, 100) . '...';
        } elseif ($value) {
            echo htmlspecialchars($value);
        } else {
            echo '<span style="color: #999;">(空)</span>';
        }
        echo '</td>';
        echo '<td>' . gettype($value) . '</td>';
        echo '</tr>';
    }
    echo '</table>';
    
    echo '<hr>';
    
    // Pods設定の確認
    echo '<h3>Pods設定の確認</h3>';
    
    // すべてのPodsを取得
    $all_pods = pods_api()->load_pods();
    $page_pod = null;
    
    foreach ($all_pods as $pod_data) {
        if ($pod_data['type'] === 'post_type' && $pod_data['name'] === 'page') {
            $page_pod = $pod_data;
            break;
        }
    }
    
    if ($page_pod) {
        echo '<p style="color: green;">✓ 固定ページ用のPodが設定されています</p>';
        echo '<h4>設定されているフィールド:</h4>';
        echo '<ul>';
        foreach ($page_pod['fields'] as $field_name => $field_data) {
            echo '<li><strong>' . $field_name . '</strong> (' . $field_data['type'] . ')</li>';
        }
        echo '</ul>';
    } else {
        echo '<p style="color: red;">✗ 固定ページ用のPodが見つかりません</p>';
    }
    
    ?>
    
    <hr>
    <h3>推奨される解決策</h3>
    <ol>
        <li>上記で値が取得できているフィールドがあれば、その取得方法を使用してください</li>
        <li>フィールド名が正しいか確認してください（大文字小文字も含めて）</li>
        <li>Podsの設定で、固定ページ（page）を拡張していることを確認してください</li>
        <li>フィールドの値を保存し直してみてください</li>
    </ol>
</div>

<?php get_footer(); ?>