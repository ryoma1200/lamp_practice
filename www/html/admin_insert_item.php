<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';

session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();

$user = get_login_user($db);

if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}

$name = get_post('name');
$price = get_post('price');
$status = get_post('status');
$stock = get_post('stock');
$image = get_file('image');

$token = get_post("token");

// var_dump($token);

if (is_valid_csrf_token($token) !== false) {
  if(regist_item($db, $name, $price, $stock, $status, $image)){
    set_message('商品を登録しました。');
  }else {
    set_error('商品の登録に失敗しました。');
  }
} else {
  set_error('不正なリクエストです。');
}

redirect_to(ADMIN_URL);