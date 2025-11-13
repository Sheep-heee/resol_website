<?php get_header(); ?>
<div class="app app--page">
  <div class="app__inner">
    <aside class="sidebar">
      <?php
      // 페이지 공통 사이드바 (있으면)
      // get_template_part('parts/sidebar', 'page');
      ?>
    </aside>
    <section class="app__main">
      <?php
      $slug = get_post_field('post_name');
      $loaded = locate_template("page/{$slug}.php", true, false);
      ?>
    </section>
  </div>
</div>
<?php get_footer(); ?>