drop database if exists gg_store;
create database gg_store default character set utf8 collate utf8_general_ci;
drop user if exists 'crushers'@'localhost';
create user 'crushers'@'localhost' identified by 'crushggs@2025';
GRANT ALL ON gg_store.* to 'crushers'@'localhost';		
use gg_store;


-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- ホスト: 127.0.0.1
-- 生成日時: 2025-11-07 05:44:10
-- サーバのバージョン： 10.4.32-MariaDB
-- PHP のバージョン: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- データベース: `gg_store`
--

-- --------------------------------------------------------

--
-- テーブルの構造 `compatibility_rules`
--

CREATE TABLE `compatibility_rules` (
  `rule_id` int(11) NOT NULL,
  `platform_id` int(11) NOT NULL,
  `genre_id` int(11) DEFAULT NULL,
  `recommend_category_id` int(11) NOT NULL,
  `platform_genre_key` varchar(100) GENERATED ALWAYS AS (concat(`platform_id`,':',coalesce(cast(`genre_id` as char charset utf8mb4),'GENERAL'))) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- テーブルのデータのダンプ `compatibility_rules`
--

INSERT INTO `compatibility_rules` (`rule_id`, `platform_id`, `genre_id`, `recommend_category_id`) VALUES
(1, 1, 3, 2),
(2, 1, 11, 5),
(3, 2, 3, 1),
(4, 6, NULL, 5),
(5, 1, NULL, 1);

-- --------------------------------------------------------

--
-- ビュー用の代替構造 `database_check_constraints`
-- (実際のビューを参照するには下にあります)
--
CREATE TABLE `database_check_constraints` (
`table_name` varchar(64)
,`constraint_name` varchar(64)
,`check_clause` longtext
);

-- --------------------------------------------------------

--
-- テーブルの構造 `gg_admins`
--

CREATE TABLE `gg_admins` (
  `admin_id` int(11) NOT NULL,
  `login_name` varchar(100) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `firstname_kana` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `lastname_kana` varchar(100) NOT NULL,
  `mailaddress` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `creation_date` datetime NOT NULL DEFAULT current_timestamp(),
  `update_date` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- テーブルのデータのダンプ `gg_admins`
--

INSERT INTO `gg_admins` (`admin_id`, `login_name`, `firstname`, `firstname_kana`, `lastname`, `lastname_kana`, `mailaddress`, `password`, `creation_date`, `update_date`) VALUES
(1, 'admin_master', '管理', 'カンリ', '主', 'シュ', 'admin@example.com', '$2y$10$hashedAdminPasswordHere1', '2025-10-27 11:23:50', NULL),
(2, 'ito_support', '一郎', 'イチロウ', '伊藤', 'イトウ', 'support-ito@example.com', '$2y$10$hashedAdminPasswordHere2', '2025-10-27 11:23:50', NULL),
(3, 'system_ops', '二郎', 'ジロウ', '加藤', 'カトウ', 'ops-kato@example.co.jp', '$2y$10$hashedAdminPasswordHere3', '2025-10-27 11:23:50', NULL);

-- --------------------------------------------------------

--
-- テーブルの構造 `gg_carts`
--

CREATE TABLE `gg_carts` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `session_id` varchar(50) DEFAULT NULL,
  `game_id` int(11) DEFAULT NULL,
  `gadget_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `creation_date` datetime NOT NULL DEFAULT current_timestamp(),
  `update_date` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `cart_item_key` varchar(255) GENERATED ALWAYS AS (concat(coalesce(concat('U:',`user_id`),concat('S:',`session_id`)),':',coalesce(concat('G:',`game_id`),concat('D:',`gadget_id`)))) STORED
) ;

--
-- テーブルのデータのダンプ `gg_carts`
--

INSERT INTO `gg_carts` (`cart_id`, `user_id`, `session_id`, `game_id`, `gadget_id`, `quantity`, `creation_date`, `update_date`) VALUES
(1, 1, NULL, 1, NULL, 1, '2025-11-06 12:11:39', NULL),
(2, 1, NULL, NULL, 3, 1, '2025-11-06 12:11:39', NULL),
(3, 2, NULL, 3, NULL, 2, '2025-11-06 12:11:39', NULL),
(4, NULL, 'abc123xyz789_guest_session_id', NULL, 1, 1, '2025-11-06 12:11:39', NULL),
(5, NULL, 'efg456hij000_another_session', 3, NULL, 1, '2025-11-06 12:11:39', NULL);

-- --------------------------------------------------------

--
-- テーブルの構造 `gg_category`
--

CREATE TABLE `gg_category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- テーブルのデータのダンプ `gg_category`
--

INSERT INTO `gg_category` (`category_id`, `category_name`) VALUES
(8, 'ウェブカメラ'),
(9, 'キャプチャーボード'),
(3, 'ゲーミングキーボード'),
(6, 'ゲーミングチェア'),
(2, 'ゲーミングマウス'),
(5, 'コントローラー'),
(1, 'ヘッドセット'),
(7, 'マイク'),
(4, 'マウスパッド');

-- --------------------------------------------------------

--
-- テーブルの構造 `gg_delivery`
--

CREATE TABLE `gg_delivery` (
  `delivery_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `postal_code` varchar(20) NOT NULL,
  `address` varchar(200) NOT NULL,
  `mail_address` varchar(255) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `creation_date` datetime NOT NULL DEFAULT current_timestamp(),
  `update_date` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- テーブルのデータのダンプ `gg_delivery`
--

INSERT INTO `gg_delivery` (`delivery_id`, `user_id`, `postal_code`, `address`, `mail_address`, `phone_number`, `creation_date`, `update_date`) VALUES
(1, 1, '100-0001', '東京都千代田区千代田1-1', 'taro.tanaka@example.com', '090-1234-5678', '2025-10-27 11:40:39', NULL),
(2, 1, '105-0011', '東京都港区芝公園4-2-8 (勤務先)', 'taro.tanaka@example.com', '03-1111-2222', '2025-10-27 11:40:39', NULL),
(3, 2, '540-0002', '大阪府大阪市中央区大阪城1-1', 'hanako.suzuki@example.net', '080-9876-5432', '2025-10-27 11:40:39', NULL),
(4, 3, '604-8001', '京都府京都市中京区河原町通三条上る (実家)', 'kenji.sato@example.org', '075-123-4567', '2025-10-27 11:40:39', NULL);

-- --------------------------------------------------------

--
-- テーブルの構造 `gg_detail_orders`
--

CREATE TABLE `gg_detail_orders` (
  `detail_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `game_id` int(11) DEFAULT NULL,
  `gadget_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `points_earned_item` int(11) NOT NULL DEFAULT 0
) ;

--
-- テーブルのデータのダンプ `gg_detail_orders`
--

INSERT INTO `gg_detail_orders` (`detail_id`, `order_id`, `game_id`, `gadget_id`, `quantity`, `unit_price`, `points_earned_item`) VALUES
(1, 1, 1, NULL, 1, 8900.00, 89),
(2, 1, 3, NULL, 1, 8400.00, 84),
(3, 2, 3, NULL, 1, 5980.00, 59),
(4, 3, NULL, 2, 1, 11800.00, 118),
(5, 4, 2, NULL, 1, 7980.00, 79);

-- --------------------------------------------------------

--
-- テーブルの構造 `gg_favorite`
--

CREATE TABLE `gg_favorite` (
  `favorite_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `game_id` int(11) DEFAULT NULL,
  `gadget_id` int(11) DEFAULT NULL,
  `favorite_date` datetime NOT NULL DEFAULT current_timestamp(),
  `user_product_key` varchar(100) GENERATED ALWAYS AS (concat(`user_id`,':',coalesce(concat('game:',`game_id`),concat('gadget:',`gadget_id`)))) STORED
) ;

--
-- テーブルのデータのダンプ `gg_favorite`
--

INSERT INTO `gg_favorite` (`favorite_id`, `user_id`, `game_id`, `gadget_id`, `favorite_date`) VALUES
(1, 1, 1, NULL, '2025-11-06 12:20:59'),
(2, 1, NULL, 1, '2025-11-06 12:20:59'),
(3, 2, 3, NULL, '2025-11-06 12:20:59'),
(4, 2, 6, NULL, '2025-11-06 12:20:59'),
(5, 3, NULL, 2, '2025-11-06 12:20:59'),
(6, 3, 1, NULL, '2025-11-06 12:20:59');

-- --------------------------------------------------------

--
-- テーブルの構造 `gg_gadget`
--

CREATE TABLE `gg_gadget` (
  `gadget_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `gadget_name` varchar(255) NOT NULL,
  `gadget_explanation` text NOT NULL,
  `manufacturer` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `Sales_Status` int(11) NOT NULL,
  `created_time` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_time` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- テーブルのデータのダンプ `gg_gadget`
--

INSERT INTO `gg_gadget` (`gadget_id`, `category_id`, `gadget_name`, `gadget_explanation`, `manufacturer`, `price`, `stock`, `Sales_Status`, `created_time`, `updated_time`) VALUES
(1, 2, 'Logicool G Pro X Superlight', 'プロ仕様の超軽量ワイヤレスゲーミングマウス。HERO 25Kセンサーを搭載し、高精度なトラッキングを実現。', 'Logicool', 17800.00, 30, 1, '2025-10-27 11:36:32', NULL),
(2, 1, 'Razer BlackShark V2', 'eスポーツ向けの有線ゲーミングヘッドセット。優れた遮音性とマイク品質、THX Spatial Audioに対応。', 'Razer', 11800.00, 45, 1, '2025-10-27 11:36:32', NULL),
(3, 3, 'SteelSeries Apex Pro TKL', 'テンキーレスのメカニカルキーボード。アクチュエーションポイントを調整可能なOmniPointスイッチを搭載。', 'SteelSeries', 25000.00, 15, 1, '2025-10-27 11:36:32', NULL),
(4, 5, 'Xbox Elite ワイヤレス コントローラー シリーズ 2', '交換可能なスティックや背面パドルを備えた、プロ仕様のカスタマイズ可能なコントローラー。', 'Microsoft', 19980.00, 20, 1, '2025-10-27 11:36:32', NULL),
(5, 7, 'Shure MV7', 'ポッドキャストや配信に最適なダイナミックマイク。USBとXLRの両方に対応し、クリアな音質を提供。', 'Shure', 31000.00, 10, 1, '2025-10-27 11:36:32', NULL),
(6, 2, 'ZOWIE EC2-C', 'eスポーツ、特にFPSプレイヤーに人気の有線ゲーミングマウス。エルゴノミクスデザインが特徴。', 'BenQ ZOWIE', 8800.00, 0, 0, '2025-10-27 11:36:32', NULL),
(7, 2, 'Logicool G PRO X 2 LIGHTSPEED', '初のグラフェンドライバー「PRO-Gグラフェンドライバー」搭載、「PRO X 2 LIGHTSPEED ワイヤレスゲーミングヘッドセット」に保証期間1年間のAmazon.co.jp限定モデルが登場！
PRO-Gグラフェンドライバー搭載で複数の音を認知。安定した高音質を楽しむことができます。最大連続使用時間50時間、前機種に比べ2倍を超える電池寿命に向上しました。
さらに25gの軽量化も実現し、より長時間のプレイでも疲れにくくなりました。
', 'Logicool', 32500, 41, 1, now(), NULL);

-- --------------------------------------------------------

--
-- テーブルの構造 `gg_gadget_specs`
--

CREATE TABLE `gg_gadget_specs` (
  `gadget_spec_id` int(11) NOT NULL,
  `gadget_id` int(11) NOT NULL,
  `spec_id` int(11) NOT NULL,
  `spec_value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- テーブルのデータのダンプ `gg_gadget_specs`
--

INSERT INTO `gg_gadget_specs` (`gadget_spec_id`, `gadget_id`, `spec_id`, `spec_value`) VALUES
(1, 1, 6, '63'),
(2, 1, 8, 'HERO 25K'),
(3, 1, 9, '100 - 25,600'),
(4, 1, 11, '70'),
(5, 1, 13, '2'),
(6, 2, 6, '262'),
(7, 2, 12, '1.8'),
(8, 2, 13, '2'),
(9, 3, 10, 'OmniPoint'),
(10, 3, 12, '2.0'),
(11, 3, 13, '1');

-- --------------------------------------------------------

--
-- テーブルの構造 `gg_game`
--

CREATE TABLE `gg_game` (
  `game_id` int(11) NOT NULL,
  `game_name` varchar(255) NOT NULL,
  `game_explanation` text NOT NULL,
  `platform_id` int(11) NOT NULL,
  `manufacturer` varchar(100) NOT NULL,
  `game_type` varchar(20) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) DEFAULT NULL,
  `Sales_Status` int(11) DEFAULT NULL,
  `created_time` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_time` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- テーブルのデータのダンプ `gg_game`
--

INSERT INTO `gg_game` (`game_id`, `game_name`, `game_explanation`, `platform_id`, `manufacturer`, `game_type`, `price`, `stock`, `Sales_Status`, `created_time`, `updated_time`) VALUES
(1, 'Elden Ring', 'フロム・ソフトウェアが開発した大人気アクションRPG。広大な「狭間の地」を舞台に、王を目指す冒険が繰り広げられる。', 2, 'フロム・ソフトウェア', 'Physical', 8900.00, 50, 1, '2025-10-27 11:32:36', NULL),
(2, 'Cyberpunk 2077', '未来都市ナイトシティを舞台にしたオープンワールドRPG。危険と退廃、そしてテクノロジーが支配する世界を生き抜け。', 1, 'CD Projekt Red', 'Digital', 7980.00, NULL, 1, '2025-10-27 11:32:36', NULL),
(3, 'あつまれ どうぶつの森', '無人島に移住し、住民たちとの交流や島の開発を楽しむコミュニケーションゲーム。スローライフを満喫しよう。', 6, '任天堂', 'Physical', 5980.00, 150, 1, '2025-10-27 11:32:36', NULL),
(4, 'Forza Horizon 5', 'メキシコの広大で美しいオープンワールドを舞台にした、究極のレーシング体験。数百台の名車で駆け抜けろ。', 4, 'Microsoft Studios', 'Digital', 7500.00, NULL, 1, '2025-10-27 11:32:36', NULL),
(5, 'Stardew Valley', '荒廃した祖父の農場を受け継ぎ、田舎町での新しい生活を始めるインディー・ファーミングシミュレーション。', 1, 'ConcernedApe', 'Digital', 1480.00, NULL, 1, '2025-10-27 11:32:36', NULL),
(6, 'ゼルダの伝説 ティアーズ オブ ザ キングダム', '「ブレス オブ ザ ワイルド」の続編。ハイラルの大地と、遥か上空に広がる空島を舞台にしたアクションアドベンチャー。', 6, '任天堂', 'Physical', 7200.00, 0, 0, '2025-10-27 11:32:36', NULL);

-- --------------------------------------------------------

--
-- テーブルの構造 `gg_game_genres`
--

CREATE TABLE `gg_game_genres` (
  `game_id` int(11) NOT NULL,
  `genre_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- テーブルのデータのダンプ `gg_game_genres`
--

INSERT INTO `gg_game_genres` (`game_id`, `genre_id`) VALUES
(1, 1),
(1, 2),
(2, 1),
(2, 2),
(2, 3),
(3, 5),
(4, 9),
(4, 10),
(5, 2),
(5, 5),
(6, 1),
(6, 2),
(6, 7);

-- --------------------------------------------------------

--
-- テーブルの構造 `gg_game_requirements`
--

CREATE TABLE `gg_game_requirements` (
  `game_req_id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `spec_id` int(11) NOT NULL,
  `spec_value` varchar(255) NOT NULL,
  `type` varchar(10) NOT NULL
) ;

--
-- テーブルのデータのダンプ `gg_game_requirements`
--

INSERT INTO `gg_game_requirements` (`game_req_id`, `game_id`, `spec_id`, `spec_value`, `type`) VALUES
(1, 2, 1, 'Windows 10 64-bit', 'MIN'),
(2, 2, 3, '8', 'MIN'),
(3, 2, 2, 'Intel Core i5-3570K or AMD FX-8310', 'MIN'),
(4, 2, 4, '70', 'MIN'),
(5, 2, 5, '12', 'MIN'),
(6, 2, 1, 'Windows 10 64-bit', 'REC'),
(7, 2, 3, '12', 'REC'),
(8, 2, 2, 'Intel Core i7-6700 or AMD Ryzen 5 1600', 'REC'),
(9, 2, 4, '70 (SSD Recommended)', 'REC'),
(10, 2, 5, '12', 'REC'),
(11, 5, 1, 'Windows Vista or greater', 'MIN'),
(12, 5, 3, '2', 'MIN'),
(13, 5, 2, '2.0 GHz', 'MIN'),
(14, 5, 4, '1', 'MIN'),
(15, 5, 5, '10', 'MIN');

-- --------------------------------------------------------

--
-- テーブルの構造 `gg_genres`
--

CREATE TABLE `gg_genres` (
  `genre_id` int(11) NOT NULL,
  `genre_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- テーブルのデータのダンプ `gg_genres`
--

INSERT INTO `gg_genres` (`genre_id`, `genre_name`) VALUES
(3, 'FPS'),
(2, 'RPG'),
(4, 'TPS'),
(1, 'アクション'),
(7, 'アドベンチャー'),
(5, 'シミュレーション'),
(6, 'ストラテジー'),
(9, 'スポーツ'),
(8, 'パズル'),
(12, 'ホラー'),
(10, 'レース'),
(11, '格闘');

-- --------------------------------------------------------

--
-- テーブルの構造 `gg_media`
--

CREATE TABLE `gg_media` (
  `media_id` int(11) NOT NULL,
  `game_id` int(11) DEFAULT NULL,
  `gadget_id` int(11) DEFAULT NULL,
  `url` varchar(250) NOT NULL,
  `type` varchar(30) NOT NULL,
  `is_primary` tinyint(1) NOT NULL
) ;

--
-- テーブルのデータのダンプ `gg_media`
--

INSERT INTO `gg_media` (`media_id`, `game_id`, `gadget_id`, `url`, `type`, `is_primary`) VALUES
(1, NULL, 1, 'gadget-images/gadgets-1_1.jpg', 'image', 1),
(2, NULL, 2, 'gadget-images/gadgets-2_1.jpg', 'image', 1),
(3, NULL, 3, 'gadget-images/gadgets-3_1.jpg', 'image', 1),
(4, NULL, 4, 'gadget-images/gadgets-4_1.jpg', 'image', 1),
(5, NULL, 5, 'gadget-images/gadgets-5_1.jpg', 'image', 1),
(6, NULL, 6, 'gadget-images/gadgets-6_1.jpg', 'image', 1),
(7, NULL, 7, 'gadget-images/gadgets-7_1.jpg', 'image', 1),
(8, NULL, 7, 'gadget-images/gadgets-7_2.jpg', 'image', 0),
(9, NULL, 7, 'gadget-images/gadgets-7_3.jpg', 'image', 0),
(10, NULL, 7, 'gadget-images/gadgets-7_4.jpg', 'image', 0),
(11, NULL, 7, 'gadget-images/gadgets-7_5.jpg', 'image', 0),
(12, NULL, 7, 'gadget-images/gadgets-7_6.jpg', 'image', 0),
(13, NULL, 7, 'gadget-images/gadgets-7_7.jpg', 'image', 0),
(14, NULL, 7, 'gadget-images/gadgets-7_8.jpg', 'image', 0);


-- --------------------------------------------------------

--
-- テーブルの構造 `gg_orders`
--

CREATE TABLE `gg_orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `points_earned` int(11) NOT NULL DEFAULT 0,
  `points_redeemed` int(11) NOT NULL DEFAULT 0,
  `shipping_address` varchar(200) NOT NULL,
  `shipping_postal_code` varchar(10) NOT NULL,
  `shipping_mail_address` varchar(255) NOT NULL,
  `shipping_phone_number` varchar(20) NOT NULL,
  `order_status` varchar(50) NOT NULL DEFAULT 'Pending',
  `creation_date` datetime NOT NULL DEFAULT current_timestamp(),
  `update_date` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- テーブルのデータのダンプ `gg_orders`
--

INSERT INTO `gg_orders` (`order_id`, `user_id`, `total`, `points_earned`, `points_redeemed`, `shipping_address`, `shipping_postal_code`, `shipping_mail_address`, `shipping_phone_number`, `order_status`, `creation_date`, `update_date`) VALUES
(1, 1, 17300.00, 173, 500, '東京都千代田区千代田1-1', '100-0001', 'taro.tanaka@example.com', '090-1234-5678', 'Delivered', '2025-10-27 11:52:15', NULL),
(2, 2, 5980.00, 59, 0, '大阪府大阪市中央区大阪城1-1', '540-0002', 'hanako.suzuki@example.net', '080-9876-5432', 'Shipped', '2025-10-27 11:52:15', NULL),
(3, 1, 11800.00, 118, 0, '東京都港区芝公園4-2-8 (勤務先)', '105-0011', 'taro.tanaka@example.com', '03-1111-2222', 'Pending', '2025-10-27 11:52:15', NULL),
(4, 3, 7980.00, 79, 0, '京都府京都市中京区', '604-8001', 'kenji.sato@example.org', '075-123-4567', 'Delivered', '2025-10-27 11:52:15', NULL);

-- --------------------------------------------------------

--
-- テーブルの構造 `gg_platforms`
--

CREATE TABLE `gg_platforms` (
  `platform_id` int(11) NOT NULL,
  `platform_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- テーブルのデータのダンプ `gg_platforms`
--

INSERT INTO `gg_platforms` (`platform_id`, `platform_name`) VALUES
(8, 'Android'),
(7, 'iOS'),
(6, 'Nintendo Switch'),
(1, 'PC'),
(3, 'PlayStation 4'),
(2, 'PlayStation 5'),
(5, 'Xbox One'),
(4, 'Xbox Series X/S');

-- --------------------------------------------------------

--
-- テーブルの構造 `gg_points`
--

CREATE TABLE `gg_points` (
  `user_id` int(11) NOT NULL,
  `points` int(11) NOT NULL,
  `last_update_date` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- テーブルのデータのダンプ `gg_points`
--

INSERT INTO `gg_points` (`user_id`, `points`, `last_update_date`) VALUES
(1, 791, '2024-10-20 14:30:00'),
(2, 159, '2024-09-15 11:00:00'),
(3, 79, '2024-08-05 09:12:00'),
(4, 0, '2024-01-10 18:00:00'),
(5, 0, '2024-02-11 20:00:00');

-- --------------------------------------------------------

--
-- テーブルの構造 `gg_point_transactions`
--

CREATE TABLE `gg_point_transactions` (
  `transaction_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `points_change` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- テーブルの構造 `gg_premium`
--

CREATE TABLE `gg_premium` (
  `user_id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `current_discount` decimal(4,2) NOT NULL,
  `new_discount` decimal(4,2) NOT NULL,
  `creation_date` datetime NOT NULL DEFAULT current_timestamp(),
  `update_date` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- テーブルのデータのダンプ `gg_premium`
--

INSERT INTO `gg_premium` (`user_id`, `is_active`, `start_date`, `end_date`, `current_discount`, `new_discount`, `creation_date`, `update_date`) VALUES
(1, 1, '2024-11-01 00:00:00', '2025-11-01 00:00:00', 5.00, 5.00, '2025-10-27 11:56:32', NULL),
(2, 1, '2025-01-15 00:00:00', '2026-01-15 00:00:00', 7.50, 10.00, '2025-10-27 11:56:32', NULL),
(3, 0, '2023-09-01 00:00:00', '2024-09-01 00:00:00', 5.00, 0.00, '2025-10-27 11:56:32', NULL);

-- --------------------------------------------------------

--
-- テーブルの構造 `gg_reviews`
--

CREATE TABLE `gg_reviews` (
  `review_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `game_id` int(11) DEFAULT NULL,
  `gadget_id` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `review_date` datetime NOT NULL DEFAULT current_timestamp(),
  `user_product_key` varchar(100) GENERATED ALWAYS AS (concat(`user_id`,':',coalesce(concat('game:',`game_id`),concat('gadget:',`gadget_id`)))) STORED
) ;

--
-- テーブルのデータのダンプ `gg_reviews`
--

INSERT INTO `gg_reviews` (`review_id`, `user_id`, `rating`, `game_id`, `gadget_id`, `comment`, `review_date`) VALUES
(1, 1, 5, 1, NULL, '最高のゲーム体験。難易度は高いが、達成感が素晴らしい。グラフィックも音楽も文句なし。', '2024-03-15 10:30:00'),
(2, 2, 4, 3, NULL, '毎日少しずつプレイするのが楽しい。癒やされます。ただ、もう少しイベントが多いと嬉しい。', '2024-05-20 18:45:00'),
(3, 3, 5, NULL, 2, 'FPSゲームで使用。足音が非常によく聞こえる。マイクの音質もクリアで、仲間からも好評。', '2024-09-01 12:00:00'),
(4, 1, 4, NULL, 1, '本当に軽い。ワイヤレスで遅延も感じない。ただし、価格が少し高いので星4つ。', '2024-10-10 21:00:00'),
(5, 2, 3, 2, NULL, 'アップデートでかなり良くなったと聞いて購入。世界観は好きだが、まだバグが残っている。', '2024-10-25 14:15:00'),
(6, 4, 2, 2, NULL, 'too much lags', '2025-11-04 14:06:12'),
(8, 1, 4, 2, NULL, 'cool game it was fantastic.', '2025-11-04 17:20:35');

-- --------------------------------------------------------

--
-- テーブルの構造 `gg_specifications`
--

CREATE TABLE `gg_specifications` (
  `spec_id` int(11) NOT NULL,
  `spec_name` varchar(100) NOT NULL,
  `unit` varchar(10) DEFAULT NULL,
  `product_type` varchar(10) NOT NULL
) ;

--
-- テーブルのデータのダンプ `gg_specifications`
--

INSERT INTO `gg_specifications` (`spec_id`, `spec_name`, `unit`, `product_type`) VALUES
(1, 'Minimum OS', NULL, 'GAME'),
(2, 'Recommended CPU', NULL, 'GAME'),
(3, 'Minimum RAM', 'GB', 'GAME'),
(4, 'Storage Space', 'GB', 'GAME'),
(5, 'DirectX Version', NULL, 'GAME'),
(6, 'Weight', 'g', 'GADGET'),
(7, 'Dimensions', 'mm', 'GADGET'),
(8, 'Sensor Type', NULL, 'GADGET'),
(9, 'DPI', NULL, 'GADGET'),
(10, 'Key Switch Type', NULL, 'GADGET'),
(11, 'Battery Life', 'hours', 'GADGET'),
(12, 'Cable Length', 'm', 'GADGET'),
(13, 'Warranty', 'years', 'BOTH');

-- --------------------------------------------------------

--
-- ビュー用の代替構造 `gg_unique_constraints`
-- (実際のビューを参照するには下にあります)
--
CREATE TABLE `gg_unique_constraints` (
`table_name` varchar(64)
,`constraint_name` varchar(64)
,`constraint_type` varchar(64)
,`constrained_columns` mediumtext
);

-- --------------------------------------------------------

--
-- テーブルの構造 `gg_users`
--

CREATE TABLE `gg_users` (
  `user_id` int(11) NOT NULL,
  `login_name` varchar(100) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `firstname_kana` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `lastname_kana` varchar(100) NOT NULL,
  `postalcode` varchar(10) NOT NULL,
  `address` varchar(200) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `mailaddress` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `birthday` datetime DEFAULT NULL,
  `creation_date` datetime NOT NULL DEFAULT current_timestamp(),
  `update_date` datetime DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- テーブルのデータのダンプ `gg_users`
--

INSERT INTO `gg_users` (`user_id`, `login_name`, `firstname`, `firstname_kana`, `lastname`, `lastname_kana`, `postalcode`, `address`, `phone_number`, `mailaddress`, `password`, `gender`, `birthday`, `creation_date`, `update_date`) VALUES
(1, 'tanaka.taro', '太郎', 'タロウ', '田中', 'タナカ', '100-0001', '東京都千代田区千代田1-1', '090-1234-5678', 'taro.tanaka@example.com', '$2y$10$examplehashedpassword1', '男性', '1990-04-15 00:00:00', '2025-10-27 11:22:22', NULL),
(2, 'suzuki.hanako', '花子', 'ハナコ', '鈴木', 'スズキ', '540-0002', '大阪府大阪市中央区大阪城1-1', '080-9876-5432', 'hanako.suzuki@example.net', '$2y$10$examplehashedpassword2', '女性', '1995-11-20 00:00:00', '2025-10-27 11:22:22', NULL),
(3, 'sato.kenji', '健司', 'ケンジ', '佐藤', 'サトウ', '604-8001', '京都府京都市中京区', NULL, 'kenji.sato@example.org', '$2y$10$examplehashedpassword3', '男性', '1988-01-30 00:00:00', '2025-10-27 11:22:22', NULL),
(4, 'takahashi.yuki', '雪', 'ユキ', '高橋', 'タカハシ', '060-0001', '北海道札幌市中央区北1条西2丁目', '011-222-3333', 'yuki.taka@example.com', '$2y$10$examplehashedpassword4', '女性', NULL, '2025-10-27 11:22:22', NULL),
(5, 'watanabe.akira', '明', 'アキラ', '渡辺', 'ワタナベ', '810-0001', '福岡県福岡市中央区天神1-1', NULL, 'akira.w@test.jp', '$2y$10$examplehashedpassword5', '回答しない', '2001-07-07 00:00:00', '2025-10-27 11:22:22', NULL);

-- --------------------------------------------------------

--
-- テーブルの構造 `gg_views`
--

CREATE TABLE `gg_views` (
  `view_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `game_id` int(11) DEFAULT NULL,
  `gadget_id` int(11) DEFAULT NULL,
  `view_time` datetime NOT NULL DEFAULT current_timestamp(),
  `session_id` int(11) DEFAULT NULL
) ;

--
-- テーブルのデータのダンプ `gg_views`
--

INSERT INTO `gg_views` (`view_id`, `user_id`, `game_id`, `gadget_id`, `view_time`, `session_id`) VALUES
(1, 1, 1, NULL, '2024-10-26 10:30:00', NULL),
(2, 1, NULL, 1, '2024-10-26 10:32:00', NULL),
(3, NULL, 6, NULL, '2024-10-26 11:15:00', 1001),
(4, NULL, NULL, 2, '2024-10-26 11:17:00', 1001),
(5, 2, 3, NULL, '2024-10-27 08:45:00', 2005),
(6, 1, 1, NULL, '2024-10-27 09:00:00', NULL);

-- --------------------------------------------------------

--
-- ビュー用の代替構造 `review_game_gadget`
-- (実際のビューを参照するには下にあります)
--
CREATE TABLE `review_game_gadget` (
`review_id` int(11)
,`firstname` varchar(100)
,`lastname` varchar(100)
,`game_name` varchar(255)
,`gadget_name` varchar(255)
,`rating` int(11)
,`comment` text
,`review_date` datetime
);

-- --------------------------------------------------------

--
-- ビュー用の構造 `database_check_constraints`
--
DROP TABLE IF EXISTS `database_check_constraints`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `database_check_constraints`  AS SELECT `information_schema`.`check_constraints`.`TABLE_NAME` AS `table_name`, `information_schema`.`check_constraints`.`CONSTRAINT_NAME` AS `constraint_name`, `information_schema`.`check_constraints`.`CHECK_CLAUSE` AS `check_clause` FROM `information_schema`.`check_constraints` WHERE `information_schema`.`check_constraints`.`CONSTRAINT_SCHEMA` = 'gg_store' ;

-- --------------------------------------------------------

--
-- ビュー用の構造 `gg_unique_constraints`
--
DROP TABLE IF EXISTS `gg_unique_constraints`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `gg_unique_constraints`  AS SELECT `tc`.`TABLE_NAME` AS `table_name`, `tc`.`CONSTRAINT_NAME` AS `constraint_name`, `tc`.`CONSTRAINT_TYPE` AS `constraint_type`, group_concat(`kcu`.`COLUMN_NAME` order by `kcu`.`ORDINAL_POSITION` ASC separator ',') AS `constrained_columns` FROM (`information_schema`.`table_constraints` `tc` join `information_schema`.`key_column_usage` `kcu` on(`tc`.`CONSTRAINT_NAME` = `kcu`.`CONSTRAINT_NAME` and `tc`.`TABLE_SCHEMA` = `kcu`.`TABLE_SCHEMA`)) WHERE `tc`.`CONSTRAINT_SCHEMA` = 'gg_store' AND `tc`.`CONSTRAINT_TYPE` in ('UNIQUE','PRIMARY KEY') GROUP BY `tc`.`TABLE_NAME`, `tc`.`CONSTRAINT_NAME`, `tc`.`CONSTRAINT_TYPE` ORDER BY `tc`.`TABLE_NAME` ASC, `tc`.`CONSTRAINT_TYPE` ASC ;

-- --------------------------------------------------------

--
-- ビュー用の構造 `review_game_gadget`
--
DROP TABLE IF EXISTS `review_game_gadget`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `review_game_gadget`  AS SELECT `review`.`review_id` AS `review_id`, `user`.`firstname` AS `firstname`, `user`.`lastname` AS `lastname`, `game`.`game_name` AS `game_name`, `gadget`.`gadget_name` AS `gadget_name`, `review`.`rating` AS `rating`, `review`.`comment` AS `comment`, `review`.`review_date` AS `review_date` FROM (((`gg_reviews` `review` join `gg_users` `user` on(`review`.`user_id` = `user`.`user_id`)) left join `gg_game` `game` on(`review`.`game_id` = `game`.`game_id`)) left join `gg_gadget` `gadget` on(`review`.`gadget_id` = `gadget`.`gadget_id`)) ;

--
-- ダンプしたテーブルのインデックス
--

--
-- テーブルのインデックス `compatibility_rules`
--
ALTER TABLE `compatibility_rules`
  ADD PRIMARY KEY (`rule_id`),
  ADD UNIQUE KEY `platform_genre_key` (`platform_genre_key`),
  ADD KEY `fk_compatibility_platform` (`platform_id`),
  ADD KEY `fk_compatibility_genre` (`genre_id`),
  ADD KEY `fk_compatibility_category` (`recommend_category_id`);

--
-- テーブルのインデックス `gg_admins`
--
ALTER TABLE `gg_admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `uk_admin_login_name` (`login_name`),
  ADD UNIQUE KEY `uk_admin_mailaddress` (`mailaddress`);

--
-- テーブルのインデックス `gg_carts`
--
ALTER TABLE `gg_carts`
  ADD PRIMARY KEY (`cart_id`),
  ADD UNIQUE KEY `cart_item_key` (`cart_item_key`),
  ADD KEY `fk_carts_user` (`user_id`),
  ADD KEY `fk_carts_game` (`game_id`),
  ADD KEY `fk_carts_gadget` (`gadget_id`);

--
-- テーブルのインデックス `gg_category`
--
ALTER TABLE `gg_category`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `uk_category_name` (`category_name`);

--
-- テーブルのインデックス `gg_delivery`
--
ALTER TABLE `gg_delivery`
  ADD PRIMARY KEY (`delivery_id`),
  ADD UNIQUE KEY `uk_unique_address` (`user_id`,`postal_code`,`address`);

--
-- テーブルのインデックス `gg_detail_orders`
--
ALTER TABLE `gg_detail_orders`
  ADD PRIMARY KEY (`detail_id`,`order_id`),
  ADD KEY `fk_detail_order_id` (`order_id`),
  ADD KEY `fk_detail_game` (`game_id`),
  ADD KEY `fk_detail_gadget` (`gadget_id`);

--
-- テーブルのインデックス `gg_favorite`
--
ALTER TABLE `gg_favorite`
  ADD PRIMARY KEY (`favorite_id`),
  ADD UNIQUE KEY `user_product_key` (`user_product_key`),
  ADD KEY `fk_favorite_user` (`user_id`),
  ADD KEY `fk_favorite_game` (`game_id`),
  ADD KEY `fk_favorite_gadget` (`gadget_id`);

--
-- テーブルのインデックス `gg_gadget`
--
ALTER TABLE `gg_gadget`
  ADD PRIMARY KEY (`gadget_id`),
  ADD KEY `fk_gadget_category` (`category_id`);

--
-- テーブルのインデックス `gg_gadget_specs`
--
ALTER TABLE `gg_gadget_specs`
  ADD PRIMARY KEY (`gadget_spec_id`),
  ADD UNIQUE KEY `uk_gadget_spec` (`gadget_id`,`spec_id`),
  ADD KEY `fk_gadget_spec_name` (`spec_id`);

--
-- テーブルのインデックス `gg_game`
--
ALTER TABLE `gg_game`
  ADD PRIMARY KEY (`game_id`),
  ADD KEY `fk_game_platform` (`platform_id`);

--
-- テーブルのインデックス `gg_game_genres`
--
ALTER TABLE `gg_game_genres`
  ADD PRIMARY KEY (`game_id`,`genre_id`),
  ADD KEY `fk_gamegenre_genre` (`genre_id`);

--
-- テーブルのインデックス `gg_game_requirements`
--
ALTER TABLE `gg_game_requirements`
  ADD PRIMARY KEY (`game_req_id`),
  ADD UNIQUE KEY `uk_game_req` (`game_id`,`spec_id`,`type`),
  ADD KEY `fk_game_req_spec_name` (`spec_id`);

--
-- テーブルのインデックス `gg_genres`
--
ALTER TABLE `gg_genres`
  ADD PRIMARY KEY (`genre_id`),
  ADD UNIQUE KEY `uk_genre_name` (`genre_name`);

--
-- テーブルのインデックス `gg_media`
--
ALTER TABLE `gg_media`
  ADD PRIMARY KEY (`media_id`),
  ADD KEY `fk_media_game` (`game_id`),
  ADD KEY `fk_media_gadget` (`gadget_id`);

--
-- テーブルのインデックス `gg_orders`
--
ALTER TABLE `gg_orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `fk_orders_user` (`user_id`);

--
-- テーブルのインデックス `gg_platforms`
--
ALTER TABLE `gg_platforms`
  ADD PRIMARY KEY (`platform_id`),
  ADD UNIQUE KEY `uk_platform_name` (`platform_name`);

--
-- テーブルのインデックス `gg_points`
--
ALTER TABLE `gg_points`
  ADD PRIMARY KEY (`user_id`);

--
-- テーブルのインデックス `gg_point_transactions`
--
ALTER TABLE `gg_point_transactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `fk_transaction_user` (`user_id`),
  ADD KEY `fk_transaction_order` (`order_id`);

--
-- テーブルのインデックス `gg_premium`
--
ALTER TABLE `gg_premium`
  ADD PRIMARY KEY (`user_id`);

--
-- テーブルのインデックス `gg_reviews`
--
ALTER TABLE `gg_reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD UNIQUE KEY `user_product_key` (`user_product_key`),
  ADD KEY `fk_reviews_user` (`user_id`),
  ADD KEY `fk_reviews_game` (`game_id`),
  ADD KEY `fk_reviews_gadget` (`gadget_id`);

--
-- テーブルのインデックス `gg_specifications`
--
ALTER TABLE `gg_specifications`
  ADD PRIMARY KEY (`spec_id`),
  ADD UNIQUE KEY `uk_spec_name` (`spec_name`);

--
-- テーブルのインデックス `gg_users`
--
ALTER TABLE `gg_users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `uk_login_name` (`login_name`),
  ADD UNIQUE KEY `uk_mailaddress` (`mailaddress`);

--
-- テーブルのインデックス `gg_views`
--
ALTER TABLE `gg_views`
  ADD PRIMARY KEY (`view_id`),
  ADD KEY `fk_views_user` (`user_id`),
  ADD KEY `fk_views_game` (`game_id`),
  ADD KEY `fk_views_gadget` (`gadget_id`);

--
-- ダンプしたテーブルの AUTO_INCREMENT
--

--
-- テーブルの AUTO_INCREMENT `compatibility_rules`
--
ALTER TABLE `compatibility_rules`
  MODIFY `rule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- テーブルの AUTO_INCREMENT `gg_admins`
--
ALTER TABLE `gg_admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- テーブルの AUTO_INCREMENT `gg_carts`
--
ALTER TABLE `gg_carts`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `gg_category`
--
ALTER TABLE `gg_category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- テーブルの AUTO_INCREMENT `gg_delivery`
--
ALTER TABLE `gg_delivery`
  MODIFY `delivery_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- テーブルの AUTO_INCREMENT `gg_detail_orders`
--
ALTER TABLE `gg_detail_orders`
  MODIFY `detail_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `gg_favorite`
--
ALTER TABLE `gg_favorite`
  MODIFY `favorite_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `gg_gadget`
--
ALTER TABLE `gg_gadget`
  MODIFY `gadget_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- テーブルの AUTO_INCREMENT `gg_gadget_specs`
--
ALTER TABLE `gg_gadget_specs`
  MODIFY `gadget_spec_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- テーブルの AUTO_INCREMENT `gg_game`
--
ALTER TABLE `gg_game`
  MODIFY `game_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- テーブルの AUTO_INCREMENT `gg_game_requirements`
--
ALTER TABLE `gg_game_requirements`
  MODIFY `game_req_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `gg_media`
--
ALTER TABLE `gg_media`
  MODIFY `media_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `gg_orders`
--
ALTER TABLE `gg_orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- テーブルの AUTO_INCREMENT `gg_point_transactions`
--
ALTER TABLE `gg_point_transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `gg_reviews`
--
ALTER TABLE `gg_reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `gg_specifications`
--
ALTER TABLE `gg_specifications`
  MODIFY `spec_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- テーブルの AUTO_INCREMENT `gg_users`
--
ALTER TABLE `gg_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- テーブルの AUTO_INCREMENT `gg_views`
--
ALTER TABLE `gg_views`
  MODIFY `view_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- ダンプしたテーブルの制約
--

--
-- テーブルの制約 `compatibility_rules`
--
ALTER TABLE `compatibility_rules`
  ADD CONSTRAINT `fk_compatibility_category` FOREIGN KEY (`recommend_category_id`) REFERENCES `gg_category` (`category_id`),
  ADD CONSTRAINT `fk_compatibility_genre` FOREIGN KEY (`genre_id`) REFERENCES `gg_genres` (`genre_id`),
  ADD CONSTRAINT `fk_compatibility_platform` FOREIGN KEY (`platform_id`) REFERENCES `gg_platforms` (`platform_id`);

--
-- テーブルの制約 `gg_carts`
--
ALTER TABLE `gg_carts`
  ADD CONSTRAINT `fk_carts_gadget` FOREIGN KEY (`gadget_id`) REFERENCES `gg_gadget` (`gadget_id`),
  ADD CONSTRAINT `fk_carts_game` FOREIGN KEY (`game_id`) REFERENCES `gg_game` (`game_id`),
  ADD CONSTRAINT `fk_carts_user` FOREIGN KEY (`user_id`) REFERENCES `gg_users` (`user_id`);

--
-- テーブルの制約 `gg_delivery`
--
ALTER TABLE `gg_delivery`
  ADD CONSTRAINT `fk_delivery_user` FOREIGN KEY (`user_id`) REFERENCES `gg_users` (`user_id`);

--
-- テーブルの制約 `gg_detail_orders`
--
ALTER TABLE `gg_detail_orders`
  ADD CONSTRAINT `fk_detail_gadget` FOREIGN KEY (`gadget_id`) REFERENCES `gg_gadget` (`gadget_id`),
  ADD CONSTRAINT `fk_detail_game` FOREIGN KEY (`game_id`) REFERENCES `gg_game` (`game_id`),
  ADD CONSTRAINT `fk_detail_order_id` FOREIGN KEY (`order_id`) REFERENCES `gg_orders` (`order_id`);

--
-- テーブルの制約 `gg_favorite`
--
ALTER TABLE `gg_favorite`
  ADD CONSTRAINT `fk_favorite_gadget` FOREIGN KEY (`gadget_id`) REFERENCES `gg_gadget` (`gadget_id`),
  ADD CONSTRAINT `fk_favorite_game` FOREIGN KEY (`game_id`) REFERENCES `gg_game` (`game_id`),
  ADD CONSTRAINT `fk_favorite_user` FOREIGN KEY (`user_id`) REFERENCES `gg_users` (`user_id`);

--
-- テーブルの制約 `gg_gadget`
--
ALTER TABLE `gg_gadget`
  ADD CONSTRAINT `fk_gadget_category` FOREIGN KEY (`category_id`) REFERENCES `gg_category` (`category_id`);

--
-- テーブルの制約 `gg_gadget_specs`
--
ALTER TABLE `gg_gadget_specs`
  ADD CONSTRAINT `fk_gadget_spec_accessory` FOREIGN KEY (`gadget_id`) REFERENCES `gg_gadget` (`gadget_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_gadget_spec_name` FOREIGN KEY (`spec_id`) REFERENCES `gg_specifications` (`spec_id`);

--
-- テーブルの制約 `gg_game`
--
ALTER TABLE `gg_game`
  ADD CONSTRAINT `fk_game_platform` FOREIGN KEY (`platform_id`) REFERENCES `gg_platforms` (`platform_id`);

--
-- テーブルの制約 `gg_game_genres`
--
ALTER TABLE `gg_game_genres`
  ADD CONSTRAINT `fk_gamegenre_game` FOREIGN KEY (`game_id`) REFERENCES `gg_game` (`game_id`),
  ADD CONSTRAINT `fk_gamegenre_genre` FOREIGN KEY (`genre_id`) REFERENCES `gg_genres` (`genre_id`);

--
-- テーブルの制約 `gg_game_requirements`
--
ALTER TABLE `gg_game_requirements`
  ADD CONSTRAINT `fk_game_req_game` FOREIGN KEY (`game_id`) REFERENCES `gg_game` (`game_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_game_req_spec_name` FOREIGN KEY (`spec_id`) REFERENCES `gg_specifications` (`spec_id`);

--
-- テーブルの制約 `gg_media`
--
ALTER TABLE `gg_media`
  ADD CONSTRAINT `fk_media_gadget` FOREIGN KEY (`gadget_id`) REFERENCES `gg_gadget` (`gadget_id`),
  ADD CONSTRAINT `fk_media_game` FOREIGN KEY (`game_id`) REFERENCES `gg_game` (`game_id`);

--
-- テーブルの制約 `gg_orders`
--
ALTER TABLE `gg_orders`
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `gg_users` (`user_id`);

--
-- テーブルの制約 `gg_points`
--
ALTER TABLE `gg_points`
  ADD CONSTRAINT `fk_points_user` FOREIGN KEY (`user_id`) REFERENCES `gg_users` (`user_id`);

--
-- テーブルの制約 `gg_point_transactions`
--
ALTER TABLE `gg_point_transactions`
  ADD CONSTRAINT `fk_transaction_order` FOREIGN KEY (`order_id`) REFERENCES `gg_orders` (`order_id`),
  ADD CONSTRAINT `fk_transaction_user` FOREIGN KEY (`user_id`) REFERENCES `gg_users` (`user_id`);

--
-- テーブルの制約 `gg_premium`
--
ALTER TABLE `gg_premium`
  ADD CONSTRAINT `fk_premium_user` FOREIGN KEY (`user_id`) REFERENCES `gg_users` (`user_id`);

--
-- テーブルの制約 `gg_reviews`
--
ALTER TABLE `gg_reviews`
  ADD CONSTRAINT `fk_reviews_gadget` FOREIGN KEY (`gadget_id`) REFERENCES `gg_gadget` (`gadget_id`),
  ADD CONSTRAINT `fk_reviews_game` FOREIGN KEY (`game_id`) REFERENCES `gg_game` (`game_id`),
  ADD CONSTRAINT `fk_reviews_user` FOREIGN KEY (`user_id`) REFERENCES `gg_users` (`user_id`);

--
-- テーブルの制約 `gg_views`
--
ALTER TABLE `gg_views`
  ADD CONSTRAINT `fk_views_gadget` FOREIGN KEY (`gadget_id`) REFERENCES `gg_gadget` (`gadget_id`),
  ADD CONSTRAINT `fk_views_game` FOREIGN KEY (`game_id`) REFERENCES `gg_game` (`game_id`),
  ADD CONSTRAINT `fk_views_user` FOREIGN KEY (`user_id`) REFERENCES `gg_users` (`user_id`);
COMMIT;

CREATE VIEW
	gg_gadget_category_specs
AS
SELECT 
    gg_gadget.gadget_id, 
    gg_category.category_id ,
    gg_specifications.spec_id, 
    gg_gadget.manufacturer,
    gg_gadget.gadget_name,
    gg_gadget.price,
    gg_category.category_name,
    gg_specifications.spec_name, 
    gg_gadget_specs.spec_value,
    gg_specifications.unit,
    gg_gadget.stock
FROM
	gg_gadget
INNER JOIN
	gg_gadget_specs
ON
	gg_gadget.gadget_id = gg_gadget_specs.gadget_id
INNER JOIN
	gg_category
ON
	gg_gadget.category_id = gg_category.category_id
INNER JOIN
	gg_specifications
ON
	gg_gadget_specs.spec_id = gg_specifications.spec_id;


CREATE VIEW
	gadget_insert
AS
SELECT 
    gg_gadget.gadget_id, 
    gg_category.category_id ,
    gg_specifications.spec_id, 
    gg_media.media_id,
    gg_gadget.manufacturer,
    gg_gadget.gadget_name,
    gg_gadget.price,
    gg_category.category_name,
    gg_specifications.spec_name, 
    gg_gadget_specs.spec_value,
    gg_specifications.unit,
    gg_gadget.stock,
    gg_media.url,
    gg_media.is_primary,
    gg_gadget.created_time
FROM
	gg_gadget
INNER JOIN
	gg_gadget_specs
ON
	gg_gadget.gadget_id = gg_gadget_specs.gadget_id
INNER JOIN
	gg_category
ON
	gg_gadget.category_id = gg_category.category_id
INNER JOIN
	gg_specifications
ON
	gg_gadget_specs.spec_id = gg_specifications.spec_id
INNER JOIN
	gg_media
ON
	gg_gadget.gadget_id = gg_media.gadget_id;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
