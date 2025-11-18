<?php
$parent = $args['parent'] ?? '';
$label = $args['label'] ?? '';
$count = isset($args['count']) ? (int) $args['count'] : 0;
$link = $args['url'] ?? '';

$icon_name = '';

switch ($label) {
  case '디자인 소통법':
    $icon_name = 'snowFlake';
    break;
  case '디자인 팁':
    $icon_name = 'pointingUp';
    break;
  case '행사 참가 후기':
    $icon_name = 'penNib';
    break;
  case '디자인 산책':
    $icon_name = 'balloon';
    break;
  case '복잡한 사회 이야기':
    $icon_name = 'sun';
    break;
  case '미디어 리뷰':
    $icon_name = 'knight';
    break;
  default:
    $icon_name = '';
    break;
}
?>

<li class="cat_nav_item">
  <a href="<?php echo esc_url($link); ?>" class="cat_nav_btn">
    <?php if (!empty($icon_name)) : ?>
      <svg class="icon-<?php echo esc_attr($icon_name); ?>">
        <use href="#icon-<?php echo esc_attr($icon_name); ?>"></use>
      </svg>
      <?php echo esc_html($label); ?>
    <?php endif; ?>
  </a>
</li>