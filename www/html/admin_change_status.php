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
$changes_to = get_post('changes_to');
$token = get_post('token');
// 受け取ったステータスによって処理を分ける
if(is_valid_csrf_token($token) !== false ){
  if($changes_to === 'open'){
    update_item_status($db, $item_id, ITEM_STATUS_OPEN);
    set_message('ステータスを変更しました。');
  }else if($changes_to === 'close'){
    update_item_status($db, $item_id, ITEM_STATUS_CLOSE);
    set_message('ステータスを変更しました。');
  }else {
    // openかclose以外のときにエラーメッセージを表示する
    set_error('不正なリクエストです。');
  }
}
// リダイレクトしてページを更新
redirect_to(ADMIN_URL);