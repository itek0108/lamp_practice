<!DOCTYPE html>
<html lang="ja">
<head>
  <?php include VIEW_PATH . 'templates/head.php'; ?>
  <title>購入明細</title>
  <link rel="stylesheet" href="<?php print(STYLESHEET_PATH . 'index.css'); ?>">
</head>
<body>
  <?php include VIEW_PATH . 'templates/header_logined.php'; ?>
  <h1>購入明細</h1>
  <?php include VIEW_PATH . 'templates/messages.php'; ?>
  <table>
    <tr>
      <th>注文番号</th>
      <th>購入日時</th>
      <th>合計金額</th>
    </tr>
    <tr>
      <td><?php print(h($history_id));?></td>
      <td><?php print(h($created)); ?></td>
      <td><?php print(h($total));?></td>
    </tr>
  </table>
  <table>
    <tr>
      <th>商品名</th>
      <th>価格</th>
      <th>購入数</th>
      <th>小計</th>
    </tr>
    <?php foreach ($details as $detail){ ?>
    <tr>
      <td><?php print(h($detail['name'])); ?></td>
      <td><?php print(h($detail['price'])); ?>円</td>
      <td><?php print(h($detail['amount'])); ?></td>
      <td><?php print(h($detail['price']*$detail['amount'])); ?>円</td>
    </tr>
  </table>
  <?php } ?>
</body>
</html>