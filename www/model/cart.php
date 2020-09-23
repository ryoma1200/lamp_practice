<?php 
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

function get_user_carts($db, $user_id){
  $sql = "
    SELECT
      items.item_id,
      items.name,
      items.price,
      items.stock,
      items.status,
      items.image,
      carts.cart_id,
      carts.user_id,
      carts.amount
    FROM
      carts
    JOIN
      items
    ON
      carts.item_id = items.item_id
    WHERE
      carts.user_id = ?
  ";

  $statement = $db->prepare($sql);
  $statement->bindValue(1, $user_id, PDO::PARAM_INT);

  return fetch_all_query($db, $statement);
}

function get_user_cart($db, $user_id, $item_id){
  $sql = "
    SELECT
      items.item_id,
      items.name,
      items.price,
      items.stock,
      items.status,
      items.image,
      carts.cart_id,
      carts.user_id,
      carts.amount
    FROM
      carts
    JOIN
      items
    ON
      carts.item_id = items.item_id
    WHERE
      carts.user_id = ?
    AND
      items.item_id = ?
  ";

  $statement = $db->prepare($sql);
  $statement->bindValue(1, $user_id, PDO::PARAM_INT);
  $statement->bindValue(2, $item_id, PDO::PARAM_INT);

  return fetch_query($db, $statement);

}

function add_cart($db, $user_id, $item_id ) {
  $cart = get_user_cart($db, $user_id, $item_id);
  if($cart === false){
    return insert_cart($db, $user_id, $item_id);
  }
  return update_cart_amount($db, $cart['cart_id'], $cart['amount'] + 1);
}

function insert_cart($db, $user_id, $item_id, $amount = 1){
  $sql = "
    INSERT INTO
      carts(
        item_id,
        user_id,
        amount
      )
    VALUES(?, ?, ?)
  ";

  $statement = $db->prepare($sql);
  $statement->bindValue(1, $item_id, PDO::PARAM_INT);
  $statement->bindValue(2, $user_id, PDO::PARAM_INT);
  $statement->bindValue(3, $amount, PDO::PARAM_INT);

  return execute_query($db, $statement);
}

function update_cart_amount($db, $cart_id, $amount){
  $sql = "
    UPDATE
      carts
    SET
      amount = ?
    WHERE
      cart_id = ?
    LIMIT 1
  ";

  $statement = $db->prepare($sql);
  $statement->bindValue(1, $amount, PDO::PARAM_INT);
  $statement->bindValue(2, $cart_id, PDO::PARAM_INT);

  return execute_query($db, $statement);
}

function delete_cart($db, $cart_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      cart_id = ?
    LIMIT 1
  ";

  $statement = $db->prepare($sql);
  $statement->bindValue(1, $cart_id, PDO::PARAM_INT);

  return execute_query($db, $statement);
}

function purchase_carts($db, $carts){
  if(validate_cart_purchase($carts) === false){         // 購入可能かチェック
    return false;
  }
  foreach($carts as $cart){
    if(update_item_stock($db, $cart['item_id'], $cart['stock'] - $cart['amount']) === false || 
       insert_order_data($db, $cart['user_id'], $cart['item_id'], $cart['amount'], $cart['price']) === false ){
        set_error($cart['name'] . 'の購入に失敗しました。');
    }
  }
  
  delete_user_carts($db, $carts[0]['user_id']);
}

function delete_user_carts($db, $user_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      user_id = ?
  ";

  $statement = $db->prepare($sql);
  $statement->bindValue(1, $user_id, PDO::PARAM_INT);

  execute_query($db, $statement);
}


function sum_carts($carts){
  $total_price = 0;
  foreach($carts as $cart){
    $total_price += $cart['price'] * $cart['amount'];
  }
  return $total_price;
}

function validate_cart_purchase($carts){
  if(count($carts) === 0){
    set_error('カートに商品が入っていません。');
    return false;
  }
  foreach($carts as $cart){
    if(is_open($cart) === false){
      set_error($cart['name'] . 'は現在購入できません。');
    }
    if($cart['stock'] - $cart['amount'] < 0){
      set_error($cart['name'] . 'は在庫が足りません。購入可能数:' . $cart['stock']);
    }
  }
  if(has_error() === true){
    return false;
  }
  return true;
}

