<?php

if (!defined('ABSPATH')) {
	exit;
}

add_filter('xmlrpc_enabled', '__return_false');

add_filter('xmlrpc_methods', function($methods) {
    unset($methods['pingback.ping']);
    unset($methods['pingback.extensions.getPingbacks']);
    return $methods;
});

remove_action('wp_head', 'wp_generator');

add_filter('the_generator', '__return_empty_string');

function add_security_headers() {
	header('X-Content-Type-Options: nosniff');
	header('X-Frame-Options: SAMEORIGIN');
	header('X-XSS-Protection: 1; mode=block');
	header('Referrer-Policy: strict-origin-when-cross-origin');
	if (is_ssl()) {
			header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
	}
}
add_action('send_headers', 'add_security_headers');

function safe_query_example($user_input) {
	global $wpdb;
	$result = $wpdb->get_results(
			$wpdb->prepare(
					"SELECT * FROM {$wpdb->posts} WHERE post_title LIKE %s",
					'%' . $wpdb->esc_like($user_input) . '%'
			)
	);
	return $result;
}

/**
 * add custom menu(include)
 */
require get_template_directory() . '/inc/update-checker.php';

/**
 * initial settings
 */

// hide wordpress version
remove_action('wp_head', 'wp_generator');

// Enable support for post thumbnails on posts and pages
add_theme_support('post-thumbnails');

// title tag
function theme_slug_setup()
{
	add_theme_support('title-tag');
}
add_action('after_setup_theme', 'theme_slug_setup');

// setting no image
function get_default_noimage()
{
	return get_template_directory_uri() . '/assets/img/no-image.png';
}

function the_post_thumbnail_or_noimage($size = 'full', $alt = 'No Image')
{
	if (has_post_thumbnail()) {
		echo the_post_thumbnail($size);
	} else {
		echo '<img src="' . esc_url(get_default_noimage()) . '" alt="' . esc_attr($alt) . '">';
	}
}

function enqueue_media_uploader()
{
	if (isset($_GET['taxonomy']) && $_GET['taxonomy'] === 'category') {
		wp_enqueue_media();
	}
	if (isset($_GET['taxonomy']) && $_GET['taxonomy'] === 'post_tag') {
		wp_enqueue_media();
	}
	if (isset($_GET['page']) && $_GET['page'] === 'top-page-settings') {
		wp_enqueue_media();
	}
	if (isset($_GET['page']) && $_GET['page'] === 'site-settings') {
		wp_enqueue_media();
	}
}
add_action('admin_enqueue_scripts', 'enqueue_media_uploader');

function ensure_jquery_loaded()
{
	if (isset($_GET['taxonomy']) && $_GET['taxonomy'] === 'category') {
		wp_enqueue_script('jquery');
	}
	if (isset($_GET['taxonomy']) && $_GET['taxonomy'] === 'post_tag') {
		wp_enqueue_script('jquery');
	}
	if (isset($_GET['page']) && $_GET['page'] === 'top-page-settings') {
		wp_enqueue_script('jquery');
	}
	if (isset($_GET['page']) && $_GET['page'] === 'site-settings') {
		wp_enqueue_script('jquery');
	}
}
add_action('admin_enqueue_scripts', 'ensure_jquery_loaded');

/**
 * include js and css for admin page
 */
function load_custom_admin_scripts($hook)
{
	if ('toplevel_page_site-settings' === $hook || 'サイト設定_page_top-page-settings' === $hook) {
		// カスタムスクリプト
		wp_enqueue_script('custom-admin', get_template_directory_uri() . '/js/custom-admin.js', array('jquery'), null, true);
	}
}
add_action('admin_enqueue_scripts', 'load_custom_admin_scripts');

function my_admin_enqueue_scripts()
{
	wp_enqueue_style('my-admin-style', get_template_directory_uri() . '/assets/admin/style.css');

	wp_enqueue_script('my-admin-script', get_template_directory_uri() . '/assets/admin/index.js', array('jquery'), null, true);
}
add_action('admin_enqueue_scripts', 'my_admin_enqueue_scripts');

/**
 * define custom menu
 */
function twpp_setup_theme()
{
	register_nav_menu('global-navigation', 'Header Navigation');
	register_nav_menu('global-navigation2', 'Header Navigation2');
	register_nav_menu('footer-navigation', 'Footer Navigation');
}
add_action('after_setup_theme', 'twpp_setup_theme');

/**
 * define sidebar
 */
function theme_slug_widgets_init()
{
	register_sidebar(array(
		'name' => 'サイドバー',
		'id' => 'sidebar',
	));
}
add_action('widgets_init', 'theme_slug_widgets_init');

/**
 * exclude bot count
 */
