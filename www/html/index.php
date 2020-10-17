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

$token = get_csrf_token();                      // トークンの生成 ＆ セッションに格納

if (is_valid_csrf_token($token)) {  
  if (isset($_GET['sort_type'])) {             // sort_typeがpostされたときの処理
    $sort_type = (int)$_GET['sort_type'];      // postされたsort_typeをint型にして$sort_typeに代入
  } else {
    $sort_type = SORT_TYPE_NEW;                             // 並び替え用の変数
  }
}

// view表示用
$last_item_number = calc_last_item_number($db);                        // 最後の商品番号を取得する
$last_page = (int)ceil($last_item_number / NUM_ITEMS_PER_PAGE);        // ページ数を計算

//（ここから、遷移するページ番号($page_number)を決める）

if (is_valid_csrf_token($token)) {  
  // 遷移したいページ番号が押されたとき
  if (isset($_GET['page_number'])) {     
    $page_number = (int)$_GET['page_number'];             // getされたpage_numberを$page_number(遷移先のページ)に代入

  // 次へまたは前へが押されたとき
  } else if (isset($_GET['pagination_type']) && $_GET['current_page']) {   
    $pagination_type = (int)$_GET['pagination_type'];           // getされたページの更新タイプ(次へか前へ)を取得
    $current_page = (int)$_GET['current_page'];           // getされた現在のページを取得

    // 前へが押されたとき & 1ページ目にいないとき
    if ($pagination_type === PAGINATION_TYPE_BACK && $current_page !== 1){  
      $page_number = $current_page - 1;                   // $page_numberを１減らす

    // 次へが押されたとき & 最後のページにいないとき
    } else if ($pagination_type === PAGINATION_TYPE_NEXT && $current_page !== $last_page) {
      $page_number = $current_page + 1;                   // $page_numberを１増やす

    } else {
      $page_number = $current_page;                       // $current_page(現在のページ)を$page_number(遷移先のページ)        
    }

  // 何もgetされていないときの処理
  } else {    
    $page_number = 1;             // $page_numberに1を代入（1ページ目を表示)
  }
}

$first_item_number = calc_first_item_number($page_number);         // 表示される１つ目の商品の番号を取得


$items = get_open_items($db, $sort_type, $first_item_number);      // 表示する商品のデータを取得する


for ($i = 0; $i < count($items); $i++) {           // エンティティ化
  $items[$i]['name'] = h($items[$i]['name']);
  $items[$i]['stock'] = h($items[$i]['stock']);
  $items[$i]['price'] = h($items[$i]['price']);
  $items[$i]['image'] = h($items[$i]['image']);  
}


// ランキング機能
$ranking_items = get_rankingu_items($db);

for ($i = 0; $i < count($ranking_items); $i++) {           // エンティティ化
  $items[$i]['name'] = h($items[$i]['name']);
  $items[$i]['total_amount'] = h($items[$i]['total_amount']);
  $items[$i]['image'] = h($items[$i]['image']);  
}

include_once VIEW_PATH . 'index_view.php';