<?php
$root = $args['root'] ?? null;
$middle_id = isset($args['middle']) ? (int) $args['middle'] : 0;
$children = $args['children'] ?? '';

$middle_terms = [];

if ($root instanceof WP_Term) {
  $middle_terms = get_terms([
    'taxonomy'   => 'category',
    'parent'     => $root->term_id,
    'hide_empty' => false,
  ]);

  if (is_wp_error($middle_terms)) {
    $middle_terms = [];
  }
}

?>

<nav class="inner exp_bar">
  <div class="tab_bar">
    <?php if (! empty($middle_terms)) : ?>
      <?php foreach ($middle_terms as $m_item) : ?>
        <a href="<?php echo esc_url(get_category_link($m_item->term_id)); ?>" class="tab_btn<?php echo $m_item->term_id === $middle_id ? " active" : "" ?>">
          <?php echo esc_html($m_item->name) ?>
        </a>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
  <div class="se_container">
    <?php
    get_template_part('components/search-bar', null, [
      'location' => 'ebar'
    ]);
    ?>
  </div>
</nav>