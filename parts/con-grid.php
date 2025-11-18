<?php
$root_type = $args['root'] ?? '';
$children = $args['children'] ?? [];

$initial_count = 6;
$per_page_more = 6;
$selected_term_id = 0;

$root_key = is_int($root_type) ? 'notice' : (string) $root_type;

if (is_int($root_type)) {
  $selected_term_id = $root_type;
} else {
  $middle_id = isset($args['middle']) ? (int) $args['middle'] : 0;
  $selected_term_id = $middle_id;
  if ($root_type === 'blog') {
    $initial_count = 7;
  } else {
    $initial_count = 12;
  }
}

$query_args = [
  'post_type' => 'post',
  'posts_per_page' => $initial_count,
  'post_status' => 'publish',
];

if ($selected_term_id) {
  $query_args['cat'] = $selected_term_id;
}

$grid_query = new WP_Query($query_args);
?>

<div class="<?php echo esc_attr($root_key); ?>_post_grid" id="js-<?php echo esc_attr($root_key); ?>-grid" data-term-id="<?php echo esc_attr($selected_term_id); ?>">

  <?php if ($grid_query->have_posts()) : ?>
    <?php
    $index = 0;
    while ($grid_query->have_posts()) : $grid_query->the_post();
      $index++;
      $is_featured = ($root_key === 'blog' && $index === 1);
    ?>
      <?php
      get_template_part(
        'components/postcard',
        null,
        [
          'cat_type' => $root_key,
          'variant'  => $is_featured ? 'featured' : 'default',
        ]
      );
      ?>
    <?php endwhile; ?>
    <?php wp_reset_postdata(); ?>
  <?php endif; ?>
</div>
<div class="<?php echo esc_attr($root_key); ?>-more">
  <button type="button"
    id="js-load-more-posts"
    class="btn-load-more"
    data-offset="<?php echo esc_attr($initial_count); ?>"
    data-per-page="<?php echo esc_attr($per_page_more); ?>"
    data-term-id="<?php echo esc_attr($selected_term_id); ?>">
    <span class="arrow_icon">
      <svg class="icon-downArrow">
        <use href="#icon-downArrow"></use>
      </svg>
    </span>
    <?php echo $root_type === 'works' ? '작업물 ' : '글 ' ?>
    <?php echo esc_html($per_page_more); ?>개 더보기
  </button>
</div>