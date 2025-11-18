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
  wp_enqueue_script(
    'grid-ajax',
    get_stylesheet_directory_uri() . '/assets/js/grid-ajax.js',
    array(),
    filemtime(get_stylesheet_directory() . '/assets/js/grid-ajax.js'),
    true
  );
}, 20);

add_action('after_setup_theme', function () {
  remove_theme_support('block-templates');
}, 11);

add_action('wp_ajax_resol_load_more_posts', 'resol_load_more_posts');
add_action('wp_ajax_nopriv_resol_load_more_posts', 'resol_load_more_posts');

function resol_load_more_posts()
{
  if (! check_ajax_referer('load_more_posts_nonce', 'nonce', false)) {
    wp_send_json_error(['message' => 'invalid_nonce']);
  }

  $offset    = isset($_POST['offset'])    ? (int) $_POST['offset']    : 0;
  $per_page  = isset($_POST['per_page'])  ? (int) $_POST['per_page']  : 6;
  $term_id   = isset($_POST['term_id'])   ? (int) $_POST['term_id']   : 0;
  $root_key  = isset($_POST['root_key'])  ? sanitize_key($_POST['root_key']) : 'blog';

  $query_args = [
    'post_type'      => 'post',
    'posts_per_page' => $per_page,
    'offset'         => $offset,
    'post_status'    => 'publish',
  ];

  if ($term_id) {
    $query_args['cat'] = $term_id;
  }

  $query = new WP_Query($query_args);

  if (! $query->have_posts()) {
    wp_send_json_success([
      'html'    => '',
      'hasMore' => false,
    ]);
  }

  ob_start();

  while ($query->have_posts()) {
    $query->the_post();
    get_template_part(
      'components/postcard',
      null,
      [
        'cat_type' => $root_key,
        'variant'  => 'default',
      ]
    );
  }

  wp_reset_postdata();

  $html = ob_get_clean();

  wp_send_json_success([
    'html'    => $html,
    'hasMore' => true,
  ]);
}

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
