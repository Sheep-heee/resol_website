<?php
get_header();

$chain = bd_get_category_chain();
$root = $chain[0] ?? null;
$middle = $chain[1] ?? null;
$current = $chain[2] ?? null;

$section = null;
if ($root) {
  if ($root->slug === 'blog') {
    $section = 'blog';
  } elseif ($root->slug === 'works') {
    $section = 'works';
  } else {
    $section = 'notice';
  }
}

$children = [];
if ($middle) {
  $children = get_terms([
    'taxonomy'   => 'category',
    'parent'     => $middle->term_id,
    'hide_empty' => true,
  ]);
}

?>

<div class="main_ban">
  <?php
  get_template_part('parts/topban', null, [
    'root' => $section
  ]);
  ?>
</div>
<div class="main_exp">
  <?php
  get_template_part('parts/ebar', 'cat', [
    'root' => $root,
    'middle' => $middle->term_id,
    'children' => $children
  ]);
  ?>
</div>
<div class="inner main_con">
  <aside class="sidebar">
    <?php
    get_template_part('components/profile', null, [
      'root' => $section
    ]);
    ?>
    <?php if ($middle) : ?>
      <div class="label_group">
        <h3 class="group_label">
          <span></span>
          <?php echo $section === 'blog' ? '시리즈' : '분야'; ?>
        </h3>
        <ul class="item_wrpper category">
          <?php
          $middle_label = $section === "blog" ? "모든 시리즈" : "모든 분야";
          get_template_part('components/group-label-item', null, [
            'slug' => $middle->slug,
            'label' => $middle_label,
            'count' => $middle->count,
            'url'   => get_category_link($middle->term_id),
          ]);
          if (! is_wp_error($children) && ! empty($children)) {
            foreach ($children as $child) {
              get_template_part('components/group-label-item', null, [
                'slug' => $child->slug,
                'label' => $child->name,
                'count' => $child->count,
                'url'   => get_category_link($child->term_id),
              ]);
            }
          } else {
            echo '<li>' . esc_html('하위 카테고리가 없습니다.') . '</li>';
          }
          ?>
        </ul>
      </div>
    <?php endif; ?>
    <?php if ($section !== "works") : ?>
      <div class="label_group">
        <h3 class="group_label">
          <span></span>
          공지사항
        </h3>
        <ul class="item_wrpper notice">
          <?php
          $notice_args = [
            'post_type'      => 'post',
            'category_name'  => 'notice',
            'posts_per_page' => $section === 'blog' ? 4 : 8,
          ];

          $notice_query = new WP_Query($notice_args);
          ?>

          <?php if ($notice_query->have_posts()) : ?>

            <?php while ($notice_query->have_posts()) : $notice_query->the_post(); ?>
              <?php get_template_part(
                'components/notice-item',
                null,
                [
                  'title'     => get_the_title(),
                  'permalink' => get_permalink(),
                  'date'      => get_the_date('Y. m. d'),
                ]
              ); ?>
            <?php endwhile; ?>
          <?php else : ?>
            <li><?php echo esc_html('공지사항이 없습니다.'); ?></li>
          <?php endif; ?>
          <?php wp_reset_postdata(); ?>
        </ul>
      </div>
    <?php endif; ?>
  </aside>
  <section class="contents_grid">
    <?php
    if ($section === 'blog') {
      get_template_part('parts/grid', 'blog');
    } elseif ($section === 'works') {
      get_template_part('parts/grid', 'works');
    } else {
      get_template_part('parts/grid', 'notice');
    }
    ?>
  </section>
</div>
<?php get_footer(); ?>