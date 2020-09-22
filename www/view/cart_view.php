<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>カート</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'cart.css'); ?>">
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  <h1>カート</h1>
  <div class="container">

    <?php include VIEW_PATH . 'templates/messages.php'; ?>

    <?php if(count($carts) > 0){ ?>
      <table class="table table-bordered">
        <thead class="thead-light">
          <tr>
            <th>商品画像</th>
            <th>商品名</th>
            <th>価格</th>
            <th>購入数</th>
            <th>小計</th>
            <th>操作</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($carts as $cart){ ?>
          <tr>
            <td><img src="<?php print(IMAGE_PATH . $cart['image']);?>" class="item_image"></td>
            <td><?php print($cart['name']); ?></td>
            <td><?php print(number_format($cart['price'])); ?>円</td>
            <td>
              <form method="post" action="cart_change_amount.php">
                <input type="number" name="amount" value="<?php print($cart['amount']); ?>">
                個
                <input type="submit" value="変更" class="btn btn-secondary">
                <input type="hidden" name="cart_id" value="<?php print($cart['cart_id']); ?>">
                <input type="hidden" name="token" value="<?php print $token; ?>">
              </form>
            </td>
            <td><?php print(number_format($cart['price'] * $cart['amount'])); ?>円</td>
            <td>

              <form method="post" action="cart_delete_cart.php">
                <input type="submit" value="削除" class="btn btn-danger delete">
                <input type="hidden" name="cart_id" value="<?php print($cart['cart_id']); ?>">
                <input type="hidden" name="token" value="<?php print $token; ?>">
              </form>

            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
      <p class="text-right">合計金額: <?php print number_format($total_price); ?>円</p>
      <form method="post" action="finish.php">
        <input class="btn btn-block btn-primary" type="submit" value="購入する">
        <input type="hidden" name="token" value="<?php print $token; ?>">
      </form>
    <?php } else { ?>
      <p>カートに商品はありません。</p>
    <?php } ?> 
  </div>
  <script>
    $('.delete').on('click', () => confirm('本当に削除しますか？'))
  </script>
</body>
</html>