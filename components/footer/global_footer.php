<footer id="footer">
	<div class="footer-inner">
		<div class="footer-t">
			<?php
			$footer_left_title = get_option('footer_left_title');
			?>
			<div class="footer-menu">
				<div class="footer-menu-inner">
					<?php wp_nav_menu(
						array(
							'theme_location' => 'footer-navigation',
							'menu_id' => 'footer-navigation',
							'container' => false,
							'items_wrap' => '<ul id="%1$s" class="g-nav %2$s">%3$s</ul>'
						)
					); ?>
				</div>
			</div>

			<div class="site-logo">
				<h1>
					<?php
					$site_logo = get_option('my_general_section_setting_site_logo');
					$site_logo_image = get_option('my_general_section_setting_site_logo_image2');
					?>
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

		<div class="footer-b">
			<div class="site-info">
				<p>
					Copyright (C) 2024 Growth Insight. All Rights Reserved.
				</p>
			</div>
		</div>
	</div>
</footer>