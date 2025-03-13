<?php

// functions.php での実装例
function check_for_theme_update($transient) {
    if (empty($transient->checked)) {
        return $transient;
    }

    $theme_data = wp_get_theme();
    $theme_slug = $theme_data->get_template();
    $current_version = $theme_data->get('Version');

    // GitHubのAPIを使用してリリース情報を取得
    $github_api_url = 'https://api.github.com/repos/h-reiya/Hatori_Lab_CMS_01/releases/latest';
    
    $response = wp_remote_get($github_api_url);
    
    if (!is_wp_error($response)) {
        $body = wp_remote_retrieve_body($response);
        $release_data = json_decode($body);
        
        if ($release_data && version_compare($current_version, ltrim($release_data->tag_name, 'v'), '<')) {
            $transient->response[$theme_slug] = array(
                'theme' => $theme_slug,
                'new_version' => ltrim($release_data->tag_name, 'v'),
                'url' => $release_data->html_url,
                'package' => $release_data->zipball_url,
            );
        }
    }
    
    return $transient;
}
add_filter('pre_set_site_transient_update_themes', 'check_for_theme_update');