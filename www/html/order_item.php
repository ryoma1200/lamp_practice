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

if (is_valid_csrf_token($token)) {

  if(is_admin($user) === true){          // 管理者でログインした場合の処理
    $orders = get_all_orders($db);       // 全ユーザーの購入履歴を取得する   
  } else {
    $orders = get_user_orders($db, $user['user_id']);               // 特定のユーザーの購入履歴を取得する
  }

  $user_order_items = array();           // $user_oeder_items の初期化

  foreach($orders as $order) {
    $item_list = get_user_order_items($db, $order['order_id']);     // 同じorder_idをもつ商品を$item_listに格納する
    
    $user_order_items[] = array(                      // 購入履歴と商品情報を、まとめて$user_order_itemsに格納していく
      'order_id' => $order['order_id'],
      'user_id' => $order['user_id'],
      'price_sum' => $order['price_sum'],
      'create_date' => $order['create_date'],
      'item_list' => $item_list                       // 商品のデータ
    );
  }
}

if (isset($user_order_items)) { 
  $user_order_items = array_reverse($user_order_items);                // $ordersの配列を、注文の新着順にする。
  for ($i = 0; $i < count($user_order_items); $i++) {                  // エンティティ化
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
}

include_once VIEW_PATH . 'order_item_view.php';