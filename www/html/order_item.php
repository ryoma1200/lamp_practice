<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';
require_once MODEL_PATH . 'order.php';

session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();
$user = get_login_user($db);
$order_id = get_post('order_id');

$token = get_csrf_token();

$user_order_items = array();

$orders = get_user_orders($db, $user['user_id']);

foreach($orders as $order) {

  $item_list = get_user_order_items($db, $order['order_id']);
  $user_order_items[] = array(
    'order_id' => $order['order_id'],
    'price_sum' => $order['price_sum'],
    'create_date' => $order['create_date'],
    'item_list' => $item_list
  );
}





for ($i = 0; $i < count($user_order_items); $i++) {           // エンティティ化
  $user_order_items[$i]['order_id'] = h($user_order_items[$i]['order_id']);
  $user_order_items[$i]['price_sum'] = h($user_order_items[$i]['price_sum']);
  $user_order_items[$i]['create_date'] = h($user_order_items[$i]['create_date']);
  
  for ($j = 0; $j < count($user_order_items[$i]['item_list']); $j++) {
    $user_order_items[$i]['item_list'][$j]['item_id'] = h($user_order_items[$i]['item_list'][$j]['item_id']);
    $user_order_items[$i]['item_list'][$j]['price'] = h($user_order_items[$i]['item_list'][$j]['price']);
    $user_order_items[$i]['item_list'][$j]['amount'] = h($user_order_items[$i]['item_list'][$j]['amount']);
    $user_order_items[$i]['item_list'][$j]['name'] = h($user_order_items[$i]['item_list'][$j]['name']);
  }
}

var_dump($user_order_items);

include_once VIEW_PATH . 'order_item_view.php';