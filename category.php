<?php get_header(); ?>
<div class="inner main_ban">
  <?php
  if (is_page("blog")) {
    get_template_part( 'parts/tban', 'blog' );
  } else {
    get_template_part( 'parts/tban', 'works' );
  }
  ?>
</div>
<div class="inner main_exp">
  <?php
  if (is_page("blog")) {
    get_template_part( 'parts/ebar', 'blog' );
  } else {
    get_template_part( 'parts/ebar', 'works' );
  }
  ?>
</div>
<div class="inner main_con">
  <aside class="sidebar">
    <?php
      get_template_part( 'components/profile', null, [
        'page' => is_page("blog") ? "blog" : "works"
      ]);
    ?>
    <div class="label_group">
      <h1 class="group_label">
        <span></span>
        <?php is_page("blog") ? esc_html( "시리즈" ) : esc_html( "분야" ) ?>
      </h1>
      <ul class="item_wrpper">
      </ul>
    </div>
  </aside>
  <section class="app__main">
    <?php
    if (is_page("blog")) {
      get_template_part( 'parts/grid', 'blog' );
    } else {
      get_template_part( 'parts/grid', 'works' );
    }
    ?>
  </section>
</div>
<?php get_footer(); ?>