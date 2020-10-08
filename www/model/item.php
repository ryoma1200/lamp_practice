<?php
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'db.php';

// DB利用

function get_item($db, $item_id){
  $sql = "
    SELECT
      item_id, 
      name,
      stock,
      price,
      image,
      status
    FROM
      items
    WHERE
      item_id = ?
  ";

  $statement = $db->prepare($sql);
  $statement->bindValue(1, $item_id, PDO::PARAM_INT);

  return fetch_query($db, $statement);
}

function get_items($db, $is_open = false, $sort_type = 0, $first_item_number = 0){
  $sql = '
    SELECT
      item_id, 
      name,
      stock,
      price,
      image,
      status
    FROM
      items
  ';

  if ($is_open === true){
    $sql .= '
      WHERE status = 1
    ';
  }

  if ($sort_type === 0 || $sort_type === 1) {            // 新着順に並べる
    $sql .= '
      ORDER BY created DESC
    ';
  } else if ($sort_type === 2) {            // 値段が安い順に並べる
    $sql .= '
      ORDER BY price ASC
    ';
  } else if ($sort_type === 3) {           // 値段が高い順に並べる
    $sql .= '
      ORDER BY price DESC
    ';
  }

  if ($first_item_number !== 0) {
    $sql .= 'LIMIT ?, ?';
  }

  $statement = $db->prepare($sql);

  if ($first_item_number !== 0) {
    $statement->bindValue(1, $first_item_number - 1, PDO::PARAM_INT);
    $statement->bindValue(2, NUM_ITEMS_PER_PAGE, PDO::PARAM_INT);
  }

  return fetch_all_query($db, $statement);
}

function get_all_items($db){
  return get_items($db);
}

function get_open_items($db, $sort_type, $first_item_number){
  return get_items($db, true, $sort_type, $first_item_number);
}

function count_items($db){
  $items = get_all_items($db);
  return count($items);
}

function regist_item($db, $name, $price, $stock, $status, $image){
  $filename = get_upload_filename($image);
  if(validate_item($name, $price, $stock, $filename, $status) === false){
    return false;
  }
  return regist_item_transaction($db, $name, $price, $stock, $status, $image, $filename);
}

function regist_item_transaction($db, $name, $price, $stock, $status, $image, $filename){
  $db->beginTransaction();
  if(insert_item($db, $name, $price, $stock, $filename, $status) 
    && save_image($image, $filename)){
    $db->commit();
    return true;
  }
  $db->rollback();
  return false;
  
}

function insert_item($db, $name, $price, $stock, $filename, $status){
  $status_value = PERMITTED_ITEM_STATUSES[$status];
  $sql = "
    INSERT INTO
      items(
        name,
        price,
        stock,
        image,
        status
      )
    VALUES(?, ?, ?, ?, ?);
  ";

  $statement = $db->prepare($sql);
  $statement->bindValue(1, $name, PDO::PARAM_STR);
  $statement->bindValue(2, $price, PDO::PARAM_INT);
  $statement->bindValue(3, $stock, PDO::PARAM_INT);
  $statement->bindValue(4, $filename, PDO::PARAM_STR);
  $statement->bindValue(5, $status_value, PDO::PARAM_INT);

  return execute_query($db, $statement);
}

function update_item_status($db, $item_id, $status){
  $sql = "
    UPDATE
      items
    SET
      status = ?
    WHERE
      item_id = ?
    LIMIT 1
  ";
 
  $statement = $db->prepare($sql);
  $statement->bindValue(1, $status, PDO::PARAM_INT);
  $statement->bindValue(2, $item_id, PDO::PARAM_INT);
  
  return execute_query($db, $statement);
}

function update_item_stock($db, $item_id, $stock){
  $sql = "
    UPDATE
      items
    SET
      stock = ?
    WHERE
      item_id = ?
    LIMIT 1
  ";

  $statement = $db->prepare($sql);
  $statement->bindValue(1, $stock, PDO::PARAM_INT);
  $statement->bindValue(2, $item_id, PDO::PARAM_INT);

  return execute_query($db, $statement);
}

function destroy_item($db, $item_id){
  $item = get_item($db, $item_id);
  if($item === false){
    return false;
  }
  $db->beginTransaction();
  if(delete_item($db, $item['item_id'])
    && delete_image($item['image'])){
    $db->commit();
    return true;
  }
  $db->rollback();
  return false;
}

function delete_item($db, $item_id){
  $sql = "
    DELETE FROM
      items
    WHERE
      item_id = ?
    LIMIT 1
  ";

  $statement = $db->prepare($sql);
  $statement->bindValue(1, $item_id, PDO::PARAM_INT);
  
  return execute_query($db, $statement);
}


// 非DB

function is_open($item){
  return $item['status'] === 1;
}

function validate_item($name, $price, $stock, $filename, $status){
  $is_valid_item_name = is_valid_item_name($name);
  $is_valid_item_price = is_valid_item_price($price);
  $is_valid_item_stock = is_valid_item_stock($stock);
  $is_valid_item_filename = is_valid_item_filename($filename);
  $is_valid_item_status = is_valid_item_status($status);

  return $is_valid_item_name
    && $is_valid_item_price
    && $is_valid_item_stock
    && $is_valid_item_filename
    && $is_valid_item_status;
}

function is_valid_item_name($name){
  $is_valid = true;
  if(is_valid_length($name, ITEM_NAME_LENGTH_MIN, ITEM_NAME_LENGTH_MAX) === false){
    set_error('商品名は'. ITEM_NAME_LENGTH_MIN . '文字以上、' . ITEM_NAME_LENGTH_MAX . '文字以内にしてください。');
    $is_valid = false;
  }
  return $is_valid;
}

function is_valid_item_price($price){
  $is_valid = true;
  if(is_positive_integer($price) === false){
    set_error('価格は0以上の整数で入力してください。');
    $is_valid = false;
  }
  return $is_valid;
}

function is_valid_item_stock($stock){
  $is_valid = true;
  if(is_positive_integer($stock) === false){
    set_error('在庫数は0以上の整数で入力してください。');
    $is_valid = false;
  }
  return $is_valid;
}

function is_valid_item_filename($filename){
  $is_valid = true;
  if($filename === ''){
    $is_valid = false;
  }
  return $is_valid;
}

function is_valid_item_status($status){
  $is_valid = true;
  if(isset(PERMITTED_ITEM_STATUSES[$status]) === false){
    $is_valid = false;
  }
  return $is_valid;
}

function calc_first_item_number($page_number) {
  $first_item_number = ($page_number - 1) * NUM_ITEMS_PER_PAGE + 1;
   return $first_item_number;
}


function calc_last_item_number($db) {
  $last_item_number = count_items($db);
  return $last_item_number;
}