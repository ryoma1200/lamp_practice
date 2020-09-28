<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>カート</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'cart.css'); ?>">
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  <h1>注文履歴</h1>
  <div class="container">

    <?php include VIEW_PATH . 'templates/messages.php'; ?>

    <?php foreach($user_order_items as $user_order_item) { ?>
      <?php print $user_order_item['order_id'] ?>
      <?php print $user_order_item['create_date'] ?>
      <?php print $user_order_item['price_sum'] ?>円
      <table class="table table-bordered">
        <thead class="thead-light">
          <tr>
            <th>商品名</th>
            <th>価格</th>
            <th>個数</th>
            <th>小計</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($user_order_item['item_list'] as $user_order_item_list) { ?>
            <tr>
              <td><?php print $user_order_item_list['name'] ?></td>
              <td><?php print $user_order_item_list['price'] ?>円</td>
              <td><?php print $user_order_item_list['amount'] ?>点</td>
              <td><?php print $user_order_item_list['price'] * $user_order_item_list['amount'] ?>円</td>
            </tr>
          <?php } ?>
        </tbody>
    <?php } ?>


</div>
</body>
</html>