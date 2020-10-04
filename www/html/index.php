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

$token = get_csrf_token();              // トークンの生成 ＆ セッションに格納

$sort = 0;                              // 並び替え用の変数
if (isset($_POST['sort'])) {            // sortがpostされたときの処理
  $sort = (int)$_POST['sort'];          // postされたsortをint型にして$sortに代入
} 

$items = get_open_items($db, $sort);


for ($i = 0; $i < count($items); $i++) {           // エンティティ化
  $items[$i]['name'] = h($items[$i]['name']);
  $items[$i]['stock'] = h($items[$i]['stock']);
  $items[$i]['price'] = h($items[$i]['price']);
  $items[$i]['image'] = h($items[$i]['image']);  
}

include_once VIEW_PATH . 'index_view.php';