-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 05, 2025 at 07:20 PM
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
-- Database: `portal`
--

-- --------------------------------------------------------

--
-- Table structure for table `company_profiles`
--

CREATE TABLE `company_profiles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `company_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `company_profiles`
--

INSERT INTO `company_profiles` (`id`, `user_id`, `company_name`, `description`, `website`) VALUES
(2, 4, 'Amal', 'at18vxc', 'https://mail.google.com/');

-- --------------------------------------------------------

--
-- Table structure for table `job_applications`
--

CREATE TABLE `job_applications` (
  `id` int(11) NOT NULL,
  `job_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `application_date` timestamp NULL DEFAULT current_timestamp(),
  `status` enum('pending','reviewed','accepted','rejected') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job_applications`
--

INSERT INTO `job_applications` (`id`, `job_id`, `student_id`, `application_date`, `status`) VALUES
(27, 5705, 1, '2024-12-15 06:23:35', 'pending'),
(28, 5706, 1, '2024-12-15 19:13:00', 'rejected'),
(29, 5708, 6, '2025-01-01 16:43:46', 'accepted'),
(30, 5707, 1, '2025-01-01 18:25:06', 'accepted'),
(31, 5708, 1, '2025-01-02 06:16:37', 'reviewed'),
(32, 5708, 1, '2025-01-02 06:18:43', 'reviewed');

-- --------------------------------------------------------

--
-- Table structure for table `job_listings`
--

CREATE TABLE `job_listings` (
  `id` int(11) NOT NULL,
  `company_id` int(11) DEFAULT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `requirements` text DEFAULT NULL,
  `salary` varchar(50) DEFAULT NULL,
  `location` varchar(100) DEFAULT NULL,
  `job_type` enum('full-time','part-time','contract','internship') DEFAULT 'full-time',
  `image_path` varchar(255) DEFAULT NULL,
  `posted_at` timestamp NULL DEFAULT current_timestamp(),
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `expiration_date` datetime DEFAULT (`posted_at` + interval 30 day)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job_listings`
--

INSERT INTO `job_listings` (`id`, `company_id`, `title`, `description`, `requirements`, `salary`, `location`, `job_type`, `image_path`, `posted_at`, `status`, `expiration_date`) VALUES
(5705, 2, 'Software Developer', 'Design, develop, and maintain software applications. Collaborate with cross-functional teams to define and implement new features.', 'Bachelor’s degree in Computer Science or related field.\r\nProficiency in programming languages like Java, Python, or C++.', '₹6,00,000 - ₹8,00,000', 'Bengaluru, Karnataka', 'full-time', NULL, '2024-12-15 06:08:15', 'approved', '2025-01-14 07:08:15'),
(5706, 2, 'Digital Marketing Specialist', 'Develop and manage digital marketing campaigns, including SEO, PPC, and social media marketing. Analyze website traffic and optimize campaigns for better ROI.', 'Bachelor’s degree in Marketing or equivalent.\r\nExperience with Google Analytics, SEM, and SEO tools.\r\nExcellent communication skills.', '₹4,00,000 - ₹6,00,000 ', 'Mumbai, Maharashtra', 'full-time', NULL, '2024-12-15 06:27:06', 'approved', '2025-01-14 07:27:06'),
(5707, 2, 'HR Manager', 'Oversee recruitment, employee relations, and performance management. Develop HR policies and ensure compliance with labor laws.', 'MBA in HR Management or equivalent.\r\n5+ years of experience in HR roles.\r\nStrong leadership and organizational skills.', '₹7,50,000 - ₹10,00,000', 'Hyderabad, Telangana', 'full-time', NULL, '2024-12-15 06:28:08', 'approved', '2025-01-14 07:28:08'),
(5708, 2, 'Data Analyst', 'Analyze datasets to extract insights and create visual reports for decision-making. Collaborate with teams to define data-driven strategies.', 'Bachelor’s degree in Statistics, Mathematics, or related field.\r\nProficiency in SQL, Python, and data visualization tools like Tableau.\r\nStrong analytical skills.', '₹5,00,000 - ₹7,00,000', 'Pune, Maharashtra', 'full-time', NULL, '2024-12-15 06:29:11', 'approved', '2025-01-14 07:29:11'),
(5710, 2, 'hi', 'nn', 'nvn', 'vnv', 'nvn', 'contract', NULL, '2025-01-02 06:26:49', 'approved', '2025-02-01 07:26:49');

-- --------------------------------------------------------

--
-- Table structure for table `student_profiles`
--

CREATE TABLE `student_profiles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `full_name` varchar(100) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `education` text DEFAULT NULL,
  `skills` text DEFAULT NULL,
  `resume_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student_profiles`
--

INSERT INTO `student_profiles` (`id`, `user_id`, `full_name`, `date_of_birth`, `education`, `skills`, `resume_path`) VALUES
(1, 3, 'amal ser', '2024-10-17', 'BCA', 'zczc', 'uploads/resumes/resume_6707b4a6e758b.pdf'),
(2, 6, 'amal', '2024-10-20', 'hhk', 'jkj', NULL),
(4, 9, 'adarsh', NULL, NULL, NULL, NULL),
(5, 12, '', '2024-12-26', 'hi', 'ghu', 'uploads/resumes/resume_676d702ab0d51.pdf'),
(6, 14, '', '2025-01-01', 'BCA', 'Ka', 'uploads/resumes/resume_6775708c29d11.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `user_type` enum('admin','company','student') NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `user_type`, `created_at`) VALUES
(1, 'amal', '$2y$10$dd5h.fl2owdGSQRAFM5oHuD07C8Ma7OFTWZBjNI0A4Dwdhm.bhaqi', 'at183267@gmail.com', 'admin', '2024-10-10 10:46:50'),
(3, 'amal01', '$2y$10$Bvz/6koyzESSzcnK3V0avuDkCrsr0E/WKsSEB/TzPzNpcLzTZFjl.', 'chaudaryrahul106@gmail.com', 'student', '2024-10-10 10:49:29'),
(4, 'sanik', '$2y$10$/aw2lhfUAOTr7aT/8ycl6.bN05xZyuRRfWspDrPAKB3cHYppRrJG2', 'watchasports@gmail.com', 'company', '2024-10-10 10:55:04'),
(6, 'a20', '$2y$10$04wfYJt.FklsQ52iG4HGqueTZ.7iy33b7kS3oFG9joQbzripKB2zO', 'whatsappbotmain@gmail.com', 'student', '2024-10-10 18:11:54'),
(8, 'adith', '$2y$10$ZmQXcxmrDyFW5SZpNvO.zehj1aD/Ff4EuchbtDgitCo6BMmJRq/FK', 'adith@gmail.com', 'company', '2024-11-27 19:26:53'),
(9, 'adarsh', '$2y$10$b04zyK6SmGz5rf2xDk6IdOWAmglWrYolELFXmm.RZ1RQr4X3O.ho2', 'adarsh@outlook.com', 'student', '2024-11-27 19:28:36'),
(10, 'a201', '$2y$10$16SE/8GoLeMsESGN9i4M5u1gXiqO85poYTZvF87U1zikekKtX3vyK', 'sa@gmail.com', 'student', '2024-12-26 15:01:00'),
(12, 'Lisa', '$2y$10$P6sO1wjcY//hB.iq3Bj70O1j9naVIvn5QHQMvQ9IhGslF3JTWTm3q', 'Lisa@gmail.com', 'student', '2024-12-26 15:02:20'),
(14, 'devis', '$2y$10$/BI54R/KToUcTRaILlzfk.A7Vk6O/zvfznowZpL88cypg2a8kzlES', 'Lisa1@gmail.com', 'student', '2025-01-01 16:41:43');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `company_profiles`
--
ALTER TABLE `company_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `job_applications`
--
ALTER TABLE `job_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_id` (`job_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `job_listings`
--
ALTER TABLE `job_listings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `company_id` (`company_id`);

--
-- Indexes for table `student_profiles`
--
ALTER TABLE `student_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `company_profiles`
--
ALTER TABLE `company_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `job_applications`
--
ALTER TABLE `job_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `job_listings`
--
ALTER TABLE `job_listings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5712;

--
-- AUTO_INCREMENT for table `student_profiles`
--
ALTER TABLE `student_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `company_profiles`
--
ALTER TABLE `company_profiles`
  ADD CONSTRAINT `company_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `job_applications`
--
ALTER TABLE `job_applications`
  ADD CONSTRAINT `job_applications_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `job_listings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `job_applications_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `student_profiles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `job_listings`
--
ALTER TABLE `job_listings`
  ADD CONSTRAINT `job_listings_ibfk_1` FOREIGN KEY (`company_id`) REFERENCES `company_profiles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `student_profiles`
--
ALTER TABLE `student_profiles`
  ADD CONSTRAINT `student_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
