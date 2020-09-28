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

    <?php if(count($orders) > 0){ ?>
      <table class="table table-bordered">
        <thead class="thead-light">
          <tr>
            <th>注文番号</th>
            <th>購入日時</th>
            <th>お支払い金額</th>
            <th>購入明細</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($orders as $order) { ?>
          <tr>
            <td><?php print $order['order_id'] ?></td>
            <td><?php print $order['create_date'] ?></td>
            <td><?php print $order['price_sum'] ?>円</td>
            <td>
              <form method="post" action="order_item.php">
                <input type="submit" name="order_item" value="購入明細表示" class="btn btn-secondary">
                <input type="hidden" name="order_id" value="<?php print $order['order_id'] ?>">
                <input type="hidden" name="token" value="<?php print $token; ?>">
              </form>
            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    <?php } else { ?>
      <p>購入履歴はありません。</p>
    <?php } ?> 
  </div>
</body>
</html>