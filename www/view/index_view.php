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