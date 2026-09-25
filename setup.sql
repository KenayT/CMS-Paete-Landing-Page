-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 25, 2026 at 10:35 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `paete_cms`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password_hash`, `created_at`) VALUES
(1, 'kenneth102', '$2y$10$WV7Gd/2k3kgJogkwV1.0keBYYU/bIoQddg94lOkzhRePLdoCZTzaO', '2026-09-06 09:38:11');

-- --------------------------------------------------------

--
-- Table structure for table `page_sections`
--

CREATE TABLE `page_sections` (
  `id` int(11) NOT NULL,
  `section` varchar(50) NOT NULL,
  `content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`content`)),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `page_sections`
--

INSERT INTO `page_sections` (`id`, `section`, `content`, `updated_at`) VALUES
(1, 'header', '{\"logoSrc\":\"assets/logo.png\",\"logoAlt\":\"Rural Bank of Paete Logo\",\"bankName\":\"Rural Bank of Paete, Inc.\",\"navLinks\":[{\"name\":\"Home\",\"url\":\"index.html\"},{\"name\":\"About Us\",\"url\":\"about.html\"},{\"name\":\"Products & Services\",\"url\":\"products.html\"},{\"name\":\"News & Updates\",\"url\":\"news.html\"},{\"name\":\"Contact Us\",\"url\":\"contacts.html\"},{\"name\":\"Test Link\",\"url\":\"test.html\"}]}', '2026-09-24 08:21:23'),
(2, 'hero', '{\"bgImage\":\"assets/landing page bg section 1.jpg\",\"vectorImage\":\"assets/vector art section 1.png\",\"headline\":\"Welcome to\",\"headlineHighlight\":\"Rural Bank of Paete Inc.\",\"description\":\"Serving our community with trust, care, and financial empowerment since 1966. We offer secure and accessible banking services tailored to the needs of individuals, families, farmers, and small businesses in Paete and beyond.\",\"badgeText\":\"NOW, SAVE, GROW WITH US!\",\"ctaPrimary\":{\"text\":\"Open an Account Now!\",\"url\":\"#\"},\"ctaSecondary\":{\"text\":\"Learn More\",\"url\":\"#\"}}', '2026-09-24 18:30:46'),
(3, 'carousel', '[\r\n  { \"id\": 1, \"icon\": \"assets/icon1.png\", \"title\": \"Regular Savings\", \"description\": \"A deposit account that earns interest and allows you to...\", \"linkText\": \"Learn more...\", \"url\": \"#\" },\r\n  { \"id\": 2, \"icon\": \"assets/icon2.png\", \"title\": \"Basic Deposit Account\", \"description\": \"A simplified savings account designed for the unbanked...\", \"linkText\": \"Learn more...\", \"url\": \"#\" },\r\n  { \"id\": 3, \"icon\": \"assets/icon3.png\", \"title\": \"Time Deposit Account\", \"description\": \"A deposit account where funds are locked in for...\", \"linkText\": \"Learn more...\", \"url\": \"#\" },\r\n  { \"id\": 4, \"icon\": \"assets/icon4.png\", \"title\": \"Checking Account\", \"description\": \"A deposit account that allows the account holder to issue...\", \"linkText\": \"Learn more...\", \"url\": \"#\" }\r\n]', '2026-09-24 18:53:18'),
(4, 'loan-services', '[{\"id\":1,\"bgImage\":\"assets/loan bg.jpg\",\"badgeText\":\"Loan Services\",\"image\":\"assets/loan.png\",\"imageAlt\":\"Loan application illustration\",\"description\":\"Whether you\'re growing your farm, starting a business, or facing an emergency, we have a loan for you.\",\"features\":[\"Competitive interest rates\",\"Fast approval process\",\"Friendly, local service\",\"Flexible payment terms\"],\"ctaPrimary\":{\"text\":\"Check loan requirements\",\"url\":\"#\"},\"ctaSecondary\":{\"text\":\"Apply for loan now?\",\"url\":\"#\"}}]', '2026-09-24 19:28:52'),
(5, 'offerings', '[{\"id\":1,\"heading\":\"What We Offer\",\"description\":\"Discover our range of financial services designed to help you save, grow, and manage your money with trust and convenience.\",\"badgeText\":\"Products we offer:\",\"image\":\"assets\\/vector art 2 section 2.png\",\"loans\":[\"Agricultural Loan\",\"Industrial Loan\",\"Commercial Loan\",\"Medical Loan\",\"Housing Loan\",\"Salary Loan\",\"O.F.W. Loan\",\"Pension Loan \\/ SSS \\/ GSIS\",\"Other Government Supervised Loan\"],\"deposits\":[\"Savings Account Deposit\",\"Time Deposit\"]}]', '2026-09-25 05:34:32'),
(6, 'info-boxes', '[{\"id\":1,\"type\":\"list\",\"title\":\"Members (Test)\",\"items\":[\"Philippine Deposit Insurance Corporation\",\"Rural Bankers Association of the Philippines\",\"Confederation of Southern Tagalog Rural Bank\"]},{\"id\":2,\"type\":\"list\",\"title\":\"Services We Offer\",\"items\":[\"Bancnet - POS (Point of Sale)\",\"Cash Out\"]},{\"id\":3,\"type\":\"cta\",\"title\":\"Let us know what you want...\",\"url\":\"#\"}]', '2026-09-25 06:34:10'),
(7, 'footer', '{\"brandName\":\"Rural Bank of Paete, Inc.\",\"copyright\":\"© 2026 Rural Bank of Paete, Inc. All rights reserved.\",\"links\":[{\"label\":\"Home\",\"url\":\"index.html\"},{\"label\":\"About Us\",\"url\":\"about.html\"},{\"label\":\"Privacy Policy\",\"url\":\"#\"},{\"label\":\"Terms of Service\",\"url\":\"#\"}]}', '2026-09-25 08:11:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `page_sections`
--
ALTER TABLE `page_sections`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `section` (`section`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `page_sections`
--
ALTER TABLE `page_sections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
