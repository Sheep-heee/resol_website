<?php get_header(); ?>
<div class="app app--blog two-col">
  <aside class="sidebar">
    <?php get_template_part('parts/blog', 'sidebar');
    ?>
  </aside>
  <section class="app__main">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <article <?php post_class('post-card'); ?>>
          <h2 class="post-card__title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
          </h2>
          <div class="post-card__meta"><?php the_time('Y.m.d'); ?></div>
          <div class="post-card__excerpt"><?php the_excerpt(); ?></div>
        </article>
      <?php endwhile;
      the_posts_pagination();
    else: ?>
      <p>게시물이 없습니다.</p>
    <?php endif; ?>
  </section>
</div>
<?php get_footer(); ?>