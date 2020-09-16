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


$item_id = get_post('item_id');

$token = get_post("token");                         // postされたトークンを取得

if (is_valid_csrf_token($token)) {                  // トークンの照合
  if(add_cart($db,$user['user_id'], $item_id)){
    set_message('カートに商品を追加しました。');
  } else {
    set_error('カートの更新に失敗しました。');  
  }
  delete_csrf_token();                              // セッションに保存されたトークンを削除
} else {                                            // 照合できなかったときの処理
  set_error('不正なリクエストです。');
}

redirect_to(HOME_URL);