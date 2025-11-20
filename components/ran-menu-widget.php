<?php
$path = get_stylesheet_directory() . '/assets/data/random-menus.json';
$menu_list = json_decode(file_get_contents($path), true);
$menu = $menu_list[array_rand($menu_list)];
?>
<div class="widget-main-content">
  <h4 class="widget-title">뭐 먹지?</h4>
  <div class="widget-content">
    <p class="menu-text"><?= esc_html($menu) ?></p>
    <button class="refresh-btn">
      <svg class="icon-rotate">
        <use href="#icon-rotate"></use>
      </svg>
    </button>
  </div>
</div>