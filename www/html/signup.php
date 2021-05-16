<?php
// 定数ファイルを読み込み
require_once '../conf/const.php';
// 汎用関数ファイルを読み込み
require_once MODEL_PATH . 'functions.php';

// セッションを開始
session_start();

// ログインチェックし、ログインしている場合ホームにリダイレクト
if(is_logined() === true){
  redirect_to(HOME_URL);
}
// トークンを作成
$token = get_csrf_token();
// Viewファイル読み込み
include_once VIEW_PATH . 'signup_view.php';



