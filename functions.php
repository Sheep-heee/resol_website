<?php
add_action('wp_enqueue_scripts', function () {
  wp_enqueue_style(
    'tt4c-style',
    get_stylesheet_directory_uri() . '/assets/build/style.css',
    [],
    filemtime(get_stylesheet_directory() . '/assets/build/style.css')
  );
  wp_enqueue_script(
    'search-clear',
    get_stylesheet_directory_uri() . '/assets/js/search-clear.js',
    array(),
    filemtime(get_stylesheet_directory() . '/assets/js/search-clear.js'),
    true
  );
}, 20);

add_action('after_setup_theme', function () {
  remove_theme_support('block-templates');
}, 11);


function bd_get_category_chain($term = null)
{
  if ($term === null) {

    if (is_category()) {
      $term = get_queried_object();
    } elseif (is_single()) {
      $cats = get_the_category();
      if (empty($cats)) {
        return [];
      }
      $term = $cats[0];
    } else {
      return [];
    }
  }

  if (is_numeric($term)) {
    $term = get_term($term, 'category');
  }

  if (! $term || is_wp_error($term)) {
    return [];
  }

  $chain = [];
  $current = $term;

  while ($current && ! is_wp_error($current)) {
    $chain[] = $current;

    if (! $current->parent) {
      break;
    }

    $current = get_term($current->parent, 'category');
  }

  $chain = array_reverse($chain);

  return $chain;
}


function time_diff($post_id = null)
{
  $post_id = $post_id ?: get_the_ID();

  $post_time = get_post_time('U', true, $post_id);
  $now = current_time('timestamp');

  $diff = $now - $post_time;

  if ($diff < 60) {
    return '방금 전';
  }

  if ($diff < HOUR_IN_SECONDS) {
    $mins = floor($diff / MINUTE_IN_SECONDS);
    return $mins . '분 전';
  }

  if ($diff < DAY_IN_SECONDS) {
    $hours = floor($diff / HOUR_IN_SECONDS);
    return $hours . '시간 전';
  }

  if ($diff < WEEK_IN_SECONDS) {
    $days = floor($diff / DAY_IN_SECONDS);
    return $days . '일 전';
  }

  if ($diff < 30 * DAY_IN_SECONDS) {
    return get_the_date('Y.m.d', $post_id);
  }
}
