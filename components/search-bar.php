<?php
$location = $args['location'] ?? '';
?>

<form role="search" method="get" class="search-form" action="<?php echo home_url('/'); ?>">
  <div class="search_input_box">
    <input type="search" name="s" class="search_input <?php echo $location ?>" placeholder="전체 검색…" value="<?php echo is_search() ? get_search_query() : ''; ?>" autocomplete="off" />
    <div class="func_btn">
      <button type="button" class="clear_btn <?php echo $location ?>">
        <svg class="icon-close">
          <use href="#icon-close"></use>
        </svg>
      </button>
      <button type="submit" class="search_submit">
        <svg class="icon-search">
          <use href="#icon-search"></use>
        </svg>
      </button>
    </div>
  </div>
</form>