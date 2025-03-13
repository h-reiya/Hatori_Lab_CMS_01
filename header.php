<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=6, user-scalable=yes">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<?php wp_head(); ?>

<!-- libs -->
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/libs/swiper@8/swiper-bundle.min.css?<?php echo date('Ymd-Hi'); ?>" type="text/css" />
<script async src="<?php echo get_template_directory_uri(); ?>/libs/swiper@8/swiper-bundle.min.js?<?php echo date('Ymd-Hi'); ?>"></script>
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/libs/google/material-icons.css?<?php echo date('Ymd-Hi'); ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/libs/google/material-symbols.css?<?php echo date('Ymd-Hi'); ?>" type="text/css" />
<script src="https://unpkg.com/lenis@1.1.14/dist/lenis.min.js?<?php echo date('Ymd-Hi'); ?>"></script>
<!-- libs -->

<!-- src -->
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/style.css?<?php echo date('Ymd-Hi'); ?>" type="text/css" />
<script async src="<?php echo get_template_directory_uri(); ?>/assets/js/index.min.js?<?php echo date('Ymd-Hi'); ?>"></script>
<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/custom-style.css?<?php echo date('Ymd-Hi'); ?>" type="text/css" />
<script async src="<?php echo get_template_directory_uri(); ?>/assets/js/custom-index.min.js?<?php echo date('Ymd-Hi'); ?>"></script>

<!-- src -->
</head>

<body <?php body_class(); ?> ontouchstart="">
	<?php get_template_part( 'components/header/global_header' ); ?>