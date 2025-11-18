<?php
$root = $args['cat_type'] ?? '';
$variant  = $args['variant'] ?? 'default';

$thumb_size = ($variant === 'featured') ? 'large' : 'medium';
?>

<article class="post_card_wrapper card_<?php echo esc_attr($root); ?> card_<?php echo esc_attr($thumb_size); ?>">
  <a href="<?php the_permalink(); ?>" class="card_thumb">
    <?php if (has_post_thumbnail()) : ?>
      <?php the_post_thumbnail($thumb_size); ?>
    <?php endif; ?>
  </a>
  <div class="card_text_area">
    <a href="<?php the_permalink(); ?>" class="card_title">
      <?php the_title(); ?>
    </a>
    <p class="card_excerpt">
      <?php echo esc_html(wp_trim_words(get_the_content(), 30, '…')); ?>
    </p>
    <div class="card_meta">
      <time datetime="<?php echo esc_attr(get_the_date('c')); ?>">
        <?php echo esc_html(time_diff()); ?>
      </time>
    </div>
  </div>
</article>