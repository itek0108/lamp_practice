CREATE TABLE details(
  detail_id INT AUTO_INCREMENT NOT NULL,
  history_id INT NOT NULL,
  item_id INT NOT NULL,
  price INT NOT NULL,
  amount INT NOT NULL,
  primary key(detail_id)
);

CREATE TABLE histories(
  history_id INT AUTO_INCREMENT NOT NULL,
  user_id INT NOT NULL,
  created DATETIME NOT NULL,
  primary key(history_id)
);