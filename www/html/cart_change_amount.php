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

// セッション開始
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
$cart_id = get_post('cart_id');
$amount = get_post('amount');
$token = get_csrf_token();

if(is_valid_csrf_token($token) !== false ){
  // カートを更新し、メッセージを表示
  if(update_cart_amount($db, $cart_id, $amount)){
    set_message('購入数を更新しました。');
  } else {
  // 失敗した場合はメッセージの内容を変える  
    set_error('購入数の更新に失敗しました。');
  }
}
// リダイレクトしてページを更新
redirect_to(CART_URL);