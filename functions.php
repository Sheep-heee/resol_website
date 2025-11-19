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
  wp_localize_script(
    'grid-ajax',
    'RESOL_LOAD_MORE',
    [
      'ajax_url' => admin_url('admin-ajax.php'),
      'nonce'    => wp_create_nonce('load_more_posts_nonce'),
    ]
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
    'orderby'        => 'date',
    'order'          => 'DESC',
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

  $total_posts = (int) $query->found_posts;
  $has_more    = ($offset + $per_page < $total_posts);

  wp_send_json_success([
    'html'    => $html,
    'hasMore' => $has_more,
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

// 🔥 테스트용 더미 포스트 + 랜덤 썸네일 생성 (한 번만 실행)
// add_action('admin_init', 'resol_generate_dummy_posts_with_random_images');

// function resol_generate_dummy_posts_with_random_images()
// {
//   if (!current_user_can('manage_options')) return;

//   // 이미 생성했다면 재실행 방지
//   if (get_option('resol_dummy_posts_created_v3')) return;

//   // ✅ 미디어 라이브러리에서 이미지 attachment 전부 가져오기
//   $images = get_posts([
//     'post_type'      => 'attachment',
//     'post_mime_type' => 'image',
//     'post_status'    => 'inherit',
//     'posts_per_page' => -1,
//     'fields'         => 'ids',
//   ]);

//   // 이미지가 하나도 없으면 썸네일 없이 진행
//   $has_images = !empty($images) && !is_wp_error($images);

//   // 🔧 카테고리별 생성 수량 (slug => 개수)
//   $categories_to_generate = [
//     'design-walk' => 10,
//     'design-communication'           => 10,
//     'design-tip'         => 10,
//     'event-report'        => 10,
//     'media' => 10,
//     'issue'      => 10,
//     'landing'    => 30,
//     'flyer'    => 30,
//     'branding'    => 30,
//     'book'    => 30,
//     'document'    => 30,
//     'product-detail'    => 30,
//     'uiux'    => 30,
//     'poster'    => 30,
//     'web'    => 30,
//     'notice'    => 10,
//     // 필요하면 추가
//   ];

//   foreach ($categories_to_generate as $slug => $count) {
//     $term = get_term_by('slug', $slug, 'category');
//     if (!$term || is_wp_error($term)) continue;

//     for ($i = 1; $i <= $count; $i++) {
//       $title = sprintf('[TEST] %s - 더미 글 %d', $slug, $i);
//       $content = "이 글은 그리드 / AJAX / 필터 / 썸네일 테스트용 자동 생성 포스트입니다.\n\n카테고리: {$slug}\n번호: {$i}";

//       $post_id = wp_insert_post([
//         'post_title'   => $title,
//         'post_content' => $content,
//         'post_status'  => 'publish',
//         'post_type'    => 'post',
//         'post_category' => [(int)$term->term_id],
//       ]);

//       if ($post_id && !is_wp_error($post_id)) {
//         // ✅ 이 글은 더미임을 표시 (나중에 일괄 삭제용)
//         add_post_meta($post_id, '_resol_dummy', 1, true);

//         // ✅ 랜덤 썸네일 지정 (이미지가 있을 때만)
//         if ($has_images) {
//           $thumb_id = $images[array_rand($images)];
//           set_post_thumbnail($post_id, $thumb_id);
//         }
//       }
//     }
//   }

//   // 다시 실행되지 않도록 플래그 저장
//   update_option('resol_dummy_posts_created_v3', 1);
// }

add_action('admin_init', 'resol_delete_all_dummy_posts');

function resol_delete_all_dummy_posts()
{
  if (!current_user_can('manage_options')) return;

  // URL에 ?resol_delete_dummy=1 있을 때만 실행되게 안전장치
  if (!isset($_GET['resol_delete_dummy']) || $_GET['resol_delete_dummy'] !== '1') {
    return;
  }

  // _resol_dummy 메타가 1인 포스트 전부 가져오기
  $dummy_posts = get_posts([
    'post_type'      => 'post',
    'posts_per_page' => -1,
    'meta_key'       => '_resol_dummy',
    'meta_value'     => 1,
    'fields'         => 'ids',
  ]);

  if (!empty($dummy_posts)) {
    foreach ($dummy_posts as $post_id) {
      // 휴지통 거치지 않고 완전 삭제 (원하면 false로 바꾸면 휴지통으로)
      wp_delete_post($post_id, true);
    }
  }

  // 플래그도 같이 초기화 (원하면 다시 생성 가능)
  delete_option('resol_dummy_posts_created_v3');
}
