<?php
require_once '../conf/const.php';
require_once MODEL_PATH . 'functions.php';
require_once MODEL_PATH . 'user.php';
require_once MODEL_PATH . 'item.php';
require_once MODEL_PATH . 'cart.php';
session_start();

if(is_logined() === false){
  redirect_to(LOGIN_URL);
}
$token=get_post('token');
if(is_valid_csrf_token($token)===false){
  redirect_to(LOGIN_URL);
}

$db = get_db_connect();
$user = get_login_user($db);

$history_id=get_post('history_id');
$created=get_post('created');
$total=get_post('total');

$histories = get_all_user_history($db);
$details = get_detail($db, $history_id);

include_once VIEW_PATH . 'details_view.php'; 