CREATE TABLE `user_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` MEDIUMTEXT NOT NULL,
  `product_name` MEDIUMTEXT NOT NULL,
  `booking_date` varchar(50) NOT NULL,
  `user_name` MEDIUMTEXT NOT NULL,
  `user_mob` MEDIUMTEXT NOT NULL,
  `user_email` MEDIUMTEXT NOT NULL,
  `boarding_point` MEDIUMTEXT NOT NULL,
  `quantity` varchar(50) NOT NULL,
  `price` varchar(50) NOT NULL,
  `subtotal` varchar(50) NOT NULL,
  `gst` varchar(50) NOT NULL,
  `payment_type` MEDIUMTEXT DEFAULT NULL,
  `total` longtext DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
