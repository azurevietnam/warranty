CREATE TABLE IF NOT EXISTS `#__warranty_products` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`shop_name` VARCHAR(255)  NOT NULL ,
`shop_address` VARCHAR(255)  NOT NULL ,
`shop_region` VARCHAR(255)  NOT NULL ,
`sender` VARCHAR(255)  NOT NULL ,
`customer_id` int(11)  NOT NULL ,
`customer_name` VARCHAR(255)  NOT NULL ,
`customer_address` VARCHAR(255)  NOT NULL ,
`customer_phone` VARCHAR(255)  NOT NULL ,
`customer_note` TEXT  NOT NULL ,
`active` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
`model` VARCHAR(255)  NOT NULL ,
`color` VARCHAR(255)  NOT NULL ,
`imei` VARCHAR(255)  NOT NULL ,
`note` TEXT  NOT NULL ,
`image` VARCHAR(255)  NOT NULL ,
`sell_in` VARCHAR(255)  NOT NULL ,
`phone_status` TEXT  NOT NULL ,
`error` int(11)  NOT NULL ,
`modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'  NOT NULL ,
`modified_by` int(11)  NOT NULL ,
`created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'  NOT NULL ,
`created_by` int(11)  NOT NULL ,
`status` tinyint(1)  NOT NULL ,
`manufacturer` VARCHAR(255)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__warranty_models` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`code` VARCHAR(255)  NOT NULL ,
`name` VARCHAR(255)  NOT NULL ,
`product` INT(11)  NOT NULL ,
`modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'  NOT NULL ,
`modified_by` int(11)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__warranty_requests` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`customer_id` VARCHAR(255)  NOT NULL ,
`imei` VARCHAR(255)  NOT NULL ,
`status` tinyint(1)  NOT NULL,
`created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

INSERT INTO `#__warranty_models` VALUES ('1', '358614083', 'S3', '120');
INSERT INTO `#__warranty_models` VALUES ('2', '358614012', 'S3', '120');
INSERT INTO `#__warranty_models` VALUES ('3', '352678025', 'S5', '106');
INSERT INTO `#__warranty_models` VALUES ('4', '359423501', 'M6', '118');
INSERT INTO `#__warranty_models` VALUES ('5', '359423557', 'A10', '104');
INSERT INTO `#__warranty_models` VALUES ('6', '358206811', 'K-Fone', '112');
INSERT INTO `#__warranty_models` VALUES ('7', '359423558', 'Venus', '125');
INSERT INTO `#__warranty_models` VALUES ('8', '354939060', 'K200', '119');
INSERT INTO `#__warranty_models` VALUES ('9', '358332065', 'K110', '105');
INSERT INTO `#__warranty_models` VALUES ('10', '358332064', 'K110', '105');
INSERT INTO `#__warranty_models` VALUES ('11', '355200065', 'K100', '103');
INSERT INTO `#__warranty_models` VALUES ('12', '355226068', 'K100', '103');
INSERT INTO `#__warranty_models` VALUES ('13', '355099050', 'Mini 2', '126');

