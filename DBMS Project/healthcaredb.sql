-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 14, 2026 at 01:49 PM
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
-- Database: `healthcaredb`
--

-- --------------------------------------------------------

--
-- Table structure for table `accountant`
--

CREATE TABLE `accountant` (
  `Accountant_ID` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Phone_Number` int(11) NOT NULL,
  `Email_Address` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accountant`
--

INSERT INTO `accountant` (`Accountant_ID`, `Name`, `Phone_Number`, `Email_Address`) VALUES
(3002, 'Peter Parker', 1345453339, 'pp3002@private.com'),
(3003, 'Jessie Pinkman', 1684628885, 'jp3003@private.com');

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `appointment_id` int(11) NOT NULL,
  `Doctor_ID` int(11) NOT NULL,
  `Patient_ID` int(11) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Pending',
  `reason` varchar(50) DEFAULT NULL,
  `date` date NOT NULL,
  `time` time DEFAULT NULL,
  `Manager_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment`
--

INSERT INTO `appointment` (`appointment_id`, `Doctor_ID`, `Patient_ID`, `status`, `reason`, `date`, `time`, `Manager_ID`) VALUES
(101, 2002, 1002, 'Approved', 'fever', '2026-04-10', '10:26:32', 4001),
(102, 2001, 1001, 'Pending', '', '2026-04-23', '20:26:32', 4002),
(104, 2001, 1001, 'Completed', 'djs', '2026-04-17', '19:45:00', NULL),
(105, 2002, 1001, 'Pending', 'fever', '2026-04-18', '19:24:00', 4001),
(106, 2002, 1002, 'Approved', 'Argent', '2026-04-21', '00:27:00', NULL),
(107, 2002, 1001, 'Confirmed', 'Chest pain', '2026-04-17', '18:55:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `appointment_manager`
--

CREATE TABLE `appointment_manager` (
  `Appointment_Manager_ID` int(11) NOT NULL,
  `Appointment_Manager_Name` varchar(50) NOT NULL,
  `Phone_Number` int(11) NOT NULL,
  `Email_Address` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment_manager`
--

INSERT INTO `appointment_manager` (`Appointment_Manager_ID`, `Appointment_Manager_Name`, `Phone_Number`, `Email_Address`) VALUES
(4001, 'Bruce Banner', 1345453338, 'bb4001@private.com'),
(4002, 'Natasha Romanoff', 148462870, 'nr4002@private.com');

-- --------------------------------------------------------

--
-- Table structure for table `care_requests`
--

CREATE TABLE `care_requests` (
  `Req_ID` int(11) NOT NULL,
  `Patient_ID` int(11) NOT NULL,
  `Doctor_ID` int(11) DEFAULT NULL,
  `Category` varchar(100) NOT NULL,
  `Patient_Condition` varchar(255) NOT NULL,
  `Patient_Notes` text DEFAULT NULL,
  `Request_Date` date NOT NULL,
  `Status` varchar(50) DEFAULT 'Pending Review',
  `Doctor_Recommendation` text DEFAULT NULL,
  `Response_Date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `care_requests`
--

INSERT INTO `care_requests` (`Req_ID`, `Patient_ID`, `Doctor_ID`, `Category`, `Patient_Condition`, `Patient_Notes`, `Request_Date`, `Status`, `Doctor_Recommendation`, `Response_Date`) VALUES
(1, 1001, 2001, 'Diet/Lifestyle', 'Up BMI', 'Null', '2026-04-14', 'Responded', 'Eat healthy food avoid junk food', '2026-04-14 10:41:39'),
(2, 1001, 2001, 'General Health', 'Raising weight', 'Null', '2026-04-18', 'Pending Review', 'Null\r\n', '2026-04-14 10:58:48');

-- --------------------------------------------------------

--
-- Table structure for table `doctor`
--

CREATE TABLE `doctor` (
  `Doctor_ID` int(11) NOT NULL,
  `Doctor_Name` varchar(50) NOT NULL,
  `Specialization` varchar(50) NOT NULL,
  `Phone_Number` int(11) NOT NULL,
  `Email_Address` varchar(50) NOT NULL,
  `Status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctor`
--

INSERT INTO `doctor` (`Doctor_ID`, `Doctor_Name`, `Specialization`, `Phone_Number`, `Email_Address`, `Status`) VALUES
(2001, 'J Epstein', 'Surgical', 1145453339, 'je2001@private.com', 'Free'),
(2002, 'Walter White', 'Heart Surgeon', 1484628880, 'ww2002@private.com', 'Busy');

-- --------------------------------------------------------

--
-- Table structure for table `insuranceofficer`
--

CREATE TABLE `insuranceofficer` (
  `iOfficerID` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Phone_Number` int(11) NOT NULL,
  `Email_Address` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `insuranceofficer`
--

INSERT INTO `insuranceofficer` (`iOfficerID`, `Name`, `Phone_Number`, `Email_Address`) VALUES
(6001, 'Bruce Wayne', 1345453352, 'bw6001@private.com'),
(6002, 'Tony Stark', 1685228880, 'ts6002@private.com');

-- --------------------------------------------------------

--
-- Table structure for table `insurance_claim`
--

CREATE TABLE `insurance_claim` (
  `Invoice_ID` int(11) NOT NULL,
  `Patient_ID` int(11) NOT NULL,
  `Amount` decimal(15,2) NOT NULL,
  `Claim_ID` int(11) NOT NULL,
  `Officer_ID` int(11) NOT NULL,
  `Status` varchar(50) NOT NULL DEFAULT 'Pending',
  `Description` varchar(500) DEFAULT NULL,
  `date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `insurance_claim`
--

INSERT INTO `insurance_claim` (`Invoice_ID`, `Patient_ID`, `Amount`, `Claim_ID`, `Officer_ID`, `Status`, `Description`, `date`) VALUES
(502, 1002, 10000.00, 601, 6001, 'Approved', NULL, '2026-04-11'),
(501, 1001, 100.00, 602, 6002, 'Rejected', NULL, '2026-04-11'),
(506, 1002, 1.05, 603, 6001, 'Pending', 'NULL', '2026-04-11'),
(501, 1001, 500000.00, 604, 6001, 'Pending', 'N/A', '2026-04-17');

-- --------------------------------------------------------

--
-- Table structure for table `investigator`
--

CREATE TABLE `investigator` (
  `Investigator_ID` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Phone_Number` int(11) NOT NULL,
  `Email_Address` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `investigator`
--

INSERT INTO `investigator` (`Investigator_ID`, `Name`, `Phone_Number`, `Email_Address`) VALUES
(5001, 'Ronald Coleman', 114555533, 'rc5001@private.com'),
(5002, 'Kevin Levrone', 148463170, 'kl5002@private.com');

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `invoice_Id` int(11) NOT NULL,
  `accountantID` int(11) NOT NULL,
  `patient_Id` int(11) NOT NULL,
  `description` varchar(500) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL DEFAULT 'Due'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `invoice`
--

INSERT INTO `invoice` (`invoice_Id`, `accountantID`, `patient_Id`, `description`, `amount`, `date`, `status`) VALUES
(501, 3003, 1001, 'test', 500000.99, '2026-04-10 21:45:41', 'Due'),
(502, 3002, 1002, 'check up', 510.00, '2026-04-10 21:45:41', 'Paid'),
(506, 3003, 1002, 'NULL', 2929.00, '2026-04-11 14:58:13', 'Due');

-- --------------------------------------------------------

--
-- Table structure for table `medicaltest`
--

CREATE TABLE `medicaltest` (
  `test_ID` int(11) NOT NULL,
  `Doctor_ID` int(11) NOT NULL,
  `Patient_ID` int(11) NOT NULL,
  `Test_Type` varchar(100) NOT NULL,
  `InvestigatorID` int(11) DEFAULT NULL,
  `Priority_Level` varchar(50) NOT NULL,
  `Result_Status` varchar(50) NOT NULL DEFAULT 'Pending',
  `Report_File_Path` blob DEFAULT NULL,
  `Result_Details` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicaltest`
--

INSERT INTO `medicaltest` (`test_ID`, `Doctor_ID`, `Patient_ID`, `Test_Type`, `InvestigatorID`, `Priority_Level`, `Result_Status`, `Report_File_Path`, `Result_Details`) VALUES
(301, 2001, 1001, 'X-Ray', 5001, 'Urgent', 'Completed', NULL, 'n/a'),
(302, 2002, 1002, 'Eye power test', 5001, 'Normal', 'Pending', NULL, 'null'),
(303, 2002, 1002, 'Blood Test', 5001, 'Urgent', 'In Progress', NULL, 'N?A'),
(304, 2001, 1001, 'X-Ray', NULL, 'Urgent', 'Pending', NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `medical_record`
--

CREATE TABLE `medical_record` (
  `record_id` int(11) NOT NULL,
  `report_ID` int(11) DEFAULT NULL,
  `patient_id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `diagnosis` varchar(500) DEFAULT NULL,
  `treatment` varchar(500) DEFAULT NULL,
  `date` date NOT NULL DEFAULT current_timestamp(),
  `additional_Notes` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medical_record`
--

INSERT INTO `medical_record` (`record_id`, `report_ID`, `patient_id`, `doctor_id`, `diagnosis`, `treatment`, `date`, `additional_Notes`) VALUES
(401, 301, 1001, 2001, NULL, NULL, '2026-04-11', ''),
(402, NULL, 1001, 2001, 'n/a', 'n/a', '2026-04-11', 'n/a'),
(403, 301, 1001, 2001, 'n/a', 'n/a', '2026-04-11', 'n/a'),
(405, 302, 1002, 2001, 'n/a', 'n/a', '2026-04-11', 'n/a'),
(406, 303, 1002, 2002, 'Blood Test', 'Vitamin Suppliment', '2026-04-11', 'N/A'),
(407, 304, 1001, 2001, 'NULL', 'NULL', '2026-04-14', 'NULL');

-- --------------------------------------------------------

--
-- Table structure for table `patient`
--

CREATE TABLE `patient` (
  `Patient_ID` int(11) NOT NULL,
  `Patient_Name` varchar(50) NOT NULL,
  `dob` date NOT NULL,
  `Gender` varchar(50) NOT NULL,
  `Email_Address` varchar(50) DEFAULT NULL,
  `Phone_Number` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient`
--

INSERT INTO `patient` (`Patient_ID`, `Patient_Name`, `dob`, `Gender`, `Email_Address`, `Phone_Number`) VALUES
(1001, 'D Khan', '2000-04-15', 'Male', 'dk1001@private.com', 1745453339),
(1002, 'M Rahman', '2003-12-31', 'Female', 'mr1002@private.com', 1684628880);

-- --------------------------------------------------------

--
-- Table structure for table `symptomlog`
--

CREATE TABLE `symptomlog` (
  `log_ID` int(11) NOT NULL,
  `Patient_ID` int(11) NOT NULL,
  `symptom` varchar(50) NOT NULL,
  `additional_notes` varchar(500) DEFAULT NULL,
  `severity` varchar(50) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `symptomlog`
--

INSERT INTO `symptomlog` (`log_ID`, `Patient_ID`, `symptom`, `additional_notes`, `severity`, `date`) VALUES
(201, 1001, 'pain', 'fever', 'Normal', '2026-04-10'),
(202, 1002, 'Loosing mind', 'hallucinate sometimes', 'Urgent', '2026-04-15'),
(203, 1001, 'Headache', 'm', 'Moderate', '2026-04-11'),
(204, 1001, 'Headache', 'm', 'Moderate', '2026-04-11'),
(205, 1001, 'Fever', 'Null', 'Severe', '2026-04-14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accountant`
--
ALTER TABLE `accountant`
  ADD PRIMARY KEY (`Accountant_ID`);

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`appointment_id`),
  ADD KEY `Appointment_Doctor_ID` (`Doctor_ID`),
  ADD KEY `Appointment_Manager_ID` (`Manager_ID`),
  ADD KEY `Appointment_Patient_ID` (`Patient_ID`);

--
-- Indexes for table `appointment_manager`
--
ALTER TABLE `appointment_manager`
  ADD PRIMARY KEY (`Appointment_Manager_ID`);

--
-- Indexes for table `care_requests`
--
ALTER TABLE `care_requests`
  ADD PRIMARY KEY (`Req_ID`),
  ADD KEY `care_Pateint_ID` (`Patient_ID`),
  ADD KEY `care_Doctor_ID` (`Doctor_ID`);

--
-- Indexes for table `doctor`
--
ALTER TABLE `doctor`
  ADD PRIMARY KEY (`Doctor_ID`);

--
-- Indexes for table `insuranceofficer`
--
ALTER TABLE `insuranceofficer`
  ADD PRIMARY KEY (`iOfficerID`);

--
-- Indexes for table `insurance_claim`
--
ALTER TABLE `insurance_claim`
  ADD PRIMARY KEY (`Claim_ID`),
  ADD KEY `Claim_Invoice_ID` (`Invoice_ID`),
  ADD KEY `Claim_Officer_ID` (`Officer_ID`),
  ADD KEY `Claim_Patient_ID` (`Patient_ID`);

--
-- Indexes for table `investigator`
--
ALTER TABLE `investigator`
  ADD PRIMARY KEY (`Investigator_ID`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`invoice_Id`),
  ADD KEY `invoice_Patient_ID` (`patient_Id`),
  ADD KEY `invoice_Accountant_ID` (`accountantID`);

--
-- Indexes for table `medicaltest`
--
ALTER TABLE `medicaltest`
  ADD PRIMARY KEY (`test_ID`),
  ADD KEY `test_Doctor_ID` (`Doctor_ID`),
  ADD KEY `test_Investigator_ID` (`InvestigatorID`),
  ADD KEY `test_Patient_ID` (`Patient_ID`);

--
-- Indexes for table `medical_record`
--
ALTER TABLE `medical_record`
  ADD PRIMARY KEY (`record_id`),
  ADD KEY `record_Doctor_ID` (`doctor_id`),
  ADD KEY `record_Patient_ID` (`patient_id`),
  ADD KEY `record_report_id` (`report_ID`);

--
-- Indexes for table `patient`
--
ALTER TABLE `patient`
  ADD PRIMARY KEY (`Patient_ID`);

--
-- Indexes for table `symptomlog`
--
ALTER TABLE `symptomlog`
  ADD PRIMARY KEY (`log_ID`),
  ADD KEY `log_Patient_ID` (`Patient_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accountant`
--
ALTER TABLE `accountant`
  MODIFY `Accountant_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3004;

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `appointment_manager`
--
ALTER TABLE `appointment_manager`
  MODIFY `Appointment_Manager_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4004;

--
-- AUTO_INCREMENT for table `care_requests`
--
ALTER TABLE `care_requests`
  MODIFY `Req_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `doctor`
--
ALTER TABLE `doctor`
  MODIFY `Doctor_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2003;

--
-- AUTO_INCREMENT for table `insuranceofficer`
--
ALTER TABLE `insuranceofficer`
  MODIFY `iOfficerID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6003;

--
-- AUTO_INCREMENT for table `insurance_claim`
--
ALTER TABLE `insurance_claim`
  MODIFY `Claim_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=608;

--
-- AUTO_INCREMENT for table `investigator`
--
ALTER TABLE `investigator`
  MODIFY `Investigator_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5004;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `invoice_Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=507;

--
-- AUTO_INCREMENT for table `medicaltest`
--
ALTER TABLE `medicaltest`
  MODIFY `test_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=305;

--
-- AUTO_INCREMENT for table `medical_record`
--
ALTER TABLE `medical_record`
  MODIFY `record_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=408;

--
-- AUTO_INCREMENT for table `patient`
--
ALTER TABLE `patient`
  MODIFY `Patient_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1003;

--
-- AUTO_INCREMENT for table `symptomlog`
--
ALTER TABLE `symptomlog`
  MODIFY `log_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=206;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointment`
--
ALTER TABLE `appointment`
  ADD CONSTRAINT `Appointment_Doctor_ID` FOREIGN KEY (`Doctor_ID`) REFERENCES `doctor` (`Doctor_ID`),
  ADD CONSTRAINT `Appointment_Manager_ID` FOREIGN KEY (`Manager_ID`) REFERENCES `appointment_manager` (`Appointment_Manager_ID`),
  ADD CONSTRAINT `Appointment_Patient_ID` FOREIGN KEY (`Patient_ID`) REFERENCES `patient` (`Patient_ID`);

--
-- Constraints for table `care_requests`
--
ALTER TABLE `care_requests`
  ADD CONSTRAINT `care_Doctor_ID` FOREIGN KEY (`Doctor_ID`) REFERENCES `doctor` (`Doctor_ID`),
  ADD CONSTRAINT `care_Pateint_ID` FOREIGN KEY (`Patient_ID`) REFERENCES `patient` (`Patient_ID`);

--
-- Constraints for table `insurance_claim`
--
ALTER TABLE `insurance_claim`
  ADD CONSTRAINT `Claim_Invoice_ID` FOREIGN KEY (`Invoice_ID`) REFERENCES `invoice` (`invoice_Id`),
  ADD CONSTRAINT `Claim_Officer_ID` FOREIGN KEY (`Officer_ID`) REFERENCES `insuranceofficer` (`iOfficerID`),
  ADD CONSTRAINT `Claim_Patient_ID` FOREIGN KEY (`Patient_ID`) REFERENCES `patient` (`Patient_ID`);

--
-- Constraints for table `invoice`
--
ALTER TABLE `invoice`
  ADD CONSTRAINT `invoice_Accountant_ID` FOREIGN KEY (`accountantID`) REFERENCES `accountant` (`Accountant_ID`),
  ADD CONSTRAINT `invoice_Patient_ID` FOREIGN KEY (`patient_Id`) REFERENCES `patient` (`Patient_ID`);

--
-- Constraints for table `medicaltest`
--
ALTER TABLE `medicaltest`
  ADD CONSTRAINT `test_Doctor_ID` FOREIGN KEY (`Doctor_ID`) REFERENCES `doctor` (`Doctor_ID`),
  ADD CONSTRAINT `test_Investigator_ID` FOREIGN KEY (`InvestigatorID`) REFERENCES `investigator` (`Investigator_ID`),
  ADD CONSTRAINT `test_Patient_ID` FOREIGN KEY (`Patient_ID`) REFERENCES `patient` (`Patient_ID`);

--
-- Constraints for table `medical_record`
--
ALTER TABLE `medical_record`
  ADD CONSTRAINT `record_Doctor_ID` FOREIGN KEY (`doctor_id`) REFERENCES `doctor` (`Doctor_ID`),
  ADD CONSTRAINT `record_Patient_ID` FOREIGN KEY (`patient_id`) REFERENCES `patient` (`Patient_ID`),
  ADD CONSTRAINT `record_report_id` FOREIGN KEY (`report_ID`) REFERENCES `medicaltest` (`test_ID`);

--
-- Constraints for table `symptomlog`
--
ALTER TABLE `symptomlog`
  ADD CONSTRAINT `log_Patient_ID` FOREIGN KEY (`Patient_ID`) REFERENCES `patient` (`Patient_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
