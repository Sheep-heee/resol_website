<?php
$root = $args['root'] ?? '';
$title = "";
$imgUrl = "";
$desc_map = [];

switch ($root) {
  case "blog":
    $title = "블로그";
    $imgUrl = get_theme_file_uri('assets/img/illust/swinging.svg');
    $desc_map = [
      "디자인에 재능 없는 것 같고, 먹고 살 수 있을지 걱정돼도 ",
      "어찌저찌 살다보니 계속 디자인하고 있는 디자이너분들께, ",
      "화려한 커리어는 없지만 매일을 충실히 보낸 우리에게, ",
      "“오늘도 수고하셨습니다.”",
    ];
    break;
  case "works":
    $title = "포트폴리오";
    $imgUrl = get_theme_file_uri('assets/img/illust/zombieing.svg');
    $desc_map = [
      [
        "공개 가능한 외주 작업물과 ",
        "개인 사이드 프로젝트 모음입니다. ",
      ],
      "좋아하는 게 많아서, 분야를 막론하고 다루고 있습니다.",
    ];
    break;
  case "notice":
    $title = "공지사항";
    $imgUrl = get_theme_file_uri('assets/img/illust/messy.svg');
    $desc_map = [
      "초등학교 시절엔 선생님이 내일 챙겨야 할 것을 ",
      "하교 전에 항상 알림장에 적어주시곤 했는데 ",
      "여러분도 그런 경험이 있으신가요. ",
      "저는 어른이라 이제 혼자 해야합니다.",
    ];
    break;
  default:
    $title = "404 ERROR";
    $imgUrl = get_theme_file_uri('assets/img/illust/sitting.svg');
    $desc_map = [
      "올바르지 않은 경로입니다.",
    ];
    break;
}

?>

<div class="inner topban">
  <div class="title_area">
    <h2 class="t_text"><?php echo esc_html($title) ?></h2>
    <div class="t_desc">
      <?php foreach ($desc_map as $row) : ?>
        <?php if (is_array($row)) : ?>
          <div class="f_line">
            <?php foreach ($row as $line) : ?>
              <span><?php echo esc_html($line); ?></span>
            <?php endforeach; ?>
          </div>
        <?php else : ?>
          <span><?php echo esc_html($row); ?></span>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
  </div>
  <div class="illust_area <?php echo $root ? $root : "error" ?>">
    <img src="<?php echo esc_url($imgUrl) ?>" alt="<?php echo esc_attr($title); ?> 커버 일러스트">
  </div>
</div>