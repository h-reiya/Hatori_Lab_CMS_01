<?php

// テーマのアップデートチェッカーを実装
function github_theme_updater() {
    // テーマのスラッグを定義（style.cssのTheme Nameと一致させる）
    define('THEME_SLUG', 'Hatori Lab CMS-01');
    
    // GitHubの設定
    define('GITHUB_USERNAME', 'h-reiya');
    define('GITHUB_REPOSITORY', 'Hatori_Lab_CMS-01');
}
add_action('init', 'github_theme_updater');

// アップデートチェックの実装
function check_for_theme_update($transient) {
    if (empty($transient->checked)) {
        return $transient;
    }

    $theme_data = wp_get_theme(THEME_SLUG);
    $current_version = $theme_data->get('Version');

    // GitHubのAPIエンドポイント
    $github_api_url = sprintf('https://api.github.com/repos/%s/%s/releases/latest',
        GITHUB_USERNAME,
        GITHUB_REPOSITORY
    );

    $response = wp_remote_get($github_api_url, array(
        'headers' => array(
            'Accept' => 'application/vnd.github.v3+json'
        )
    ));

    if (!is_wp_error($response) && 200 === wp_remote_retrieve_response_code($response)) {
        $release_data = json_decode(wp_remote_retrieve_body($response));
        
        // バージョン比較（vを除去）
        $github_version = ltrim($release_data->tag_name, 'v');
        
        if (version_compare($current_version, $github_version, '<')) {
            $transient->response[THEME_SLUG] = array(
                'theme' => THEME_SLUG,
                'new_version' => $github_version,
                'url' => $release_data->html_url,
                'package' => $release_data->zipball_url,
                'requires' => '5.0', // WordPressの最小要件バージョン
                'requires_php' => '7.0' // PHPの最小要件バージョン
            );
        }
    }

    return $transient;
}
add_filter('pre_set_site_transient_update_themes', 'check_for_theme_update');

// アップデート詳細情報の追加
function github_theme_update_details($false, $action, $response) {
    if ('theme_information' !== $action || THEME_SLUG !== $response->slug) {
        return $false;
    }

    $github_api_url = sprintf('https://api.github.com/repos/%s/%s/releases/latest',
        GITHUB_USERNAME,
        GITHUB_REPOSITORY
    );

    $response = wp_remote_get($github_api_url);

    if (!is_wp_error($response) && 200 === wp_remote_retrieve_response_code($response)) {
        $release_data = json_decode(wp_remote_retrieve_body($response));
        
        return (object) array(
            'name' => THEME_SLUG,
            'version' => ltrim($release_data->tag_name, 'v'),
            'author' => GITHUB_USERNAME,
            'requires' => '5.0',
            'requires_php' => '7.0',
            'last_updated' => $release_data->published_at,
            'details_url' => $release_data->html_url,
            'download_link' => $release_data->zipball_url
        );
    }

    return $false;
}
add_filter('themes_api', 'github_theme_update_details', 10, 3);