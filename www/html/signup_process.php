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
$password_confirmation = get_post('password_confirmation');

// データベースに接続
$db = get_db_connect();

// 名前、パスワード、パスワード（確認用）が正しい形式で入力されているか確認
try{
  $result = regist_user($db, $name, $password, $password_confirmation);
  // 正しい形式で入力されていない場合、エラーメッセージを表示
  if( $result=== false){
    set_error('ユーザー登録に失敗しました。');
    // ユーザー登録ページにリダイレクト
    redirect_to(SIGNUP_URL);
  }
}catch(PDOException $e){
  set_error('ユーザー登録に失敗しました。');
  // ユーザー登録ページにリダイレクト
  redirect_to(SIGNUP_URL);
}

set_message('ユーザー登録が完了しました。');
// ログインし、セッションにユーザーIDを保存
login_as($db, $name, $password);
// ホームにリダイレクト
redirect_to(HOME_URL);