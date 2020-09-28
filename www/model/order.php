<?php 
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

function get_user_orders($db, $user_id){
    $sql = "
        SELECT
            order.order_id,
            order.user_id,
            order.create_date,
            sum(order_item.price * order_item.amount) AS 'price_sum'
        FROM
            `order`
        JOIN
            order_item
        ON
            order.order_id = order_item.order_id
        WHERE
            order.user_id = ?
        GROUP BY
            order.order_id
    ";

    $statement = $db->prepare($sql);
    $statement->bindValue(1, $user_id, PDO::PARAM_INT);
  
    return fetch_all_query($db, $statement);

}

function get_user_order_items($db, $order_id){
    $sql = "
        SELECT
            order.order_id,
            order.user_id,
            order.create_date,
            order_item.item_id,
            order_item.price,
            order_item.amount,
            items.name
        FROM
            `order`
        JOIN
            order_item
        ON
            order.order_id = order_item.order_id
        JOIN
            items
        ON
            order_item.item_id = items.item_id
        WHERE
            order.order_id = ?
        ";

    $statement = $db->prepare($sql);
    $statement->bindValue(1, $order_id, PDO::PARAM_INT);
    
    return fetch_all_query($db, $statement);
}
 
/*
  function get_user_orders($db, $user_id){
    $sql = "
      SELECT
        order.order_id,
        order.user_id,
        order.create_date,
        order_item.order_item_id,
        order_item.item_id,
        order_item.price,
        order_item.amount
      FROM
        `order`
      JOIN
        order_item
      ON
        order.order_id = order_item.order_id
      WHERE
        order.user_id = ?
    ";

    $statement = $db->prepare($sql);
    $statement->bindValue(1, $user_id, PDO::PARAM_INT);
  
    return fetch_all_query($db, $statement);

  }
  */