CREATE TABLE IF NOT EXISTS `#__warranty_specs` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`product` INT(11)  NOT NULL ,
`field1` VARCHAR(255)  NOT NULL ,
`field2` VARCHAR(255)  NOT NULL ,
`field3` VARCHAR(255)  NOT NULL ,
`field4` VARCHAR(255)  NOT NULL ,
`field5` VARCHAR(255)  NOT NULL ,
`field6` VARCHAR(255)  NOT NULL ,
`field7` VARCHAR(255)  NOT NULL ,
`field8` VARCHAR(255)  NOT NULL ,
`field9` VARCHAR(255)  NOT NULL ,
`field10` VARCHAR(255)  NOT NULL ,
`description` TEXT  NOT NULL ,
`modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'  NOT NULL ,
`modified_by` int(11)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

INSERT INTO `#__warranty_specs` VALUES ('1', '230', 'Màn hình 2.8inch', 'Hỗ trợ MP3, MP4', 'Hỗ trợ thẻ nhớ SD', 'Camera 0.3Mp', '2 SIM card', 'Bluetooth 2.0', 'GPRS', '', 'Pin 800mAh', '', '');
INSERT INTO `#__warranty_specs` VALUES ('2', '228', 'Tốc độ xử lý lõi tứ (Quad cores) 1.3GHz', 'Hệ điều hành Android 4.2.2', 'Màn hình 4.5inch QHD và tấm nền IPS', 'RAM 512 MB', 'Bộ nhớ trong 4GB', 'Hỗ trợ thẻ nhớ 32GB', 'Camera chính 5.0 Megapixels', 'Camera phụ 2.0 Megapixels', 'Dung lượng PIN 1500mAh', '2 SIM card (Mini SIM, Micro SIM)', '');
INSERT INTO `#__warranty_specs` VALUES ('3', '125', 'Tốc độ xử lý lõi tứ (Quad cores) 1.3 GHz', 'Hệ điều hành Android 4.4.2', 'Màn hình 4.7inch HD và tấm nền IPS', 'RAM 1 GB', 'Bộ nhớ trong 16GB', 'Hỗ trợ thể nhớ 32GB', 'Camera chính 13.0 Megapixels', 'Camera phụ 8.0 Megapixels', 'Dung lượng PIN 2000mAh', '', '');
INSERT INTO `#__warranty_specs` VALUES ('4', '120', 'Tốc độ xử lý lõi kép (Dual cores) 1.3 GHz', 'Hệ điều hành Android 4.4.2', 'Màn hình 4.0 WVGA và tấm nền IPS', 'RAM 512 MB', 'Bộ nhớ trong 4GB', 'Hỗ trợ thể nhớ 32GB', 'Camera chính 5.0 Megapixels', 'Camera phụ 2.0 Megapixels', 'Dung lượng PIN 1400mAh', '2 SIM card', '');
INSERT INTO `#__warranty_specs` VALUES ('5', '119', 'Màn hình 2.4inch QVGA TFT, LCD', 'Hỗ trợ MP3', 'Hỗ trợ thẻ nhớ SD', 'Camera 1.3 Megapixels', '2 SIM card', 'Bluetooth 2.0', 'GPRS', 'Bật nắp cá tính', '', '', '');
INSERT INTO `#__warranty_specs` VALUES ('6', '118', 'Tốc độ xử lý lõi tứ (Quad cores) 1.3GHz', 'Hệ điều hành Android 4.2.2', 'Màn hình 4.5inch QHD và tấm nền IPS', 'RAM 1 GB', 'Bộ nhớ trong 8GB', 'Hỗ trợ thẻ nhớ 32GB', 'Camera chính 8.0 Megapixels', 'Camera phụ 5.0 Megapixels', 'Dung lượng PIN 1400mAh', '', '');
INSERT INTO `#__warranty_specs` VALUES ('7', '112', 'Tốc độ xử lý lõi tứ (Quad Core) 1.3Ghz', 'Hệ điều hành Android 4.4', 'Màn hình 5.0inch QHD và tấm nền IPS', 'RAM 1 GB', 'Bộ nhớ trong 4GB', '', 'Camera chính 13.0 Megapixels', 'Camera phụ 8.0 Megapixels', '', '', '');
INSERT INTO `#__warranty_specs` VALUES ('8', '106', 'Tốc độ xử lý lõi kép (Dual cores) 1.2GHz', 'Hệ điều hành Android 4.2', 'Màn hình 4.0inch WVGA và tấm nền IPS', 'RAM 512 MB', 'Bộ nhớ trong 4GB', 'Hỗ trợ thẻ nhớ 32GB', 'Camera chính 2.0 Megapixels', 'Camera phụ 0.3 Megapixels', 'Dung lượng PIN 1200mAh', '2 SIM card', '');
INSERT INTO `#__warranty_specs` VALUES ('9', '105', 'Màn hình 2.4inch QVGA TFT, LCD', 'Hỗ trợ MP3', 'Hỗ trợ thể nhớ SD', 'Camera 1.3 Megapixels', '2 SIM card', 'Bluetooth 2.0', 'GPRS', 'Bàn phím cảm ứng ngoài', '', '', '');
INSERT INTO `#__warranty_specs` VALUES ('10', '104', 'Tốc độ xử lý lõi tứ (Quad core) 1.3GHz', 'Hệ điều hành Android 4.2.2', 'Màn hình 5.0inch HD và tấm nền IPS', 'RAM 1GB', 'Bộ nhớ trong 16GB', 'Hỗ trợ thể nhớ 32GB', 'Camera chính 13.0 Megapixels', 'Camera phụ 8.0 Megapixels', 'Dung lượng PIN 1800mAh', '', '');
INSERT INTO `#__warranty_specs` VALUES ('11', '103', 'Màn hình 2.4inch QVGA TFT, LCD', 'Hỗ trợ MP3', 'Hỗ trợ thể nhớ SD', 'Camera 1.3 Megapixels', '2 SIM card', 'Bluetooth 2.0', '', '', '', '', '');
INSERT INTO `#__warranty_specs` VALUES ('12', '126', 'Sắc màu thân thiện', 'Chức năng gọi thông minh', 'Danh bạ liên lạc', 'Hỗ trợ thẻ nhớ 32GB', '', '', '', '', '', '', '');

