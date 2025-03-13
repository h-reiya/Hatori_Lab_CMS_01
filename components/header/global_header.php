<header id="header">
	<div class="header-t">
		<?php if (wp_is_mobile()): ?>
			<div class="hamburger-menu">
				<span></span>
				<span></span>
				<span></span>
			</div>
		<?php endif; ?>
		<div id="search-box">
			<?php include(STYLESHEETPATH . '/searchform.php'); ?>
		</div>
		<?php
		$my_general_section_site_color = get_option('my_general_section_site_color');
		$site_logo = get_option('my_general_section_setting_site_logo');
		$site_logo_image = get_option('my_general_section_setting_site_logo_image');
		?>
		<div id="site-logo" class="site-logo" style="background: <?php echo $my_general_section_site_color; ?>;">
			<h1>
				<a class="site-logo-link <?php if ($site_logo): ?>img-wrap<?php endif; ?>" href="<?php echo home_url('/'); ?>">
					<?php if ($site_logo): ?>
						<img class="site-logo-image" src="<?php echo $site_logo_image; ?>" alt="">
					<?php else: ?>
						<span class="logo-main-text"><?php bloginfo('name'); ?></span>
						<span class="logo-sub-text"><?php bloginfo('description'); ?></span>
					<?php endif; ?>
				</a>
			</h1>
		</div>
	</div>
	<div class="header-b">
		<?php wp_nav_menu(
			array(
				'theme_location' => 'global-navigation',
				'menu_id' => 'global-navigation',
				'container' => false,
				'items_wrap' => '<ul id="%1$s" class="g-menu %2$s">%3$s</ul>'
			)
		); ?>
	</div>
	<div class="header-mb">
		<div class="prev-button"></div>
		<div class="menu-list">
			<?php wp_nav_menu(
				array(
					'theme_location' => 'global-navigation2',
					'menu_id' => 'global-navigation2',
					'container' => false,
					'items_wrap' => '<ul id="%1$s" class="g-menu %2$s">%3$s</ul>'
				)
			); ?>
		</div>
		<div class="next-button"></div>
	</div>
</header>