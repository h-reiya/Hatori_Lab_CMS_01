<?php if (wp_is_mobile()): ?>
	<div class="hamburger_close_menu">
		<span></span>
		<span></span>
	</div>
<?php endif; ?>

<form action="<?php echo esc_url(home_url('/')); ?>" method="get" id="searchform">
	<input id="widget_searchBtn" type="submit" name="searchBtn" value="search" />
	<input id="widget_keywords" type="text" name="s" placeholder="<?php _e('記事を探す', ''); ?>" />
</form>