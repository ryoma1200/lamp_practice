<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'item.php';

session_start();

$token = get_csrf_token();   
$db = get_db_connect();

if (is_valid_csrf_token($token)) {  
  if (isset($_GET['sort_type'])) {             // sort_typeがpostされたときの処理
    $sort_type = (int)$_GET['sort_type'];      // postされたsort_typeをint型にして$sort_typeに代入
  }
  if (isset($_GET['last_item_number'])) {             // sort_typeがpostされたときの処理
    $last_item_number = (int)$_GET['last_item_number'];      // postされたsort_typeをint型にして$sort_typeに代入
    $last_page = (int)ceil($last_item_number / NUM_ITEMS_PER_PAGE);
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


// (ここから追加するHTMLの文字列を、$htmlに追加していく)


$html = '<p>'.$last_item_number.'件中'.$first_item_number.'-';

if(($first_item_number + NUM_ITEMS_PER_PAGE - 1) <= $last_item_number) {          
  $html .= $first_item_number + NUM_ITEMS_PER_PAGE - 1;                        
} else if (($first_item_number + NUM_ITEMS_PER_PAGE - 1) > $last_item_number) {   
  $html .= $last_item_number;          
}

$html .= '件目の商品</p>
  <form method="get"> 
    <input type="submit" name="sub" value="前へ" class="submit_buttom">
    <input type="hidden" name="pagination_type" value="1">
    <input type="hidden" name="current_page" value="'.$page_number.'">
    <input type="hidden" name="sort_type" value="'.$sort_type.'">
    <input type="hidden" name="token" value="'.$token.'">
  </form>';

for ($i = 1; $i <= $last_page; $i++) {
  $html .= '<a href="#" class="page_number" id="page_number_'.$i.'" value="'.$i.'">'.$i.'</a> ';
}

$html .= '
<form method="get"> 
  <input type="submit" name="sub" value="次へ" class="submit_buttom">
  <input type="hidden" name="pagination_type" value="2">
  <input type="hidden" name="current_page" value="'.$page_number.'">
  <input type="hidden" name="sort_type" value="'.$sort_type.'">
  <input type="hidden" name="token" value="'.$token.'">
</form>';

foreach($items as $item){ 
  $html .= '
    <div class="col-6 item">
    <div class="card h-100 text-center">
    <div class="card-header">
    '.$item['name'].'
    </div>
    <figure class="card-body">
    <img class="card-img" src="'.IMAGE_PATH.$item['image'].'">
    <figcaption>'.number_format($item['price']).'円';

  if($item['stock'] > 0){ 
    $html .= '
      <form action="index_add_cart.php" method="post">
      <input type="submit" value="カートに追加" class="btn btn-primary btn-block">
      <input type="hidden" name="item_id" value="'.$item['item_id'].'">
      <input type="hidden" name="current_page" value="'.$page_number.'">
      <input type="hidden" name="token" value="'.$token.'">
      </form>';
  } else {
     $html .= '<p class="text-danger">現在売り切れです。</p>';
  }
        
  $html .= '
    </figcaption>
    </figure>
    </div>
    </div>
    </div>';
} 

echo json_encode($html);    // 生成したhtmlのテキストをjson形式で返す