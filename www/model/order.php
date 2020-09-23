<?php 
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

function insert_order_data($db, $user_id, $item_id, $amount, $price) {
  try {
    $order_id = insert_order($db, $user_id);
    insert_order_item($db, $order_id, $item_id, $amount, $price);
  } catch (Exception $e) {
    return false;
  }
}

function insert_order($db, $user_id) {
  $sql = "
     INSERT INTO 
      `order`(
          `user_id`
      ) 
    VALUES(?)
    ";

  $statement = $db->prepare($sql);
  $statement->bindValue(1, $user_id, PDO::PARAM_INT);

  return execute_order_query($db, $statement);
}

function insert_order_item($db, $order_id, $item_id, $amount, $price) {
  $sql = "
    INSERT INTO 
       `order_item`(
          `order_id`,
          `item_id`,
          `price`,
          `amount`
      ) 
    VALUES(?, ?, ?, ?)
    ";

  $statement = $db->prepare($sql);
  $statement->bindValue(1, $order_id, PDO::PARAM_INT);
  $statement->bindValue(2, $item_id, PDO::PARAM_INT);
  $statement->bindValue(3, $price, PDO::PARAM_INT);
  $statement->bindValue(4, $amount, PDO::PARAM_INT);

  return execute_query($db, $statement);
}