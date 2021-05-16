<?php
// 定数ファイルを読み込み
require_once '../conf/const.php';
// 汎用関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';
// userデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'user.php';
// itemデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'item.php';

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
// adminユーザーか確認し、違う場合はログインページに飛ばす
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}
// フォームからの情報受け取り
$item_id = get_post('item_id');
$stock = get_post('stock');
$token = get_post('token');
// 在庫数を変更し、メッセージを表示
if(is_valid_csrf_token($token) !== false ){
  if(update_item_stock($db, $item_id, $stock)){
    set_message('在庫数を変更しました。');
  } else {
    // エラーメッセージを表示
    set_error('在庫数の変更に失敗しました。');
  }
}
// リダイレクトしてページを更新
redirect_to(ADMIN_URL);