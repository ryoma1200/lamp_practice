SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;

START TRANSACTION;
SET time_zone = "+00:00";


--  -----------------------------------------------


CREATE TABLE `order` (
    `order_id` INT(11) NOT NULL,
    `user_id` INT(11) NOT NULL,
    `create_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `order`
    ADD PRIMARY KEY (`order_id`),
    ADD KEY `user_id` (`user_id`);


ALTER TABLE `order`
    MODIFY `order_id` INT(11) NOT NULL AUTO_INCREMENT;


--  -----------------------------------------------


CREATE TABLE `order_item` (
    `order_item_id` INT(11) NOT NULL,
    `order_id` INT(11) NOT NULL,
    `item_id` INT(11) NOT NULL,
    `price` INT(11) NOT NULL,
    `amount` INT(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `order_item`
    ADD PRIMARY KEY (`order_item_id`),
    ADD KEY `order_id` (`order_id`),
    ADD KEY `item_id` (`item_id`); 


ALTER TABLE `order_item`
    ADD CONSTRAINT `restricted_order_id`        -- 制約の名前
    FOREIGN KEY (`order_id`)        -- どのキーに制約をつけるか
    REFERENCES `order` (`order_id`)        -- どのテーブルのどのカラムを参照するか
    ON DELETE RESTRICT ON UPDATE RESTRICT;       -- 参照元を delete or update しようとするとエラーになる


ALTER TABLE `order_item`
    MODIFY `order_item_id` INT(11) NOT NULL AUTO_INCREMENT;


COMMIT;