CREATE TABLE IF NOT EXISTS `#__warranty_errors` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`code` VARCHAR(255)  NOT NULL ,
`desc_tw` TEXT  NOT NULL ,
`desc_en` TEXT  NOT NULL ,
`desc_vi` TEXT  NOT NULL ,
`modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'  NOT NULL ,
`modified_by` int(11)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

INSERT INTO `#__warranty_errors` VALUES ('1', 'A01', '更換上折蓋', 'Change flip upside cover', 'Thay khung LCD (K100, K110, K200)', '2015-07-13 15:03:48', '');
INSERT INTO `#__warranty_errors` VALUES ('2', 'A02', '更換下折蓋', 'Change flip upderside cover', 'Thay khung bàn phím', '2015-07-13 15:04:16', '');
INSERT INTO `#__warranty_errors` VALUES ('3', 'A03', '更換前蓋', 'Change front cover', 'Thay nắp vỏ trước', '2015-07-13 15:04:37', '');
INSERT INTO `#__warranty_errors` VALUES ('4', 'A04', '更換後蓋', 'Change back cover', 'Thay khung sườn sau', '2015-07-13 15:05:09', '');
INSERT INTO `#__warranty_errors` VALUES ('5', 'A05', '更換電池蓋', 'Change battery cover', 'Thay nắp pin', '2015-07-13 15:05:38', '');
INSERT INTO `#__warranty_errors` VALUES ('6', 'A06', '更換殼料配件', 'Change case parts', 'Thay khung viền (Viền nhôm A10)', '2015-07-13 15:05:59', '');
INSERT INTO `#__warranty_errors` VALUES ('7', 'A07', '更換螢幕鏡片', 'Change LCD lens', 'Thay mặt kính màn hình (K100, k110, K200)', '2015-07-13 15:06:33', '');
INSERT INTO `#__warranty_errors` VALUES ('8', 'A08', '更換相機鏡片', 'Change camera lens', 'Thay mặt kính camera', '2015-07-13 15:06:56', '');
INSERT INTO `#__warranty_errors` VALUES ('9', 'A09', '更換閃光燈鏡片', 'Change flash-LED lens', 'Thay mặt kính đèn Led', '2015-07-13 15:07:44', '');
INSERT INTO `#__warranty_errors` VALUES ('10', 'A10', '更換按鍵', 'Change keypad', 'Thay bàn phím', '2015-07-13 15:08:10', '');
INSERT INTO `#__warranty_errors` VALUES ('11', 'A11', '更換電源按鍵', 'Change power button', 'Thay nút phím nguồn', '2015-07-13 15:08:30', '');
INSERT INTO `#__warranty_errors` VALUES ('12', 'A12', '更換音量鍵', 'Change volume button', 'Thay nút phím âm lượng', '2015-07-13 15:08:50', '');
INSERT INTO `#__warranty_errors` VALUES ('13', 'A13', '更換轉軸', 'Change rotator', 'Thay cơ xoay', '2015-07-13 15:09:10', '');
INSERT INTO `#__warranty_errors` VALUES ('14', 'B01', '更換聽筒 ', 'Change receiver', 'Thay loa trong', '2015-07-13 15:09:51', '');
INSERT INTO `#__warranty_errors` VALUES ('15', 'B02', '更換響鈴', 'Change speaker', 'Thay loa ngoài', '2015-07-13 15:10:13', '');
INSERT INTO `#__warranty_errors` VALUES ('16', 'B03', '更換麥克風', 'Change microphone', 'Thay míc', '2015-07-13 15:10:34', '');
INSERT INTO `#__warranty_errors` VALUES ('17', 'B04', '更換震動器', 'Change vibration', 'Thay rung', '2015-07-13 15:10:54', '');
INSERT INTO `#__warranty_errors` VALUES ('18', 'B05', '更換液晶', 'Change LCD', 'Thay màn hình LCD', '2015-07-13 15:11:16', '');
INSERT INTO `#__warranty_errors` VALUES ('19', 'B06', '更換觸控面板', 'Change Touch Panel', 'Thay cảm ứng', '2015-07-13 15:11:35', '');
INSERT INTO `#__warranty_errors` VALUES ('20', 'B07', '更換液晶模組', 'Change LCM (LCD+Touch Panel)', 'Thay nguyên bộ LCD + Cảm ứng (A10, M6)', '2015-07-13 15:11:54', '');
INSERT INTO `#__warranty_errors` VALUES ('21', 'B08', '更換液晶排線組', 'Change LCD FPC cable', 'Thay dây nguồn lcd\r\n', '2015-07-13 15:12:17', '');
INSERT INTO `#__warranty_errors` VALUES ('22', 'B09', '更換主相機模組', 'Change main camera', 'Thay camera chính', '2015-07-13 15:12:37', '');
INSERT INTO `#__warranty_errors` VALUES ('23', 'B10', '更換前相機模組', 'Change front camera', 'Thay camera trước', '2015-07-13 15:12:55', '');
INSERT INTO `#__warranty_errors` VALUES ('24', 'B11', '更換主機板', 'Change mainboard', 'Thay main', '2015-07-13 15:13:12', '');
INSERT INTO `#__warranty_errors` VALUES ('25', 'B12', '更換充電小板', 'Change charge board', 'Thay board sạc', '2015-07-13 15:13:31', '');
INSERT INTO `#__warranty_errors` VALUES ('26', 'B13', '更換按鍵機板', 'Change keyboard', 'Thay bàn phím', '2015-07-13 15:13:57', '');
INSERT INTO `#__warranty_errors` VALUES ('27', 'B14', '更換SIM CARD 座', 'Change SIM slot FPC', 'Thay hộp sim', '2015-07-13 15:14:14', '');
INSERT INTO `#__warranty_errors` VALUES ('28', 'B15', '更換MicroSD CARD 座', 'Change MicroSD slot FPC', 'Thay hộp thẻ nhớ', '2015-07-13 15:14:31', '');
INSERT INTO `#__warranty_errors` VALUES ('29', 'B16', '更換電源鍵排線', 'Change power button FPC', 'Thay cáp phím nguồn', '2015-07-13 15:14:48', '');
INSERT INTO `#__warranty_errors` VALUES ('30', 'B17', '更換音量鍵排線', 'Change volume button FPC', 'Thay cáp phím âm lượng', '2015-07-13 15:15:07', '');
INSERT INTO `#__warranty_errors` VALUES ('31', 'B18', '更換側鍵排線', 'Change side button FPC (power + volume)', 'Thay cáp phím nguồn + âm lượng', '2015-07-13 15:15:31', '');
INSERT INTO `#__warranty_errors` VALUES ('32', 'B19', '更換天線', 'Change antenna FPC', 'Đổi ăng ten FPC', '2015-07-13 15:15:48', '');
INSERT INTO `#__warranty_errors` VALUES ('33', 'B20', '更換同軸線', 'Change coaxial cable', 'Thay đổi cáp đồng trục', '2015-07-13 15:16:24', '');
INSERT INTO `#__warranty_errors` VALUES ('34', 'C01', '更換電池', 'Change battery', 'Đổi pin', '2015-07-13 15:16:47', '');
INSERT INTO `#__warranty_errors` VALUES ('35', 'C02', '更換免持聽筒', 'Change headphone', 'Đổi tai nghe', '2015-07-13 15:17:04', '');
INSERT INTO `#__warranty_errors` VALUES ('36', 'C03', '更換旅充', 'Change charge', 'Đổi cóc sạc', '2015-07-13 15:17:49', '');
INSERT INTO `#__warranty_errors` VALUES ('37', 'C04', '更換資料傳輸線', 'Change USB cable', 'Đổi cáp sạc usb', '2015-07-13 15:18:30', '');
INSERT INTO `#__warranty_errors` VALUES ('38', 'C05', '更單機', 'Change new phone', 'Đổi máy mới', '2015-07-13 15:18:47', '');
INSERT INTO `#__warranty_errors` VALUES ('39', 'E01', '摔機報價不修', 'Quote for drop, client reply to not repair', 'Điện thoại bị rớt, khách không đồng ý sữa chữa', '2015-07-13 15:19:32', '');
INSERT INTO `#__warranty_errors` VALUES ('40', 'E02', '滲水報價不修', 'Damp and offer, client reply to not repair', 'Điện thoại bị vô nước, khách không đồng ý sữa chữa', '2015-07-13 15:19:59', '');
INSERT INTO `#__warranty_errors` VALUES ('41', 'E03', '摔機修妥後不予保固', 'Drop,Adjustment, no warranty', 'Máy va đập, từ chối bảo hành', '2015-07-13 15:20:18', '');
INSERT INTO `#__warranty_errors` VALUES ('42', 'E04', '滲水修妥後不予保固', 'Damp,Adjustment, no warranty', 'Máy vô nước, từ chối bảo hành', '2015-07-13 15:20:34', '');
INSERT INTO `#__warranty_errors` VALUES ('43', 'E05', '單體零件報價不修', 'Quote for parts/accessory, client reply to not repair', 'Báo giá sửa chữa, khách không đồng ý sửa.', '2015-07-13 15:20:52', '');
INSERT INTO `#__warranty_errors` VALUES ('44', 'E06', '報價待回覆', 'Quote awaiting reply', 'Báo giá chờ khách trả lời', '2015-07-13 15:21:08', '');
INSERT INTO `#__warranty_errors` VALUES ('45', 'D01', '軟體昇級', 'Software upgrade', 'Chạy chương trình', '2015-07-13 15:21:24', '');
INSERT INTO `#__warranty_errors` VALUES ('46', 'D03', '測試正常', 'Test normal', 'Kiểm tra bình thường', '2015-07-13 15:21:41', '');
INSERT INTO `#__warranty_errors` VALUES ('47', 'D04', '調整', 'Adjustment', 'Chỉnh sửa', '2015-07-13 15:22:17', '');
INSERT INTO `#__warranty_errors` VALUES ('48', 'D05', '其它', 'Other', 'Sửa chữa khác', '2015-07-13 15:22:32', '');

