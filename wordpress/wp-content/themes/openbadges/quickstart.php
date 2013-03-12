<?php

/*
 * Template Name: QuickStart
 */

global $post;

function question_formatting ($matches) {
	$buttons = array();
	$body = $matches[3];
	preg_match_all('#(<(\w+) [^>]*class="([^"]*)answer([^"]*)"[^>]*>)(.*?)(</\2>)#', $body, $options, PREG_SET_ORDER);

	foreach ($options as $option) {
		$classes = array('button', preg_replace('#[^\w]#', '_', trim(strToLower($option[5]))));
		if (strpos($option[3].' '.$option[4], 'correct') > -1) {
			$classes[] = 'correct';
		}
		$buttons[] = '<a class="'.implode(' ', $classes).'">'.$option[5].'</a>';
	}

	return implode('', array(
		$matches[1],
		$body,
		'<p class="options">' . implode(' ', $buttons) . '</p>',
		$matches[4]
	));
}

get_header();

if (have_posts()) {
	while (have_posts()) {
		the_post();

		echo '<div id="badges-101">';

		if (IS_AJAX) {
			$question_search_re = 'class="[^"]*question[^"]*"';
			$question_re = '#(<(\w+) [^>]*'.$question_search_re.'[^>]*>)(.*?)(</\2>)#s';

			echo '<div id="slides" role="main">';
			echo '<div class="slides_container">';
			$pages = explode('<!--nextpage-->', $post->post_content);
			foreach ($pages as $index => $page) {
				$classes = array('slide');
				if (preg_match('#'.$question_search_re.'#', $page)) {
					$classes[] = 'quiz';
					$page = preg_replace_callback($question_re, 'question_formatting', $page);
				}
				echo '<div id="quickstart-page-'.($index+1).'" class="'.implode(' ', $classes).'">';
				echo str_replace('class="button"', 'class="next button"', $page);
				echo '</div>';
			}
			echo '</div>';
			echo '</div>';
		} else {
			the_content();
		}

		echo '</div>';
	}
}

get_footer();
