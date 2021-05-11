<?php 
// 汎用関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';
// DBに関する関数ファイル読み込み
require_once MODEL_PATH . 'db.php';

// user_idを指定し、カート内の商品情報を取得
function get_user_carts($db, $user_id){
  $sql = "
    SELECT
      items.item_id,
      items.name,
      items.price,
      items.stock,
      items.status,
      items.image,
      carts.cart_id,
      carts.user_id,
      carts.amount
    FROM
      carts
    JOIN
      items
    ON
      carts.item_id = items.item_id
    WHERE
      carts.user_id = {$user_id}
  ";
  // SQLを実行し、商品情報を配列で取得する
  return fetch_all_query($db, $sql);
}
// user_idとitem_idを指定し、カート内の商品情報を取得
function get_user_cart($db, $user_id, $item_id){
  $sql = "
    SELECT
      items.item_id,
      items.name,
      items.price,
      items.stock,
      items.status,
      items.image,
      carts.cart_id,
      carts.user_id,
      carts.amount
    FROM
      carts
    JOIN
      items
    ON
      carts.item_id = items.item_id
    WHERE
      carts.user_id = {$user_id}
    AND
      items.item_id = {$item_id}
  ";
  
  // SQLを実行し、商品情報を配列で取得する
  return fetch_query($db, $sql);

}

function add_cart($db, $user_id, $item_id ) {
  // user_idとitem_idを指定し、カート内の商品情報を取得
  $cart = get_user_cart($db, $user_id, $item_id);
  // カート内に同じ商品が無い場合
  if($cart === false){
    // カートに商品を追加する
    return insert_cart($db, $user_id, $item_id);
  }
  // 既にその商品がカートにある場合、数量を＋１する
  return update_cart_amount($db, $cart['cart_id'], $cart['amount'] + 1);
}
// カートに商品を追加する
function insert_cart($db, $user_id, $item_id, $amount = 1){
  $sql = "
    INSERT INTO
      carts(
        item_id,
        user_id,
        amount
      )
    VALUES({$item_id}, {$user_id}, {$amount})
  ";

  // SQLを実行
  return execute_query($db, $sql);
}
// カートの商品の数量を変更する
function update_cart_amount($db, $cart_id, $amount){
  $sql = "
    UPDATE
      carts
    SET
      amount = {$amount}
    WHERE
      cart_id = {$cart_id}
    LIMIT 1
  ";
  // SQLを実行
  return execute_query($db, $sql);
}
// カートの商品を削除する
function delete_cart($db, $cart_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      cart_id = {$cart_id}
    LIMIT 1
  ";
// SQLを実行
  return execute_query($db, $sql);
}

function purchase_carts($db, $carts){
  // 商品ステータスや在庫があるか等の確認
  if(validate_cart_purchase($carts) === false){
    return false;
  }
  foreach($carts as $cart){
    // 在庫とカートの数量を比較し、在庫がなければエラーメッセージを表示し、購入できない
    if(update_item_stock(
        $db, 
        $cart['item_id'], 
        $cart['stock'] - $cart['amount']
      ) === false){
      set_error($cart['name'] . 'の購入に失敗しました。');
    }
  }
  // カートから商品を削除
  delete_user_carts($db, $carts[0]['user_id']);
}
// カートから商品を削除
function delete_user_carts($db, $user_id){
  $sql = "
    DELETE FROM
      carts
    WHERE
      user_id = {$user_id}
  ";
// SQLを実行
  execute_query($db, $sql);
}


function sum_carts($carts){
  $total_price = 0;
  foreach($carts as $cart){
    $total_price += $cart['price'] * $cart['amount'];
  }
  return $total_price;
}

function validate_cart_purchase($carts){
  //　カートに商品が入っていない場合エラーメッセージを表示
  if(count($carts) === 0){
    set_error('カートに商品が入っていません。');
    return false;
  }
  // 商品のステータスが非公開の場合、エラーメッセージを表示し、購入できない
  foreach($carts as $cart){
    if(is_open($cart) === false){
      set_error($cart['name'] . 'は現在購入できません。');
    }
    // 在庫とカートの数量を比較し、在庫がなければエラーメッセージを表示し、購入できない
    if($cart['stock'] - $cart['amount'] < 0){
      set_error($cart['name'] . 'は在庫が足りません。購入可能数:' . $cart['stock']);
    }
  }
  // その他のエラーがある場合も購入できない
  if(has_error() === true){
    return false;
  }
  return true;
}

