<?php
// 定数ファイルを読み込み
require_once '../conf/const.php';
// 汎用関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';
// userデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'user.php';
// itemデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'item.php';
// cartデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'cart.php';

// セッションを開始
session_start();

// ログインチェックし、ログインしていない場合ログインページにリダイレクト
if(is_logined() === false){
  redirect_to(LOGIN_URL);
}
// データベースに接続
$db = get_db_connect();
// ユーザーの情報を取得
$user = get_login_user($db);
// フォームからの情報受け取り
$token = get_post('token');
// ユーザーIDを参照し、カートのデータを取得
$carts = get_user_carts($db, $user['user_id']);
// 購入処理
if(is_valid_csrf_token($token) !== false ){
  if(purchase_carts($db, $carts) === false){
    set_error('商品が購入できませんでした。');
    redirect_to(CART_URL);
  } 
  insert_history($db,$user['user_id']);
  $history_id=$db->lastInsertId();
  for ($i = 0; $i < count($carts); $i++){
    insert_details($db,$history_id,$carts[$i]['item_id'],$carts[$i]['price'],$carts[$i]['amount']);
  }
}
// カートの合計金額を計算
$total_price = sum_carts($carts);
// Viewファイル読み込み
include_once '../view/finish_view.php';