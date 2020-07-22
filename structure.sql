-- phpMyAdmin SQL Dump
-- version 4.6.6
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jan 20, 2018 at 06:47 AM
-- Server version: 5.7.17-log
-- PHP Version: 7.1.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `worker_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `assignment`
--

CREATE TABLE `assignment` (
  `id` int(11) NOT NULL,
  `worker` int(255) DEFAULT NULL,
  `date_time` varchar(245) DEFAULT NULL,
  `total_quantity` int(255) DEFAULT NULL,
  `total_amount` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `assignment`
--

INSERT INTO `assignment` (`id`, `worker`, `date_time`, `total_quantity`, `total_amount`) VALUES
(3, 4, '2018-01-18', 72, 3960),
(5, 3, '2018-01-19', 120, 816),
(6, 5, '2018-01-19', 120, 6480);

-- --------------------------------------------------------

--
-- Table structure for table `assignment_item`
--

CREATE TABLE `assignment_item` (
  `id` int(11) NOT NULL,
  `lot_id` int(255) DEFAULT NULL,
  `size` varchar(255) DEFAULT NULL,
  `quantity` int(255) DEFAULT NULL,
  `rate` int(255) DEFAULT NULL,
  `amount` int(255) DEFAULT NULL,
  `assignment_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `assignment_item`
--

INSERT INTO `assignment_item` (`id`, `lot_id`, `size`, `quantity`, `rate`, `amount`, `assignment_id`) VALUES
(8, 2, '7845', 72, 55, 3960, 3),
(12, 2, '', 72, 6, 432, 5),
(13, 3, '', 48, 8, 384, 5),
(17, 6, '44', 72, 50, 3600, 6),
(18, 3, '55', 48, 60, 2880, 6);

-- --------------------------------------------------------

--
-- Table structure for table `company_details`
--

