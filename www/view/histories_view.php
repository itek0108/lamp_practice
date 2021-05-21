<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>購入履歴</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'index.css'); ?>">
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  <h1>購入履歴一覧</h1>
  <?php include VIEW_PATH . 'templates/messages.php'; ?>
  <table>
    <tr>
      <th>注文番号</th>
      <th>購入日時</th>
      <th>合計金額</th>
      <th>購入明細</th>
    </tr>
    <?php foreach ($histories as $history){ ?>
    <tr>
      <td><?php print(h($history['history_id']));?></td>
      <td><?php print(h($history['created'])); ?></td>
      <td><?php print(h($history['total']));?>円</td>
      <td>
        <form method="post" action="details.php">
          <input type="submit" value="購入詳細表示">
          <input type="hidden" name="history_id" value="<?php print h($history['history_id']); ?>">
          <input type="hidden" name="token" value="<?php print h($token);?>">
          <input type="hidden" name="created" value="<?php print h($history['created']);?>">
          <input type="hidden" name="total" value="<?php print h($history['total']);?>">
        </form>
      </td>
    </tr>
    <?php } ?>
  </table>
</body>
</html>