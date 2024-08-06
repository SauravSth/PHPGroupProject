-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 06, 2024 at 04:05 AM
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
-- Database: `cars`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `model_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `makes`
--

CREATE TABLE `makes` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `makes`
--

INSERT INTO `makes` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Dodge', '2024-08-02 18:28:06', '2024-08-02 18:28:06'),
(2, 'Ford', '2024-08-02 18:28:06', '2024-08-02 18:28:06'),
(3, 'Volkswagen', '2024-08-02 18:28:06', '2024-08-02 18:28:06'),
(4, 'Honda', '2024-08-02 18:28:06', '2024-08-02 18:28:06'),
(5, 'Mazda', '2024-08-02 18:28:06', '2024-08-02 18:28:06'),
(6, 'Hyundai', '2024-08-02 18:28:06', '2024-08-02 18:28:06'),
(7, 'Tesla', '2024-08-02 18:28:06', '2024-08-02 18:28:06'),
(8, 'Nissan', '2024-08-02 18:28:06', '2024-08-02 18:28:06'),
(9, 'Kia', '2024-08-02 18:28:06', '2024-08-02 18:28:06');

-- --------------------------------------------------------

--
-- Table structure for table `models`
--

CREATE TABLE `models` (
  `id` int(11) NOT NULL,
  `make_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `year` year(4) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `color` varchar(20) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `models`
--

