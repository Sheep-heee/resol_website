<?php
add_action('wp_enqueue_scripts', function () {
  wp_enqueue_style(
    'tt4c-style',
    get_stylesheet_directory_uri() . '/assets/build/style.css',
    [],
    filemtime(get_stylesheet_directory() . '/assets/build/style.css')
  );
}, 20);

add_action('after_setup_theme', function () {
  remove_theme_support('block-templates');
}, 11);
