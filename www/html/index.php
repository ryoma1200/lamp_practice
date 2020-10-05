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

if (isset($_POST['sort'])) {            // sortがpostされたときの処理
  $sort = (int)$_POST['sort'];          // postされたsortをint型にして$sortに代入
} else {
  $sort = 0;                            // 並び替え用の変数
}

// view表示用
$item_count = count_items($db);
$total_item_pages = (int)ceil($item_count / NUM_ITEMS_PER_PAGE);

print '$total_item_pages:'.$total_item_pages.'<br>';
if (isset($_GET['page_number'])) {
  $page_number = (int)$_GET['page_number'];   
  

} else if (isset($_GET['page_up_type']) && $_GET['current_page']) {
  $page_up_type = (int)$_GET['page_up_type'];
  $current_page = (int)$_GET['current_page'];
  print '$current_page :'.$current_page;

  if ($page_up_type === PAGE_UP_TYPE_BACK && $current_page !== 1){
    $page_number = $current_page - 1;
  } else if ($page_up_type === PAGE_UP_TYPE_NEXT && $current_page !== $total_item_pages) {
    $page_number = $current_page + 1;
  } else {
    $page_number = $current_page;
    $first_item_number = calc_first_item_number($page_number);
  }

} else {
  $page_number = 1;
  $first_item_number = 1;     // 最初に表示される商品の番号
}
$first_item_number = calc_first_item_number($page_number);
/*確認用
print '<br>$page_number:'.$page_number.'<br>$first_item_number:'.$first_item_number.'<br><br>';
*/

$items = get_open_items($db, $sort, $first_item_number);

for ($i = 0; $i < count($items); $i++) {           // エンティティ化
  $items[$i]['name'] = h($items[$i]['name']);
  $items[$i]['stock'] = h($items[$i]['stock']);
  $items[$i]['price'] = h($items[$i]['price']);
  $items[$i]['image'] = h($items[$i]['image']);  
}

include_once VIEW_PATH . 'index_view.php';