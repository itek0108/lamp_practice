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
$name = get_post('name');
$price = get_post('price');
$status = get_post('status');
$stock = get_post('stock');
$image = get_file('image');
$token = get_csrf_token();
// 関数によって商品の登録を行い、結果によってメッセージの内容を変える
if(is_valid_csrf_token($token) !== false ){
  if(regist_item($db, $name, $price, $stock, $status, $image)){
    set_message('商品を登録しました。');
  }else {
    set_error('商品の登録に失敗しました。');
  }
}

// リダイレクトしてページを更新
redirect_to(ADMIN_URL);