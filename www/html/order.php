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

$token = get_csrf_token();


if (is_valid_csrf_token($token)) {
  if(is_admin($user) === true){                          // 管理者でログインした場合の処理
    $orders = get_all_orders($db);                       // 全ユーザーの購入履歴を取得する
  } else { 
    $orders = get_user_orders($db, $user['user_id']);    // 特定のユーザーの購入履歴を取得する
  }
}

if (isset($orders)) { 
  $orders = array_reverse($orders);                      // $ordersの配列を、注文の新着順にする。
  for ($i = 0; $i < count($orders); $i++) {              // エンティティ化
    $orders[$i]['order_id'] = h($orders[$i]['order_id']);
    $orders[$i]['price_sum'] = h($orders[$i]['price_sum']);
    $orders[$i]['create_date'] = h($orders[$i]['create_date']);
  }
}

include_once VIEW_PATH . 'order_view.php';