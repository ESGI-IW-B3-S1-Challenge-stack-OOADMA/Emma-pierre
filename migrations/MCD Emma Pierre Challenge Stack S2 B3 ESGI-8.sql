CREATE TABLE `order` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `reference` varchar(255) UNIQUE,
  `shipping_address_id` int,
  `billing_address_id` int,
  `user_id` int,
  `coupon_id` int,
  `total` int,
  `status` ENUM ('paid', 'unpaid'),
  `stripe_id` varchar(255),
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp DEFAULT (now())
);

CREATE TABLE `order_item` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `order_id` int,
  `product_id` int,
  `quantity` int DEFAULT 1,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp DEFAULT (now())
);

CREATE TABLE `coupon` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255),
  `code` varchar(255),
  `percent` int,
  `duration` varchar(255),
  `duration_in_months` int,
  `valid` boolean,
  `stripe_id` varchar(255),
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp DEFAULT (now())
);

CREATE TABLE `favorite` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `user_id` int,
  `product_id` int,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp DEFAULT (now())
);

CREATE TABLE `product` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255),
  `description` text,
  `product_category_id` int,
  `jewelry_category_id` int,
  `price` int,
  `available` boolean,
  `stripe_id` varchar(255),
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp DEFAULT (now())
);

CREATE TABLE `product_image` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `product_id` int,
  `path` varchar(255),
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp DEFAULT (now()),
  `position` int
);

CREATE TABLE `product_category` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255),
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp DEFAULT (now())
);

CREATE TABLE `jewelry_category` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255),
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp DEFAULT (now())
);

CREATE TABLE `attribute_group` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255),
  `attribute_type` ENUM ('dropdown', 'radio_button', 'color'),
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp DEFAULT (now())
);

CREATE TABLE `attribute` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `data` varchar(255),
  `attribute_group_id` int,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp DEFAULT (now())
);

CREATE TABLE `user` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `address_id` int,
  `lastname` varchar(255),
  `firstname` varchar(255),
  `email` varchar(255),
  `phone_number` varchar(255),
  `password` varchar(255),
  `roles` json,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp DEFAULT (now())
);

CREATE TABLE `address` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255),
  `address_line1` varchar(255),
  `address_line2` varchar(255),
  `city` varchar(255),
  `postal_code` varchar(255),
  `country_id` int,
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp DEFAULT (now())
);

CREATE TABLE `country` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(255),
  `code` varchar(2),
  `created_at` timestamp DEFAULT (now()),
  `updated_at` timestamp DEFAULT (now())
);

ALTER TABLE `order` ADD FOREIGN KEY (`shipping_address_id`) REFERENCES `address` (`id`);

ALTER TABLE `order` ADD FOREIGN KEY (`billing_address_id`) REFERENCES `address` (`id`);

ALTER TABLE `order` ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

ALTER TABLE `order` ADD FOREIGN KEY (`coupon_id`) REFERENCES `coupon` (`id`);

ALTER TABLE `order_item` ADD FOREIGN KEY (`order_id`) REFERENCES `order` (`id`);

ALTER TABLE `order_item` ADD FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

ALTER TABLE `favorite` ADD FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

ALTER TABLE `favorite` ADD FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

ALTER TABLE `product` ADD FOREIGN KEY (`product_category_id`) REFERENCES `product_category` (`id`);

ALTER TABLE `product` ADD FOREIGN KEY (`jewelry_category_id`) REFERENCES `jewelry_category` (`id`);

ALTER TABLE `product_image` ADD FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE;

ALTER TABLE `attribute` ADD FOREIGN KEY (`attribute_group_id`) REFERENCES `attribute_group` (`id`);

ALTER TABLE `user` ADD FOREIGN KEY (`address_id`) REFERENCES `address` (`id`);

ALTER TABLE `address` ADD FOREIGN KEY (`country_id`) REFERENCES `country` (`id`);

CREATE TABLE `attribute_product` (
  `attribute_id` int,
  `product_id` int,
  PRIMARY KEY (`attribute_id`, `product_id`)
);

ALTER TABLE `attribute_product` ADD FOREIGN KEY (`attribute_id`) REFERENCES `attribute` (`id`);

ALTER TABLE `attribute_product` ADD FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE;