CREATE TABLE `company_details` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `mobile` int(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `gst` varchar(255) DEFAULT NULL,
  `extension` varchar(255) DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `bank_ac_no` varchar(255) DEFAULT NULL,
  `bank_ifsc` varchar(255) DEFAULT NULL,
  `bank_rtgs` varchar(255) DEFAULT NULL,
  `invoice_suffix` varchar(255) DEFAULT NULL,
  `invoice_prefix` varchar(255) DEFAULT NULL,
  `print` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `company_details`
--

INSERT INTO `company_details` (`id`, `name`, `address`, `mobile`, `email`, `gst`, `extension`, `bank_name`, `bank_ac_no`, `bank_ifsc`, `bank_rtgs`, `invoice_suffix`, `invoice_prefix`, `print`) VALUES
(1, 'pine orange pvt ltd', 'kolkata', 8721414, 'pineorange@gmail.com', 'GST12345', 'EXT', 'UBI', 'ub64543233', 'IFSC4654511325', 'RTGS47156', 'IN471SF74', 'INPR67', 1);

-- --------------------------------------------------------

--
-- Table structure for table `lot_details`
--

CREATE TABLE `lot_details` (
  `id` int(11) NOT NULL,
  `date_time` varchar(250) DEFAULT NULL,
  `lot_no` varchar(255) DEFAULT NULL,
  `design_no` varchar(255) DEFAULT NULL,
  `item` varchar(255) DEFAULT NULL,
  `dozon` int(255) DEFAULT NULL,
  `quantity` int(255) DEFAULT NULL,
  `rate` int(255) DEFAULT NULL,
  `amount` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `lot_details`
--

INSERT INTO `lot_details` (`id`, `date_time`, `lot_no`, `design_no`, `item`, `dozon`, `quantity`, `rate`, `amount`) VALUES
(2, '2018-01-18', '3001', '7001', 'Frock', 6, 72, 45, 3240),
(3, '2018-01-18', '3002', '7002', 'watch', 4, 48, 77, 3696),
(4, '2018-01-19', '6001', '9001', 'laptop', 8, 96, 60, 5760),
(5, '2018-01-19', '5002', '', '', 0, 0, 0, 0),
(6, '2018-01-19', '8001', '9002', 'watch', 6, 72, 300, 21600);

-- --------------------------------------------------------

--
-- Table structure for table `payment_method`
--

CREATE TABLE `payment_method` (
  `id` int(25) NOT NULL,
  `text` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `mode` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payment_method`
--

INSERT INTO `payment_method` (`id`, `text`, `description`, `mode`) VALUES
(1, 'cash', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `payment_worker`
--

CREATE TABLE `payment_worker` (
  `id` int(11) NOT NULL,
  `date` varchar(255) DEFAULT NULL,
  `worker_id` int(55) DEFAULT NULL,
  `adjustment` double(55,2) DEFAULT NULL,
  `amount` int(44) DEFAULT NULL,
  `payment_method_id` int(55) DEFAULT NULL,
  `cheque_no` varchar(255) DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `payment_worker`
--

INSERT INTO `payment_worker` (`id`, `date`, `worker_id`, `adjustment`, `amount`, `payment_method_id`, `cheque_no`, `remarks`) VALUES
(3, '2018-01-18', 3, 0.00, 500, 1, '', ''),
(4, '2018-01-19', 5, 200.00, 300, 1, '', '');

-- --------------------------------------------------------

--
-- Table structure for table `print_setup`
--

CREATE TABLE `print_setup` (
  `id` int(11) NOT NULL,
  `text` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `print_setup`
--

INSERT INTO `print_setup` (`id`, `text`, `link`) VALUES
(1, 'link1', NULL),
(2, 'link2', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `receive`
--

CREATE TABLE `receive` (
  `id` int(11) NOT NULL,
  `worker_name` int(255) DEFAULT NULL,
  `date` varchar(255) DEFAULT NULL,
  `total_quantity` int(255) DEFAULT NULL,
  `total_amount` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `receive`
--

INSERT INTO `receive` (`id`, `worker_name`, `date`, `total_quantity`, `total_amount`) VALUES
(3, 2, '2018-01-19', 72, 3960),
(4, 5, '2018-01-19', 120, 6480);

-- --------------------------------------------------------

--
-- Table structure for table `received_item`
--

CREATE TABLE `received_item` (
  `id` int(11) NOT NULL,
  `assignment_item_id` int(11) DEFAULT NULL,
  `receive_id` int(11) DEFAULT NULL,
  `quantity` int(255) DEFAULT NULL,
  `rate` int(255) DEFAULT NULL,
  `amount` int(255) DEFAULT NULL,
  `lot_id` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `received_item`
--

INSERT INTO `received_item` (`id`, `assignment_item_id`, `receive_id`, `quantity`, `rate`, `amount`, `lot_id`) VALUES
(9, 8, 3, 72, 55, 3960, NULL),
(14, 17, 4, 72, 50, 3600, NULL),
(15, 18, 4, 48, 60, 2880, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tb_worker`
--

CREATE TABLE `tb_worker` (
  `id` int(11) NOT NULL,
  `worker_type` varchar(22) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_worker`
--

INSERT INTO `tb_worker` (`id`, `worker_type`) VALUES
(2, 'Sewing'),
(3, 'shoping'),
(4, 'Iron');

-- --------------------------------------------------------

--
-- Table structure for table `tb_worker_details`
--

CREATE TABLE `tb_worker_details` (
  `worker_id` int(11) NOT NULL,
  `worker_type` int(11) DEFAULT NULL,
  `name` varchar(33) DEFAULT NULL,
  `address` varchar(33) DEFAULT NULL,
  `phone_no` varchar(33) DEFAULT NULL,
  `opening_balance` varchar(33) DEFAULT NULL,
  `remarks` varchar(33) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_worker_details`
--

INSERT INTO `tb_worker_details` (`worker_id`, `worker_type`, `name`, `address`, `phone_no`, `opening_balance`, `remarks`) VALUES
(2, 4, 'kishor', 'kalyani', '74855', '200', 'good'),
(3, 3, 'sukanta', 'Kolkata', '78542', '700', 'good'),
(4, 4, 'kapli', 'kolkat', '854572', '300', 'good'),
(5, 2, 'sougata', 'barasat', '8547152', '600', 'good');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `assignment`
--
ALTER TABLE `assignment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `worker` (`worker`);

--
-- Indexes for table `assignment_item`
--
ALTER TABLE `assignment_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lot_id` (`lot_id`),
  ADD KEY `assignment_id` (`assignment_id`);

--
-- Indexes for table `company_details`
--
ALTER TABLE `company_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `print` (`print`);

--
-- Indexes for table `lot_details`
--
ALTER TABLE `lot_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_method`
--
ALTER TABLE `payment_method`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payment_worker`
--
ALTER TABLE `payment_worker`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_method_id` (`payment_method_id`),
  ADD KEY `worker_id` (`worker_id`);

--
-- Indexes for table `print_setup`
--
ALTER TABLE `print_setup`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `receive`
--
ALTER TABLE `receive`
  ADD PRIMARY KEY (`id`),
  ADD KEY `worker_name` (`worker_name`);

--
-- Indexes for table `received_item`
--
ALTER TABLE `received_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `receive_id` (`receive_id`),
  ADD KEY `assignment_item_id` (`assignment_item_id`),
  ADD KEY `lot_id` (`lot_id`);

--
-- Indexes for table `tb_worker`
--
ALTER TABLE `tb_worker`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_worker_details`
--
ALTER TABLE `tb_worker_details`
  ADD PRIMARY KEY (`worker_id`),
  ADD KEY `worker_type` (`worker_type`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `assignment`
--
ALTER TABLE `assignment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `assignment_item`
--
ALTER TABLE `assignment_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `company_details`
--
ALTER TABLE `company_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `lot_details`
--
ALTER TABLE `lot_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `payment_method`
--
ALTER TABLE `payment_method`
  MODIFY `id` int(25) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `payment_worker`
--
ALTER TABLE `payment_worker`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `print_setup`
--
ALTER TABLE `print_setup`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `receive`
--
ALTER TABLE `receive`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `received_item`
--
ALTER TABLE `received_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `tb_worker`
--
ALTER TABLE `tb_worker`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `tb_worker_details`
--
ALTER TABLE `tb_worker_details`
  MODIFY `worker_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `assignment`
--
ALTER TABLE `assignment`
  ADD CONSTRAINT `assignment_ibfk_1` FOREIGN KEY (`worker`) REFERENCES `tb_worker_details` (`worker_id`);

--
-- Constraints for table `assignment_item`
--
ALTER TABLE `assignment_item`
  ADD CONSTRAINT `assignment_item_ibfk_1` FOREIGN KEY (`assignment_id`) REFERENCES `assignment` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `assignment_item_ibfk_2` FOREIGN KEY (`lot_id`) REFERENCES `lot_details` (`id`);

--
-- Constraints for table `company_details`
--
ALTER TABLE `company_details`
  ADD CONSTRAINT `company_details_ibfk_1` FOREIGN KEY (`print`) REFERENCES `print_setup` (`id`);

--
-- Constraints for table `payment_worker`
--
ALTER TABLE `payment_worker`
  ADD CONSTRAINT `payment_worker_ibfk_1` FOREIGN KEY (`worker_id`) REFERENCES `tb_worker_details` (`worker_id`),
  ADD CONSTRAINT `payment_worker_ibfk_2` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_method` (`id`);

--
-- Constraints for table `receive`
--
ALTER TABLE `receive`
  ADD CONSTRAINT `receive_ibfk_1` FOREIGN KEY (`worker_name`) REFERENCES `tb_worker_details` (`worker_id`);

--
-- Constraints for table `received_item`
--
ALTER TABLE `received_item`
  ADD CONSTRAINT `received_item_ibfk_1` FOREIGN KEY (`receive_id`) REFERENCES `receive` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `received_item_ibfk_3` FOREIGN KEY (`assignment_item_id`) REFERENCES `assignment_item` (`id`),
  ADD CONSTRAINT `received_item_ibfk_4` FOREIGN KEY (`lot_id`) REFERENCES `lot_details` (`id`);

--
-- Constraints for table `tb_worker_details`
--
ALTER TABLE `tb_worker_details`
  ADD CONSTRAINT `tb_worker_details_ibfk_1` FOREIGN KEY (`worker_type`) REFERENCES `tb_worker` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
