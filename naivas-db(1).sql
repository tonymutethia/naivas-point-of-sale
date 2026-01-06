-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 12, 2025 at 07:08 PM
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
-- Database: `naivas-db`
--

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
(16, 'Groceries '),
(18, 'Fresh Produce'),
(21, 'Bakery'),
(22, 'Beverages'),
(24, 'Fresh produce'),
(25, 'Dairy&eggs'),
(27, 'Meat&sea food'),
(28, 'Frozen foods'),
(29, 'Health & beauty '),
(31, 'Household supplies '),
(32, 'Baby products '),
(33, 'Pet supplies ');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` enum('unpaid','paid') DEFAULT 'unpaid',
  `order_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `product_id`, `quantity`, `total`, `status`, `order_date`) VALUES
(46, 0, 13, 1, 15000.00, 'unpaid', '2025-06-11 19:59:57'),
(47, 0, 13, 1, 15000.00, 'paid', '2025-06-12 08:00:41'),
(48, 0, 14, 2, 14000.00, 'unpaid', '2025-06-12 08:03:22'),
(49, 0, 15, 1, 6000.00, 'paid', '2025-06-12 08:12:52');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `category` varchar(50) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `category`, `image`, `created_at`) VALUES
(13, 'hennessy', 15000.00, 'Beverages', 'Uploads/WhatsApp Image 2025-06-11 at 21.24.01_174e922b.jpg', '2025-06-11 19:39:10'),
(14, 'jameson', 7000.00, 'Beverages', 'Uploads/WhatsApp Image 2025-06-11 at 21.24.01_8834efdf.jpg', '2025-06-11 19:39:48'),
(15, 'black lable', 6000.00, 'Beverages', 'Uploads/WhatsApp Image 2025-06-11 at 21.24.02_0ff01d50.jpg', '2025-06-11 19:40:12'),
(16, 'apple', 120.00, 'Fresh Produce', 'Uploads/juan-ellul-KLMRrXGuxdI-unsplash.jpg', '2025-06-11 19:52:46'),
(17, 'cake', 500.00, 'Bakery', 'Uploads/yeh-xintong-go3DT3PpIw4-unsplash.jpg', '2025-06-11 19:53:45'),
(18, 'jackdaniel', 6000.00, 'Beverages', 'Uploads/jd.jpg', '2025-06-11 19:54:32');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `product_name` varchar(100) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `sale_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `customer_name`, `product_name`, `quantity`, `total_price`, `sale_date`) VALUES
(1, 'John Mwangi', 'Sofa Set', 1, 25000.00, '2025-05-02'),
(2, 'Mary Wanjiku', 'Gas Cooker', 2, 15000.00, '2025-05-04'),
(3, 'Peter Otieno', 'TV 55 inch', 1, 40000.00, '2025-05-06'),
(4, 'Lucy Njeri', 'Dining Table', 1, 30000.00, '2025-05-08'),
(5, 'James Ouma', 'Office Desk', 3, 21000.00, '2025-05-10'),
(6, 'Angela Atieno', 'Bookshelf', 2, 12000.00, '2025-05-12'),
(7, 'Samuel Kipkoech', 'Bed (5x6)', 1, 18000.00, '2025-05-13'),
(8, 'Grace Nduta', 'Mattress (6 inch)', 1, 8500.00, '2025-05-15'),
(9, 'Paul Kariuki', 'Laptop Table', 2, 9000.00, '2025-05-18'),
(10, 'Nancy Mumo', 'Wardrobe', 1, 25000.00, '2025-05-20'),
(11, 'Brian Muthoni', 'Blender', 3, 7500.00, '2025-05-22'),
(12, 'Agnes Chebet', 'Microwave', 1, 11000.00, '2025-05-23'),
(13, 'Elvis Achieng', 'Study Desk', 2, 14000.00, '2025-05-25'),
(14, 'Jane Nyambura', 'Office Chair', 1, 6000.00, '2025-05-28'),
(15, 'Collins Maina', 'TV Stand', 1, 10000.00, '2025-05-30'),
(16, 'Faith Kimani', 'Fridge 200L', 1, 38000.00, '2025-06-01'),
(17, 'Dennis Ouma', 'Electric Kettle', 2, 4000.00, '2025-06-02'),
(18, 'Susan Wekesa', 'Dining Chairs (Set of 4)', 1, 12000.00, '2025-06-04'),
(19, 'Robert Njoroge', 'Curtain Set', 3, 4500.00, '2025-06-06'),
(20, 'Diana Wambui', 'Shoe Rack', 2, 7000.00, '2025-06-09');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `password`) VALUES
(2, 'tony', 'snn', 'tony@gmail.com', '$2y$10$DR8YL.uXLp7geEzjYKemqulbMvOSrVeojJFw8hIPzVC3vOeVuxxJG'),
(3, 'DENNIS', 'kimah', 'deno@gmail.com', '$2y$10$XuAlO.8I4a1Nivy7HhzuyONjYaM.5VxylQ7YCtVn2y3MNmIA6qQg.'),
(4, 'ann', 'aka', 'ann@gmail.com', '$2y$10$fH8mdugxe.pP45H.IxKSyOckTRclAIpJdy3kagV9f5zD2/8.ySSpW'),
(5, 'mark', 'mark', 'mark@gmail.com', '$2y$10$thMY1OM3MkymfTmQev3DU.OceHxdUt0SO/uzlsAh4tsPRGFBdotDu'),
(6, 'ERICK', 'MURIGI', 'erickmurigi@gmail.com', '$2y$10$rbVjTGnUtBxRrlIGWn.8ju9m0ncihxjWVTc6arLA57M5k1klkCwVW'),
(7, 'bb', 'b', 'n@gmail.com', '$2y$10$N3KPZxkyHDq8dzKa/5VIo.E5IUvyUuvDB2wAGlLoRYOtQb5WlbRhW');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
