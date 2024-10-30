-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 30, 2024 at 01:34 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blog`
--

-- --------------------------------------------------------

--
-- Table structure for table `articles`
--

CREATE TABLE `articles` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `author_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `upload_date` date DEFAULT curdate(),
  `view_count` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `articles`
--

INSERT INTO `articles` (`id`, `title`, `content`, `image`, `author_id`, `category_id`, `upload_date`, `view_count`) VALUES
(1, 'Cara Membuat Sebuah Website', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut purus eros, finibus sed est non, accumsan vestibulum diam. Proin sit amet nibh malesuada libero dictum ultrices at vel libero. Nam erat elit, rutrum non scelerisque id, tristique quis risus. Ut vitae dui non magna efficitur condimentum. Sed tincidunt tellus ut fringilla elementum. Praesent posuere, felis semper porta blandit, purus purus tempus nisl, at faucibus risus odio sed elit. Duis tristique, libero eu vulputate bibendum, dolor mi volutpat tortor, eget tincidunt ex purus at magna. Donec nec tortor eget sem porttitor luctus. In gravida dictum ligula eget tincidunt. Phasellus mattis hendrerit rutrum. Suspendisse interdum tortor a dolor tincidunt eleifend. Integer consequat bibendum urna ornare lobortis. Vivamus malesuada egestas sagittis.', 'assets\\images\\1.jpg', 1, 1, '2024-10-30', 5),
(2, 'Hidup sehat dengan bakwan jagung', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Quisque auctor sagittis mi, id molestie eros volutpat a. Morbi a congue libero. Vivamus dignissim augue vitae orci vestibulum, vel dictum arcu condimentum. Sed magna enim, mattis sed nunc vitae, volutpat condimentum mi. Ut felis arcu, fringilla sit amet sapien imperdiet, dignissim finibus augue. Sed est mi, consequat accumsan aliquam sit amet, lobortis at ligula. Nullam dignissim justo vel elit fermentum, in tincidunt eros dapibus. Quisque dapibus elementum nisi ut lobortis. Etiam quis nisi ipsum. Proin sit amet ultrices nunc. Nullam at erat felis. Proin vel ipsum ac turpis consequat aliquam. Etiam fermentum pretium ornare. Curabitur accumsan sit amet est dignissim tincidunt. Morbi et metus molestie, pulvinar dolor eget, feugiat ipsum.', 'assets\\images\\2.jpg', 1, 2, '2024-10-30', 9),
(3, 'Kapan Judi Online Bisa Diblokir Sepenuhnya?', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Aliquam elementum augue neque, eu efficitur ante viverra a. Fusce urna felis, pulvinar sit amet mattis quis, commodo vitae eros. Morbi quam ex, porta quis arcu sit amet, ultricies accumsan lacus. Nulla gravida sem nibh, quis posuere risus lacinia et. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Etiam at nulla nisi. In dignissim imperdiet lorem sed imperdiet.\r\n\r\nNulla nec nulla ac nulla egestas sollicitudin. Praesent interdum viverra dapibus. Ut pulvinar sollicitudin libero, vitae ullamcorper dui. Donec quis elit est. Praesent est dolor, bibendum non feugiat ac, consectetur in tortor. Donec dictum urna nulla, sed vehicula nibh rhoncus a. Nunc et urna nisl. Nulla eget ex condimentum, molestie mi nec, iaculis felis. In vestibulum vulputate rhoncus. Ut porttitor tempor rhoncus. Donec sit amet suscipit nulla. Sed feugiat rhoncus hendrerit. Vestibulum eget sapien lorem.', 'D:\\Download\\WhatsApp Image 2024-09-10 at 16.06.03_a1519749.jpg', 1, 1, '2024-10-30', 2);

-- --------------------------------------------------------

--
-- Table structure for table `authors`
--

CREATE TABLE `authors` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `authors`
--

INSERT INTO `authors` (`id`, `name`) VALUES
(1, 'Admin'),
(2, 'Co Admin');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Technology'),
(2, 'Lifestyle');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `articles`
--
ALTER TABLE `articles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author_id` (`author_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `authors`
--
ALTER TABLE `authors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `articles`
--
ALTER TABLE `articles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `authors`
--
ALTER TABLE `authors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `authors` (`id`),
  ADD CONSTRAINT `articles_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
