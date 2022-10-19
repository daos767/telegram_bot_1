/*
 Navicat Premium Data Transfer

 Source Server         : !!!NAS
 Source Server Type    : MySQL
 Source Server Version : 50568
 Source Host           : nas.ds1.net.ua:3306
 Source Schema         : test-chat1

 Target Server Type    : MySQL
 Target Server Version : 50568
 File Encoding         : 65001

 Date: 19/10/2022 22:55:06
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for t_answers
-- ----------------------------
DROP TABLE IF EXISTS `t_answers`;
CREATE TABLE `t_answers`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` int(1) NULL DEFAULT NULL,
  `branch` int(5) NULL DEFAULT NULL,
  `step` int(5) NULL DEFAULT NULL,
  `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `url` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `go_to` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `is_end` int(1) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 14 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of t_answers
-- ----------------------------
INSERT INTO `t_answers` VALUES (1, 1, 0, 0, 'Привіт. Зараз як ніколи треба діяти злагоджено. Тільки так ми подолаємо ворога.\r\n\r\nДопоможіть нашій армії – повідомляйте адресу пересування військової техніки та військ окупантів. Вони допоможуть Штабу Збройних Сил України завдати гідну відсіч супротивнику ✊', NULL, NULL, NULL);
INSERT INTO `t_answers` VALUES (2, 2, 0, 0, '🇺🇦 Для підтвердження особистості пройдіть авторизацію через застосунок Дія.\r\n\r\n☝️ Попередньо потрібно оновити застосунок Дія до останньої версії.\r\n\r\n✅ Натискайте кнопку авторизації тільки у мобільному застосунку Telegram.\r\n\r\nℹ️ Якщо протягом 30 секунд ви не авторизувались через Дію, то потрібно згенерувати нове посилання.\r\n\r\nТисніть /start, щоб згенерувати нове посилання.', NULL, NULL, NULL);
INSERT INTO `t_answers` VALUES (3, 3, 1, 1, '🚛 Якщо ви побачили техніку чи окупантів, фотографуйте або знімайте на відео, долучайте геолокацію, щоб ми оперативно передали до ЗСУ.\r\n\r\n🇷🇺 Якщо ви дізналися, про колаборантів, які допомагають ворогу та відкрито виступають проти держави, повідомляйте нам.\r\n\r\n🧨 Якщо натрапили на вибухонебезпечні та підозрілі предмети, повідомляйте про них Державній службі з надзвичайних ситуацій.\r\n\r\n🩸 Проте якщо ви в Бучі, Ірпені чи Гостомелі, бачили російських військових, маєте їх фотографії або відео, подробиці про їх вчинки або вбивства – відправляйте нам.\r\n  \r\n🕹 Будь ласка, оберіть кнопками, про що саме ви б хотіли повідомити?', NULL, NULL, NULL);
INSERT INTO `t_answers` VALUES (6, 3, 15, 1, '👀 Звідки ви дізналися про ворога? Вкажіть, будь ласка, джерело даних\r\n\r\n❕ Нам дуже важливо знати, звідки надходить інформація', NULL, NULL, NULL);
INSERT INTO `t_answers` VALUES (7, 3, -1, -1, '❓ Ви бачили техніку, скупчення окупантів, російських солдатів в Київській області чи хотіли б повідомити про колаборантів?', NULL, NULL, NULL);
INSERT INTO `t_answers` VALUES (8, 3, 6, 1, '🏠 Оберіть, будь ласка, місто, в якому ви бачили російських солдатів', NULL, NULL, NULL);
INSERT INTO `t_answers` VALUES (9, 3, 21, 1, '📸 Надішліть фото або відео облич російських солдатів', NULL, NULL, NULL);
INSERT INTO `t_answers` VALUES (10, 4, 21, 1, 'Як це зробити?', 'https://test-chat1.ds1.net.ua/video/PHOTO_1.mp4', '{\"branch\":23,\"step\":1}', NULL);
INSERT INTO `t_answers` VALUES (11, 5, 22, 1, '✍️ Опишіть, що знаєте про цього солдата — ПІБ, військовий підрозділ, його дії в період вторгнення, відношення до оточуючих або вбивства мирних жителів\r\n\r\n☝️ Подробиці допоможуть знайти цих нелюдів', NULL, '{\"branch\":24,\"step\":1}', NULL);
INSERT INTO `t_answers` VALUES (12, 3, 23, 1, '☝️Якщо завантажили всі файли, то тисніть ➡️ Далі', NULL, NULL, NULL);
INSERT INTO `t_answers` VALUES (13, 6, 24, 1, '🙌 Дякуємо! Ваші дані дозволять знайти та покарати загарбників.', NULL, '{\"branch\":-1,\"step\":-1}', 1);

-- ----------------------------
-- Table structure for t_answers_buttons
-- ----------------------------
DROP TABLE IF EXISTS `t_answers_buttons`;
CREATE TABLE `t_answers_buttons`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` int(5) NULL DEFAULT NULL,
  `answer_id` int(5) NULL DEFAULT NULL,
  `row` int(1) NULL DEFAULT NULL,
  `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `url` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of t_answers_buttons
-- ----------------------------
INSERT INTO `t_answers_buttons` VALUES (1, 1, 2, 0, '✅ Авторизуватися', 'https://test-chat1.ds1.net.ua/');

-- ----------------------------
-- Table structure for t_answers_buttons_arguments
-- ----------------------------
DROP TABLE IF EXISTS `t_answers_buttons_arguments`;
CREATE TABLE `t_answers_buttons_arguments`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `button_id` int(5) NULL DEFAULT NULL,
  `argument` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `value` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of t_answers_buttons_arguments
-- ----------------------------
INSERT INTO `t_answers_buttons_arguments` VALUES (1, 1, 'action', 'authorization');
INSERT INTO `t_answers_buttons_arguments` VALUES (3, 1, 'chat_id', NULL);

-- ----------------------------
-- Table structure for t_answers_keyboards
-- ----------------------------
DROP TABLE IF EXISTS `t_answers_keyboards`;
CREATE TABLE `t_answers_keyboards`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `type` int(5) NULL DEFAULT NULL,
  `answer_id` int(5) NULL DEFAULT NULL,
  `row` int(1) NULL DEFAULT NULL,
  `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL,
  `md5` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `go_to` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 37 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of t_answers_keyboards
-- ----------------------------
INSERT INTO `t_answers_keyboards` VALUES (1, 2, 3, 0, '🚛 Техніку', '652183e59d1499b74094cf19bf76a1bd', '{\"branch\":1,\"step\":1}');
INSERT INTO `t_answers_keyboards` VALUES (3, 2, 3, 0, '🪖 Окупантів', 'a4f4d27ac4339cb55a7ac7254a6546ae', '{\"branch\":1,\"step\":1}');
INSERT INTO `t_answers_keyboards` VALUES (4, 2, 3, 1, '🇷🇺 Повідомити про колаборантів', '8332ab3a19f04d3b5f5bfcc017326545', '{\"branch\":1,\"step\":1}');
INSERT INTO `t_answers_keyboards` VALUES (5, 2, 3, 2, '🧨 Вибухонебезпечні та підозрілі предмети', '5b8a779ab880be19b15faf49e3039f6d', '{\"branch\":1,\"step\":1}');
INSERT INTO `t_answers_keyboards` VALUES (6, 2, 3, 3, '🩸 Вбивці в Бучі/Ірпіні/Гостомелі', 'b37212390668081a7232db153040d901', '{\"branch\":6,\"step\":1}');
INSERT INTO `t_answers_keyboards` VALUES (19, 2, 6, 0, 'Власні спостереження', '235887ddd352ab2eb594969490db38a7', '{\"branch\":1,\"step\":1}');
INSERT INTO `t_answers_keyboards` VALUES (20, 2, 6, 1, 'Повідомили знайомі', 'ab089c573fe148886c707ac33f57351d', '{\"branch\":1,\"step\":1}');
INSERT INTO `t_answers_keyboards` VALUES (21, 2, 6, 2, 'Соцмережі або чутки', 'b0d94308dab5731e0bc7fa8fcf32f5a5', '{\"branch\":1,\"step\":1}');
INSERT INTO `t_answers_keyboards` VALUES (22, 3, 6, 3, '↩️ Назад', 'bcf8a4bd588438d37b5a3beaa356407e', '{\"branch\":7,\"step\":1}');
INSERT INTO `t_answers_keyboards` VALUES (23, 2, 7, 0, '🚛 Техніку', '652183e59d1499b74094cf19bf76a1bd', '{\"branch\":2,\"step\":1}');
INSERT INTO `t_answers_keyboards` VALUES (24, 2, 7, 0, '🪖 Окупантів', 'a4f4d27ac4339cb55a7ac7254a6546ae', '{\"branch\":3,\"step\":1}');
INSERT INTO `t_answers_keyboards` VALUES (25, 2, 7, 1, '🇷🇺 Повідомити про колаборантів', '8332ab3a19f04d3b5f5bfcc017326545', '{\"branch\":4,\"step\":1}');
INSERT INTO `t_answers_keyboards` VALUES (26, 2, 7, 2, '🧨 Вибухонебезпечні та підозрілі предмети', '5b8a779ab880be19b15faf49e3039f6d', '{\"branch\":5,\"step\":1}');
INSERT INTO `t_answers_keyboards` VALUES (27, 2, 7, 3, '🩸 Вбивці в Бучі/Ірпіні/Гостомелі', 'b37212390668081a7232db153040d901', '{\"branch\":6,\"step\":1}');
INSERT INTO `t_answers_keyboards` VALUES (28, 2, 8, 0, 'Буча', 'ac9e57e31c4babc7d36a4008ab1fb573', '{\"branch\":21,\"step\":1}');
INSERT INTO `t_answers_keyboards` VALUES (29, 2, 8, 0, 'Ірпінь', '654dbb9170e6d37b5710d13498565916', '{\"branch\":21,\"step\":1}');
INSERT INTO `t_answers_keyboards` VALUES (30, 2, 8, 1, 'Гостомель', 'e6069dcf97eb9df8a693b24f509beb5c', '{\"branch\":21,\"step\":1}');
INSERT INTO `t_answers_keyboards` VALUES (31, 3, 8, 2, '↩️ Назад', 'bcf8a4bd588438d37b5a3beaa356407e', '{\"branch\":1,\"step\":1}');
INSERT INTO `t_answers_keyboards` VALUES (32, 2, 9, 0, '➡️ Пропустити', 'bcf582097bce9ce11532c124aa553656', '{\"branch\":22,\"step\":1}');
INSERT INTO `t_answers_keyboards` VALUES (33, 3, 9, 1, '↩️ Назад', 'bcf8a4bd588438d37b5a3beaa356407e', '{\"branch\":6,\"step\":1}');
INSERT INTO `t_answers_keyboards` VALUES (34, 3, 11, 0, '↩️ Назад', 'bcf8a4bd588438d37b5a3beaa356407e', '{\"branch\":21,\"step\":1}');
INSERT INTO `t_answers_keyboards` VALUES (35, 2, 12, 0, '➡️ Далі', 'e59da76a30f2b98710bc652cabd73cef', '{\"branch\":22,\"step\":1}');
INSERT INTO `t_answers_keyboards` VALUES (36, 3, 12, 1, '↩️ Назад', 'bcf8a4bd588438d37b5a3beaa356407e', '{\"branch\":21,\"step\":1}');

-- ----------------------------
-- Table structure for t_chats
-- ----------------------------
DROP TABLE IF EXISTS `t_chats`;
CREATE TABLE `t_chats`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `chat_id` int(10) NULL DEFAULT NULL,
  `token` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `branch` int(5) NULL DEFAULT NULL,
  `step` int(5) NULL DEFAULT NULL,
  `is_authorization` int(1) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of t_chats
-- ----------------------------

-- ----------------------------
-- Table structure for t_dialogs
-- ----------------------------
DROP TABLE IF EXISTS `t_dialogs`;
CREATE TABLE `t_dialogs`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `data_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `message_id` int(10) NULL DEFAULT NULL,
  `first_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `language_code` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `chat_id` int(10) NULL DEFAULT NULL,
  `type` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `date` int(10) NULL DEFAULT NULL,
  `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of t_dialogs
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
