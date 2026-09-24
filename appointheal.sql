-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 14, 2025 at 06:18 PM
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
-- Database: `appointheal`
--

-- --------------------------------------------------------

--
-- Table structure for table `apply`
--

CREATE TABLE `apply` (
  `PID` int(11) NOT NULL,
  `AID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `AID` int(11) NOT NULL,
  `PID` int(11) DEFAULT NULL,
  `DID` int(11) DEFAULT NULL,
  `day` varchar(20) DEFAULT NULL,
  `Time` time DEFAULT NULL,
  `Status` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE `doctor` (
  `DID` int(11) NOT NULL,
  `Name` varchar(50) DEFAULT NULL,
  `Specialize` varchar(30) DEFAULT NULL,
  `Phone` varchar(15) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`DID`, `Name`, `Specialize`, `Phone`, `Email`) VALUES
(1, 'Dr. Aisha Khan', 'Cardiologist', '01876543210', 'aisha.khan@example.com'),
(2, 'Dr. Shofikul Islam', 'Neurologist', '01876543211', 'sofikul.islam@example.com'),
(3, 'Dr. Priya Sharma', 'Orthopedic', '01876543212', 'priya.sharma@example.com'),
(4, 'Dr. Siam Ahamed', 'Dermatologist', '01876543213', 'siam.ahamed@example.com'),
(5, 'Dr. Sofia Khatun', 'Orthopedic', '01876543214', 'sofia.katun@example.com');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_available_days`
--

CREATE TABLE `doctor_available_days` (
  `DID` int(11) NOT NULL,
  `days` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor_available_days`
--

INSERT INTO `doctor_available_days` (`DID`, `days`) VALUES
(1, 'Mon'),
(1, 'Sat'),
(1, 'Wed'),
(2, 'Sun'),
(2, 'Thu'),
(2, 'Tue'),
(3, 'Mon'),
(3, 'Sat'),
(3, 'Wed'),
(4, 'Sun'),
(4, 'Thu'),
(4, 'Tue'),
(5, 'Fri');

-- --------------------------------------------------------

--
-- Table structure for table `doctor_time`
--

CREATE TABLE `doctor_time` (
  `DID` int(11) NOT NULL,
  `time` time NOT NULL,
  `isBooked` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor_time`
--

INSERT INTO `doctor_time` (`DID`, `time`, `isBooked`) VALUES
(1, '15:00:00', 0),
(1, '16:00:00', 0),
(1, '17:00:00', 0),
(2, '15:00:00', 0),
(2, '16:00:00', 0),
(2, '17:00:00', 0),
(3, '18:00:00', 0),
(3, '19:00:00', 0),
(3, '20:00:00', 0),
(4, '18:00:00', 0),
(4, '19:00:00', 0),
(4, '20:00:00', 0),
(5, '15:00:00', 0),
(5, '16:00:00', 0),
(5, '17:00:00', 0),
(5, '18:00:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `generaldoctor`
--

CREATE TABLE `generaldoctor` (
  `DID` int(11) NOT NULL,
  `ServiceHour` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `generaldoctor`
--

INSERT INTO `generaldoctor` (`DID`, `ServiceHour`) VALUES
(1, '03:00 PM - 06:00 PM'),
(3, '06:00 PM - 09:00 PM'),
(5, '03:00 PM - 07:00 PM');

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `PID` int(11) NOT NULL,
  `Name` varchar(50) DEFAULT NULL,
  `Gender` varchar(10) DEFAULT NULL,
  `DateOfBirth` date DEFAULT NULL,
  `Phone` varchar(15) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Address` text DEFAULT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`PID`, `Name`, `Gender`, `DateOfBirth`, `Phone`, `Email`, `Address`, `password`) VALUES
(2, 'MD Jahidur Rahman Mahin', 'Male', '2000-10-04', '01754633726', 'jahidur.rahman.mahin@g.bracu.ac.bd', 'Dhaka, Bangladesh', '$2y$10$vjXY3WjAb.2348OydDOtH.kwvlM/faXYLM/ZDyxl54dVQ.ZwVg2yu'),
(3, 'Johura Fatima Lucky', 'Female', '1982-04-02', '01688317676', 'lostapato79@gmail.com', 'Dhaka, Bangladesh', '$2y$10$IpwCGD4AEuv/x1/ba.kmaOk9FeLRacnHFHBHLEhndxIyKLfsSgER2');

-- --------------------------------------------------------

--
-- Table structure for table `surgeondoctor`
--

CREATE TABLE `surgeondoctor` (
  `DID` int(11) NOT NULL,
  `SurgerySpecialty` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `surgeondoctor`
--

INSERT INTO `surgeondoctor` (`DID`, `SurgerySpecialty`) VALUES
(2, 'Brain and Spine Surgery'),
(4, 'Skin Tumor Removal');

-- --------------------------------------------------------

--
-- Table structure for table `treatedby`
--

CREATE TABLE `treatedby` (
  `PID` int(11) NOT NULL,
  `DID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `apply`
--
ALTER TABLE `apply`
  ADD PRIMARY KEY (`PID`,`AID`),
  ADD KEY `AID` (`AID`);

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`AID`),
  ADD KEY `PID` (`PID`),
  ADD KEY `DID` (`DID`);

--
-- Indexes for table `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`DID`);

--
-- Indexes for table `doctor_available_days`
--
ALTER TABLE `doctor_available_days`
  ADD PRIMARY KEY (`DID`,`days`);

--
-- Indexes for table `doctor_time`
--
ALTER TABLE `doctor_time`
  ADD PRIMARY KEY (`DID`,`time`);

--
-- Indexes for table `generaldoctor`
--
ALTER TABLE `generaldoctor`
  ADD PRIMARY KEY (`DID`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`PID`);

--
-- Indexes for table `surgeondoctor`
--
ALTER TABLE `surgeondoctor`
  ADD PRIMARY KEY (`DID`);

--
-- Indexes for table `treatedby`
--
ALTER TABLE `treatedby`
  ADD PRIMARY KEY (`PID`,`DID`),
  ADD KEY `DID` (`DID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `AID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `doctor`
--
ALTER TABLE `doctor`
  MODIFY `DID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `patient`
--
ALTER TABLE `patient`
  MODIFY `PID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `apply`
--
ALTER TABLE `apply`
  ADD CONSTRAINT `apply_ibfk_1` FOREIGN KEY (`PID`) REFERENCES `patient` (`PID`),
  ADD CONSTRAINT `apply_ibfk_2` FOREIGN KEY (`AID`) REFERENCES `appointment` (`AID`);

--
-- Constraints for table `appointment`
--
ALTER TABLE `appointment`
  ADD CONSTRAINT `appointment_ibfk_1` FOREIGN KEY (`PID`) REFERENCES `patient` (`PID`),
  ADD CONSTRAINT `appointment_ibfk_2` FOREIGN KEY (`DID`) REFERENCES `doctor` (`DID`);

--
-- Constraints for table `doctor_available_days`
--
ALTER TABLE `doctor_available_days`
  ADD CONSTRAINT `doctor_available_days_ibfk_1` FOREIGN KEY (`DID`) REFERENCES `doctor` (`DID`);

--
-- Constraints for table `doctor_time`
--
ALTER TABLE `doctor_time`
  ADD CONSTRAINT `doctor_time_ibfk_1` FOREIGN KEY (`DID`) REFERENCES `doctor` (`DID`);

--
-- Constraints for table `generaldoctor`
--
ALTER TABLE `generaldoctor`
  ADD CONSTRAINT `generaldoctor_ibfk_1` FOREIGN KEY (`DID`) REFERENCES `doctor` (`DID`);

--
-- Constraints for table `surgeondoctor`
--
ALTER TABLE `surgeondoctor`
  ADD CONSTRAINT `surgeondoctor_ibfk_1` FOREIGN KEY (`DID`) REFERENCES `doctor` (`DID`);

--
-- Constraints for table `treatedby`
--
ALTER TABLE `treatedby`
  ADD CONSTRAINT `treatedby_ibfk_1` FOREIGN KEY (`PID`) REFERENCES `patient` (`PID`),
  ADD CONSTRAINT `treatedby_ibfk_2` FOREIGN KEY (`DID`) REFERENCES `doctor` (`DID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
