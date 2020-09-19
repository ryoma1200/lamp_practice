
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;

START TRANSACTION;
SET time_zone = "+00:00";


--  -----------------------------------------------


CREATE TABLE `history` (
    `history_id` INT(11) NOT NULL,
    `user_id` INT(11) NOT NULL,
    `order_num` INT(11) NOT NULL,   /* histryテーブルへの追加処理の直前に、以前のorder_numを取得し、それに１加算したものをこちらに代入する */
    `create_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `history`
    ADD PRIMARY KEY (`history_id`),
    ADD KEY `user_id` (`user_id`);


ALTER TABLE `history`
    MODIFY `history_id` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;


--  -----------------------------------------------


CREATE TABLE `payment_statement` (
    `statement_id` INT(11) NOT NULL,
    `order_num` INT(11) NOT NULL,
    `item_id` INT(11) NOT NULL,
    `price` INT(11) NOT NULL,
    `amount` INT(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `payment_statement`
    ADD PRIMARY KEY (`statement_id`),
    ADD KEY `item_id` (`item_id`); 


ALTER TABLE `payment_statement`
    MODIFY `statement_id` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;


COMMIT;