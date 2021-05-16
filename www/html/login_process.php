<?php
// 定数ファイルを読み込み
require_once '../conf/const.php';
// 汎用関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';
// userデータに関する関数ファイルを読み込み
require_once MODEL_PATH . 'user.php';

// セッションを開始
session_start();

// ログインチェックし、ログインしている場合ホームにリダイレクト
if(is_logined() === true){
  redirect_to(HOME_URL);
}

// フォームからの情報受け取り
$name = get_post('name');
$password = get_post('password');
$token = get_post('token');
// データベースに接続
$db = get_db_connect();

// 名前とパスワードが合っているかの確認
if(is_valid_csrf_token($token) !== false ){
  $user = login_as($db, $name, $password);
  if( $user === false){
    set_error('ログインに失敗しました。');
    redirect_to(LOGIN_URL);
  }

  set_message('ログインしました。');
  // adminユーザーであれば、管理ページへリダイレクト
  if ($user['type'] === USER_TYPE_ADMIN){
    redirect_to(ADMIN_URL);
  }
}
// 一般ユーザーはホームへ
redirect_to(HOME_URL);