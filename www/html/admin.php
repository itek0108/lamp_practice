<?php
// 定数ファイルを読み込み
require_once '../conf/const.php';
// セッションからユーザーIDを取得
require_once MODEL_PATH . 'functions.php';
// adminユーザーか確認し、違う場合はログインページに飛ばす
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
// トークンを作成
$token = get_csrf_token();
// adminユーザーか確認し、違う場合はログインページに飛ばす
if(is_admin($user) === false){
  redirect_to(LOGIN_URL);
}
// 商品の情報を取得
$items = get_all_items($db);
// Viewファイル読み込み
include_once VIEW_PATH . '/admin_view.php';