CREATE TABLE IF NOT EXISTS `#__warranty_shops` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`code` VARCHAR(255)  NOT NULL ,
`name` VARCHAR(255)  NOT NULL ,
`address` VARCHAR(255)  NOT NULL ,
`phone` VARCHAR(255)  NOT NULL ,
`created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'  NOT NULL ,
`created_by` int(11)  NOT NULL ,
`modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'  NOT NULL ,
`modified_by` int(11)  NOT NULL ,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;

CREATE TABLE IF NOT EXISTS `#__warranty_warranties` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
`shop_id` int(11) UNSIGNED NOT NULL,
`imei` VARCHAR(255) NOT NULL ,
`accessories` text NOT NULL ,
`errors` text NOT NULL ,
`error_codes` VARCHAR(255) NOT NULL ,
`warranty` text NOT NULL ,
`note` text NOT NULL ,
`received` DATE NOT NULL DEFAULT '0000-00-00'  NOT NULL ,
`delivery` DATE NOT NULL DEFAULT '0000-00-00'  NOT NULL ,
`status` tinyint(1)  NOT NULL ,
`created` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'  NOT NULL ,
`created_by` int(11)  NOT NULL ,
`modified` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'  NOT NULL ,
`modified_by` int(11)  NOT NULL,
PRIMARY KEY (`id`)
) DEFAULT COLLATE=utf8_general_ci;