INSERT INTO `models` (`id`, `make_id`, `name`, `year`, `price`, `description`, `color`, `image`, `created_at`, `updated_at`) VALUES
(1, 1, 'Challenger', '2017', 37590.00, 'The Dodge Challenger is a retro-inspired muscle car that combines iconic styling with modern performance. With its powerful V8 engine options, aggressive design, and comfortable interior, the Challenger delivers exhilarating performance on the road while offering a range of high-tech features and driver-assist systems.', 'Orange', '../public/img/dodge-challenger.jpg', '2024-08-02 18:31:18', '2024-08-06 00:13:16'),
(2, 2, 'F-150 XLT', '2017', 30990.00, 'The Ford F-150 XLT is a versatile and capable full-size pickup truck known for its durability and performance. It offers a range of powerful engine options, including turbocharged EcoBoost variants, and comes with advanced technology features. The XLT trim adds extra comfort and convenience, making it suitable for both work and everyday driving.', 'Dark Grey', '../public/img/ford-f150xt.jpg', '2024-08-02 18:31:18', '2024-08-06 00:13:16'),
(3, 3, 'Jetta Trendline', '2023', 23990.00, 'The Volkswagen Jetta Trendline is a compact sedan that offers a blend of German engineering, practicality, and efficiency. It features a sleek design, a comfortable and well-appointed interior, and advanced safety and infotainment systems. The Jetta is known for its smooth ride and reliable performance, making it a great choice for daily commuting.', 'White', '../public/img/volks-jetta.jpg', '2024-08-02 18:31:18', '2024-08-06 00:13:16'),
(4, 4, 'Accord Touring 2.0', '2020', 28990.00, 'The Honda Accord Touring 2.0 is a premium midsize sedan that combines performance, luxury, and technology. It features a turbocharged 2.0-liter engine that delivers strong acceleration, along with a refined and spacious interior. The Touring trim includes advanced driver-assistance features, a premium audio system, and a sleek, modern design.', 'Dark Grey', '../public/img/honda-accord-touring.jpg', '2024-08-02 18:31:18', '2024-08-06 00:13:16'),
(5, 5, 'CX-3 GX', '2019', 21590.00, 'The Mazda CX-3 GX is a subcompact crossover that offers sporty handling and a stylish design. It features a responsive 2.0-liter engine, a driver-focused interior, and a range of safety features. The CX-3 GX is an excellent choice for those who want a practical and fun-to-drive vehicle that stands out in the crowded crossover market.', 'Blue', '../public/img/mazda-cx-3.jpg', '2024-08-02 18:31:18', '2024-08-06 00:13:16'),
(6, 4, 'Civic Sedan Touring', '2022', 28590.00, 'The Honda Civic Sedan Touring is a compact sedan that combines efficiency, technology, and premium features. The Touring trim offers a turbocharged engine, a luxurious interior with leather seats, advanced safety features, and a high-quality audio system. The Civic is known for its reliability and strong resale value.', 'White', '../public/img/honda-civic-touring.jpg', '2024-08-02 18:31:18', '2024-08-06 00:13:16'),
(7, 2, 'Mustang EcoBoost', '2018', 23590.00, 'The Ford Mustang EcoBoost is a modern muscle car that offers impressive performance with a more fuel-efficient turbocharged engine. It features a sleek and aggressive design, sporty handling, and a high-tech interior. The EcoBoost model provides an exciting driving experience while being more economical than its V8 counterparts.', 'Navy Blue', '../public/img/ford-ecoboost.jpg', '2024-08-02 18:31:18', '2024-08-06 00:13:16'),
(8, 2, 'Mustang GT', '2021', 59980.00, 'The Ford Mustang GT is a legendary American muscle car that comes with a powerful V8 engine, delivering exhilarating performance and a throaty exhaust note. It features a bold and aggressive design, advanced technology, and a well-crafted interior. The Mustang GT is designed for those who crave a thrilling and iconic driving experience.', 'Silver', '../public/img/ford-mustang-gt.jpg', '2024-08-02 18:31:18', '2024-08-06 00:13:16'),
(9, 6, 'Ioniq 5', '2023', 42990.00, 'The Hyundai Ioniq 5 is a futuristic electric crossover that offers impressive range, fast charging capabilities, and a spacious, tech-forward interior. With its bold design, advanced driver-assistance features, and innovative use of sustainable materials, the Ioniq 5 represents the next generation of electric vehicles.', 'Matte Grey', '../public/img/hyundai-ioniq-5.jpg', '2024-08-02 18:31:18', '2024-08-06 00:13:16'),
(10, 6, 'Elantra', '2021', 21990.00, 'The Hyundai Elantra is a compact sedan known for its sharp design, efficiency, and advanced technology. It features a spacious interior with high-quality materials, a range of safety and infotainment options, and a comfortable ride. The Elantra is a great choice for those looking for a stylish and practical vehicle.', 'White', '../public/img/hyundai-elantra.jpg', '2024-08-02 18:31:18', '2024-08-06 00:13:16'),
(11, 7, 'Model 3', '2022', 38590.00, 'The Tesla Model 3 is a groundbreaking electric sedan that offers impressive range, cutting-edge technology, and a minimalist interior design. Known for its quick acceleration, Autopilot capabilities, and access to Tesla’s Supercharger network, the Model 3 has set a new standard for electric vehicles and continues to be a popular choice among eco-conscious drivers.', 'Dark Grey', '../public/img/tesla-model-3.jpg', '2024-08-02 18:31:18', '2024-08-06 00:13:16'),
(12, 8, 'Qashqai S', '2021', 19990.00, 'The Nissan Qashqai S is a compact crossover that combines a stylish design with practicality and modern technology. It offers a comfortable and spacious interior, a smooth ride, and advanced safety features. The Qashqai S is designed for urban driving and provides a versatile option for those seeking a compact SUV.', 'White', '../public/img/nissan-qashqai-s.jpg', '2024-08-02 18:31:18', '2024-08-06 00:13:16'),
(13, 6, 'Sonata Sport 1.6T', '2022', 24990.00, 'The Hyundai Sonata Sport 1.6T is a midsize sedan that offers a balance of sporty performance and comfort. It features a turbocharged 1.6-liter engine, sporty design elements, and a well-equipped interior with advanced technology. The Sonata Sport provides an engaging driving experience while maintaining the practicality of a sedan.', 'Black', '../public/img/hyundai-sonanta-s.jpg', '2024-08-02 18:31:18', '2024-08-06 00:13:16'),
(14, 9, 'Rio EX', '2023', 23990.00, 'The Kia Rio EX is a subcompact car that offers value, efficiency, and a surprising level of refinement. It features a stylish design, a well-appointed interior with modern technology, and a fuel-efficient engine. The Rio EX is an excellent choice for those looking for an affordable and reliable car that doesn’t compromise on features.', 'White', '../public/img/kia-rio.jpg', '2024-08-02 18:31:18', '2024-08-06 00:13:16'),
(15, 7, 'Model 3 LR', '2023', 44990.00, 'The Tesla Model 3 Long Range is an electric sedan that offers an extended range on a single charge, making it ideal for long-distance travel. It features quick acceleration, Autopilot capabilities, and a minimalist, high-tech interior. The Model 3 LR provides the benefits of electric driving with the added convenience of a longer range.', 'Deep Blue', '../public/img/tesla-model-3-lr.jpg', '2024-08-02 18:31:18', '2024-08-06 00:13:16'),
(16, 5, 'Mazda 3 Sport GS', '2024', 28990.00, 'The Mazda 3 Sport GS is a compact hatchback that offers a blend of sporty performance, stylish design, and practicality. It features a responsive engine, sharp handling, and a well-crafted interior with advanced technology. The Mazda 3 Sport GS is a great choice for those who want a fun-to-drive car that is also versatile and efficient.', 'White', '../public/img/mazda-3-sport.jpg', '2024-08-02 18:31:18', '2024-08-06 00:13:16'),
(17, 2, 'Mustang V6 Coupe', '2016', 21790.00, 'The Ford Mustang V6 Coupe is a classic American sports car that offers a balance of performance and affordability. It features a potent V6 engine, a sleek and aggressive design, and a driver-focused interior. The Mustang V6 Coupe delivers a thrilling driving experience with a distinctive style and presence.', 'Dark Grey', '../public/img/ford-mustang-v6.jpg', '2024-08-02 18:31:18', '2024-08-06 00:13:16');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `order_status` varchar(50) NOT NULL,
  `payment_status` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `model_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `user_type` enum('admin','customer') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `password`, `email`, `first_name`, `last_name`, `phone_number`, `address`, `user_type`, `created_at`, `updated_at`) VALUES
(8, '$2y$10$hlsKanPiRxdKfvVEsp6XAuqgQfs.4dTYmUDq5h4KydJqPE6yfanBS', 'user@admin.com', 'Admin', 'User', '5198850300', '108 University Ave, Waterloo, ON N2J 2W2', 'admin', '2024-08-06 02:03:13', '2024-08-06 02:03:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `model_id` (`model_id`);

--
-- Indexes for table `makes`
--
ALTER TABLE `makes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `models`
--
ALTER TABLE `models`
  ADD PRIMARY KEY (`id`),
  ADD KEY `make_id` (`make_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `model_id` (`model_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `makes`
--
ALTER TABLE `makes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `models`
--
ALTER TABLE `models`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`model_id`) REFERENCES `models` (`id`);

--
-- Constraints for table `models`
--
ALTER TABLE `models`
  ADD CONSTRAINT `models_ibfk_1` FOREIGN KEY (`make_id`) REFERENCES `makes` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`model_id`) REFERENCES `models` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
