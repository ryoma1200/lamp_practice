<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';

session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();
$user = get_login_user($db);

$token = get_post("token");

$carts = get_user_carts($db, $user['user_id']);

if (is_valid_csrf_token($token)) {
  if(purchase_carts($db, $carts) === false){
    set_error('商品が購入できませんでした。');
    redirect_to(CART_URL);
  } 
  delete_csrf_token();
} else {
  set_error('不正なリクエストです。');
}

$total_price = sum_carts($carts);

for ($i = 0; $i < count($carts); $i++) {           // エンティティ化
  $carts[$i]['name'] = h($carts[$i]['name']);
  $carts[$i]['amount'] = h($carts[$i]['amount']);
  $carts[$i]['price'] = h($carts[$i]['price']);
  $carts[$i]['image'] = h($carts[$i]['image']);  
}

include_once '../view/finish_view.php';