function is_bot()
{
	$ua = $_SERVER['HTTP_USER_AGENT'];

	$bot = array(
		'Googlebot',
		'Yahoo! Slurp',
		'Mediapartners-Google',
		'msnbot',
		'bingbot',
		'MJ12bot',
		'Ezooms',
		'pirst; MSIE 8.0;',
		'Google Web Preview',
		'ia_archiver',
		'Sogou web spider',
		'Googlebot-Mobile',
		'AhrefsBot',
		'YandexBot',
		'Purebot',
		'Baiduspider',
		'UnwindFetchor',
		'TweetmemeBot',
		'MetaURI',
		'PaperLiBot',
		'Showyoubot',
		'JS-Kit',
		'PostRank',
		'Crowsnest',
		'PycURL',
		'bitlybot',
		'Hatena',
		'facebookexternalhit',
		'NINJA bot',
		'YahooCacheSystem',
		'NHN Corp.',
		'Steeler',
		'DoCoMo',
	);

	foreach ($bot as $bot) {
		if (stripos($ua, $bot) !== false) {
			return true;
		}
	}

	return false;
}

/**
 * post order by popular
 */
function get_post_views($post_id)
{
	$count_key = 'post_views_count';
	$count = get_post_meta($post_id, $count_key, true);

	if ($count == "") {
		delete_post_meta($post_id, $count_key);
		add_post_meta($post_id, $count_key, '0');
		return "0 View";
	}

	return $count . ' Views';
}

function set_post_views($post_id)
{
	$count_key = 'post_views_count';
	$count = get_post_meta($post_id, $count_key, true);

	if ($count == "") {
		$count = 0;
		delete_post_meta($post_id, $count_key);
		add_post_meta($post_id, $count_key, '0');
	} else {
		$count++;
		update_post_meta($post_id, $count_key, $count);
	}
}
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);

/**
 * add custom menu(include)
 */
require get_template_directory() . '/inc/custom-settings.php';

/**
 * add custom setting for category
 */
require get_template_directory() . '/inc/category-custom-settings.php';

/**
 * add custom setting for tag
 */
require get_template_directory() . '/inc/tag-custom-setting.php';

/**
 * breadcrumb
 */
require get_template_directory() . '/inc/breadcrumb.php';




/**
 * define custom post type
 */
// add_action( 'init', 'create_post_type' );
// function create_post_type() {
//   register_post_type(
//     'news',
//     array(
//       'label' => 'ニュース',
//       'public' => true,
//       'has_archive' => true,
//       'show_in_rest' => true,
//       'menu_position' => 5,
//       'menu_icon' => 'dashicons-media-text',
//       'supports' => array(
//         'title',
//         'editor',
//         'thumbnail',
//         'excerpt',
//         'custom-fields',
//         'revisions'
//       ),
//     )
//   );
//   register_post_type(
//     'interview',
//     array(
//       'label' => 'インタビュー',
//       'public' => true,
//       'has_archive' => true,
//       'show_in_rest' => true,
//       'menu_position' => 5,
//       'menu_icon' => 'dashicons-businessman',
//       'supports' => array(
//         'title',
//         'editor',
//         'thumbnail',
//         'excerpt',
//         'custom-fields',
//         'revisions'
//       ),
//     )
//   );

//   register_taxonomy(
//     'news-category',
//     'news',
//     array(
//       'label' => 'カテゴリー',
//       'hierarchical' => true,
//       'public' => true,
//       'show_in_rest' => true,
//     )
//   );
//   register_taxonomy(
//     'interview-category',
//     'interview',
//     array(
//       'label' => 'カテゴリー',
//       'hierarchical' => true,
//       'public' => true,
//       'show_in_rest' => true,
//     )
//   );

//   register_taxonomy(
//     'news-tag',
//     'news',
//     array(
//       'label' => 'タグ',
//       'hierarchical' => false,
//       'public' => true,
//       'show_in_rest' => true,
//       'update_count_callback' => '_update_post_term_count',
//     )
//   );
//   register_taxonomy(
//     'interview-tag',
//     'interview',
//     array(
//       'label' => 'タグ',
//       'hierarchical' => false,
//       'public' => true,
//       'show_in_rest' => true,
//       'update_count_callback' => '_update_post_term_count',
//     )
//   );
// }

/**
 * add views column to post list
 */
// 投稿一覧に閲覧数カラムを追加
function add_posts_views_column($columns)
{
	$new_columns = array();
	foreach ($columns as $key => $value) {
		$new_columns[$key] = $value;
		if ($key === 'date') {  // 日付の後ろに閲覧数カラムを追加
			$new_columns['post_views'] = '閲覧数';
		}
	}
	return $new_columns;
}
add_filter('manage_posts_columns', 'add_posts_views_column');

// 閲覧数カラムの内容を表示
function display_posts_views_column($column_name, $post_id)
{
	if ($column_name === 'post_views') {
		$views = get_post_meta($post_id, 'post_views_count', true);
		echo !empty($views) ? number_format($views) : '0';
	}
}
add_action('manage_posts_custom_column', 'display_posts_views_column', 10, 2);

// カラムをソート可能にする
function make_views_column_sortable($columns)
{
	$columns['post_views'] = 'post_views_count';
	return $columns;
}
add_filter('manage_edit-post_sortable_columns', 'make_views_column_sortable');

// ソート時のクエリを変更
function views_column_orderby($query)
{
	if (!is_admin()) {
		return;
	}

	$orderby = $query->get('orderby');
	if ('post_views_count' === $orderby) {
		$query->set('meta_key', 'post_views_count');
		$query->set('orderby', 'meta_value_num');
	}
}
add_action('pre_get_posts', 'views_column_orderby');
