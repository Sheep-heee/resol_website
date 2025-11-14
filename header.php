<!doctype html>
<html <?php language_attributes(); ?>>

<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
  <header>
    <div class="inner head_con">
      <div class="logo">
        <img src="<?php echo esc_url( get_theme_file_uri( 'assets/img/resol_logo.svg' ) ); ?>" alt="resolvisual logo">
      </div>
      <nav class="t_menu">
        <ul class="me_list">
          <li class="nav_btn"><a href="/blog">블로그</a></li>
          <li class="nav_btn"><a href="/works">포트폴리오</a></li>
          <li class="nav_btn"><a href="/contact">협업문의</a></li>
        </ul>
        <button type="button"><div class="flag"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/img/flag/south_korea.svg' ) ); ?>" alt=""></div>KOR</button>
      </nav>
    </div>
  </header>
  <main>