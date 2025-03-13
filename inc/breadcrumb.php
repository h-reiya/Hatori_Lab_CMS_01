<?php
function custom_breadcrumb() {
  // ホームへのリンクを作成
  $home_url = home_url('/');
  echo '<nav class="breadcrumb"><a href="' . $home_url . '">ホーム</a> » ';
  
  if (is_category() || is_single()) {
      // カテゴリページや投稿ページの場合
      the_category(' » ');
      if (is_single()) {
          echo ' » ';
          the_title();
      }
  } elseif (is_page()) {
      // 固定ページの場合
      if ($post->post_parent) {
          // 親ページがある場合はそのリンクも表示
          $parent_id = $post->post_parent;
          $breadcrumbs = array();
          while ($parent_id) {
              $page = get_page($parent_id);
              $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
              $parent_id = $page->post_parent;
          }
          $breadcrumbs = array_reverse($breadcrumbs);
          foreach ($breadcrumbs as $crumb) echo $crumb . ' » ';
      }
      echo the_title();
  } elseif (is_home()) {
      // ブログトップページの場合
      echo 'ブログ';
  } elseif (is_archive()) {
      // アーカイブページの場合
      the_archive_title();
  } elseif (is_search()) {
      // 検索結果ページの場合
      echo '検索結果: "' . get_search_query() . '"';
  } elseif (is_404()) {
      // 404エラーページの場合
      echo 'ページが見つかりません';
  }

  echo '</nav>';
}
