<?php

get_header();

if (have_posts()) {
	while (have_posts()) {
		the_post();
		get_template_part('templates/content', get_post_format());
	}
} else {
	
}

$menus = get_nav_menu_locations();
if (isset($menus['front-page'])) {
	$items = wp_get_nav_menu_items($menus['front-page']);
	if (!empty($items)) {
		echo '<ul class="featured">';
		foreach ($items as $item) {
			$class = sanitize_title($item->title);
			echo '<li class="'.$class.'"><a href="'.$item->url.'">';
			echo '<div class="badge"><span></span></div>';
			echo '<h3 class="title">'.$item->title.'</h3>';
			echo '<p>'.$item->post_excerpt.'</p>';
			echo '</a></li>';
		}
		echo '</ul>';
	}
// wp_nav_menu(array('theme_location' => 'front-page', 'container_class' => 'feature', 'fallback_cb' => false));
}

get_footer();

