<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  
  <title>商品一覧</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'index.css'); ?>">
  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <script src="sort.js"></script>
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  
  <div class="container">
    <h1>商品一覧</h1>
    <?php include VIEW_PATH . 'templates/messages.php'; ?>
    <select name="sort">
      <option value="1" <?php if($sort === 1){ print 'selected'; } ?>>新着
      <option value="2" <?php if($sort === 2){ print 'selected'; } ?>>価格の安い順
      <option value="3" <?php if($sort === 3){ print 'selected'; } ?>>価格の高い順
    </select>

    <!-- ここから 「xx件中 xx - xx件目の商品」の表示 -->
    <p>
      <?php print $item_count; ?>件中
      <?php print $first_item_number; ?> - 
      <?php if(($first_item_number + NUM_ITEMS_PER_PAGE) <= $item_count) { 
              print $first_item_number + NUM_ITEMS_PER_PAGE - 1; 
            } else if (($first_item_number + NUM_ITEMS_PER_PAGE -1) > $item_count) {
              print $item_count;
            }
      ?>件目の商品
    </p>

    <!-- ここから ページ番号の表示 -->
    ページ：
      <a id="back">前へ</a>
      <?php 
      for ($i = 1; $i <= $total_item_pages; $i++) {
      ?>
        <a class="page_number" id="page_number_<?php print $i; ?>" value="<?php print $i; ?>"><?php print $i; ?></a>
      <?php
      }
      ?>
      <a id="next">次へ</a>
    <input type="hidden" id="current_page" value="<?php print $page_number; ?>">

    <div class="card-deck">
      <div class="row">
      <?php foreach($items as $item){ ?>
        <div class="col-6 item">
          <div class="card h-100 text-center">
            <div class="card-header">
              <?php print($item['name']); ?>
            </div>
            <figure class="card-body">
              <img class="card-img" src="<?php print(IMAGE_PATH . $item['image']); ?>">
              <figcaption>
                <?php print(number_format($item['price'])); ?>円
                <?php if($item['stock'] > 0){ ?>
                  <form action="index_add_cart.php" method="post">
                    <input type="submit" value="カートに追加" class="btn btn-primary btn-block">
                    <input type="hidden" name="item_id" value="<?php print($item['item_id']); ?>">
                    <input type="hidden" name="token" value="<?php print $token; ?>">
                  </form>
                <?php } else { ?>
                  <p class="text-danger">現在売り切れです。</p>
                <?php } ?>
              </figcaption>
            </figure>
          </div>
        </div>
      <?php } ?>
      </div>
    </div>
  </div>
  
</body>
</html>