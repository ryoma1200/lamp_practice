<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>商品一覧</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'index.css'); ?>">
  <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
  <script src="sort.js" defer></script>
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  <div class="container">
    <h1>商品一覧</h1>
    <?php include VIEW_PATH . 'templates/messages.php'; ?>
    <select name="sort_type">
      <option value="1" <?php if($sort_type === SORT_TYPE_NEW){ print 'selected'; } ?>>新着
      <option value="2" <?php if($sort_type === SORT_TYPE_PRICE_ASC){ print 'selected'; } ?>>価格の安い順
      <option value="3" <?php if($sort_type === SORT_TYPE_PRICE_DESC){ print 'selected'; } ?>>価格の高い順
      <input type="hidden" id="first_item_number" value="<?php print $first_item_number; ?>">
      <input type="hidden" id="last_item_number" value="<?php print $last_item_number; ?>">
    </select>

    <!-- ここから 「xx件中 xx - xx件目の商品」の表示 -->
    <div id="display">
      <p>
        <?php print $last_item_number; ?>件中
        <?php print $first_item_number; ?>-
        <?php if(($first_item_number + NUM_ITEMS_PER_PAGE - 1) <= $last_item_number) {           // そのページ最後になりうる商品番号が、最後の商品番号以下か
                print $first_item_number + NUM_ITEMS_PER_PAGE - 1;                               // そのページ最後の商品番号を表示する
              } else if (($first_item_number + NUM_ITEMS_PER_PAGE - 1) > $last_item_number) {    // そのページ最後になりうる商品番号が、最後の商品番号より大きいとき
                print $last_item_number;                                                         // 最後の商品番号を表示する
              }
        ?>件目の商品
      </p>

      <!-- ここから ページ番号の表示 -->
      ページ：
      <form method="get"> 
        <input type="submit" name="sub" value="前へ" class="submit_buttom">
        <input type="hidden" name="pagination_type" value="1">
        <input type="hidden" name="current_page" value="<?php print $page_number; ?>">
        <input type="hidden" name="sort_type" value="<?php print $sort_type; ?>">
        <input type="hidden" name="token" value="<?php print $token; ?>">
      </form>
      <?php 
      for ($i = 1; $i <= $last_page; $i++) {
      ?>
        <a href="#" class="page_number" id="page_number_<?php print $i; ?>" value="<?php print $i; ?>"><?php print $i; ?></a>
      <?php
      }
      ?>
      <form method="get"> 
        <input type="submit" name="sub" value="次へ" class="submit_buttom">
        <input type="hidden" name="pagination_type" value="2">
        <input type="hidden" name="current_page" value="<?php print $page_number; ?>">
        <input type="hidden" name="sort_type" value="<?php print $sort_type; ?>">
        <input type="hidden" name="token" value="<?php print $token; ?>">
      </form>

      <!-- ここから　商品の表示 -->
      <div class="card-deck">
        <div class="row">
        <?php 
        foreach($items as $item){ ?>
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
                      <input type="hidden" name="current_page" value="<?php print $page_number; ?>">
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
    <br>
    <div>
      <h1>人気ランキング</h1>
        <?php for($i = 0; $i < count($ranking_items); $i++){  ?>
          <h2>第<?php print $i+1; ?>位</h2>
          <div class="col-6 item">
            <div class="card h-100 text-center">
              <div class="card-header">
                <?php print($ranking_items[$i]['name']); ?>
              </div>
              <figure class="card-body">
                <img class="card-img" src="<?php print(IMAGE_PATH . $ranking_items[$i]['image']); ?>">
                <figcaption>
                  <p>売り上げた数</p>
                  <p><?php print $ranking_items[$i]['total_amount']; ?>個</p>
                </figcaption>
              </figure>
            </div>
          </div>
        <?php } ?>
    </div>
    <br>
  </div>
</body>
</html>