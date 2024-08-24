-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 14, 2024 at 12:12 PM
-- Server version: 8.0.37
-- PHP Version: 8.1.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `database`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`, `email`) VALUES
(1, 'admin', '$2y$10$R.JXagad2XUHsb0h1dWHsuxj4j9BzBe6rpUcFGRVpBtDKRJileX.u', 'admin@admin.com');

-- --------------------------------------------------------

--
-- Table structure for table `banners_adsense`
--

CREATE TABLE `banners_adsense` (
  `id` int NOT NULL,
  `type` enum('banner_top','banner_bottom','adsense') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banners_adsense`
--

INSERT INTO `banners_adsense` (`id`, `type`, `content`) VALUES
(1, 'banner_top', ''),
(2, 'banner_bottom', ''),
(3, 'adsense', '<script async src=\"https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=\" =\"\"></script>');

-- --------------------------------------------------------

--
-- Table structure for table `blog_articles`
--

CREATE TABLE `blog_articles` (
  `id` int NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `author` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `meta_description` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keywords` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `featured_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_info`
--

CREATE TABLE `contact_info` (
  `id` int NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact_info`
--

INSERT INTO `contact_info` (`id`, `title`, `email`) VALUES
(1, 'Get in Touch ', 'example@example.com');

-- --------------------------------------------------------

--
-- Table structure for table `css_styles`
--

CREATE TABLE `css_styles` (
  `id` int NOT NULL,
  `selector` varchar(255) NOT NULL,
  `property` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `theme` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `css_styles`
--

INSERT INTO `css_styles` (`id`, `selector`, `property`, `value`, `theme`) VALUES
(1, 'body', 'background', '#f0f0f0', 'light_mode'),
(2, '.c3', 'color', '#535493', 'light_mode'),
(3, '.dark body', 'background', '#add8e6', 'dark_mode'),
(4, '.dark .dark-c8', 'color', '#71baf9', 'dark_mode');

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` int NOT NULL,
  `question` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`id`, `question`, `answer`, `created_at`, `updated_at`) VALUES
(1, 'What is a QR code?', 'A QR code (Quick Response code) is a type of matrix barcode (or two-dimensional barcode) first designed in 1994 for the automotive industry in Japan. It\'s a machine-readable optical label that contains information about the item to which it is attached.', '2024-05-17 05:44:23', '2024-06-14 09:52:29'),
(3, 'Can I use the generated QR Codes for commercial purposes?', 'Yes, all QR codes you created with this QR generator are free and can be used for whatever you want.', '2024-05-17 05:44:23', '2024-06-13 15:32:54'),
(4, 'Are the created qr codes expiring?', 'They do not expire and will work forever! QR Codes created are static and do not stop working after a certain time. You just can’t edit the content of the QR Codes again.', '2024-05-17 05:44:23', '2024-06-13 15:33:40'),
(5, 'Is there a scan limit for the QR codes?', 'There is no limit and the created QR code will work forever. Scan it is many times as you wish!', '2024-05-17 05:44:23', '2024-06-13 15:34:02'),
(6, 'Does QR codes have to be black and white?', 'Not at all. You can use your brand or company colors, or whatever color you like.', '2024-05-17 05:44:23', '2024-06-13 15:37:23');

-- --------------------------------------------------------

--
-- Table structure for table `header_links`
--

CREATE TABLE `header_links` (
  `id` int NOT NULL,
  `title` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `header_links`
--

INSERT INTO `header_links` (`id`, `title`, `url`, `enabled`) VALUES
(1, 'Home', 'index.php', 1),
(2, 'Blog', 'blog.php', 1),
(3, 'Contact Us', 'contact.php', 1);

-- --------------------------------------------------------

--
-- Table structure for table `how_it_works`
--

CREATE TABLE `how_it_works` (
  `id` int NOT NULL,
  `general_title` varchar(255) DEFAULT NULL,
  `icon_title` varchar(100) NOT NULL,
  `icon_svg` text NOT NULL,
  `icon_description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `how_it_works`
--

INSERT INTO `how_it_works` (`id`, `general_title`, `icon_title`, `icon_svg`, `icon_description`) VALUES
(15, NULL, 'Select a QR Code Type ', '<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 448 512\"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d=\"M128 40c0-22.1 17.9-40 40-40s40 17.9 40 40V188.2c8.5-7.6 19.7-12.2 32-12.2c20.6 0 38.2 13 45 31.2c8.8-9.3 21.2-15.2 35-15.2c25.3 0 46 19.5 47.9 44.3c8.5-7.7 19.8-12.3 32.1-12.3c26.5 0 48 21.5 48 48v48 16 48c0 70.7-57.3 128-128 128l-16 0H240l-.1 0h-5.2c-5 0-9.9-.3-14.7-1c-55.3-5.6-106.2-34-140-79L8 336c-13.3-17.7-9.7-42.7 8-56s42.7-9.7 56 8l56 74.7V40zM240 304c0-8.8-7.2-16-16-16s-16 7.2-16 16v96c0 8.8 7.2 16 16 16s16-7.2 16-16V304zm48-16c-8.8 0-16 7.2-16 16v96c0 8.8 7.2 16 16 16s16-7.2 16-16V304c0-8.8-7.2-16-16-16zm80 16c0-8.8-7.2-16-16-16s-16 7.2-16 16v96c0 8.8 7.2 16 16 16s16-7.2 16-16V304z\"/></svg>', 'Pick from 12 types of QR codes youd like to generate by clicking on the appropriate button'),
(16, NULL, 'Fill in the details', '<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 512 512\"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d=\"M0 64C0 28.7 28.7 0 64 0H352c35.3 0 64 28.7 64 64v64c0 35.3-28.7 64-64 64H64c-35.3 0-64-28.7-64-64V64zM160 352c0-17.7 14.3-32 32-32V304c0-44.2 35.8-80 80-80H416c17.7 0 32-14.3 32-32V160 69.5c37.3 13.2 64 48.7 64 90.5v32c0 53-43 96-96 96H272c-8.8 0-16 7.2-16 16v16c17.7 0 32 14.3 32 32V480c0 17.7-14.3 32-32 32H192c-17.7 0-32-14.3-32-32V352z\"/></svg>', 'Depending on the QR code type you have selected, you will be prompted to enter all relevant data. You can also add customizations, like color, your logo .'),
(21, NULL, 'Customize Options', '<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 512 512\"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d=\"M3.9 54.9C10.5 40.9 24.5 32 40 32H472c15.5 0 29.5 8.9 36.1 22.9s4.6 30.5-5.2 42.5L320 320.9V448c0 12.1-6.8 23.2-17.7 28.6s-23.8 4.3-33.5-3l-64-48c-8.1-6-12.8-15.5-12.8-25.6V320.9L9 97.3C-.7 85.4-2.8 68.8 3.9 54.9z\"/></svg>', 'set the the image format  for your QR code .PNG .JPEG .Webp and size , Error Correction.'),
(22, NULL, 'Generate & Download', '<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 512 512\"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d=\"M256 0a256 256 0 1 0 0 512A256 256 0 1 0 256 0zM376.9 294.6L269.8 394.5c-3.8 3.5-8.7 5.5-13.8 5.5s-10.1-2-13.8-5.5L135.1 294.6c-4.5-4.2-7.1-10.1-7.1-16.3c0-12.3 10-22.3 22.3-22.3l57.7 0 0-96c0-17.7 14.3-32 32-32l32 0c17.7 0 32 14.3 32 32l0 96 57.7 0c12.3 0 22.3 10 22.3 22.3c0 6.2-2.6 12.1-7.1 16.3z\"/></svg>', 'Click Generate and Download your newly generated QR code .');

-- --------------------------------------------------------

--
-- Table structure for table `maintenance_mode`
--

CREATE TABLE `maintenance_mode` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `maintenance_mode`
--

INSERT INTO `maintenance_mode` (`id`, `title`, `message`, `active`) VALUES
(1, 'We\'ll Be Back Soon!', 'Thank you for visiting Our site is currently undergoing scheduled maintenance to improve your experience. We apologize for any inconvenience and appreciate your patience.', 0);

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_description` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `keywords` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `include_header` tinyint(1) NOT NULL DEFAULT '0',
  `include_footer` tinyint(1) NOT NULL DEFAULT '0',
  `include_both` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `title`, `content`, `meta_description`, `keywords`, `include_header`, `include_footer`, `include_both`) VALUES
(18, 'Terms and Privacy', '<h3>&nbsp;</h3><h4>Introduction</h4><p>Welcome to QRGen! By using our QR code generation service, you agree to the following terms and conditions. Please read them carefully.</p><h4>Use of Service</h4><ol><li><strong>Free Use</strong>: QRGen is provided to you free of charge.</li><li><strong>Acceptance of Terms</strong>: By accessing or using QRGen, you agree to be bound by these terms.</li><li><strong>Modification of Terms</strong>: We reserve the right to modify these terms at any time without prior notice. Your continued use of the service constitutes acceptance of the new terms.</li></ol><h4>Disclaimer</h4><ol><li><strong>No Warranty</strong>: QRGen is provided \"as is\" without warranty of any kind. We do not guarantee that the service will be uninterrupted or error-free.</li><li><strong>Limitation of Liability</strong>: In no event shall QRGen be liable for any damages arising out of the use or inability to use the service.</li></ol><h4>Governing Law</h4><p>These terms shall be governed by and construed in accordance with the laws of [Your Country/State], without regard to its conflict of law provisions.</p><p>&nbsp;</p><p>&nbsp;</p><h4>Introduction</h4><p>QRGen respects your privacy. This policy explains how we handle your information when you use our service.</p><h4>Data Collection</h4><ol><li><strong>No Personal Data</strong>: QRGen does not collect or store any personal data from users.</li><li><strong>QR Code Data</strong>: The data you input to generate QR codes is not stored or monitored by us. Once the QR code is generated, we do not retain any information.</li></ol><h4>Use of Data</h4><ol><li><strong>No Data Storage</strong>: Since we do not collect or store any data, we do not use or share any personal information.</li><li><strong>Third Parties</strong>: We do not share any user information with third parties because no user information is collected.</li></ol><h4>Security</h4><p>We take reasonable measures to ensure the security of our service. However, since no system can be completely secure, we cannot guarantee the absolute security of our service.</p><h4>Changes to This Policy</h4><p>We may update our Privacy Policy from time to time. Any changes will be posted on this page. Your continued use of the service after any changes are made constitutes acceptance of the new policy.</p><h4>&nbsp;</h4>', 'terms of use', 'terms of use', 0, 1, 0),
(19, 'About us ', '<h4>QRGen: Your Free QR Code Generator</h4><p>Welcome to QRGen! We are a dedicated team of developers committed to providing a simple, efficient, and free tool for generating QR codes. Our mission is to make QR code creation accessible to everyone, whether for personal, educational, or business purposes.</p><h4>What is QRGen?</h4><p>QRGen is a user-friendly, web-based QR code generator designed to help you create QR codes quickly and easily. Whether you need a QR code for a website URL, contact information, event details, or any other purpose, QRGen allows you to generate high-quality QR codes in just a few clicks.</p><h4>Why Choose QRGen?</h4><ul><li><strong>Free to Use</strong>: QRGen is completely free with no hidden charges.</li><li><strong>Privacy Focused</strong>: We respect your privacy. We do not collect or store any of your data.</li><li><strong>Easy to Use</strong>: Our intuitive interface ensures that anyone can generate QR codes without any technical knowledge.</li><li><strong>No Data Storage</strong>: The data you input to generate QR codes is not stored or monitored by us.</li></ul><h4>Our Vision</h4><p>At QRGen, our vision is to empower individuals and businesses by providing them with the tools they need to easily incorporate QR codes into their activities. We believe in the power of technology to simplify everyday tasks and enhance communication.</p><h4>Get in Touch</h4><p>If you have any questions, feedback, or suggestions, please don\'t hesitate to contact us. We are always here to help and improve our services.</p><p>Thank you for choosing QRGen!</p>', 'about us ', 'about us ', 0, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `seo_metadata`
--

CREATE TABLE `seo_metadata` (
  `id` int NOT NULL,
  `title` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `seo_description` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `seo_keywords` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `open_graph_title` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `open_graph_description` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `index_page_title` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `index_page_lead_paragraph` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `footer_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `seo_metadata`
--

INSERT INTO `seo_metadata` (`id`, `title`, `seo_description`, `seo_keywords`, `open_graph_title`, `open_graph_description`, `index_page_title`, `index_page_lead_paragraph`, `footer_content`) VALUES
(2, 'QRGen - Free Online QR Code Generator', 'Generate high-quality QR codes for free with QRGen. No sign-up required, no data stored. Create QR codes for URLs, contact information, events, and more instantly!', 'QR code generator, free QR code generator, online QR code generator, QR code creator, QR code maker, QR code, generate QR codes, QRGen', 'QRGen - Free Online QR Code Generator', 'Create high-quality QR codes for any purpose with QRGen. It\'s free, easy to use, and no data is stored. Generate your QR code in seconds!', 'QR code Generator', 'Generate simple & advanced QR codes. Easy, customizable in seconds ', 'Copyright © QRGen ');

-- --------------------------------------------------------

--
-- Table structure for table `social_media`
--

CREATE TABLE `social_media` (
  `id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon_class` varchar(16000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `social_media`
--

INSERT INTO `social_media` (`id`, `name`, `icon_class`, `link`) VALUES
(2, 'Twitter', '<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 512 512\"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d=\"M459.4 151.7c.3 4.5 .3 9.1 .3 13.6 0 138.7-105.6 298.6-298.6 298.6-59.5 0-114.7-17.2-161.1-47.1 8.4 1 16.6 1.3 25.3 1.3 49.1 0 94.2-16.6 130.3-44.8-46.1-1-84.8-31.2-98.1-72.8 6.5 1 13 1.6 19.8 1.6 9.4 0 18.8-1.3 27.6-3.6-48.1-9.7-84.1-52-84.1-103v-1.3c14 7.8 30.2 12.7 47.4 13.3-28.3-18.8-46.8-51-46.8-87.4 0-19.5 5.2-37.4 14.3-53 51.7 63.7 129.3 105.3 216.4 109.8-1.6-7.8-2.6-15.9-2.6-24 0-57.8 46.8-104.9 104.9-104.9 30.2 0 57.5 12.7 76.7 33.1 23.7-4.5 46.5-13.3 66.6-25.3-7.8 24.4-24.4 44.8-46.1 57.8 21.1-2.3 41.6-8.1 60.4-16.2-14.3 20.8-32.2 39.3-52.6 54.3z\"/></svg>', ''),
(3, 'Instagram', '<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 448 512\"><!--!Font Awesome Free 6.5.2 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d=\"M194.4 211.7a53.3 53.3 0 1 0 59.3 88.7 53.3 53.3 0 1 0 -59.3-88.7zm142.3-68.4c-5.2-5.2-11.5-9.3-18.4-12c-18.1-7.1-57.6-6.8-83.1-6.5c-4.1 0-7.9 .1-11.2 .1c-3.3 0-7.2 0-11.4-.1c-25.5-.3-64.8-.7-82.9 6.5c-6.9 2.7-13.1 6.8-18.4 12s-9.3 11.5-12 18.4c-7.1 18.1-6.7 57.7-6.5 83.2c0 4.1 .1 7.9 .1 11.1s0 7-.1 11.1c-.2 25.5-.6 65.1 6.5 83.2c2.7 6.9 6.8 13.1 12 18.4s11.5 9.3 18.4 12c18.1 7.1 57.6 6.8 83.1 6.5c4.1 0 7.9-.1 11.2-.1c3.3 0 7.2 0 11.4 .1c25.5 .3 64.8 .7 82.9-6.5c6.9-2.7 13.1-6.8 18.4-12s9.3-11.5 12-18.4c7.2-18 6.8-57.4 6.5-83c0-4.2-.1-8.1-.1-11.4s0-7.1 .1-11.4c.3-25.5 .7-64.9-6.5-83l0 0c-2.7-6.9-6.8-13.1-12-18.4zm-67.1 44.5A82 82 0 1 1 178.4 324.2a82 82 0 1 1 91.1-136.4zm29.2-1.3c-3.1-2.1-5.6-5.1-7.1-8.6s-1.8-7.3-1.1-11.1s2.6-7.1 5.2-9.8s6.1-4.5 9.8-5.2s7.6-.4 11.1 1.1s6.5 3.9 8.6 7s3.2 6.8 3.2 10.6c0 2.5-.5 5-1.4 7.3s-2.4 4.4-4.1 6.2s-3.9 3.2-6.2 4.2s-4.8 1.5-7.3 1.5l0 0c-3.8 0-7.5-1.1-10.6-3.2zM448 96c0-35.3-28.7-64-64-64H64C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H384c35.3 0 64-28.7 64-64V96zM357 389c-18.7 18.7-41.4 24.6-67 25.9c-26.4 1.5-105.6 1.5-132 0c-25.6-1.3-48.3-7.2-67-25.9s-24.6-41.4-25.8-67c-1.5-26.4-1.5-105.6 0-132c1.3-25.6 7.1-48.3 25.8-67s41.5-24.6 67-25.8c26.4-1.5 105.6-1.5 132 0c25.6 1.3 48.3 7.1 67 25.8s24.6 41.4 25.8 67c1.5 26.3 1.5 105.4 0 131.9c-1.3 25.6-7.1 48.3-25.8 67z\"/></svg>', '');

-- --------------------------------------------------------

--
-- Table structure for table `support_requests`
--

CREATE TABLE `support_requests` (
  `id` int NOT NULL,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `enabled` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `banners_adsense`
--
ALTER TABLE `banners_adsense`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blog_articles`
--
ALTER TABLE `blog_articles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `contact_info`
--
ALTER TABLE `contact_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `css_styles`
--
ALTER TABLE `css_styles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `header_links`
--
ALTER TABLE `header_links`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `how_it_works`
--
ALTER TABLE `how_it_works`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `maintenance_mode`
--
ALTER TABLE `maintenance_mode`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `seo_metadata`
--
ALTER TABLE `seo_metadata`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `social_media`
--
ALTER TABLE `social_media`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `support_requests`
--
ALTER TABLE `support_requests`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `banners_adsense`
--
ALTER TABLE `banners_adsense`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `blog_articles`
--
ALTER TABLE `blog_articles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `contact_info`
--
ALTER TABLE `contact_info`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `css_styles`
--
ALTER TABLE `css_styles`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `header_links`
--
ALTER TABLE `header_links`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `how_it_works`
--
ALTER TABLE `how_it_works`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `maintenance_mode`
--
ALTER TABLE `maintenance_mode`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `seo_metadata`
--
ALTER TABLE `seo_metadata`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `social_media`
--
ALTER TABLE `social_media`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `support_requests`
--
ALTER TABLE `support_requests`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
