<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'item.php';

$token = get_csrf_token();   
$db = get_db_connect();

if (is_valid_csrf_token($token)) {  
  if (isset($_GET['sort_type'])) {             // sort_typeがpostされたときの処理
    $sort_type = (int)$_GET['sort_type'];      // postされたsort_typeをint型にして$sort_typeに代入
  } else {
    $sort_type = SORT_TYPE_NEW;                             // 並び替え用の変数
  }
}

$page_number = 1;   
$first_item_number = 1;
$items = get_open_items($db, $sort_type, $first_item_number);      // 表示する商品のデータを取得する

for ($i = 0; $i < count($items); $i++) {           // エンティティ化
  $items[$i]['name'] = h($items[$i]['name']);
  $items[$i]['stock'] = h($items[$i]['stock']);
  $items[$i]['price'] = h($items[$i]['price']);
  $items[$i]['image'] = h($items[$i]['image']);  
}

$items = json_encode($items/*,JSON_UNESCAPED_UNICODE*/);

/*foreach($items as $item){ 
  print($item['name']) ;
}*/
header("Content-type: application/json; charset=UTF-8");
echo $items;