INSERT INTO `sp_tag` (`id`, `name`, `created_at`, `updated_at`)
VALUES
	(1, 'Smartphone', '2020-05-30 09:53:45', '2020-05-30 09:53:45'),
	(2, 'Tablet', '2020-05-30 09:53:56', '2020-05-30 09:53:56'),
	(3, 'Laptop', '2020-05-30 09:54:15', '2020-05-30 09:54:15'),
	(4, 'Sportschuh', '2020-05-30 10:04:08', '2020-05-30 10:04:08'),
	(5, 'Weiß', '2020-05-30 10:11:37', '2020-05-30 10:11:37'),
	(6, 'Schwarz', '2020-05-30 10:11:48', '2020-05-30 10:11:48'),
	(7, 'Grün', '2020-05-30 10:14:27', '2020-05-30 10:14:27');

INSERT INTO `sp_filter` (`id`, `name`, `rank`, `presentation`, `created_at`, `updated_at`, `is_online`)
VALUES
	(1, 'Technik', 0, 'radios', '2020-05-30 10:04:32', '2020-05-30 10:04:32', 1),
	(2, 'Schuhe', 1, 'dropdown', '2020-05-30 10:04:49', '2020-05-30 10:04:49', 1),
	(3, 'Farben', 2, 'radios', '2020-05-30 10:12:10', '2020-05-30 10:12:10', 1);

INSERT INTO `sp_tag_x_filter` (`tag_id`, `facet_id`, `show_in_frontend`, `rank`, `created_at`, `updated_at`)
VALUES
	(1, 1, 0, 0, '2020-05-30 10:04:32', '2020-05-30 10:04:32'),
	(2, 1, 0, 0, '2020-05-30 10:04:32', '2020-05-30 10:04:32'),
	(3, 1, 0, 0, '2020-05-30 10:04:32', '2020-05-30 10:04:32'),
	(4, 2, 0, 0, '2020-05-30 10:04:49', '2020-05-30 10:04:49'),
	(5, 3, 0, 0, '2020-05-30 10:12:10', '2020-05-30 10:12:10'),
	(6, 3, 0, 1, '2020-05-30 10:12:10', '2020-05-30 10:14:37'),
	(7, 3, 0, 2, '2020-05-30 10:14:37', '2020-05-30 10:14:37');

INSERT INTO `sp_discount_code` (`id`, `code`, `percent`, `expiration_date`, `used`, `created_at`, `updated_at`)
VALUES
	(1, 'sale-30', 30.00, '2030-01-01', 0, '2020-05-30 10:10:38', '2020-05-30 10:10:38');

INSERT INTO `sp_product` (`id`, `title`, `is_online`, `rank`, `shipping_price`, `description`, `popularity`, `created_at`, `updated_at`)
VALUES
	(1, 'iPhone SE', 1, 0, 10.00, '<p>Ein gro&szlig;artiges iPhone.&nbsp;Angefangen beim&nbsp;Preis.</p>\r\n', 0, '2020-05-30 09:56:22', '2020-05-30 09:56:22'),
	(2, "Nike Air Force 1 \'07", 1, 0, 0.00, '<p>Der Nike Air Force 1 &rsquo;07 ist ein Basketball-Original, das einen frischen Look erh&auml;lt. Er besticht mit bew&auml;hrten Details: geschmeidigem Leder, auff&auml;lligen Farben und dem gewissen Etwas, das dir Glanz verleiht.</p>\r\n\r\n<p>Style: CJ0952-400</p>\r\n', 0, '2020-05-30 10:03:51', '2020-05-30 10:05:16'),
	(3, 'Sticker', 1, 2, 1.50, '', 0, '2020-05-30 10:15:14', '2020-05-30 10:15:14'),
	(4, 'Test Produkt', 0, 2, 1.50, '', 0, '2020-05-30 10:15:14', '2020-05-30 10:15:14');

INSERT INTO `sp_tag_x_product` (`tag_id`, `product_id`, `created_at`, `updated_at`)
VALUES
	(1, 1, '2020-05-30 09:56:22', '2020-05-30 09:56:22'),
	(4, 2, '2020-05-30 10:05:16', '2020-05-30 10:05:16'),
	(5, 1, '2020-05-30 10:12:29', '2020-05-30 10:12:29'),
	(5, 2, '2020-05-30 10:12:32', '2020-05-30 10:12:32'),
	(5, 3, '2020-05-30 10:15:14', '2020-05-30 10:15:14'),
	(6, 1, '2020-05-30 10:12:25', '2020-05-30 10:12:25'),
	(6, 3, '2020-05-30 10:15:14', '2020-05-30 10:15:14'),
	(7, 3, '2020-05-30 10:15:14', '2020-05-30 10:15:14');

INSERT INTO `sp_variant` (`id`, `product_id`, `title`, `thumbnail_image`, `is_online`, `rank`, `price`, `hex_color`, `stock`, `sku`, `description`, `created_at`, `updated_at`)
VALUES
	(1, 1, '64 GB Weiß', '/product-variant-image.png', 1, 0, 479.00, '#ffffff', 120, 'iphone-se-64gb-white', '', '2020-05-30 09:59:00', '2020-05-30 10:07:59'),
	(2, 1, '64 GB Schwarz', '/product-variant-image.png', 0, 0, 479.00, '#000000', 80, 'iphone-se-64gb-black', '', '2020-05-30 09:59:50', '2020-05-30 10:07:48'),
	(3, 1, '128 GB Schwarz', '/product-variant-image.png', 1, 2, 529.00, '#000000', 20, 'iphone-se-128gb-black', '', '2020-05-30 10:01:22', '2020-05-30 10:08:15'),
	(4, 2, 'Weiß', '/product-variant-image.png', 1, 0, 100.00, '#ffffff', 5, 'nike-air-force-1-07-white', '', '2020-05-30 10:07:23', '2020-05-30 10:07:23'),
	(5, 3, 'Weiß', '/product-variant-image.png', 1, 0, 2.50, '#ffffff', 25, 'sticker-weiss', '', '2020-05-30 10:15:51', '2020-05-30 10:15:51'),
	(6, 3, 'Schwarz', '/product-variant-image.png', 1, 0, 1.50, '#000000', 16, 'sticker-schwarz', '', '2020-05-30 10:16:27', '2020-05-30 10:16:27'),
	(7, 3, 'Grün', '/product-variant-image.png', 1, 3, 1.50, '#00ff00', 0, 'sticker-gruen', '', '2020-05-30 10:17:12', '2020-05-30 10:17:23'),
	(8, 4, 'Test Variante', '/product-variant-image.png', 1, 3, 4.50, '#f0f0f0', 0, 'test-produkt-test-variante', '', '2020-05-30 10:17:12', '2020-05-30 10:17:23');

