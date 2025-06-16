-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 08, 2024 at 09:35 AM
-- Server version: 5.7.23-23
-- PHP Version: 8.1.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `xuwl9qaw_broadmead`
--

-- --------------------------------------------------------

--
-- Table structure for table `activeusers`
--

CREATE TABLE `activeusers` (
  `id` int(11) NOT NULL,
  `app_id` text NOT NULL,
  `userid` text NOT NULL,
  `date` text NOT NULL,
  `time` text NOT NULL,
  `logouttime` text NOT NULL,
  `device` text NOT NULL,
  `browser` text NOT NULL,
  `ipaddress` text NOT NULL,
  `location` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `activeusers`
--

INSERT INTO `activeusers` (`id`, `app_id`, `userid`, `date`, `time`, `logouttime`, `device`, `browser`, `ipaddress`, `location`) VALUES
(1, '169325', '10', '2023-05-15', '12:56', '14:00', 'Windows', 'Google Chrome', '::1', 'Zambia, Lusaka'),
(2, '169325', '1', '2023-05-15', '13:06', '16:55', 'Windows', 'Google Chrome', '::1', 'Zambia, Lusaka'),
(3, '169325', '10', '2023-05-15', '13:16', '14:00', 'Windows', 'Google Chrome', '::1', 'Zambia, Lusaka'),
(4, '169325', '10', '2023-05-15', '16:26', '--', 'Windows', 'Google Chrome', '::1', 'Zambia, Lusaka'),
(5, '169326', '2', '2023-05-15', '21:59', '--', 'Windows', 'Google Chrome', '45.215.255.140', 'United States, Houston'),
(6, '169326', '2', '2023-05-16', '01:42', '01:50', 'Windows', 'Google Chrome', '45.215.255.140', 'United States, Houston'),
(7, '169325', '1', '2023-05-16', '01:43', '--', 'Windows', 'Google Chrome', '154.47.98.168', 'United States, Houston'),
(8, '169325', '1', '2023-05-16', '17:20', '--', 'Windows', 'Google Chrome', '41.223.119.41', 'United States, Houston'),
(9, '169325', '1', '2023-05-16', '19:59', '--', 'Windows', 'Google Chrome', '41.223.117.70', 'United States, Houston'),
(10, '169326', '2', '2023-05-16', '20:58', '--', 'Windows', 'Google Chrome', '102.144.76.228', 'United States, Houston'),
(11, '169326', '2', '2023-05-17', '00:40', '--', 'Windows', 'Google Chrome', '102.151.239.66', 'United States, Houston'),
(12, '169326', '2', '2023-05-17', '09:35', '--', 'Windows', 'Google Chrome', '102.151.239.66', 'United States, Houston'),
(13, '169326', '2', '2023-05-17', '11:55', '--', 'Windows', 'Google Chrome', '102.145.50.227', 'United States, Houston'),
(14, '169326', '2', '2023-05-17', '13:31', '--', 'Windows', 'Google Chrome', '102.145.50.227', 'United States, Houston'),
(15, '169326', '2', '2023-05-17', '16:06', '--', 'Windows', 'Google Chrome', '102.145.50.227', 'United States, Houston'),
(16, '169326', '2', '2023-05-17', '17:45', '--', 'Windows', 'Google Chrome', '102.145.50.227', 'United States, Houston'),
(17, '169326', '2', '2023-05-18', '01:04', '--', 'Windows', 'Google Chrome', '102.150.21.230', 'United States, Houston'),
(18, '0df03a292d16c009a9ba2190720f957f', '4', '2023-05-18', '21:01', '--', 'Windows', 'Google Chrome', '45.215.255.84', 'United States, Houston'),
(19, '950862c44989d6795534f8415257b08a', '5', '2023-05-18', '21:11', '--', 'Windows', 'Google Chrome', '45.215.255.84', 'United States, Houston'),
(20, '169325', '1', '2023-05-18', '21:29', '--', 'Windows', 'Google Chrome', '154.47.98.168', 'United States, Houston'),
(21, '950862c44989d6795534f8415257b08a', '5', '2023-05-19', '02:15', '--', 'Windows', 'Google Chrome', '102.146.21.129', 'United States, Houston'),
(22, '950862c44989d6795534f8415257b08a', '5', '2023-05-19', '14:31', '--', 'Windows', 'Google Chrome', '197.213.147.110', 'United States, Houston'),
(23, '950862c44989d6795534f8415257b08a', '5', '2023-05-19', '17:54', '--', 'Windows', 'Google Chrome', '102.151.186.54', 'United States, Houston'),
(24, '950862c44989d6795534f8415257b08a', '5', '2023-05-22', '00:41', '--', 'iOS', 'Apple Safari', '197.213.118.2', 'United States, Houston'),
(25, '950862c44989d6795534f8415257b08a', '5', '2023-05-22', '00:46', '--', 'iOS', 'Apple Safari', '197.213.118.2', 'United States, Houston'),
(26, '169325', '1', '2023-05-22', '02:02', '02:08', 'Windows', 'Mozilla Firefox', '154.47.98.168', 'United States, Houston'),
(27, '169325', '1', '2023-05-22', '02:12', '--', 'Windows', 'Mozilla Firefox', '154.47.98.168', 'United States, Houston'),
(28, '169325', '1', '2023-05-22', '02:22', '--', 'Windows', 'Google Chrome', '154.47.98.168', 'United States, Houston'),
(29, '169325', '1', '2023-05-22', '23:12', '--', 'Windows', 'Google Chrome', '154.47.98.168', 'United States, Houston'),
(30, '169325', '1', '2023-05-30', '19:32', '--', 'Windows', 'Google Chrome', '86.12.166.114', 'United States, Houston'),
(31, '169325', '3', '2023-05-31', '14:50', '15:06', 'Windows', 'Google Chrome', '31.94.73.208', 'United States, Houston'),
(32, '169325', '9', '2023-05-31', '15:10', '16:42', 'Windows', 'Google Chrome', '31.94.73.208', 'United States, Houston'),
(33, '169325', '11', '2023-05-31', '17:16', '17:51', 'Windows', 'Google Chrome', '86.12.166.114', 'United States, Houston'),
(34, '169325', '10', '2023-05-31', '17:55', '20:06', 'Windows', 'Google Chrome', '86.12.166.114', 'United States, Houston'),
(35, '169325', '3', '2023-06-02', '16:03', '--', 'Windows', 'Google Chrome', '82.26.193.132', 'United States, Houston'),
(36, '169326', '2', '2023-06-05', '13:25', '--', 'Windows', 'Google Chrome', '102.147.1.88', 'United States, Houston'),
(37, '169325', '1', '2023-06-12', '18:39', '--', 'Windows', 'Google Chrome', '79.69.188.187', 'United States, Houston'),
(38, '169325', '1', '2023-06-14', '22:56', '--', 'Windows', 'Google Chrome', '79.69.188.187', 'United States, Houston'),
(39, '169325', '11', '2023-06-20', '16:14', '--', 'Windows', 'Google Chrome', '86.12.166.114', 'United States, Houston');

-- --------------------------------------------------------

--
-- Table structure for table `bank_info`
--

CREATE TABLE `bank_info` (
  `id` int(11) NOT NULL,
  `app_id` text NOT NULL,
  `name` text NOT NULL,
  `bankname` text NOT NULL,
  `bankbranch` text NOT NULL,
  `number` text NOT NULL,
  `sortcode` text NOT NULL,
  `invoiceemail` text NOT NULL,
  `vatnumber` text NOT NULL,
  `vatpercentage` text NOT NULL,
  `createdBy` text NOT NULL,
  `createdOn` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `bank_info`
--

INSERT INTO `bank_info` (`id`, `app_id`, `name`, `bankname`, `bankbranch`, `number`, `sortcode`, `invoiceemail`, `vatnumber`, `vatpercentage`, `createdBy`, `createdOn`) VALUES
(1, '169325', 'Nocturnal Recruitment Solutions Limited ', 'Baclays Bank', 'London', '93557367', '20-25-19', 'accounts@nocturnalrectruitment.co.uk', '321849110', '20', 'Michael Jr Musenge', '11th April 2023'),
(2, '169326', 'Zanaco', 'Zanaco Bank', 'Lusaka', '163423131', '0989371', 'andie@boz.com', '13390231', '16', 'Andie  ', '9th May 2023');

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` int(11) NOT NULL,
  `status` varchar(200) NOT NULL DEFAULT 'Inactive',
  `app_id` text NOT NULL,
  `client` text NOT NULL,
  `branch_id` text NOT NULL,
  `name` text NOT NULL,
  `email` text NOT NULL,
  `address` text NOT NULL,
  `city` text NOT NULL,
  `postcode` text NOT NULL,
  `regno` text NOT NULL,
  `vatno` text NOT NULL,
  `terms` text NOT NULL,
  `rates` text NOT NULL,
  `manager` text NOT NULL,
  `emailaddress` text NOT NULL,
  `mobilenumber` text NOT NULL,
  `createdBy` text NOT NULL,
  `createdOn` text NOT NULL,
  `time` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `status`, `app_id`, `client`, `branch_id`, `name`, `email`, `address`, `city`, `postcode`, `regno`, `vatno`, `terms`, `rates`, `manager`, `emailaddress`, `mobilenumber`, `createdBy`, `createdOn`, `time`) VALUES
(1, 'Active', '169325', '7902', '7902', 'Full Of Life ', 'hr@fulloflifekc.com', '379 Ladbroke Grove', 'London ', 'W10 5BQ', '0', '0', '', '', 'Gill ', 'gill@fulloflifekc.com', '07951551674', '1', '2023-05-31', '13:45'),
(2, 'Active', '169325', '2344', '2344', 'Ferndale Residential Home', 'angela.walker@centralbedfordshire.gov.uk', 'Easton Road ', 'Flitwick ', 'MK45 1HB', '0', '0', '', '', 'Angela Walker ', 'angela.walker@centralbedfordshire.gov.uk', '03003005071', '1', '2023-05-31', '14:35'),
(3, 'Active', '169325', '9954', '9954', 'Linsell House - Central Bedfordshire ', 'Dawn.Jenner@centralbedfordshire.gov.uk', 'Ridgeway Avenue, Dunstable LU5 4QJ ', 'Bedfordshire', 'LU5 4QJ ', '0', '0', '', '', 'Charlotte Bond', 'Charlotte.Bond@centralbedfordshire.gov.uk', '0300 300 5684 ', '9', '2023-05-31', '15:24'),
(4, 'Inactive', '169325', '6416', '6416', 'Watcombe Circus - Nottingham Council Housing Association', 'Theresa.Taylor@ncha.org.uk', '2-4, Watcombe Circus, Nottingham NG5 2DT', 'Nottingham', 'NG5 2DT', '0', '0', '', '', 'Theresa Taylor', 'Theresa.Taylor@ncha.org.uk', '0115 9609592', '9', '2023-05-31', '15:29'),
(5, 'Inactive', '169325', '1706', '1706', 'Orchard Close - Nottingham Community Housing Association', 'Julia.Watkinson@ncha.org.uk', '2-8 Orchard Street, Hucknall, Nottinghamshire, NG15 7JZ', 'Nottingham', 'NG15 7JZ', '0', '0', '', '', 'Julia Watkinson', 'Julia.Watkinson@ncha.org.uk', '0115 9680525', '9', '2023-05-31', '15:35'),
(6, 'Inactive', '169325', '5149', '5149', 'Allison House Residential Home', 'Naomi.Ransford@centralbedfordshire.gov.uk', 'Allison House Swan Lane, Sandy SG19 1NE ', 'Sandy', 'SG19 1NE ', '0', '0', '', '', 'Geraldine Smith', 'Geraldine.Smith@centralbedfordshire.gov.uk', '0300 300 4415', '9', '2023-05-31', '15:39'),
(7, 'Inactive', '169325', '8958', '8958', 'Evergreen Unit - Central Bedfordshire', 'Sarah.Milton@centralbedfordshire.gov.uk', 'EVERGREEN SUSD UNIT Wingfield Court Ampthill MK45 2TE', 'Bedfordshire', 'MK45 2TE', '0', '0', '', '', 'Sarah Milton', 'Sarah.Milton@centralbedfordshire.gov.uk', '0300 300 6091', '9', '2023-05-31', '15:54'),
(8, 'Inactive', '169325', '6241', '6241', 'Breakaway Short Breaks', 'Martin.Nsubuga@camden.gov.uk', '120 Rowley Way ', 'London', 'NW8 0SP', '0', '0', 'files/1685545528.docx', 'files/194279.docx', 'Manager Nsubuga', 'Martin.Nsubuga@camden.gov.uk', '07771666871', '10', '2023-05-31', '16:05'),
(9, 'Inactive', '169325', '2729', '2729', 'Charlie Ratchford', 'Laurie.Armantrading@camden.gov.uk', 'Charlie Ratchford Court, 43 Crogsland Rd, Chalk Farm', 'London', 'NW1 8FA', '0', '0', 'files/1685545696.docx', 'files/835006.docx', 'Laurie Armantrading', 'Laurie.Armantrading@camden.gov.uk', '07971177814', '10', '2023-05-31', '16:08'),
(10, 'Inactive', '169325', '6751', '6751', 'The Green Bank House', 'Fiona.Armstrong@barnet.gov.uk', '27 Woodside Avenue', 'London', 'N12 8AT', '0', '0', 'files/1685545934.docx', 'files/833515.docx', 'Sophie Nzenwa', 'Sophie.Nzenwa@Barnet.gov.uk', '07931177926', '10', '2023-05-31', '16:12'),
(11, 'Inactive', '169325', '7216', '7216', 'Hatton Grove', 'THamer@Hillingdon.gov.uk', '4 Hatton Grove', 'London', 'UB7 7AU', '0', '0', 'files/1685546128.docx', 'files/133196.docx', 'Tracie Hamer', 'THamer@Hillingdon.Gov.UK', ' 01895 556989', '10', '2023-05-31', '16:15'),
(12, 'Inactive', '169325', '8929', '8929', '68a Meadows Close', 'Dionne.Bulcock@barnet.gov.uk', 'Dollis Valley Way, Barnet', 'London', ' EN5 2UF ', '0', '0', 'files/1685546326.docx', 'files/287933.docx', 'Dionne Bulcock', 'Dionne.Bulcock@barnet.gov.uk', '020 8359 3389', '10', '2023-05-31', '16:18'),
(13, 'Inactive', '169325', '1111', '1111', 'New Park House', 'Jane.Stevenson@barnet.gov.uk', 'New Park House, 25 Parkhurst Road,  New Southgate', 'London', 'N11 3EN', '0', '0', 'files/1685546501.docx', 'files/145058.docx', 'Sophie Nzenwa', 'Sophie.Nzenwa@Barnet.gov.uk', '07931177926', '10', '2023-05-31', '16:21'),
(14, 'Inactive', '169325', '2719', '2719', 'Arlington', 'Ronald.Onyango@islington.gov.uk', '14a Arlington Square ', 'London ', 'N1 7FS', '0', '0', '', '', 'Ronald ', 'Onyango ', '07816 116655', '11', '2023-05-31', '17:06'),
(15, 'Inactive', '169325', '2204', '2204', 'New Options ', 'andrew.rossides@enfield.gov.uk', '25 Connop Road', 'Enfield ', 'EN3 5FB', '0', '0', '', '', 'Simon Rahman', 'simon.rahman@enfield.gov.uk', '020 8379 5359', '11', '2023-05-31', '17:10'),
(16, 'Active', '169325', '9619', '9619', 'Formont ', 'stavros.costi@enfield.gov.uk', 'Waverley Road', 'Enfield ', 'EN2 7BP', '0', '0', '', '', 'Shard Madhewoo ', 'shard.madhewoo@enfield.gov.uk', '07985 281407', '11', '2023-05-31', '17:12'),
(17, 'Inactive', '169326', '4153', '4153', 'Michael Musenge', 'musengemichaeljr@gmail.com', 'New Chalala', 'Lusaka', '10101', 'Michael Musenge', '12345', '', '', 'Mike', 'musengemichaeljr@gmail.com', '0777504081', '2', '2023-06-03', '21:24'),
(18, 'Inactive', '169326', '7256', '7256', 'Highbury New Park Day Centre', 'Susan.O\'dell@islington.gov.uk', '127 Highbury New Park', 'London', 'N5 2DS', '1', '1', 'files/1687342064.docx', 'files/840038.docx', 'Annoulla Loizou', 'Annoulla.Loizou@islington.gov.uk', '07971064606', '12', '2023-06-21', '11:07'),
(19, 'Inactive', '169325', '4857', '4857', 'Highbury New Park Day Centre', 'Susan.O\'dell@islington.gov.uk', '127 Highbury New Park', 'London', 'N5 2DS', '1', '1', 'files/1687342323.docx', 'files/778747.docx', 'Annoulla Loizou', 'Annoulla.Loizou@islington.gov.uk', '07971064606', '10', '2023-06-21', '11:12'),
(20, 'Inactive', '169325', '2947', '2947', 'The Markhouse Centre', 'Saima.Mehmood@walthamforest.gov.uk', '247 Markhouse Road, Waltham Forest', 'London', 'E17 8DW', '.', '.', '', '', 'Saima Mehmood', 'Saima.Mehmood@walthamforest.gov.uk', '020 8496 2710', '9', '2023-06-26', '16:20'),
(21, 'Active', '169325', '4112', '4112', 'Daylight', 'Lakhvinder.Bhogal@islington.gov.uk', '14-16 Highbury Grove, Islington', 'London', 'N5 2EA', '.', '.', '', '', 'Lakhvinder Bhogal', 'Lakhvinder.Bhogal@islington.gov.uk', '07584370840', '9', '2023-06-26', '16:37'),
(22, 'Inactive', '169325', '4244', '4244', 'Westlands', 'Aderonke.Eyeowa@centralbedfordshire.gov.uk', 'Duncombe Dr, Leighton Buzzard', 'Bedfordshire', ' LU7 1SD', '1', '1', '', '', 'Anna Maria', 'Anna-Maria.Johnson-Brown@centralbedfordshire.gov.uk', '0300 300 8596', '9', '2023-07-26', '09:17'),
(23, 'Inactive', '169325', '2042', '2042', 'Chapel Lane - Hillingdon Council', 'hakerman@hillingdon.gov.uk', 'Chapel Lane', 'London', 'UB8 3DS', '1', '1', '', '', 'Hannah Akerman', 'hakerman@hillingdon.gov.uk', '01895 557762', '9', '2023-07-26', '09:55'),
(24, 'Inactive', '169325', '7640', '7640', '3 Merrimans - Hillingdon Council', 'bwhite1@hillingdon.gov.uk', 'West Drayton Road', 'London', 'UB8 3JZ', '1', '1', '', '', 'Barbra White - Admin', 'bwhite1@hillingdon.gov.uk', '01895 277584', '9', '2023-07-26', '10:00'),
(25, 'Inactive', '169325', '2354', '2354', 'Heritage - The Edwardian', 'edwardian@heritagecarehomes.co.uk', '168 Biscot Road', 'Luton', 'LU3 1AX', '1', '1', '', '', 'May Puthoor', 'edwardian@heritagecarehomes.co.uk', '01582 705100', '9', '2023-07-26', '10:04'),
(26, 'Inactive', '169325', '9923', '9923', 'Mulberry House', 'sergio.completecare@outlook.com', '120 Barton Road', 'Luton', 'LU3 2BD', '1', '1', '', '', 'Sergio', 'sergio.completecare@outlook.com', '01582570569', '9', '2023-07-26', '10:10'),
(27, 'Inactive', '169325', '1108', '1108', 'Queen Ann House', 'careteam@queenanncare.com', '40-42 Old Park Road', 'London', 'N13 4RE', '1', '1', '', '', 'Anita - Admin', 'careteam@queenanncare.com', '020 8920 3340', '9', '2023-07-26', '10:23'),
(28, 'Inactive', '169325', '7119', '7119', 'Unified Care ', 'shamir@unifiedcare.co.uk', '37 Coleraine Road', 'London', 'N8 0QJ', '1', '1', '', '', 'Shamir', 'shamir@unifiedcare.co.uk', '08007720925', '9', '2023-07-26', '10:26'),
(29, 'Inactive', '169325', '4949', '4949', 'Greenwood Centre', 'c.thomas@ldnlondon.org', '37 Greenwood Pl', 'London', 'NW5 1LB', '1', '1', '', '', 'Chantelle', 'c.thomas@ldnlondon.org', '02082065925', '9', '2023-07-26', '10:37'),
(30, 'Inactive', '169325', '6826', '6826', 'LDN London', 'jhilton@ldnlondon.org', '22A Ainger Road', 'London', 'NW1 8HX', '1', '1', '', '', 'Jennifer Hilton', 'jhilton@ldnlondon.org', '02074833757', '9', '2023-07-26', '10:58'),
(31, 'Inactive', '169325', '4943', '4943', 'Shirland Road - St Mungo\'s', 'Caroline.Nzegbulem@mungos.org', '93-95 Shirland Road', 'London', 'W9 2EL', '1', '1', '', '', 'Caroline Nzegbulem', 'Caroline.Nzegbulem@mungos.org', '02072660161', '9', '2023-07-26', '11:12'),
(32, 'Inactive', '169325', '1049', '1049', 'LQ Living - Helena Road', 'Norah.Matsie-ssonko@lqliving.co.uk', '2c-d Helena Road', 'London', 'E13 0DU', '1', '1', '', '', 'Norah Matsie-ssonko', 'Norah.Matsie-ssonko@lqliving.co.uk', '02084701382', '9', '2023-07-26', '15:50'),
(33, 'Inactive', '169325', '2023', '2023', 'LDN 4 U - Queensland Road', 'dwallace@ldnlondon.org', '7 paxton Court', 'Islington', 'N7 8AF', '1', '1', '', '', 'Dominica Wallace', 'dwallace@ldnlondon.org', '02076078993', '9', '2023-08-01', '15:41'),
(34, 'Inactive', '169325', '1427', '1427', 'LQLiving - Woodview Court', 'sempela.kaulu@lq-living.co.uk', 'Flat 6, 199 Wood Street, ', 'London', 'E17 3NU', '1', '1', '', '', 'Sempala Kaur', 'sempela.kaulu@lq-living.co.uk', '0208 189 4618 ', '9', '2023-08-04', '11:46'),
(35, 'Inactive', '169325', '1308', '1308', 'Abbottsford Residential Home - Ruislip Care Homes', 'tajreaz.cader@nhs.net', '53 Moss Lane Pinner ', 'London', ' HA5 3AZ', '1', '1', '', '', '.', 'tajreaz.cader@nhs.net', '020 8866 0921', '9', '2023-08-07', '15:11'),
(36, 'Inactive', '169325', '7743', '7743', 'The Boyne Residential Care Home', 'care@ruislipcarehomes.co.uk', '38 Park Way, Ruislip, Middlesex', 'London', 'HA4 8NU', '1', '1', '', '', 'The Boyne Residential Care Home', 'care@ruislipcarehomes.co.uk', '01895 623 118', '9', '2023-08-07', '15:17'),
(37, 'Inactive', '169325', '9948', '9948', 'Poplars Residential Care Home', 'care@ruislipcarehomes.co.uk', '15-17 Ickenham Road Ruislip', 'London', 'HA4 7BZ', '1', '1', '', '', 'Poplars Residential Care Home', 'care@ruislipcarehomes.co.ukcare@ruislipcarehomes.co.uk', '01895 635 284', '9', '2023-08-07', '15:20'),
(38, 'Inactive', '169325', '4354', '4354', 'Primrose House Nursing Home', 'care@ruislipcarehomes.co.uk', '765-767 Kenton Lane Harrow, Middlesex', 'London', 'HA3 6AH', '1', '1', '', '', 'Primrose House Nursing Home', 'care@ruislipcarehomes@co.uk', '020 8954 4442', '9', '2023-08-07', '15:24'),
(39, 'Inactive', '169325', '1522', '1522', 'Ruislip  Nursing Home', 'care@ruislipcarehomes', '173 West End Road Ruislip', 'London', 'HA4 6LB', '1', '1', '', '', 'Ruislip  Nursing Home', 'care@ruislipcarehomes', '01895 676 442', '9', '2023-08-07', '15:40'),
(40, 'Inactive', '169325', '1458', '1458', 'Pinkwell Primary School', 'pink@lane.co', 'Pinkwell Lane, Hayes', 'London', 'UB3 1PG', '1', '1', '', '', 'Pinkwell Primary School', '.', '020 85732199', '9', '2023-08-07', '15:51'),
(41, 'Inactive', '169325', '2727', '2727', 'Marlin Lodge', 'unknown@marlin.com', '31 Marlborough Rd', 'Luton', 'LU3 1EF', '1', '1', '', '', 'Marlin Lodge', '@marlin.com', ' 01582 723495', '9', '2023-08-07', '15:56'),
(42, 'Inactive', '169325', '7156', '7156', 'Belle Vue', 'thehomemanager@outlook.com', '123 New Bedford Rd ', 'Luton', ' LU3 1LF', '1', '1', '', '', 'Belle Vue', 'thehomemanager@outlook.com', '07814966062', '9', '2023-08-07', '16:03'),
(43, 'Inactive', '169325', '7143', '7143', 'Castletroy Residential Home', 'castletroy@btconnect.com', '130 Cromer Way Bushmead ', 'Luton', 'LU2 7GP', '1', '1', '', '', 'Castletroy Residential Home', 'castletroy@btconnect.com', '01582 417995', '9', '2023-08-07', '16:08'),
(44, 'Inactive', '169325', '6310', '6310', 'Mulberry House', 'info@completecare.org.uk', '120 Barton Road', 'Luton', 'LU3 2BD', '1', '1', '', '', 'Kris Hurry', 'info@completecare.org.uk', '01582 570569', '9', '2023-08-07', '16:12'),
(45, 'Inactive', '169325', '5256', '5256', 'Zakia', 'info@theenableproject.co.uk', '110 Butterfield, Great Marlings ', 'Luton ', 'LU2 8DL', '1', '1', '', '', 'Jackie Leslie', 'info@theenableproject.co.uk', '07523 634907', '9', '2023-08-07', '16:16'),
(46, 'Inactive', '169325', '10047', '10047', ' St Theresa\'s Rest Home', 'unknown@resthome.com', '6-8 Queen Annes Gardens', 'Enfield', 'EN1 2JN', '1', '1', '', '', 'Mr Paul and Mrs Paul', 'unknown@resthome.com', '020 8360 6272', '9', '2023-08-07', '16:22'),
(47, 'Inactive', '169325', '3505', '3505', 'Twinglobe - Azeala Court', 'info@twinglobe.com', 'Azalea Court, 58 Abbey Road, Bush Hill Park', 'Enfield', 'EN1 2QN', '1', '1', '', '', '.', 'info@twinglobe.com', '020 8370 1750', '9', '2023-08-07', '16:27'),
(48, 'Inactive', '169325', '8904', '8904', 'Twinglobe - Willows Carehome', 'info@twinglobe.com', '58 Abbey Road, Bush Hill Park', 'Enfield', '020 8370 1750', '1', '1', '', '', '.', 'info@twinglobe.com', '020 8370 1750', '9', '2023-08-07', '16:37'),
(49, 'Inactive', '169325', '8709', '8709', 'Saivan Care Services - Henley Lodge', 'unknown@henley', '28 Hyde Way', 'Enfield', 'N9 9RT', '1', '1', '', '', ' Faezeh Khodaverdy (Manager)', '.', '020 8090 9042', '9', '2023-08-07', '16:46'),
(50, 'Inactive', '169325', '5245', '5245', 'Saivan Care Services Ltd - Saivi House', 'unknown@saiv.com', '39 Doveridge Gardens', 'Palmers Green, London', 'N13 5BJ', '1', '1', '', '', 'Sanjaye Nath Ramsaha (Manager)', '.', '020 8245 7212', '9', '2023-08-07', '16:50'),
(51, 'Inactive', '169325', '8825', '8825', 'Saivan Care Services Ltd - Keevan Lodge', 'unknown@saivan', '98 Clive Road', 'Enfield ', 'EN1 1RF', '1', '1', '', '', 'Faezeh Khodaverdy (Manager)', '.', '020 8367 0441', '9', '2023-08-07', '16:53'),
(52, 'Inactive', '169325', '8008', '8008', 'Saivan Care Ltd -  Kellan Lodge', 'unknown@saivan', '24 Little Park Gardens', 'London', 'EN2 6PG', '1', '1', '', '', 'Faezeh Khodaverdy (Manager)', '.', '020 8363 5398', '9', '2023-08-07', '16:55'),
(53, 'Inactive', '169325', '9601', '9601', 'Reamcare', 'info@reamcare.co.uk', '100 Thorkhill Road', 'Surrey', 'KT7 0UW', '1', '1', '', '', 'Rayman', 'info@reamcare.co.uk ', '0208 224 3495', '9', '2023-08-08', '16:36'),
(54, 'Inactive', '169325', '8218', '8218', 'Lukka Care Homes - Acorn Lodge', 'info@lukkahomes.com', '15 Atherden Road Hackney ', 'London', 'E5 0QP', '1', '1', '', '', '.', 'acornlodge@lukkahomes.com', '0208 533 9555 - home line', '9', '2023-08-08', '16:45'),
(55, 'Inactive', '169325', '9331', '9331', 'Lukka Care Home - Albany Road', 'info@lukkahomes.com', '11/12 Albany Road', 'London ', 'E10 7EL', '1', '1', '', '', '.', 'Albany@lukkahomes.com', '02085567242', '9', '2023-08-08', '16:56'),
(56, 'Inactive', '169325', '4405', '4405', 'Lukka Care Homes - Ashton Lodge Care Home', 'info@lukkahomes.com', '95 The Hyde', 'London', 'NW9 6LE', '1', '1', '', '', '.', 'ashtonlodge@lukkahomes.com', '020 8732 7260', '9', '2023-08-08', '17:04'),
(57, 'Inactive', '', '2550', '2550', 'Lukka Care Homes - Mornington Hall', 'info@lukkahomes.com', '76 Whitta Road, Manor Park, London E12 5DA', 'London ', 'E12 5DA', '1', '1', '', '', '.', 'morningtonhall@lukkahomes.com', '020 4599 0480', '', '2023-08-08', '18:21'),
(58, 'Inactive', '169325', '6011', '6011', 'Lukka Care Home - Mornington Hall', 'info@lukkahomes.com', '76 Whitta Road', 'London', ' E12 5DA', '1', '1', '', '', '.', 'morningtonhall@lukkahomes.com', '020 4599 0480', '9', '2023-08-09', '09:15'),
(59, 'Inactive', '169325', '3427', '3427', 'Lukka Care Homes - Ravenscourt Care Home', 'info@lukkahomes.com', '111/113 Station Lane ', ' Hornchurch', 'RM12 6HT', '1', '1', '', '', '.', 'ravenscourt@lukkahomes.com', '01708 454715', '9', '2023-08-09', '09:18'),
(60, 'Inactive', '169325', '3321', '3321', 'Lukka Care Homes -Summerdale Court Care Home', 'info@lukkahomes.com', '73 Butchers Road, Newham, London E16 1PH', 'London', ' E16 1PH', '1', '1', '', '', '.', 'summerdale@lukkahomes.com', '020 7540 2200', '9', '2023-08-09', '09:22'),
(61, 'Inactive', '169325', '5051', '5051', 'Mapleton Road Home', 'christina.adamu@walthamforest.gov.uk', '87 Mapleton Road', 'London', ' E4 6XJ', '1', '1', '', '', 'christina.adamu@walthamforest.gov.uk', 'christina.adamu@walthamforest.gov.uk', '020 8529 2266', '9', '2023-08-09', '09:36'),
(62, 'Inactive', '169325', '7153', '7153', 'Salisbury Support 4 Autism', 'info@ss4autism.com', 'Liddall House, 66 Albert Road', 'West Drayton', 'UB7 8ES', '1', '1', '', '', 'Melanie Amaral', 'melanie@ss4autism.com', '07507351852', '9', '2023-08-09', '10:43'),
(63, 'Inactive', '169325', '4220', '4220', 'Halliwell Homes', '.recruitment@halliwellhomes.co.uk', '.', '.', '.', '1', '1', '', '', 'David.preston', 'David.preston@halliwellhomes.co.uk', '01614379491', '9', '2023-08-10', '16:42'),
(64, 'Inactive', '169325', '2411', '2411', 'St Johns College', 'megan.birch@st-johns.co.uk', '.', '.', '.', '1', '1', '', '', '.', 'recruitment@st-johns.co.uk', '01273 244000', '9', '2023-08-10', '16:44'),
(65, 'Inactive', '169325', '2828', '2828', 'Camphill Village Trust', 'recruitment@cvt.org.uk', '.', '.', '.', '1', '1', '', '', '.', 'Yasmin.Howe@cvt.org.uk/ cvtcentralhr@cvt.org.uk', '03316 308282', '9', '2023-08-10', '16:45'),
(66, 'Inactive', '169325', '1353', '1353', 'People In Action', 'admin@people-in-action.co.uk', '.', '.', '.', '1', '1', '', '', 'Amy', 'admin@people-in-action.co.uk', '02476643776', '9', '2023-08-10', '16:47'),
(67, 'Inactive', '169325', '8109', '8109', 'Jamores Ltd', 'recruitment@jamores.co.uk', '.', '.', '.', '1', '1', '', '', 'Benedict', 'referrals@jamores.co.uk/recruitment@jamores.co.uk', '07412 238 370', '9', '2023-08-10', '16:48'),
(68, 'Inactive', '169325', '7254', '7254', 'Julee Care ', 'info@juleecare.co.uk', '.', '.', '.', '1', '1', '', '', 'Christy', 'info@juleecare.co.uk', '01582 271361', '9', '2023-08-10', '16:55'),
(69, 'Inactive', '169325', '3333', '3333', 'S$', 'info@ss4autism.com', '.', '.', '.', '1', '1', '', '', 'Melanie', 'info@ss4autism.com', '0800 368 9433', '9', '2023-08-10', '16:57'),
(70, 'Inactive', '169325', '6433', '6433', 'Dimensions', '.@com', '.', '.', '.', '1', '1', '', '', '.', '.', '0300 303 9001', '9', '2023-08-10', '17:10'),
(71, 'Inactive', '169325', '2557', '2557', 'Creative Support', 'recruitment@creativesupport.co.uk', 'Wellington House 131 Wellington Road South', 'Stockport', 'SK1 3TS', '1', '1', '', '', '.', 'recruitment@creativesupport.co.uk', '0161 236 0829', '9', '2023-08-10', '17:26'),
(72, 'Inactive', '169325', '7035', '7035', 'Turning Point', 'info@com', '.', '.', '.', '1', '1', '', '', '.', '.', '07786 938 601 (Out of hours)', '9', '2023-08-10', '17:41'),
(73, 'Inactive', '169325', '5343', '5343', 'National Autistic Society', 'nas@nas.org.uk', '.', '.', '.', '1', '1', '', '', '.', 'nas@nas.org.uk', '0207 833 2299', '9', '2023-08-10', '17:48'),
(74, 'Inactive', '169325', '4150', '4150', 'Borough Care', 'enquiries@boroughcare.org.uk', '9 Acorn Business Park Heaton Lane', 'Stockport', 'SK4 1AS', '1', '1', '', '', '.', ' enquiries@boroughcare.org.uk', '0161 475 0140', '9', '2023-08-10', '17:55'),
(75, 'Inactive', '169325', '5946', '5946', 'Choice Care Group', 'enquiries@choicecaregroup.com', '.', '.', '.', '1', '1', '', '', '.', 'careers@choicecaregroup.com', ' 0203 195 0151', '9', '2023-08-10', '18:09'),
(76, 'Inactive', '169325', '8912', '8912', 'Disabilities Trust', 'recruitment@thedtgroup.org', '.', '.', '.', '1', '1', '', '', '.', 'recruitment@thedtgroup.org', '03300 581882', '9', '2023-08-10', '18:25'),
(77, 'Inactive', '169325', '4248', '4248', 'Kisharon', 'info@kisharon.org.uk', '1st Floor, 333 Edgware Road', 'London ', 'NW9 6TD', '1', '1', '', '', '.', 'recruitment@kisharon.org.uk.', '020 3209 1160', '9', '2023-08-10', '18:30'),
(78, 'Inactive', '169325', '6256', '6256', 'Leyton Green Road', 'raul.jaque@walthamforest.gov.uk', '99a Leyton Green Road', 'London', 'E10 6DB', '', '', '', '', 'Jennifer Elias', 'jennifer.elias@walthamforest.gov.uk', '07771 159183', '11', '2024-02-09 14:28:46', '14:28'),
(79, 'Inactive', '169325', '3350', '3350', 'Alliston House Care Home', 'michael.olaniyan@waltham-forest.gov.uk', '45 Church Hill Road', 'London', 'E17 9RX', '', '', '', '', 'Michael Olaniyan', 'michael.olaniyan@waltham-forest.gov.uk', '020 8520 4984', '11', '2024-02-13 16:15:30', '16:15'),
(80, 'Inactive', '169325', '5753', '5753', 'MTVH', 'MTVH', 'MTVH', 'MTVH', 'MTVH', '', '', '', '', 'MTVH', 'MTVH', 'MTVH', '11', '2024-02-22 14:08:24', '14:08'),
(81, 'Inactive', '169325', '5753', '663169', 'Woodvale ', 'MTVH', 'MTVH', 'MTVH', 'MTVH', '', '', '', '', 'MTVH', 'MTVH', 'MTVH', '11', '2024-02-22', '14:09');

-- --------------------------------------------------------

--
-- Table structure for table `calllog`
--

CREATE TABLE `calllog` (
  `id` int(11) NOT NULL,
  `app_id` text NOT NULL,
  `candidateid` text NOT NULL,
  `type` text NOT NULL,
  `description` text NOT NULL,
  `date` text NOT NULL,
  `color` text NOT NULL,
  `createdBy` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `calllog`
--

INSERT INTO `calllog` (`id`, `app_id`, `candidateid`, `type`, `description`, `date`, `color`, `createdBy`) VALUES
(2, '169325', '1', 'meeting ', 'In this code, we use the PHP variables corresponding to the $_POST values to construct the INSERT statement. We also assume that the date is set to the current date and time (you can adjust this as needed). This code uses prepared statements and provides basic error handling, similar to the previous example.', '2023-10-13 18:31', 'danger', '2'),
(3, '169325', '1', 'Phone Call', 'Called about tax code ', '2023-10-13 18:31', 'primary', '1'),
(4, '169325', '1', 'Phone Call', 'Hello', '2023-10-13 18:31', 'info', '1'),
(15, '169325', '1', 'Text Message', '<p><em><strong>Hey&nbsp;</strong></em></p>', '2023-10-13 18:52', 'primary', '5'),
(16, '169325', '1', 'Phone call.', '<p><em><strong>You can rewrite the provided PHP code to use prepared statements with placeholders and <code>bindParam</code> for better security and readability. Here\'s the modified code.</strong></em></p>', '2023-10-13 18:56', 'danger', '5'),
(18, '169325', '1', 'DESCRIPTION.', '<p><em><strong>DESCRIPTION.</strong></em></p>', '2023-10-13 19:02', 'info', '5'),
(19, '169325', '1', '', '<p><em><strong>MICHEAL MUSENGE.</strong></em></p>', '2023-10-13 19:09', 'danger', '5'),
(20, '169325', '155', 'Phone Call ', '<p>need to be on PAYE&nbsp;</p>', '2024-01-04 15:17', 'info', '1'),
(21, '169325', '10', 'Hello', 'Hello ', '2024-01-07 15:00', 'primary', '5'),
(22, '169325', '10', 'Hello', 'Hello ', '2024-01-07 15:02', 'primary', '5');

-- --------------------------------------------------------

--
-- Table structure for table `campaigns`
--

CREATE TABLE `campaigns` (
  `id` int(11) NOT NULL,
  `type` text NOT NULL,
  `name` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `campaigns`
--

INSERT INTO `campaigns` (`id`, `type`, `name`) VALUES
(1, 'Newletter', 'HTML & CSS'),
(2, 'Promotional', 'HTML & CSS'),
(3, 'Service and Product', 'HTML & CSS'),
(4, 'Product', 'HTML & CSS'),
(5, 'Reminder Email', 'HTML & CSS'),
(6, 'Notifications', 'HTML & CSS'),
(7, 'Landing Page', 'HTML & CSS'),
(8, 'Blog Email', 'HTML & CSS'),
(9, 'Big Offer', 'HTML & CSS'),
(10, 'Plain Text', 'Plain Text');

-- --------------------------------------------------------

--
-- Table structure for table `candidates`
--

CREATE TABLE `candidates` (
  `id` int(11) NOT NULL,
  `app_id` text NOT NULL,
  `status` varchar(200) NOT NULL DEFAULT 'Active',
  `first_name` text NOT NULL,
  `middle_name` text NOT NULL,
  `last_name` text NOT NULL,
  `dob` text NOT NULL,
  `job_title` text NOT NULL,
  `gender` text NOT NULL,
  `email` text NOT NULL,
  `address` text NOT NULL,
  `postcode` text NOT NULL,
  `mobilenumber` text NOT NULL,
  `profile` text NOT NULL,
  `country` text NOT NULL,
  `createdBy` text NOT NULL,
  `createdOn` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `time` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `candidates`
--

INSERT INTO `candidates` (`id`, `app_id`, `status`, `first_name`, `middle_name`, `last_name`, `dob`, `job_title`, `gender`, `email`, `address`, `postcode`, `mobilenumber`, `profile`, `country`, `createdBy`, `createdOn`, `time`) VALUES
(1, '169325', 'Active', 'Haja', '', 'Mushtaba', '1968-10-21', 'Care Worker ', 'Female ', 'hajafmushtaba1@gmail.com  ', '55 Tythe Road ', 'LU4 9JH', '07988675927', 'profiles/807811338.jpg', 'Luton', '1', '2023-05-31 00:00:00', '13:58'),
(2, '169325', 'Active', 'Adekoyejo', '', 'Adeniregun ', '', 'Support Worker', 'Male', 'koyejo2006@yahoo.com', 'Flat 25 Bulwer Court Road', 'E11 1DB', '07446332173', 'profiles/1346609575.png', 'London ', '1', '2023-05-31 00:00:00', '14:27'),
(4, '169325', 'Active', 'Bianca ', '', 'Riley', '1973-03-21', 'Care Support Worker', 'Female', 'biancariley03@yahoo.com ', '55,STRACEY ROAD', 'E7 0HQ', '07562539772', 'profiles/839124758.png', 'London', '9', '2023-05-31 00:00:00', '16:07'),
(6, '169325', 'Archived', 'Pascal', '', 'Chukwuemeka', '1992-02-17', 'Care Support Worker', 'Male', 'pascaluzo2018@gmail.com   ', 'Flat 18 Equinox House Wakering Road', 'IG11 8RN', '07576723739', 'profiles/2090851074.png', 'LONDON', '9', '2023-05-31 00:00:00', '16:10'),
(7, '169325', 'Active', 'Saba', '', 'Amanuel', '2023-05-31', 'Care Supporter Worker', 'Female', 'sabaamanuel62@yahoo.co.uk', 'Flat 13 Gallery House', 'E8 3NR', '07948518263', 'profiles/user.png', 'London', '9', '2023-05-31 00:00:00', '16:16'),
(8, '169325', 'Active', 'Sonali', '', 'Sonali', '2000-02-01', 'Care Support Worker', 'Female', 'arorasonali53@gmail.com   ', '78B Cromwell Road', 'LU3 1DN', '07828758476', 'profiles/1011287344.png', 'Luton', '9', '2023-05-31 00:00:00', '16:23'),
(9, '169325', 'Active', 'Owen ', '', 'Umukoro', '1978-07-28', 'Care Support Worker', 'Male', 'owenumukorp926@gmail.com  ', '5 RADBOURNE ROAD, SNEINTON, NOTTINGHAM', 'NG2 4BX', '07733816126', 'profiles/1894789465.png', 'Nottingham', '9', '2023-05-31 00:00:00', '16:28'),
(10, '169325', 'Active', 'Daniel ', '', 'Kusi', '1977-05-10', 'Care Support Worker', 'Male', 'tawiahm1@gmail.com ', '37 Higham Street', 'E17 6BW', '07983784495', 'profiles/1984443560.png', 'London', '9', '2023-05-31 00:00:00', '16:38'),
(11, '169325', 'Active', 'Aisha', '', ' Ebanks', '1992-01-22', 'Support Worker', 'Female', 'Isha.ebanks@gmail.com ', '116b Colney Hatch Lane', 'N10 1EA', '07385655776', 'profiles/488906501.png', 'London', '10', '2023-05-31 00:00:00', '16:45'),
(13, '169325', 'Active', 'Michael', '', 'Henry', '1962-12-06', 'Support Worker', 'Male', 'mrbabingtan@hotmail.com ', '31 Woodside Road', 'N22 5HP', '07931328881', 'profiles/338173179.png', 'London', '10', '2023-05-31 00:00:00', '16:49'),
(15, '169325', 'Active', 'Oluseyi', '', 'Fadayomi', '2023-05-31', 'Support Worker', 'Male', 'fadoski2001@gmail.com ', '8 Jefferson Way', 'CO7 8GN', '07400469725', 'profiles/197646404.jpg', 'Colchester', '10', '2023-05-31 00:00:00', '16:52'),
(16, '169325', 'Active', 'Abiodun', '', 'Abiola', '1962-04-20', 'Care Support Worker', 'Male', 'dgproperty07@hotmail.co.uk  ', '15 Reynolds House, Ayley Croft, Enfield', 'EN1 1XR', '07466574753', 'profiles/574139387.png', 'United Kingdom', '9', '2023-05-31 00:00:00', '16:53'),
(17, '169325', 'Active', 'Akinwale', 'Ayodele', 'Ibidapo-Obe', '1957-03-29', 'Support Worker', 'Male', 'akinwaleibidapoobe@gmail.com  ', '11 Betsham Road', 'DA8 2BG', '07970084507', 'profiles/921730731.png', 'London', '10', '2023-05-31 00:00:00', '16:58'),
(18, '169325', 'Active', 'Fatimo', '', 'Olowolaiyemo', '1992-01-02', 'Support Worker', 'Female', 'Titilopeatinuke@yahoo.com  ', '121a Gosport Road', 'E17 7LX', '07446131487', 'profiles/470518784.png', 'London', '10', '2023-05-31 00:00:00', '17:01'),
(19, '169325', 'Active', 'Mohamed', '', 'Jama', '1981-12-30', 'Support Worker', 'Male', 'Adamjama88@gmail.com ', '35 Churchill Court, Newmarket Avenue', 'UB5 4EP', '07901308556', 'profiles/280806162.jpg', 'London', '10', '2023-05-31 00:00:00', '17:06'),
(20, '169325', 'Archived', 'Gbenga', '', 'Anthony', '1971-02-09', 'Support Worker', 'Male', 'Gbengaanthony@yahoo.com  ', '8 Anne Mews Barking', 'IG11 8GH', '07951382467', 'profiles/user.png', 'London', '10', '2023-05-31 00:00:00', '17:09'),
(21, '169325', 'Active', 'Feven', '', 'Andemichael', '1979-09-21', 'Support Worker', 'Male', 'Fevennimrod@yahoo.com  ', 'Flat 24 Athlone House, Wilkine Street', 'NW5 4LS', '07939142823', 'profiles/254455024.png', 'London', '10', '2023-05-31 00:00:00', '17:11'),
(22, '169325', 'Active', 'Sia', 'Christiana Dennisa', 'Kabba-Sei', '2023-05-31', 'Support Worker', 'Female', 'moiwosia@yahoo.com  ', '39 Chelmsford Road, Leytonstone', 'E11 1BT', '07476360506', 'profiles/775051319.png', 'London', '10', '2023-05-31 00:00:00', '17:14'),
(23, '169325', 'Active', 'Catherine', '', 'Sugrue', '1953-09-09', 'Support Worker', 'Female', 'c.indigo@sky.com ', 'Flat 17, 79 Dame Street', 'N1 7FS', '07853 944019', 'profiles/916464955.png', 'London', '11', '2023-05-31 00:00:00', '17:18'),
(24, '169325', 'Active', 'Blessing', '', 'Adjei', '1993-10-21', 'Care Assistant', 'Female', 'Blessingadjei31@gmail.com ', '45 Templemead House, Homerton Road', 'E9 5PU', '07497606285', 'profiles/1241573391.png', 'London', '10', '2023-05-31 00:00:00', '17:21'),
(25, '169325', 'Active', 'Nurie ', '', 'Laci', '1974-08-13', 'Support Worker', 'Female', 'ariflaci@hotmail.co.uk ', '39 Roedean Avenue', 'EN3 5QL', '07412 625313', 'profiles/216693569.png', 'Enfield', '11', '2023-05-31 00:00:00', '17:24'),
(26, '169325', 'Active', 'Comfort ', '', 'Serwaa', '1992-07-25', 'Support Worker', 'Female ', 'cserwaa92@gmail.com', 'Cserwaa92@gmail.com', 'EN2 6TY', '07956 036508', 'profiles/982014677.jpg', 'Enfield ', '11', '2023-05-31 00:00:00', '17:30'),
(27, '169325', 'Active', 'Bola', '', 'Owo', '1960-06-06', 'Support Worker', 'Male', 'bolaowo94@gmail.com', '7 Camberley Avenue', 'EN1 2AR', '07493 306191', 'profiles/1707967009.jpg', 'Enfield', '11', '2023-05-31 00:00:00', '17:34'),
(28, '169325', 'Active', 'Olusola', '', 'Adewole', '1968-11-15', 'Support Worker', 'Female', 'solafayoda@gmail.com', '108 Churchbury Road', 'SE9 5HZ', '07365 125242', 'profiles/1830483661.jpg', 'London', '11', '2023-05-31 00:00:00', '17:37'),
(29, '169325', 'Pending Compliance', 'Jude ', '', 'Nwamadi', '1978-07-21', 'Support Worker', 'Male', 'nwamadijudeikenna@yahoo.com ', '147 Kirkland Drive', 'EN2 0RJ', '07407 208154', 'profiles/330303750.png', 'Enfield ', '11', '2023-05-31 00:00:00', '17:40'),
(30, '169325', 'Active', 'Kamorudeen', '', 'Oyeniran', '1987-08-18', 'Support Worker', 'Male', 'oyenirankamorudeen@gmail.com ', '8 Marshall Path', 'SE28 8DX', '07312 603761', 'profiles/663316222.png', 'London', '11', '2023-05-31 00:00:00', '17:43'),
(31, '169325', 'Active', 'Oliver ', '', 'Amony ', '1977-01-22', 'Support Worker', 'Female', 'oliveramonykit41@gmail.com ', '52 Heenan Close', 'IG11 8QP', '07459 174995', 'profiles/1185337391.png', 'Essex', '11', '2023-05-31 00:00:00', '17:45'),
(32, '169325', 'Active', 'Abdulkareem', '', 'Isah', '1981-04-30', 'Support Worker', 'Male', 'talk2babaisah@yahoo.com', '189 High Road ', 'N11 1PQ', '07523 250084', 'profiles/394525629.jpg', 'London', '11', '2023-05-31 00:00:00', '17:47'),
(33, '169325', 'Active', 'Georgina ', '', 'Okhajaguan ', '1984-08-17', 'Support Worker', 'Female', 'georginaok02@gmail.com', 'Flat 7, Regnas Heights, 198 High Road', 'IG1 1LR', '07526149893', 'profiles/1594653305.jpg', 'Essex', '11', '2023-05-31 00:00:00', '17:49'),
(34, '169326', 'Inactive', 'Michael', '', 'Musenge', '2023-06-03', 'Worker', 'Male', 'musengemichaeljr@gmail.com', 'New Chalala', '10101', '0777504081', 'profiles/1953692803.webp', 'Zambia', '2', '2023-06-03 00:00:00', '21:25'),
(35, '169325', 'Active', 'Okikiola ', '', 'Alabi', '1990-05-30', 'Support Worker', 'Male', 'alabiokiola@outlook.com', 'FLAT 87 SISKIN HOUSE', 'N9 8UH', '07542334117', 'profiles/113439089.jpg', 'LONDON', '9', '2023-06-06 00:00:00', '10:36'),
(36, '169325', 'Active', 'Dennis', '', 'Muchiri', '1995-08-15', 'Support Worker', 'Male', 'dennis.chomba01@gmail.com', '17 Whitchurch Avenue', 'HA8 6HU', '07426731418', 'profiles/103917211.jpg', 'London', '9', '2023-06-06 00:00:00', '10:40'),
(37, '169325', 'Active', 'Babatope', '', 'Onabanjo', '2023-06-06', 'Support Worker', 'Male', 'Onabanjobabatope@yahoo.com', '203 Windsor Court Mast Street Ig11 7pa', 'Ig11 7pa', '07708161357', 'profiles/1964014128.png', 'London', '9', '2023-06-06 00:00:00', '10:44'),
(38, '169325', 'Active', 'Bola ', '', 'Olaifa', '2023-06-06', 'Support Worker', 'Female', 'moji56@msn.com', '56 Altair Close', 'N17 0BW', '07487403515', 'profiles/868457646.jpeg', 'London', '9', '2023-06-06 00:00:00', '10:51'),
(39, '169325', 'Active', 'Olusola', '', 'Sotinrin', '2023-06-06', 'Support Worker', 'Female', 'sollyshot@yahoo.com', '90 Bradley Road', 'EN3 6LT', '07366333343', 'profiles/1811388010.jpg', 'London', '9', '2023-06-06 00:00:00', '10:56'),
(40, '169325', 'Active', 'Oluwatosin ', '', 'Famakinwa', '1990-02-01', 'Support Worker', 'Female', 'tosinfamak1@gmail.com', '150 FERME PARK ROAD ,CROUCH END', 'N8 9SE', '07916621139', 'profiles/1623286870.jpg', 'London', '9', '2023-06-06 00:00:00', '11:01'),
(41, '169325', 'Inactive', 'Isabella ', '', 'Kabasinguzi', '1992-09-14', 'Support Worker', 'Female', 'kabasinguziizabella@gmail.com', '20 Shelton Way', 'LU29AP', '07873223389', 'profiles/430291828.jpg', 'London', '9', '2023-06-06 00:00:00', '11:07'),
(42, '169325', 'Archived', 'Constance', '', 'Obakpolor', '2023-06-06', 'Support Worker', 'Female', 'constanceobakpolor@gmail.com', '55 Idmiston Road Stratford ', 'E15 1RG', '07405544415', 'profiles/1199879938.png', 'London', '9', '2023-06-06 00:00:00', '11:43'),
(43, '', 'Inactive', 'Olalekan ', '', 'Olawale', '1987-09-09', 'Support Worker', 'Male', 'Muyiwaolawale@yahoo.com', 'Flat 2 Radleys Mead Dagenham ', 'Rm10 8sh', '07476006277', 'profiles/1291746956.png', 'London', '', '2023-06-06 00:00:00', '15:13'),
(44, '169325', 'Archived', 'Olalekan', '', 'Olawale', '1987-09-09', 'Support Worker', 'Male', 'muyiwaolawale@yahoo.com', 'Flat 2 Radleys Mead Dagenham', 'Rm10 8sh ', '07476006277', 'profiles/2095381107.png', 'London', '9', '2023-06-06 00:00:00', '15:21'),
(45, '169325', 'Active', 'Mavis ', '', 'Konama', '2000-07-13', 'Support Worker', 'Female', 'maviskonama13@gmail.com', '50 Ashburnham Road', 'LU1 1JR', '07478112311', 'profiles/2061125740.png', 'Luton', '9', '2023-06-06 00:00:00', '15:54'),
(46, '169325', 'Active', 'Golam', '', 'Shuvo', '1997-07-07', 'Support Worker', 'Male', 'grshuvo815@gmail.com  ', '28 Dunmow Road', 'E15 1TZ', '07576949251', 'profiles/1703044638.jpg', 'London', '10', '2023-06-06 00:00:00', '15:56'),
(47, '169325', 'Active', 'Mercy', '', 'Olurinu', '1958-08-15', 'Care/Support Worker', 'Female', 'mercitinu@yahoo.co.uk', '19, Gerald Road', 'RM8 1PT', '07960899407', 'profiles/435465573.png', 'London', '10', '2023-06-06 00:00:00', '16:02'),
(48, '169325', 'Active', 'Nasteho', '', 'Mohamed', '1998-06-11', 'Care Assistant', 'Female', 'nastaa10m@gmail.com', '12 Shelley Close', 'UB4 0QP', '07424647079', 'profiles/329064514.png', 'London', '10', '2023-06-06 00:00:00', '16:06'),
(49, '169325', 'Inactive', 'Elisabeth', '', 'Hippolyte', '1956-09-06', 'Domestic', 'Female', 'elisabeth139@outlook.com', '70a High Street, Plaistow', 'E13 0AJ', '07432533078', 'profiles/user.png', 'London', '10', '2023-06-06 00:00:00', '16:13'),
(50, '169325', 'Active', 'Abdulfatai', '', 'Olumegbon', '1986-12-18', 'Support Worker', 'Male', 'folumegbon@gmail.com', '6 Dumfries, Chapel Langley', 'LU1 5BG', '07879142367', 'profiles/787012700.png', 'Luton', '9', '2023-06-06 00:00:00', '16:16'),
(51, '169325', 'Inactive', 'Dibarna', '', 'Singha', '1985-01-10', 'Support Worker', 'Female', 'dibarnasingha@gmail.com', '24 Hatfield Road', 'E15 1QY', '077518813080', 'profiles/1514041236.png', 'London', '10', '2023-06-06 00:00:00', '16:22'),
(52, '169325', 'Active', 'Amope', '', 'Jimoh', '1991-11-12', 'Support Worker', 'Female', 'Lathoyetiscot@gmail.com ', '67 The Shires Old Bedford ', 'LU2 7QB', '07867285908', 'profiles/1558501188.png', 'Luton ', '9', '2023-06-06 00:00:00', '16:22'),
(53, '169325', 'Active', 'Ajibola', '', 'Charles', '1977-01-03', 'Support Worker', 'Male', 'chb5050a@gmail.com  ', '138 Leven Road', 'E14 0XS', '07784316411', 'profiles/1141547456.png', 'London', '10', '2023-06-06 00:00:00', '16:26'),
(54, '169325', 'Active', 'Eni', '', 'Adhokorie', '2023-06-06', 'Support Worker', 'Female', 'Eni.Adhokorie@gmail.com', '58 Brive Road', 'LU5 4EJ', '07868235392', 'profiles/222059364.PNG', 'Dunstable', '9', '2023-06-06 00:00:00', '16:28'),
(55, '169325', 'Active', 'Abiba', '', 'Musah', '1978-04-03', 'Support Worker', 'Female', 'musahabiba565@yahoo.com', '741A High Road', 'N17 8AG', '07931657430', 'profiles/109973552.jpg', 'London', '10', '2023-06-06 00:00:00', '16:29'),
(56, '169325', 'Inactive', 'David', '', 'Chukwu', '1990-05-12', 'Support Worker', 'Luton', 'devochuks12@gmail.com', '224 Crawley Green Road', 'LU2 0SJ', '07466006292', 'profiles/246861919.PNG', 'Luton', '9', '2023-06-06 00:00:00', '16:40'),
(57, '169325', 'Pending Compliance', 'Karima', ' ', 'Heddi', '1968-10-23', 'Support Worker', 'Female', 'k_heddi@yahoo.co.uk ', '54 Cann Hall Road', 'E11 3HZ', '07833439690 / 07429762102', 'profiles/1310333553.png', 'London', '10', '2023-06-06 00:00:00', '16:41'),
(58, '169325', 'Pending Compliance', 'Patricia', '', 'Kormawah', '1967-08-08', 'Support Worker', 'Female', 'Pkormawah@gmail.com ', '65 Urlwin Walk. Myatts Fields South Estates', 'SW9 6QJ', '07740041957', 'profiles/2014579563.png', 'London', '10', '2023-06-06 00:00:00', '16:46'),
(59, '169325', 'Pending Compliance', 'Boluwatife ', '', 'Anthony', '1989-10-24', 'Support Worker', 'Male', 'anboluwa01@gmail.com', 'Flat 2, 30 Hart Hill Drive', 'Lu2 0ax', '07459875660', 'profiles/805735833.jpg', 'Luton', '9', '2023-06-06 00:00:00', '16:46'),
(60, '169325', 'Pending Compliance', 'Pamela', '', 'Mbeta-Buhika', '1986-02-15', 'Care/Support Worker', 'Female', 'pamelabuhika@gmail.com', '28 Burnett Close, Hackney', 'E9 6ET', '07417447636', 'profiles/2090330659.png', 'London', '10', '2023-06-06 00:00:00', '16:51'),
(61, '169325', 'Pending Compliance', 'Sankung', '', 'Jatta', '1982-10-04', 'Support Worker', 'Male', 'basankungjatta@yahoo.com', 'Flat 5 Kingsmead House', 'E9 5QH', '07415643220', 'profiles/user.png', 'London', '10', '2023-06-06 00:00:00', '16:53'),
(62, '169325', 'Pending Compliance', 'Chiamaka', '', 'Obioji', '1995-01-22', 'Care/Support Worker', 'Female', 'fellyobioji@yahoo.com', '50 Alexandra Street', 'E16 4DJ', '07438336043', 'profiles/1059285542.JPG', 'London', '10', '2023-06-06 00:00:00', '16:57'),
(63, '169325', 'Pending Compliance', 'Esther', '', 'Ogunnubi', '1981-04-11', 'Care/Support Worker', 'Female', 'olufunkeogunnubi@gmail.com', '58, Ashley Drive', 'WD6 2JD', '07771031254', 'profiles/774370406.png', 'Borehamwood', '10', '2023-06-06 00:00:00', '17:06'),
(64, '169325', 'Active', 'Aanuoluwapo ', 'Silas', 'Oluwadare', '1991-09-12', 'Care Worker ', 'Male', 'Silasaanuoluwapo12@gmail.com ', '59-61 Guildford Street', 'LU1 2NL', '07947488409', 'profiles/1737030417.jpg', 'Luton', '1', '2023-06-08 00:00:00', '17:42'),
(65, '169325', 'Active', 'Adebola', '', 'Afolabi', '1964-02-17', 'Care Worker', 'Female', 'sekbol@yahoo.com', 'Flat 7, Gaby House, 15 Belton Road', 'E7 9PF', '07727121215', 'profiles/855354671.jpg', 'London', '1', '2023-06-08 00:00:00', '17:50'),
(66, '169325', 'Archived', 'Adedolapo', '', 'Olomola', '1989-05-20', 'Care Worker', 'Female', 'dolapoolomola@gmail.com  ', '2308 Hobart Buildign', 'E14 9LE', '07823724459', 'profiles/1890630601.JPG', 'London', '1', '2023-06-08 00:00:00', '17:56'),
(67, '169325', 'Active', 'Adekunle', '', 'Toluju', '1967-07-03', 'Care Worker', 'Male', 'atoluju@gmail.com  ', 'FLAT 12 BEDFORD HOUSE', '41-43 DUDLEY STREET', '07823581825', 'profiles/277306953.jpg', 'Luton', '1', '2023-06-08 00:00:00', '18:14'),
(68, '169325', 'Inactive', 'Adetola', 'Favour', 'Alonge', '1994-04-09', 'Care Worker', 'Female', 'adetolaalonge@gmaail.com', '50 Mansion, Flat 30D Canalside, Lower Loveday Street', 'B19 3SJ', '07776465234', 'profiles/1276335559.JPG', 'Birmingham', '1', '2023-06-08 00:00:00', '18:17'),
(69, '169325', 'Inactive', 'Angele', '', 'FAFONA', '1992-07-02', 'Care Worker', 'Female', 'hardeolahr438@gmsil.com   ', 'Flat 45 Berwick House 8-10b Knoll Rise', 'BR6 OFD', '07572762038', 'profiles/1875081586.png', 'London ', '1', '2023-06-08 00:00:00', '18:21'),
(70, '169325', 'Active', 'Anthonia', '', 'Ogunyemi', '1974-05-21', 'Care Worker', 'Female', 'gbemisolaogunyemi@yahoo.com  ', '1 Proctor Place', 'RM13 7BJ', '07538057018', 'profiles/983015029.png', 'Essex', '1', '2023-06-08 00:00:00', '18:27'),
(71, '169325', 'Inactive', 'Basirat', '', 'Atilade', '1974-12-12', 'Care Worker', 'Female', 'basiratatilade@gmail.com  ', '34 Frinton Road', 'NG8 6GQ', '01158492646', 'profiles/363876898.jpg', ' Nottingham', '1', '2023-06-08 00:00:00', '18:30'),
(72, '169325', 'Active', 'Belenda', '', 'Chi', '1985-08-09', 'Care Worker', 'Female', 'ebudechi@yahoo.com  ', '8 Zion Place', 'DA12 1BH', '07727136428', 'profiles/1418511932.png', 'Kent', '1', '2023-06-08 00:00:00', '18:34'),
(73, '169325', 'Inactive', 'Blessing', '', 'Abang', '1990-05-11', 'Care Worker', 'Female', 'Blessingabang692@gmail.com  ', '142 Midland Road', 'LU2 0GH', '07407036869', 'profiles/1295026780.jpg', 'Luton', '1', '2023-06-08 00:00:00', '18:40'),
(74, '169325', 'Active', 'Blessing', '', ' Ivwurie', '1971-02-19', 'Care Worker', 'Female', 'Ndee418@gmail.com    ', '29 Harewood Avenue', 'NG6 9EF', '07491640048', 'profiles/2050013534.png', 'Nottingham', '1', '2023-06-08 00:00:00', '18:47'),
(75, '169325', 'Active', 'Celine ', '', 'Igbokwe', '1978-09-20', 'Care Worker', 'Female', 'Celinechidinma@yahoo.com ', '303 Carat House 34 Ursula Gould Way', 'E14 7FZ', '07878853247', 'profiles/1047359053.png', 'London', '1', '2023-06-08 00:00:00', '18:54'),
(76, '169325', 'Inactive', 'Chibuzoh', '', 'Okoro', '1992-02-21', 'Care Worker', 'Male', 'okoro4g@gmail.com', '14 Gladstone Avenue', 'LU1 1QL', '+44 7915 576518', 'profiles/119508335.jpg', 'luton', '1', '2023-06-08 00:00:00', '18:57'),
(77, '169325', 'Inactive', 'Chidera', 'Valentine', 'OKOYE', '1994-07-04', 'Care Worker', 'Male', 'deraval042@gmail.com', 'Flat 11 Lochbie Mansion Crunch Hill ', 'N4 4sb', '07769760776', 'profiles/1189871316.jpg', 'London', '1', '2023-06-08 00:00:00', '19:01'),
(78, '169325', 'Inactive', 'Edikan', '', 'Amusa', '1984-08-24', 'Care Worker', 'Female', 'spicyedikan@yahoo.com ', 'FLAT 16,59-61 GUILDFORD STREET', 'NIL', '07769383551', 'profiles/413109270.jpg', 'Luton', '1', '2023-06-08 00:00:00', '19:06'),
(79, '169325', 'Inactive', 'Edwige', '', 'Tekeu', '1976-12-27', 'Care Worker', 'Female', 'edwigelono@yahoo.fr', '59 Heneage Street Nechells', 'B7 4NF', '07863826480', 'profiles/1035204346.png', 'Birmingham', '1', '2023-06-08 00:00:00', '19:17'),
(80, '169325', 'Inactive', 'Eiman', '', 'Habob', '1987-07-20', 'Care Worker', 'Female', 'eiman.yagoub@yahoo.com', 'Flat 51 Linconl Tower Gilby Road', 'B16 8RH', '07480937675', 'profiles/1390566550.jpg', 'Birmingham', '1', '2023-06-08 00:00:00', '19:22'),
(81, '169325', 'Inactive', 'Elizabeth', '', 'Conteh', '1986-11-21', 'Care Worker', 'Female', 'thatuconteh@hotmail.co.uk     ', 'Flat 28 Matcham Court, 45 Hannibal Road', 'E1 3FF', '07446068724', 'profiles/1247155162.png', 'London', '1', '2023-06-08 00:00:00', '19:25'),
(82, '169325', 'Archived', 'Ellen', '', 'ACHIAA', '1991-06-05', 'Care Worker', 'Female', 'ellen.achiaa70@yahoo.com', '28 MARTIGALE CHASE', 'RG14 2EN', '07452896371', 'profiles/1085022912.jpg', 'NEWBURY', '1', '2023-06-10 00:00:00', '14:44'),
(83, '169325', 'Inactive', 'Onyelo', 'Evelyn', 'Aganoke', '1983-04-29', 'Care Worker', 'Female', 'EAGANOKE@GMAIL.COM  ', '37, PITFIELD CRESCENT THAMESMEAD', 'SE28 8RG', '07484370773', 'profiles/387189403.JPG', 'London', '1', '2023-06-10 00:00:00', '14:50'),
(84, '169325', 'Inactive', 'Idongesit', '', 'Effiong', '1982-06-12', 'Care Worker', 'Male', 'sitdonig@yahoo.com', '6 Colwick Road', 'NG2 4BU', '07368955126', 'profiles/171522931.JPG', 'Nottingham', '1', '2023-06-10 00:00:00', '14:52'),
(85, '169325', 'Active', 'Ife', 'Funke', 'Ajijo', '1975-11-07', 'Care Worker', 'Female', 'Ajijofunke1975@gmail.com   ', 'Flat 62 Cricketers Close Erith', 'DA8 1TX', '07737009815', 'profiles/2098158580.JPG', 'Kent', '1', '2023-06-10 00:00:00', '14:56'),
(86, '169325', 'Inactive', 'Izogie', '', 'Ogiehor', '1978-08-11', 'Care Worker', 'Female', 'Izogiejulius@gmail.com', '9 Buckton Road', 'WD6 4HN', '07872 51 8347', 'profiles/286678095.jpg', 'Borehamwood Hertfordshire', '1', '2023-06-10 00:00:00', '15:00'),
(87, '169325', 'Active', 'Joy', '', 'Aigbefoh', '1995-05-21', 'Care Worker', 'Female', 'Joyaigbefoh@gmail.com', '698 Pershore Road ', 'B29 7NR', '07856230330', 'profiles/1018185779.png', 'Birmingham', '1', '2023-06-10 00:00:00', '15:05'),
(88, '169325', 'Inactive', 'Judith', '', 'Ogar', '1982-04-23', 'Care Worker', 'Female', 'Judith_ogar@yahoo.com', '19,Canada Road, Erith,', 'DA82HE', '07466609278', 'profiles/1342269089.png', 'Kent', '1', '2023-06-10 00:00:00', '15:08'),
(89, '169325', 'Active', 'Kafayat', '', 'Ogunmola', '1987-10-22', 'Care Worker', 'Female', 'kefyshe2@gmail.com', '128A,ROCHESTER WAY', 'SE3 8AR', '07593122781', 'profiles/308284342.png', 'London', '1', '2023-06-10 00:00:00', '15:10'),
(90, '169325', 'Inactive', 'Kenneth', '', 'Nwokocha', '1967-05-03', 'Care Worker', 'Male', 'kenwok1@yahoo.com', '5 Warriner Drive', 'N9 0NW', '07411309109', 'profiles/user.png', 'London', '1', '2023-06-10 00:00:00', '15:13'),
(91, '169325', 'Inactive', 'Kenneth', 'Elijah', 'Wasswa', '1982-08-19', 'Care Worker', 'Male', 'ELIJKENN@GMAIL.COM', '16 CHARGEABLE ', 'E13 8DFt', '07466811073', 'profiles/591926203.JPG', 'London', '1', '2023-06-10 00:00:00', '15:21'),
(92, '169325', 'Active', 'Khadijah', 'M', 'Kamara', '2000-09-15', 'Care Worker', 'Female', 'khadijahkamara11@gmail.com', '331 Harlesden Road, Flat 2', 'NW10 3RX', '07769344734', 'profiles/387694895.png', 'London , Brent', '1', '2023-06-10 00:00:00', '15:23'),
(93, '169325', 'Active', 'Mary', '', 'John', '1962-10-07', 'Care Worker', 'Female', 'maryjohn1062@yahoo.com', '109 Lindfield Road', 'NG8 6HL', '0772286986', 'profiles/1203898493.jpg', 'Nottingham', '1', '2023-06-10 00:00:00', '15:27'),
(94, '169325', 'Active', 'Mildred', '', 'Allah-kaku', '1990-12-20', 'Care Worker', 'Female', 'milagobabe39@gmail.com', '27A Fredrick Crescent Enfield', 'EN3 7HH', '07519521050', 'profiles/1330530003.JPG', 'London', '1', '2023-06-10 00:00:00', '15:29'),
(95, '169325', 'Active', 'Adebola', ' Musibau', 'Adesina', '1995-09-09', 'Care Worker', 'Male', 'mcadesina@gmail.com', '58, REGINALD STREET', 'LU2 7QZ', '07771098414', 'profiles/610335247.JPG', 'LUTON', '1', '2023-06-10 00:00:00', '15:32'),
(96, '169325', 'Active', 'Nadene', '', 'Magdeliene', '1971-11-04', 'Care Worker', 'Female', 'Magnadene71@hotmail.com', '94 Zambezi Drive', 'N9 OGU', '07804415063', 'profiles/user.png', 'London', '1', '2023-06-10 00:00:00', '15:35'),
(97, '169325', 'Active', 'Ola', ' Bukola', 'Olaosebikan', '1980-07-09', 'Care Worker', 'Female', 'OLABUKOLAOSIBODU@YAHOO.COM', '9 SELINA CLOSE', 'LU3 3AW', '07807575956', 'profiles/1502965737.JPG', 'LUTON, BEDFORSHIRE', '1', '2023-06-10 00:00:00', '15:38'),
(98, '169325', 'Active', 'Olubunmi', '', 'Toluju', '1976-09-25', 'Care Worker', 'Female', 'OLUWABUNMITOLUJU@GMAIL.COM', 'FLAT 12 NEWBEDFORD HOUSE, 41-43 DUDLEY STREEY', 'LU2 0NP', '07823581833', 'profiles/1796023375.JPG', 'luton', '1', '2023-06-10 00:00:00', '15:41'),
(99, '169325', 'Inactive', 'Oluwatosin', 'Ati', 'Adeleke', '1983-12-19', 'Care Worker', 'Female', 'tbestofgod83@gmail.com', '63 Melbourne Road Stapleford', 'NG9 8NE', '07459032161', 'profiles/1703590686.JPG', 'Nottingham', '1', '2023-06-10 00:00:00', '15:44'),
(100, '169325', 'Inactive', 'Rasheedat', '', 'Ajinaja', '1978-02-23', 'Care Worker', 'Female', 'Rashidodalw@gmail.com', '107 Lindfield Road ', 'NG8 6HL', '01159193271', 'profiles/1038415327.jpg', 'Nottingham', '1', '2023-06-10 00:00:00', '15:48'),
(101, '169325', 'Active', 'Roberta', '', 'Ahiaba', '1987-05-11', 'Care Worker', 'Female', 'vahiate@icloud.com    ', '168 Chandlers Drive', ' DA8 1LW', '07957337084', 'profiles/118682603.JPG', 'Erith, Kent ', '1', '2023-06-10 00:00:00', '15:53'),
(102, '169325', 'Inactive', 'Sona', 'Diabang Ep ', 'Danfa', '1988-03-18', 'Care Worker', 'Female', 'dsona807@yahoo.com', '3 Manor Street ,', 'NG2 4JP', '07415618067', 'profiles/1131498562.jpg', 'Nottingham', '1', '2023-06-10 00:00:00', '15:56'),
(103, '169325', 'Inactive', 'Temitope', '', 'Olusanya', '1990-05-12', 'Care Worker', 'Female', 'adetopeolusanya@gmail.com', '4 Denzil Road ', 'NW10 2UP', '07878943357', 'profiles/513694536.jpg', 'Neasden', '1', '2023-06-10 00:00:00', '15:57'),
(104, '169325', 'Active', 'Thandolwenkosi', '', 'Sibenke', '1999-04-19', 'Care Worker', 'Female', 'miissibenke@hotmail.com', '10 Deakin Avenue', 'WS8 7QA', '07413891928', 'profiles/1466952330.jpg', 'Walsall', '1', '2023-06-10 00:00:00', '16:00'),
(105, '169325', 'Active', 'Uduak', '', 'Unwene', '1976-02-27', 'Care Worker', 'Female', 'uduakunwene@gmail.com', '49 Whitton Walk, Alfred Street', 'E3 2AF', '07908097602', 'profiles/799362266.jpg', 'London', '1', '2023-06-10 00:00:00', '16:02'),
(106, '169325', 'Archived', 'Zainab', '', 'Abubakar', '1997-07-15', 'Care Worker', 'Female', 'zainabsadeeq18@gmail.com', '7 Greenfield Street ', 'NG7 2JN', '07715433187', 'profiles/1323521468.jpg', 'Nottingham', '1', '2023-06-10 00:00:00', '16:06'),
(107, '169325', 'Inactive', 'Olawunmi', '', 'Onolaja', '1976-09-18', 'Care Worker', 'Female', 'wunmicecil@yahoo.com', '5 Manor Street', 'NG2 4JP', '07438610671', 'profiles/1748174049.jpg', 'Nottingham', '1', '2023-06-10 00:00:00', '16:10'),
(108, '169325', 'Active', 'Taofeek', 'Oluwaseun', 'Akande', '1992-09-23', 'Care Worker', 'Male', 'TAKANDE40@YAHOO.COM', 'FLAT 4 POSEIDON COURT, SPINNAKER CLOSE ', 'IG11 0GR', '07466664829', 'profiles/142135061.JPG', 'Barking, Essex', '1', '2023-06-10 00:00:00', '16:14'),
(109, '169325', 'Archived', 'Bamidele ', '', 'Emmanuel', '1976-07-07', 'Support Worker', 'Male', 'bamoski_lala_yahoo.com', '293 Kingshill Avenue', 'UB4 8BP', '07400 174741', 'profiles/982656676.jpg', 'Hayes', '11', '2023-06-12 00:00:00', '16:29'),
(110, '169325', 'Archived', 'Helen', '', 'Osafor', '1976-03-08', 'Support Worker', 'Female', 'helenmichaeljohnson@yahoo.com', 'Flat 11, Ingrebourne Court, Chingford Avenue', 'E4 6RL', '07802 505557', 'profiles/user.png', 'London', '11', '2023-06-12 00:00:00', '16:33'),
(111, '169325', 'Archived', 'Lucky ', '', 'Omokaro', '1984-06-26', 'Support Worker', 'Male ', 'omokaroovie@gmail.com', '50 Conway Road ', 'SE18 1AR', '07725 781235', 'profiles/316904663.JPG', 'London', '11', '2023-06-12 00:00:00', '16:37'),
(112, '169325', 'Archived', 'Yodit ', '', 'Kidane ', '1992-09-09', 'Support Worker', 'Female ', 'yodit_kidane@hotmail.com', 'Flat 18 Morland House, Marsham Street', 'SW1P 4JH', '07538 657114', 'profiles/1701072994.jpg', 'London', '11', '2023-06-12 00:00:00', '16:39'),
(113, '169325', 'Pending Compliance', 'David', 'Ohimamene', 'Akposkho', '1996-08-20', 'Support Worker', 'Male', 'davidskillzboy@yahoo.com', '59a Epsom Road', 'SM4 5PR', '07438076501', 'profiles/636364799.jpg', 'Morden', '11', '2023-06-12 00:00:00', '16:42'),
(114, '169325', 'Archived', 'Yusif ', '', 'Ibrahim', '1999-01-11', 'Support Worker', 'Male', 'instudentoo@gmail.com', '48 Veronica Close ', 'RM3 8JN', '07939819075', 'profiles/user.png', 'Romford', '11', '2023-06-12 00:00:00', '16:44'),
(115, '169325', 'Archived', 'Maryam ', 'Mohamed', 'Omar', '1991-12-15', 'Support Worker', 'Female ', 'maryam.m.omar2@gmail.com', 'Flat B, 2 Wyndham Crescent', 'N19 5QJ', '07474 572343', 'profiles/user.png', 'London', '11', '2023-06-14 00:00:00', '14:20'),
(116, '169325', 'Pending Compliance', 'Ayotunde ', 'Olufunso', 'Akande', '1973-02-17', 'Support Worker', 'Male', 'akandeayo0@gmail.com', '29 Adams Way', 'CR0 6XN', '07311679787', 'profiles/user.png', 'Croydon', '11', '2023-06-14 00:00:00', '14:24'),
(117, '', 'Inactive', 'Austin ', '', 'Okoro', '1966-08-20', 'Support Worker', 'Male', 'austinokoro20@yahoo.co.uk', '43a Victoria Way', 'SE7 7UF', '07496 948975', 'profiles/user.png', 'London', '', '2023-06-16 00:00:00', '12:56'),
(118, '169325', 'Pending Compliance', 'Austin', '', 'Okoro', '1966-08-20', 'Support Worker', 'Male', 'austinokoro20@yahoo.co.uk', '43a Victoria Way', 'SE7 7UF', '07496948975', 'profiles/user.png', 'London', '11', '2023-06-16 00:00:00', '13:01'),
(119, '169325', 'Archived', 'Diafuka', 'Chouchou', 'Samu', '1979-09-05', 'Support Worker', 'Female ', 'chouchous@ymail.com', '805 Great Cambridge Road', 'EN1 4BU', '07961 674811', 'profiles/user.png', 'Enfield ', '11', '2023-06-16 00:00:00', '13:05'),
(120, '169325', 'Active', 'Chilton ', '', 'Panda', '1990-07-05', 'Support Worker ', 'Male', 'big_m.a.n@hotmail.com', 'Flat 18, 104 Hindrey Road', 'E5 9HQ', '07309 072705', 'profiles/1040784515.jpg', 'London', '11', '2023-06-16 00:00:00', '13:08'),
(121, '169325', 'Pending Compliance', 'Abiola ', '', 'Sutton', '1963-10-30', 'Support Worker', 'Female ', 'bosy.beauty@yahoo.co.uk', '30 Yarlington Court, 1 Sparkford Gardens', 'N11 3GS', '07932 710404', 'profiles/user.png', 'London', '11', '2023-06-16 00:00:00', '13:10'),
(122, '169325', 'Pending Compliance', 'Daniel ', '', 'Mosima ', '1984-02-25', 'Support Worker', 'Male', 'danielmosima18@gmail.com ', '76 Coldbath Street', 'SE15 7RG', '07498563192', 'profiles/1673034416.png', 'London', '11', '2023-06-16 00:00:00', '13:14'),
(123, '169325', 'Pending Compliance', 'Habib', 'Oluwadamilola', 'Yusuf', '1991-09-20', 'Support Worker', 'Male', 'damilo4u@gmail.com', '3 Laila Terrace', 'N9 0AG', '07776 760841', 'profiles/590515279.jpg', 'London', '11', '2023-06-19 00:00:00', '12:40'),
(124, '169325', 'Archived', 'Donovan', '', 'Housen', '1974-11-07', 'Support Worker', 'Male', 'dahousen@yahoo.com', '51b Canadian Avenue', 'SE6 3AX', '07931744370', 'profiles/user.png', 'London', '11', '2023-06-19 00:00:00', '12:42'),
(125, '169325', 'Archived', 'Evelyn ', '', 'Tangiri', '1972-03-12', 'Social Worker', 'Female', 'l.tangiri@yahoo.co.uk', '27 Blewbury House, Yarnton Way', 'SE2 9UJ', '07545 107351', 'profiles/1565174715.jpg', 'London', '11', '2023-06-19 00:00:00', '12:45'),
(126, '169325', 'Active', 'Adenike ', '', 'Akinwumi', '1990-03-03', 'Support Worker', 'Female', 'adenike.adeshina15@gmail.com ', 'Flat 14 Lancefield House, Nunhead Lane', 'SE15 3UP', '07597 925062', 'profiles/1148003090.png', 'London', '11', '2023-06-19 00:00:00', '12:50'),
(127, '169325', 'Inactive', 'Afia', '', 'Obodai', '1991-08-16', 'Support Worker', 'Female', 'princella16@yahoo.com ', '50 Gascoigne Close', 'N17 8BA', '07383 726625', 'profiles/51941042.png', 'London', '11', '2023-06-19 00:00:00', '13:00'),
(128, '169325', 'Inactive', 'Afolasade', '', 'Alatishe', '1980-01-13', 'Support Worker', 'Female', 'afolasadealatishe@gmail.com ', '53 St. Matthews Road', 'SW2 1NE', '07366 726626', 'profiles/433579323.png', 'London', '11', '2023-06-19 00:00:00', '13:02'),
(129, '169325', 'Inactive', 'Mabinty ', '', 'Jalloh', '1974-12-26', 'Support Worker', 'Female ', 'mabintyjalloh49@yahoo.com', '53 Oslac Road', 'SE6 3QB', '07916 338652', 'profiles/565238754.png', 'London', '11', '2023-06-19 00:00:00', '13:04'),
(130, '169325', 'Inactive', 'Michael ', '', 'Twum-Barimah', '1983-12-30', 'Support Worker', 'Male', 'berbegh@yahoo.com ', '105 Camrose Avenue', 'HA8 6BY', '07388 368577', 'profiles/1674657351.png', 'Edgware', '11', '2023-06-19 00:00:00', '13:05'),
(131, '169325', 'Active', 'Muyideen ', '', 'Olasimbo', '1973-03-18', 'Support Worker', 'Male', 'muyideenolasimbo5@gmail.com', '175 Greenhaven Drive', 'SE28 8FU', '07878 908488', 'profiles/1136170675.jpg', 'London', '11', '2023-06-19 00:00:00', '13:08'),
(132, '169325', 'Inactive', 'Oluwakemi', 'Motunrayo', 'Fayemi', '1992-08-08', 'Support Worker', 'Female ', 'mo5unrayokemi25@gmail.com ', '110a Beulah Road', 'CR7 8JF', '07947 629805', 'profiles/830486546.png', 'Thornton Heath', '11', '2023-06-19 00:00:00', '13:25'),
(133, '169325', 'Inactive', 'Philipah', '', 'Amoah', '1992-11-23', 'Support Worker', 'Female', 'msphilipah_afia@hotmail.com', '80 Burncroft Avenue', 'EN3 7JH', '07713 695838', 'profiles/705040514.jpg', 'Enfield', '11', '2023-06-19 00:00:00', '13:37'),
(134, '169325', 'Active', 'Olubunmi ', 'Omowunmi', 'Awobimpe', '1966-10-24', 'Admin/Customer Support', 'Female', 'oluawobimpe@yahoo.co.uk', '8 Bickleigh House, Frogwell Close', 'N15 6ED', '07940 902076', 'profiles/1274263006.jpg', 'London', '11', '2023-06-20 00:00:00', '14:24'),
(135, '169325', 'Archived', 'Elizabeth', '', 'Sirtuy', '2023-06-22', 'Support Worker', 'Female', 'sirtuy@gmail.com', 'Sundon Park, Luton LU3 3ES', 'LU3 3ES', '07442060334', 'profiles/956839695.jpg', 'Luton', '9', '2023-06-22 00:00:00', '10:32'),
(136, '169325', 'Active', 'Yohana', '', 'Tesfamichael', '1994-01-07', 'Support Worker', 'Female', 'Yohanatesfamichaelst@gmail.com', '68 Margery Fry Court Tufnell Park Road', ' N7 0DR', '07506531916', 'profiles/155805614.png', 'London', '9', '2023-06-22 00:00:00', '10:56'),
(137, '169325', 'Inactive', 'Seye', '', 'Omolagbe', '1985-03-01', 'Support Worker', 'Male', 'seyeomolagbe@gmail.com', '20 Heron Quay, Bedford', 'MK40 1TH', '07307252398', 'profiles/69057648.png', 'Bedfordshire', '9', '2023-06-22 00:00:00', '11:02'),
(138, '169325', 'Inactive', 'Issa', '', 'Kargbo', '1987-07-20', 'Heath Care Assistant', 'Male', 'issakargbo538@gmail.com', '21 Tappesfield Road', 'SE15 3HD', '0728224374', 'profiles/user.png', 'London', '15', '2023-07-17 00:00:00', '15:48'),
(139, '169325', 'Inactive', 'Rosaline', '', 'Nartey ', '1967-01-31', 'Support Worker', 'Female', 'Mamechieouse@yahoo.co.uk   ', '1 Cornwall Close', 'EN8 7RA', '07505478710', 'profiles/648340375.jpg', 'Hertfordshire', '15', '2023-07-17 00:00:00', '15:55'),
(140, '169325', 'Inactive', 'Sreekutty', 'Radhamoni', 'Amma', '1995-10-25', 'Support Worker', 'Female', 'sreesidhi526@gmail.com', '20 Elmfield House Highburry', 'E33 NW', '07469640758', 'profiles/6410848.jpg', 'London', '15', '2023-07-17 00:00:00', '16:02'),
(141, '169325', 'Inactive', 'Sheethal', '', 'Raju', '2000-02-15', 'Support Worker', 'Female', 'sheethalammu15@gmail.com', '47 Whyteville Road', 'E7 91P', '07391586863', 'profiles/1929102421.png', 'London', '15', '2023-07-17 00:00:00', '16:06'),
(142, '169325', 'Inactive', 'Kingsley ', '', 'Boateng', '1973-08-24', 'Clearner', 'Male', 'Kingsleyboateng586@gmail.com', 'Flat 12 Shacklewell House', 'E8 3EQ', '07957134499', 'profiles/user.png', 'London', '15', '2023-07-17 00:00:00', '16:17'),
(143, '169325', 'Inactive', 'Ibrahim', '', 'Elmi', '1956-03-03', 'Cleaning', 'Male', 'ibelmi33@hotmail.com', '20 Elmfield House Highbury', 'N5 2NT', '07473811989', 'profiles/473247539.jpg', 'London', '15', '2023-07-17 00:00:00', '16:20'),
(145, '169325', 'Inactive', 'Esther', '', 'Omoniyi', '1983-04-21', 'Support Worker', 'Female', 'Nifemitanwa@gmail.com  ', '63 Dale Street ', 'DA1 5TY', '0792527292120', 'profiles/1134626165.jpg', 'Kent', '15', '2023-07-17 00:00:00', '16:37'),
(146, '169325', 'Inactive', 'Cynthia ', '', 'Osisiogu', '1972-06-06', 'HCA / Support Worker', 'Female', 'cynthibube@yahoo.com ', '103a Bellegrove Road', 'DA16 3PG', '07916188180', 'profiles/user.png', 'Kent', '15', '2023-07-17 00:00:00', '16:42'),
(147, '169325', 'Inactive', 'Adeola', '', 'Adeogun', '1978-09-08', 'Home Care / Support Assistant', 'Male', 'adeolaadeogun@gamil.com', '42 Guinness Square', 'SE1 4HH', '07778609915', 'profiles/1672199996.jpg', 'London', '15', '2023-07-17 00:00:00', '17:01'),
(148, '169325', 'Inactive', 'Adetutu', '', 'Sholoye', '1961-10-22', 'Support Worker', 'Female', 'sholoyead@aol.com', '2 Heathaway House', 'N1 6QE', '07538861670', 'profiles/user.png', 'London', '15', '2023-07-17 00:00:00', '17:07'),
(149, '169325', 'Active', 'Charlotte ', '', 'Kumah', '1990-10-13', 'Healthcare Assistant', 'Female', 'Kcharlotte922@gmail.com', '41 Albert Close', 'NG15 7UZ', '07309472740', 'profiles/669701640.png', 'Huckanl', '15', '2023-07-17 00:00:00', '17:10'),
(150, '169325', 'Pending Compliance', 'Ganiyat', '', 'Adesina-Agboola', '2023-07-18', 'Care / Support Worker', 'Female', 'Adeghaniya02@gmail.com', '58 Reginald Street ', 'LU2 7QZ', '07771098418', 'profiles/user.png', 'Bedforshire', '15', '2023-07-18 00:00:00', '09:31'),
(151, '169325', 'Pending Compliance', 'Carmen', '', 'Bacale Mangue', '1982-02-12', 'Support Worker', 'Female', 'carmenbacalemangue@gmail.com ', 'FLAT 114 SEVENTH AVENUE MANOR PARK', 'E12 5JH', '07742884395', 'profiles/2101572171.jpg', 'LONDON', '9', '2023-07-27 00:00:00', '09:29'),
(152, '169325', 'Inactive', 'Deborah', '', 'Debrah', '2023-07-27', 'Support Worker', 'Female', 'anophil2012@gmail.com ', 'CLIFTON HALL STAIRCASE N,ROOM 034', 'UB8 3PH', '07920178319', 'profiles/1754885445.jpg', 'London', '9', '2023-07-27 00:00:00', '09:32'),
(153, '169325', 'Inactive', 'Joy', '', 'Ukwa', '1972-10-02', 'Support Worker', 'Female', 'destinyjoy2009@gmail.com', '3,porlock Road ', 'EN12NH', '07586892967', 'profiles/user.png', 'London', '9', '2023-07-27 00:00:00', '09:52'),
(154, '169325', 'Inactive', 'Oluwagbenga', '', 'Joseph', '1978-12-02', 'Support Worker', 'Male', 'KUPONIKE23@YAHOO.COM', '31,CHAUCER COURT MILTON GARDEN ESTATE HOWARD ROAD ', 'N16 8TS', '07930986197', 'profiles/1556945572.jpg', 'London', '9', '2023-07-27 00:00:00', '09:58'),
(155, '169325', 'Inactive', 'Omotola', '', 'Opayinka', '1985-11-15', 'Support Worker', 'Female', 'omotolagil@yahoo.co.uk  ', 'Flat 123 Lincoln Court Bethune Road ', ' N16 5EA', '07951 781908', 'profiles/528685422.jpg', 'London', '9', '2023-07-27 00:00:00', '10:06'),
(156, '169325', 'Pending Compliance', 'Sylvie ', '', 'Makombo', '2023-07-27', 'Support Worker', 'Female', 'sykombo@yahoo.co.uk', '50 Eluelorwin House 6 Commander Avenue', 'nw9 5zb', '07411597365', 'profiles/user.png', 'London', '9', '2023-07-27 00:00:00', '10:09'),
(157, '169325', 'Pending Compliance', 'Sympathy', '', 'Madumere', '1989-04-23', 'Support Worker', 'Female', 'symze@yahoo.com', '58 Brookfield Road ', 'N90dd', '07758008622', 'profiles/user.png', 'London', '9', '2023-07-27 00:00:00', '10:11'),
(158, '169325', 'Inactive', 'Michael', 'Test', 'Musenge', '2023-07-31', 'Programmer', 'Male', 'musengemichaeljr@gmail.com', 'New Chalala', '10101', '0777504081', 'profiles/user.png', 'Zambia', '1', '2023-07-31 00:00:00', '18:00'),
(159, '169325', 'Inactive', 'Michael 2', 'Test 2', 'Musenge', '2023-07-31', 'Programmer', 'Male', 'info@broad-mead.com', 'New Chalala', '10101', '0777504081', 'profiles/user.png', 'Zambia', '1', '2023-07-31 00:00:00', '18:02'),
(160, '169325', 'Inactive', 'Alice', '', 'Arko-Brako ', '1992-02-24', 'Care Assistant ', 'Female', 'amaspecial21@gmail.com', '37a Hainault Road', 'E11 1EB', '07554 286688', 'profiles/user.png', 'London', '11', '2023-08-08 00:00:00', '10:22'),
(161, '169325', 'Inactive', 'Mary', '', 'Aborah', '2023-08-08', 'Support Worker', 'Female', 'aborahmary@yahoo.co.uk', '52b Langham Road', 'N15 3RA', '07555 202106', 'profiles/1607469574.jpg', 'London', '11', '2023-08-08 00:00:00', '10:47'),
(162, '169325', 'Inactive', 'Timileyin', '', 'Oyelami', '1990-11-06', 'Support Worker', 'Male', 'oyelamitimileyin@gmail.com', '515a Norwood Road', 'SE27 9DL', '07918 383187', 'profiles/1230487252.jpeg', 'London', '11', '2023-08-08 00:00:00', '10:56'),
(163, '169325', 'Inactive', 'Valentina ', 'Oluwabepelumi', 'Awoyemi', '1996-02-14', 'Support Worker', 'Female', 'valentinaawoyemi@yahoo.com', '83 Black Boy Lane', 'N15 3AP', '07341 430672', 'profiles/user.png', 'London', '11', '2023-08-08 00:00:00', '11:10'),
(164, '169325', 'Inactive', 'Jacob', '', 'Enwunli-Nwolley', '1987-01-24', 'Care Worker', 'Male', 'jacobnwolley@gmail.com', '3A Green Street Enfield', 'EN3 7JU', '07770197997', 'profiles/1360490008.JPG', 'Enfield', '16', '2023-08-17 00:00:00', '14:46'),
(165, '169325', 'Inactive', 'Friday Okoumose', '', 'Ezeanochie', '1967-07-21', 'Care Worker', 'Male', 'fridayany1@gmail.com ', '104 Port Arthur Rd ', 'NG2 4GE', '07459044401', 'profiles/user.png', 'Nottingham', '16', '2023-08-17 00:00:00', '15:30'),
(166, '169325', 'Inactive', 'Tinashe Michael', '', 'Kaseke', '2000-01-26', 'Care Worker', 'Male', 'kaseketinashe3@gmail.com', '60 Hudson Place Plumstead London ', 'SE18 7SL', '07471389983', 'profiles/user.png', 'London', '16', '2023-08-17 00:00:00', '15:45'),
(167, '169325', 'Inactive', 'Olugbemiga', '', 'Akintayo', '1973-10-01', 'Care Worker', 'Male', 'akintayoadebayo96@gmail.com', '42 Dunkirk Street West Norwood Lambert', 'SE27 9JQ', '07393097349', 'profiles/211794626.jpg', 'London', '16', '2023-08-17 00:00:00', '15:50'),
(168, '169325', 'Inactive', 'Chishamiso', '', 'Tiriwabaye', '1972-09-24', 'Care Worker', 'Female', 'ctiriwabaye@gmail.com ', '92 Lindfield Road', 'NG8 6HQ', '07704082041', 'profiles/user.png', 'Nottingham', '16', '2023-08-17 00:00:00', '16:07'),
(169, '169325', 'Inactive', 'Nwayieze Genevieve', '', 'Nwodo', '1985-02-15', 'Care Worker', 'Female', 'genevieveonyinye@gmail.com', '33 Sedgwick Road Leyton ', 'E10 6QP', '07810341077', 'profiles/825479022.png', 'London', '16', '2023-08-17 00:00:00', '16:24'),
(170, '169325', 'Active', 'Adedoyin', '', 'Oke', '1972-11-22', 'Care Worker', 'Female', 'dhoyeenoke@yahoo.co.uk', '183 Evelyn Court Amhurst Road ', 'E8 2BJ', '07305512326', 'profiles/2116974643.jpg', 'London', '16', '2023-08-17 00:00:00', '16:31'),
(171, '169325', 'Inactive', 'Adelaide', '', 'Ansu Sakaah', '1970-05-07', 'Care Worker', 'Female', 'mamaadelaide83@gmail.com ', '37 primrose avenue ', 'EN2 0SZ', '07943255270', 'profiles/1217519311.jpg', 'Enfield', '16', '2023-08-17 00:00:00', '16:37'),
(172, '169325', 'Inactive', 'Prisca Ezinwanne', '', 'Awurum', '1989-08-17', 'Care Worker', 'Female', 'awurum_prisca@yahoo.com', '230 Middle Park Avenue Eltham London', 'SE9 5SQ', '07466118227', 'profiles/user.png', 'London', '16', '2023-08-21 00:00:00', '09:28'),
(173, '169325', 'Inactive', 'Leyla', '', 'Sharif', '1973-05-05', 'Care Worker', 'Female', 'laylash05@hotmail.com', '20A East Avenue ', 'UB3 2HP', '07528866965', 'profiles/user.png', 'Hayes', '16', '2023-08-22 00:00:00', '10:41'),
(174, '169325', 'Inactive', 'Janet Oluwatosin', '', 'Adeogun', '1979-08-14', 'Care Worker', 'Female', 'oluwatosinadeogun18@gmail.com', '42 Guiness Square Pages Walk', 'SE1 4HH', '07823645493', 'profiles/user.png', 'London', '16', '2023-08-31 00:00:00', '14:44'),
(175, '169325', 'Inactive', 'Dorcas', 'Mitsh', 'Nakabambe', '1967-05-19', 'Care Assistant', 'Female', 'dorcasmitsh@hotmail.co.uk', '117 Roedean Avenue', 'EN3 5QN', '07903 241190', 'profiles/1980485005.jpg', 'Enfield ', '11', '2023-09-15 00:00:00', '12:02'),
(176, '169325', 'Inactive', 'Gifty', '', 'Turkson', '2001-09-15', 'Care Worker', 'Female', 'giftyturksonn@gmail.com', '2 Simpson Close Leagrave Luton', 'LU4 9TP', '07909404889', 'profiles/1173674144.jpg', 'United Kingdom', '16', '2023-10-04 00:00:00', '15:40'),
(177, '169325', 'Inactive', 'Richard ', '', 'Odigiri', '1990-02-25', 'Support Worker', 'Male', 'odigiririchard@gmail.com', '6 Alliance Road', 'SE18 2BA', '07868 095932', 'profiles/1658604025.jpg', 'London', '11', '2023-10-11 00:00:00', '14:18'),
(178, '169325', 'Inactive', 'Titus ', '', 'Marume', '1981-07-07', 'Support Worker', 'Male', 'titusmarume83@gmail.com', '25 Church Lane', 'N9 9PZ', '07944 968401', 'profiles/user.png', 'London', '11', '2023-10-11 00:00:00', '14:19'),
(179, '169325', 'Inactive', 'Edcel ', '', 'Encarnacion', '1996-03-29', 'Support Worker', 'Female', 'edcelencarnacion@gmail.com', '187b Baker Street', 'EN1 3JT', '07881 813525', 'profiles/user.png', 'Enfield ', '11', '2023-10-11 00:00:00', '14:23'),
(180, '169325', 'Inactive', 'Mary ', '', 'Batsa', '1982-11-04', 'Support Worker', 'Female', 'maryblove@ymail.com', '70 Cecil Road, 31 Bole Court', 'EN2 6BY', '07877 834168', 'profiles/1513995338.jpg', 'Enfield', '11', '2023-10-11 00:00:00', '14:25'),
(181, '169325', 'Inactive', 'Rinsola ', '', 'Shosilva', '2004-11-07', 'Support Worker', 'Female', 'aderinsolashosilva@gmail.com', '11 Wells Court, Medhurst Drive', 'BR1 4TF', '07769 816806', 'profiles/1490301345.jpg', 'Bromley', '11', '2023-10-11 00:00:00', '14:31'),
(182, '169325', 'Inactive', 'Valentina', '', 'Manfrin ', '1996-04-02', 'Support Worker', 'Female', 'valentinamanfrin646@yahoo.it', '41 Kitchener Road', 'N17 6DU', '07888 716662', 'profiles/1012357161.pdf', 'London', '11', '2023-10-11 00:00:00', '14:38'),
(183, '169325', 'Inactive', 'Onyekachi', 'Cynthia', 'Iheanacho', '1986-06-17', 'Care Worker', 'Female', 'onyiicynthi2@gmail.com', '96 Faymore Gardens', 'RM15 5NN', '07932072811', 'profiles/468145544.png', 'Essex', '16', '2023-11-07 00:00:00', '11:20'),
(184, '169325', 'Inactive', 'Olufunke', 'Doris', 'Afe', '1978-05-14', 'Care Worker', 'Female', 'dofedoris@yahoo.com', '435 Ley Street', 'IG1 4AD', '07535247990', 'profiles/1028756628.jpg', 'Ilford', '16', '2023-11-07 00:00:00', '11:25'),
(185, '169325', 'Inactive', 'Temitope', 'Josephine', 'Awoyemi', '1994-09-30', 'Care Worker', 'Female', 'Josiepeters94@icloud.com', '71 Butteridges Close', 'RM9 6YD', '07438455323', 'profiles/83548245.jpg', 'Dagenham', '16', '2023-11-07 00:00:00', '11:29'),
(186, '169325', 'Inactive', 'Olayinka ', 'Sakirat', 'Towolawi-Onafeso', '1969-06-02', 'Support Worker', 'Female', 'oreyinka@yahoo.com', '53 Victoria Road', 'RM10 7XL', '07458 697989', 'profiles/914336219.pdf', 'Dagenham', '11', '2023-11-09 00:00:00', '12:42'),
(187, '169325', 'Inactive', 'Cynthia', '', 'Owusu-Ansah', '1973-04-11', 'Support Worker', 'Female', 'koomsonmama1@gmail.com', '49b Northumberland Park', 'N17 0TB', '07932 459413', 'profiles/1974329558.jpg', 'London', '11', '2023-11-09 00:00:00', '12:45'),
(188, '169325', 'Inactive', 'Gideon', 'Baah ', 'Okyere', '1988-02-21', 'Support Worker', 'Male', 'gideon.baah@yahoo.com', '19 Cross Brook Street', 'EN8 8LR', '07492 951884', 'profiles/625596961.jpg', 'Waltham Cross', '11', '2023-11-09 00:00:00', '12:46'),
(189, '169325', 'Inactive', 'Sussana ', 'Mensah', 'Turkson', '1972-02-14', 'Support Worker', 'Female', 'sus_tur@yahoo.co.uk', '30 Paxton Court, Armfield Crescent', 'CR4 2JZ', '07983 560313', 'profiles/user.png', 'Mitcham', '11', '2023-12-06 00:00:00', '13:18'),
(190, '169325', 'Active', 'Bukola ', 'Eunice ', 'Ige', '1978-09-24', 'Support Worker', 'Female', 'bukolaigeeunice@gmail.com', '11 Wells Court, Medhurst Drive', 'BR1 4TF', '07741 912445', 'profiles/166480261.jpg', 'Bromley', '11', '2023-12-06 00:00:00', '13:21'),
(191, '169325', 'Active', 'Abimbola', 'Sadia', 'Suara', '1972-08-24', 'Support Worker', 'Female', 'bimbosuara89@gmail.com', '99 Holstein Way', 'DA18 4DH', '07506 712465', 'profiles/940742345.pdf', 'Kent', '11', '2023-12-06 00:00:00', '13:24'),
(192, '169325', 'Inactive', 'Oluwafunke', 'Oluwatoyin', 'Hokon', '1982-07-25', 'Support Worker', 'Female ', 'oluwafunke.hokon@gmail.com', 'Flat 1, Warehouse Court, No 1 Street', 'SE18 6FB', '07393 975425', 'profiles/126675379.jpg', 'London', '11', '2023-12-06 00:00:00', '13:28'),
(193, '169325', 'Active', 'Winny ', 'Chinonso', 'Oboko', '1981-04-10', 'Support Worker', 'Female', 'winny4rill@gmail.com', '90 Red Lion Lane', 'SE18 4LE', '07393 134152', '6.jpg', 'London', '11', '2024-02-02 00:00:00', '11:26'),
(194, '169325', 'Pending Compliance', 'Esther', 'Datonye', 'George', '2001-08-09', 'Support Worker', 'Female', 'essiegeorge13@gmail.com', '21 Arthur Close', 'SE15 2LP', '07393 576371', 'profiles/Passport Photo.jpg', 'London', '11', '2024-02-02 00:00:00', '11:29'),
(195, '169325', 'Pending Compliance', 'Samnun', 'Wais', 'Chowdhury', '1990-01-01', 'Support Worker', 'Male', 'samnunchy2017@gmail.com', '108 Aldborough Road South', 'IG3 8EY', '07735 810869', 'profiles/Passport Photo.jpg', 'Ilford', '11', '2024-02-02 00:00:00', '11:40'),
(196, '169325', 'Pending Compliance', 'Barakat', 'Damilola', 'Ojomu', '1997-06-06', 'Support Worker', 'Female', 'damilola.ojomu@gmail.com', '69 Addiscombe Court Road', 'CR0 6TT', '07729 877477', 'profiles/Passport Photo.jpg', 'Croydon', '11', '2024-02-02 00:00:00', '11:42'),
(197, '169325', 'Inactive', 'Mercy', '', 'Ivwurie', '1999-03-04', 'Support Worker', 'Female', 'Mercybest22@gmail.com', '5 Radbourne Rd', 'NG2 4BX', '07767562571', '7.jpg', 'Nottingham', '9', '2024-03-04 00:00:00', '17:22'),
(198, '169325', 'Inactive', 'Rukayat ', '', 'Adeleye', '1988-06-24', 'Support Worker', 'Female', 'bukola456@gmail.com', '116 Wellington street', 'LU1 5AF', '07867388811', '2.jpg', 'Luton', '9', '2024-03-04 00:00:00', '17:27'),
(199, '169325', 'Inactive', 'Emediong ', '', 'Udosoh', '1999-03-04', 'Support Worker', 'Female', 'emediongudosoh@gmail.com', '39 EXCELSOIR GARDENS', 'SE13 7PS', '07438282677', '5.jpg', 'LEWISHAM', '9', '2024-03-04 00:00:00', '17:30'),
(200, '169325', 'Inactive', 'Ephraim ', '', 'Kormegah', '1993-09-28', 'Support Worker', 'Male', 'ephraimking6@gmail.com', '59 BURY STREET', 'N9 7JN', '07404047332', '16.jpg', 'EDMONTON', '9', '2024-03-04 00:00:00', '17:34'),
(201, '169325', 'Inactive', 'Gbenga ', '', 'Fayomi', '1987-05-02', 'Support Worker', 'Male', 'Gbenga_fayomi@yahoo.com', 'Gbenga_fayomi@yahoo.com', 'E8 2BD', '07424768062', '11.jpg', 'Hackney', '9', '2024-03-04 00:00:00', '17:41'),
(202, '169325', 'Inactive', 'Sharmin', '', 'Begum', '1999-06-26', 'Support Worker', 'Female', 'sharmin_2@hotmail.co.uk', '34 eaderoad', 'n4 1dh', '07415935484', '5.jpg', 'London', '9', '2024-03-04 00:00:00', '17:47'),
(203, '169325', 'Inactive', 'Annie', '', 'Bitomene', '1968-05-20', 'Support Worker', 'Female', 'annie.bitomene@yahoo.co.uk', '6 PARADE GARDENS ', 'E4 8BJ', '07960789059', '1.jpg', 'London', '9', '2024-03-04 00:00:00', '17:49'),
(204, '169325', 'Inactive', 'Abosede ', '', 'Ashimi', '1978-06-04', 'Support Worker', 'Female', 'abosedeajoke@hotmail.com', 'Flat 17 THEOBALDS COURTS, 40 QUEENS DRIVE ', 'N4 2XG', '07956367367', '6.jpg', 'London', '9', '2024-03-04 00:00:00', '17:53'),
(205, '169325', 'Active', 'Adejoke', '', 'Solarin', '1968-05-13', 'Care Worker', 'Female', 'jksolarin@yahoo.co.uk', '86 Dale Drive', 'UB4 8AU', '07960281260', '3.jpg', 'London', '13', '2024-03-05 00:00:00', '11:09');
INSERT INTO `candidates` (`id`, `app_id`, `status`, `first_name`, `middle_name`, `last_name`, `dob`, `job_title`, `gender`, `email`, `address`, `postcode`, `mobilenumber`, `profile`, `country`, `createdBy`, `createdOn`, `time`) VALUES
(206, '169325', 'Active', 'Nduaya', '', 'Kayoyi', '1969-02-12', 'Care Worker', 'Female', 'kayoyinduaya@hotmail.com', '125 Memorial Avenue ', 'E15 3BS', '07704455993', '4.jpg', 'London', '13', '2024-03-05 00:00:00', '11:15'),
(207, '169325', 'Active', 'Olawale', 'Joseph', 'Ogundipe', '1993-04-21', 'Care Worker', 'Male', 'walegee9@gmail.com', '60 Woodbrook Road', 'SE2 0PA', '07480968721', '14.jpg', 'London', '13', '2024-03-05 00:00:00', '11:18'),
(208, '169325', 'Active', 'Olayinka ', '', 'Oshimade', '1984-01-20', 'Care Worker', 'Female', 'oyindamon@yahoo.com', '99A Goodmayes Lane', 'IG3 9PL', '07916151994', '1.jpg', 'Ilford', '13', '2024-03-05 00:00:00', '11:20'),
(209, '169325', 'Active', 'Patricia', '', 'Allijohn', '1967-10-07', 'Care Worker', 'Female', 'patricialamoth@yahoo.co.uk ', 'Flat 97 Geneva Court, 2 Rookery Way', 'NW9 6GB', '07885550087', '1.jpg', 'London', '13', '2024-03-05 00:00:00', '11:23'),
(210, '169325', 'Active', 'Patricia', '', 'Okosun', '1975-08-10', 'Care Worker', 'Female', 'patriciaokosun22@gmail.com', '14A Studholme Street', 'SE15 1DD', '07960981122', '5.jpg', 'London', '13', '2024-03-05 00:00:00', '11:24'),
(211, '169325', 'Active', 'Temisan', 'Denny', 'Ogidigbeji', '2003-05-08', 'Care Worker', 'Male', 'temidenny@gmail.com', '26 Fedora Way, Houghton Regis', 'LU5 6SZ', '07554088056', '9.jpg', 'Dunstable', '13', '2024-03-05 00:00:00', '11:28'),
(212, '169325', 'Active', 'tracey', '', 'tetteh', '1997-06-11', 'Care Worker', 'Female', 'traceytetteh14@gmail.com', '49 Chandos Street, Netherfield', 'NG4 2LP', '07871930479', '6.jpg', 'Nottingham', '13', '2024-03-05 00:00:00', '11:38'),
(213, '169325', 'Active', 'Nuria ', '', 'Alves Fernandes', '2002-03-17', 'Care Worker', 'Female', 'nuriadeaniela17@outlook.com', '70 albany close', 'N15 3RQ', '07990323337', '4.jpg', 'London', '13', '2024-03-05 00:00:00', '11:41'),
(214, '169325', 'Inactive', 'Adewale', '', 'Adekunle', '1998-04-08', 'Care Worker', 'Male', 'adewhale96@yahoo.com', '12 martello close', 'rm17 6fl', '07769381505', '16.jpg', 'Grays', '13', '2024-03-05 00:00:00', '11:43'),
(215, '169325', 'Inactive', 'Faith', '', 'Eboigbe', '1986-07-23', 'Care Worker', 'Female', 'eboigbe.faith@gmail.com', 'flat 29 charles court, 12-14 park street', 'LU1 3EP', '07393162205', '7.jpg', 'Luton', '13', '2024-03-05 00:00:00', '11:48'),
(216, '169325', 'Inactive', 'Isioma', '', 'Odeleye', '1975-12-19', 'Care Worker', 'Female', 'bettyisi2000@ymail.com', '179 Luton Road', 'LU5 4LP', '07729430691', '1.jpg', 'Bedfordshire', '13', '2024-03-05 00:00:00', '11:51'),
(217, '169325', 'Inactive', 'Emmanuel', '', 'Oswald Boachie ', '1991-03-12', 'Care Worker', 'Male', 'eoswaldboachie33@gmail.com', '33 Greenwood Road', 'CR4 1PF', '07770187514', '10.jpg', 'Mitcham', '13', '2024-03-05 00:00:00', '11:55'),
(218, '169325', 'Inactive', 'Geoffrey', '', 'Njeri', '1976-12-10', 'Care Worker', 'Male', 'seniorhustlergeffg@gmail.com', '56 Mayesbrook Road', 'RM8 2EB', '07592137978', '11.jpg', 'Dagenham', '13', '2024-03-05 00:00:00', '12:01'),
(219, '169325', 'Active', 'Louise', '', 'Kalonji', '1971-03-25', 'Care Worker', 'Female', 'Mirindiokito@hotmail.com ', '159 Bournemouth Park Road, Southend on Sea', 'SS2 5JN', '07491952780', '3.jpg', 'London', '16', '2024-03-14 00:00:00', '16:13'),
(220, '169325', 'Active', 'Aderonke', '', 'Alade', '1971-01-15', 'Support Worker', 'Female', 'ronke.alade@yahoo.com', 'Flat 117a Dickins House, Doddington Grove, Walworth', 'SE17 3SZ', '07498362532', '8.jpg', 'London', '16', '2024-03-14 00:00:00', '16:31'),
(221, '169325', 'Inactive', 'Anita', '', 'Ansah', '1983-05-24', 'Support Worker', 'Female', 'anitaansah40@yahoo.com', '23 Memorial Avenue', 'E15 3BT', '07848794548', '6.jpg', 'London', '16', '2024-03-14 00:00:00', '16:35'),
(222, '169325', 'Inactive', 'Babatunde', '', 'Ismaila', '1979-07-16', 'Support Worker', 'Male', 'ismailababatunde28@gmail.com ', 'Flat 70 Seaton Point, Nolan Way, Clapton ', 'E5 8PZ', '07306059985', '10.jpg', 'London', '16', '2024-03-14 00:00:00', '16:39'),
(223, '169325', 'Inactive', 'Lois', '', 'Antwi', '1991-12-25', 'Support Worker', 'Female', 'loska002@gmail.com', '3 Tanhouse Field, Torriano Avenue', 'NW5 2SX', '07543530495', '7.jpg', 'London', '16', '2024-03-15 00:00:00', '09:43'),
(224, '169325', 'Inactive', 'Martins Iloya', '', 'Ojieabu', '1999-03-15', 'Support Worker', 'Male', 'matolegendry5@gmail.com', '147 Colwyn Road,   United Kingdom', 'NN1 3PU', '07423238156', '15.jpg', 'Northampton', '16', '2024-03-15 00:00:00', '09:47'),
(225, '169325', 'Inactive', 'Mutombo Igonji', '', 'Kasongo', '1968-06-06', 'Support Worker', 'Female', 'kas_mut@hotmail.com', '24 Saint Anns, Barking', 'IG11 7AL', '07988705811', '1.jpg', 'London', '16', '2024-03-15 00:00:00', '10:02'),
(226, '169325', 'Inactive', 'Onyedikachi Philip', '', 'Agu', '1997-12-06', 'Support Worker', 'Male', 'aguphilip1996@gmail.com', '29 Lafitte House, New Orleans Walk', 'N19 3UE', '07379205023', '12.jpg', 'London', '16', '2024-03-15 00:00:00', '10:08'),
(227, '169325', 'Inactive', 'Victoria', '', 'Ablorh', '1985-12-15', 'Support Worker', 'Female', 'victoriaablorh@gmail.com', '4 Avondale Crescent, Enfield, Middlesex', 'EN3 7RX', '07917333816', '4.jpg', 'London', '16', '2024-03-15 00:00:00', '10:12'),
(228, '169325', 'Inactive', 'Marcos Kenju', '', 'Karanja', '2001-02-04', 'Support Worker', 'Male', 'marcoskaranja46@gmail.com', 'Flat 15 Block G, Rodney Road, Walworth', 'SE17 1BU', '07482429033', '12.jpg', 'London', '16', '2024-03-15 00:00:00', '10:18'),
(229, '169325', 'Inactive', 'Joseph', '', 'Karanja', '2002-04-18', 'Support Worker', 'Male', 'pjboyoc001@gmail.com', 'Flat 15 Block G, Rodney Road, Walworth', 'SE17 1BU', '07473304246', '16.jpg', 'London', '16', '2024-03-15 00:00:00', '10:22'),
(230, '169325', 'Inactive', 'Oluwakemi', '', 'Olanrewaju', '1988-11-16', 'Support Worker', 'Male', 'kemmystrie@gmail.com', 'Flat 10 Richmond Moore Court, 699 Rainham Road South, Dagenham', 'RM10 8BF', '07983091370', '16.jpg', 'London', '16', '2024-03-15 00:00:00', '10:37'),
(231, '169325', 'Inactive', 'Vera', '', 'Ako', '1982-02-02', 'Support Worker', 'Female', 'veraako82@gmail.com', '7 Gliddon Drive, Clapton', 'E5 8LA', '07823784616', '6.jpg', 'London', '16', '2024-03-15 00:00:00', '10:41'),
(232, '169325', 'Inactive', 'Endurance Henry', '', 'Omonua Ogudu', '1973-08-22', 'Support Worker', 'Female', 'Rinconventure@gmail.com', '104 Port Arthur Road', 'NG2 4GE', '07751608206', '6.jpg', 'Nottingham', '16', '2024-03-15 00:00:00', '10:51'),
(233, '169325', 'Active', 'Lilian ', '', 'Onwumere', '1981-12-04', 'Care Worker', 'Female', 'vsonwumere@gmail.com ', '33 Tanners End Lane ', 'N18 1PG', '07727261085', '8.jpg', 'London', '13', '2024-07-08 00:00:00', '14:50'),
(234, '169325', 'Active', 'Great', '', 'Nnanna', '1999-07-08', 'Care Worker', 'Male', 'great.nnanna@outlook.com', '144 Temple Avenue', 'RM8 1NB', '07576767353', '13.jpg', 'London', '13', '2024-07-08 00:00:00', '14:52'),
(235, '169325', 'Inactive', 'Lami', 'Bunmi', 'Lawal', '1965-05-05', 'Care Worker', 'Female', 'bunmilawal55@yahoo.com', '15 Therfield Court Brownswood Road', 'N4 2XL', '07904219118', '3.jpg', 'London', '13', '2024-07-08 00:00:00', '14:54'),
(236, '169325', 'Inactive', 'Comfort ', 'Abosede', 'Olayemi', '1980-06-15', 'Care Worker', 'Female', 'boysse88@gmail.com', '79 Falconers Road', 'LU2 9ET', '07950491719', '5.jpg', 'Luton', '13', '2024-07-08 00:00:00', '14:56'),
(237, '169325', 'Inactive', 'Akachukwu', 'Miracle', 'Okorie', '1986-10-31', 'Care Worker', 'Male', 'Mokorie47@gmail.com', 'Flat 20 Nelson Mandela House', 'N16 6AJ', '07495979916', '16.jpg', 'London', '13', '2024-07-08 00:00:00', '14:58'),
(238, '169325', 'Inactive', 'Adekoya', 'Olajumoke ', 'Adedayo', '1984-11-22', 'Care Worker', 'Female', 'olajumokeadedayo8@gmail.com', '62 Durell Road', 'RM9 5XU', '07460410293', '3.jpg', 'London', '13', '2024-07-08 00:00:00', '15:03'),
(239, '169325', 'Inactive', 'Asha', 'Said', 'Ahmed', '1964-07-01', 'Care Worker', 'Female', 'ladan09@hotmail.com', 'Flat 4, Cleaver House Adelaide Road', 'NW3 3PT', '07951281287', '2.jpg', 'London', '13', '2024-07-08 00:00:00', '15:05'),
(240, '169325', 'Inactive', 'Adewale', 'Stephen', 'Akinde', '1976-06-12', 'Care Worker', 'Male', 'stephenakinde@yahoo.com ', 'Flat 4 Burke Lodge, 122 Balaam St', 'E13 8RE', '07948874447', '16.jpg', 'London', '13', '2024-07-08 00:00:00', '15:08'),
(241, '169325', 'Active', 'Dorcas', '', 'Ileramah', '1988-09-12', 'Care Worker', 'Female', 'dorcasileramah@gmail.com', '14 Rosebank Walk', 'NW1 9YA', '07459746420', '4.jpg', 'London', '13', '2024-07-08 00:00:00', '15:10'),
(242, '169325', 'Inactive', 'Emmanuel', '', 'Menjoh', '1982-12-25', 'Care Worker', 'Male', 'Princeemmanuel1344@gmail.com', '42 Latimer Close', 'NG6 9AS', '07438765381', '14.jpg', 'Nottingham', '13', '2024-07-08 00:00:00', '15:25'),
(243, '169325', 'Active', 'Adeola', '', 'Agboola', '1997-11-08', 'Care Worker', 'Female', 'agboolaadeola07@gmail.com', 'Flat 1 Dawson Apartments, 9 Kings Hill', 'IG11 0ZR', '07361882034', '7.jpg', 'London ', '13', '2024-07-08 00:00:00', '15:29'),
(244, '169325', 'Inactive', 'Timileyin ', '', 'Akinpade', '1995-04-20', 'Care Worker', 'Female', 'akinpadeoreoluwa95@gmail.com', '12 Glenview', 'SE2 0SD', '07440758282', '8.jpg', 'London', '13', '2024-07-08 00:00:00', '15:32');

-- --------------------------------------------------------

--
-- Table structure for table `candidatesfiles`
--

CREATE TABLE `candidatesfiles` (
  `id` int(11) NOT NULL,
  `app_id` text NOT NULL,
  `candidateid` text NOT NULL,
  `name` text NOT NULL,
  `type` text NOT NULL,
  `file` text NOT NULL,
  `notes` text NOT NULL,
  `expirydate` text NOT NULL,
  `createdOn` text NOT NULL,
  `time` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `candidatesfiles`
--

INSERT INTO `candidatesfiles` (`id`, `app_id`, `candidateid`, `name`, `type`, `file`, `notes`, `expirydate`, `createdOn`, `time`) VALUES
(3, '169325', '33', 'BRP', 'RTW Documents', 'files/1685718601.pdf', '', '2024-11-30', '2nd June 2023', ''),
(4, '169325', '33', 'DBS + Update Service', 'DBS Documents', 'files/1685723759.pdf', '', '2024-02-02', '2nd June 2023', ''),
(5, '169325', '33', 'POAx2', 'Proof of residence', 'files/1685724314.pdf', '', '', '2nd June 2023', ''),
(7, '169325', '2', 'BRP', 'RTW Documents', 'files/1685970160.pdf', '', '2024-12-26', '5th June 2023', ''),
(8, '169325', '2', 'POAx2', 'Proof of residence', 'files/1685970393.pdf', '', '', '5th June 2023', ''),
(9, '169325', '2', 'Manual Handling', 'Mandatory Training', 'files/1685970561.pdf', '', '2023-12-17', '5th June 2023', ''),
(10, '169325', '2', 'Fire Safety', 'Mandatory Training', 'files/1685970842.pdf', '', '2023-12-17', '5th June 2023', ''),
(11, '169325', '2', 'Infection Prevention & Control', 'Mandatory Training', 'files/1685971011.pdf', '', '2023-12-16', '5th June 2023', ''),
(14, '169325', '2', 'Medication', 'Mandatory Training', 'files/1685971718.pdf', '', '2023-12-10', '5th June 2023', ''),
(15, '169325', '2', 'First Aid', 'Mandatory Training', 'files/1685971939.pdf', '', '2023-12-10', '5th June 2023', ''),
(16, '169325', '2', 'Food Hygiene', 'Mandatory Training', 'files/1685972030.pdf', '', '2023-12-03', '5th June 2023', ''),
(18, '169325', '1', 'Mandatory Training', 'Mandatory Training', 'files/1686046253.pdf', '', '2024-01-18', '6th June 2023', ''),
(19, '169325', '1', 'POAx2', 'Proof of residence', 'files/1686046342.pdf', '', '', '6th June 2023', ''),
(20, '169325', '4', 'EU ID', 'RTW Documents', 'files/1686046682.pdf', '', '2026-05-19', '6th June 2023', ''),
(21, '169325', '4', 'DBS - NRS', 'DBS Documents', 'files/1686046768.pdf', '', '2024-01-28', '6th June 2023', ''),
(22, '169325', '4', 'Manual Handling', 'Mandatory Training', 'files/1686046982.pdf', '', '2023-11-22', '6th June 2023', ''),
(23, '169325', '6', 'BRP', 'RTW Documents', 'files/1686047231.pdf', '', '2023-09-09', '6th June 2023', ''),
(24, '169325', '6', 'DBS - NRS', 'DBS Documents', 'files/1686047358.pdf', '', '2023-12-14', '6th June 2023', ''),
(25, '169325', '6', 'POAx2', 'Proof of residence', 'files/1686047430.pdf', '', '', '6th June 2023', ''),
(26, '169325', '6', 'Mandatory Training', 'Mandatory Training', 'files/1686047555.pdf', '', '2023-12-12', '6th June 2023', ''),
(27, '169325', '6', 'NRS Application Form', 'Application Documents', 'files/1686047887.pdf', '', '', '6th June 2023', ''),
(28, '169325', '6', 'NRS Health_Questionnaire', 'Application Documents', 'files/1686047942.pdf', '', '', '6th June 2023', ''),
(29, '169325', '6', 'NRS CV', 'CV Documents', 'files/1686048162.pdf', '', '', '6th June 2023', ''),
(30, '169325', '6', 'NRS CV', 'CV Documents', 'files/1686048165.pdf', '', '', '6th June 2023', ''),
(31, '169325', '7', 'Passport', 'RTW Documents', 'files/1686048654.pdf', '', '20230-08-23', '6th June 2023', ''),
(32, '169325', '7', 'Mandatory Training', 'Mandatory Training', 'files/1686049047.pdf', '', '2024-03-04', '6th June 2023', ''),
(33, '169325', '7', 'Application Form', 'Application Documents', 'files/1686049092.pdf', '', '', '6th June 2023', ''),
(34, '169325', '7', 'NRS CV', 'CV Documents', 'files/1686049188.pdf', '', '', '6th June 2023', ''),
(35, '169325', '8', 'NRS Application Form', 'Application Documents', 'files/1686049370.pdf', '', '', '6th June 2023', ''),
(36, '169325', '8', 'BRP', 'RTW Documents', 'files/1686049484.pdf', '', '2024-11-21', '6th June 2023', ''),
(38, '169325', '8', 'POAx2', 'Proof of residence', 'files/1686049707.pdf', '', '', '6th June 2023', ''),
(40, '169325', '8', 'NRS CV', 'CV Documents', 'files/1686049957.pdf', '', '', '6th June 2023', ''),
(43, '169325', '9', 'NRS CV', 'CV Documents', 'files/1686050550.pdf', '', '', '6th June 2023', ''),
(44, '169325', '9', 'Application Form', 'Application Documents', 'files/1686050615.pdf', '', '', '6th June 2023', ''),
(45, '169325', '9', 'Safeguarding Vulnerable Adults', 'Mandatory Training', 'files/1686050757.pdf', '', '2023-12-01', '6th June 2023', ''),
(46, '169325', '9', 'Mandatory Training', 'Mandatory Training', 'files/1686050847.pdf', '', '2024-03-03', '6th June 2023', ''),
(47, '169325', '10', 'NRS Application Form', 'Application Documents', 'files/1686051504.pdf', '', '', '6th June 2023', ''),
(48, '169325', '10', 'EU -Passport', 'RTW Documents', 'files/1686051684.pdf', '', '2025-05-03', '6th June 2023', ''),
(50, '169325', '10', 'DBS + Update Service', 'DBS Documents', 'files/1686051861.pdf', '', '2023-09-25', '6th June 2023', ''),
(51, '169325', '10', 'POAx2', 'Proof of residence', 'files/1686051902.pdf', '', '', '6th June 2023', ''),
(53, '169325', '10', 'NRS CV', 'CV Documents', 'files/1686052162.pdf', '', '', '6th June 2023', ''),
(54, '169325', '7', 'DBS - NRS', 'DBS Documents', 'files/1686052595.pdf', '', '2023-12-09', '6th June 2023', ''),
(55, '169325', '2', 'Application Form', 'Application Documents', 'files/1686142543.pdf', '', '', '7th June 2023', ''),
(56, '169325', '2', 'NRS CV', 'CV Documents', 'files/1686142597.pdf', '', '', '7th June 2023', ''),
(57, '169325', '4', 'Application Form', 'Application Documents', 'files/1686142876.pdf', '', '', '7th June 2023', ''),
(58, '169325', '4', 'NRS CV', 'CV Documents', 'files/1686143032.pdf', '', '', '7th June 2023', ''),
(59, '169325', '11', 'Application Form', 'Application Documents', 'files/1686143760.pdf', '', '', '7th June 2023', ''),
(60, '169325', '11', 'NRS CV', 'CV Documents', 'files/1686143872.pdf', '', '', '7th June 2023', ''),
(61, '169325', '11', 'Passport', 'RTW Documents', 'files/1686144160.pdf', '', '2029-07-23', '7th June 2023', ''),
(63, '169325', '11', 'POAx2', 'Proof of residence', 'files/1686144469.pdf', '', '', '7th June 2023', ''),
(64, '169325', '11', 'Mandatory Training', 'Mandatory Training', 'files/1686144536.pdf', '', '2024-02-12', '7th June 2023', ''),
(65, '169325', '13', 'NRS Application Form', 'Application Documents', 'files/1686144902.pdf', '', '', '7th June 2023', ''),
(66, '169325', '13', 'NRS CV', 'CV Documents', 'files/1686145015.pdf', '', '', '7th June 2023', ''),
(67, '169325', '13', 'Passport', 'RTW Documents', 'files/1686145165.pdf', '', '2023-06-20', '7th June 2023', ''),
(68, '169325', '13', 'DBS - NRS', 'DBS Documents', 'files/1686145308.pdf', '', '2023-09-27', '7th June 2023', ''),
(69, '169325', '13', 'POAx2', 'Proof of residence', 'files/1686145440.pdf', '', '', '7th June 2023', ''),
(70, '169325', '15', 'NRS Application Form', 'Application Documents', 'files/1686145628.pdf', '', '', '7th June 2023', ''),
(71, '169325', '15', 'NRS Health_Questionnaire', 'Application Documents', 'files/1686145661.pdf', '', '', '7th June 2023', ''),
(72, '169325', '15', 'NRS CV', 'CV Documents', 'files/1686145802.pdf', '', '', '7th June 2023', ''),
(74, '169325', '15', 'POAx2', 'Proof of residence', 'files/1686145991.pdf', '', '', '7th June 2023', ''),
(75, '169325', '15', 'Mandatory Training', 'Mandatory Training', 'files/1686146079.pdf', '', '2024-04-20', '7th June 2023', ''),
(76, '169325', '16', 'NRS Application Form', 'Application Documents', 'files/1686146345.pdf', '', '', '7th June 2023', ''),
(77, '169325', '16', 'NRS CV', 'CV Documents', 'files/1686146517.pdf', '', '', '7th June 2023', ''),
(78, '169325', '16', 'DBS - NRS', 'DBS Documents', 'files/1686146669.pdf', '', '2023-09-15', '7th June 2023', ''),
(80, '169325', '15', 'Passport', 'RTW Documents', 'files/1686227838.pdf', '', '2030-06-01', '8th June 2023', ''),
(81, '169325', '16', 'Passport', 'RTW Documents', 'files/1686228216.pdf', '', '2030-06-26', '8th June 2023', ''),
(82, '169325', '16', 'POAx2', 'Proof of residence', 'files/1686228277.pdf', '', '', '8th June 2023', ''),
(83, '169325', '16', 'Mandatory Training', 'Mandatory Training', 'files/1686228413.pdf', '', '2023-09-14', '8th June 2023', ''),
(84, '169325', '17', 'NRS Application Form', 'Application Documents', 'files/1686228620.pdf', '', '', '8th June 2023', ''),
(85, '169325', '17', 'NRS CV', 'CV Documents', 'files/1686228791.pdf', '', '', '8th June 2023', ''),
(86, '169325', '17', 'Passport', 'RTW Documents', 'files/1686229050.pdf', '', '2029-12-28', '8th June 2023', ''),
(87, '169325', '17', 'DBS + Update Service', 'DBS Documents', 'files/1686229224.pdf', '', '2024-02-17', '8th June 2023', ''),
(88, '169325', '17', 'POAx2', 'Proof of residence', 'files/1686229338.pdf', '', '', '8th June 2023', ''),
(89, '169325', '17', 'Mandatory Training', 'Mandatory Training', 'files/1686229462.pdf', '', '2024-03-23', '8th June 2023', ''),
(90, '169325', '18', 'NRS Application Form', 'Application Documents', 'files/1686229714.pdf', '', '', '8th June 2023', ''),
(91, '169325', '18', 'NRS CV', 'CV Documents', 'files/1686229865.pdf', '', '', '8th June 2023', ''),
(92, '169325', '18', 'BRP', 'RTW Documents', 'files/1686230034.pdf', '', '2024-05-12', '8th June 2023', ''),
(93, '169325', '18', 'DBS + Update Service', 'DBS Documents', 'files/1686230197.pdf', '', '2023-09-23', '8th June 2023', ''),
(94, '169325', '18', 'POAx2', 'Proof of residence', 'files/1686230265.pdf', '', '', '8th June 2023', ''),
(95, '169325', '18', 'Mandatory Training', 'Mandatory Training', 'files/1686230434.pdf', '', '2024-04-13', '8th June 2023', ''),
(96, '169325', '18', 'Fire Safety', 'Mandatory Training', 'files/1686230548.pdf', '', '2023-12-16', '8th June 2023', ''),
(97, '169325', '18', 'Infection Prevention & Control', 'Mandatory Training', 'files/1686230619.pdf', '', '2023-12-14', '8th June 2023', ''),
(98, '169325', '19', 'NRS Application Form', 'Application Documents', 'files/1686230785.pdf', '', '', '8th June 2023', ''),
(99, '169325', '19', 'NRS CV', 'CV Documents', 'files/1686231232.pdf', '', '', '8th June 2023', ''),
(100, '169325', '19', 'Passport', 'RTW Documents', 'files/1686231300.pdf', '', '2027-09-20', '8th June 2023', ''),
(101, '169325', '19', 'POAx2', 'Proof of residence', 'files/1686231339.pdf', '', '', '8th June 2023', ''),
(102, '169325', '19', 'DBS + Update Service', 'DBS Documents', 'files/1686231429.pdf', '', '2024-02-03', '8th June 2023', ''),
(103, '169325', '19', 'Mandatory Training', 'Mandatory Training', 'files/1686231519.pdf', '', '2024-03-03', '8th June 2023', ''),
(104, '169325', '2', 'DBS - NRS', 'DBS Documents', 'files/1686306140.pdf', '', '2023-12-16', '9th June 2023', ''),
(105, '169325', '21', 'NRS Application Form', 'Application Documents', 'files/1686307516.pdf', '', '', '9th June 2023', ''),
(106, '169325', '21', 'NRS CV', 'CV Documents', 'files/1686307547.pdf', '', '', '9th June 2023', ''),
(107, '169325', '21', 'Passport', 'RTW Documents', 'files/1686307651.pdf', '', '2026-05-10', '9th June 2023', ''),
(109, '169325', '21', 'POAx2', 'Proof of residence', 'files/1686307859.pdf', '', '', '9th June 2023', ''),
(110, '169325', '21', 'Mandatory Training', 'Mandatory Training', 'files/1686307923.pdf', '', '2023-10-14', '9th June 2023', ''),
(111, '169325', '22', 'NRS Application Form', 'Application Documents', 'files/1686308290.pdf', '', '', '9th June 2023', ''),
(112, '169325', '22', 'NRS CV', 'CV Documents', 'files/1686308336.pdf', '', '', '9th June 2023', ''),
(113, '169325', '22', 'BRP', 'RTW Documents', 'files/1686308468.pdf', '', '2024-11-30', '9th June 2023', ''),
(115, '169325', '22', 'POAx2', 'Proof of residence', 'files/1686308646.pdf', '', '', '9th June 2023', ''),
(116, '169325', '22', 'Mandatory Training', 'Mandatory Training', 'files/1686308730.pdf', '', '2024-02-23', '9th June 2023', ''),
(117, '169325', '23', 'NRS Application Form', 'Application Documents', 'files/1686309954.pdf', '', '', '9th June 2023', ''),
(118, '169325', '23', 'NRS CV', 'CV Documents', 'files/1686310050.pdf', '', '', '9th June 2023', ''),
(119, '169325', '23', 'Passport', 'RTW Documents', 'files/1686310202.pdf', '', '2024-05-14', '9th June 2023', ''),
(120, '169325', '23', 'DBS + Update Service', 'DBS Documents', 'files/1686310295.pdf', '', '2024-04-11', '9th June 2023', ''),
(121, '169325', '23', 'POAx2', 'Proof of residence', 'files/1686310366.pdf', '', '', '9th June 2023', ''),
(122, '169325', '23', 'Mandatory Training', 'Mandatory Training', 'files/1686310439.pdf', '', '2024-05-05', '9th June 2023', ''),
(123, '169325', '24', 'NRS Application Form', 'Application Documents', 'files/1686310925.pdf', '', '', '9th June 2023', ''),
(124, '169325', '24', 'NRS CV', 'CV Documents', 'files/1686310976.pdf', '', '', '9th June 2023', ''),
(125, '169325', '24', 'BRP', 'RTW Documents', 'files/1686311157.pdf', '', '2024-11-30', '9th June 2023', ''),
(126, '169325', '24', 'Adult -DBS', 'DBS Documents', 'files/1686311252.pdf', '', '2024-02-17', '9th June 2023', ''),
(127, '169325', '24', 'POAx2', 'Proof of residence', 'files/1686311319.pdf', '', '', '9th June 2023', ''),
(128, '169325', '24', 'Mandatory Training', 'Mandatory Training', 'files/1686311440.pdf', '', '2024-03-18', '9th June 2023', ''),
(129, '169325', '25', 'NRS Application Form', 'Application Documents', 'files/1686311868.pdf', '', '', '9th June 2023', ''),
(130, '169325', '25', 'NRS CV', 'CV Documents', 'files/1686311915.pdf', '', '', '9th June 2023', ''),
(131, '169325', '25', 'BRP', 'RTW Documents', 'files/1686312042.pdf', '', '2024-11-30', '9th June 2023', ''),
(132, '169325', '25', 'POAx2', 'Proof of residence', 'files/1686312746.pdf', '', '', '9th June 2023', ''),
(134, '169325', '26', 'NRS Application Form', 'Application Documents', 'files/1686313208.pdf', '', '', '9th June 2023', ''),
(135, '169325', '26', 'NRS CV', 'CV Documents', 'files/1686313268.pdf', '', '', '9th June 2023', ''),
(136, '169325', '26', 'BRP', 'RTW Documents', 'files/1686313337.pdf', '', '2023-11-11', '9th June 2023', ''),
(137, '169325', '26', 'DBS + Update Service', 'DBS Documents', 'files/1686319884.pdf', '', '2024-03-19', '9th June 2023', ''),
(138, '169325', '26', 'POAx2', 'Proof of residence', 'files/1686320144.pdf', '', '', '9th June 2023', ''),
(139, '169325', '26', 'Mandatory Training', 'Mandatory Training', 'files/1686320789.pdf', '', '2024-02-11', '9th June 2023', ''),
(140, '169325', '27', 'NRS Application Form', 'Application Documents', 'files/1686323429.pdf', '', '', '9th June 2023', ''),
(141, '169325', '27', 'NRS CV', 'CV Documents', 'files/1686332308.pdf', '', '', '9th June 2023', ''),
(142, '169325', '27', 'Passport', 'RTW Documents', 'files/1686332410.pdf', '', '2024-07-28', '9th June 2023', ''),
(144, '169325', '27', 'POAx2', 'Proof of residence', 'files/1686332792.pdf', '', '', '9th June 2023', ''),
(146, '169325', '28', 'NRS Application Form', 'Application Documents', 'files/1686333153.pdf', '', '', '9th June 2023', ''),
(147, '169325', '28', 'NRS CV', 'CV Documents', 'files/1686333374.pdf', '', '', '9th June 2023', ''),
(148, '169325', '28', 'BRP', 'RTW Documents', 'files/1686333467.pdf', '', '2024-11-30', '9th June 2023', ''),
(149, '169325', '28', 'POA 1', 'Proof of residence', 'files/1686333527.pdf', '', '', '9th June 2023', ''),
(150, '169325', '28', 'Mandatory Training', 'Mandatory Training', 'files/1686333617.pdf', '', '2023-09-13', '9th June 2023', ''),
(151, '169325', '28', 'DBS + Update Service', 'DBS Documents', 'files/1686333724.pdf', '', '2023-09-11', '9th June 2023', ''),
(152, '169325', '29', 'NRS Application Form', 'Application Documents', 'files/1686334170.pdf', '', '', '9th June 2023', ''),
(153, '169325', '29', 'BRP', 'RTW Documents', 'files/1686334267.pdf', '', '2024-11-30', '9th June 2023', ''),
(154, '169325', '29', 'DBS + Update Service', 'DBS Documents', 'files/1686334374.pdf', '', '2023-11-23', '9th June 2023', ''),
(155, '169325', '29', 'POAx2', 'Proof of residence', 'files/1686334441.pdf', '', '', '9th June 2023', ''),
(156, '169325', '29', 'Fire Safety', 'Mandatory Training', 'files/1686334613.pdf', '', '2023-11-30', '9th June 2023', ''),
(157, '169325', '29', 'Health & Safety', 'Mandatory Training', 'files/1686334780.pdf', '', '2023-11-30', '9th June 2023', ''),
(158, '169325', '29', 'Food Hygiene', 'Mandatory Training', 'files/1686334882.pdf', '', '2023-11-30', '9th June 2023', ''),
(159, '169325', '29', 'Manual Handling', 'Mandatory Training', 'files/1686335041.pdf', '', '2023-11-30', '9th June 2023', ''),
(160, '169325', '29', 'Safeguarding Children', 'Mandatory Training', 'files/1686335103.pdf', '', '2023-11-30', '9th June 2023', ''),
(161, '169325', '29', 'First Aid', 'Mandatory Training', 'files/1686335180.pdf', '', '2023-11-30', '9th June 2023', ''),
(162, '169325', '30', 'NRS Application Form', 'Application Documents', 'files/1686335971.pdf', '', '', '9th June 2023', ''),
(163, '169325', '30', 'NRS CV', 'CV Documents', 'files/1686336019.pdf', '', '', '9th June 2023', ''),
(164, '169325', '30', 'BRP', 'RTW Documents', 'files/1686336229.pdf', '', '2026-02-02', '9th June 2023', ''),
(165, '169325', '30', 'DBS - NRS', 'DBS Documents', 'files/1686336406.pdf', '', '2023-12-21', '9th June 2023', ''),
(166, '169325', '30', 'POAx2', 'Proof of residence', 'files/1686336451.pdf', '', '', '9th June 2023', ''),
(168, '169325', '30', 'Mandatory Training', 'Mandatory Training', 'files/1686336700.pdf', '', '2024-02-02', '9th June 2023', ''),
(169, '169325', '31', 'NRS Application Form', 'Application Documents', 'files/1686337346.pdf', '', '', '9th June 2023', ''),
(170, '169325', '31', 'NRS CV', 'CV Documents', 'files/1686337416.pdf', '', '', '9th June 2023', ''),
(171, '169325', '31', 'BRP', 'RTW Documents', 'files/1686337556.pdf', '', '2024-09-21', '9th June 2023', ''),
(173, '169325', '31', 'POAx2', 'Proof of residence', 'files/1686337746.pdf', '', '', '9th June 2023', ''),
(175, '169325', '31', 'Infection Prevention & Control', 'Mandatory Training', 'files/1686338022.pdf', '', '2024-04-08', '9th June 2023', ''),
(176, '169325', '31', 'First Aid', 'Mandatory Training', 'files/1686338135.pdf', '', '2024-02-04', '9th June 2023', ''),
(177, '169325', '31', 'Safeguarding Children', 'Mandatory Training', 'files/1686338243.pdf', '', '2024-02-09', '9th June 2023', ''),
(179, '169325', '31', 'Fire Safety', 'Mandatory Training', 'files/1686338417.pdf', '', '2024-02-13', '9th June 2023', ''),
(180, '169325', '31', 'Medication', 'Mandatory Training', 'files/1686338540.pdf', '', '2024-02-14', '9th June 2023', ''),
(181, '169325', '31', 'Manual Handling', 'Mandatory Training', 'files/1686338627.pdf', '', '2024-02-03', '9th June 2023', ''),
(182, '169325', '32', 'NRS Application Form', 'Application Documents', 'files/1686563669.pdf', '', '', '12th June 2023', ''),
(183, '169325', '32', 'NRS CV', 'CV Documents', 'files/1686563789.pdf', '', '', '12th June 2023', ''),
(184, '169325', '32', 'BRP', 'RTW Documents', 'files/1686563866.pdf', '', '2029-09-24', '12th June 2023', ''),
(185, '169325', '32', 'DBS + Update Service', 'DBS Documents', 'files/1686563945.pdf', '', '2023-09-09', '12th June 2023', ''),
(186, '169325', '32', 'POAx2', 'Proof of residence', 'files/1686563994.pdf', '', '', '12th June 2023', ''),
(188, '169325', '33', 'NRS Application Form', 'Application Documents', 'files/1686564370.pdf', '', '', '12th June 2023', ''),
(189, '169325', '33', 'NRS CV', 'CV Documents', 'files/1686564764.pdf', '', '', '12th June 2023', ''),
(190, '169325', '33', 'Fire Safety', 'Mandatory Training', 'files/1686564873.pdf', '', '2024-02-04', '12th June 2023', ''),
(191, '169325', '33', 'First Aid', 'Mandatory Training', 'files/1686564939.pdf', '', '2024-02-01', '12th June 2023', ''),
(192, '169325', '33', 'Health & Safety', 'Mandatory Training', 'files/1686565013.pdf', '', '2024-01-27', '12th June 2023', ''),
(193, '169325', '33', 'Manual Handling', 'Mandatory Training', 'files/1686565093.pdf', '', '2024-01-26', '12th June 2023', ''),
(194, '169325', '33', 'Safeguarding Vulnerable Adults', 'Mandatory Training', 'files/1686565162.pdf', '', '2024-03-07', '12th June 2023', ''),
(195, '169325', '33', 'Infection Prevention & Control', 'Mandatory Training', 'files/1686565221.pdf', '', '2024-02-07', '12th June 2023', ''),
(196, '169325', '35', 'NRS Application Form', 'Application Documents', 'files/1686565770.pdf', '', '', '12th June 2023', ''),
(197, '169325', '35', 'BRP', 'RTW Documents', 'files/1686565941.pdf', '', '2023-09-30', '12th June 2023', ''),
(199, '169325', '35', 'POAx2', 'Proof of residence', 'files/1686566069.pdf', '', '', '12th June 2023', ''),
(201, '169325', '32', 'Mandatory Training', 'Mandatory Training', 'files/1686566474.pdf', '', '2024-04-11', '12th June 2023', ''),
(203, '169325', '35', 'NRS CV', 'CV Documents', 'files/1686566782.pdf', '', '', '12th June 2023', ''),
(204, '169325', '36', 'NRS Application Form', 'Application Documents', 'files/1686566881.pdf', '', '', '12th June 2023', ''),
(205, '169325', '36', 'NRS CV', 'CV Documents', 'files/1686567005.pdf', '', '', '12th June 2023', ''),
(206, '169325', '36', 'DBS - NRS', 'DBS Documents', 'files/1686567072.pdf', '', '2024-04-17', '12th June 2023', ''),
(207, '169325', '36', 'POAx2', 'Proof of residence', 'files/1686567116.pdf', '', '', '12th June 2023', ''),
(208, '169325', '36', 'Mandatory Training', 'Mandatory Training', 'files/1686567319.pdf', '', '2024-02-05', '12th June 2023', ''),
(209, '169325', '37', 'NRS Application Form', 'Application Documents', 'files/1686567598.pdf', '', '', '12th June 2023', ''),
(210, '169325', '37', 'BRP', 'RTW Documents', 'files/1686567721.pdf', '', '2023-09-30', '12th June 2023', ''),
(211, '169325', '37', 'POAx2', 'Proof of residence', 'files/1686567771.pdf', '', '', '12th June 2023', ''),
(212, '169325', '37', 'Mandatory Training', 'Mandatory Training', 'files/1686567823.pdf', '', '2024-04-23', '12th June 2023', ''),
(213, '169325', '37', 'DBS + Update Service', 'DBS Documents', 'files/1686567902.pdf', '', '2024-03-13', '12th June 2023', ''),
(214, '169325', '38', 'NRS Application Form', 'Application Documents', 'files/1686568124.pdf', '', '', '12th June 2023', ''),
(215, '169325', '38', 'NRS CV', 'CV Documents', 'files/1686568194.pdf', '', '', '12th June 2023', ''),
(217, '169325', '38', 'Passport', 'RTW Documents', 'files/1686568386.pdf', '', '2029-03-09', '12th June 2023', ''),
(218, '169325', '38', 'DBS - NRS', 'DBS Documents', 'files/1686568604.pdf', '', '2023-11-15', '12th June 2023', ''),
(219, '169325', '38', 'POAx2', 'Proof of residence', 'files/1686568664.pdf', '', '', '12th June 2023', ''),
(220, '169325', '38', 'Mandatory Training', 'Mandatory Training', 'files/1686568771.pdf', '', '2023-11-22', '12th June 2023', ''),
(221, '169325', '39', 'NRS Application Form', 'Application Documents', 'files/1686568984.pdf', '', '', '12th June 2023', ''),
(222, '169325', '39', 'BRP', 'RTW Documents', 'files/1686569107.pdf', '', '2024-11-30', '12th June 2023', ''),
(223, '169325', '39', 'DBS + Update Service', 'DBS Documents', 'files/1686569193.pdf', '', '2024-02-02', '12th June 2023', ''),
(224, '169325', '39', 'POA 1', 'Proof of residence', 'files/1686569234.pdf', '', '', '12th June 2023', ''),
(225, '169325', '39', 'Mandatory Training', 'Mandatory Training', 'files/1686569403.pdf', '', '2023-08-01', '12th June 2023', ''),
(226, '169325', '40', 'Application Form', 'Application Documents', 'files/1686570657.pdf', '', '', '12th June 2023', ''),
(227, '169325', '40', 'BRP', 'RTW Documents', 'files/1686571084.pdf', 'Student Visa', '2023-09-30', '12th June 2023', ''),
(228, '169325', '40', 'DBS + Update Service', 'DBS Documents', 'files/1686571261.pdf', '', '2023-11-07', '12th June 2023', ''),
(229, '169325', '40', 'POAx2', 'Proof of residence', 'files/1686571348.pdf', '', '', '12th June 2023', ''),
(230, '169325', '40', 'First Aid', 'Mandatory Training', 'files/1686571559.pdf', '', '2023-12-27', '12th June 2023', ''),
(231, '169325', '40', 'Fire Safety', 'Mandatory Training', 'files/1686571601.pdf', '', '2024-01-01', '12th June 2023', ''),
(232, '169325', '40', 'Food Hygiene', 'Mandatory Training', 'files/1686571660.pdf', '', '2023-12-31', '12th June 2023', ''),
(233, '169325', '40', 'Health & Safety', 'Mandatory Training', 'files/1686571733.pdf', '', '2024-01-02', '12th June 2023', ''),
(234, '169325', '40', 'Infection Prevention & Control', 'Mandatory Training', 'files/1686571836.pdf', '', '2023-12-29', '12th June 2023', ''),
(235, '169325', '40', 'Medication', 'Mandatory Training', 'files/1686571899.pdf', '', '2024-01-02', '12th June 2023', ''),
(236, '169325', '40', 'Health & Safety', 'Mandatory Training', 'files/1686571965.pdf', '', '2024-01-02', '12th June 2023', ''),
(237, '169325', '44', 'NRS Application Form', 'Application Documents', 'files/1686572259.pdf', '', '', '12th June 2023', ''),
(238, '169325', '44', 'NRS CV', 'CV Documents', 'files/1686572299.pdf', '', '', '12th June 2023', ''),
(240, '169325', '44', 'POAx2', 'Proof of residence', 'files/1686572441.pdf', '', '', '12th June 2023', ''),
(243, '169325', '25', 'DBS - NRS', 'DBS Documents', 'files/1686653454.pdf', '', '2024-05-02', '13th June 2023', ''),
(244, '169325', '45', 'NRS Application Form', 'Application Documents', 'files/1686653616.pdf', '', '', '13th June 2023', ''),
(245, '169325', '45', 'NRS CV', 'CV Documents', 'files/1686653649.pdf', '', '', '13th June 2023', ''),
(246, '169325', '45', 'BRP', 'RTW Documents', 'files/1686653750.pdf', '', '2024-12-21', '13th June 2023', ''),
(247, '169325', '45', 'DBS + Update Service', 'DBS Documents', 'files/1686653834.pdf', '', '2024-04-07', '13th June 2023', ''),
(248, '169325', '45', 'POAx2', 'Proof of residence', 'files/1686653873.pdf', '', '', '13th June 2023', ''),
(249, '169325', '46', 'NRS Application Form', 'Application Documents', 'files/1686654274.pdf', '', '', '13th June 2023', ''),
(250, '169325', '46', 'NRS CV', 'CV Documents', 'files/1686654375.pdf', '', '', '13th June 2023', ''),
(251, '169325', '46', 'DBS + Update Service', 'DBS Documents', 'files/1686654462.pdf', '', '2024-03-18', '13th June 2023', ''),
(252, '169325', '46', 'POAx2', 'Proof of residence', 'files/1686654533.pdf', '', '', '13th June 2023', ''),
(253, '169325', '46', 'Mandatory Training', 'Mandatory Training', 'files/1686654651.pdf', '', '2023-11-20', '13th June 2023', ''),
(254, '169325', '47', 'NRS Application Form', 'Application Documents', 'files/1686654933.pdf', '', '', '13th June 2023', ''),
(255, '169325', '47', 'NRS CV', 'CV Documents', 'files/1686655050.pdf', '', '', '13th June 2023', ''),
(256, '169325', '47', 'Passport', 'RTW Documents', 'files/1686655117.pdf', '', '2027-09-03', '13th June 2023', ''),
(258, '169325', '47', 'POAx2', 'Proof of residence', 'files/1686655562.pdf', '', '', '13th June 2023', ''),
(261, '169325', '48', 'Application Form', 'Application Documents', 'files/1686656030.pdf', '', '', '13th June 2023', ''),
(262, '169325', '48', 'NRS CV', 'CV Documents', 'files/1686656151.pdf', '', '', '13th June 2023', ''),
(263, '169325', '48', 'BRP', 'RTW Documents', 'files/1686656222.pdf', '', '2025-09-14', '13th June 2023', ''),
(264, '169325', '48', 'DBS - NRS', 'DBS Documents', 'files/1686656292.pdf', '', '2024-01-21', '13th June 2023', ''),
(265, '169325', '48', 'POAx2', 'Proof of residence', 'files/1686656359.pdf', '', '', '13th June 2023', ''),
(266, '169325', '48', 'Mandatory Training', 'Mandatory Training', 'files/1686656472.pdf', '', '2023-10-15', '13th June 2023', ''),
(267, '169325', '49', 'NRS Application Form', 'Application Documents', 'files/1686656719.pdf', '', '', '13th June 2023', ''),
(268, '169325', '49', 'NRS CV', 'CV Documents', 'files/1686657028.pdf', '', '', '13th June 2023', ''),
(269, '169325', '49', 'Passport', 'RTW Documents', 'files/1686657116.pdf', '', '2026-03-26', '13th June 2023', ''),
(270, '169325', '49', 'DBS + Update Service', 'DBS Documents', 'files/1686657189.pdf', '', '2024-02-23', '13th June 2023', ''),
(271, '169325', '49', 'POAx2', 'Proof of residence', 'files/1686657234.pdf', '', '', '13th June 2023', ''),
(273, '169325', '49', 'Fire Safety', 'Mandatory Training', 'files/1686657351.pdf', '', '2023-08-18', '13th June 2023', ''),
(274, '169325', '49', 'First Aid', 'Mandatory Training', 'files/1686657398.pdf', '', '2023-08-18', '13th June 2023', ''),
(275, '169325', '49', 'Health & Safety', 'Mandatory Training', 'files/1686657449.pdf', '', '2023-08-18', '13th June 2023', ''),
(276, '169325', '49', 'Manual Handling', 'Mandatory Training', 'files/1686657511.pdf', '', '2023-08-19', '13th June 2023', ''),
(277, '169325', '49', 'Safeguarding Vulnerable Adults', 'Mandatory Training', 'files/1686657555.pdf', '', '2023-08-19', '13th June 2023', ''),
(278, '169325', '50', 'NRS Application Form', 'Application Documents', 'files/1686691497.pdf', '', '', '14th June 2023', ''),
(279, '169325', '50', 'BRP', 'RTW Documents', 'files/1686691731.pdf', '', '2024-12-31', '14th June 2023', ''),
(280, '169325', '50', 'NRS CV', 'CV Documents', 'files/1686692078.docx', '', '', '14th June 2023', ''),
(281, '169325', '50', 'DBS - NRS', 'DBS Documents', 'files/1686692528.pdf', '', '2024-03-14', '14th June 2023', ''),
(282, '169325', '50', 'POAx2', 'Proof of residence', 'files/1686692659.pdf', '', '', '14th June 2023', ''),
(283, '169325', '50', 'Fire Safety', 'Mandatory Training', 'files/1686692767.pdf', '', '2024-03-02', '14th June 2023', ''),
(284, '169325', '50', 'First Aid', 'Mandatory Training', 'files/1686692849.pdf', '', '2023-08-29', '14th June 2023', ''),
(285, '169325', '50', 'First Aid', 'Mandatory Training', 'files/1686692955.pdf', '', '2023-09-24', '14th June 2023', ''),
(286, '169325', '50', 'Food Hygiene', 'Mandatory Training', 'files/1686693041.pdf', '', '2023-09-24', '14th June 2023', ''),
(287, '169325', '50', 'Health & Safety', 'Mandatory Training', 'files/1686693445.pdf', '', '2023-09-26', '14th June 2023', ''),
(288, '169325', '50', 'Infection Prevention & Control', 'Mandatory Training', 'files/1686693663.pdf', '', '2023-09-26', '14th June 2023', ''),
(289, '169325', '50', 'Medication', 'Mandatory Training', 'files/1686693880.pdf', '', '2023-09-26', '14th June 2023', ''),
(290, '169325', '53', 'NRS Application Form', 'Application Documents', 'files/1686694254.pdf', '', '', '14th June 2023', ''),
(291, '169325', '53', 'NRS CV', 'CV Documents', 'files/1686694329.docx', '', '', '14th June 2023', ''),
(293, '169325', '53', 'POAx2', 'Proof of residence', 'files/1686694630.pdf', '', '', '14th June 2023', ''),
(294, '169325', '53', 'ECS -RTW', 'RTW Documents', 'files/1686695243.pdf', '', '2023-10-17', '14th June 2023', ''),
(296, '169325', '64', 'NRS Application Form', 'Application Documents', 'files/1686695737.pdf', '', '', '14th June 2023', ''),
(297, '169325', '64', 'NRS CV', 'CV Documents', 'files/1686695766.docx', '', '', '14th June 2023', ''),
(298, '169325', '64', 'BRP', 'RTW Documents', 'files/1686695849.pdf', '', '2024-02-29', '14th June 2023', ''),
(300, '169325', '64', 'POAx2', 'Proof of residence', 'files/1686696015.pdf', '', '', '14th June 2023', ''),
(302, '169325', '65', 'NRS Application Form', 'Application Documents', 'files/1686696344.pdf', '', '', '14th June 2023', ''),
(303, '169325', '65', 'NRS CV', 'CV Documents', 'files/1686696414.docx', '', '', '14th June 2023', ''),
(304, '169325', '65', 'EU -Passport', 'RTW Documents', 'files/1686696611.pdf', '', '2024-05-13', '14th June 2023', ''),
(305, '169325', '65', 'DBS - NRS', 'DBS Documents', 'files/1686696684.pdf', '', '2023-10-18', '14th June 2023', ''),
(306, '169325', '65', 'POA 2', 'Proof of residence', 'files/1686696758.pdf', '', '', '14th June 2023', ''),
(307, '169325', '66', 'Application Form', 'Application Documents', 'files/1686697110.pdf', '', '', '14th June 2023', ''),
(308, '169325', '66', 'NRS CV', 'CV Documents', 'files/1686697138.docx', '', '', '14th June 2023', ''),
(309, '169325', '66', 'BRP', 'RTW Documents', 'files/1686697308.pdf', '', '2024-08-06', '14th June 2023', ''),
(312, '169325', '66', 'POAx2', 'Proof of residence', 'files/1686697574.pdf', '', '', '14th June 2023', ''),
(314, '169325', '67', 'NRS Application Form', 'Application Documents', 'files/1686739576.pdf', '', '', '14th June 2023', ''),
(315, '169325', '67', 'NRS CV', 'CV Documents', 'files/1686739634.docx', '', '', '14th June 2023', ''),
(316, '169325', '67', 'BRP', 'RTW Documents', 'files/1686740052.pdf', '', '2024-06-02', '14th June 2023', ''),
(317, '169325', '67', 'POAx2', 'Proof of residence', 'files/1686740475.pdf', '', '', '14th June 2023', ''),
(318, '169325', '67', 'Mandatory Training', 'Mandatory Training', 'files/1686740550.pdf', '', '2024-01-09', '14th June 2023', ''),
(319, '169325', '68', 'NRS Application Form', 'Application Documents', 'files/1686740986.pdf', '', '', '14th June 2023', ''),
(320, '169325', '68', 'NRS CV', 'CV Documents', 'files/1686741034.docx', '', '', '14th June 2023', ''),
(322, '169325', '68', 'DBS - NRS', 'DBS Documents', 'files/1686741778.pdf', '', '2023-12-28', '14th June 2023', ''),
(323, '169325', '68', 'POAx2', 'Proof of residence', 'files/1686742073.pdf', '', '', '14th June 2023', ''),
(324, '169325', '68', 'BRP', 'RTW Documents', 'files/1686742384.pdf', '', '2024-12-15', '14th June 2023', ''),
(325, '169325', '68', 'Mandatory Training', 'Mandatory Training', 'files/1686742694.pdf', '', '2023-10-18', '14th June 2023', ''),
(326, '169325', '70', 'NRS Application Form', 'Application Documents', 'files/1686919912.pdf', '', '', '16th June 2023', ''),
(327, '169325', '70', 'NRS CV', 'CV Documents', 'files/1686919956.docx', '', '', '16th June 2023', ''),
(328, '169325', '70', 'BRP', 'RTW Documents', 'files/1686920044.pdf', '', '2024-12-31', '16th June 2023', ''),
(329, '169325', '70', 'DBS - NRS', 'DBS Documents', 'files/1686920103.pdf', '', '2024-02-24', '16th June 2023', ''),
(330, '169325', '70', 'POAx2', 'Proof of residence', 'files/1686920164.pdf', '', '', '16th June 2023', ''),
(331, '169325', '70', 'Mandatory Training', 'Mandatory Training', 'files/1686920295.pdf', '', '2024-02-02', '16th June 2023', ''),
(332, '169325', '71', 'NRS Application Form', 'Application Documents', 'files/1686920464.docx', '', '', '16th June 2023', ''),
(333, '169325', '71', 'NRS CV', 'CV Documents', 'files/1686920515.docx', '', '', '16th June 2023', ''),
(334, '169325', '71', 'BRP', 'RTW Documents', 'files/1686920640.pdf', '', '2024-01-11', '16th June 2023', ''),
(335, '169325', '71', 'DBS - NRS', 'DBS Documents', 'files/1686920724.pdf', '', '2023-09-12', '16th June 2023', ''),
(336, '169325', '71', 'POAx2', 'Proof of residence', 'files/1686920765.pdf', '', '', '16th June 2023', ''),
(337, '169325', '71', 'Fire Safety', 'Mandatory Training', 'files/1686920895.pdf', '', '2024-03-24', '16th June 2023', ''),
(338, '169325', '71', 'First Aid', 'Mandatory Training', 'files/1686920949.pdf', '', '2024-04-01', '16th June 2023', ''),
(339, '169325', '71', 'Medication', 'Mandatory Training', 'files/1686921004.pdf', '', '2024-03-24', '16th June 2023', ''),
(342, '169325', '71', 'Health & Safety', 'Mandatory Training', 'files/1686921197.pdf', '', '2024-04-01', '16th June 2023', ''),
(343, '169325', '71', 'Infection Prevention & Control', 'Mandatory Training', 'files/1686921299.pdf', '', '2024-04-01', '16th June 2023', ''),
(344, '169325', '71', 'Infection Prevention & Control', 'Mandatory Training', 'files/1686921301.pdf', '', '2024-04-01', '16th June 2023', ''),
(345, '169325', '71', 'Infection Prevention & Control', 'Mandatory Training', 'files/1686921303.pdf', '', '2024-04-01', '16th June 2023', ''),
(346, '169325', '71', 'Manual Handling', 'Mandatory Training', 'files/1686921357.pdf', '', '2024-04-01', '16th June 2023', ''),
(347, '169325', '71', 'Safeguarding Children', 'Mandatory Training', 'files/1686921407.pdf', '', '2024-03-28', '16th June 2023', ''),
(348, '169325', '71', 'Safeguarding Children', 'Mandatory Training', 'files/1686921408.pdf', '', '2024-03-28', '16th June 2023', ''),
(349, '169325', '72', 'Application Form', 'Application Documents', 'files/1686921822.pdf', '', '', '16th June 2023', ''),
(350, '169325', '72', 'NRS CV', 'CV Documents', 'files/1686922339.docx', '', '', '16th June 2023', ''),
(351, '169325', '72', 'BRP', 'RTW Documents', 'files/1687172540.pdf', '', '2024-11-30', '19th June 2023', ''),
(354, '169325', '72', 'POAx2', 'Proof of residence', 'files/1687173106.pdf', '', '', '19th June 2023', ''),
(355, '169325', '72', 'Mandatory Training', 'Mandatory Training', 'files/1687173232.pdf', '', '2024-04-18', '19th June 2023', ''),
(356, '169325', '73', 'NRS Application Form', 'Application Documents', 'files/1687173876.pdf', '', '', '19th June 2023', ''),
(357, '169325', '73', 'NRS CV', 'CV Documents', 'files/1687173917.docx', '', '', '19th June 2023', ''),
(358, '169325', '73', 'BRP', 'RTW Documents', 'files/1687174042.pdf', 'Student', '2024-02-24', '19th June 2023', ''),
(359, '169325', '73', 'Adult -DBS', 'DBS Documents', 'files/1687174149.pdf', '', '2024-01-17', '19th June 2023', ''),
(360, '169325', '73', 'POAx2', 'Proof of residence', 'files/1687174211.pdf', '', '', '19th June 2023', ''),
(361, '169325', '73', 'Mandatory Training', 'Mandatory Training', 'files/1687174308.pdf', '', '2024-01-04', '19th June 2023', ''),
(362, '169325', '74', 'NRS Application Form', 'Application Documents', 'files/1687174520.pdf', '', '', '19th June 2023', ''),
(363, '169325', '74', 'NRS CV', 'CV Documents', 'files/1687174578.docx', '', '', '19th June 2023', ''),
(364, '169325', '74', 'BRP', 'RTW Documents', 'files/1687174742.pdf', '', '2024-01-17', '19th June 2023', ''),
(365, '169325', '74', 'DBS + Update Service', 'DBS Documents', 'files/1687174814.pdf', '', '2023-12-27', '19th June 2023', ''),
(367, '169325', '74', 'POAx2', 'Proof of residence', 'files/1687175101.pdf', '', '', '19th June 2023', ''),
(368, '169325', '74', 'Mandatory Training', 'Mandatory Training', 'files/1687175231.pdf', '', '2024-02-28', '19th June 2023', ''),
(369, '169325', '75', 'NRS Application Form', 'Application Documents', 'files/1687175368.docx', '', '', '19th June 2023', ''),
(370, '169325', '75', 'NRS CV', 'CV Documents', 'files/1687175445.docx', '', '', '19th June 2023', ''),
(371, '169325', '75', 'BRP', 'RTW Documents', 'files/1687175618.pdf', '', '2024-01-13', '19th June 2023', ''),
(373, '169325', '75', 'POAx2', 'Proof of residence', 'files/1687175750.pdf', '', '', '19th June 2023', ''),
(375, '169325', '76', 'NRS Application Form', 'Application Documents', 'files/1687175974.pdf', '', '', '19th June 2023', ''),
(376, '169325', '76', 'NRS CV', 'CV Documents', 'files/1687176020.docx', '', '', '19th June 2023', ''),
(377, '169325', '76', 'BRP', 'RTW Documents', 'files/1687176120.pdf', 'Student Visa', '2024-01-29', '19th June 2023', ''),
(378, '169325', '76', 'DBS + Update Service', 'DBS Documents', 'files/1687176189.pdf', '', '2023-11-10', '19th June 2023', ''),
(379, '169325', '76', 'POAx2', 'Proof of residence', 'files/1687176220.pdf', '', '', '19th June 2023', ''),
(380, '169325', '76', 'Mandatory Training', 'Mandatory Training', 'files/1687176316.pdf', '', '2023-08-29', '19th June 2023', ''),
(381, '169325', '77', 'NRS Application Form', 'Application Documents', 'files/1687176497.pdf', '', '', '19th June 2023', ''),
(382, '169325', '77', 'NRS CV', 'CV Documents', 'files/1687176598.docx', '', '', '19th June 2023', ''),
(384, '169325', '77', 'DBS + Update Service', 'DBS Documents', 'files/1687176837.pdf', '', '2023-09-26', '19th June 2023', ''),
(385, '169325', '77', 'POA 1', 'Proof of residence', 'files/1687176873.pdf', '', '', '19th June 2023', ''),
(386, '169325', '77', 'Mandatory Training', 'Mandatory Training', 'files/1687176964.pdf', '', '2023-09-20', '19th June 2023', ''),
(387, '169325', '78', 'NRS Application Form', 'Application Documents', 'files/1687177107.pdf', '', '', '19th June 2023', ''),
(388, '169325', '78', 'NRS CV', 'CV Documents', 'files/1687177151.docx', '', '', '19th June 2023', ''),
(389, '169325', '78', 'BRP', 'RTW Documents', 'files/1687177391.pdf', 'Student Visa', '2024-01-29', '19th June 2023', ''),
(390, '169325', '78', 'DBS + Update Service', 'DBS Documents', 'files/1687177454.pdf', '', '2023-10-19', '19th June 2023', ''),
(391, '169325', '78', 'POAx2', 'Proof of residence', 'files/1687177484.pdf', '', '', '19th June 2023', ''),
(392, '169325', '78', 'Mandatory Training', 'Mandatory Training', 'files/1687177547.pdf', '', '2023-10-15', '19th June 2023', ''),
(393, '169325', '80', 'NRS Application Form', 'Application Documents', 'files/1687178673.PDF', '', '', '19th June 2023', ''),
(394, '169325', '80', 'NRS CV', 'CV Documents', 'files/1687178723.docx', '', '', '19th June 2023', ''),
(395, '169325', '80', 'Passport', 'RTW Documents', 'files/1687178970.pdf', '', '2029-06-12', '19th June 2023', ''),
(396, '169325', '80', 'DBS - NRS', 'DBS Documents', 'files/1687179032.pdf', '', '2024-01-20', '19th June 2023', ''),
(397, '169325', '80', 'POAx2', 'Proof of residence', 'files/1687179073.pdf', '', '', '19th June 2023', ''),
(398, '169325', '80', 'Mandatory Training', 'Mandatory Training', 'files/1687179218.pdf', '', '2024-06-06', '19th June 2023', ''),
(399, '169325', '81', 'NRS Application Form', 'Application Documents', 'files/1687181595.pdf', '', '', '19th June 2023', ''),
(400, '169325', '81', 'NRS CV', 'CV Documents', 'files/1687181650.docx', '', '', '19th June 2023', ''),
(401, '169325', '81', 'Passport', 'RTW Documents', 'files/1687181698.pdf', '', '2026-08-16', '19th June 2023', ''),
(402, '169325', '81', 'DBS - NRS', 'DBS Documents', 'files/1687181785.pdf', '', '2023-10-17', '19th June 2023', ''),
(403, '169325', '81', 'POAx2', 'Proof of residence', 'files/1687181833.pdf', '', '', '19th June 2023', ''),
(404, '169325', '81', 'First Aid', 'Mandatory Training', 'files/1687181927.pdf', '', '2023-10-26', '19th June 2023', ''),
(405, '169325', '81', 'Medication', 'Mandatory Training', 'files/1687181995.pdf', '', '2023-10-26', '19th June 2023', ''),
(406, '169325', '81', 'Manual Handling', 'Mandatory Training', 'files/1687182059.pdf', '', '2023-10-26', '19th June 2023', ''),
(407, '169325', '81', 'Health & Safety', 'Mandatory Training', 'files/1687182131.pdf', '', '2023-10-26', '19th June 2023', ''),
(408, '169325', '81', 'Infection Prevention & Control', 'Mandatory Training', 'files/1687182212.pdf', '', '2023-10-26', '19th June 2023', ''),
(409, '169325', '81', 'Safeguarding Children', 'Mandatory Training', 'files/1687182308.pdf', '', '2023-11-26', '19th June 2023', ''),
(411, '169325', '82', 'NRS Application Form', 'Application Documents', 'files/1687182603.docx', '', '', '19th June 2023', ''),
(412, '169325', '82', 'NRS CV', 'CV Documents', 'files/1687182645.docx', '', '', '19th June 2023', ''),
(413, '169325', '82', 'BRP', 'RTW Documents', 'files/1687182780.pdf', '', '2024-11-30', '19th June 2023', ''),
(415, '169325', '82', 'POAx2', 'Proof of residence', 'files/1687182930.pdf', '', '', '19th June 2023', ''),
(417, '169325', '83', 'NRS Application Form', 'Application Documents', 'files/1687183863.pdf', '', '', '19th June 2023', ''),
(418, '169325', '83', 'NRS CV', 'CV Documents', 'files/1687183895.docx', '', '', '19th June 2023', ''),
(419, '169325', '83', 'BRP', 'RTW Documents', 'files/1687183988.pdf', 'Student Visa', '2024-01-16', '19th June 2023', ''),
(420, '169325', '83', 'DBS + Update Service', 'DBS Documents', 'files/1687184071.pdf', '', '2023-10-09', '19th June 2023', ''),
(421, '169325', '83', 'DBS + Update Service', 'DBS Documents', 'files/1687184104.pdf', '', '2023-10-09', '19th June 2023', ''),
(422, '169325', '83', 'POAx2', 'Proof of residence', 'files/1687265597.pdf', '', '', '20th June 2023', ''),
(423, '169325', '83', 'Mandatory Training', 'Mandatory Training', 'files/1687265711.pdf', '', '2023-09-03', '20th June 2023', ''),
(424, '169325', '85', 'NRS Application Form', 'Application Documents', 'files/1687265961.pdf', '', '', '20th June 2023', ''),
(425, '169325', '85', 'NRS CV', 'CV Documents', 'files/1687266010.docx', '', '', '20th June 2023', ''),
(426, '169325', '85', 'BRP', 'RTW Documents', 'files/1687266194.pdf', '', '2026-01-11', '20th June 2023', ''),
(427, '169325', '85', 'DBS - NRS', 'DBS Documents', 'files/1687266264.pdf', '', '2023-12-07', '20th June 2023', ''),
(428, '169325', '85', 'POAx2', 'Proof of residence', 'files/1687266322.pdf', '', '', '20th June 2023', ''),
(429, '169325', '85', 'Mandatory Training', 'Mandatory Training', 'files/1687266390.pdf', '', '2023-12-31', '20th June 2023', ''),
(430, '169325', '87', 'Application Form', 'Application Documents', 'files/1687266559.docx', '', '', '20th June 2023', ''),
(431, '169325', '87', 'NRS CV', 'CV Documents', 'files/1687266587.docx', '', '', '20th June 2023', ''),
(432, '169325', '87', 'ECS -RTW', 'RTW Documents', 'files/1687266813.pdf', '', '2023-10-25', '20th June 2023', ''),
(434, '169325', '87', 'POA 1', 'Proof of residence', 'files/1687266977.pdf', '', '', '20th June 2023', ''),
(435, '169325', '87', 'Food Hygiene', 'Mandatory Training', 'files/1687267140.pdf', '', '2023-11-17', '20th June 2023', ''),
(436, '169325', '87', 'Manual Handling', 'Mandatory Training', 'files/1687267182.pdf', '', '2023-11-17', '20th June 2023', ''),
(437, '169325', '87', 'Fire Safety', 'Mandatory Training', 'files/1687267238.pdf', '', '2023-11-17', '20th June 2023', ''),
(438, '169325', '87', 'Medication', 'Mandatory Training', 'files/1687267293.pdf', '', '2023-11-17', '20th June 2023', ''),
(439, '169325', '87', 'Infection Prevention & Control', 'Mandatory Training', 'files/1687267382.pdf', '', '2024-02-28', '20th June 2023', ''),
(440, '169325', '87', 'First Aid', 'Mandatory Training', 'files/1687267518.pdf', '', '2024-03-01', '20th June 2023', ''),
(441, '169325', '87', 'Health & Safety', 'Mandatory Training', 'files/1687267724.pdf', '', '2024-03-01', '20th June 2023', ''),
(443, '169325', '88', 'Application Form', 'Application Documents', 'files/1687268134.pdf', '', '', '20th June 2023', ''),
(444, '169325', '88', 'NRS CV', 'CV Documents', 'files/1687268257.docx', '', '', '20th June 2023', ''),
(445, '169325', '88', 'ECS -RTW', 'RTW Documents', 'files/1687268490.pdf', '', '2023-10-26', '20th June 2023', ''),
(446, '169325', '88', 'DBS + Update Service', 'DBS Documents', 'files/1687268569.pdf', '', '2024-06-20', '20th June 2023', ''),
(447, '169325', '88', 'POAx2', 'Proof of residence', 'files/1687268627.pdf', '', '', '20th June 2023', ''),
(448, '169325', '88', 'Mandatory Training', 'Mandatory Training', 'files/1687268734.pdf', '', '2024-04-15', '20th June 2023', ''),
(449, '169325', '27', 'Mandatory Training', 'Mandatory Training', 'files/1687352896.pdf', '', '2024-05-19', '21st June 2023', ''),
(450, '169325', '89', 'NRS Application Form', 'Application Documents', 'files/1687353640.pdf', '', '', '21st June 2023', ''),
(451, '169325', '89', 'NRS CV', 'CV Documents', 'files/1687353675.docx', '', '', '21st June 2023', ''),
(452, '169325', '89', 'BRP', 'RTW Documents', 'files/1687353755.pdf', '', '2024-11-30', '21st June 2023', ''),
(453, '169325', '89', 'DBS - NRS', 'DBS Documents', 'files/1687353823.pdf', '', '2024-01-10', '21st June 2023', ''),
(454, '169325', '89', 'POAx2', 'Proof of residence', 'files/1687353865.pdf', '', '', '21st June 2023', ''),
(455, '169325', '89', 'Mandatory Training', 'Mandatory Training', 'files/1687353998.pdf', '', '2023-12-18', '21st June 2023', ''),
(456, '169325', '90', 'NRS Application Form', 'Application Documents', 'files/1687354159.docx', '', '', '21st June 2023', ''),
(457, '169325', '90', 'NRS CV', 'CV Documents', 'files/1687354208.docx', '', '', '21st June 2023', ''),
(458, '169325', '90', 'Passport', 'RTW Documents', 'files/1687354350.pdf', '', '2028-06-13', '21st June 2023', ''),
(459, '169325', '90', 'POAx2', 'Proof of residence', 'files/1687354505.pdf', '', '', '21st June 2023', ''),
(460, '169325', '90', 'Mandatory Training', 'Mandatory Training', 'files/1687354672.pdf', '', '2023-12-14', '21st June 2023', ''),
(461, '169325', '91', 'Application Form', 'Application Documents', 'files/1687354829.docx', '', '', '21st June 2023', ''),
(462, '169325', '91', 'NRS CV', 'CV Documents', 'files/1687354870.docx', '', '', '21st June 2023', ''),
(463, '169325', '91', 'BRP', 'RTW Documents', 'files/1687355007.pdf', 'Settlement', '2024-11-30', '21st June 2023', ''),
(464, '169325', '91', 'DBS + Update Service', 'DBS Documents', 'files/1687355192.pdf', '', '2024-06-18', '21st June 2023', ''),
(465, '169325', '91', 'POAx2', 'Proof of residence', 'files/1687355237.pdf', '', '', '21st June 2023', ''),
(466, '169325', '91', 'Safeguarding Children', 'Mandatory Training', 'files/1687355360.pdf', '', '2023-08-24', '21st June 2023', ''),
(467, '169325', '91', 'Manual Handling', 'Mandatory Training', 'files/1687355417.pdf', '', '2023-10-01', '21st June 2023', ''),
(468, '169325', '91', 'Medication', 'Mandatory Training', 'files/1687357389.pdf', '', '2023-09-16', '21st June 2023', ''),
(469, '169325', '91', 'Infection Prevention & Control', 'Mandatory Training', 'files/1687357542.pdf', '', '2023-09-28', '21st June 2023', ''),
(470, '169325', '91', 'First Aid', 'Mandatory Training', 'files/1687357667.pdf', '', '2023-09-27', '21st June 2023', ''),
(471, '169325', '91', 'Fire Safety', 'Mandatory Training', 'files/1687357728.pdf', '', '2023-09-25', '21st June 2023', ''),
(472, '169325', '91', 'Food Hygiene', 'Mandatory Training', 'files/1687357842.pdf', '', '2023-09-25', '21st June 2023', ''),
(473, '169325', '92', 'NRS Application Form', 'Application Documents', 'files/1687359446.pdf', '', '', '21st June 2023', ''),
(474, '169325', '92', 'NRS CV', 'CV Documents', 'files/1687359479.docx', '', '', '21st June 2023', ''),
(475, '169325', '92', 'Passport', 'RTW Documents', 'files/1687359589.pdf', 'GBR', '2029-04-08', '21st June 2023', ''),
(476, '169325', '81', 'Safeguarding Vulnerable Adults', 'Mandatory Training', 'files/1687943574.pdf', '', '2023-10-26', '28th June 2023', ''),
(477, '169325', '92', 'DBS - NRS', 'DBS Documents', 'files/1687943947.pdf', '', '2023-10-01', '28th June 2023', ''),
(478, '169325', '92', 'POAx2', 'Proof of residence', 'files/1687943990.pdf', '', '', '28th June 2023', ''),
(480, '169325', '92', 'Safeguarding Vulnerable Adults', 'Mandatory Training', 'files/1687944154.pdf', '', '2023-08-12', '28th June 2023', ''),
(481, '169325', '92', 'Manual Handling', 'Mandatory Training', 'files/1687944200.pdf', '', '2023-08-24', '28th June 2023', ''),
(482, '169325', '92', 'Health & Safety', 'Mandatory Training', 'files/1687944248.pdf', '', '2023-11-08', '28th June 2023', ''),
(483, '169325', '92', 'Infection Prevention & Control', 'Mandatory Training', 'files/1687944293.pdf', '', '2023-11-08', '28th June 2023', ''),
(484, '169325', '92', 'First Aid', 'Mandatory Training', 'files/1687944339.pdf', '', '2023-11-08', '28th June 2023', ''),
(485, '169325', '92', 'Food Hygiene', 'Mandatory Training', 'files/1687944386.pdf', '', '2023-11-08', '28th June 2023', ''),
(486, '169325', '92', 'Fire Safety', 'Mandatory Training', 'files/1687944701.pdf', '', '2023-10-24', '28th June 2023', '');
INSERT INTO `candidatesfiles` (`id`, `app_id`, `candidateid`, `name`, `type`, `file`, `notes`, `expirydate`, `createdOn`, `time`) VALUES
(487, '169325', '93', 'NRS Application Form', 'Application Documents', 'files/1687944885.pdf', '', '', '28th June 2023', ''),
(488, '169325', '93', 'NRS CV', 'CV Documents', 'files/1687944934.docx', '', '', '28th June 2023', ''),
(489, '169325', '93', 'BRP', 'RTW Documents', 'files/1687945081.pdf', 'EU Pre Settlement', '2026-04-10', '28th June 2023', ''),
(490, '169325', '93', 'DBS + Update Service', 'DBS Documents', 'files/1687945323.pdf', '', '2024-06-18', '28th June 2023', ''),
(491, '169325', '93', 'POAx2', 'Proof of residence', 'files/1687945369.pdf', '', '', '28th June 2023', ''),
(492, '169325', '93', 'Medication', 'Mandatory Training', 'files/1687945465.pdf', '', '2024-03-06', '28th June 2023', ''),
(493, '169325', '93', 'Manual Handling', 'Mandatory Training', 'files/1687945511.pdf', '', '2024-03-06', '28th June 2023', ''),
(494, '169325', '93', 'Fire Safety', 'Mandatory Training', 'files/1687945574.pdf', '', '2023-11-24', '28th June 2023', ''),
(495, '169325', '93', 'Food Hygiene', 'Mandatory Training', 'files/1687945631.pdf', '', '2023-11-26', '28th June 2023', ''),
(496, '169325', '93', 'Health & Safety', 'Mandatory Training', 'files/1687945679.pdf', '', '2023-11-26', '28th June 2023', ''),
(497, '169325', '93', 'Infection Prevention & Control', 'Mandatory Training', 'files/1687945722.pdf', '', '2023-11-29', '28th June 2023', ''),
(498, '169325', '93', 'Safeguarding Vulnerable Adults', 'Mandatory Training', 'files/1687945781.pdf', '', '2024-03-06', '28th June 2023', ''),
(499, '169325', '93', 'First Aid', 'Mandatory Training', 'files/1687945832.pdf', '', '2023-11-24', '28th June 2023', ''),
(500, '169325', '94', 'NRS Application Form', 'Application Documents', 'files/1687945952.pdf', '', '', '28th June 2023', ''),
(501, '169325', '94', 'NRS CV', 'CV Documents', 'files/1687945985.docx', '', '', '28th June 2023', ''),
(502, '169325', '94', 'BRP', 'RTW Documents', 'files/1687946078.pdf', '', '2024-09-19', '28th June 2023', ''),
(503, '169325', '94', 'DBS + Update Service', 'DBS Documents', 'files/1687946163.pdf', '', '2023-09-07', '28th June 2023', ''),
(504, '169325', '94', 'POAx2', 'Proof of residence', 'files/1687946206.pdf', '', '', '28th June 2023', ''),
(505, '169325', '94', 'Medication', 'Mandatory Training', 'files/1687946339.pdf', '', '2023-11-12', '28th June 2023', ''),
(506, '169325', '94', 'Fire Safety', 'Mandatory Training', 'files/1687946403.pdf', '', '2023-11-19', '28th June 2023', ''),
(507, '169325', '94', 'Food Hygiene', 'Mandatory Training', 'files/1687946456.pdf', '', '2023-11-23', '28th June 2023', ''),
(508, '169325', '94', 'Health & Safety', 'Mandatory Training', 'files/1687946589.pdf', '', '2024-05-05', '28th June 2023', ''),
(509, '169325', '94', 'Safeguarding Vulnerable Adults', 'Mandatory Training', 'files/1687946658.pdf', '', '2024-01-12', '28th June 2023', ''),
(510, '169325', '94', 'First Aid', 'Mandatory Training', 'files/1687946736.pdf', '', '2024-01-12', '28th June 2023', ''),
(511, '169325', '94', 'Infection Prevention & Control', 'Mandatory Training', 'files/1687946796.pdf', '', '2024-02-20', '28th June 2023', ''),
(512, '169325', '94', 'Manual Handling', 'Mandatory Training', 'files/1687946853.pdf', '', '2023-11-15', '28th June 2023', ''),
(513, '169325', '95', 'NRS Application Form', 'Application Documents', 'files/1687947044.pdf', '', '', '28th June 2023', ''),
(514, '169325', '95', 'NRS CV', 'CV Documents', 'files/1687947083.docx', '', '', '28th June 2023', ''),
(515, '169325', '95', 'BRP', 'RTW Documents', 'files/1687947197.pdf', 'Student Visa', '2024-01-31', '28th June 2023', ''),
(516, '169325', '95', 'DBS - NRS', 'DBS Documents', 'files/1687947276.pdf', '', '2024-02-16', '28th June 2023', ''),
(517, '169325', '95', 'Mandatory Training', 'Mandatory Training', 'files/1687947365.pdf', '', '2023-12-29', '28th June 2023', ''),
(518, '169325', '95', 'POAx2', 'Proof of residence', 'files/1687947430.pdf', '', '', '28th June 2023', ''),
(519, '169325', '96', 'NRS Application Form', 'Application Documents', 'files/1687947625.pdf', '', '', '28th June 2023', ''),
(520, '169325', '96', 'NRS CV', 'CV Documents', 'files/1687947793.docx', '', '', '28th June 2023', ''),
(521, '169325', '96', 'Passport', 'RTW Documents', 'files/1687947920.pdf', 'British Passport', '2026-01-16', '28th June 2023', ''),
(522, '169325', '96', 'POAx2', 'Proof of residence', 'files/1687947979.pdf', '', '', '28th June 2023', ''),
(523, '169325', '96', 'Mandatory Training', 'Mandatory Training', 'files/1687948051.pdf', '', '2024-02-28', '28th June 2023', ''),
(524, '169325', '96', 'Infection Prevention & Control', 'Mandatory Training', 'files/1687948112.pdf', '', '2024-04-22', '28th June 2023', ''),
(525, '169325', '96', 'Manual Handling', 'Mandatory Training', 'files/1687948162.pdf', '', '2024-04-22', '28th June 2023', ''),
(526, '169325', '96', 'Safeguarding Vulnerable Adults', 'Mandatory Training', 'files/1687948268.pdf', '', '2024-05-23', '28th June 2023', ''),
(527, '169325', '97', 'NRS Application Form', 'Application Documents', 'files/1687948626.pdf', '', '', '28th June 2023', ''),
(528, '169325', '97', 'NRS CV', 'CV Documents', 'files/1687948677.docx', '', '', '28th June 2023', ''),
(529, '169325', '97', 'BRP', 'RTW Documents', 'files/1687948815.pdf', 'Leave To Remain', '2024-12-31', '28th June 2023', ''),
(530, '169325', '97', 'DBS - NRS', 'DBS Documents', 'files/1687948875.pdf', '', '2024-01-10', '28th June 2023', ''),
(531, '169325', '97', 'POAx2', 'Proof of residence', 'files/1687948924.pdf', '', '', '28th June 2023', ''),
(533, '169325', '98', 'NRS Application Form', 'Application Documents', 'files/1687949235.pdf', '', '', '28th June 2023', ''),
(534, '169325', '98', 'NRS CV', 'CV Documents', 'files/1687949287.docx', '', '', '28th June 2023', ''),
(535, '169325', '98', 'BRP', 'RTW Documents', 'files/1687949711.pdf', 'Student Visa', '2024-10-22', '28th June 2023', ''),
(536, '169325', '98', 'DBS - NRS', 'DBS Documents', 'files/1687950147.pdf', '', '2024-02-08', '28th June 2023', ''),
(537, '169325', '98', 'POAx2', 'Proof of residence', 'files/1687950195.pdf', '', '', '28th June 2023', ''),
(538, '169325', '98', 'Mandatory Training', 'Mandatory Training', 'files/1687950469.pdf', '', '2024-01-09', '28th June 2023', ''),
(539, '169325', '101', 'NRS Application Form', 'Application Documents', 'files/1687953742.pdf', '', '', '28th June 2023', ''),
(540, '169325', '101', 'NRS CV', 'CV Documents', 'files/1687953801.docx', '', '', '28th June 2023', ''),
(541, '169325', '101', 'BRP', 'RTW Documents', 'files/1687953949.pdf', 'EU Pre Settlement', '2025-11-15', '28th June 2023', ''),
(543, '169325', '101', 'POAx2', 'Proof of residence', 'files/1687954223.pdf', '', '', '28th June 2023', ''),
(544, '169325', '101', 'Mandatory Training', 'Mandatory Training', 'files/1687954405.pdf', '', '2024-04-22', '28th June 2023', ''),
(545, '169325', '104', 'NRS Application Form', 'Application Documents', 'files/1687954587.docx', '', '', '28th June 2023', ''),
(546, '169325', '104', 'NRS CV', 'CV Documents', 'files/1687954680.docx', '', '', '28th June 2023', ''),
(547, '169325', '104', 'Passport', 'RTW Documents', 'files/1687954889.pdf', '', '2032-01-03', '28th June 2023', ''),
(548, '169325', '104', 'DBS - NRS', 'DBS Documents', 'files/1687955031.pdf', 'DBS With Convictions', '2024-01-20', '28th June 2023', ''),
(549, '169325', '104', 'Mandatory Training', 'Mandatory Training', 'files/1687955122.pdf', '', '2024-04-02', '28th June 2023', ''),
(550, '169325', '105', 'NRS Application Form', 'Application Documents', 'files/1687955427.docx', '', '', '28th June 2023', ''),
(551, '169325', '105', 'NRS CV', 'CV Documents', 'files/1687955475.docx', '', '', '28th June 2023', ''),
(552, '169325', '105', 'BRP', 'RTW Documents', 'files/1687955566.pdf', '', '2024-11-30', '28th June 2023', ''),
(554, '169325', '105', 'Health & Safety', 'Mandatory Training', 'files/1687955762.pdf', '', '2024-05-09', '28th June 2023', ''),
(555, '169325', '105', 'POAx2', 'Proof of residence', 'files/1687957687.pdf', '', '', '28th June 2023', ''),
(556, '169325', '131', 'NRS Application Form', 'Application Documents', 'files/1688044793.pdf', '', '', '29th June 2023', ''),
(557, '169325', '131', 'NRS CV', 'CV Documents', 'files/1688044833.docx', '', '', '29th June 2023', ''),
(558, '169325', '131', 'ECS -RTW', 'RTW Documents', 'files/1688044965.pdf', '', '2023-09-19', '29th June 2023', ''),
(559, '169325', '131', 'DBS - NRS', 'DBS Documents', 'files/1688045041.pdf', '', '2023-09-28', '29th June 2023', ''),
(560, '169325', '131', 'POAx2', 'Proof of residence', 'files/1688045074.pdf', '', '', '29th June 2023', ''),
(561, '169325', '131', 'Mandatory Training', 'Mandatory Training', 'files/1688045159.pdf', '', '2023-09-22', '29th June 2023', ''),
(562, '169325', '126', 'NRS Application Form', 'Application Documents', 'files/1688045534.pdf', '', '', '29th June 2023', ''),
(563, '169325', '126', 'NRS CV', 'CV Documents', 'files/1688045595.docx', '', '', '29th June 2023', ''),
(565, '169325', '126', 'DBS - NRS', 'DBS Documents', 'files/1688045766.pdf', '', '2023-10-25', '29th June 2023', ''),
(566, '169325', '126', 'POAx2', 'Proof of residence', 'files/1688045845.pdf', '', '', '29th June 2023', ''),
(567, '169325', '126', 'Mandatory Training', 'Mandatory Training', 'files/1688046084.pdf', '', '2024-01-03', '29th June 2023', ''),
(568, '169325', '126', 'Fire Safety', 'Mandatory Training', 'files/1688046152.pdf', '', '2023-10-30', '29th June 2023', ''),
(569, '169325', '126', 'Medication', 'Mandatory Training', 'files/1688046217.pdf', '', '2023-10-30', '29th June 2023', ''),
(570, '169325', '136', 'NRS Application Form', 'Application Documents', 'files/1688548609.pdf', '', '', '5th July 2023', ''),
(571, '169325', '136', 'NRS CV', 'CV Documents', 'files/1688548658.docx', '', '', '5th July 2023', ''),
(572, '169325', '136', 'BRP', 'RTW Documents', 'files/1688548779.pdf', 'Settlement', '2024-11-30', '5th July 2023', ''),
(573, '169325', '136', 'DBS + Update Service', 'DBS Documents', 'files/1688548860.pdf', '', '2024-01-22', '5th July 2023', ''),
(574, '169325', '136', 'POAx2', 'Proof of residence', 'files/1688548917.pdf', '', '', '5th July 2023', ''),
(575, '169325', '136', 'Mandatory Training', 'Mandatory Training', 'files/1688548983.pdf', '', '2024-03-05', '5th July 2023', ''),
(576, '169325', '135', 'NRS Application Form', 'Application Documents', 'files/1688549172.docx', '', '', '5th July 2023', ''),
(577, '169325', '135', 'NRS CV', 'CV Documents', 'files/1688549288.docx', '', '', '5th July 2023', ''),
(578, '169325', '135', 'Mandatory Training', 'Mandatory Training', 'files/1688549464.pdf', '', '2024-02-24', '5th July 2023', ''),
(579, '169325', '135', 'BRP', 'RTW Documents', 'files/1688549586.pdf', 'Leave To Remain - Dependant Partner', '2024-11-30', '5th July 2023', ''),
(581, '169325', '135', 'POAx2', 'Proof of residence', 'files/1688549740.pdf', '', '', '5th July 2023', ''),
(582, '169325', '134', 'NRS Application Form', 'Application Documents', 'files/1688549888.pdf', '', '', '5th July 2023', ''),
(583, '169325', '134', 'NRS CV', 'CV Documents', 'files/1688549938.docx', '', '', '5th July 2023', ''),
(585, '169325', '134', 'Passport', 'RTW Documents', 'files/1688550102.pdf', 'British', '2029-06-14', '5th July 2023', ''),
(586, '169325', '134', 'DBS - NRS', 'DBS Documents', 'files/1688550185.pdf', '', '2024-06-08', '5th July 2023', ''),
(587, '169325', '134', 'POAx2', 'Proof of residence', 'files/1688550230.pdf', '', '', '5th July 2023', ''),
(588, '169325', '130', 'NRS Application Form', 'Application Documents', 'files/1688550793.docx', '', '', '5th July 2023', ''),
(589, '169325', '130', 'POAx2', 'Proof of residence', 'files/1688550856.pdf', '', '', '5th July 2023', ''),
(590, '169325', '130', 'NRS CV', 'CV Documents', 'files/1688550916.docx', '', '', '5th July 2023', ''),
(591, '169325', '129', 'NRS Application Form', 'Application Documents', 'files/1688551127.pdf', '', '', '5th July 2023', ''),
(592, '169325', '129', 'NRS CV', 'CV Documents', 'files/1688551239.docx', '', '', '5th July 2023', ''),
(593, '169325', '129', 'POAx2', 'Proof of residence', 'files/1688551274.pdf', '', '', '5th July 2023', ''),
(594, '169325', '128', 'NRS Application Form', 'Application Documents', 'files/1688551715.pdf', '', '', '5th July 2023', ''),
(595, '169325', '128', 'NRS CV', 'CV Documents', 'files/1688551807.docx', '', '', '5th July 2023', ''),
(596, '169325', '128', 'POAx2', 'Proof of residence', 'files/1688551869.pdf', '', '', '5th July 2023', ''),
(597, '169325', '127', 'NRS CV', 'CV Documents', 'files/1688552163.docx', '', '', '5th July 2023', ''),
(598, '169325', '127', 'POA 1', 'Proof of residence', 'files/1688552322.pdf', '', '', '5th July 2023', ''),
(599, '169325', '127', 'NRS Application Form', 'Application Documents', 'files/1688552868.docx', '', '', '5th July 2023', ''),
(600, '169325', '125', 'NRS Application Form', 'Application Documents', 'files/1688553131.docx', '', '', '5th July 2023', ''),
(601, '169325', '125', 'NRS CV', 'CV Documents', 'files/1688553253.docx', '', '', '5th July 2023', ''),
(602, '169325', '125', 'POAx2', 'Proof of residence', 'files/1688553325.pdf', '', '', '5th July 2023', ''),
(603, '169325', '121', 'Application Form', 'Application Documents', 'files/1688553907.pdf', '', '', '5th July 2023', ''),
(604, '169325', '121', 'POA 1', 'Proof of residence', 'files/1688553951.pdf', '', '', '5th July 2023', ''),
(605, '169325', '120', 'NRS Application Form', 'Application Documents', 'files/1688554270.pdf', '', '', '5th July 2023', ''),
(606, '169325', '120', 'NRS CV', 'CV Documents', 'files/1688554318.docx', '', '', '5th July 2023', ''),
(607, '169325', '120', 'BRP', 'RTW Documents', 'files/1688554486.pdf', 'Settlement', '2024-11-30', '5th July 2023', ''),
(609, '169325', '120', 'Food Hygiene', 'Mandatory Training', 'files/1688554643.pdf', '', '2023-10-03', '5th July 2023', ''),
(610, '169325', '120', 'Health & Safety', 'Mandatory Training', 'files/1688554703.pdf', '', '2023-10-18', '5th July 2023', ''),
(611, '169325', '120', 'Manual Handling', 'Mandatory Training', 'files/1688554775.pdf', '', '2023-10-18', '5th July 2023', ''),
(612, '169325', '120', 'Mandatory Training', 'Mandatory Training', 'files/1688554861.pdf', '', '2024-01-01', '5th July 2023', ''),
(613, '169325', '120', 'POAx2', 'Proof of residence', 'files/1688554933.pdf', '', '', '5th July 2023', ''),
(614, '169325', '108', 'Application Form', 'Application Documents', 'files/1688556578.pdf', '', '', '5th July 2023', ''),
(615, '169325', '108', 'NRS CV', 'CV Documents', 'files/1688556622.docx', '', '', '5th July 2023', ''),
(616, '169325', '108', 'BRP', 'RTW Documents', 'files/1688556753.pdf', 'Student Visa', '2023-12-19', '5th July 2023', ''),
(617, '169325', '108', 'DBS + Update Service', 'DBS Documents', 'files/1688556852.pdf', '', '2023-10-11', '5th July 2023', ''),
(618, '169325', '108', 'POAx2', 'Proof of residence', 'files/1688556900.pdf', '', '', '5th July 2023', ''),
(619, '169325', '108', 'Mandatory Training', 'Mandatory Training', 'files/1688556977.pdf', '', '2023-10-11', '5th July 2023', ''),
(620, '169325', '108', 'Infection Prevention & Control', 'Mandatory Training', 'files/1688557078.pdf', '', '2024-03-01', '5th July 2023', ''),
(621, '169325', '47', 'DBS + Update Service', 'DBS Documents', 'files/1688647485.pdf', '', '2024-05-30', '6th July 2023', ''),
(622, '169325', '1', 'DBS - NRS', 'DBS Documents', 'files/1689072315.pdf', '', '2024-05-16', '11th July 2023', ''),
(623, '169325', '1', 'Application Form', 'Application Documents', 'files/1689072358.docx', '', '', '11th July 2023', ''),
(624, '169325', '1', 'NRS CV', 'CV Documents', 'files/1689072429.docx', '', '', '11th July 2023', ''),
(625, '169325', '36', 'BRP', 'RTW Documents', 'files/1689073205.pdf', 'Graduate -Leave To Remain', '2024-07-04', '11th July 2023', ''),
(626, '169325', '39', 'NRS CV', 'CV Documents', 'files/1689073580.docx', '', '', '11th July 2023', ''),
(627, '169325', '46', 'BRP', 'RTW Documents', 'files/1689073972.pdf', 'Graduate -Leave To Remain', '2024-11-28', '11th July 2023', ''),
(628, '169325', '52', 'Application Form', 'Application Documents', 'files/1689079559.pdf', '', '', '11th July 2023', ''),
(629, '169325', '52', 'NRS CV', 'CV Documents', 'files/1689079638.docx', '', '', '11th July 2023', ''),
(630, '169325', '52', 'BRP', 'RTW Documents', 'files/1689079886.pdf', 'Skilled Worker -', '2024-11-29', '11th July 2023', ''),
(631, '169325', '52', 'DBS + Update Service', 'DBS Documents', 'files/1689079974.pdf', '', '2023-11-08', '11th July 2023', ''),
(632, '169325', '52', 'POAx2', 'Proof of residence', 'files/1689080038.pdf', '', '', '11th July 2023', ''),
(633, '169325', '52', 'Mandatory Training', 'Mandatory Training', 'files/1689080103.pdf', '', '2023-09-13', '11th July 2023', ''),
(634, '169325', '54', 'NRS Application Form', 'Application Documents', 'files/1689081246.pdf', '', '', '11th July 2023', ''),
(635, '169325', '54', 'NRS CV', 'CV Documents', 'files/1689081286.docx', '', '', '11th July 2023', ''),
(636, '169325', '27', 'DBS + Update Service', 'DBS Documents', 'files/1689251476.pdf', '', '2024-06-08', '13th July 2023', ''),
(637, '169325', '54', 'Passport', 'RTW Documents', 'files/1689251648.pdf', '', '2028-09-15', '13th July 2023', ''),
(638, '169325', '54', 'DBS + Update Service', 'DBS Documents', 'files/1689251713.pdf', '', '2023-09-27', '13th July 2023', ''),
(639, '169325', '54', 'POAx2', 'Proof of residence', 'files/1689251744.pdf', '', '', '13th July 2023', ''),
(640, '169325', '54', 'Mandatory Training', 'Mandatory Training', 'files/1689251825.pdf', '', '2024-05-15', '13th July 2023', ''),
(641, '169325', '55', 'Application Form', 'Application Documents', 'files/1689251931.docx', '', '', '13th July 2023', ''),
(642, '169325', '55', 'NRS CV', 'CV Documents', 'files/1689251977.docx', '', '', '13th July 2023', ''),
(643, '169325', '55', 'Passport', 'RTW Documents', 'files/1689252169.pdf', 'British Passport', '2029-01-24', '13th July 2023', ''),
(645, '169325', '55', 'POAx2', 'Proof of residence', 'files/1689252494.pdf', '', '', '13th July 2023', ''),
(654, '169325', '67', 'DBS - NRS', 'DBS Documents', 'files/1689254330.jpg', '', '2024-02-28', '13th July 2023', ''),
(656, '169325', '47', 'Mandatory Training', 'Mandatory Training', 'files/1689763731.pdf', '', '2024-06-10', '19th July 2023', ''),
(657, '169325', '149', 'Application Form', 'Application Documents', 'files/1690456508.pdf', '', '', '27th July 2023', ''),
(658, '169325', '149', 'NRS CV', 'CV Documents', 'files/1690456547.docx', '', '', '27th July 2023', ''),
(659, '169325', '149', 'Passport', 'RTW Documents', 'files/1690456896.pdf', 'Share Code -Check', '2028-01-10', '27th July 2023', ''),
(660, '169325', '149', 'DBS + Update Service', 'DBS Documents', 'files/1690458906.pdf', '', '2024-03-13', '27th July 2023', ''),
(661, '169325', '149', 'POAx2', 'Proof of residence', 'files/1690458944.pdf', '', '', '27th July 2023', ''),
(662, '169325', '149', 'Mandatory Training', 'Mandatory Training', 'files/1690459075.pdf', '', '2024-02-06', '27th July 2023', ''),
(664, '169325', '170', 'NRS Application Form', 'Application Documents', 'files/1692710820.pdf', '', '', '22nd August 2023', ''),
(665, '169325', '170', 'NRS CV', 'CV Documents', 'files/1692711184.docx', '', '', '22nd August 2023', ''),
(666, '169325', '170', 'Passport', 'RTW Documents', 'files/1692711473.pdf', '', '2032-05-07', '22nd August 2023', ''),
(667, '169325', '170', 'DBS + Update Service', 'DBS Documents', 'files/1692711645.pdf', '', '2024-01-31', '22nd August 2023', ''),
(668, '169325', '170', 'POAx2', 'Proof of residence', 'files/1692711737.pdf', '', '', '22nd August 2023', ''),
(669, '169325', '171', 'NRS Application Form', 'Application Documents', 'files/1692804168.pdf', '', '', '23rd August 2023', ''),
(670, '169325', '171', 'POAx2', 'Proof of residence', 'files/1692804269.pdf', '', '', '23rd August 2023', ''),
(671, '169325', '171', 'POAx2', 'Proof of residence', 'files/1692804271.pdf', '', '', '23rd August 2023', ''),
(672, '169325', '168', 'Application Form', 'Application Documents', 'files/1692804699.docx', '', '', '23rd August 2023', ''),
(673, '169325', '168', 'NRS CV', 'CV Documents', 'files/1692804735.docx', '', '', '23rd August 2023', ''),
(675, '169325', '1', 'EU -Passport', 'RTW Documents', 'files/1693300549.pdf', '', '2030-07-07', '29th August 2023', ''),
(676, '169325', '9', 'DBS + Update Service', 'DBS Documents', 'files/1693318455.pdf', '', '2024-07-17', '29th August 2023', ''),
(677, '169325', '9', 'BRP', 'RTW Documents', 'files/1693318558.pdf', '', '2024-11-30', '29th August 2023', ''),
(678, '169325', '22', 'DBS + Update Service', 'DBS Documents', 'files/1693318920.pdf', '', '2024-06-21', '29th August 2023', ''),
(679, '169325', '168', 'BRP', 'RTW Documents', 'files/1693319166.pdf', '', '2024-11-30', '29th August 2023', ''),
(680, '169325', '25', 'Mandatory Training', 'Mandatory Training', 'files/1693319407.pdf', '', '2024-06-15', '29th August 2023', ''),
(681, '169325', '126', 'BRP', 'RTW Documents', 'files/1693319624.pdf', '', '2024-11-30', '29th August 2023', ''),
(682, '169325', '35', 'Mandatory Training', 'Mandatory Training', 'files/1693319846.pdf', '', '2024-07-18', '29th August 2023', ''),
(683, '169325', '35', 'DBS + Update Service', 'DBS Documents', 'files/1693319926.pdf', '', '2024-07-26', '29th August 2023', ''),
(684, '169325', '105', 'DBS + Update Service', 'DBS Documents', 'files/1693321145.pdf', '', '2024-07-02', '29th August 2023', ''),
(685, '169325', '101', 'DBS + Update Service', 'DBS Documents', 'files/1693321367.pdf', '', '2024-05-30', '29th August 2023', ''),
(686, '169325', '75', 'Mandatory Training', 'Mandatory Training', 'files/1693321627.pdf', '', '2024-06-12', '29th August 2023', ''),
(687, '169325', '75', 'DBS + Update Service', 'DBS Documents', 'files/1693321712.pdf', '', '2024-07-26', '29th August 2023', ''),
(688, '169325', '120', 'DBS + Update Service', 'DBS Documents', 'files/1693322264.pdf', '', '2024-01-28', '29th August 2023', ''),
(689, '169325', '87', 'DBS + Update Service', 'DBS Documents', 'files/1693322590.pdf', '', '2024-06-23', '29th August 2023', ''),
(690, '169325', '97', 'Mandatory Training', 'Mandatory Training', 'files/1693323167.pdf', '', '2024-03-05', '29th August 2023', ''),
(691, '169325', '11', 'Child - DBS', 'DBS Documents', 'files/1693323758.pdf', '', '2024-07-13', '29th August 2023', ''),
(692, '169325', '21', 'DBS + Update Service', 'DBS Documents', 'files/1693324031.pdf', '', '2024-07-02', '29th August 2023', ''),
(694, '169325', '55', 'Manual Handling', 'Mandatory Training', 'files/1693325073.pdf', '', '2024-06-04', '29th August 2023', ''),
(695, '169325', '55', 'Health & Safety', 'Mandatory Training', 'files/1693325135.pdf', '', '2024-06-04', '29th August 2023', ''),
(696, '169325', '55', 'Fire Safety', 'Mandatory Training', 'files/1693325195.pdf', '', '2024-06-30', '29th August 2023', ''),
(697, '169325', '55', 'Medication', 'Mandatory Training', 'files/1693325327.pdf', '', '2024-06-04', '29th August 2023', ''),
(698, '169325', '55', 'First Aid', 'Mandatory Training', 'files/1693325437.pdf', '', '2024-06-05', '29th August 2023', ''),
(699, '169325', '55', 'Food Hygiene', 'Mandatory Training', 'files/1693325499.pdf', '', '2024-05-30', '29th August 2023', ''),
(700, '169325', '55', 'Infection Prevention & Control', 'Mandatory Training', 'files/1693325599.pdf', '', '2024-07-04', '29th August 2023', ''),
(701, '169325', '55', 'Safeguarding Vulnerable Adults', 'Mandatory Training', 'files/1693325671.pdf', '', '2024-06-04', '29th August 2023', ''),
(702, '169325', '170', 'Mandatory Training', 'Mandatory Training', 'files/1693326009.pdf', '', '2024-07-05', '29th August 2023', ''),
(703, '169325', '77', 'BRP', 'RTW Documents', 'files/1693488709.pdf', '', '2024-11-30', '31st August 2023', ''),
(704, '169325', '8', 'Mandatory Training', 'Mandatory Training', 'files/1694179263.pdf', '', '2024-08-06', '8th September 2023', ''),
(706, '169325', '10', 'Mandatory Training', 'Mandatory Training', 'files/1694182728.pdf', '', '2024-07-29', '8th September 2023', ''),
(707, '169325', '31', 'DBS + Update Service', 'DBS Documents', 'files/1694436668.pdf', '', '2024-07-29', '11th September 2023', ''),
(708, '169325', '72', 'DBS + Update Service', 'DBS Documents', 'files/1695993475.pdf', '', '2024-08-22', '29th September 2023', ''),
(710, '169325', '8', 'DBS - NRS', 'DBS Documents', 'files/1696001333.pdf', '', '2023-10-08', '29th September 2023', ''),
(711, '169325', '15', 'DBS + Update Service', 'DBS Documents', 'files/1696002050.pdf', '', '2024-08-27', '29th September 2023', ''),
(712, '169325', '53', 'Manual Handling', 'Mandatory Training', 'files/1696002729.pdf', '', '2024-07-17', '29th September 2023', ''),
(713, '169325', '53', 'Food Hygiene', 'Mandatory Training', 'files/1696002988.pdf', '', '2024-08-12', '29th September 2023', ''),
(714, '169325', '53', 'Fire Safety', 'Mandatory Training', 'files/1696003533.pdf', '', '2024-06-25', '29th September 2023', ''),
(715, '169325', '53', 'First Aid', 'Mandatory Training', 'files/1696003615.pdf', '', '2024-07-14', '29th September 2023', ''),
(716, '169325', '53', 'Medication', 'Mandatory Training', 'files/1696003705.pdf', '', '2024-08-12', '29th September 2023', ''),
(717, '169325', '53', 'Infection Prevention & Control', 'Mandatory Training', 'files/1696003780.pdf', '', '2024-07-07', '29th September 2023', ''),
(718, '169325', '53', 'Health & Safety', 'Mandatory Training', 'files/1696003870.pdf', '', '2024-06-24', '29th September 2023', ''),
(719, '169325', '53', 'Safeguarding Children', 'Mandatory Training', 'files/1696004005.pdf', '', '2024-07-17', '29th September 2023', ''),
(720, '169325', '53', 'Safeguarding Vulnerable Adults', 'Mandatory Training', 'files/1696004470.pdf', '', '2024-07-17', '29th September 2023', ''),
(721, '169325', '53', 'DBS + Update Service', 'DBS Documents', 'files/1696004599.pdf', '', '2024-08-26', '29th September 2023', ''),
(722, '169325', '64', 'Mandatory Training', 'Mandatory Training', 'files/1696881913.pdf', '', '2024-04-26', '9th October 2023', ''),
(723, '169325', '65', 'Mandatory Training', 'Mandatory Training', 'files/1696882835.pdf', '', '2024-08-26', '9th October 2023', ''),
(724, '169325', '64', 'Update Service Check', 'DBS Documents', 'files/1697097918.pdf', '', '2024-10-11', '12th October 2023', ''),
(725, '169325', '39', 'Training', 'Mandatory Training', 'files/lVPHbg3Kaqur.pdf', '', '', '21st February 2024', ''),
(726, '169325', '33', 'Infection & Prevention', 'Mandatory Training', 'files/e2y76M2oXqJW.pdf', '26/02/2024', '', '26th February 2024', ''),
(727, '169325', '33', 'Infection & Prevention', 'Mandatory Training', 'files/lKwiTSw0grS3.pdf', '26/02/2024', '', '26th February 2024', ''),
(728, '169325', '64', 'Dbs', 'DBS Documents', 'files/waN1W82mdIRE.pdf', '', '', '23rd May 2024', ''),
(729, '169325', '64', 'Dbs', 'DBS Documents', 'files/SqqeBQ3RpCE8.pdf', '', '', '23rd May 2024', '');

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `status` text NOT NULL,
  `app_id` text NOT NULL,
  `client_id` text NOT NULL,
  `client_name` text NOT NULL,
  `email` text NOT NULL,
  `phonenumber` text NOT NULL,
  `telephone` text NOT NULL,
  `address` text NOT NULL,
  `city` text NOT NULL,
  `postcode` text NOT NULL,
  `type` text NOT NULL,
  `company_no` text NOT NULL,
  `vat_no` text NOT NULL,
  `logo` text NOT NULL,
  `businessterm` text NOT NULL,
  `rates` text NOT NULL,
  `managername` text NOT NULL,
  `emailaddress` text NOT NULL,
  `mobilenumber` text NOT NULL,
  `createdOn` text NOT NULL,
  `createdBy` text NOT NULL,
  `time` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `clients`
--

INSERT INTO `clients` (`id`, `status`, `app_id`, `client_id`, `client_name`, `email`, `phonenumber`, `telephone`, `address`, `city`, `postcode`, `type`, `company_no`, `vat_no`, `logo`, `businessterm`, `rates`, `managername`, `emailaddress`, `mobilenumber`, `createdOn`, `createdBy`, `time`) VALUES
(1, 'Active', '169325', '7902', 'Full Of Life ', 'hr@fulloflifekc.com', '02089699993', '', '379 Ladbroke Grove', 'London ', 'W10 5BQ', 'Private', '0', '0', 'files/placeholder.jpg', '', '', 'Gill ', 'gill@fulloflifekc.com', '07951551674', '2023-05-31', '1', '13:45'),
(2, 'Active', '169325', '2344', 'Ferndale Residential Home', 'angela.walker@centralbedfordshire.gov.uk', '03003008594', '', 'Easton Road ', 'Flitwick ', 'MK45 1HB', 'VMS - Matrix', '0', '0', 'files/placeholder.jpg', '', '', 'Angela Walker ', 'angela.walker@centralbedfordshire.gov.uk', '03003005071', '2023-05-31', '1', '14:35'),
(3, 'Active', '169325', '9954', 'Linsell House - Central Bedfordshire ', 'Dawn.Jenner@centralbedfordshire.gov.uk', '0300 300 5684 ', '', 'Ridgeway Avenue, Dunstable LU5 4QJ ', 'Bedfordshire', 'LU5 4QJ ', 'VMS - Matrix', '0', '0', 'files/placeholder.jpg', '', '', 'Charlotte Bond', 'Charlotte.Bond@centralbedfordshire.gov.uk', '0300 300 5684 ', '2023-05-31', '9', '15:24'),
(4, 'Targeting', '169325', '6416', 'Watcombe Circus - Nottingham Council Housing Association', 'Theresa.Taylor@ncha.org.uk', '0115 9609592', '', '2-4, Watcombe Circus, Nottingham NG5 2DT', 'Nottingham', 'NG5 2DT', 'VMS - Matrix', '0', '0', 'files/placeholder.jpg', '', '', 'Theresa Taylor', 'Theresa.Taylor@ncha.org.uk', '0115 9609592', '2023-05-31', '9', '15:29'),
(5, 'Targeting', '169325', '1706', 'Orchard Close - Nottingham Community Housing Association', 'Julia.Watkinson@ncha.org.uk', '01159680525 ', '', '2-8 Orchard Street, Hucknall, Nottinghamshire, NG15 7JZ', 'Nottingham', 'NG15 7JZ', 'VMS - Matrix', '0', '0', 'files/placeholder.jpg', '', '', 'Julia Watkinson', 'Julia.Watkinson@ncha.org.uk', '0115 9680525', '2023-05-31', '9', '15:35'),
(6, 'inactive', '169325', '5149', 'Allison House Residential Home', 'Naomi.Ransford@centralbedfordshire.gov.uk', '0300 300 8591', '', 'Allison House Swan Lane, Sandy SG19 1NE ', 'Sandy', 'SG19 1NE ', 'VMS - Matrix', '0', '0', 'files/placeholder.jpg', '', '', 'Geraldine Smith', 'Geraldine.Smith@centralbedfordshire.gov.uk', '0300 300 4415', '2023-05-31', '9', '15:39'),
(7, 'inactive', '169325', '8958', 'Evergreen Unit - Central Bedfordshire', 'Sarah.Milton@centralbedfordshire.gov.uk', '0300 300 6091', '', 'EVERGREEN SUSD UNIT Wingfield Court Ampthill MK45 2TE', 'Bedfordshire', 'MK45 2TE', 'VMS - Matrix', '0', '0', 'files/placeholder.jpg', '', '', 'Sarah Milton', 'Sarah.Milton@centralbedfordshire.gov.uk', '0300 300 6091', '2023-05-31', '9', '15:54'),
(8, 'Active', '169325', '6241', 'Breakaway Short Breaks', 'Martin.Nsubuga@camden.gov.uk', '02079745812 / 07771666871', '', '120 Rowley Way ', 'London', 'NW8 0SP', 'VMS - Matrix', '0', '0', 'files/placeholder.jpg', 'files/1685545528.docx', 'files/194279.docx', 'Manager Nsubuga', 'Martin.Nsubuga@camden.gov.uk', '07771666871', '2023-05-31', '10', '16:05'),
(9, 'Active', '169325', '2729', 'Charlie Ratchford', 'Laurie.Armantrading@camden.gov.uk', '020 7974 3740', '', 'Charlie Ratchford Court, 43 Crogsland Rd, Chalk Farm', 'London', 'NW1 8FA', 'VMS - Matrix', '0', '0', 'files/placeholder.jpg', 'files/1685545696.docx', 'files/835006.docx', 'Laurie Armantrading', 'Laurie.Armantrading@camden.gov.uk', '07971177814', '2023-05-31', '10', '16:08'),
(10, 'Active', '169325', '6751', 'The Green Bank House', 'Fiona.Armstrong@barnet.gov.uk', '020 8359 3384', '', '27 Woodside Avenue', 'London', 'N12 8AT', 'VMS - Matrix', '0', '0', 'files/placeholder.jpg', 'files/1685545934.docx', 'files/833515.docx', 'Sophie Nzenwa', 'Sophie.Nzenwa@Barnet.gov.uk', '07931177926', '2023-05-31', '10', '16:12'),
(11, 'Active', '169325', '7216', 'Hatton Grove', 'THamer@Hillingdon.gov.uk', ' 01895 556989', '', '4 Hatton Grove', 'London', 'UB7 7AU', 'VMS - Matrix', '0', '0', 'files/placeholder.jpg', 'files/1685546128.docx', 'files/133196.docx', 'Tracie Hamer', 'THamer@Hillingdon.Gov.UK', ' 01895 556989', '2023-05-31', '10', '16:15'),
(12, 'Active', '169325', '8929', '68a Meadows Close', 'Dionne.Bulcock@barnet.gov.uk', '020 8359 3389', '', 'Dollis Valley Way, Barnet', 'London', ' EN5 2UF ', 'VMS - Matrix', '0', '0', 'files/placeholder.jpg', 'files/1685546326.docx', 'files/287933.docx', 'Dionne Bulcock', 'Dionne.Bulcock@barnet.gov.uk', '020 8359 3389', '2023-05-31', '10', '16:18'),
(13, 'Active', '169325', '1111', 'New Park House', 'Jane.Stevenson@barnet.gov.uk', '020 8359 3474/0', '', 'New Park House, 25 Parkhurst Road,  New Southgate', 'London', 'N11 3EN', 'VMS - Matrix', '0', '0', 'files/placeholder.jpg', 'files/1685546501.docx', 'files/145058.docx', 'Sophie Nzenwa', 'Sophie.Nzenwa@Barnet.gov.uk', '07931177926', '2023-05-31', '10', '16:21'),
(14, 'Active', '169325', '2719', 'Arlington', 'Ronald.Onyango@islington.gov.uk', '020 7527 1811', '', '14a Arlington Square ', 'London ', 'N1 7FS', 'VMS - Matrix', '0', '0', 'files/placeholder.jpg', '', '', 'Ronald ', 'Onyango ', '07816 116655', '2023-05-31', '11', '17:06'),
(15, 'inactive', '169325', '2204', 'New Options ', 'andrew.rossides@enfield.gov.uk', '020 8363 6388', '', '25 Connop Road', 'Enfield ', 'EN3 5FB', 'VMS - Matrix', '0', '0', 'files/placeholder.jpg', '', '', 'Simon Rahman', 'simon.rahman@enfield.gov.uk', '020 8379 5359', '2023-05-31', '11', '17:10'),
(16, 'Active', '169325', '9619', 'Formont ', 'stavros.costi@enfield.gov.uk', '020 8363 6388', '', 'Waverley Road', 'Enfield ', 'EN2 7BP', 'VMS - Matrix', '0', '0', 'files/placeholder.jpg', '', '', 'Shard Madhewoo ', 'shard.madhewoo@enfield.gov.uk', '07985 281407', '2023-05-31', '11', '17:12'),
(17, 'Active', '169326', '4153', 'Michael Musenge', 'musengemichaeljr@gmail.com', '0777504081', '', 'New Chalala', 'Lusaka', '10101', 'Private Company', 'Michael Musenge', '12345', 'files/placeholder.jpg', '', '', 'Mike', 'musengemichaeljr@gmail.com', '0777504081', '2023-06-03', '2', '21:24'),
(18, 'inactive', '1505a0227dd363c4c6478e86198e6c3b', '7256', 'Highbury New Park Day Centre', 'Susan.O\'dell@islington.gov.uk', '020 7704 3731', '', '127 Highbury New Park', 'London', 'N5 2DS', 'VMS - Matrix', '1', '1', 'files/placeholder.jpg', 'files/1687342064.docx', 'files/840038.docx', 'Annoulla Loizou', 'Annoulla.Loizou@islington.gov.uk', '07971064606', '2023-06-21', '12', '11:07'),
(19, 'inactive', '169325', '4857', 'Highbury New Park Day Centre', 'Susan.O\'dell@islington.gov.uk', '020 7704 3731', '', '127 Highbury New Park', 'London', 'N5 2DS', 'VMS - Matrix', '1', '1', 'files/placeholder.jpg', 'files/1687342323.docx', 'files/778747.docx', 'Annoulla Loizou', 'Annoulla.Loizou@islington.gov.uk', '07971064606', '2023-06-21', '10', '11:12'),
(20, 'Active', '169325', '2947', 'The Markhouse Centre', 'Saima.Mehmood@walthamforest.gov.uk', '020 8496 2710', '', '247 Markhouse Road, Waltham Forest', 'London', 'E17 8DW', 'VMS - Matrix', '.', '.', 'files/placeholder.jpg', '', '', 'Saima Mehmood', 'Saima.Mehmood@walthamforest.gov.uk', '020 8496 2710', '2023-06-26', '9', '16:20'),
(21, 'Active', '169325', '4112', 'Daylight', 'Lakhvinder.Bhogal@islington.gov.uk', '07584370840', '', '14-16 Highbury Grove, Islington', 'London', 'N5 2EA', 'VMS - Matrix', '.', '.', 'files/placeholder.jpg', '', '', 'Lakhvinder Bhogal', 'Lakhvinder.Bhogal@islington.gov.uk', '07584370840', '2023-06-26', '9', '16:37'),
(22, 'Active', '169325', '4244', 'Westlands', 'Aderonke.Eyeowa@centralbedfordshire.gov.uk', '0300 300 8596', '', 'Duncombe Dr, Leighton Buzzard', 'Bedfordshire', ' LU7 1SD', 'VMS', '1', '1', 'files/placeholder.jpg', '', '', 'Anna Maria', 'Anna-Maria.Johnson-Brown@centralbedfordshire.gov.uk', '0300 300 8596', '2023-07-26', '9', '09:17'),
(23, 'Targeting', '169325', '2042', 'Chapel Lane - Hillingdon Council', 'hakerman@hillingdon.gov.uk', '01895 557762', '', 'Chapel Lane', 'London', 'UB8 3DS', 'VMS', '1', '1', 'files/placeholder.jpg', '', '', 'Hannah Akerman', 'hakerman@hillingdon.gov.uk', '01895 557762', '2023-07-26', '9', '09:55'),
(24, 'Targeting', '169325', '7640', '3 Merrimans - Hillingdon Council', 'bwhite1@hillingdon.gov.uk', '01895 277584', '', 'West Drayton Road', 'London', 'UB8 3JZ', 'VMS - Matrix', '1', '1', 'files/placeholder.jpg', '', '', 'Barbra White - Admin', 'bwhite1@hillingdon.gov.uk', '01895 277584', '2023-07-26', '9', '10:00'),
(25, 'Targeting', '169325', '2354', 'Heritage - The Edwardian', 'edwardian@heritagecarehomes.co.uk', '01582 705100', '', '168 Biscot Road', 'Luton', 'LU3 1AX', 'Private', '1', '1', 'files/placeholder.jpg', '', '', 'May Puthoor', 'edwardian@heritagecarehomes.co.uk', '01582 705100', '2023-07-26', '9', '10:04'),
(26, 'Targeting', '169325', '9923', 'Mulberry House', 'sergio.completecare@outlook.com', '01582570569', '', '120 Barton Road', 'Luton', 'LU3 2BD', 'Private', '1', '1', 'files/placeholder.jpg', '', '', 'Sergio', 'sergio.completecare@outlook.com', '01582570569', '2023-07-26', '9', '10:10'),
(27, 'Targeting', '169325', '1108', 'Queen Ann House', 'careteam@queenanncare.com', '020 8920 3340', '', '40-42 Old Park Road', 'London', 'N13 4RE', 'Private', '1', '1', 'files/placeholder.jpg', '', '', 'Anita - Admin', 'careteam@queenanncare.com', '020 8920 3340', '2023-07-26', '9', '10:23'),
(28, 'Targeting', '169325', '7119', 'Unified Care ', 'shamir@unifiedcare.co.uk', '08007720925', '', '37 Coleraine Road', 'London', 'N8 0QJ', 'Private', '1', '1', 'files/placeholder.jpg', '', '', 'Shamir', 'shamir@unifiedcare.co.uk', '08007720925', '2023-07-26', '9', '10:26'),
(29, 'inactive', '169325', '4949', 'Greenwood Centre', 'c.thomas@ldnlondon.org', '02082065925', '', '37 Greenwood Pl', 'London', 'NW5 1LB', 'Private', '1', '1', 'files/placeholder.jpg', '', '', 'Chantelle', 'c.thomas@ldnlondon.org', '02082065925', '2023-07-26', '9', '10:37'),
(30, 'Targeting', '169325', '6826', 'LDN London', 'jhilton@ldnlondon.org', '02074833757', '', '22A Ainger Road', 'London', 'NW1 8HX', 'Private', '1', '1', 'files/placeholder.jpg', '', '', 'Jennifer Hilton', 'jhilton@ldnlondon.org', '02074833757', '2023-07-26', '9', '10:58'),
(31, 'Targeting', '169325', '4943', 'Shirland Road - St Mungo\'s', 'Caroline.Nzegbulem@mungos.org', '02072660161', '', '93-95 Shirland Road', 'London', 'W9 2EL', 'Private', '1', '1', 'files/placeholder.jpg', '', '', 'Caroline Nzegbulem', 'Caroline.Nzegbulem@mungos.org', '02072660161', '2023-07-26', '9', '11:12'),
(32, 'inactive', '169325', '1049', 'LQ Living - Helena Road', 'Norah.Matsie-ssonko@lqliving.co.uk', '02084701382', '', '2c-d Helena Road', 'London', 'E13 0DU', 'VMS - Matrix', '1', '1', 'files/placeholder.jpg', '', '', 'Norah Matsie-ssonko', 'Norah.Matsie-ssonko@lqliving.co.uk', '02084701382', '2023-07-26', '9', '15:50'),
(33, 'inactive', '169325', '2023', 'LDN 4 U - Queensland Road', 'dwallace@ldnlondon.org', '02076078993', '', '7 paxton Court', 'Islington', 'N7 8AF', 'Private', '1', '1', 'files/placeholder.jpg', '', '', 'Dominica Wallace', 'dwallace@ldnlondon.org', '02076078993', '2023-08-01', '9', '15:41'),
(34, 'inactive', '169325', '1427', 'LQLiving - Woodview Court', 'sempela.kaulu@lq-living.co.uk', '0208 189 4618 ', '', 'Flat 6, 199 Wood Street, ', 'London', 'E17 3NU', 'VMS - Matrix', '1', '1', 'files/placeholder.jpg', '', '', 'Sempala Kaur', 'sempela.kaulu@lq-living.co.uk', '0208 189 4618 ', '2023-08-04', '9', '11:46'),
(35, 'inactive', '169325', '1308', 'Abbottsford Residential Home - Ruislip Care Homes', 'tajreaz.cader@nhs.net', '020 8866 0921', '', '53 Moss Lane Pinner ', 'London', ' HA5 3AZ', 'Private', '1', '1', 'files/placeholder.jpg', '', '', '.', 'tajreaz.cader@nhs.net', '020 8866 0921', '2023-08-07', '9', '15:11'),
(36, 'inactive', '169325', '7743', 'The Boyne Residential Care Home', 'care@ruislipcarehomes.co.uk', '01895 621 732', '', '38 Park Way, Ruislip, Middlesex', 'London', 'HA4 8NU', 'Private', '1', '1', 'files/placeholder.jpg', '', '', 'The Boyne Residential Care Home', 'care@ruislipcarehomes.co.uk', '01895 623 118', '2023-08-07', '9', '15:17'),
(37, 'inactive', '169325', '9948', 'Poplars Residential Care Home', 'care@ruislipcarehomes.co.uk', '01895 635 284', '', '15-17 Ickenham Road Ruislip', 'London', 'HA4 7BZ', 'Private', '1', '1', 'files/placeholder.jpg', '', '', 'Poplars Residential Care Home', 'care@ruislipcarehomes.co.ukcare@ruislipcarehomes.co.uk', '01895 635 284', '2023-08-07', '9', '15:20'),
(38, 'inactive', '169325', '4354', 'Primrose House Nursing Home', 'care@ruislipcarehomes.co.uk', '020 8954 4442', '', '765-767 Kenton Lane Harrow, Middlesex', 'London', 'HA3 6AH', 'Private', '1', '1', 'files/placeholder.jpg', '', '', 'Primrose House Nursing Home', 'care@ruislipcarehomes@co.uk', '020 8954 4442', '2023-08-07', '9', '15:24'),
(39, 'inactive', '169325', '1522', 'Ruislip  Nursing Home', 'care@ruislipcarehomes', '01895 676 442', '', '173 West End Road Ruislip', 'London', 'HA4 6LB', 'Private', '1', '1', 'files/placeholder.jpg', '', '', 'Ruislip  Nursing Home', 'care@ruislipcarehomes', '01895 676 442', '2023-08-07', '9', '15:40'),
(40, 'inactive', '169325', '1458', 'Pinkwell Primary School', 'pink@lane.co', '020 85732199', '', 'Pinkwell Lane, Hayes', 'London', 'UB3 1PG', 'VMS - Matrix', '1', '1', 'files/placeholder.jpg', '', '', 'Pinkwell Primary School', '.', '020 85732199', '2023-08-07', '9', '15:51'),
(41, 'inactive', '169325', '2727', 'Marlin Lodge', 'unknown@marlin.com', ' 01582 723495', '', '31 Marlborough Rd', 'Luton', 'LU3 1EF', 'Private', '1', '1', 'files/placeholder.jpg', '', '', 'Marlin Lodge', '@marlin.com', ' 01582 723495', '2023-08-07', '9', '15:56'),
(42, 'inactive', '169325', '7156', 'Belle Vue', 'thehomemanager@outlook.com', '07814966062', '', '123 New Bedford Rd ', 'Luton', ' LU3 1LF', 'Private', '1', '1', 'files/placeholder.jpg', '', '', 'Belle Vue', 'thehomemanager@outlook.com', '07814966062', '2023-08-07', '9', '16:03'),
(43, 'inactive', '169325', '7143', 'Castletroy Residential Home', 'castletroy@btconnect.com', '01582 417995', '', '130 Cromer Way Bushmead ', 'Luton', 'LU2 7GP', 'Private', '1', '1', 'files/placeholder.jpg', '', '', 'Castletroy Residential Home', 'castletroy@btconnect.com', '01582 417995', '2023-08-07', '9', '16:08'),
(44, 'inactive', '169325', '6310', 'Mulberry House', 'info@completecare.org.uk', '01582 570569', '', '120 Barton Road', 'Luton', 'LU3 2BD', 'Private', '1', '1', 'files/placeholder.jpg', '', '', 'Kris Hurry', 'info@completecare.org.uk', '01582 570569', '2023-08-07', '9', '16:12'),
(45, 'inactive', '169325', '5256', 'Zakia', 'info@theenableproject.co.uk', '07523 634907', '', '110 Butterfield, Great Marlings ', 'Luton ', 'LU2 8DL', 'Private', '1', '1', 'files/placeholder.jpg', '', '', 'Jackie Leslie', 'info@theenableproject.co.uk', '07523 634907', '2023-08-07', '9', '16:16'),
(46, 'inactive', '169325', '10047', ' St Theresa\'s Rest Home', 'unknown@resthome.com', '020 8360 6272', '', '6-8 Queen Annes Gardens', 'Enfield', 'EN1 2JN', 'Private', '1', '1', 'files/placeholder.jpg', '', '', 'Mr Paul and Mrs Paul', 'unknown@resthome.com', '020 8360 6272', '2023-08-07', '9', '16:22'),
(47, 'inactive', '169325', '3505', 'Twinglobe - Azeala Court', 'info@twinglobe.com', '020 8370 1750', '', 'Azalea Court, 58 Abbey Road, Bush Hill Park', 'Enfield', 'EN1 2QN', 'Private', '1', '1', 'files/placeholder.jpg', '', '', '.', 'info@twinglobe.com', '020 8370 1750', '2023-08-07', '9', '16:27'),
(48, 'inactive', '169325', '8904', 'Twinglobe - Willows Carehome', 'info@twinglobe.com', '020 8370 1750', '', '58 Abbey Road, Bush Hill Park', 'Enfield', '020 8370 1750', 'Private', '1', '1', 'files/placeholder.jpg', '', '', '.', 'info@twinglobe.com', '020 8370 1750', '2023-08-07', '9', '16:37'),
(49, 'inactive', '169325', '8709', 'Saivan Care Services - Henley Lodge', 'unknown@henley', '020 8090 9042', '', '28 Hyde Way', 'Enfield', 'N9 9RT', 'Private', '1', '1', 'files/placeholder.jpg', '', '', ' Faezeh Khodaverdy (Manager)', '.', '020 8090 9042', '2023-08-07', '9', '16:46'),
(50, 'inactive', '169325', '5245', 'Saivan Care Services Ltd - Saivi House', 'unknown@saiv.com', '020 8245 7212', '', '39 Doveridge Gardens', 'Palmers Green, London', 'N13 5BJ', 'Private', '1', '1', 'files/placeholder.jpg', '', '', 'Sanjaye Nath Ramsaha (Manager)', '.', '020 8245 7212', '2023-08-07', '9', '16:50'),
(51, 'inactive', '169325', '8825', 'Saivan Care Services Ltd - Keevan Lodge', 'unknown@saivan', '020 8367 0441', '', '98 Clive Road', 'Enfield ', 'EN1 1RF', 'Private', '1', '1', 'files/placeholder.jpg', '', '', 'Faezeh Khodaverdy (Manager)', '.', '020 8367 0441', '2023-08-07', '9', '16:53'),
(52, 'inactive', '169325', '8008', 'Saivan Care Ltd -  Kellan Lodge', 'unknown@saivan', '020 8363 5398', '', '24 Little Park Gardens', 'London', 'EN2 6PG', 'Private', '1', '1', 'files/placeholder.jpg', '', '', 'Faezeh Khodaverdy (Manager)', '.', '020 8363 5398', '2023-08-07', '9', '16:55'),
(53, 'inactive', '169325', '9601', 'Reamcare', 'info@reamcare.co.uk', '0208 224 3495', '', '100 Thorkhill Road', 'Surrey', 'KT7 0UW', 'Private', '1', '1', 'files/placeholder.jpg', '', '', 'Rayman', 'info@reamcare.co.uk ', '0208 224 3495', '2023-08-08', '9', '16:36'),
(54, 'inactive', '169325', '8218', 'Lukka Care Homes - Acorn Lodge', 'info@lukkahomes.com', '0208 346 9235', '', '15 Atherden Road Hackney ', 'London', 'E5 0QP', 'Private', '1', '1', 'files/placeholder.jpg', '', '', '.', 'acornlodge@lukkahomes.com', '0208 533 9555 - home line', '2023-08-08', '9', '16:45'),
(55, 'inactive', '169325', '9331', 'Lukka Care Home - Albany Road', 'info@lukkahomes.com', '0208 346 9235', '', '11/12 Albany Road', 'London ', 'E10 7EL', 'Private', '1', '1', 'files/placeholder.jpg', '', '', '.', 'Albany@lukkahomes.com', '02085567242', '2023-08-08', '9', '16:56'),
(56, 'inactive', '169325', '4405', 'Lukka Care Homes - Ashton Lodge Care Home', 'info@lukkahomes.com', '0208 346 9235', '', '95 The Hyde', 'London', 'NW9 6LE', 'Private', '1', '1', 'files/placeholder.jpg', '', '', '.', 'ashtonlodge@lukkahomes.com', '020 8732 7260', '2023-08-08', '9', '17:04'),
(57, 'inactive', '', '2550', 'Lukka Care Homes - Mornington Hall', 'info@lukkahomes.com', '0208 346 9235', '', '76 Whitta Road, Manor Park, London E12 5DA', 'London ', 'E12 5DA', 'Private', '1', '1', 'files/placeholder.jpg', '', '', '.', 'morningtonhall@lukkahomes.com', '020 4599 0480', '2023-08-08', '', '18:21'),
(58, 'inactive', '169325', '6011', 'Lukka Care Home - Mornington Hall', 'info@lukkahomes.com', '0208 346 9235', '', '76 Whitta Road', 'London', ' E12 5DA', 'Private', '1', '1', 'files/placeholder.jpg', '', '', '.', 'morningtonhall@lukkahomes.com', '020 4599 0480', '2023-08-09', '9', '09:15'),
(59, 'inactive', '169325', '3427', 'Lukka Care Homes - Ravenscourt Care Home', 'info@lukkahomes.com', '0208 346 9235', '', '111/113 Station Lane ', ' Hornchurch', 'RM12 6HT', 'Private', '1', '1', 'files/placeholder.jpg', '', '', '.', 'ravenscourt@lukkahomes.com', '01708 454715', '2023-08-09', '9', '09:18'),
(60, 'inactive', '169325', '3321', 'Lukka Care Homes -Summerdale Court Care Home', 'info@lukkahomes.com', '0208 346 9235', '', '73 Butchers Road, Newham, London E16 1PH', 'London', ' E16 1PH', 'Private', '1', '1', 'files/placeholder.jpg', '', '', '.', 'summerdale@lukkahomes.com', '020 7540 2200', '2023-08-09', '9', '09:22'),
(61, 'inactive', '169325', '5051', 'Mapleton Road Home', 'christina.adamu@walthamforest.gov.uk', '020 8529 2266', '', '87 Mapleton Road', 'London', ' E4 6XJ', 'VMS - Matrix', '1', '1', 'files/placeholder.jpg', '', '', 'christina.adamu@walthamforest.gov.uk', 'christina.adamu@walthamforest.gov.uk', '020 8529 2266', '2023-08-09', '9', '09:36'),
(62, 'inactive', '169325', '7153', 'Salisbury Support 4 Autism', 'info@ss4autism.com', '0800 368 9433', '', 'Liddall House, 66 Albert Road', 'West Drayton', 'UB7 8ES', 'Private', '1', '1', 'files/placeholder.jpg', '', '', 'Melanie Amaral', 'melanie@ss4autism.com', '07507351852', '2023-08-09', '9', '10:43'),
(63, 'inactive', '169325', '4220', 'Halliwell Homes', '.recruitment@halliwellhomes.co.uk', '01614379491', '', '.', '.', '.', 'Private', '1', '1', 'files/placeholder.jpg', '', '', 'David.preston', 'David.preston@halliwellhomes.co.uk', '01614379491', '2023-08-10', '9', '16:42'),
(64, 'inactive', '169325', '2411', 'St Johns College', 'megan.birch@st-johns.co.uk', '01273 244048', '', '.', '.', '.', 'Private', '1', '1', 'files/placeholder.jpg', '', '', '.', 'recruitment@st-johns.co.uk', '01273 244000', '2023-08-10', '9', '16:44'),
(65, 'inactive', '169325', '2828', 'Camphill Village Trust', 'recruitment@cvt.org.uk', '03316 308282', '', '.', '.', '.', 'Private', '1', '1', 'files/placeholder.jpg', '', '', '.', 'Yasmin.Howe@cvt.org.uk/ cvtcentralhr@cvt.org.uk', '03316 308282', '2023-08-10', '9', '16:45'),
(66, 'inactive', '169325', '1353', 'People In Action', 'admin@people-in-action.co.uk', '02476643776', '', '.', '.', '.', 'Private', '1', '1', 'files/placeholder.jpg', '', '', 'Amy', 'admin@people-in-action.co.uk', '02476643776', '2023-08-10', '9', '16:47'),
(67, 'inactive', '169325', '8109', 'Jamores Ltd', 'recruitment@jamores.co.uk', '07412 238 370', '', '.', '.', '.', 'Private', '1', '1', 'files/placeholder.jpg', '', '', 'Benedict', 'referrals@jamores.co.uk/recruitment@jamores.co.uk', '07412 238 370', '2023-08-10', '9', '16:48'),
(68, 'inactive', '169325', '7254', 'Julee Care ', 'info@juleecare.co.uk', '07576850731', '', '.', '.', '.', 'Private', '1', '1', 'files/placeholder.jpg', '', '', 'Christy', 'info@juleecare.co.uk', '01582 271361', '2023-08-10', '9', '16:55'),
(69, 'inactive', '169325', '3333', 'S$', 'info@ss4autism.com', '0800 368 9433', '', '.', '.', '.', 'Private', '1', '1', 'files/placeholder.jpg', '', '', 'Melanie', 'info@ss4autism.com', '0800 368 9433', '2023-08-10', '9', '16:57'),
(70, 'inactive', '169325', '6433', 'Dimensions', '.@com', '0300 303 9150', '', '.', '.', '.', 'Private', '1', '1', 'files/placeholder.jpg', '', '', '.', '.', '0300 303 9001', '2023-08-10', '9', '17:10'),
(71, 'inactive', '169325', '2557', 'Creative Support', 'recruitment@creativesupport.co.uk', '0161 236 0829', '', 'Wellington House 131 Wellington Road South', 'Stockport', 'SK1 3TS', 'Private', '1', '1', 'files/placeholder.jpg', '', '', '.', 'recruitment@creativesupport.co.uk', '0161 236 0829', '2023-08-10', '9', '17:26'),
(72, 'inactive', '169325', '7035', 'Turning Point', 'info@com', '020 7481 7632', '', '.', '.', '.', 'Private', '1', '1', 'files/placeholder.jpg', '', '', '.', '.', '07786 938 601 (Out of hours)', '2023-08-10', '9', '17:41'),
(73, 'inactive', '169325', '5343', 'National Autistic Society', 'nas@nas.org.uk', '0207 833 2299', '', '.', '.', '.', 'Private', '1', '1', 'files/placeholder.jpg', '', '', '.', 'nas@nas.org.uk', '0207 833 2299', '2023-08-10', '9', '17:48'),
(74, 'inactive', '169325', '4150', 'Borough Care', 'enquiries@boroughcare.org.uk', '0161 475 0140', '', '9 Acorn Business Park Heaton Lane', 'Stockport', 'SK4 1AS', 'Private', '1', '1', 'files/placeholder.jpg', '', '', '.', ' enquiries@boroughcare.org.uk', '0161 475 0140', '2023-08-10', '9', '17:55'),
(75, 'inactive', '169325', '5946', 'Choice Care Group', 'enquiries@choicecaregroup.com', '0203 195 0151', '', '.', '.', '.', 'Private', '1', '1', 'files/placeholder.jpg', '', '', '.', 'careers@choicecaregroup.com', ' 0203 195 0151', '2023-08-10', '9', '18:09'),
(76, 'inactive', '169325', '8912', 'Disabilities Trust', 'recruitment@thedtgroup.org', '03300 581882', '', '.', '.', '.', 'Private', '1', '1', 'files/placeholder.jpg', '', '', '.', 'recruitment@thedtgroup.org', '03300 581882', '2023-08-10', '9', '18:25'),
(77, 'inactive', '169325', '4248', 'Kisharon', 'info@kisharon.org.uk', '020 3209 1160', '', '1st Floor, 333 Edgware Road', 'London ', 'NW9 6TD', 'Private', '1', '1', 'files/placeholder.jpg', '', '', '.', 'recruitment@kisharon.org.uk.', '020 3209 1160', '2023-08-10', '9', '18:30'),
(78, 'Inactive', '169325', '6256', 'Leyton Green Road', 'raul.jaque@walthamforest.gov.uk', '07767 162440', '07771 159183', '99a Leyton Green Road', 'London', 'E10 6DB', 'VMS', '', '', '', '', '', 'Jennifer Elias', 'jennifer.elias@walthamforest.gov.uk', '07771 159183', '2024-02-09 14:28:46', '11', '14:28'),
(79, 'Inactive', '169325', '3350', 'Alliston House Care Home', 'michael.olaniyan@waltham-forest.gov.uk', '020 8520 4984', '020 8520 4984', '45 Church Hill Road', 'London', 'E17 9RX', 'VMS', '', '', '', '', '', 'Michael Olaniyan', 'michael.olaniyan@waltham-forest.gov.uk', '020 8520 4984', '2024-02-13 16:15:30', '11', '16:15'),
(80, 'Inactive', '169325', '5753', 'MTVH', 'MTVH', '0115 ', 'MTVH', 'MTVH', 'MTVH', 'MTVH', 'Private', '', '', '', '', '', 'MTVH', 'MTVH', 'MTVH', '2024-02-22 14:08:24', '11', '14:08');

-- --------------------------------------------------------

--
-- Table structure for table `clientype`
--

CREATE TABLE `clientype` (
  `id` int(11) NOT NULL,
  `app_id` text NOT NULL,
  `name` text NOT NULL,
  `createdBy` text NOT NULL,
  `createdOn` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `clientype`
--

INSERT INTO `clientype` (`id`, `app_id`, `name`, `createdBy`, `createdOn`) VALUES
(1, '169326', 'Private Company', 'Andie  ', '9th May 2023'),
(2, '169325', 'Private', 'Chax  Shamwana', '13th May 2023'),
(7, '169325', 'VMS', 'Chax  Shamwana', '16th May 2023 23:54'),
(10, '169325', '1-1 Client', 'Chax  Shamwana', '17th May 2023 00:06'),
(11, '169325', 'VMS - Matrix', 'Chax  Shamwana', '18th May 2023 22:06'),
(12, '950862c44989d6795534f8415257b08a', 'private', 'J  Musenge', '19th May 2023 15:19'),
(13, '169325', 'VMS - Comensura', 'Chax  Shamwana', '31st May 2023 15:38');

-- --------------------------------------------------------

--
-- Table structure for table `client_call_logs`
--

CREATE TABLE `client_call_logs` (
  `id` int(11) NOT NULL,
  `app_id` text NOT NULL,
  `client` text NOT NULL,
  `type` text NOT NULL,
  `description` text NOT NULL,
  `date` text NOT NULL,
  `color` text NOT NULL,
  `createdBy` text NOT NULL,
  `createdOn` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `client_call_logs`
--

INSERT INTO `client_call_logs` (`id`, `app_id`, `client`, `type`, `description`, `date`, `color`, `createdBy`, `createdOn`) VALUES
(1, '169325', '4', 'call', 'gill needs 5 more people next Tuesday ', '13th May 2023 00:41', '#6610f2', '1', '13th May 2023 00:41'),
(2, '169325', '5', 'MEETING ', 'went for safeguarding meeting with Gill', '13th May 2023 00:51', '#6610f2', '1', '13th May 2023 00:51'),
(3, '169325', '4', 'Phone Call', 'cancelled Ade for Wednesday 23rd ', '17th May 2023 00:40', '#ffc107', '1', '17th May 2023 00:40'),
(4, '169325', '4', 'hi', 'hello ', '17th May 2023 00:41', '#6f42c1', '1', '17th May 2023 00:41'),
(5, '169325', '4', 'hkj', 'k', '17th May 2023 00:41', 'red', '1', '17th May 2023 00:41'),
(6, '169325', '4', 'g', 'm', '17th May 2023 00:42', '#007bff', '1', '17th May 2023 00:42'),
(7, '169325', '4', 'hy ', 'jpl ', '17th May 2023 00:46', '#ffc107', '1', '17th May 2023 00:46'),
(8, '169325', '6', 'phone', 'cancelled shift', '19th May 2023 10:22', '#ffc107', '1', '19th May 2023 10:22'),
(9, '169325', '15', 'call', 'no need for new workers', '2024-01-15 00:35:30', 'white', '1', '2024-01-15 00:35:30'),
(10, '169325', '15', 'call', 'no need for new workers', '2024-01-15 00:37:54', 'white', '1', '2024-01-15 00:37:54'),
(11, '169325', '15', 'call', 'no need for new workers', '2024-01-15 00:38:15', 'white', '1', '2024-01-15 00:38:15'),
(12, '169325', '15', 'call', 'no need for new workers', '2024-01-15 00:38:40', 'white', '1', '2024-01-15 00:38:40'),
(13, '169325', '15', 'call', 'no need for new workers', '2024-01-15 00:38:43', 'white', '1', '2024-01-15 00:38:43'),
(14, '169325', '15', 'call', 'no need for new workers', '2024-01-15 00:39:04', 'white', '1', '2024-01-15 00:39:04'),
(15, '169325', '15', 'call', 'no need for new workers', '2024-01-15 00:39:17', 'white', '1', '2024-01-15 00:39:17'),
(16, '169325', '15', 'call', 'no need for new workers', '2024-01-15 00:39:17', 'white', '1', '2024-01-15 00:39:17');

-- --------------------------------------------------------

--
-- Table structure for table `communicationlogs`
--

CREATE TABLE `communicationlogs` (
  `id` int(11) NOT NULL,
  `recipients` text NOT NULL,
  `type` text NOT NULL,
  `category` text NOT NULL,
  `content` text NOT NULL,
  `createdby` text NOT NULL,
  `createdon` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `communicationlogs`
--

INSERT INTO `communicationlogs` (`id`, `recipients`, `type`, `category`, `content`, `createdby`, `createdon`) VALUES
(1, '50', 'candidate', 'Samsung Phones', 'Hello', '1', '2024-01-22 11:59:18'),
(2, '64', 'candidate', 'call', 'checked availability ', '1', '2024-01-25 12:56:11'),
(3, '64', 'candidate', 'call ', 'spoke about service requirement and booked in for an induction that ', '1', '2024-01-25 12:57:14'),
(4, '64', 'candidate', 'Call', 'Right to work documents expiry soon asked for consent to carry out ECS', '3', '2024-02-26 14:05:30'),
(5, '55', 'candidate', 'Call', 'DBS Renewal Reminder', '3', '2024-05-09 12:15:52'),
(6, '167', 'candidate', 'Workplace Banned ', 'Banned from working at St Quinton\'s ', '13', '2024-07-08 14:20:14'),
(7, '218', 'candidate', 'Workplace Banned ', 'Banned from working at Full of Life ', '13', '2024-07-08 14:21:38');

-- --------------------------------------------------------

--
-- Table structure for table `connected_app`
--

CREATE TABLE `connected_app` (
  `id` int(11) NOT NULL,
  `userid` text NOT NULL,
  `app` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `connected_app`
--

INSERT INTO `connected_app` (`id`, `userid`, `app`) VALUES
(1, '', 'Microsoft'),
(2, '2', 'Microsoft'),
(3, '1', 'Microsoft'),
(4, '11', 'Microsoft');

-- --------------------------------------------------------

--
-- Table structure for table `currency`
--

CREATE TABLE `currency` (
  `id` int(11) NOT NULL,
  `app_id` text NOT NULL,
  `currency` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `currency`
--

INSERT INTO `currency` (`id`, `app_id`, `currency`) VALUES
(13, '169326', '£'),
(14, '169325', '£');

-- --------------------------------------------------------

--
-- Table structure for table `date`
--

CREATE TABLE `date` (
  `id` int(11) NOT NULL,
  `userid` text COLLATE utf8_unicode_ci NOT NULL,
  `from` text COLLATE utf8_unicode_ci NOT NULL,
  `to` text COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `date`
--

INSERT INTO `date` (`id`, `userid`, `from`, `to`) VALUES
(1, '5', '2024-06-03', '2024-06-09'),
(2, '5', '2024-06-03', '2024-06-09'),
(3, '13', '2024-05-13', '2024-05-19'),
(4, '14', '2024-05-20', '2024-05-26'),
(5, '14', '2024-05-20', '2024-05-26'),
(6, '14', '2024-05-20', '2024-05-26'),
(7, '5', '2024-06-03', '2024-06-09'),
(8, '5', '2024-06-03', '2024-06-09'),
(9, '13', '2024-05-20', '2024-05-26'),
(10, '3', '2024-05-20', '2024-05-26'),
(11, '3', '2024-05-20', '2024-05-26'),
(12, '14', '2024-05-20', '2024-05-26'),
(13, '3', '2024-05-20', '2024-05-26'),
(14, '14', '2024-05-20', '2024-05-26'),
(15, '14', '2024-05-20', '2024-05-26'),
(16, '5', '2024-06-03', '2024-06-09'),
(17, '5', '2024-06-03', '2024-06-09'),
(18, '14', '2024-05-20', '2024-05-26'),
(19, '13', '2024-05-20', '2024-05-26'),
(20, '3', '2024-05-20', '2024-05-26'),
(21, '14', '2024-05-20', '2024-05-26'),
(22, '14', '2024-05-20', '2024-05-26'),
(23, '3', '2024-05-20', '2024-05-26'),
(24, '10', '2024-06-03', '2024-06-09'),
(25, '5', '2024-06-03', '2024-06-09'),
(26, '14', '2024-06-03', '2024-06-09'),
(27, '14', '2024-06-10', '2024-06-16'),
(28, '5', '2024-06-10', '2024-06-16'),
(29, '14', '2024-06-10', '2024-06-16'),
(30, '5', '2024-06-17', '2024-06-23'),
(31, '5', '2024-06-17', '2024-06-23'),
(32, '5', '2024-06-24', '2024-06-30'),
(33, '14', '2024-07-08', '2024-07-14'),
(34, '3', '2024-07-08', '2024-07-14'),
(35, '13', '2024-07-08', '2024-07-14'),
(36, '13', '2024-07-08', '2024-07-14');

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `id` int(11) NOT NULL,
  `app_id` text NOT NULL,
  `department` text NOT NULL,
  `createdBy` text NOT NULL,
  `createdOn` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `department`
--

INSERT INTO `department` (`id`, `app_id`, `department`, `createdBy`, `createdOn`) VALUES
(9, '169325', 'Administrative', 'Michael Jr Musenge', '10th April 2023'),
(18, '950862c44989d6795534f8415257b08a', 'Consultant Dept', 'J  Musenge', '19th May 2023');

-- --------------------------------------------------------

--
-- Table structure for table `documenttype`
--

CREATE TABLE `documenttype` (
  `id` int(11) NOT NULL,
  `app_id` text NOT NULL,
  `type` text NOT NULL,
  `name` text NOT NULL,
  `expirydate` varchar(200) NOT NULL DEFAULT 'false',
  `createdBy` text NOT NULL,
  `createdOn` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `documenttype`
--

INSERT INTO `documenttype` (`id`, `app_id`, `type`, `name`, `expirydate`, `createdBy`, `createdOn`) VALUES
(1, '169326', 'RTW Documents', 'Biometric Card', 'true', 'Andie  ', '9th May 2023'),
(2, '169325', 'RTW Documents', 'Passport', 'true', 'Chax  Shamwana', '18th May 2023'),
(3, '169325', 'Mandatory Training', 'Manual Handling', 'true', 'Samantha   Sunduza-Heath', '2nd June 2023'),
(6, '169325', 'Mandatory Training', 'Food Hygiene', 'true', 'Samantha   Sunduza-Heath', '2nd June 2023'),
(7, '169325', 'Mandatory Training', 'Fire Safety', 'true', 'Samantha   Sunduza-Heath', '2nd June 2023'),
(8, '169325', 'Mandatory Training', 'First Aid', 'true', 'Samantha   Sunduza-Heath', '2nd June 2023'),
(9, '169325', 'Mandatory Training', 'Medication', 'true', 'Samantha   Sunduza-Heath', '2nd June 2023'),
(11, '169325', 'Mandatory Training', 'Infection Prevention & Control', 'true', 'Samantha   Sunduza-Heath', '2nd June 2023'),
(12, '169325', 'Mandatory Training', 'Health & Safety', 'true', 'Samantha   Sunduza-Heath', '2nd June 2023'),
(13, '169325', 'Mandatory Training', 'Safeguarding Children', 'true', 'Samantha   Sunduza-Heath', '2nd June 2023'),
(14, '169325', 'Mandatory Training', 'Safeguarding Vulnerable Adults', 'true', 'Samantha   Sunduza-Heath', '2nd June 2023'),
(16, '169325', 'Mandatory Training', 'Mandatory Training', 'true', 'Samantha   Sunduza-Heath', '2nd June 2023'),
(17, '169325', 'RTW Documents', 'BRP', 'true', 'Samantha   Sunduza-Heath', '2nd June 2023'),
(18, '169325', 'DBS Documents', 'DBS - NRS', 'true', 'Samantha   Sunduza-Heath', '2nd June 2023'),
(19, '169325', 'DBS Documents', 'DBS + Update Service', 'true', 'Samantha   Sunduza-Heath', '2nd June 2023'),
(21, '169325', 'Proof of residence', 'POA  1', 'false', 'Samantha   Sunduza-Heath', '2nd June 2023'),
(22, '169325', 'Proof of residence', 'POA 2', 'false', 'Samantha   Sunduza-Heath', '2nd June 2023'),
(23, '169325', 'Proof of residence', 'POAx2', 'false', 'Samantha   Sunduza-Heath', '2nd June 2023'),
(25, '169325', 'Application Documents', 'Application Form', 'false', 'Samantha   Sunduza-Heath', '5th June 2023'),
(28, '169325', 'RTW Documents', 'EU -Passport', 'true', 'Samantha   Sunduza-Heath', '5th June 2023'),
(29, '169325', 'RTW Documents', 'EU ID', 'true', 'Samantha   Sunduza-Heath', '6th June 2023'),
(30, '169325', 'Application Documents', 'NRS Application Form', 'false', 'Samantha   Sunduza-Heath', '6th June 2023'),
(31, '169325', 'Application Documents', 'NRS Health_Questionnaire', 'false', 'Samantha   Sunduza-Heath', '6th June 2023'),
(33, '169325', 'CV Documents', 'NRS Staff Profile', 'false', 'Samantha   Sunduza-Heath', '6th June 2023'),
(34, '169325', 'CV Documents', 'NRS CV', 'false', 'Samantha   Sunduza-Heath', '6th June 2023'),
(35, '169325', 'DBS Documents', 'Child - DBS', 'true', 'Samantha   Sunduza-Heath', '7th June 2023'),
(36, '169325', 'DBS Documents', 'Adult -DBS', 'true', 'Samantha   Sunduza-Heath', '7th June 2023'),
(38, '169325', 'RTW Documents', 'ECS -RTW', 'true', 'Samantha   Sunduza-Heath', '14th June 2023');

-- --------------------------------------------------------

--
-- Table structure for table `emailconfigs`
--

CREATE TABLE `emailconfigs` (
  `id` int(11) NOT NULL,
  `app_id` text NOT NULL,
  `name` text NOT NULL,
  `email` text NOT NULL,
  `function` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `emailconfigs`
--

INSERT INTO `emailconfigs` (`id`, `app_id`, `name`, `email`, `function`) VALUES
(8, '169325', 'Broadmead', 'vacancies@broad-mead.com', 'vacancies'),
(16, '169325', 'Broadmead Recruitment Firm.', 'info@broad-mead.com', 'bulkemail'),
(21, '169326', 'Cog-Nation Digital Limited', 'info@cog-nation.com', 'bulkemail'),
(22, '169326', 'Chax Shamwana', 'chax@nocturnalrecruitment.co.uk', 'invoices'),
(26, '169325', 'Chax Shamwana', 'chax@nocturnalrecruitment.co.uk', 'loginapproval'),
(27, '169325', 'Chax Shamwana ', 'chax@nocturnalrecruitment.co.uk', 'invoices');

-- --------------------------------------------------------

--
-- Table structure for table `emaillist`
--

CREATE TABLE `emaillist` (
  `id` int(11) NOT NULL,
  `emailid` text NOT NULL,
  `email` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `emaillist`
--

INSERT INTO `emaillist` (`id`, `emailid`, `email`) VALUES
(1, '7032d218-0328-421f-aa2d-8089edd0529d', 'owenumukorp926@gmail.com  '),
(2, '7032d218-0328-421f-aa2d-8089edd0529d', 'tawiahm1@gmail.com '),
(3, '7032d218-0328-421f-aa2d-8089edd0529d', 'arorasonali53@gmail.com   '),
(4, '7032d218-0328-421f-aa2d-8089edd0529d', 'biancariley03@yahoo.com '),
(5, '7032d218-0328-421f-aa2d-8089edd0529d', 'dgproperty07@hotmail.co.uk  '),
(6, '7032d218-0328-421f-aa2d-8089edd0529d', 'adamjama88@gmail.com '),
(7, '7032d218-0328-421f-aa2d-8089edd0529d', 'moiwosia@yahoo.com  '),
(8, '7032d218-0328-421f-aa2d-8089edd0529d', 'gbengaanthony@yahoo.com  '),
(9, '7032d218-0328-421f-aa2d-8089edd0529d', 'dibarnasingha@gmail.com'),
(10, '7032d218-0328-421f-aa2d-8089edd0529d', 'moji56@msn.com'),
(11, '7032d218-0328-421f-aa2d-8089edd0529d', 'oyenirankamorudeen@gmail.com '),
(12, '7032d218-0328-421f-aa2d-8089edd0529d', 'lathoyetiscot@gmail.com '),
(13, '7032d218-0328-421f-aa2d-8089edd0529d', 'nwamadijudeikenna@yahoo.com '),
(14, '7032d218-0328-421f-aa2d-8089edd0529d', 'c.indigo@sky.com '),
(15, '7032d218-0328-421f-aa2d-8089edd0529d', 'sabaamanuel62@yahoo.co.uk'),
(16, '7032d218-0328-421f-aa2d-8089edd0529d', 'titilopeatinuke@yahoo.com  '),
(17, '7032d218-0328-421f-aa2d-8089edd0529d', 'fadoski2001@gmail.com '),
(18, '7032d218-0328-421f-aa2d-8089edd0529d', 'isha.ebanks@gmail.com '),
(19, '7032d218-0328-421f-aa2d-8089edd0529d', 'koyejo2006@yahoo.com'),
(20, '7032d218-0328-421f-aa2d-8089edd0529d', 'onabanjobabatope@yahoo.com'),
(21, '7032d218-0328-421f-aa2d-8089edd0529d', 'solafayoda@gmail.com'),
(22, '7032d218-0328-421f-aa2d-8089edd0529d', 'fevennimrod@yahoo.com  '),
(23, '7032d218-0328-421f-aa2d-8089edd0529d', 'muyiwaolawale@yahoo.com'),
(24, '7032d218-0328-421f-aa2d-8089edd0529d', 'akinwaleibidapoobe@gmail.com  '),
(25, '7032d218-0328-421f-aa2d-8089edd0529d', 'talk2babaisah@yahoo.com'),
(26, '7032d218-0328-421f-aa2d-8089edd0529d', 'elisabeth139@outlook.com'),
(27, '7032d218-0328-421f-aa2d-8089edd0529d', 'blessingadjei31@gmail.com '),
(28, '7032d218-0328-421f-aa2d-8089edd0529d', 'fellyobioji@yahoo.com'),
(29, '7032d218-0328-421f-aa2d-8089edd0529d', 'hajafmushtaba1@gmail.com  '),
(30, '7032d218-0328-421f-aa2d-8089edd0529d', 'cserwaa92@gmail.com'),
(31, '7032d218-0328-421f-aa2d-8089edd0529d', 'devochuks12@gmail.com'),
(32, '7032d218-0328-421f-aa2d-8089edd0529d', 'silasaanuoluwapo12@gmail.com '),
(33, '7032d218-0328-421f-aa2d-8089edd0529d', 'dennis.chomba01@gmail.com'),
(34, '7032d218-0328-421f-aa2d-8089edd0529d', 'mrbabingtan@hotmail.com '),
(35, '7032d218-0328-421f-aa2d-8089edd0529d', 'ariflaci@hotmail.co.uk '),
(36, '7032d218-0328-421f-aa2d-8089edd0529d', 'alabiokiola@outlook.com'),
(37, '7032d218-0328-421f-aa2d-8089edd0529d', 'kabasinguziizabella@gmail.com'),
(38, '7032d218-0328-421f-aa2d-8089edd0529d', 'sollyshot@yahoo.com'),
(39, '7032d218-0328-421f-aa2d-8089edd0529d', 'constanceobakpolor@gmail.com'),
(40, '7032d218-0328-421f-aa2d-8089edd0529d', 'bolaowo94@gmail.com'),
(41, '7032d218-0328-421f-aa2d-8089edd0529d', 'eni.adhokorie@gmail.com'),
(42, '7032d218-0328-421f-aa2d-8089edd0529d', 'pascaluzo2018@gmail.com   '),
(43, '7032d218-0328-421f-aa2d-8089edd0529d', 'grshuvo815@gmail.com  '),
(44, '7032d218-0328-421f-aa2d-8089edd0529d', 'chb5050a@gmail.com  '),
(45, '7032d218-0328-421f-aa2d-8089edd0529d', 'basiratatilade@gmail.com  '),
(46, '7032d218-0328-421f-aa2d-8089edd0529d', 'tosinfamak1@gmail.com'),
(47, '7032d218-0328-421f-aa2d-8089edd0529d', 'celinechidinma@yahoo.com '),
(48, '7032d218-0328-421f-aa2d-8089edd0529d', 'eiman.yagoub@yahoo.com'),
(49, '7032d218-0328-421f-aa2d-8089edd0529d', 'mercitinu@yahoo.co.uk'),
(50, '7032d218-0328-421f-aa2d-8089edd0529d', 'adetolaalonge@gmaail.com'),
(51, '7032d218-0328-421f-aa2d-8089edd0529d', 'olufunkeogunnubi@gmail.com'),
(52, '7032d218-0328-421f-aa2d-8089edd0529d', 'oliveramonykit41@gmail.com '),
(53, '7032d218-0328-421f-aa2d-8089edd0529d', 'folumegbon@gmail.com'),
(54, '7032d218-0328-421f-aa2d-8089edd0529d', 'sekbol@yahoo.com'),
(55, '7032d218-0328-421f-aa2d-8089edd0529d', 'musahabiba565@yahoo.com'),
(56, '7032d218-0328-421f-aa2d-8089edd0529d', 'nastaa10m@gmail.com'),
(57, '7032d218-0328-421f-aa2d-8089edd0529d', 'dolapoolomola@gmail.com  '),
(58, '7032d218-0328-421f-aa2d-8089edd0529d', 'maviskonama13@gmail.com'),
(59, '7032d218-0328-421f-aa2d-8089edd0529d', 'pkormawah@gmail.com '),
(60, '7032d218-0328-421f-aa2d-8089edd0529d', 'k_heddi@yahoo.co.uk '),
(61, '7032d218-0328-421f-aa2d-8089edd0529d', 'basankungjatta@yahoo.com'),
(62, '7032d218-0328-421f-aa2d-8089edd0529d', 'deraval042@gmail.com'),
(63, '7032d218-0328-421f-aa2d-8089edd0529d', 'ellen.achiaa70@yahoo.com'),
(64, '7032d218-0328-421f-aa2d-8089edd0529d', 'gbemisolaogunyemi@yahoo.com  '),
(65, '7032d218-0328-421f-aa2d-8089edd0529d', 'pamelabuhika@gmail.com'),
(66, '7032d218-0328-421f-aa2d-8089edd0529d', 'blessingabang692@gmail.com  '),
(67, '7032d218-0328-421f-aa2d-8089edd0529d', 'sitdonig@yahoo.com'),
(68, '7032d218-0328-421f-aa2d-8089edd0529d', 'atoluju@gmail.com  '),
(69, '7032d218-0328-421f-aa2d-8089edd0529d', 'ndee418@gmail.com    '),
(70, '7032d218-0328-421f-aa2d-8089edd0529d', 'spicyedikan@yahoo.com '),
(71, '7032d218-0328-421f-aa2d-8089edd0529d', 'judith_ogar@yahoo.com'),
(72, '7032d218-0328-421f-aa2d-8089edd0529d', 'miissibenke@hotmail.com'),
(73, '7032d218-0328-421f-aa2d-8089edd0529d', 'anboluwa01@gmail.com'),
(74, '7032d218-0328-421f-aa2d-8089edd0529d', 'okoro4g@gmail.com'),
(75, '7032d218-0328-421f-aa2d-8089edd0529d', 'khadijahkamara11@gmail.com'),
(76, '7032d218-0328-421f-aa2d-8089edd0529d', 'dsona807@yahoo.com'),
(77, '7032d218-0328-421f-aa2d-8089edd0529d', 'ebudechi@yahoo.com  '),
(78, '7032d218-0328-421f-aa2d-8089edd0529d', 'georginaok02@gmail.com'),
(79, '7032d218-0328-421f-aa2d-8089edd0529d', 'joyaigbefoh@gmail.com'),
(80, '7032d218-0328-421f-aa2d-8089edd0529d', 'thatuconteh@hotmail.co.uk     '),
(81, '7032d218-0328-421f-aa2d-8089edd0529d', 'maryjohn1062@yahoo.com'),
(82, '7032d218-0328-421f-aa2d-8089edd0529d', 'bamoski_lala_yahoo.com'),
(83, '7032d218-0328-421f-aa2d-8089edd0529d', 'adetopeolusanya@gmail.com'),
(84, '7032d218-0328-421f-aa2d-8089edd0529d', 'tbestofgod83@gmail.com'),
(85, '7032d218-0328-421f-aa2d-8089edd0529d', 'yodit_kidane@hotmail.com'),
(86, '7032d218-0328-421f-aa2d-8089edd0529d', 'vahiate@icloud.com    '),
(87, '7032d218-0328-421f-aa2d-8089edd0529d', 'kenwok1@yahoo.com'),
(88, '7032d218-0328-421f-aa2d-8089edd0529d', 'akandeayo0@gmail.com'),
(89, '7032d218-0328-421f-aa2d-8089edd0529d', 'milagobabe39@gmail.com'),
(90, '7032d218-0328-421f-aa2d-8089edd0529d', 'ajijofunke1975@gmail.com   '),
(91, '7032d218-0328-421f-aa2d-8089edd0529d', 'big_m.a.n@hotmail.com'),
(92, '7032d218-0328-421f-aa2d-8089edd0529d', 'uduakunwene@gmail.com'),
(93, '7032d218-0328-421f-aa2d-8089edd0529d', 'oluwabunmitoluju@gmail.com'),
(94, '7032d218-0328-421f-aa2d-8089edd0529d', 'helenmichaeljohnson@yahoo.com'),
(95, '7032d218-0328-421f-aa2d-8089edd0529d', 'danielmosima18@gmail.com '),
(96, '7032d218-0328-421f-aa2d-8089edd0529d', 'olabukolaosibodu@yahoo.com'),
(97, '7032d218-0328-421f-aa2d-8089edd0529d', 'hardeolahr438@gmsil.com   '),
(98, '7032d218-0328-421f-aa2d-8089edd0529d', 'kefyshe2@gmail.com'),
(99, '7032d218-0328-421f-aa2d-8089edd0529d', 'edwigelono@yahoo.fr'),
(100, '7032d218-0328-421f-aa2d-8089edd0529d', 'wunmicecil@yahoo.com'),
(101, '7032d218-0328-421f-aa2d-8089edd0529d', 'takande40@yahoo.com'),
(102, '7032d218-0328-421f-aa2d-8089edd0529d', 'austinokoro20@yahoo.co.uk'),
(103, '7032d218-0328-421f-aa2d-8089edd0529d', 'princella16@yahoo.com '),
(104, '7032d218-0328-421f-aa2d-8089edd0529d', 'zainabsadeeq18@gmail.com'),
(105, '7032d218-0328-421f-aa2d-8089edd0529d', 'rashidodalw@gmail.com'),
(106, '7032d218-0328-421f-aa2d-8089edd0529d', 'msphilipah_afia@hotmail.com'),
(107, '7032d218-0328-421f-aa2d-8089edd0529d', 'instudentoo@gmail.com'),
(108, '7032d218-0328-421f-aa2d-8089edd0529d', 'oluawobimpe@yahoo.co.uk'),
(109, '7032d218-0328-421f-aa2d-8089edd0529d', 'mamechieouse@yahoo.co.uk   '),
(110, '7032d218-0328-421f-aa2d-8089edd0529d', 'sreesidhi526@gmail.com'),
(111, '7032d218-0328-421f-aa2d-8089edd0529d', 'sheethalammu15@gmail.com'),
(112, '7032d218-0328-421f-aa2d-8089edd0529d', 'chouchous@ymail.com'),
(113, '7032d218-0328-421f-aa2d-8089edd0529d', 'davidskillzboy@yahoo.com'),
(114, '7032d218-0328-421f-aa2d-8089edd0529d', 'eaganoke@gmail.com  '),
(115, '7032d218-0328-421f-aa2d-8089edd0529d', 'adenike.adeshina15@gmail.com '),
(116, '7032d218-0328-421f-aa2d-8089edd0529d', 'elijkenn@gmail.com'),
(117, '7032d218-0328-421f-aa2d-8089edd0529d', 'sirtuy@gmail.com'),
(118, '7032d218-0328-421f-aa2d-8089edd0529d', 'seyeomolagbe@gmail.com'),
(119, '7032d218-0328-421f-aa2d-8089edd0529d', 'mcadesina@gmail.com'),
(120, '7032d218-0328-421f-aa2d-8089edd0529d', 'adeghaniya02@gmail.com'),
(121, '7032d218-0328-421f-aa2d-8089edd0529d', 'dahousen@yahoo.com'),
(122, '7032d218-0328-421f-aa2d-8089edd0529d', 'l.tangiri@yahoo.co.uk'),
(123, '7032d218-0328-421f-aa2d-8089edd0529d', 'anophil2012@gmail.com '),
(124, '7032d218-0328-421f-aa2d-8089edd0529d', 'bosy.beauty@yahoo.co.uk'),
(125, '7032d218-0328-421f-aa2d-8089edd0529d', 'mo5unrayokemi25@gmail.com '),
(126, '7032d218-0328-421f-aa2d-8089edd0529d', 'nifemitanwa@gmail.com  '),
(127, '7032d218-0328-421f-aa2d-8089edd0529d', 'kuponike23@yahoo.com'),
(128, '7032d218-0328-421f-aa2d-8089edd0529d', 'symze@yahoo.com'),
(129, '7032d218-0328-421f-aa2d-8089edd0529d', 'sykombo@yahoo.co.uk'),
(130, '7032d218-0328-421f-aa2d-8089edd0529d', 'destinyjoy2009@gmail.com'),
(131, '7032d218-0328-421f-aa2d-8089edd0529d', 'info@broad-mead.com'),
(132, '7032d218-0328-421f-aa2d-8089edd0529d', 'aborahmary@yahoo.co.uk'),
(133, '7032d218-0328-421f-aa2d-8089edd0529d', 'valentinaawoyemi@yahoo.com'),
(134, '7032d218-0328-421f-aa2d-8089edd0529d', 'oyelamitimileyin@gmail.com'),
(135, '7032d218-0328-421f-aa2d-8089edd0529d', 'jacobnwolley@gmail.com'),
(136, '7032d218-0328-421f-aa2d-8089edd0529d', 'omokaroovie@gmail.com'),
(137, '7032d218-0328-421f-aa2d-8089edd0529d', 'izogiejulius@gmail.com'),
(138, '7032d218-0328-421f-aa2d-8089edd0529d', 'musengemichaeljr@gmail.com'),
(139, '7032d218-0328-421f-aa2d-8089edd0529d', 'maryam.m.omar2@gmail.com'),
(140, '7032d218-0328-421f-aa2d-8089edd0529d', 'amaspecial21@gmail.com'),
(141, '7032d218-0328-421f-aa2d-8089edd0529d', 'ctiriwabaye@gmail.com '),
(142, '7032d218-0328-421f-aa2d-8089edd0529d', 'magnadene71@hotmail.com'),
(143, '7032d218-0328-421f-aa2d-8089edd0529d', 'mamaadelaide83@gmail.com '),
(144, '7032d218-0328-421f-aa2d-8089edd0529d', 'kaseketinashe3@gmail.com'),
(145, '7032d218-0328-421f-aa2d-8089edd0529d', 'akintayoadebayo96@gmail.com'),
(146, '7032d218-0328-421f-aa2d-8089edd0529d', 'fridayany1@gmail.com '),
(147, '7032d218-0328-421f-aa2d-8089edd0529d', 'dhoyeenoke@yahoo.co.uk'),
(148, '7032d218-0328-421f-aa2d-8089edd0529d', 'laylash05@hotmail.com'),
(149, '7032d218-0328-421f-aa2d-8089edd0529d', 'giftyturksonn@gmail.com'),
(150, '7032d218-0328-421f-aa2d-8089edd0529d', 'aderinsolashosilva@gmail.com'),
(151, '7032d218-0328-421f-aa2d-8089edd0529d', 'omotolagil@yahoo.co.uk  '),
(152, '7032d218-0328-421f-aa2d-8089edd0529d', 'awurum_prisca@yahoo.com'),
(153, '7032d218-0328-421f-aa2d-8089edd0529d', 'kcharlotte922@gmail.com'),
(154, '7032d218-0328-421f-aa2d-8089edd0529d', 'valentinamanfrin646@yahoo.it'),
(155, '7032d218-0328-421f-aa2d-8089edd0529d', 'oreyinka@yahoo.com'),
(156, '7032d218-0328-421f-aa2d-8089edd0529d', 'adeolaadeogun@gamil.com'),
(157, '7032d218-0328-421f-aa2d-8089edd0529d', 'damilo4u@gmail.com'),
(158, '7032d218-0328-421f-aa2d-8089edd0529d', 'gideon.baah@yahoo.com'),
(159, '7032d218-0328-421f-aa2d-8089edd0529d', 'genevieveonyinye@gmail.com'),
(160, '7032d218-0328-421f-aa2d-8089edd0529d', 'bukolaigeeunice@gmail.com'),
(161, '7032d218-0328-421f-aa2d-8089edd0529d', 'berbegh@yahoo.com '),
(162, '7032d218-0328-421f-aa2d-8089edd0529d', 'afolasadealatishe@gmail.com '),
(163, '7032d218-0328-421f-aa2d-8089edd0529d', 'koomsonmama1@gmail.com'),
(164, '7032d218-0328-421f-aa2d-8089edd0529d', 'samnunchy2017@gmail.com'),
(165, '7032d218-0328-421f-aa2d-8089edd0529d', 'winny4rill@gmail.com'),
(166, '7032d218-0328-421f-aa2d-8089edd0529d', 'sholoyead@aol.com'),
(167, '7032d218-0328-421f-aa2d-8089edd0529d', 'mabintyjalloh49@yahoo.com'),
(168, '7032d218-0328-421f-aa2d-8089edd0529d', 'yohanatesfamichaelst@gmail.com'),
(169, '7032d218-0328-421f-aa2d-8089edd0529d', 'carmenbacalemangue@gmail.com '),
(170, '7032d218-0328-421f-aa2d-8089edd0529d', 'oluwatosinadeogun18@gmail.com'),
(171, '7032d218-0328-421f-aa2d-8089edd0529d', 'kingsleyboateng586@gmail.com'),
(172, '7032d218-0328-421f-aa2d-8089edd0529d', 'maryblove@ymail.com'),
(173, '7032d218-0328-421f-aa2d-8089edd0529d', 'oluwafunke.hokon@gmail.com'),
(174, '7032d218-0328-421f-aa2d-8089edd0529d', 'sus_tur@yahoo.co.uk'),
(175, '7032d218-0328-421f-aa2d-8089edd0529d', 'edcelencarnacion@gmail.com'),
(176, '7032d218-0328-421f-aa2d-8089edd0529d', 'bimbosuara89@gmail.com'),
(177, '7032d218-0328-421f-aa2d-8089edd0529d', 'dorcasmitsh@hotmail.co.uk'),
(178, '7032d218-0328-421f-aa2d-8089edd0529d', 'issakargbo538@gmail.com'),
(179, '7032d218-0328-421f-aa2d-8089edd0529d', 'odigiririchard@gmail.com'),
(180, '7032d218-0328-421f-aa2d-8089edd0529d', 'josiepeters94@icloud.com'),
(181, '7032d218-0328-421f-aa2d-8089edd0529d', 'essiegeorge13@gmail.com'),
(182, '7032d218-0328-421f-aa2d-8089edd0529d', 'titusmarume83@gmail.com'),
(183, '7032d218-0328-421f-aa2d-8089edd0529d', 'muyideenolasimbo5@gmail.com'),
(184, '7032d218-0328-421f-aa2d-8089edd0529d', 'ibelmi33@hotmail.com'),
(185, '7032d218-0328-421f-aa2d-8089edd0529d', 'cynthibube@yahoo.com '),
(186, '7032d218-0328-421f-aa2d-8089edd0529d', 'damilola.ojomu@gmail.com'),
(187, '7032d218-0328-421f-aa2d-8089edd0529d', 'onyiicynthi2@gmail.com'),
(188, '7032d218-0328-421f-aa2d-8089edd0529d', 'dofedoris@yahoo.com');

-- --------------------------------------------------------

--
-- Table structure for table `emails`
--

CREATE TABLE `emails` (
  `id` int(11) NOT NULL,
  `app_id` text NOT NULL,
  `emailid` text NOT NULL,
  `campaignid` text NOT NULL,
  `user` text NOT NULL,
  `email` text NOT NULL,
  `subject` text NOT NULL,
  `body` longtext NOT NULL,
  `createdBy` text NOT NULL,
  `time` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `emails`
--

INSERT INTO `emails` (`id`, `app_id`, `emailid`, `campaignid`, `user`, `email`, `subject`, `body`, `createdBy`, `time`) VALUES
(1, '169325', 'b147a61c1d07c1c999560f62add6dbc7', '10', 'client', 'careenquiries@countrycourtcare.com', 'DINNER', 'html', '1', '2023-05-07'),
(2, '169325', 'b147a61c1d07c1c999560f62add6dbc7', '10', 'client', 'lylehousemanchester@gmail.com', 'DINNER', 'html', '1', '2023-05-07'),
(3, '169325', 'b147a61c1d07c1c999560f62add6dbc7', '10', 'client', 'london@fb.com', 'DINNER', 'html', '1', '2023-05-07'),
(4, '169325', 'a63fc8c5d915e1f1a40f40e6c7499863', '7', 'client', 'careenquiries@countrycourtcare.com', 'DINNER', 'html', '1', '2023-05-07'),
(5, '169325', 'a63fc8c5d915e1f1a40f40e6c7499863', '7', 'candidate', 'musengemichaeljr@gmail.com', 'DINNER', 'html', '1', '2023-05-07'),
(6, '169325', 'a63fc8c5d915e1f1a40f40e6c7499863', '7', 'client', 'london@fb.com', 'DINNER', 'html', '1', '2023-05-07'),
(7, '169325', 'a63fc8c5d915e1f1a40f40e6c7499863', '7', 'client', 'lylehousemanchester@gmail.com', 'DINNER', 'html', '1', '2023-05-07'),
(8, '169325', 'a63fc8c5d915e1f1a40f40e6c7499863', '7', 'candidate', 'musengemichaeljr@yahoo.com  ', 'DINNER', 'html', '1', '2023-05-07'),
(9, '169325', '107030ca685076c0ed5e054e2c3ed940', '5', 'candidate', 'musengemichaeljr@yahoo.com  ', 'Appointment Reminder', 'html', '1', '2023-05-08'),
(10, '169325', '107030ca685076c0ed5e054e2c3ed940', '5', 'candidate', 'musengemichaeljr@gmail.com', 'Appointment Reminder', 'html', '1', '2023-05-08'),
(11, '169325', '107030ca685076c0ed5e054e2c3ed940', '5', 'candidate', 'johnalex@gmail.com', 'Appointment Reminder', 'html', '1', '2023-05-08'),
(12, '169326', '9a1de01f893e0d2551ecbb7ce4dc963e', '5', 'candidate', 'musengemichaeljr@gmail.com', 'interview reminder', 'html', '2', '2023-05-09'),
(13, '169326', 'c2ba1bc54b239208cb37b901c0d3b363', '5', 'candidate', 'musengemichaeljr@gmail.com', 'Interview Reminder', 'html', '2', '2023-05-09'),
(14, '169326', '403ea2e851b9ab04a996beab4a480a30', '7', 'candidate', 'info@cog-nation.com ', 'Test Email', 'html', '2', '2023-05-09'),
(15, '169326', '9e82757e9a1c12cb710ad680db11f6f1', '10', 'candidate', 'info@cog-nation.com ', 'Reminder', 'html', '2', '2023-05-09'),
(16, '169326', '240c945bb72980130446fc2b40fbb8e0', '7', 'candidate', 'chax@nocturnalrecruitment.co.uk', 'Test Email', 'html', '2', '2023-05-09'),
(17, '169326', 'd58f855fdcc76daf232aee454c4e59f7', '7', 'candidate', 'chax@nocturnalrecruitment.co.uk', 'Test Email', 'html', '2', '2023-05-09');

-- --------------------------------------------------------

--
-- Table structure for table `emergency`
--

CREATE TABLE `emergency` (
  `id` int(11) NOT NULL,
  `app_id` text NOT NULL,
  `candidateid` text NOT NULL,
  `relationship` text NOT NULL,
  `first_name` text NOT NULL,
  `last_name` text NOT NULL,
  `emergency` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `emergency`
--

INSERT INTO `emergency` (`id`, `app_id`, `candidateid`, `relationship`, `first_name`, `last_name`, `emergency`) VALUES
(1, '169325', '3', 'Mother ', 'jo', 'm', '0'),
(2, '169325', '33', 'Sister', 'Susan', 'Otabor', '07706238021'),
(3, '169325', '6', 'Friend', 'Quadri', 'Akande', '07417483997'),
(4, '169325', '2', 'pARTNER', 'Abolanle', 'Akin', '07506873250'),
(5, '169325', '11', 'Cousin', 'Karen', 'J', '07961751233');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `app_id` text NOT NULL,
  `title` text NOT NULL,
  `tags` text NOT NULL,
  `color` text NOT NULL,
  `start_datetime` datetime NOT NULL,
  `end_datetime` datetime NOT NULL,
  `createdBy` text NOT NULL,
  `date` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `app_id`, `title`, `tags`, `color`, `start_datetime`, `end_datetime`, `createdBy`, `date`) VALUES
(2, '169325', 'System deadline', '', '', '2023-08-31 00:06:00', '2023-08-31 00:06:00', '5', '2023-08-22'),
(3, '169325', 'Meeting', '', '', '2023-08-01 00:09:00', '2023-08-01 00:09:00', '5', '2023-08-22'),
(4, '169325', 'MIchael Musenge Meeting ', '', '', '2023-08-25 10:00:00', '2023-08-25 12:00:00', '5', '2023-08-22'),
(5, '169325', 'John\'s meeting', 'meeting,systems', '', '2023-08-28 10:00:00', '2023-08-28 12:00:00', '5', '2023-08-22'),
(6, '169325', 'Chax\'s meeting on thursaday with michael', 'broadmead,kpis', '', '2023-08-29 20:00:00', '2023-08-29 22:00:00', '5', '2023-08-22'),
(7, '169325', 'birthday ', '', '#FF5733', '2024-01-11 12:26:00', '2024-01-11 12:26:00', '1', '2024-01-04');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `userid` text NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `userid`, `description`) VALUES
(1, '169325', 'The system is good'),
(2, '169325', ''),
(3, '169325', 'good'),
(4, '169325', 'typo. its describe');

-- --------------------------------------------------------

--
-- Table structure for table `interviews`
--

CREATE TABLE `interviews` (
  `id` int(11) NOT NULL,
  `app_id` text NOT NULL,
  `status` varchar(200) NOT NULL DEFAULT 'Active',
  `name` text NOT NULL,
  `manager` text NOT NULL,
  `client` text NOT NULL,
  `candidate` text NOT NULL,
  `branchname` text NOT NULL,
  `interviewdate` text NOT NULL,
  `interviewtime` text NOT NULL,
  `document` text NOT NULL,
  `createdBy` text NOT NULL,
  `createdOn` text NOT NULL,
  `time` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `interviews`
--

INSERT INTO `interviews` (`id`, `app_id`, `status`, `name`, `manager`, `client`, `candidate`, `branchname`, `interviewdate`, `interviewtime`, `document`, `createdBy`, `createdOn`, `time`) VALUES
(1, '', 'Active', '0', 'Gill ', '1', '64', '1', '2024-01-17', '11:00', '/home3/xuwl9qaw/public_html/a/dist/assets/candidate/files/218730636.', '', '2024-01-15', '00:55:00'),
(2, '', 'Active', '0', 'Michael Musenge', '12', '1', '12', '2024-01-20', '02:47', '/home3/xuwl9qaw/public_html/a/dist/assets/candidate/files/9014438.', '', '2024-01-20', '02:47:47'),
(3, '169325', 'Pending', '', 'Michael Musenge', '12', '1', '12', '2024-01-20', '03:03', 'files/48241969.', '1', '2024-01-23', '');

-- --------------------------------------------------------

--
-- Table structure for table `invoiceitems`
--

CREATE TABLE `invoiceitems` (
  `id` int(11) NOT NULL,
  `app_id` int(11) NOT NULL,
  `invoiceid` text NOT NULL,
  `item` text NOT NULL,
  `qty` text NOT NULL,
  `price` text NOT NULL,
  `total` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `invoiceitems`
--

INSERT INTO `invoiceitems` (`id`, `app_id`, `invoiceid`, `item`, `qty`, `price`, `total`) VALUES
(1, 169326, '27111371254884415', 'Monday Shift -8th May 2023 (Regular)', '8.83', '24', '211.92'),
(2, 169326, '27111371254884415', 'Tuesday Shift -9th May 2023 (Regular)', '8.83', '24', '211.92'),
(3, 169326, '27111371254884415', 'Wednesday Shift -10th May 2023 (Regular)', '8.83', '24', '211.92'),
(4, 169326, '27111371254884415', 'Thursday Shift -11th May 2023 (Regular)', '9', '24', '216'),
(5, 169326, '27111371254884415', 'Friday Shift -12th May 2023 (Night Shift)', '5.5', '35', '192.5'),
(6, 169325, '54521054047174050', 'Monday Shift -15th May 2023 (Regular)', '14', '14.8', '207.2'),
(7, 169325, '54521054047174050', 'Wednesday Shift -17th May 2023 (Saturday Day)', '14', '14.8', '207.2'),
(8, 169325, '54521054047174050', 'Sunday Shift -21st May 2023 (Weekday Night)', '14', '14.8', '207.2');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` int(11) NOT NULL,
  `app_id` text NOT NULL,
  `invoiceid` text NOT NULL,
  `status` text NOT NULL,
  `client` text NOT NULL,
  `branchname` text NOT NULL,
  `date` text NOT NULL,
  `duedate` text NOT NULL,
  `note` text NOT NULL,
  `createdby` text NOT NULL,
  `createdon` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `app_id`, `invoiceid`, `status`, `client`, `branchname`, `date`, `duedate`, `note`, `createdby`, `createdon`) VALUES
(1, '169325', '897513983', 'Pending', '8', '8', '2023-11-21', '2023-11-19', 'No notes', '5', '2023-11-19'),
(2, '169325', '472517207', 'Paid', '8', '8', '2023-11-21', '2023-11-21', 'This assumes that the emails in the input string are separated by spaces. If they are separated by commas, semicolons, or another delimiter, you can adjust the explode function accordingly. For example, if emails are separated by commas, you would use.', '5', '2023-11-20'),
(3, '169325', '520805', 'Pending', '12', '12', '2024-01-29', '2024-01-29', '', '1', '2024-01-29 19:51:07');

-- --------------------------------------------------------

--
-- Table structure for table `jobtype`
--

CREATE TABLE `jobtype` (
  `id` int(11) NOT NULL,
  `app_id` text NOT NULL,
  `name` text NOT NULL,
  `createdBy` text NOT NULL,
  `createdOn` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `jobtype`
--

INSERT INTO `jobtype` (`id`, `app_id`, `name`, `createdBy`, `createdOn`) VALUES
(1, '169325', 'Permanent Job', 'Michael Junior Musenge', '14th April 2023'),
(2, '169325', 'Shift', 'Michael Junior Musenge', '14th April 2023');

-- --------------------------------------------------------

--
-- Table structure for table `keyjobarea`
--

CREATE TABLE `keyjobarea` (
  `id` int(11) NOT NULL,
  `app_id` text NOT NULL,
  `location` text NOT NULL,
  `specification` text NOT NULL,
  `payrate` text NOT NULL,
  `service_user` text NOT NULL,
  `description` text NOT NULL,
  `createdBy` text NOT NULL,
  `createdOn` text NOT NULL,
  `time` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `keyjobarea`
--

INSERT INTO `keyjobarea` (`id`, `app_id`, `location`, `specification`, `payrate`, `service_user`, `description`, `createdBy`, `createdOn`, `time`) VALUES
(7, '169325', 'Luton ', 'Support Worker ', '15', 'LD', 'Driver needed', '1', '2024-01-04', '12:11'),
(8, '169325', 'enfield', ' hca', '13.00', ' Dementia', '<p>need experience. km</p>', '1', '2024-01-14', '22:29'),
(9, '169325', 'Lusaka 1', ' IT 1', '20', ' Michael Testing Updated Version', 'It support and work Updated', '5', '2024-01-20', '11:21');

-- --------------------------------------------------------

--
-- Table structure for table `kpi`
--

CREATE TABLE `kpi` (
  `id` int(11) NOT NULL,
  `app_id` text NOT NULL,
  `status` text NOT NULL,
  `kpiid` text NOT NULL,
  `assignee` text NOT NULL,
  `name` text NOT NULL,
  `target` text NOT NULL,
  `priority` text NOT NULL,
  `from_date` text NOT NULL,
  `to_date` text NOT NULL,
  `description` text NOT NULL,
  `createdBy` text NOT NULL,
  `createdOn` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `kpi`
--

INSERT INTO `kpi` (`id`, `app_id`, `status`, `kpiid`, `assignee`, `name`, `target`, `priority`, `from_date`, `to_date`, `description`, `createdBy`, `createdOn`) VALUES
(1, '169325', '', '10909042654838', '10', 'Get FIve Candidates.', '5', 'Medium', '2023-12-22', '2023-12-22', 'Alex\'s Lapompe KPIs', '5', '2024-01-20 02:00'),
(2, '169325', '', '10909042654838', '10', 'Invoices', '4', 'High', '2023-12-22', '2023-12-22', 'Alex\'s Lapompe KPIs', '5', '2024-01-20 02:00'),
(6, '169325', '', '20783368465444', '9', 'get a nurse ', '2', 'Medium', '2023-12-01', '2024-01-31', '', '10', '2024-02-22 14:06'),
(7, '169325', '', '20783368465444', '9', 'send ads', '2', 'High', '2023-12-01', '2024-01-31', '', '10', '2024-02-22 14:06'),
(8, '169325', '', '20783368465444', '9', 'visit clients', '', '', '2023-12-01', '2024-01-31', '', '10', '2024-02-22 14:06');

-- --------------------------------------------------------

--
-- Table structure for table `kpiachieved`
--

CREATE TABLE `kpiachieved` (
  `id` int(11) NOT NULL,
  `assignee` text NOT NULL,
  `kpiid` text NOT NULL,
  `achieved` text NOT NULL,
  `date` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `kpiachieved`
--

INSERT INTO `kpiachieved` (`id`, `assignee`, `kpiid`, `achieved`, `date`) VALUES
(1, '10', '1', '11', '2023-12-22'),
(4, '', '2', '12', '2023-12-22 11:51:24');

-- --------------------------------------------------------

--
-- Table structure for table `loginstatus`
--

CREATE TABLE `loginstatus` (
  `id` int(11) NOT NULL,
  `app_id` text NOT NULL,
  `userid` text NOT NULL,
  `status` text NOT NULL,
  `code` text NOT NULL,
  `expirytime` text NOT NULL,
  `date` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `loginstatus`
--

INSERT INTO `loginstatus` (`id`, `app_id`, `userid`, `status`, `code`, `expirytime`, `date`) VALUES
(1, '169325', '5', 'LoggedIn', '3437', '17:56', '2023-11-06'),
(2, '169325', '5', 'LoggedIn', '4859', '09:08', '2023-11-09'),
(3, '169325', '5', 'LoggedIn', '8240', '10:08', '2023-11-09'),
(4, '169325', '5', 'LoggedIn', '2317', '16:26', '2023-11-18'),
(5, '169325', '5', 'LoggedIn', '8726', '07:43', '2023-11-19'),
(6, '169325', '5', 'LoggedIn', '8220', '15:03', '2023-11-21'),
(7, '169325', '5', 'LoggedIn', '2920', '17:24', '2023-11-21'),
(8, '169325', '1', 'LoggedIn', '5826', '07:59', '2023-12-21'),
(9, '169325', '1', 'LoggedIn', '9407', '10:50', '2023-12-21'),
(10, '169325', '1', 'LoggedIn', '8838', '18:20', '2023-12-21'),
(11, '169325', '1', 'LoggedIn', '7943', '07:24', '2023-12-22'),
(12, '169325', '5', 'LoggedIn', '1234', '23:40', '2024-01-02'),
(13, '169325', '11', 'LoggedIn', '1234', '18:45', '2024-01-03'),
(14, '169325', '11', 'LoggedIn', '1234', '19:31', '2024-01-03'),
(15, '169325', '5', 'LoggedIn', '1234', '12:28', '2024-01-04'),
(16, '169325', '1', 'LoggedIn', '1234', '12:31', '2024-01-04'),
(17, '169325', '1', 'LoggedIn', '1234', '12:34', '2024-01-04'),
(18, '169325', '5', 'LoggedIn', '1234', '12:42', '2024-01-04'),
(19, '169325', '5', 'LoggedIn', '1234', '12:55', '2024-01-04'),
(20, '169325', '1', 'LoggedIn', '1234', '13:00', '2024-01-04'),
(21, '169325', '5', 'LoggedIn', '1234', '13:59', '2024-01-04'),
(22, '169325', '1', 'LoggedIn', '1234', '14:10', '2024-01-04'),
(23, '169325', '1', 'LoggedIn', '1234', '15:24', '2024-01-04'),
(24, '169325', '1', 'LoggedIn', '1234', '15:41', '2024-01-04'),
(25, '169325', '11', 'LoggedIn', '1234', '16:04', '2024-01-04'),
(26, '169325', '5', 'LoggedIn', '1234', '20:20', '2024-01-04'),
(27, '169325', '5', 'LoggedIn', '1234', '22:16', '2024-01-04'),
(28, '169325', '1', 'LoggedIn', '1234', '22:30', '2024-01-04'),
(29, '169325', '5', 'LoggedIn', '1234', '13:59', '2024-01-07'),
(30, '169325', '5', 'LoggedIn', '1234', '14:08', '2024-01-07'),
(31, '169325', '1', 'LoggedIn', '1234', '17:28', '2024-01-11'),
(32, '169325', '1', 'LoggedIn', '1234', '00:19', '2024-01-15'),
(33, '169325', '1', 'LoggedIn', '1234', '16:29', '2024-01-19'),
(34, '169325', '5', 'LoggedIn', '1234', '00:53', '2024-01-20'),
(35, '169325', '5', 'LoggedIn', '1234', '01:54', '2024-01-20'),
(36, '169325', '5', 'LoggedIn', '1234', '09:30', '2024-01-20'),
(37, '169325', '5', 'LoggedIn', '1234', '10:58', '2024-01-20'),
(38, '169325', '5', 'LoggedIn', '1234', '11:10', '2024-01-20'),
(39, '169325', '5', 'LoggedIn', '1234', '11:28', '2024-01-20'),
(40, '169325', '5', 'LoggedIn', '1234', '12:42', '2024-01-20'),
(41, '169325', '5', 'LoggedIn', '1234', '12:48', '2024-01-20'),
(42, '169325', '5', 'LoggedIn', '1234', '15:21', '2024-01-20'),
(43, '169325', '5', 'LoggedIn', '1234', '15:44', '2024-01-20'),
(44, '169325', '5', 'LoggedIn', '1234', '13:47', '2024-01-21'),
(45, '169325', '5', 'LoggedIn', '1234', '13:53', '2024-01-21'),
(46, '169325', '5', 'LoggedIn', '1234', '14:12', '2024-01-21'),
(47, '169325', '1', 'LoggedIn', '1234', '14:46', '2024-01-21'),
(48, '169325', '5', 'LoggedIn', '1234', '14:50', '2024-01-21'),
(49, '169325', '1', 'LoggedIn', '1234', '17:40', '2024-01-21'),
(50, '169325', '1', 'LoggedIn', '1234', '21:32', '2024-01-21'),
(51, '169325', '1', 'LoggedIn', '1234', '14:53', '2024-01-23'),
(52, '169325', '5', 'LoggedIn', '1234', '00:27', '2024-01-25'),
(53, '169325', '5', 'LoggedIn', '1234', '00:27', '2024-01-25'),
(54, '169325', '5', 'LoggedIn', '1234', '00:27', '2024-01-25'),
(55, '169325', '1', 'LoggedIn', '1234', '14:36', '2024-01-25'),
(56, '169325', '1', 'LoggedIn', '1234', '21:21', '2024-01-29'),
(57, '169325', '11', 'LoggedIn', '1234', '19:28', '2024-01-31'),
(58, '169325', '5', 'LoggedIn', '1234', '09:44', '2024-02-01'),
(59, '169325', '11', 'LoggedIn', '1234', '18:14', '2024-02-01'),
(60, '169325', '5', 'LoggedIn', '1234', '20:21', '2024-02-01'),
(61, '169326', '5', 'LoggedIn', '1234', '20:30', '2024-02-01'),
(62, '169325', '11', 'LoggedIn', '1234', '13:22', '2024-02-02'),
(63, '169325', '5', 'LoggedIn', '1234', '16:16', '2024-02-07'),
(64, '169325', '3', 'LoggedIn', '1234', '16:54', '2024-02-07'),
(65, '169325', '13', 'LoggedIn', '1234', '16:59', '2024-02-07'),
(66, '169325', '13', 'LoggedIn', '1234', '17:02', '2024-02-07'),
(67, '169325', '3', 'LoggedIn', '1234', '16:22', '2024-02-08'),
(68, '169325', '11', 'LoggedIn', '1234', '16:20', '2024-02-09'),
(69, '169325', '11', 'LoggedIn', '1234', '17:21', '2024-02-09'),
(70, '169325', '5', 'LoggedIn', '1234', '17:00', '2024-02-10'),
(71, '169325', '11', 'LoggedIn', '1234', '18:12', '2024-02-13'),
(72, '169325', '11', 'LoggedIn', '1234', '15:02', '2024-02-14'),
(73, '169325', '13', 'LoggedIn', '1234', '16:42', '2024-02-14'),
(74, '169325', '1', 'LoggedIn', '1234', '17:09', '2024-02-20'),
(75, '169325', '1', 'LoggedIn', '1234', '17:09', '2024-02-20'),
(76, '169325', '1', 'LoggedIn', '1234', '17:09', '2024-02-20'),
(77, '169325', '3', 'LoggedIn', '1234', '17:07', '2024-02-21'),
(78, '169325', '10', 'LoggedIn', '1234', '15:18', '2024-02-22'),
(79, '169325', '10', 'LoggedIn', '1234', '15:19', '2024-02-22'),
(80, '169325', '11', 'LoggedIn', '1234', '16:01', '2024-02-22'),
(81, '169325', '3', 'LoggedIn', '1234', '15:52', '2024-02-26'),
(82, '169325', '13', 'LoggedIn', '1234', '18:05', '2024-02-27'),
(83, '169325', '9', 'LoggedIn', '1234', '18:06', '2024-02-27'),
(84, '169325', '13', 'LoggedIn', '1234', '18:16', '2024-02-27'),
(85, '169325', '5', 'LoggedIn', '1234', '11:46', '2024-02-28'),
(86, '169325', '3', 'LoggedIn', '1234', '15:12', '2024-02-28'),
(87, '169325', '13', 'LoggedIn', '1234', '17:22', '2024-03-04'),
(88, '169325', '13', 'LoggedIn', '1234', '18:08', '2024-03-04'),
(89, '169325', '9', 'LoggedIn', '1234', '19:17', '2024-03-04'),
(90, '169325', '13', 'LoggedIn', '1234', '12:59', '2024-03-05'),
(91, '169325', '13', 'LoggedIn', '1234', '13:35', '2024-03-05'),
(92, '169325', '13', 'LoggedIn', '1234', '11:04', '2024-03-06'),
(93, '169325', '1', 'LoggedIn', '1234', '10:46', '2024-03-07'),
(94, '169325', '14', 'LoggedIn', '1234', '11:01', '2024-03-07'),
(95, '169325', '3', 'Pending', '1234', '11:47', '2024-03-07');

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `id` int(11) NOT NULL,
  `app_id` text NOT NULL,
  `notification` text NOT NULL,
  `time` text NOT NULL,
  `date` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`id`, `app_id`, `notification`, `time`, `date`) VALUES
(1, '169325', 'Michael Junior Musenge successfully added a new Candidate.', '19:23', '2023-05-03'),
(2, '169326', 'Andie   successfully added a new Candidate.', '17:26', '2023-05-09'),
(3, '169326', 'Andie   successfully added a new vancancy.', '17:36', '2023-05-09'),
(4, '169326', 'Andie   successfully updated a  Candidate.', '18:27', '2023-05-09'),
(5, '169326', 'Andie   successfully added a new vancancy.', '18:56', '2023-05-09'),
(6, '169326', 'Andie   successfully updated a  Candidate.', '19:36', '2023-05-09'),
(7, '169326', 'Andie   successfully delete a Key Job Area', '21:15', '2023-05-09'),
(8, '169325', 'Chax  Shamwana successfully added a new Key Job Area', '23:44', '2023-05-12'),
(9, '169325', 'Chax  Shamwana successfully updated a Key Job Area', '23:45', '2023-05-12'),
(10, '169325', 'Chax  Shamwana successfully updated a Key Job Area', '23:48', '2023-05-12'),
(11, '169325', 'Chax  Shamwana successfully added a new Weekly KPI', '00:12', '2023-05-13'),
(12, '169325', 'Chax  Shamwana successfully added a new interview.', '01:03', '2023-05-13'),
(13, '169325', 'Chax  Shamwana successfully updated an interview.', '01:04', '2023-05-13'),
(14, '169325', 'Chax  Shamwana successfully updated an interview.', '01:06', '2023-05-13'),
(15, '169325', 'Chax  Shamwana successfully delete a Key Job Area', '02:43', '2023-05-16'),
(16, '169326', 'Michael  Musenge Jr successfully added a new Key Job Area', '16:50', '2023-05-16'),
(17, '169326', 'Michael  Musenge Jr successfully delete a Key Job Area', '16:51', '2023-05-16'),
(18, '169325', 'Chax  Shamwana successfully added a new Key Job Area', '20:56', '2023-05-16'),
(19, '169325', 'Chax  Shamwana successfully updated a Key Job Area', '20:58', '2023-05-16'),
(20, '169325', 'Chax  Shamwana successfully updated a Key Job Area', '22:25', '2023-05-16'),
(21, '169325', 'Chax  Shamwana successfully added a new Key Job Area', '22:40', '2023-05-16'),
(22, '169325', 'Chax  Shamwana successfully updated a Key Job Area', '22:41', '2023-05-16'),
(23, '169325', 'Chax  Shamwana successfully registered Samantha ', '23:08', '2023-05-16'),
(24, '169325', 'Chax  Shamwana successfully added a new Weekly KPI', '23:15', '2023-05-16'),
(25, '169325', 'Chax  Shamwana successfully updated Weekly KPI', '23:22', '2023-05-16'),
(26, '169326', 'Michael  Musenge Jr successfully update client Bank Details', '12:23', '2023-05-17'),
(27, '169326', 'Michael  Musenge Jr successfully update client Bank Details', '12:24', '2023-05-17'),
(28, '169325', ' successfully added a new Candidate.', '22:05', '2023-05-18'),
(29, '169325', ' successfully added a new Candidate.', '22:21', '2023-05-18'),
(30, '169325', ' successfully updated a  Candidate.', '22:22', '2023-05-18'),
(31, '169325', 'Chax  Shamwana successfully added a new interview.', '23:06', '2023-05-18'),
(32, '950862c44989d6795534f8415257b08a', 'J  Musenge successfully added a new Key Job Area', '13:30', '2023-05-19'),
(33, '950862c44989d6795534f8415257b08a', 'J  Musenge successfully added a new Weekly KPI', '13:49', '2023-05-19'),
(34, '169325', 'Chax  Shamwana successfully added a new Key Job Area', '15:49', '2023-05-19'),
(35, '169325', 'Chax  Shamwana successfully added a new interview.', '21:01', '2023-05-19'),
(36, '169325', 'Chax  Shamwana successfully added a new vancancy.', '21:20', '2023-05-19'),
(37, '169325', 'Chax  Shamwana successfully added a new vancancy.', '21:43', '2023-05-19'),
(38, '950862c44989d6795534f8415257b08a', 'J  Musenge successfully added a new vancancy.', '12:03', '2023-05-20 12:03'),
(39, '169325', 'Chax  Shamwana successfully updated Samantha s profile.', '00:03', '2023-05-31'),
(40, '169325', 'Chax  Shamwana successfully updated Alexs profile.', '00:04', '2023-05-31'),
(41, '169325', 'Chax  Shamwana successfully added a new Key Job Area', '14:16', '2023-05-31'),
(42, '169325', 'Chax  Shamwana successfully updated Jack s profile.', '17:11', '2023-05-31'),
(43, '169325', 'Chax  Shamwana successfully updated Alexs profile.', '17:45', '2023-05-31'),
(44, '169325', 'Alex  Lapompe successfully update client Charlie Ratchford', '18:08', '2023-05-31'),
(45, '169325', 'Alex  Lapompe successfully update client Breakaway Short Breaks', '18:22', '2023-05-31'),
(46, '169325', 'Chax  Shamwana successfully updated Samantha s profile.', '18:14', '2023-06-02'),
(47, '169326', 'Michael  Musenge Jr successfully added a new vancancy.', '21:30', '2023-06-03 21:30'),
(48, '169325', 'Chax  Shamwana successfully updated Samantha s profile.', '15:30', '2023-06-08'),
(49, '169325', 'Chax  Shamwana successfully added a new Key Job Area', '21:10', '2023-06-14'),
(50, '169325', 'Alex  Lapompe successfully added a new vancancy.', '11:26', '2023-06-21 11:26'),
(51, '169325', 'Chax  Shamwana successfully updated Rebeccas profile.', '16:49', '2023-07-17'),
(52, '169325', 'Rebecca  Farnum  successfully added a new Key Job Area', '14:59', '2023-07-17'),
(53, '169325', 'Chax  Shamwana successfully updated Rebeccas profile.', '21:04', '2023-07-17'),
(54, '169325', ' successfully added a new interview.', '', ''),
(55, '169325', 'New Interview added', '', ''),
(56, '169325', 'New Interview added', '', ''),
(57, '169325', 'New Interview added', '', ''),
(58, '169325', 'New Interview added', '', ''),
(59, '169325', 'New Interview added', '', ''),
(60, '169325', 'New Interview added', '', ''),
(61, '169325', 'New Interview added', '23:59:15', ''),
(62, '169325', 'New Interview added', '23:59:25', '2023-10-13'),
(63, '169325', 'New Interview added', '23:59:36', '2023-10-13'),
(64, '169325', 'New Interview added', '23:59:40', '2023-10-13'),
(65, '169325', 'New Interview added', '23:59:41', '2023-10-13'),
(66, '169325', 'New Interview added', '23:59:44', '2023-10-13'),
(67, '169325', 'New Interview added', '00:00:26', '2023-10-14'),
(68, '169325', 'New Interview added', '00:00:27', '2023-10-14'),
(69, '169325', 'New Interview added', '00:00:31', '2023-10-14'),
(70, '169325', 'New Interview added', '00:00:32', '2023-10-14'),
(71, '169325', 'New Interview added', '00:00:32', '2023-10-14'),
(72, '169325', 'New Interview added', '00:00:33', '2023-10-14'),
(73, '169325', 'New Interview added', '00:00:35', '2023-10-14'),
(74, '169325', 'New Interview added', '00:01:39', '2023-10-14'),
(75, '169325', 'New Interview added', '00:17:16', '2023-10-14'),
(76, '169325', 'New Interview added', '00:18:13', '2023-10-14'),
(77, '169325', 'New Interview added', '00:20:30', '2023-10-14'),
(78, '169325', 'New Interview added', '00:21:50', '2023-10-14'),
(79, '169325', 'New Interview added', '00:22:17', '2023-10-14'),
(80, '169325', 'New Interview added', '00:22:39', '2023-10-14'),
(81, '169325', 'New Interview added', '00:23:33', '2023-10-14'),
(82, '169325', 'New Interview added', '00:23:46', '2023-10-14'),
(83, '169325', 'New Interview added', '19:40:29', '2023-10-21'),
(84, '169325', 'New Interview added', '22:20:09', '2023-10-21'),
(85, '169325', 'New Interview added', '22:26:28', '2023-10-21'),
(86, '169325', ' successfully added a new vancancy.', '00:06:01', '2023-10-28'),
(87, '169325', 'New Interview added', '18:50:57', '2023-11-21'),
(88, '', 'New Interview added', '15:06:16', '2024-01-07'),
(89, '', 'New Interview added', '00:55:00', '2024-01-15'),
(90, '', 'New Interview added', '02:47:47', '2024-01-20'),
(91, '169325', 'New Interview added', '03:04:09', '2024-01-20');

-- --------------------------------------------------------

--
-- Table structure for table `oauthtokens`
--

CREATE TABLE `oauthtokens` (
  `id` int(11) NOT NULL,
  `userid` text NOT NULL,
  `token` text NOT NULL,
  `date` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `oauthtokens`
--

INSERT INTO `oauthtokens` (`id`, `userid`, `token`, `date`) VALUES
(1, '1', 'EwB4A8l6BAAUs5+HQn0N+h2FxWzLS31ZgQVuHsYAAXqkTLN+wS3q0W8yTvUEIrbnPAwejY0kIN3bMj7Iq0EcNIp36B66/7cE12GKFZez8lTx/R7Hy0Afhttm4ST80c7s7pZ6uAoCt9Qo/EAkmEgTBr+YW9NRL8GEfjpwqC+LkFseRVe29E1U4W3BkaV8Op48/b6e2Lf1Mj2B55JDicGPtkXx8b5b2Lj6KGuvcqrMc6asSs1oTJgg3Gn9L6rIY5T7cpaVmsIA0Z6OuJDjsM0NdKuMHTJa3XdKeW5TzDuaNm8G4rJxhB/wUpreOZAnpyid87m0ryhsSJ5wYac85u1LcHac5At4CD1421r/8Ak7mL/zThy7i07UEqofQSJ9gjUDZgAACHY6pZyuamr9SAJPy8gptzVT5UlzjyklbdzNcxFh5RQM+j1HuHyfwgM/qdCO7F01gP2YyZ4/NOwDO6UUx+c4HlkZazRxeFLQDbCtdX0lkCO94J7IA9ipn9Mr7VyUPC6onJJJ3/C5W4PWNG/JfqOdeSJiM9PNUbkoaZ0ERQQf5dKBckbPS4x/WhixNadu6OnK7fkBH3nrKV3a/YGOb2DUM25Jsk6UKTpXN0OKoRrZqTLCgak9bxbOYxtAv5PTrpCagJPGJy7l9XmEaGCVgiIcLDjJ1Q3PSkSh5Qla/Brv7YaWI1GWzDvFVTtko6LOEltjSRRZUiPrXeeH1epag0PYVdn9V6Hb4c4Gbt2yslDFnd4FcI6Ln3V1J0lST6zneqD6SrH0UiyKz8xi4PP3r3JbctoEMpE7zQmkjhUqdSA2yRVoL6oR0w1/hRRXgpvV0tXoZ54FJodKkNBu7KpzS/ZbCIXceRZdft7ArXGD9Jhjaqu4hlJodkrAu6/cv6xpJlj8sHZ7lD3wp+8sNSY0ABTkibyOrrHKKTHL+nySXuW9QQdCcTl1Jp8gVDl2YPbB0uExqv+llEwoMnPZCn0Jg668rtDB0Ht1r0UECaeMN/FR8LZbHBudjxnP3PP6WsytTe6qPU06jTtMAQ8Z5EVSISFuH9rrRiivUiwUT6VsXpWbf1oLukBtLH5R4q1ql28+G20QT4M0/3J+jP3F3xRp7fNtPUg8WbJlJXoln0mpdvYlXv6iC4Xiym+r2N6715Y+6FY+3cR6l91C1SdbVc3ZGE/Hho1twY4C', '2023-12-22'),
(2, '1', 'EwB4A8l6BAAUs5+HQn0N+h2FxWzLS31ZgQVuHsYAAXqkTLN+wS3q0W8yTvUEIrbnPAwejY0kIN3bMj7Iq0EcNIp36B66/7cE12GKFZez8lTx/R7Hy0Afhttm4ST80c7s7pZ6uAoCt9Qo/EAkmEgTBr+YW9NRL8GEfjpwqC+LkFseRVe29E1U4W3BkaV8Op48/b6e2Lf1Mj2B55JDicGPtkXx8b5b2Lj6KGuvcqrMc6asSs1oTJgg3Gn9L6rIY5T7cpaVmsIA0Z6OuJDjsM0NdKuMHTJa3XdKeW5TzDuaNm8G4rJxhB/wUpreOZAnpyid87m0ryhsSJ5wYac85u1LcHac5At4CD1421r/8Ak7mL/zThy7i07UEqofQSJ9gjUDZgAACHY6pZyuamr9SAJPy8gptzVT5UlzjyklbdzNcxFh5RQM+j1HuHyfwgM/qdCO7F01gP2YyZ4/NOwDO6UUx+c4HlkZazRxeFLQDbCtdX0lkCO94J7IA9ipn9Mr7VyUPC6onJJJ3/C5W4PWNG/JfqOdeSJiM9PNUbkoaZ0ERQQf5dKBckbPS4x/WhixNadu6OnK7fkBH3nrKV3a/YGOb2DUM25Jsk6UKTpXN0OKoRrZqTLCgak9bxbOYxtAv5PTrpCagJPGJy7l9XmEaGCVgiIcLDjJ1Q3PSkSh5Qla/Brv7YaWI1GWzDvFVTtko6LOEltjSRRZUiPrXeeH1epag0PYVdn9V6Hb4c4Gbt2yslDFnd4FcI6Ln3V1J0lST6zneqD6SrH0UiyKz8xi4PP3r3JbctoEMpE7zQmkjhUqdSA2yRVoL6oR0w1/hRRXgpvV0tXoZ54FJodKkNBu7KpzS/ZbCIXceRZdft7ArXGD9Jhjaqu4hlJodkrAu6/cv6xpJlj8sHZ7lD3wp+8sNSY0ABTkibyOrrHKKTHL+nySXuW9QQdCcTl1Jp8gVDl2YPbB0uExqv+llEwoMnPZCn0Jg668rtDB0Ht1r0UECaeMN/FR8LZbHBudjxnP3PP6WsytTe6qPU06jTtMAQ8Z5EVSISFuH9rrRiivUiwUT6VsXpWbf1oLukBtLH5R4q1ql28+G20QT4M0/3J+jP3F3xRp7fNtPUg8WbJlJXoln0mpdvYlXv6iC4Xiym+r2N6715Y+6FY+3cR6l91C1SdbVc3ZGE/Hho1twY4C', '2023-12-22'),
(3, '1', 'EwB4A8l6BAAUs5+HQn0N+h2FxWzLS31ZgQVuHsYAAXqkTLN+wS3q0W8yTvUEIrbnPAwejY0kIN3bMj7Iq0EcNIp36B66/7cE12GKFZez8lTx/R7Hy0Afhttm4ST80c7s7pZ6uAoCt9Qo/EAkmEgTBr+YW9NRL8GEfjpwqC+LkFseRVe29E1U4W3BkaV8Op48/b6e2Lf1Mj2B55JDicGPtkXx8b5b2Lj6KGuvcqrMc6asSs1oTJgg3Gn9L6rIY5T7cpaVmsIA0Z6OuJDjsM0NdKuMHTJa3XdKeW5TzDuaNm8G4rJxhB/wUpreOZAnpyid87m0ryhsSJ5wYac85u1LcHac5At4CD1421r/8Ak7mL/zThy7i07UEqofQSJ9gjUDZgAACHY6pZyuamr9SAJPy8gptzVT5UlzjyklbdzNcxFh5RQM+j1HuHyfwgM/qdCO7F01gP2YyZ4/NOwDO6UUx+c4HlkZazRxeFLQDbCtdX0lkCO94J7IA9ipn9Mr7VyUPC6onJJJ3/C5W4PWNG/JfqOdeSJiM9PNUbkoaZ0ERQQf5dKBckbPS4x/WhixNadu6OnK7fkBH3nrKV3a/YGOb2DUM25Jsk6UKTpXN0OKoRrZqTLCgak9bxbOYxtAv5PTrpCagJPGJy7l9XmEaGCVgiIcLDjJ1Q3PSkSh5Qla/Brv7YaWI1GWzDvFVTtko6LOEltjSRRZUiPrXeeH1epag0PYVdn9V6Hb4c4Gbt2yslDFnd4FcI6Ln3V1J0lST6zneqD6SrH0UiyKz8xi4PP3r3JbctoEMpE7zQmkjhUqdSA2yRVoL6oR0w1/hRRXgpvV0tXoZ54FJodKkNBu7KpzS/ZbCIXceRZdft7ArXGD9Jhjaqu4hlJodkrAu6/cv6xpJlj8sHZ7lD3wp+8sNSY0ABTkibyOrrHKKTHL+nySXuW9QQdCcTl1Jp8gVDl2YPbB0uExqv+llEwoMnPZCn0Jg668rtDB0Ht1r0UECaeMN/FR8LZbHBudjxnP3PP6WsytTe6qPU06jTtMAQ8Z5EVSISFuH9rrRiivUiwUT6VsXpWbf1oLukBtLH5R4q1ql28+G20QT4M0/3J+jP3F3xRp7fNtPUg8WbJlJXoln0mpdvYlXv6iC4Xiym+r2N6715Y+6FY+3cR6l91C1SdbVc3ZGE/Hho1twY4C', '2023-12-22'),
(4, '1', 'EwB4A8l6BAAUs5+HQn0N+h2FxWzLS31ZgQVuHsYAAXqkTLN+wS3q0W8yTvUEIrbnPAwejY0kIN3bMj7Iq0EcNIp36B66/7cE12GKFZez8lTx/R7Hy0Afhttm4ST80c7s7pZ6uAoCt9Qo/EAkmEgTBr+YW9NRL8GEfjpwqC+LkFseRVe29E1U4W3BkaV8Op48/b6e2Lf1Mj2B55JDicGPtkXx8b5b2Lj6KGuvcqrMc6asSs1oTJgg3Gn9L6rIY5T7cpaVmsIA0Z6OuJDjsM0NdKuMHTJa3XdKeW5TzDuaNm8G4rJxhB/wUpreOZAnpyid87m0ryhsSJ5wYac85u1LcHac5At4CD1421r/8Ak7mL/zThy7i07UEqofQSJ9gjUDZgAACHY6pZyuamr9SAJPy8gptzVT5UlzjyklbdzNcxFh5RQM+j1HuHyfwgM/qdCO7F01gP2YyZ4/NOwDO6UUx+c4HlkZazRxeFLQDbCtdX0lkCO94J7IA9ipn9Mr7VyUPC6onJJJ3/C5W4PWNG/JfqOdeSJiM9PNUbkoaZ0ERQQf5dKBckbPS4x/WhixNadu6OnK7fkBH3nrKV3a/YGOb2DUM25Jsk6UKTpXN0OKoRrZqTLCgak9bxbOYxtAv5PTrpCagJPGJy7l9XmEaGCVgiIcLDjJ1Q3PSkSh5Qla/Brv7YaWI1GWzDvFVTtko6LOEltjSRRZUiPrXeeH1epag0PYVdn9V6Hb4c4Gbt2yslDFnd4FcI6Ln3V1J0lST6zneqD6SrH0UiyKz8xi4PP3r3JbctoEMpE7zQmkjhUqdSA2yRVoL6oR0w1/hRRXgpvV0tXoZ54FJodKkNBu7KpzS/ZbCIXceRZdft7ArXGD9Jhjaqu4hlJodkrAu6/cv6xpJlj8sHZ7lD3wp+8sNSY0ABTkibyOrrHKKTHL+nySXuW9QQdCcTl1Jp8gVDl2YPbB0uExqv+llEwoMnPZCn0Jg668rtDB0Ht1r0UECaeMN/FR8LZbHBudjxnP3PP6WsytTe6qPU06jTtMAQ8Z5EVSISFuH9rrRiivUiwUT6VsXpWbf1oLukBtLH5R4q1ql28+G20QT4M0/3J+jP3F3xRp7fNtPUg8WbJlJXoln0mpdvYlXv6iC4Xiym+r2N6715Y+6FY+3cR6l91C1SdbVc3ZGE/Hho1twY4C', '2023-12-22'),
(5, '1', 'EwB4A8l6BAAUs5+HQn0N+h2FxWzLS31ZgQVuHsYAAXqkTLN+wS3q0W8yTvUEIrbnPAwejY0kIN3bMj7Iq0EcNIp36B66/7cE12GKFZez8lTx/R7Hy0Afhttm4ST80c7s7pZ6uAoCt9Qo/EAkmEgTBr+YW9NRL8GEfjpwqC+LkFseRVe29E1U4W3BkaV8Op48/b6e2Lf1Mj2B55JDicGPtkXx8b5b2Lj6KGuvcqrMc6asSs1oTJgg3Gn9L6rIY5T7cpaVmsIA0Z6OuJDjsM0NdKuMHTJa3XdKeW5TzDuaNm8G4rJxhB/wUpreOZAnpyid87m0ryhsSJ5wYac85u1LcHac5At4CD1421r/8Ak7mL/zThy7i07UEqofQSJ9gjUDZgAACHY6pZyuamr9SAJPy8gptzVT5UlzjyklbdzNcxFh5RQM+j1HuHyfwgM/qdCO7F01gP2YyZ4/NOwDO6UUx+c4HlkZazRxeFLQDbCtdX0lkCO94J7IA9ipn9Mr7VyUPC6onJJJ3/C5W4PWNG/JfqOdeSJiM9PNUbkoaZ0ERQQf5dKBckbPS4x/WhixNadu6OnK7fkBH3nrKV3a/YGOb2DUM25Jsk6UKTpXN0OKoRrZqTLCgak9bxbOYxtAv5PTrpCagJPGJy7l9XmEaGCVgiIcLDjJ1Q3PSkSh5Qla/Brv7YaWI1GWzDvFVTtko6LOEltjSRRZUiPrXeeH1epag0PYVdn9V6Hb4c4Gbt2yslDFnd4FcI6Ln3V1J0lST6zneqD6SrH0UiyKz8xi4PP3r3JbctoEMpE7zQmkjhUqdSA2yRVoL6oR0w1/hRRXgpvV0tXoZ54FJodKkNBu7KpzS/ZbCIXceRZdft7ArXGD9Jhjaqu4hlJodkrAu6/cv6xpJlj8sHZ7lD3wp+8sNSY0ABTkibyOrrHKKTHL+nySXuW9QQdCcTl1Jp8gVDl2YPbB0uExqv+llEwoMnPZCn0Jg668rtDB0Ht1r0UECaeMN/FR8LZbHBudjxnP3PP6WsytTe6qPU06jTtMAQ8Z5EVSISFuH9rrRiivUiwUT6VsXpWbf1oLukBtLH5R4q1ql28+G20QT4M0/3J+jP3F3xRp7fNtPUg8WbJlJXoln0mpdvYlXv6iC4Xiym+r2N6715Y+6FY+3cR6l91C1SdbVc3ZGE/Hho1twY4C', '2023-12-22'),
(6, '1', 'EwB4A8l6BAAUs5+HQn0N+h2FxWzLS31ZgQVuHsYAAXqkTLN+wS3q0W8yTvUEIrbnPAwejY0kIN3bMj7Iq0EcNIp36B66/7cE12GKFZez8lTx/R7Hy0Afhttm4ST80c7s7pZ6uAoCt9Qo/EAkmEgTBr+YW9NRL8GEfjpwqC+LkFseRVe29E1U4W3BkaV8Op48/b6e2Lf1Mj2B55JDicGPtkXx8b5b2Lj6KGuvcqrMc6asSs1oTJgg3Gn9L6rIY5T7cpaVmsIA0Z6OuJDjsM0NdKuMHTJa3XdKeW5TzDuaNm8G4rJxhB/wUpreOZAnpyid87m0ryhsSJ5wYac85u1LcHac5At4CD1421r/8Ak7mL/zThy7i07UEqofQSJ9gjUDZgAACHY6pZyuamr9SAJPy8gptzVT5UlzjyklbdzNcxFh5RQM+j1HuHyfwgM/qdCO7F01gP2YyZ4/NOwDO6UUx+c4HlkZazRxeFLQDbCtdX0lkCO94J7IA9ipn9Mr7VyUPC6onJJJ3/C5W4PWNG/JfqOdeSJiM9PNUbkoaZ0ERQQf5dKBckbPS4x/WhixNadu6OnK7fkBH3nrKV3a/YGOb2DUM25Jsk6UKTpXN0OKoRrZqTLCgak9bxbOYxtAv5PTrpCagJPGJy7l9XmEaGCVgiIcLDjJ1Q3PSkSh5Qla/Brv7YaWI1GWzDvFVTtko6LOEltjSRRZUiPrXeeH1epag0PYVdn9V6Hb4c4Gbt2yslDFnd4FcI6Ln3V1J0lST6zneqD6SrH0UiyKz8xi4PP3r3JbctoEMpE7zQmkjhUqdSA2yRVoL6oR0w1/hRRXgpvV0tXoZ54FJodKkNBu7KpzS/ZbCIXceRZdft7ArXGD9Jhjaqu4hlJodkrAu6/cv6xpJlj8sHZ7lD3wp+8sNSY0ABTkibyOrrHKKTHL+nySXuW9QQdCcTl1Jp8gVDl2YPbB0uExqv+llEwoMnPZCn0Jg668rtDB0Ht1r0UECaeMN/FR8LZbHBudjxnP3PP6WsytTe6qPU06jTtMAQ8Z5EVSISFuH9rrRiivUiwUT6VsXpWbf1oLukBtLH5R4q1ql28+G20QT4M0/3J+jP3F3xRp7fNtPUg8WbJlJXoln0mpdvYlXv6iC4Xiym+r2N6715Y+6FY+3cR6l91C1SdbVc3ZGE/Hho1twY4C', '2023-12-22'),
(7, '1', 'EwB4A8l6BAAUs5+HQn0N+h2FxWzLS31ZgQVuHsYAAXqkTLN+wS3q0W8yTvUEIrbnPAwejY0kIN3bMj7Iq0EcNIp36B66/7cE12GKFZez8lTx/R7Hy0Afhttm4ST80c7s7pZ6uAoCt9Qo/EAkmEgTBr+YW9NRL8GEfjpwqC+LkFseRVe29E1U4W3BkaV8Op48/b6e2Lf1Mj2B55JDicGPtkXx8b5b2Lj6KGuvcqrMc6asSs1oTJgg3Gn9L6rIY5T7cpaVmsIA0Z6OuJDjsM0NdKuMHTJa3XdKeW5TzDuaNm8G4rJxhB/wUpreOZAnpyid87m0ryhsSJ5wYac85u1LcHac5At4CD1421r/8Ak7mL/zThy7i07UEqofQSJ9gjUDZgAACHY6pZyuamr9SAJPy8gptzVT5UlzjyklbdzNcxFh5RQM+j1HuHyfwgM/qdCO7F01gP2YyZ4/NOwDO6UUx+c4HlkZazRxeFLQDbCtdX0lkCO94J7IA9ipn9Mr7VyUPC6onJJJ3/C5W4PWNG/JfqOdeSJiM9PNUbkoaZ0ERQQf5dKBckbPS4x/WhixNadu6OnK7fkBH3nrKV3a/YGOb2DUM25Jsk6UKTpXN0OKoRrZqTLCgak9bxbOYxtAv5PTrpCagJPGJy7l9XmEaGCVgiIcLDjJ1Q3PSkSh5Qla/Brv7YaWI1GWzDvFVTtko6LOEltjSRRZUiPrXeeH1epag0PYVdn9V6Hb4c4Gbt2yslDFnd4FcI6Ln3V1J0lST6zneqD6SrH0UiyKz8xi4PP3r3JbctoEMpE7zQmkjhUqdSA2yRVoL6oR0w1/hRRXgpvV0tXoZ54FJodKkNBu7KpzS/ZbCIXceRZdft7ArXGD9Jhjaqu4hlJodkrAu6/cv6xpJlj8sHZ7lD3wp+8sNSY0ABTkibyOrrHKKTHL+nySXuW9QQdCcTl1Jp8gVDl2YPbB0uExqv+llEwoMnPZCn0Jg668rtDB0Ht1r0UECaeMN/FR8LZbHBudjxnP3PP6WsytTe6qPU06jTtMAQ8Z5EVSISFuH9rrRiivUiwUT6VsXpWbf1oLukBtLH5R4q1ql28+G20QT4M0/3J+jP3F3xRp7fNtPUg8WbJlJXoln0mpdvYlXv6iC4Xiym+r2N6715Y+6FY+3cR6l91C1SdbVc3ZGE/Hho1twY4C', '2023-12-22'),
(8, '1', 'EwB4A8l6BAAUs5+HQn0N+h2FxWzLS31ZgQVuHsYAAXqkTLN+wS3q0W8yTvUEIrbnPAwejY0kIN3bMj7Iq0EcNIp36B66/7cE12GKFZez8lTx/R7Hy0Afhttm4ST80c7s7pZ6uAoCt9Qo/EAkmEgTBr+YW9NRL8GEfjpwqC+LkFseRVe29E1U4W3BkaV8Op48/b6e2Lf1Mj2B55JDicGPtkXx8b5b2Lj6KGuvcqrMc6asSs1oTJgg3Gn9L6rIY5T7cpaVmsIA0Z6OuJDjsM0NdKuMHTJa3XdKeW5TzDuaNm8G4rJxhB/wUpreOZAnpyid87m0ryhsSJ5wYac85u1LcHac5At4CD1421r/8Ak7mL/zThy7i07UEqofQSJ9gjUDZgAACHY6pZyuamr9SAJPy8gptzVT5UlzjyklbdzNcxFh5RQM+j1HuHyfwgM/qdCO7F01gP2YyZ4/NOwDO6UUx+c4HlkZazRxeFLQDbCtdX0lkCO94J7IA9ipn9Mr7VyUPC6onJJJ3/C5W4PWNG/JfqOdeSJiM9PNUbkoaZ0ERQQf5dKBckbPS4x/WhixNadu6OnK7fkBH3nrKV3a/YGOb2DUM25Jsk6UKTpXN0OKoRrZqTLCgak9bxbOYxtAv5PTrpCagJPGJy7l9XmEaGCVgiIcLDjJ1Q3PSkSh5Qla/Brv7YaWI1GWzDvFVTtko6LOEltjSRRZUiPrXeeH1epag0PYVdn9V6Hb4c4Gbt2yslDFnd4FcI6Ln3V1J0lST6zneqD6SrH0UiyKz8xi4PP3r3JbctoEMpE7zQmkjhUqdSA2yRVoL6oR0w1/hRRXgpvV0tXoZ54FJodKkNBu7KpzS/ZbCIXceRZdft7ArXGD9Jhjaqu4hlJodkrAu6/cv6xpJlj8sHZ7lD3wp+8sNSY0ABTkibyOrrHKKTHL+nySXuW9QQdCcTl1Jp8gVDl2YPbB0uExqv+llEwoMnPZCn0Jg668rtDB0Ht1r0UECaeMN/FR8LZbHBudjxnP3PP6WsytTe6qPU06jTtMAQ8Z5EVSISFuH9rrRiivUiwUT6VsXpWbf1oLukBtLH5R4q1ql28+G20QT4M0/3J+jP3F3xRp7fNtPUg8WbJlJXoln0mpdvYlXv6iC4Xiym+r2N6715Y+6FY+3cR6l91C1SdbVc3ZGE/Hho1twY4C', '2023-12-22'),
(9, '1', 'EwB4A8l6BAAUs5+HQn0N+h2FxWzLS31ZgQVuHsYAAXqkTLN+wS3q0W8yTvUEIrbnPAwejY0kIN3bMj7Iq0EcNIp36B66/7cE12GKFZez8lTx/R7Hy0Afhttm4ST80c7s7pZ6uAoCt9Qo/EAkmEgTBr+YW9NRL8GEfjpwqC+LkFseRVe29E1U4W3BkaV8Op48/b6e2Lf1Mj2B55JDicGPtkXx8b5b2Lj6KGuvcqrMc6asSs1oTJgg3Gn9L6rIY5T7cpaVmsIA0Z6OuJDjsM0NdKuMHTJa3XdKeW5TzDuaNm8G4rJxhB/wUpreOZAnpyid87m0ryhsSJ5wYac85u1LcHac5At4CD1421r/8Ak7mL/zThy7i07UEqofQSJ9gjUDZgAACHY6pZyuamr9SAJPy8gptzVT5UlzjyklbdzNcxFh5RQM+j1HuHyfwgM/qdCO7F01gP2YyZ4/NOwDO6UUx+c4HlkZazRxeFLQDbCtdX0lkCO94J7IA9ipn9Mr7VyUPC6onJJJ3/C5W4PWNG/JfqOdeSJiM9PNUbkoaZ0ERQQf5dKBckbPS4x/WhixNadu6OnK7fkBH3nrKV3a/YGOb2DUM25Jsk6UKTpXN0OKoRrZqTLCgak9bxbOYxtAv5PTrpCagJPGJy7l9XmEaGCVgiIcLDjJ1Q3PSkSh5Qla/Brv7YaWI1GWzDvFVTtko6LOEltjSRRZUiPrXeeH1epag0PYVdn9V6Hb4c4Gbt2yslDFnd4FcI6Ln3V1J0lST6zneqD6SrH0UiyKz8xi4PP3r3JbctoEMpE7zQmkjhUqdSA2yRVoL6oR0w1/hRRXgpvV0tXoZ54FJodKkNBu7KpzS/ZbCIXceRZdft7ArXGD9Jhjaqu4hlJodkrAu6/cv6xpJlj8sHZ7lD3wp+8sNSY0ABTkibyOrrHKKTHL+nySXuW9QQdCcTl1Jp8gVDl2YPbB0uExqv+llEwoMnPZCn0Jg668rtDB0Ht1r0UECaeMN/FR8LZbHBudjxnP3PP6WsytTe6qPU06jTtMAQ8Z5EVSISFuH9rrRiivUiwUT6VsXpWbf1oLukBtLH5R4q1ql28+G20QT4M0/3J+jP3F3xRp7fNtPUg8WbJlJXoln0mpdvYlXv6iC4Xiym+r2N6715Y+6FY+3cR6l91C1SdbVc3ZGE/Hho1twY4C', '2023-12-22'),
(10, '1', 'EwB4A8l6BAAUs5+HQn0N+h2FxWzLS31ZgQVuHsYAAXqkTLN+wS3q0W8yTvUEIrbnPAwejY0kIN3bMj7Iq0EcNIp36B66/7cE12GKFZez8lTx/R7Hy0Afhttm4ST80c7s7pZ6uAoCt9Qo/EAkmEgTBr+YW9NRL8GEfjpwqC+LkFseRVe29E1U4W3BkaV8Op48/b6e2Lf1Mj2B55JDicGPtkXx8b5b2Lj6KGuvcqrMc6asSs1oTJgg3Gn9L6rIY5T7cpaVmsIA0Z6OuJDjsM0NdKuMHTJa3XdKeW5TzDuaNm8G4rJxhB/wUpreOZAnpyid87m0ryhsSJ5wYac85u1LcHac5At4CD1421r/8Ak7mL/zThy7i07UEqofQSJ9gjUDZgAACHY6pZyuamr9SAJPy8gptzVT5UlzjyklbdzNcxFh5RQM+j1HuHyfwgM/qdCO7F01gP2YyZ4/NOwDO6UUx+c4HlkZazRxeFLQDbCtdX0lkCO94J7IA9ipn9Mr7VyUPC6onJJJ3/C5W4PWNG/JfqOdeSJiM9PNUbkoaZ0ERQQf5dKBckbPS4x/WhixNadu6OnK7fkBH3nrKV3a/YGOb2DUM25Jsk6UKTpXN0OKoRrZqTLCgak9bxbOYxtAv5PTrpCagJPGJy7l9XmEaGCVgiIcLDjJ1Q3PSkSh5Qla/Brv7YaWI1GWzDvFVTtko6LOEltjSRRZUiPrXeeH1epag0PYVdn9V6Hb4c4Gbt2yslDFnd4FcI6Ln3V1J0lST6zneqD6SrH0UiyKz8xi4PP3r3JbctoEMpE7zQmkjhUqdSA2yRVoL6oR0w1/hRRXgpvV0tXoZ54FJodKkNBu7KpzS/ZbCIXceRZdft7ArXGD9Jhjaqu4hlJodkrAu6/cv6xpJlj8sHZ7lD3wp+8sNSY0ABTkibyOrrHKKTHL+nySXuW9QQdCcTl1Jp8gVDl2YPbB0uExqv+llEwoMnPZCn0Jg668rtDB0Ht1r0UECaeMN/FR8LZbHBudjxnP3PP6WsytTe6qPU06jTtMAQ8Z5EVSISFuH9rrRiivUiwUT6VsXpWbf1oLukBtLH5R4q1ql28+G20QT4M0/3J+jP3F3xRp7fNtPUg8WbJlJXoln0mpdvYlXv6iC4Xiym+r2N6715Y+6FY+3cR6l91C1SdbVc3ZGE/Hho1twY4C', '2023-12-22'),
(11, '1', 'EwB4A8l6BAAUs5+HQn0N+h2FxWzLS31ZgQVuHsYAAXqkTLN+wS3q0W8yTvUEIrbnPAwejY0kIN3bMj7Iq0EcNIp36B66/7cE12GKFZez8lTx/R7Hy0Afhttm4ST80c7s7pZ6uAoCt9Qo/EAkmEgTBr+YW9NRL8GEfjpwqC+LkFseRVe29E1U4W3BkaV8Op48/b6e2Lf1Mj2B55JDicGPtkXx8b5b2Lj6KGuvcqrMc6asSs1oTJgg3Gn9L6rIY5T7cpaVmsIA0Z6OuJDjsM0NdKuMHTJa3XdKeW5TzDuaNm8G4rJxhB/wUpreOZAnpyid87m0ryhsSJ5wYac85u1LcHac5At4CD1421r/8Ak7mL/zThy7i07UEqofQSJ9gjUDZgAACHY6pZyuamr9SAJPy8gptzVT5UlzjyklbdzNcxFh5RQM+j1HuHyfwgM/qdCO7F01gP2YyZ4/NOwDO6UUx+c4HlkZazRxeFLQDbCtdX0lkCO94J7IA9ipn9Mr7VyUPC6onJJJ3/C5W4PWNG/JfqOdeSJiM9PNUbkoaZ0ERQQf5dKBckbPS4x/WhixNadu6OnK7fkBH3nrKV3a/YGOb2DUM25Jsk6UKTpXN0OKoRrZqTLCgak9bxbOYxtAv5PTrpCagJPGJy7l9XmEaGCVgiIcLDjJ1Q3PSkSh5Qla/Brv7YaWI1GWzDvFVTtko6LOEltjSRRZUiPrXeeH1epag0PYVdn9V6Hb4c4Gbt2yslDFnd4FcI6Ln3V1J0lST6zneqD6SrH0UiyKz8xi4PP3r3JbctoEMpE7zQmkjhUqdSA2yRVoL6oR0w1/hRRXgpvV0tXoZ54FJodKkNBu7KpzS/ZbCIXceRZdft7ArXGD9Jhjaqu4hlJodkrAu6/cv6xpJlj8sHZ7lD3wp+8sNSY0ABTkibyOrrHKKTHL+nySXuW9QQdCcTl1Jp8gVDl2YPbB0uExqv+llEwoMnPZCn0Jg668rtDB0Ht1r0UECaeMN/FR8LZbHBudjxnP3PP6WsytTe6qPU06jTtMAQ8Z5EVSISFuH9rrRiivUiwUT6VsXpWbf1oLukBtLH5R4q1ql28+G20QT4M0/3J+jP3F3xRp7fNtPUg8WbJlJXoln0mpdvYlXv6iC4Xiym+r2N6715Y+6FY+3cR6l91C1SdbVc3ZGE/Hho1twY4C', '2023-12-22'),
(12, '1', 'EwB4A8l6BAAUs5+HQn0N+h2FxWzLS31ZgQVuHsYAAXqkTLN+wS3q0W8yTvUEIrbnPAwejY0kIN3bMj7Iq0EcNIp36B66/7cE12GKFZez8lTx/R7Hy0Afhttm4ST80c7s7pZ6uAoCt9Qo/EAkmEgTBr+YW9NRL8GEfjpwqC+LkFseRVe29E1U4W3BkaV8Op48/b6e2Lf1Mj2B55JDicGPtkXx8b5b2Lj6KGuvcqrMc6asSs1oTJgg3Gn9L6rIY5T7cpaVmsIA0Z6OuJDjsM0NdKuMHTJa3XdKeW5TzDuaNm8G4rJxhB/wUpreOZAnpyid87m0ryhsSJ5wYac85u1LcHac5At4CD1421r/8Ak7mL/zThy7i07UEqofQSJ9gjUDZgAACHY6pZyuamr9SAJPy8gptzVT5UlzjyklbdzNcxFh5RQM+j1HuHyfwgM/qdCO7F01gP2YyZ4/NOwDO6UUx+c4HlkZazRxeFLQDbCtdX0lkCO94J7IA9ipn9Mr7VyUPC6onJJJ3/C5W4PWNG/JfqOdeSJiM9PNUbkoaZ0ERQQf5dKBckbPS4x/WhixNadu6OnK7fkBH3nrKV3a/YGOb2DUM25Jsk6UKTpXN0OKoRrZqTLCgak9bxbOYxtAv5PTrpCagJPGJy7l9XmEaGCVgiIcLDjJ1Q3PSkSh5Qla/Brv7YaWI1GWzDvFVTtko6LOEltjSRRZUiPrXeeH1epag0PYVdn9V6Hb4c4Gbt2yslDFnd4FcI6Ln3V1J0lST6zneqD6SrH0UiyKz8xi4PP3r3JbctoEMpE7zQmkjhUqdSA2yRVoL6oR0w1/hRRXgpvV0tXoZ54FJodKkNBu7KpzS/ZbCIXceRZdft7ArXGD9Jhjaqu4hlJodkrAu6/cv6xpJlj8sHZ7lD3wp+8sNSY0ABTkibyOrrHKKTHL+nySXuW9QQdCcTl1Jp8gVDl2YPbB0uExqv+llEwoMnPZCn0Jg668rtDB0Ht1r0UECaeMN/FR8LZbHBudjxnP3PP6WsytTe6qPU06jTtMAQ8Z5EVSISFuH9rrRiivUiwUT6VsXpWbf1oLukBtLH5R4q1ql28+G20QT4M0/3J+jP3F3xRp7fNtPUg8WbJlJXoln0mpdvYlXv6iC4Xiym+r2N6715Y+6FY+3cR6l91C1SdbVc3ZGE/Hho1twY4C', '2023-12-22'),
(13, '1', 'EwB4A8l6BAAUs5+HQn0N+h2FxWzLS31ZgQVuHsYAAXqkTLN+wS3q0W8yTvUEIrbnPAwejY0kIN3bMj7Iq0EcNIp36B66/7cE12GKFZez8lTx/R7Hy0Afhttm4ST80c7s7pZ6uAoCt9Qo/EAkmEgTBr+YW9NRL8GEfjpwqC+LkFseRVe29E1U4W3BkaV8Op48/b6e2Lf1Mj2B55JDicGPtkXx8b5b2Lj6KGuvcqrMc6asSs1oTJgg3Gn9L6rIY5T7cpaVmsIA0Z6OuJDjsM0NdKuMHTJa3XdKeW5TzDuaNm8G4rJxhB/wUpreOZAnpyid87m0ryhsSJ5wYac85u1LcHac5At4CD1421r/8Ak7mL/zThy7i07UEqofQSJ9gjUDZgAACHY6pZyuamr9SAJPy8gptzVT5UlzjyklbdzNcxFh5RQM+j1HuHyfwgM/qdCO7F01gP2YyZ4/NOwDO6UUx+c4HlkZazRxeFLQDbCtdX0lkCO94J7IA9ipn9Mr7VyUPC6onJJJ3/C5W4PWNG/JfqOdeSJiM9PNUbkoaZ0ERQQf5dKBckbPS4x/WhixNadu6OnK7fkBH3nrKV3a/YGOb2DUM25Jsk6UKTpXN0OKoRrZqTLCgak9bxbOYxtAv5PTrpCagJPGJy7l9XmEaGCVgiIcLDjJ1Q3PSkSh5Qla/Brv7YaWI1GWzDvFVTtko6LOEltjSRRZUiPrXeeH1epag0PYVdn9V6Hb4c4Gbt2yslDFnd4FcI6Ln3V1J0lST6zneqD6SrH0UiyKz8xi4PP3r3JbctoEMpE7zQmkjhUqdSA2yRVoL6oR0w1/hRRXgpvV0tXoZ54FJodKkNBu7KpzS/ZbCIXceRZdft7ArXGD9Jhjaqu4hlJodkrAu6/cv6xpJlj8sHZ7lD3wp+8sNSY0ABTkibyOrrHKKTHL+nySXuW9QQdCcTl1Jp8gVDl2YPbB0uExqv+llEwoMnPZCn0Jg668rtDB0Ht1r0UECaeMN/FR8LZbHBudjxnP3PP6WsytTe6qPU06jTtMAQ8Z5EVSISFuH9rrRiivUiwUT6VsXpWbf1oLukBtLH5R4q1ql28+G20QT4M0/3J+jP3F3xRp7fNtPUg8WbJlJXoln0mpdvYlXv6iC4Xiym+r2N6715Y+6FY+3cR6l91C1SdbVc3ZGE/Hho1twY4C', '2023-12-22'),
(14, '1', 'EwB4A8l6BAAUs5+HQn0N+h2FxWzLS31ZgQVuHsYAAXqkTLN+wS3q0W8yTvUEIrbnPAwejY0kIN3bMj7Iq0EcNIp36B66/7cE12GKFZez8lTx/R7Hy0Afhttm4ST80c7s7pZ6uAoCt9Qo/EAkmEgTBr+YW9NRL8GEfjpwqC+LkFseRVe29E1U4W3BkaV8Op48/b6e2Lf1Mj2B55JDicGPtkXx8b5b2Lj6KGuvcqrMc6asSs1oTJgg3Gn9L6rIY5T7cpaVmsIA0Z6OuJDjsM0NdKuMHTJa3XdKeW5TzDuaNm8G4rJxhB/wUpreOZAnpyid87m0ryhsSJ5wYac85u1LcHac5At4CD1421r/8Ak7mL/zThy7i07UEqofQSJ9gjUDZgAACHY6pZyuamr9SAJPy8gptzVT5UlzjyklbdzNcxFh5RQM+j1HuHyfwgM/qdCO7F01gP2YyZ4/NOwDO6UUx+c4HlkZazRxeFLQDbCtdX0lkCO94J7IA9ipn9Mr7VyUPC6onJJJ3/C5W4PWNG/JfqOdeSJiM9PNUbkoaZ0ERQQf5dKBckbPS4x/WhixNadu6OnK7fkBH3nrKV3a/YGOb2DUM25Jsk6UKTpXN0OKoRrZqTLCgak9bxbOYxtAv5PTrpCagJPGJy7l9XmEaGCVgiIcLDjJ1Q3PSkSh5Qla/Brv7YaWI1GWzDvFVTtko6LOEltjSRRZUiPrXeeH1epag0PYVdn9V6Hb4c4Gbt2yslDFnd4FcI6Ln3V1J0lST6zneqD6SrH0UiyKz8xi4PP3r3JbctoEMpE7zQmkjhUqdSA2yRVoL6oR0w1/hRRXgpvV0tXoZ54FJodKkNBu7KpzS/ZbCIXceRZdft7ArXGD9Jhjaqu4hlJodkrAu6/cv6xpJlj8sHZ7lD3wp+8sNSY0ABTkibyOrrHKKTHL+nySXuW9QQdCcTl1Jp8gVDl2YPbB0uExqv+llEwoMnPZCn0Jg668rtDB0Ht1r0UECaeMN/FR8LZbHBudjxnP3PP6WsytTe6qPU06jTtMAQ8Z5EVSISFuH9rrRiivUiwUT6VsXpWbf1oLukBtLH5R4q1ql28+G20QT4M0/3J+jP3F3xRp7fNtPUg8WbJlJXoln0mpdvYlXv6iC4Xiym+r2N6715Y+6FY+3cR6l91C1SdbVc3ZGE/Hho1twY4C', '2023-12-22'),
(15, '1', 'EwB4A8l6BAAUs5+HQn0N+h2FxWzLS31ZgQVuHsYAAXqkTLN+wS3q0W8yTvUEIrbnPAwejY0kIN3bMj7Iq0EcNIp36B66/7cE12GKFZez8lTx/R7Hy0Afhttm4ST80c7s7pZ6uAoCt9Qo/EAkmEgTBr+YW9NRL8GEfjpwqC+LkFseRVe29E1U4W3BkaV8Op48/b6e2Lf1Mj2B55JDicGPtkXx8b5b2Lj6KGuvcqrMc6asSs1oTJgg3Gn9L6rIY5T7cpaVmsIA0Z6OuJDjsM0NdKuMHTJa3XdKeW5TzDuaNm8G4rJxhB/wUpreOZAnpyid87m0ryhsSJ5wYac85u1LcHac5At4CD1421r/8Ak7mL/zThy7i07UEqofQSJ9gjUDZgAACHY6pZyuamr9SAJPy8gptzVT5UlzjyklbdzNcxFh5RQM+j1HuHyfwgM/qdCO7F01gP2YyZ4/NOwDO6UUx+c4HlkZazRxeFLQDbCtdX0lkCO94J7IA9ipn9Mr7VyUPC6onJJJ3/C5W4PWNG/JfqOdeSJiM9PNUbkoaZ0ERQQf5dKBckbPS4x/WhixNadu6OnK7fkBH3nrKV3a/YGOb2DUM25Jsk6UKTpXN0OKoRrZqTLCgak9bxbOYxtAv5PTrpCagJPGJy7l9XmEaGCVgiIcLDjJ1Q3PSkSh5Qla/Brv7YaWI1GWzDvFVTtko6LOEltjSRRZUiPrXeeH1epag0PYVdn9V6Hb4c4Gbt2yslDFnd4FcI6Ln3V1J0lST6zneqD6SrH0UiyKz8xi4PP3r3JbctoEMpE7zQmkjhUqdSA2yRVoL6oR0w1/hRRXgpvV0tXoZ54FJodKkNBu7KpzS/ZbCIXceRZdft7ArXGD9Jhjaqu4hlJodkrAu6/cv6xpJlj8sHZ7lD3wp+8sNSY0ABTkibyOrrHKKTHL+nySXuW9QQdCcTl1Jp8gVDl2YPbB0uExqv+llEwoMnPZCn0Jg668rtDB0Ht1r0UECaeMN/FR8LZbHBudjxnP3PP6WsytTe6qPU06jTtMAQ8Z5EVSISFuH9rrRiivUiwUT6VsXpWbf1oLukBtLH5R4q1ql28+G20QT4M0/3J+jP3F3xRp7fNtPUg8WbJlJXoln0mpdvYlXv6iC4Xiym+r2N6715Y+6FY+3cR6l91C1SdbVc3ZGE/Hho1twY4C', '2023-12-22'),
(16, '1', 'EwB4A8l6BAAUs5+HQn0N+h2FxWzLS31ZgQVuHsYAAXqkTLN+wS3q0W8yTvUEIrbnPAwejY0kIN3bMj7Iq0EcNIp36B66/7cE12GKFZez8lTx/R7Hy0Afhttm4ST80c7s7pZ6uAoCt9Qo/EAkmEgTBr+YW9NRL8GEfjpwqC+LkFseRVe29E1U4W3BkaV8Op48/b6e2Lf1Mj2B55JDicGPtkXx8b5b2Lj6KGuvcqrMc6asSs1oTJgg3Gn9L6rIY5T7cpaVmsIA0Z6OuJDjsM0NdKuMHTJa3XdKeW5TzDuaNm8G4rJxhB/wUpreOZAnpyid87m0ryhsSJ5wYac85u1LcHac5At4CD1421r/8Ak7mL/zThy7i07UEqofQSJ9gjUDZgAACHY6pZyuamr9SAJPy8gptzVT5UlzjyklbdzNcxFh5RQM+j1HuHyfwgM/qdCO7F01gP2YyZ4/NOwDO6UUx+c4HlkZazRxeFLQDbCtdX0lkCO94J7IA9ipn9Mr7VyUPC6onJJJ3/C5W4PWNG/JfqOdeSJiM9PNUbkoaZ0ERQQf5dKBckbPS4x/WhixNadu6OnK7fkBH3nrKV3a/YGOb2DUM25Jsk6UKTpXN0OKoRrZqTLCgak9bxbOYxtAv5PTrpCagJPGJy7l9XmEaGCVgiIcLDjJ1Q3PSkSh5Qla/Brv7YaWI1GWzDvFVTtko6LOEltjSRRZUiPrXeeH1epag0PYVdn9V6Hb4c4Gbt2yslDFnd4FcI6Ln3V1J0lST6zneqD6SrH0UiyKz8xi4PP3r3JbctoEMpE7zQmkjhUqdSA2yRVoL6oR0w1/hRRXgpvV0tXoZ54FJodKkNBu7KpzS/ZbCIXceRZdft7ArXGD9Jhjaqu4hlJodkrAu6/cv6xpJlj8sHZ7lD3wp+8sNSY0ABTkibyOrrHKKTHL+nySXuW9QQdCcTl1Jp8gVDl2YPbB0uExqv+llEwoMnPZCn0Jg668rtDB0Ht1r0UECaeMN/FR8LZbHBudjxnP3PP6WsytTe6qPU06jTtMAQ8Z5EVSISFuH9rrRiivUiwUT6VsXpWbf1oLukBtLH5R4q1ql28+G20QT4M0/3J+jP3F3xRp7fNtPUg8WbJlJXoln0mpdvYlXv6iC4Xiym+r2N6715Y+6FY+3cR6l91C1SdbVc3ZGE/Hho1twY4C', '2023-12-22'),
(17, '1', 'EwB4A8l6BAAUs5+HQn0N+h2FxWzLS31ZgQVuHsYAAXqkTLN+wS3q0W8yTvUEIrbnPAwejY0kIN3bMj7Iq0EcNIp36B66/7cE12GKFZez8lTx/R7Hy0Afhttm4ST80c7s7pZ6uAoCt9Qo/EAkmEgTBr+YW9NRL8GEfjpwqC+LkFseRVe29E1U4W3BkaV8Op48/b6e2Lf1Mj2B55JDicGPtkXx8b5b2Lj6KGuvcqrMc6asSs1oTJgg3Gn9L6rIY5T7cpaVmsIA0Z6OuJDjsM0NdKuMHTJa3XdKeW5TzDuaNm8G4rJxhB/wUpreOZAnpyid87m0ryhsSJ5wYac85u1LcHac5At4CD1421r/8Ak7mL/zThy7i07UEqofQSJ9gjUDZgAACHY6pZyuamr9SAJPy8gptzVT5UlzjyklbdzNcxFh5RQM+j1HuHyfwgM/qdCO7F01gP2YyZ4/NOwDO6UUx+c4HlkZazRxeFLQDbCtdX0lkCO94J7IA9ipn9Mr7VyUPC6onJJJ3/C5W4PWNG/JfqOdeSJiM9PNUbkoaZ0ERQQf5dKBckbPS4x/WhixNadu6OnK7fkBH3nrKV3a/YGOb2DUM25Jsk6UKTpXN0OKoRrZqTLCgak9bxbOYxtAv5PTrpCagJPGJy7l9XmEaGCVgiIcLDjJ1Q3PSkSh5Qla/Brv7YaWI1GWzDvFVTtko6LOEltjSRRZUiPrXeeH1epag0PYVdn9V6Hb4c4Gbt2yslDFnd4FcI6Ln3V1J0lST6zneqD6SrH0UiyKz8xi4PP3r3JbctoEMpE7zQmkjhUqdSA2yRVoL6oR0w1/hRRXgpvV0tXoZ54FJodKkNBu7KpzS/ZbCIXceRZdft7ArXGD9Jhjaqu4hlJodkrAu6/cv6xpJlj8sHZ7lD3wp+8sNSY0ABTkibyOrrHKKTHL+nySXuW9QQdCcTl1Jp8gVDl2YPbB0uExqv+llEwoMnPZCn0Jg668rtDB0Ht1r0UECaeMN/FR8LZbHBudjxnP3PP6WsytTe6qPU06jTtMAQ8Z5EVSISFuH9rrRiivUiwUT6VsXpWbf1oLukBtLH5R4q1ql28+G20QT4M0/3J+jP3F3xRp7fNtPUg8WbJlJXoln0mpdvYlXv6iC4Xiym+r2N6715Y+6FY+3cR6l91C1SdbVc3ZGE/Hho1twY4C', '2023-12-22'),
(18, '1', 'EwB4A8l6BAAUs5+HQn0N+h2FxWzLS31ZgQVuHsYAAXqkTLN+wS3q0W8yTvUEIrbnPAwejY0kIN3bMj7Iq0EcNIp36B66/7cE12GKFZez8lTx/R7Hy0Afhttm4ST80c7s7pZ6uAoCt9Qo/EAkmEgTBr+YW9NRL8GEfjpwqC+LkFseRVe29E1U4W3BkaV8Op48/b6e2Lf1Mj2B55JDicGPtkXx8b5b2Lj6KGuvcqrMc6asSs1oTJgg3Gn9L6rIY5T7cpaVmsIA0Z6OuJDjsM0NdKuMHTJa3XdKeW5TzDuaNm8G4rJxhB/wUpreOZAnpyid87m0ryhsSJ5wYac85u1LcHac5At4CD1421r/8Ak7mL/zThy7i07UEqofQSJ9gjUDZgAACHY6pZyuamr9SAJPy8gptzVT5UlzjyklbdzNcxFh5RQM+j1HuHyfwgM/qdCO7F01gP2YyZ4/NOwDO6UUx+c4HlkZazRxeFLQDbCtdX0lkCO94J7IA9ipn9Mr7VyUPC6onJJJ3/C5W4PWNG/JfqOdeSJiM9PNUbkoaZ0ERQQf5dKBckbPS4x/WhixNadu6OnK7fkBH3nrKV3a/YGOb2DUM25Jsk6UKTpXN0OKoRrZqTLCgak9bxbOYxtAv5PTrpCagJPGJy7l9XmEaGCVgiIcLDjJ1Q3PSkSh5Qla/Brv7YaWI1GWzDvFVTtko6LOEltjSRRZUiPrXeeH1epag0PYVdn9V6Hb4c4Gbt2yslDFnd4FcI6Ln3V1J0lST6zneqD6SrH0UiyKz8xi4PP3r3JbctoEMpE7zQmkjhUqdSA2yRVoL6oR0w1/hRRXgpvV0tXoZ54FJodKkNBu7KpzS/ZbCIXceRZdft7ArXGD9Jhjaqu4hlJodkrAu6/cv6xpJlj8sHZ7lD3wp+8sNSY0ABTkibyOrrHKKTHL+nySXuW9QQdCcTl1Jp8gVDl2YPbB0uExqv+llEwoMnPZCn0Jg668rtDB0Ht1r0UECaeMN/FR8LZbHBudjxnP3PP6WsytTe6qPU06jTtMAQ8Z5EVSISFuH9rrRiivUiwUT6VsXpWbf1oLukBtLH5R4q1ql28+G20QT4M0/3J+jP3F3xRp7fNtPUg8WbJlJXoln0mpdvYlXv6iC4Xiym+r2N6715Y+6FY+3cR6l91C1SdbVc3ZGE/Hho1twY4C', '2023-12-22'),
(19, '1', 'EwB4A8l6BAAUs5+HQn0N+h2FxWzLS31ZgQVuHsYAAXqkTLN+wS3q0W8yTvUEIrbnPAwejY0kIN3bMj7Iq0EcNIp36B66/7cE12GKFZez8lTx/R7Hy0Afhttm4ST80c7s7pZ6uAoCt9Qo/EAkmEgTBr+YW9NRL8GEfjpwqC+LkFseRVe29E1U4W3BkaV8Op48/b6e2Lf1Mj2B55JDicGPtkXx8b5b2Lj6KGuvcqrMc6asSs1oTJgg3Gn9L6rIY5T7cpaVmsIA0Z6OuJDjsM0NdKuMHTJa3XdKeW5TzDuaNm8G4rJxhB/wUpreOZAnpyid87m0ryhsSJ5wYac85u1LcHac5At4CD1421r/8Ak7mL/zThy7i07UEqofQSJ9gjUDZgAACHY6pZyuamr9SAJPy8gptzVT5UlzjyklbdzNcxFh5RQM+j1HuHyfwgM/qdCO7F01gP2YyZ4/NOwDO6UUx+c4HlkZazRxeFLQDbCtdX0lkCO94J7IA9ipn9Mr7VyUPC6onJJJ3/C5W4PWNG/JfqOdeSJiM9PNUbkoaZ0ERQQf5dKBckbPS4x/WhixNadu6OnK7fkBH3nrKV3a/YGOb2DUM25Jsk6UKTpXN0OKoRrZqTLCgak9bxbOYxtAv5PTrpCagJPGJy7l9XmEaGCVgiIcLDjJ1Q3PSkSh5Qla/Brv7YaWI1GWzDvFVTtko6LOEltjSRRZUiPrXeeH1epag0PYVdn9V6Hb4c4Gbt2yslDFnd4FcI6Ln3V1J0lST6zneqD6SrH0UiyKz8xi4PP3r3JbctoEMpE7zQmkjhUqdSA2yRVoL6oR0w1/hRRXgpvV0tXoZ54FJodKkNBu7KpzS/ZbCIXceRZdft7ArXGD9Jhjaqu4hlJodkrAu6/cv6xpJlj8sHZ7lD3wp+8sNSY0ABTkibyOrrHKKTHL+nySXuW9QQdCcTl1Jp8gVDl2YPbB0uExqv+llEwoMnPZCn0Jg668rtDB0Ht1r0UECaeMN/FR8LZbHBudjxnP3PP6WsytTe6qPU06jTtMAQ8Z5EVSISFuH9rrRiivUiwUT6VsXpWbf1oLukBtLH5R4q1ql28+G20QT4M0/3J+jP3F3xRp7fNtPUg8WbJlJXoln0mpdvYlXv6iC4Xiym+r2N6715Y+6FY+3cR6l91C1SdbVc3ZGE/Hho1twY4C', '2023-12-22'),
(20, '1', 'EwB4A8l6BAAUs5+HQn0N+h2FxWzLS31ZgQVuHsYAAXqkTLN+wS3q0W8yTvUEIrbnPAwejY0kIN3bMj7Iq0EcNIp36B66/7cE12GKFZez8lTx/R7Hy0Afhttm4ST80c7s7pZ6uAoCt9Qo/EAkmEgTBr+YW9NRL8GEfjpwqC+LkFseRVe29E1U4W3BkaV8Op48/b6e2Lf1Mj2B55JDicGPtkXx8b5b2Lj6KGuvcqrMc6asSs1oTJgg3Gn9L6rIY5T7cpaVmsIA0Z6OuJDjsM0NdKuMHTJa3XdKeW5TzDuaNm8G4rJxhB/wUpreOZAnpyid87m0ryhsSJ5wYac85u1LcHac5At4CD1421r/8Ak7mL/zThy7i07UEqofQSJ9gjUDZgAACHY6pZyuamr9SAJPy8gptzVT5UlzjyklbdzNcxFh5RQM+j1HuHyfwgM/qdCO7F01gP2YyZ4/NOwDO6UUx+c4HlkZazRxeFLQDbCtdX0lkCO94J7IA9ipn9Mr7VyUPC6onJJJ3/C5W4PWNG/JfqOdeSJiM9PNUbkoaZ0ERQQf5dKBckbPS4x/WhixNadu6OnK7fkBH3nrKV3a/YGOb2DUM25Jsk6UKTpXN0OKoRrZqTLCgak9bxbOYxtAv5PTrpCagJPGJy7l9XmEaGCVgiIcLDjJ1Q3PSkSh5Qla/Brv7YaWI1GWzDvFVTtko6LOEltjSRRZUiPrXeeH1epag0PYVdn9V6Hb4c4Gbt2yslDFnd4FcI6Ln3V1J0lST6zneqD6SrH0UiyKz8xi4PP3r3JbctoEMpE7zQmkjhUqdSA2yRVoL6oR0w1/hRRXgpvV0tXoZ54FJodKkNBu7KpzS/ZbCIXceRZdft7ArXGD9Jhjaqu4hlJodkrAu6/cv6xpJlj8sHZ7lD3wp+8sNSY0ABTkibyOrrHKKTHL+nySXuW9QQdCcTl1Jp8gVDl2YPbB0uExqv+llEwoMnPZCn0Jg668rtDB0Ht1r0UECaeMN/FR8LZbHBudjxnP3PP6WsytTe6qPU06jTtMAQ8Z5EVSISFuH9rrRiivUiwUT6VsXpWbf1oLukBtLH5R4q1ql28+G20QT4M0/3J+jP3F3xRp7fNtPUg8WbJlJXoln0mpdvYlXv6iC4Xiym+r2N6715Y+6FY+3cR6l91C1SdbVc3ZGE/Hho1twY4C', '2023-12-22'),
(21, '1', 'EwB4A8l6BAAUs5+HQn0N+h2FxWzLS31ZgQVuHsYAAXqkTLN+wS3q0W8yTvUEIrbnPAwejY0kIN3bMj7Iq0EcNIp36B66/7cE12GKFZez8lTx/R7Hy0Afhttm4ST80c7s7pZ6uAoCt9Qo/EAkmEgTBr+YW9NRL8GEfjpwqC+LkFseRVe29E1U4W3BkaV8Op48/b6e2Lf1Mj2B55JDicGPtkXx8b5b2Lj6KGuvcqrMc6asSs1oTJgg3Gn9L6rIY5T7cpaVmsIA0Z6OuJDjsM0NdKuMHTJa3XdKeW5TzDuaNm8G4rJxhB/wUpreOZAnpyid87m0ryhsSJ5wYac85u1LcHac5At4CD1421r/8Ak7mL/zThy7i07UEqofQSJ9gjUDZgAACHY6pZyuamr9SAJPy8gptzVT5UlzjyklbdzNcxFh5RQM+j1HuHyfwgM/qdCO7F01gP2YyZ4/NOwDO6UUx+c4HlkZazRxeFLQDbCtdX0lkCO94J7IA9ipn9Mr7VyUPC6onJJJ3/C5W4PWNG/JfqOdeSJiM9PNUbkoaZ0ERQQf5dKBckbPS4x/WhixNadu6OnK7fkBH3nrKV3a/YGOb2DUM25Jsk6UKTpXN0OKoRrZqTLCgak9bxbOYxtAv5PTrpCagJPGJy7l9XmEaGCVgiIcLDjJ1Q3PSkSh5Qla/Brv7YaWI1GWzDvFVTtko6LOEltjSRRZUiPrXeeH1epag0PYVdn9V6Hb4c4Gbt2yslDFnd4FcI6Ln3V1J0lST6zneqD6SrH0UiyKz8xi4PP3r3JbctoEMpE7zQmkjhUqdSA2yRVoL6oR0w1/hRRXgpvV0tXoZ54FJodKkNBu7KpzS/ZbCIXceRZdft7ArXGD9Jhjaqu4hlJodkrAu6/cv6xpJlj8sHZ7lD3wp+8sNSY0ABTkibyOrrHKKTHL+nySXuW9QQdCcTl1Jp8gVDl2YPbB0uExqv+llEwoMnPZCn0Jg668rtDB0Ht1r0UECaeMN/FR8LZbHBudjxnP3PP6WsytTe6qPU06jTtMAQ8Z5EVSISFuH9rrRiivUiwUT6VsXpWbf1oLukBtLH5R4q1ql28+G20QT4M0/3J+jP3F3xRp7fNtPUg8WbJlJXoln0mpdvYlXv6iC4Xiym+r2N6715Y+6FY+3cR6l91C1SdbVc3ZGE/Hho1twY4C', '2023-12-22'),
(22, '5', 'EwB4A8l6BAAUs5+HQn0N+h2FxWzLS31ZgQVuHsYAAcXMdLyPoWVejQZiEaNeREWKafp0QFxyEGDdfjLxqYx8KKKa95SmPHz57VGgmPQ2Q6dpiOoQ6rHF/e4KPQsduD9HzvTp9jw6dhe+IuCsL+2uafd/HicrZs61bjycAaVgcMBWBQFfk0wIG0AN2G+/NSK5DPvNlPx4S2+37iOa8l1Xl9w7GZsV5lw9F5c6zLYq7Qr/DiXuFNWmRXIE3t1rYeFyxjktxfftb37nvvW8IWeBcXPOh48+OCqdxh2iyrj9KV0n2YaJtOY75bL4o2HXDH2gyR5Ly2bp3yTe88LbFsdn89N0Hz3eKCxlLi+vxpI1s8bW1aU2SLo87Ct1boqpZNkDZgAACN/vmIA5Ez9YSAJYBX5Z7k0I8m/+bSZJo1RE/hRnQGUn3Hm0zCovHpUQvHDTz7I5IjIXWY7wrc7jUvCEegBk/WDmBaVV76QI+v7pJeL44l+rKe+jI0eL9Wpn/iELVbDMbA/2LxqQOk5KKS8ng4gI+OyIVt5WfD65lvNZH0rzviukW5HIT+86LzSt4ZqwKy1H617lYiQ0ktuOC5N+vLQbkqIfJu1YX09V+g5174vtZtR6AUugxBRDtbC8BmDosEt4gbs5F774tY+7q3U+cNUgKRUIzscMkiPG71MQuBSEK2pl4wzNuhmhBcSI6c0u13bqfQcrjg8wZ6we5rfrzKB2GDvKx7QSXDRz3JjTtDWD7mY7MC7Rs1g3Nd8dFycpvb88tDkUG10MPPdsGqduqqqehL1Oq7Jjqko8mGCkTMb2DAgCvzSjVGZkOiYSTFsLsjXxC9GkbUEDVRrUvzphuom2MH7N5BBrX1iXf4+HEwzTZFhyxGSQhOiDn1ea0EMITl/BTLtveN3EULfZL9w4Y4DiTGbPFfcM4ph4pTQt5MO6xPEpToM8hR5JySFtZogg3hUatjvZ7n/twtFtS2aJpCuI4hdMJ/1Lkmwtt7/QgXyQo+p6/BbAQH/ECcmUlalwQso/0UA3WCoAY1LbOLGB4cnk5/jASeVFjECA9yKugmkOdpUqCUvocvbUJ0aR9ckLly87UBYBOY+YryHdWkLxH9VL2aPpWw40zfE5qZHsm8Otr43ckA843ayZxBoYFVza+Hub94STl8lBEDdSaRrtIArj/RL4/o4C', '2024-02-01');

-- --------------------------------------------------------

--
-- Table structure for table `organization`
--

CREATE TABLE `organization` (
  `id` int(11) NOT NULL,
  `app_id` text NOT NULL,
  `logo` text NOT NULL,
  `name` text NOT NULL,
  `email` text NOT NULL,
  `number` text NOT NULL,
  `address` text NOT NULL,
  `postcode` text NOT NULL,
  `city` text NOT NULL,
  `country` text NOT NULL,
  `registration_number` text NOT NULL,
  `user_limit` text NOT NULL,
  `status` text NOT NULL,
  `enddate` text NOT NULL,
  `createdOn` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `organization`
--

INSERT INTO `organization` (`id`, `app_id`, `logo`, `name`, `email`, `number`, `address`, `postcode`, `city`, `country`, `registration_number`, `user_limit`, `status`, `enddate`, `createdOn`) VALUES
(1, '169325', 'logo/386238960.jpg', 'Nocturnal Recruitment Solutions Limited', 'info@nocturnalrecruitment.co.uk', '0208 050 2708', '71-75 Shelton Street ', 'WC2H 9JQ', 'London', 'United Kingdom ', '11817091', '8', '', '', '2023-04-08'),
(3, '169326', 'logo/98711338.png', 'Andie Recruitment Solutions Limited', 'musengemichaeljr@gmail.com', '0940231311', 'Barts Health NHS Trust', '4GW SSI102', 'London', 'London', '0845 242 6201', '4', '', '', '2023-04-08'),
(5, '950862c44989d6795534f8415257b08a', 'NULL', 'Andie Recruitment Solutions Limited', 'musengemichaeljr@gmail.com', '0777504081', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '10', 'demo', '2023-05-21', '2023-05-18 13:08:29'),
(7, 'aec6528a817325faa4e43b64659ea81a', 'NULL', 'FRCE', 'chax@chaxnoble.com', '07886027990', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '3', 'Pending', '2023-06-10', '2023-06-07 13:35:15'),
(8, 'e1de9d46101bad89008ff85427e88851', 'NULL', 'hEcRskZTMlSSnrjw', '', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:01:10'),
(9, '826012e9f44b8727fae89579f2b061c6', 'NULL', 'hEcRskZTMlSSnrjw', 'c:/Windows/system.ini', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:07:15'),
(10, '2a32fa5ad302fb2e7f2b2a69417c658b', 'NULL', 'hEcRskZTMlSSnrjw', '../../../../../../../../../../../../../../../../Windows/system.ini', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:07:17'),
(11, '867fd82e9fdd54e5209f59c2e65ebfc8', 'NULL', 'hEcRskZTMlSSnrjw', 'c:\\Windows\\system.ini', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:07:19'),
(12, '952717803b4ab3c72f78b4bd177dd5ec', 'NULL', 'hEcRskZTMlSSnrjw', '..\\..\\..\\..\\..\\..\\..\\..\\..\\..\\..\\..\\..\\..\\..\\..\\Windows\\system.ini', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:07:21'),
(13, '4cc9efd3a1da75f166e9d2b4aea8177b', 'NULL', 'hEcRskZTMlSSnrjw', '/etc/passwd', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:07:23'),
(14, '9fa6ebf0754616268df069cb62accca1', 'NULL', 'hEcRskZTMlSSnrjw', '../../../../../../../../../../../../../../../../etc/passwd', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:07:25'),
(15, '00847d00c445fddf18c1759b633e1c30', 'NULL', 'hEcRskZTMlSSnrjw', 'c:/', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:07:27'),
(16, '44830d71bf081154a37bf477ebb2c3eb', 'NULL', 'hEcRskZTMlSSnrjw', '/', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:07:29'),
(17, '1ceeab92327aef75905acecdc8d10f6f', 'NULL', 'hEcRskZTMlSSnrjw', 'c:\\', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:07:31'),
(18, '14b2a053061516420ebdf7a4a58126fc', 'NULL', 'hEcRskZTMlSSnrjw', '../../../../../../../../../../../../../../../../', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:07:33'),
(19, '303fd0b061da0bc5ba40bd7aeed28865', 'NULL', 'hEcRskZTMlSSnrjw', 'WEB-INF/web.xml', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:07:35'),
(20, '74c77500dd4c843e109a6634150b70c1', 'NULL', 'hEcRskZTMlSSnrjw', 'WEB-INF\\web.xml', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:07:37'),
(21, 'cdc95a91376936fb807f3d6c64398f7c', 'NULL', 'hEcRskZTMlSSnrjw', '/WEB-INF/web.xml', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:07:39'),
(22, '8085b7a9cc565b5662eade34bc8e02b6', 'NULL', 'hEcRskZTMlSSnrjw', '\\WEB-INF\\web.xml', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:07:41'),
(23, '84792bcb442cc006d4bc62f99830b8f0', 'NULL', 'hEcRskZTMlSSnrjw', 'thishouldnotexistandhopefullyitwillnot', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:07:43'),
(24, '5d7166c3c900b2600ccce6db79098d46', 'NULL', 'hEcRskZTMlSSnrjw', 'http://www.google.com/', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:09:10'),
(25, '0abcd9cd4804b04f2008cb7707b20218', 'NULL', 'hEcRskZTMlSSnrjw', 'http://www.google.com:80/', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:09:11'),
(26, '70929dde022b3c77ada319d749ac25c1', 'NULL', 'hEcRskZTMlSSnrjw', 'http://www.google.com', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:09:12'),
(27, '432e543a3adbfc64d52f4e97f1141574', 'NULL', 'hEcRskZTMlSSnrjw', 'http://www.google.com/search?q=OWASP%20ZAP', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:09:13'),
(28, '78d121671e68c3d1517107527bc5ca57', 'NULL', 'hEcRskZTMlSSnrjw', 'http://www.google.com:80/search?q=OWASP%20ZAP', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:09:14'),
(29, '32ab79e48536ad050fe7bc53eaa23330', 'NULL', 'hEcRskZTMlSSnrjw', 'www.google.com/', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:09:15'),
(30, 'e2d9bf4f1442308586704530412dbd73', 'NULL', 'hEcRskZTMlSSnrjw', 'www.google.com:80/', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:09:15'),
(31, '3f297a1749190a75de443c64bdbae73c', 'NULL', 'hEcRskZTMlSSnrjw', 'www.google.com', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:09:16'),
(32, 'b7fa69a7a4b3c64e4086812ca32a550f', 'NULL', 'hEcRskZTMlSSnrjw', 'www.google.com/search?q=OWASP%20ZAP', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:09:17'),
(33, 'e2a93e2d505cfc490a9177288046c476', 'NULL', 'hEcRskZTMlSSnrjw', 'www.google.com:80/search?q=OWASP%20ZAP', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:09:18'),
(34, 'db07e47b90e86c2caa39ef9919c10db0', 'NULL', 'hEcRskZTMlSSnrjw', '2585487874588521895.owasp.org', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:11:18'),
(35, '846f2ae29169254a3c9b1d2b2aedd99e', 'NULL', 'hEcRskZTMlSSnrjw', 'http://2585487874588521895.owasp.org', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:11:18'),
(36, '5352fc748a09c9ed05b71d37dd30407a', 'NULL', 'hEcRskZTMlSSnrjw', 'https://2585487874588521895.owasp.org', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:11:19'),
(37, '9bee2aa4f66b261d00c2a8d6f44c66e9', 'NULL', 'hEcRskZTMlSSnrjw', 'https://2585487874588521895%2eowasp%2eorg', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:11:20'),
(38, '90fb261709e973c8740618086f7931d5', 'NULL', 'hEcRskZTMlSSnrjw', '5;URL=\'https://2585487874588521895.owasp.org\'', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:11:21'),
(39, '821bc3c35c43950645582012711aab2f', 'NULL', 'hEcRskZTMlSSnrjw', 'URL=\'http://2585487874588521895.owasp.org\'', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:11:22'),
(40, '418a596484a5a3e03c00c33832de117b', 'NULL', 'hEcRskZTMlSSnrjw', 'http://\\2585487874588521895.owasp.org', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:11:23'),
(41, 'd0fa40419cf3c7f6ee39908910297b93', 'NULL', 'hEcRskZTMlSSnrjw', 'https://\\2585487874588521895.owasp.org', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:11:23'),
(42, '9cf3a59b5485269531dcfb7d2c0fd4bd', 'NULL', 'hEcRskZTMlSSnrjw', '//2585487874588521895.owasp.org', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:11:24'),
(43, 'fe1099305236ef30488b4084b5ac6cf8', 'NULL', 'hEcRskZTMlSSnrjw', '<!--#EXEC cmd=\"ls /\"-->', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:12:15'),
(44, '8fe61f88648d7c9a720e5c9db6ebe93c', 'NULL', 'hEcRskZTMlSSnrjw', '\"><!--#EXEC cmd=\"ls /\"--><', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:12:17'),
(45, 'd93cebca4989b87d9a1802568c85bed8', 'NULL', 'hEcRskZTMlSSnrjw', '<!--#EXEC cmd=\"dir \\\"-->', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:12:19'),
(46, 'b18f1787239151d539c8dab343466a3d', 'NULL', 'hEcRskZTMlSSnrjw', '\"><!--#EXEC cmd=\"dir \\\"--><', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:12:21'),
(47, '22524087c2135300daf43559785c44d5', 'NULL', 'hEcRskZTMlSSnrjw', '0W45pz4p', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:12:48'),
(48, '8da06181b560b7e3b9fc7190b2aaf7d2', 'NULL', 'hEcRskZTMlSSnrjw', '\'\"<scrIpt>alert(1);</scRipt>', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:12:51'),
(49, 'cdb0861c660260467c4354614e7d9634', 'NULL', 'hEcRskZTMlSSnrjw', '\'\"\0<scrIpt>alert(1);</scRipt>', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:12:52'),
(50, 'a30c32b33fa05040fd37add081aaedd0', 'NULL', 'hEcRskZTMlSSnrjw', '\'\"<img src=x onerror=prompt()>', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:12:54'),
(51, '638b192220e8adcb7e81899668aaa946', 'NULL', 'hEcRskZTMlSSnrjw', 'zApPX3sS', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:13:25'),
(52, 'd70792753ac3ede45ceab3ab36ace111', 'NULL', 'hEcRskZTMlSSnrjw', '\'', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:14:50'),
(53, '738a7ff1208d5adbba7883358fd2bc63', 'NULL', 'hEcRskZTMlSSnrjw', '\"', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:14:52'),
(54, '1e21af545819112a5908c14b26fc4d24', 'NULL', 'hEcRskZTMlSSnrjw', ';', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:14:54'),
(55, 'ca2cea3b1b421915e71745e144439ace', 'NULL', 'hEcRskZTMlSSnrjw', '\'(', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:14:55'),
(56, 'f50ace5228288fe779e6586e1eef2189', 'NULL', 'hEcRskZTMlSSnrjw', ' AND 1=1 -- ', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:14:59'),
(57, '95342afb9ebd8ca86d1d9c7ea6f335ad', 'NULL', 'hEcRskZTMlSSnrjw', '\' AND \'1\'=\'1\' -- ', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:14:59'),
(58, '652ba22ee7dd105cc09ce871378cea59', 'NULL', 'hEcRskZTMlSSnrjw', '\" AND \"1\"=\"1\" -- ', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:15:00'),
(59, '776b9de02a2de9229c9c618635bd70da', 'NULL', 'hEcRskZTMlSSnrjw', ' AND 1=1', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:15:01'),
(60, 'b207a1788f114a57d1c3986bbfb2c7c2', 'NULL', 'hEcRskZTMlSSnrjw', '\' AND \'1\'=\'1', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:15:02'),
(61, '17e7f676b5e75c4975a1f56a059c3bbe', 'NULL', 'hEcRskZTMlSSnrjw', '\" AND \"1\"=\"1', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:15:03'),
(62, '78fd7579a36c9cd470977ca2b970782b', 'NULL', 'hEcRskZTMlSSnrjw', ' UNION ALL select NULL -- ', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:15:04'),
(63, 'f99a3a0f98639872c630d269638271a6', 'NULL', 'hEcRskZTMlSSnrjw', '\' UNION ALL select NULL -- ', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:15:04'),
(64, '522d59fc1d1431bf421e2b387c2e80a8', 'NULL', 'hEcRskZTMlSSnrjw', '\" UNION ALL select NULL -- ', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:15:05'),
(65, '600e95b045d87121a75dae9a99b217bc', 'NULL', 'hEcRskZTMlSSnrjw', ') UNION ALL select NULL -- ', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:15:06'),
(66, '46293fc9c139d3dcc4d55a9ea5491675', 'NULL', 'hEcRskZTMlSSnrjw', '\') UNION ALL select NULL -- ', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:15:07'),
(67, '8ffa32cb7e5d57470a631042aa5b1b42', 'NULL', 'hEcRskZTMlSSnrjw', ' / sleep(15) ', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:17:06'),
(68, 'ee55e92bcdd9760a270fca5e6fa1da24', 'NULL', 'hEcRskZTMlSSnrjw', '\' / sleep(15) / \'', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:17:07'),
(69, '078dc75282d0037e95b6574f317dbef8', 'NULL', 'hEcRskZTMlSSnrjw', '\" / sleep(15) / \"', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:17:08'),
(70, 'd198386e168386f71f5bdde610881f14', 'NULL', 'hEcRskZTMlSSnrjw', ' and 0 in (select sleep(15) ) -- ', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:17:08'),
(71, '9be966c719aa163516da202f48533d5b', 'NULL', 'hEcRskZTMlSSnrjw', '\' and 0 in (select sleep(15) ) -- ', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:17:09'),
(72, '43985c49a34538e531e14ac5a2bfc529', 'NULL', 'hEcRskZTMlSSnrjw', '\" and 0 in (select sleep(15) ) -- ', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:17:10'),
(73, '7a30108e2c29320b009ebfe7d1aabfe3', 'NULL', 'hEcRskZTMlSSnrjw', '; select \"java.lang.Thread.sleep\"(15000) from INFORMATION_SCHEMA.SYSTEM_COLUMNS where TABLE_NAME = \'SYSTEM_COLUMNS\' and COLUMN_NAME = \'TABLE_NAME\' -- ', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:17:51'),
(74, 'e084162a17c54bc70e936f47c341170a', 'NULL', 'hEcRskZTMlSSnrjw', '\'; select \"java.lang.Thread.sleep\"(15000) from INFORMATION_SCHEMA.SYSTEM_COLUMNS where TABLE_NAME = \'SYSTEM_COLUMNS\' and COLUMN_NAME = \'TABLE_NAME\' -- ', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:17:52'),
(75, '1c219bcdfae82f6040b74b7f5acdfde2', 'NULL', 'hEcRskZTMlSSnrjw', '\"; select \"java.lang.Thread.sleep\"(15000) from INFORMATION_SCHEMA.SYSTEM_COLUMNS where TABLE_NAME = \'SYSTEM_COLUMNS\' and COLUMN_NAME = \'TABLE_NAME\' -- ', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:17:53'),
(76, '5f89207df274c6a06baadde09cfdfcb6', 'NULL', 'hEcRskZTMlSSnrjw', '); select \"java.lang.Thread.sleep\"(15000) from INFORMATION_SCHEMA.SYSTEM_COLUMNS where TABLE_NAME = \'SYSTEM_COLUMNS\' and COLUMN_NAME = \'TABLE_NAME\' -- ', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:17:53'),
(77, 'a92481ed03bf1cdb9754b19d9453c996', 'NULL', 'hEcRskZTMlSSnrjw', '\"java.lang.Thread.sleep\"(15000)', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:17:54'),
(78, '1f393302ee0bc523c876d46d9214c996', 'NULL', 'hEcRskZTMlSSnrjw', '(SELECT  UTL_INADDR.get_host_name(\'10.0.0.1\') from dual union SELECT  UTL_INADDR.get_host_name(\'10.0.0.2\') from dual union SELECT  UTL_INADDR.get_host_name(\'10.0.0.3\') from dual union SELECT  UTL_INADDR.get_host_name(\'10.0.0.4\') from dual union SELECT  UTL_INADDR.get_host_name(\'10.0.0.5\') from dual)', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:18:42'),
(79, '836647c2125ebd6498a3399d33f7adef', 'NULL', 'hEcRskZTMlSSnrjw', ' / (SELECT  UTL_INADDR.get_host_name(\'10.0.0.1\') from dual union SELECT  UTL_INADDR.get_host_name(\'10.0.0.2\') from dual union SELECT  UTL_INADDR.get_host_name(\'10.0.0.3\') from dual union SELECT  UTL_INADDR.get_host_name(\'10.0.0.4\') from dual union SELECT  UTL_INADDR.get_host_name(\'10.0.0.5\') from dual) ', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:18:43'),
(80, '16950328aa0926d0aa231ab912415714', 'NULL', 'hEcRskZTMlSSnrjw', '\' / (SELECT  UTL_INADDR.get_host_name(\'10.0.0.1\') from dual union SELECT  UTL_INADDR.get_host_name(\'10.0.0.2\') from dual union SELECT  UTL_INADDR.get_host_name(\'10.0.0.3\') from dual union SELECT  UTL_INADDR.get_host_name(\'10.0.0.4\') from dual union SELECT  UTL_INADDR.get_host_name(\'10.0.0.5\') from dual) / \'', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:18:43'),
(81, 'af768c46bd3475c22ba45a4d092cffba', 'NULL', 'hEcRskZTMlSSnrjw', '\" / (SELECT  UTL_INADDR.get_host_name(\'10.0.0.1\') from dual union SELECT  UTL_INADDR.get_host_name(\'10.0.0.2\') from dual union SELECT  UTL_INADDR.get_host_name(\'10.0.0.3\') from dual union SELECT  UTL_INADDR.get_host_name(\'10.0.0.4\') from dual union SELECT  UTL_INADDR.get_host_name(\'10.0.0.5\') from dual) / \"', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:18:44'),
(82, '9ec6071dc6922d4334665c218ba05d32', 'NULL', 'hEcRskZTMlSSnrjw', ' and exists (SELECT  UTL_INADDR.get_host_name(\'10.0.0.1\') from dual union SELECT  UTL_INADDR.get_host_name(\'10.0.0.2\') from dual union SELECT  UTL_INADDR.get_host_name(\'10.0.0.3\') from dual union SELECT  UTL_INADDR.get_host_name(\'10.0.0.4\') from dual union SELECT  UTL_INADDR.get_host_name(\'10.0.0.5\') from dual) -- ', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:18:45'),
(83, '4b8529063e2193cde279cfc31c751a29', 'NULL', 'hEcRskZTMlSSnrjw', 'case when cast(pg_sleep(15) as varchar) > \'\' then 0 else 1 end', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:19:23'),
(84, 'e1202a4ba3588f25db6f22d039216924', 'NULL', 'hEcRskZTMlSSnrjw', 'case when cast(pg_sleep(15) as varchar) > \'\' then 0 else 1 end -- ', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:19:24'),
(85, 'acf674fb46d2691ff2fbb67e5570b7a7', 'NULL', 'hEcRskZTMlSSnrjw', '\'case when cast(pg_sleep(15) as varchar) > \'\' then 0 else 1 end -- ', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:19:25'),
(86, '2cd450cca81a633f15383980e041fc50', 'NULL', 'hEcRskZTMlSSnrjw', '\"case when cast(pg_sleep(15) as varchar) > \'\' then 0 else 1 end -- ', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:19:26'),
(87, 'daf89e45d9db916f5cebd6aed346cb24', 'NULL', 'hEcRskZTMlSSnrjw', ' / case when cast(pg_sleep(15) as varchar) > \'\' then 0 else 1 end ', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:19:26'),
(88, '1ded6ec803f84dc1ca061914041a1c3c', 'NULL', 'hEcRskZTMlSSnrjw', 'case randomblob(100000) when not null then 1 else 1 end ', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:20:08'),
(89, '3567b4cfccd672b8fdba58ae362f1c2a', 'NULL', 'hEcRskZTMlSSnrjw', 'case randomblob(1000000) when not null then 1 else 1 end ', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:20:09'),
(90, 'd021ccad24741050a463968875f4e712', 'NULL', 'hEcRskZTMlSSnrjw', '5najzyk989xne1c7zvldnllg7qnamcpgeknbvfbztrw6r6xa5xb1i1z2j', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:20:11'),
(91, 'f3ec1cb9b954f7bc2b41ee37d864a99c', 'NULL', 'hEcRskZTMlSSnrjw', 'case randomblob(10000000) when not null then 1 else 1 end ', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:20:13'),
(92, '7b9e67af8bbde94c80691b172586ae12', 'NULL', 'hEcRskZTMlSSnrjw', 'case randomblob(100000000) when not null then 1 else 1 end ', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:20:15'),
(93, '2c6bf9ef6d396c04704b1b74fdc8c9fa', 'NULL', 'hEcRskZTMlSSnrjw', '8mtb4j0dbm6wal5tfq6epfjqxosmqdlc8r6qzf73g6brxn5otnwez4tr4mz', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:20:17'),
(94, '54f6e6d0c0ddb243d5fb93d48b364876', 'NULL', 'hEcRskZTMlSSnrjw', 'case randomblob(1000000000) when not null then 1 else 1 end ', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:20:19'),
(95, '1505a0227dd363c4c6478e86198e6c3b', 'NULL', '#jaVasCript:/*-/*`/*\\`/*\'/*\"/**/(/* */oNcliCk=alert(5397) )//%0D%0A%0d%0a//</stYle/</titLe/</teXtarEa/</scRipt/--!>\\x3csVg/<sVg/oNloAd=alert(5397)//>\\x3e', '#jaVasCript:/*-/*`/*\\`/*\'/*\"/**/(/* */oNcliCk=alert(5397) )//%0D%0A%0d%0a//</stYle/</titLe/</teXtarEa/</scRipt/--!>\\x3csVg/<sVg/oNloAd=alert(5397)//>\\x3e', '#jaVasCript:/*-/*`/*\\`/*\'/*\"/**/(/* */oNcliCk=alert(5397) )//%0D%0A%0d%0a//</stYle/</titLe/</teXtarEa/</scRipt/--!>\\x3csVg/<sVg/oNloAd=alert(5397)//>\\x3e', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '3', 'Pending', '2023-06-10', '2023-06-07 14:21:28'),
(96, 'fb5b443bead25c79470f23f71efabc1d', 'NULL', '?name=abc#<img src=\"random.gif\" onerror=alert(5397)>', '?name=abc#<img src=\"random.gif\" onerror=alert(5397)>', '?name=abc#<img src=\"random.gif\" onerror=alert(5397)>', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '3', 'Pending', '2023-06-10', '2023-06-07 14:21:35'),
(97, '259f414a04ed7204da2050129ae54a20', 'NULL', '#javascript:alert(5397)', '#javascript:alert(5397)', '#javascript:alert(5397)', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '3', 'Pending', '2023-06-10', '2023-06-07 14:21:36'),
(98, '66710d4f577eceba00d1a2ca38076fd9', 'NULL', 'hEcRskZTMlSSnrjw', '\";print(chr(122).chr(97).chr(112).chr(95).chr(116).chr(111).chr(107).chr(101).chr(110));$var=\"', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:28:52'),
(99, '3b46d675b64b035b372e18d61ce03242', 'NULL', 'hEcRskZTMlSSnrjw', '\';print(chr(122).chr(97).chr(112).chr(95).chr(116).chr(111).chr(107).chr(101).chr(110));$var=\'', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:28:53'),
(100, '576dce81c20f2aa2eea9a72ebade3fa5', 'NULL', 'hEcRskZTMlSSnrjw', '${@print(chr(122).chr(97).chr(112).chr(95).chr(116).chr(111).chr(107).chr(101).chr(110))}', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:28:54'),
(101, '2cfe223e17614d6f8e4c85505750baee', 'NULL', 'hEcRskZTMlSSnrjw', '${@print(chr(122).chr(97).chr(112).chr(95).chr(116).chr(111).chr(107).chr(101).chr(110))}\\', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:28:55'),
(102, 'a787069dd5d9c8c5795a06c4d7a5cc87', 'NULL', 'hEcRskZTMlSSnrjw', ';print(chr(122).chr(97).chr(112).chr(95).chr(116).chr(111).chr(107).chr(101).chr(110));', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:28:56'),
(103, '60da265818a871aa56c137f74e473655', 'NULL', 'hEcRskZTMlSSnrjw', '\"+response.write(988,208*741,568)+\"', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:28:56'),
(104, 'ac8bc5dff36d99c7edf53623eda80880', 'NULL', 'hEcRskZTMlSSnrjw', '+response.write({0}*{1})+', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:28:57'),
(105, 'dd90ac913a12fe2a05973cd54167daa7', 'NULL', 'hEcRskZTMlSSnrjw', 'response.write(988,208*741,568)', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:28:58'),
(106, '08ea7dc2acffc3928ff18fb0b097eca9', 'NULL', 'hEcRskZTMlSSnrjw', 'cat /etc/passwd', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:30:12'),
(107, '629d8bd288aa3e11b403dd7234b7c820', 'NULL', 'hEcRskZTMlSSnrjw', '&cat /etc/passwd&', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:30:13'),
(108, '19ce459c5d7876f5097151e51b91e2a4', 'NULL', 'hEcRskZTMlSSnrjw', ';cat /etc/passwd;', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:30:14'),
(109, '9e0faa00fdde7df928c5bf2720b1ea6e', 'NULL', 'hEcRskZTMlSSnrjw', '\"&cat /etc/passwd&\"', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:30:14'),
(110, 'e12a5f16f4b959eb557731c5d37acfcf', 'NULL', 'hEcRskZTMlSSnrjw', '\";cat /etc/passwd;\"', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:30:15'),
(111, '684bf0c5f5b993cb6a1b936421bbe429', 'NULL', 'hEcRskZTMlSSnrjw', '\'&cat /etc/passwd&\'', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:30:16'),
(112, '79428492dd11043af6d570a00e9964a3', 'NULL', 'hEcRskZTMlSSnrjw', '\';cat /etc/passwd;\'', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:30:17'),
(113, '36c4cbdaedda96405841e2d71166948c', 'NULL', 'hEcRskZTMlSSnrjw', '&sleep 1.0&', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:30:18'),
(114, '06207cf44e79071db8689811f04ccaf5', 'NULL', 'hEcRskZTMlSSnrjw', ';sleep 1.0;', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:30:19'),
(115, 'eee5ce42d5fc87b483dbcbc32d119501', 'NULL', 'hEcRskZTMlSSnrjw', '\"&sleep 1.0&\"', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:30:19'),
(116, '910280956155705485ab22a3cd667c3b', 'NULL', 'hEcRskZTMlSSnrjw', '\";sleep 1.0;\"', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:30:20'),
(117, '755a38e1f001680cfd2a4ff111c3c716', 'NULL', 'hEcRskZTMlSSnrjw', '\'&sleep 1.0&\'', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:30:21'),
(118, '8ef9b088d736858f471d45029f6507f1', 'NULL', 'hEcRskZTMlSSnrjw', '\';sleep 1.0;\'', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:30:22'),
(119, 'd5a8d2868db2f4574106d45af9245398', 'NULL', 'hEcRskZTMlSSnrjw', 'type %SYSTEMROOT%\\win.ini', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:30:23'),
(120, '24150c7b4c6ccb6cf42c8e655986c4c5', 'NULL', 'hEcRskZTMlSSnrjw', '&type %SYSTEMROOT%\\win.ini', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:30:24'),
(121, '7a6a0a7677e061fea3c70754f88ab270', 'NULL', 'hEcRskZTMlSSnrjw', '|type %SYSTEMROOT%\\win.ini', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:30:24'),
(122, '2d1589104ff8157b9e898dd11e6826d7', 'NULL', 'hEcRskZTMlSSnrjw', '\"&type %SYSTEMROOT%\\win.ini&\"', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:30:25'),
(123, '8117303eb939e7b7b886c17382d18c8a', 'NULL', 'hEcRskZTMlSSnrjw', '\"|type %SYSTEMROOT%\\win.ini', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:30:26'),
(124, '2120f5e18a4df78196e7568609782ec5', 'NULL', 'hEcRskZTMlSSnrjw', '\'&type %SYSTEMROOT%\\win.ini&\'', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:30:27'),
(125, 'afb44541df1b9300731509937b654833', 'NULL', 'hEcRskZTMlSSnrjw', '\'|type %SYSTEMROOT%\\win.ini', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:30:28'),
(126, 'dc1f54d47e973da3eee2b5b73bcca275', 'NULL', 'hEcRskZTMlSSnrjw', '&timeout /T 1.0', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:30:29'),
(127, '841e88ce42a530f2e18ba772f3dc4bf3', 'NULL', 'hEcRskZTMlSSnrjw', '|timeout /T 1.0', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:30:29'),
(128, '56db05235cbe0ec5e72502d993f3da71', 'NULL', 'hEcRskZTMlSSnrjw', '\"&timeout /T 1.0&\"', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:30:30'),
(129, 'd62b7e85c125c75d5a95785f7855e0f9', 'NULL', 'hEcRskZTMlSSnrjw', '\"|timeout /T 1.0', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:30:31'),
(130, '8688dd2bdfd0429cc024e737e0649f44', 'NULL', 'hEcRskZTMlSSnrjw', '\'&timeout /T 1.0&\'', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:30:32'),
(131, 'e5302ceff850fed8b8f0f6d7520e37fa', 'NULL', 'hEcRskZTMlSSnrjw', '\'|timeout /T 1.0', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:30:33'),
(132, '9427558184e89c30a322ecd430ba68ce', 'NULL', 'hEcRskZTMlSSnrjw', 'get-help', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:30:34'),
(133, '72ec742970414ef42f074ed790e195f8', 'NULL', 'hEcRskZTMlSSnrjw', ';get-help', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:30:34'),
(134, '6f2ea648fdbb6c9baff38ae4457d3023', 'NULL', 'hEcRskZTMlSSnrjw', '\";get-help', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:30:35'),
(135, '3bfb3e6ffc78c3ad4c9e369263109bbc', 'NULL', 'hEcRskZTMlSSnrjw', '\';get-help', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:30:36'),
(136, '280824bc287c58bfb52cfa09cdfeb0da', 'NULL', 'hEcRskZTMlSSnrjw', ';get-help #', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:30:37'),
(137, '60493b9c51764f0e05e6312893b5ec2f', 'NULL', 'hEcRskZTMlSSnrjw', ';start-sleep -s 1.0', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:30:38'),
(138, '13783c63edeb5dee60646f20128b625a', 'NULL', 'hEcRskZTMlSSnrjw', '\";start-sleep -s 1.0', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:30:38'),
(139, '065745dea27046d25d9a68ff05a11463', 'NULL', 'hEcRskZTMlSSnrjw', '\';start-sleep -s 1.0', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:30:39'),
(140, '258498e6adcfc73a8b1fcc3ac055b2b9', 'NULL', 'hEcRskZTMlSSnrjw', ';start-sleep -s 1.0 #', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:30:40'),
(141, '1328e68f3466ec893ac2040d4a4f0416', 'NULL', 'hEcRskZTMlSSnrjw', 'IambRGQBAnnYRsUFbJtTaRIhanhDqANEABEwhYdZaKtMGpMiUDGVyeMvKedBnQnhrxIZZBoIRkZVtedratFCOXhmYjaEkdHmKWmosBoFqKnZmmYpVvAOGktSOdcvrPCoWDGFujlJKrZwSKOUePaOuLYmJnNqqPVYwUFKYEowQZKcjLGPwIamDVfWIYryKoveiWBFSjqxwZwhkUtlLIBnBJldnehdDwpbmiYGHrnBOInngJDOjMCCBZHmnLRqrRmUepKyyfEpSmbfQoPKIhlJmEbLZOjSJQZamRaUPAeaCKTFYrjOtuURsRIWImvFPbbxfvjLMRbZDHeAJSOqrAfCfEgZpmfRqUMSTrEIuMhesJaLZkMxCuMTqdtHjVMZryFyFfKrwUyQOAcKrBkGeKxAYaWwFtqcgYexBZKFKAgYHNObjIHPLCNAdpgVJAAFBiShhogXMPivDNwTVXRNhDyufMVKhAUnxkJWmkPoUEPrfFIWrLPRqnYhveklEhhCdVNqrKtGUvOZyAsdgCmjjMWmIVPhbPGomxsCvYyeDhrLlgidsMIrIQavkTbHyLrEiXmTkvUuAXYIMfNOJOEJFCfoFXcyHBEixeGAuViWjmBuNIUoMlBHwMfrQSbtiKXRjoichPOyBWPdZrkIxhnidwTVLHJNdLbYQfZAnsTBfnOgnjoEcnVRwVZAurNhVpjJVfMGNcsKpZXaYlMqHauEewekhvfVKyZRUvZDGtGntgJFavKtYPpmTaIlZuDMWqgVcPKCdpANepHJYliarBkZJyPMKJqZJhBqkGHbILdXNtyHcOWPumXfHowBsxbJMifOuvQiSkFwNaNXpkEbGSwXxVGkDMsrDLjIgdDKXjjtHORAswWyKqPTGWCyuJjEgPiAiEUwJuCwEIKeYbVMvbbjruquSVjxmHqNtVWKkoOiveFODjkjcslsoQCcRLashFSoTXcZXNPgMMVlWKTjfMuXoLuUmvNCJnheonUnaQfUPTSFCuskwAWKnfijsNHuqAcjTqaQXVjFlVXcPWisdjbWQMEcuHePCGMBuBtqMdtNfAFdwNEmbtuGOOcIsytYVhqXSyfNsDTFWCAWpcaErqkEwvvHaxBPfyXKGZfaROpdEmUoYDbVXiwrxygXJBqqkHZRjnBRPOEvnVwQvvhKELZsmQaSnfftOAjFVlkZoglsKHuMMXjmpKVNblMwGWPOvWQIyAtHPPIREVKxEpORwUURqfkBMbypYcxsHxsStvTxjJoAFlwdPKXarKcLwmqsJCNmPLWasnOwvqmQVDccCIYlIDPQoAbpXhkBQiRaKuUASFlbgtkunfKRcQqjDfUbqDaiAEgnuJoGsxyyJsehCJXCACudIYugyNeyDJUlfTDaDVwPauHSQGGXtfwvDtpanfZmEHEAHjQBKuxCdPJjSVJVliJZxcDRPSffQrKLpbMyUfHPdqFsUEipwaQNYLPgppNvwxAMXturNmNHgkLMZONfsWaUhlTLHjYtyKIECOvugwinhCCxQkFvYNfNVxJqESsIsGijfytQExrhaeobctGnRsxDViWlvQyhiwMZmrcGWDxSIhgsjnXIqpkmvpQiCNdxgyIlGVaCNZYVxxapepQOVXcjSFfBUsBpIFKTUQtjTRyCAqgduohCTgqtGFaeSIGhUIxlVvcPyrArnNMgvenxAUEdeceSfoeZbSmAbyPPwJUInmYIJCfcHkSkelFCKhMJUylZqBrjPjhWcJFptCNexBuKoWnBrSPKijXVQWIKLeEYyeobJFEdvAPmqsEiaSRHWNlDpUvhwYNIkYRMyeCXVsgDxAyMrYguENxNaxwimTuNKSCirADsXlItjKTJeSeNYtPdnumsKhfFKGtwXSnMsqqHiMsrWPRhYRxFojKTDKJxumBVFjJWjEFTpZqBRHTPFRSAiPQvhkUrAojrIwITZOJlrlPCstCCRnTtJHADyVTJGQpaOdhMgtmSpdsyvJdQdPyMVqNcDFyjgbxRgdQVjhRHgiLbVjQhJAKExRTVXcZduQSChHAQPUKZOAkRLHugyHxXRHCJwNXWeyLDXgaooLjvHPeQSPJjxMTLSWqoifYvCinbXMoqbjfA', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:34:49'),
(142, 'c457713b0ed71f2f122680d598e7e3c1', 'NULL', 'hEcRskZTMlSSnrjw', 'ZAP', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:35:10'),
(143, 'c66ce70ff6c0ca55b4e9c9bd81914517', 'NULL', 'hEcRskZTMlSSnrjw', 'ZAP%n%s%n%s%n%s%n%s%n%s%n%s%n%s%n%s%n%s%n%s%n%s%n%s%n%s%n%s%n%s%n%s%n%s%n%s%n%s%n%s\n', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:35:12'),
(144, '6ca48535795422033cb882506abb28da', 'NULL', 'hEcRskZTMlSSnrjw', 'ZAP %1!s%2!s%3!s%4!s%5!s%6!s%7!s%8!s%9!s%10!s%11!s%12!s%13!s%14!s%15!s%16!s%17!s%18!s%19!s%20!s%21!n%22!n%23!n%24!n%25!n%26!n%27!n%28!n%29!n%30!n%31!n%32!n%33!n%34!n%35!n%36!n%37!n%38!n%39!n%40!n\n', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:35:14'),
(145, 'f9a192ccc771fb65e499d4116957bf00', 'NULL', 'hEcRskZTMlSSnrjw', 'Set-cookie: Tamper=583294c3-e668-43b2-9f42-c023cedaabfe', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:35:48'),
(146, '1a0def46d3fa8349d39a51b9d1f62307', 'NULL', 'hEcRskZTMlSSnrjw', 'any\r\nSet-cookie: Tamper=583294c3-e668-43b2-9f42-c023cedaabfe', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:35:49'),
(147, 'f71896a3439f13644bee67e25d10c61f', 'NULL', 'hEcRskZTMlSSnrjw', 'any?\r\nSet-cookie: Tamper=583294c3-e668-43b2-9f42-c023cedaabfe', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:35:50'),
(148, '5f954edee7fdbdebde83dcb44d7793b3', 'NULL', 'hEcRskZTMlSSnrjw', 'any\nSet-cookie: Tamper=583294c3-e668-43b2-9f42-c023cedaabfe', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:35:50'),
(149, '4e7c71f350026094e2276f646adbbab3', 'NULL', 'hEcRskZTMlSSnrjw', 'any?\nSet-cookie: Tamper=583294c3-e668-43b2-9f42-c023cedaabfe', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:35:51'),
(150, '4dba7284857c8b3ec6655419cb55e52c', 'NULL', 'hEcRskZTMlSSnrjw', 'any\r\nSet-cookie: Tamper=583294c3-e668-43b2-9f42-c023cedaabfe\r\n', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:35:52'),
(151, '5977a297f67a476bac271bcc910b57cd', 'NULL', 'hEcRskZTMlSSnrjw', 'any?\r\nSet-cookie: Tamper=583294c3-e668-43b2-9f42-c023cedaabfe\r\n', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:35:53'),
(152, '59b5e069b735f28e93bc1a310232104d', 'NULL', 'hEcRskZTMlSSnrjw', '@', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:36:49'),
(153, '6b8d0a498f9ceb6ca6371b2a3903d7a2', 'NULL', 'hEcRskZTMlSSnrjw', '+', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:36:51'),
(154, 'e0bf2831d3ec048d345f16a1ce92b18d', 'NULL', 'hEcRskZTMlSSnrjw', '\0', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:36:53'),
(155, '983eb983cc32c6200b8df8a15637a20c', 'NULL', 'hEcRskZTMlSSnrjw', '|', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:36:55'),
(156, 'ebb8e15d888bca9b2d780834eecca1a8', 'NULL', 'hEcRskZTMlSSnrjw', '<', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:38:15'),
(157, 'ee2e28fbb4b84647ee8b2451b7ffe58b', 'NULL', 'hEcRskZTMlSSnrjw', '<xsl:value-of select=\"system-property(\'xsl:vendor\')\"/>', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:38:17'),
(158, 'a71c3ba93b322f47bd4bff3ec80e49b5', 'NULL', 'hEcRskZTMlSSnrjw', 'system-property(\'xsl:vendor\')/>', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:38:18'),
(159, 'dc54ddfeaf32c059641a018bb6259d0f', 'NULL', 'hEcRskZTMlSSnrjw', '\"/><xsl:value-of select=\"system-property(\'xsl:vendor\')\"/><!--', 'iBcgQrwDESflSCAE', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', '100', 'Pending', '2023-06-10', '2023-06-07 14:38:20');

-- --------------------------------------------------------

--
-- Table structure for table `pagelogs`
--

CREATE TABLE `pagelogs` (
  `id` int(11) NOT NULL,
  `userid` text NOT NULL,
  `log` text NOT NULL,
  `logid` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pagelogs`
--

INSERT INTO `pagelogs` (`id`, `userid`, `log`, `logid`) VALUES
(1, '5', 'dashboard', ''),
(2, '5', 'dashboard', ''),
(3, '5', 'dashboard', ''),
(4, '5', 'dashboard', ''),
(5, '5', 'keyjobareas', ''),
(6, '5', 'clients', ''),
(7, '5', 'clients', ''),
(8, '5', 'newclient', ''),
(9, '5', 'newclient', ''),
(10, '5', 'candidates', ''),
(11, '5', 'candidates', ''),
(12, '5', 'newcandidate', ''),
(13, '5', 'newcandidate', ''),
(14, '5', 'dashboard', ''),
(15, '5', 'interviews', ''),
(16, '5', 'newinterview', ''),
(17, '5', 'newinterview', ''),
(18, '5', 'dashboard', ''),
(19, '5', 'dashboard', ''),
(20, '5', 'dashboard', ''),
(21, '5', 'keyjobareas', ''),
(22, '5', 'dashboard', ''),
(23, '5', 'dashboard', ''),
(24, '5', 'keyjobareas', ''),
(25, '5', 'dashboard', ''),
(26, '5', 'dashboard', ''),
(27, '5', 'keyjobareas', ''),
(28, '5', 'keyjobareas', ''),
(29, '5', 'keyjobareas', ''),
(30, '5', 'dashboard', ''),
(31, '5', 'dashboard', ''),
(32, '5', 'keyperformanceindicator', ''),
(33, '5', 'keyperformanceindicator', ''),
(34, '5', 'newkpi/3504102775418', ''),
(35, '5', 'newkpi/3504102775418', ''),
(36, '5', 'calendar', ''),
(37, '5', 'calendar', ''),
(38, '5', 'newkpi/3979397535426', ''),
(39, '5', 'newkpi/3979397535426', ''),
(40, '5', 'keyperformanceindicator', ''),
(41, '5', 'keyperformanceindicator', ''),
(42, '5', 'dashboard', ''),
(43, '5', 'dashboard', ''),
(44, '5', 'newkeyjobarea', ''),
(45, '5', 'vacancy', ''),
(46, '5', 'vacancy', ''),
(47, '5', 'vacancy/id/1/activity', ''),
(48, '5', 'vacancy/id/1/shifts?from=2024-01-15&to=2024-01-21', ''),
(49, '5', 'dashboard', ''),
(50, '5', 'dashboard', ''),
(51, '5', 'keyjobareas', ''),
(52, '5', 'dashboard', ''),
(53, '5', 'dashboard', ''),
(54, '5', 'newkeyjobarea', ''),
(55, '5', 'keyjobareas', ''),
(56, '5', 'editkeyjobarea/9', ''),
(57, '5', 'keyjobareas', ''),
(58, '5', 'keyjobarea/9', ''),
(59, '5', 'keyjobareas', ''),
(60, '5', 'keyjobareas', ''),
(61, '5', 'clients', ''),
(62, '5', 'clients', ''),
(63, '5', 'client/8929', ''),
(64, '5', 'clients', ''),
(65, '5', 'client/5149', ''),
(66, '5', 'clients', ''),
(67, '5', 'clients', ''),
(68, '5', 'client/6241', ''),
(69, '5', 'clients', ''),
(70, '5', 'clients', ''),
(71, '5', 'client/8929', ''),
(72, '5', 'clients', ''),
(73, '5', 'clients', ''),
(74, '5', 'clients', ''),
(75, '5', 'dashboard', ''),
(76, '5', 'candidates', ''),
(77, '5', 'dashboard', ''),
(78, '5', 'candidates', ''),
(79, '5', 'candidate/64/home', ''),
(80, '5', 'dashboard', ''),
(81, '5', 'dashboard', ''),
(82, '5', 'dashboard', ''),
(83, '5', 'dashboard', ''),
(84, '5', 'keyjobareas', ''),
(85, '5', 'dashboard', ''),
(86, '5', 'dashboard', ''),
(87, '5', 'dashboard', ''),
(88, '5', 'dashboard', ''),
(89, '5', 'dashboard', ''),
(90, '5', 'dashboard', ''),
(91, '5', 'dashboard', ''),
(92, '5', 'dashboard', ''),
(93, '5', 'dashboard', ''),
(94, '5', 'dashboard', ''),
(95, '5', 'dashboard', ''),
(96, '5', 'dashboard', ''),
(97, '5', 'dashboard', ''),
(98, '5', 'dashboard', ''),
(99, '5', 'dashboard', ''),
(100, '5', 'dashboard', ''),
(101, '5', 'dashboard', ''),
(102, '5', 'dashboard', ''),
(103, '5', 'dashboard', ''),
(104, '5', 'dashboard', ''),
(105, '5', 'dashboard', ''),
(106, '5', 'dashboard', ''),
(107, '5', 'dashboard', ''),
(108, '5', 'dashboard', ''),
(109, '5', 'dashboard', ''),
(110, '5', 'dashboard', ''),
(111, '5', 'dashboard', ''),
(112, '5', 'dashboard', ''),
(113, '5', 'dashboard', ''),
(114, '5', 'dashboard', ''),
(115, '5', 'dashboard', ''),
(116, '5', 'dashboard', ''),
(117, '5', 'dashboard', ''),
(118, '5', 'dashboard', ''),
(119, '5', 'dashboard', ''),
(120, '5', 'dashboard', ''),
(121, '5', 'dashboard', ''),
(122, '5', 'dashboard', ''),
(123, '5', 'dashboard', ''),
(124, '5', 'dashboard', ''),
(125, '5', 'keyjobareas', ''),
(126, '5', 'dashboard', ''),
(127, '5', 'dashboard', ''),
(128, '5', 'dashboard', ''),
(129, '5', 'dashboard', ''),
(130, '5', 'dashboard', ''),
(131, '5', 'dashboard', ''),
(132, '5', 'dashboard', ''),
(133, '5', 'dashboard', ''),
(134, '5', 'dashboard', ''),
(135, '5', 'dashboard', ''),
(136, '5', 'dashboard', ''),
(137, '5', 'dashboard', ''),
(138, '5', 'dashboard', ''),
(139, '5', 'dashboard', ''),
(140, '5', 'dashboard', ''),
(141, '5', 'dashboard', ''),
(142, '5', 'dashboard', ''),
(143, '5', 'calendar', ''),
(144, '5', 'dashboard', ''),
(145, '5', 'dashboard', ''),
(146, '5', 'dashboard', ''),
(147, '5', 'dashboard', ''),
(148, '5', 'dashboard', ''),
(149, '5', 'dashboard', ''),
(150, '5', 'dashboard', ''),
(151, '5', 'dashboard', ''),
(152, '5', 'dashboard', ''),
(153, '5', 'dashboard', ''),
(154, '5', 'dashboard', ''),
(155, '5', 'dashboard', ''),
(156, '5', 'dashboard', ''),
(157, '5', 'dashboard', ''),
(158, '5', 'dashboard', ''),
(159, '5', 'dashboard', ''),
(160, '5', 'dashboard', ''),
(161, '5', 'dashboard', ''),
(162, '5', 'dashboard', ''),
(163, '5', 'dashboard', ''),
(164, '5', 'dashboard', ''),
(165, '5', 'dashboard', ''),
(166, '5', 'dashboard', ''),
(167, '5', 'dashboard', ''),
(168, '5', 'dashboard', ''),
(169, '5', 'dashboard', ''),
(170, '5', 'dashboard', ''),
(171, '5', 'dashboard', ''),
(172, '5', 'dashboard', ''),
(173, '5', 'dashboard', ''),
(174, '5', 'dashboard', ''),
(175, '5', 'dashboard', ''),
(176, '5', 'dashboard', ''),
(177, '5', 'dashboard', ''),
(178, '5', 'dashboard', ''),
(179, '5', 'dashboard', ''),
(180, '5', 'dashboard', ''),
(181, '5', 'dashboard', ''),
(182, '5', 'dashboard', ''),
(183, '5', 'dashboard', ''),
(184, '5', 'dashboard', ''),
(185, '5', 'dashboard', ''),
(186, '5', 'keyjobareas', ''),
(187, '5', 'dashboard', ''),
(188, '5', 'dashboard', ''),
(189, '5', 'dashboard', ''),
(190, '5', 'dashboard', ''),
(191, '5', 'dashboard', ''),
(192, '5', 'dashboard', ''),
(193, '5', 'dashboard', ''),
(194, '5', 'dashboard', ''),
(195, '5', 'dashboard', ''),
(196, '5', 'dashboard', ''),
(197, '5', 'dashboard', ''),
(198, '5', 'dashboard', ''),
(199, '5', 'dashboard', ''),
(200, '5', 'dashboard', ''),
(201, '5', 'dashboard', ''),
(202, '5', 'dashboard', ''),
(203, '5', 'dashboard', ''),
(204, '5', 'dashboard', ''),
(205, '5', 'dashboard', ''),
(206, '5', 'dashboard', ''),
(207, '5', 'dashboard', ''),
(208, '5', 'dashboard', ''),
(209, '5', 'dashboard', ''),
(210, '5', 'dashboard', ''),
(211, '5', 'dashboard', ''),
(212, '5', 'dashboard', ''),
(213, '5', 'dashboard', ''),
(214, '5', 'dashboard', ''),
(215, '5', 'dashboard', ''),
(216, '5', 'dashboard', ''),
(217, '5', 'dashboard', ''),
(218, '5', 'dashboard', ''),
(219, '5', 'dashboard', ''),
(220, '5', 'dashboard', ''),
(221, '5', 'dashboard', ''),
(222, '5', 'dashboard', ''),
(223, '5', 'dashboard', ''),
(224, '5', 'dashboard', ''),
(225, '5', 'dashboard', ''),
(226, '5', 'dashboard', ''),
(227, '5', 'dashboard', ''),
(228, '5', 'dashboard', ''),
(229, '5', 'dashboard', ''),
(230, '5', 'dashboard', ''),
(231, '5', 'dashboard', ''),
(232, '5', 'dashboard', ''),
(233, '5', 'dashboard', ''),
(234, '5', 'dashboard', ''),
(235, '5', 'dashboard', ''),
(236, '5', 'keyjobareas', ''),
(237, '5', 'newkeyjobarea', ''),
(238, '5', 'keyjobareas', ''),
(239, '5', 'keyjobareas', ''),
(240, '5', 'keyjobareas', ''),
(241, '5', 'keyperformanceindicator', ''),
(242, '5', 'newkpi/14013784233331', ''),
(243, '5', 'dashboard', ''),
(244, '5', 'dashboard', ''),
(245, '5', 'keyjobareas', ''),
(246, '5', 'newkeyjobarea', ''),
(247, '5', 'keyperformanceindicator', ''),
(248, '5', 'calendar', ''),
(249, '5', 'clients', ''),
(250, '5', 'candidates', ''),
(251, '1', 'dashboard', ''),
(252, '1', 'keyjobareas', ''),
(253, '1', 'newkeyjobarea', ''),
(254, '1', 'keyperformanceindicator', ''),
(255, '1', 'newkpi/16129791844740', ''),
(256, '1', 'calendar', ''),
(257, '1', 'dashboard', ''),
(258, '1', 'candidates', ''),
(259, '1', 'dashboard', ''),
(260, '1', 'clients', ''),
(261, '1', 'interviews', ''),
(262, '1', 'dashboard', ''),
(263, '5', 'dashboard', ''),
(264, '5', 'keyjobareas', ''),
(265, '5', 'candidates', ''),
(266, '5', 'candidates', ''),
(267, '5', 'newcandidate', ''),
(268, '5', 'dashboard', ''),
(269, '5', 'timesheets', ''),
(270, '5', 'dashboard', ''),
(271, '5', 'keyjobareas', ''),
(272, '5', 'dashboard', ''),
(273, '5', 'dashboard', ''),
(274, '5', 'keyjobareas', ''),
(275, '5', 'dashboard', ''),
(276, '5', 'candidates', ''),
(277, '5', 'dashboard', ''),
(278, '5', 'dashboard', ''),
(279, '5', 'keyjobareas', ''),
(280, '5', 'newkeyjobarea', ''),
(281, '5', 'newkeyjobarea', ''),
(282, '5', 'keyjobareas', ''),
(283, '5', 'dashboard', ''),
(284, '5', 'dashboard', ''),
(285, '5', 'shifts', ''),
(286, '5', 'dashboard', ''),
(287, '5', 'dashboard', ''),
(288, '5', 'dashboard', ''),
(289, '1', 'dashboard', ''),
(290, '1', 'keyjobareas', ''),
(291, '1', 'dashboard', ''),
(292, '1', 'dashboard', ''),
(293, '1', 'keyperformanceindicator', ''),
(294, '1', 'viewkpi/10909042654838', ''),
(295, '1', 'viewkpi/10909042654838/?from=2024-01-15&to=2024-01-21', ''),
(296, '1', 'viewkpi/10909042654838/?from=2024-01-22&to=2024-01-28', ''),
(297, '1', 'viewkpi/10909042654838/?from=2024-01-29&to=2024-02-04', ''),
(298, '1', 'viewkpi/10909042654838/?from=2024-01-22&to=2024-01-28', ''),
(299, '1', 'viewkpi/10909042654838/?from=2024-01-15&to=2024-01-21', ''),
(300, '1', 'viewkpi/10909042654838/?from=2024-01-08&to=2024-01-14', ''),
(301, '1', 'newkpi/20783368465444', ''),
(302, '1', 'newkpi/20783368465444', ''),
(303, '1', 'keyperformanceindicator', ''),
(304, '1', 'viewkpi/20783368465444', ''),
(305, '1', 'viewkpi/20783368465444/?from=2024-01-15&to=2024-01-21', ''),
(306, '1', 'viewkpi/20783368465444/?from=2024-01-08&to=2024-01-14', ''),
(307, '1', 'viewkpi/20783368465444/?from=2024-01-01&to=2024-01-07', ''),
(308, '1', 'calendar', ''),
(309, '1', 'calendar', ''),
(310, '1', 'clients', ''),
(311, '1', 'clients', ''),
(312, '1', 'client/8929', ''),
(313, '1', 'clients', ''),
(314, '1', 'clients', ''),
(315, '1', 'clients', ''),
(316, '1', 'clients', ''),
(317, '1', 'candidates', ''),
(318, '1', 'candidates', ''),
(319, '1', 'candidates', ''),
(320, '1', 'candidates', ''),
(321, '1', 'candidate/50/home', ''),
(322, '1', 'candidate/50/compliancechecklist', ''),
(323, '1', 'candidate/50/newdocument', ''),
(324, '1', 'candidate/50/vacancy', ''),
(325, '1', 'candidate/50/log', ''),
(326, '1', 'candidate/50/addlog', ''),
(327, '1', 'candidates', ''),
(328, '1', 'candidates', ''),
(329, '1', 'interviews', ''),
(330, '1', 'interviews', ''),
(331, '1', 'interviews', ''),
(332, '1', 'interviews', ''),
(333, '1', 'interviews', ''),
(334, '1', 'interviews', ''),
(335, '1', 'editinterview/3', ''),
(336, '1', 'interviews', ''),
(337, '1', 'editinterview/3', ''),
(338, '1', 'interviews', ''),
(339, '1', 'editinterview/3', ''),
(340, '1', 'interviews', ''),
(341, '1', 'editinterview/3', ''),
(342, '1', 'interviews', ''),
(343, '1', 'editinterview/3', ''),
(344, '1', 'interviews', ''),
(345, '1', 'vacancy', ''),
(346, '1', 'vacancy', ''),
(347, '1', 'vacancy/id/4/activity', ''),
(348, '1', 'vacancy/id/4/shifts?from=2024-01-22&to=2024-01-28', ''),
(349, '1', 'shifts', ''),
(350, '1', 'shifts', ''),
(351, '1', 'timesheets', ''),
(352, '1', 'timesheets', ''),
(353, '1', 'invoices', ''),
(354, '1', 'invoices', ''),
(355, '1', 'reports', ''),
(356, '1', 'reports', ''),
(357, '1', 'weeklyreport', ''),
(358, '1', 'weeklyreport', ''),
(359, '1', 'invoices', ''),
(360, '1', 'invoices', ''),
(361, '1', 'tool/users', ''),
(362, '1', 'tool/users', ''),
(363, '1', 'dashboard', ''),
(364, '1', 'dashboard', ''),
(365, '1', 'tool/emails', ''),
(366, '1', 'tool/bulkemail', ''),
(367, '1', 'tool/bulkemail', ''),
(368, '5', 'dashboard', ''),
(369, '5', 'keyjobareas', '5e377b40-8bd7-466c-a10e-ad052e049f4e'),
(370, '5', 'clients', '08943fd0-9fcc-4b60-b0ad-baf5bb689db9'),
(371, '5', 'clients/?searched=1&branch=haj&client=&type=&phonenumber=&email=&address=&city=&postcode=&status=', 'ca598206-9017-4728-aec8-2b479b711bea'),
(372, '5', 'clients', 'c71ac971-6acd-4324-9c21-622da82cf0e4'),
(373, '5', 'client/8929', 'ea664f35-83c1-40aa-aab5-a08712d85965'),
(374, '5', 'branch/?branch=12&page=profile', 'ce000088-2ff2-4547-bae2-c7583eeb0531'),
(375, '5', 'branch/?branch=12&page=timesheets', '8c8ff129-4872-42e3-adae-a7baf0e2eadb'),
(376, '1', 'dashboard', ''),
(377, '1', 'keyjobareas', '6dd72ad1-4e13-4f25-b71b-2997704d6ff4'),
(378, '1', 'keyjobarea/7', '3ff4f5e6-ece2-4cee-87b8-e942849f3a49'),
(379, '1', 'keyjobareas', 'af990404-9eba-4af6-b6d8-267a3edbaa1c'),
(380, '1', 'keyperformanceindicator', '8322e49a-e4db-49e9-a220-473400354a1d'),
(381, '1', 'viewkpi/20783368465444', '745cec67-5a6c-4b89-bcf2-5fd47269312b'),
(382, '1', 'viewkpi/20783368465444/?from=2024-01-29&to=2024-02-04', 'b7afe2c1-30bc-4bbe-8868-5bf22a55c43b'),
(383, '1', 'viewkpi/20783368465444/?from=2024-02-05&to=2024-02-11', '8dfe69df-2470-4573-890a-5cdd0a9a2f1d'),
(384, '1', 'viewkpi/20783368465444/?from=2024-02-12&to=2024-02-18', '6fd3fbf2-6d8e-42bd-9352-7801f94e9720'),
(385, '1', 'clients', '41fe2134-d51f-4f42-b965-59812a01d519'),
(386, '1', 'client/8929', 'c8396a50-a547-464d-bc91-42340818a04b'),
(387, '1', 'branch/?branch=12&page=profile', 'feb42c80-aa99-4f46-bffc-e9dea1d2f581'),
(388, '1', 'branch/?branch=12&page=log', '2e63b31c-4105-47ea-bfaa-51e07fa216b4'),
(389, '1', 'branch/?branch=12&page=interviews', 'f6e21229-485c-4e8d-8595-4c54e484e2a2'),
(390, '1', 'branch/?branch=12&page=vacancy', '454fb1a8-3942-4bbd-b111-fd9eaf6c897a'),
(391, '1', 'branch/?branch=12&page=shifts', '12f4add9-196c-4fcd-841a-46395f58397f'),
(392, '1', 'branch/?branch=12&page=timesheets', 'd5056e35-45d3-41cb-906f-e1252442d950'),
(393, '1', 'branch/?branch=12&page=shifts', 'f0081778-c1b4-4e4a-a342-c644675d946a'),
(394, '1', 'branch/?branch=12&page=invoices', '1b30b85d-ab64-41be-9f3a-b37c26331812'),
(395, '1', 'branch/?branch=12&page=interviews', '8a73ed7e-9e69-4910-b79a-172ae73be4c2'),
(396, '1', 'clients', '39048b17-f180-44ab-9124-338a5647b83d'),
(397, '1', 'client/6241', '66a2c8dd-afc8-4fa3-8cce-ca8d5cabe019'),
(398, '1', 'branch/?branch=8&page=profile', '76294a5a-554f-40a8-864c-ec592aac3953'),
(399, '1', 'branch/?branch=8&page=log', '7938fdb6-04fa-45fb-9a69-e30fb8510dab'),
(400, '1', 'branch/?branch=8&page=interviews', '286c56a3-3047-4047-b707-fbfbe7e036b9'),
(401, '1', 'branch/?branch=8&page=vacancy', 'bf032281-9fdd-4203-9198-ccde4a085ca3'),
(402, '1', 'branch/?branch=8&page=shifts', 'd21e2f8e-931a-4346-b78a-99195efa17ec'),
(403, '1', 'branch/?branch=8&page=timesheets', 'bb75d3ed-5af1-46bd-804f-e2925ecf75aa'),
(404, '1', 'clients', 'a9ffcee2-53d1-4db5-a819-fbef5eb07039'),
(405, '1', 'clients/?searched=1&branch=new%20opt&client=&type=&phonenumber=&email=&address=&city=&postcode=&status=', 'd42d3aae-be9c-4d1e-b6e4-66126c29cef8'),
(406, '1', 'clients/?searched=1&branch=new%20opt&client=&type=&phonenumber=&email=&address=&city=&postcode=&status=', '209ff7b3-f30e-4c86-827d-49567900250c'),
(407, '1', 'client/2204', 'e680155e-68de-42ca-96be-0f2962b12188'),
(408, '1', 'branch/?branch=15&page=profile', '0e70834c-8ed0-4b85-8773-fe8faf1d6d8f'),
(409, '1', 'client/2204', '1e12794e-9e3f-46fc-a379-a35067e7b54c'),
(410, '1', 'clients', 'b5b187a0-ae03-4fe3-a9b8-66fd16150c8f'),
(411, '1', 'client/2204', 'f002ad47-db53-44c8-965f-fdca07c8fefd'),
(412, '1', 'clients', 'e2c0aed9-561a-482c-beb8-297c3f167ac9'),
(413, '1', 'client/2204', '72cd11cb-36e6-43be-b194-6aad2c2ba51b'),
(414, '1', 'branch/?branch=15&page=profile', '0656d4b7-26f0-4303-ad9b-b4c04b22c605'),
(415, '1', 'clients', '771474ab-8b9f-4e3f-82e4-c444c2af5ca9'),
(416, '1', 'candidates', '84f64784-10ce-4a31-bc3a-803e86f10350'),
(417, '1', 'candidate/?id=64&page=home', 'e7432c3d-09c0-4095-b4d0-1fd92ec4f812'),
(418, '1', 'candidate/?id=64&page=log', '768067bd-4d77-40da-8ab1-885acf868c8e'),
(419, '1', 'clients', 'ef80c0bc-2dcd-42fd-aa51-bfd3632c8435'),
(420, '1', 'newinterview', 'ddb75188-bad1-4289-96a5-a29c07df3ff5'),
(421, '1', 'interviews', '9dd7fe89-3192-48b3-b57b-365e1bb02b6d'),
(422, '1', 'vacancy', '5208cf3a-1c57-45a4-bc7d-628b4edad9ed'),
(423, '1', 'viewvacancy/?id=4&page=profile', '85143185-73dd-4c10-910f-8204356bf6f3'),
(424, '1', 'timesheets', '89ffd8a7-c620-428a-9af7-ccad1e63c75b'),
(425, '1', 'invoices', '96387f7a-a142-49a1-aadf-a785d3070d4e'),
(426, '1', 'weeklyreport', '5eaa3149-b94a-4dc1-bc13-448085bc5061'),
(427, '1', 'tool/emails', '1c70ce9e-0dc6-4a0b-b119-d6af5aa58690'),
(428, '1', 'dashboard', ''),
(429, '1', 'interviews', '86369af5-a39c-495e-9680-f3239814e766'),
(430, '1', 'vacancy', 'b7a77004-0766-4198-9277-a3be975abb6d'),
(431, '1', 'viewvacancy/?id=1&page=profile', 'a7850b84-aea5-409c-b6b3-185dda91522b'),
(432, '1', 'vacancy', '6d8b4600-4ed9-458a-9605-38585c960e90'),
(433, '1', 'viewvacancy/?id=1&page=profile', 'cf88659a-530e-48f2-a897-94ee442c0352'),
(434, '1', 'shifts', 'f5f1355b-a6c5-4843-8055-b20f5a27d726'),
(435, '1', 'vacancy', '2f482e62-cd0a-4b87-b1fb-59a402b46587'),
(436, '1', 'viewvacancy/?id=4&page=profile', '9d9847e9-9ea3-4e65-bbcd-9204d6e5eb19'),
(437, '1', 'viewvacancy/?id=4&page=shifts', '38625fac-7aa3-43ad-b751-2195f8b97762'),
(438, '1', 'viewvacancy/?id=4&page=timesheets', '962d6465-fd61-4572-9ee6-32f433390965'),
(439, '1', 'viewvacancy/?id=4&page=profile', 'a491e9b8-53c4-4466-a912-3a8a40083bfb'),
(440, '1', 'viewvacancy/?id=4&page=shifts', '74ab1159-0ca8-4577-b014-90228f27f888'),
(441, '1', 'clients', '93c128b0-c229-4ac9-a095-040db481fb35'),
(442, '1', 'timesheets', '0e89bc28-c344-4cb3-822c-7baa0a38a455'),
(443, '1', 'timesheet/g/520805', '3d7130c1-37c1-4890-83f1-83140ff370f4'),
(444, '1', 'timesheets', 'cc033c59-b93a-44c8-8f6e-17da4201e4f9'),
(445, '1', 'timesheet/g/520805', '15d30c0e-3f7c-4fa6-985c-562bc2a00035'),
(446, '1', 'invoices', 'e60705ff-2d10-4397-84bd-cfd7b92f084a'),
(447, '1', 'invoices/i/520805', '43fca248-f961-428b-a49e-d415562b3c9b'),
(448, '1', 'timesheets', '5902941d-efa3-4bb6-bd7e-87d61bd409ba'),
(449, '1', 'invoices', '698365c3-fdf1-4ca8-a134-cfc7fa6955b1'),
(450, '1', 'weeklyreport', '534e1001-a5b9-49cc-9955-ca58c155ead7'),
(451, '1', 'tool/users', '31953771-75a6-41a0-bed0-2f0f7c358967'),
(452, '11', 'dashboard', ''),
(453, '11', 'keyjobareas', '028081f0-c27e-4470-a9b7-a6810f29b6e6'),
(454, '11', 'dashboard', 'ea7c15ba-f908-4947-874e-74865b1b657c'),
(455, '5', 'dashboard', ''),
(456, '5', 'calendar', 'ad81240b-4480-4337-91f3-0022edf4897a'),
(457, '5', 'vacancy', 'e6008f3f-d1ec-4fd6-9531-6f0cb9943dcd'),
(458, '5', 'viewvacancy/?id=1&page=profile', '7cc86acf-7a20-40cc-ac70-7d3870ff03ce'),
(459, '5', 'viewvacancy/?id=1&page=shifts', 'e444fbbe-7286-4604-af83-73d055c13758'),
(460, '5', 'viewvacancy/?id=1&page=profile', '0acc8a73-594c-4f0e-885b-561fbf615e6a'),
(461, '5', 'viewvacancy/?id=1&page=shifts', '38da1fa6-434c-4e98-84d6-1c9bd52936d7'),
(462, '5', 'viewvacancy/?id=1&page=shifts', '1470b5d7-5429-423c-a6ef-df4df9481e47'),
(463, '5', 'viewvacancy/?id=1&page=profile', '44f3e95f-e857-4108-bcaa-252aa4c4ac5d'),
(464, '5', 'viewvacancy/?id=1&page=shifts', '27a4cbe8-3b3d-48e0-aec2-7cfd636e2a51'),
(465, '5', 'tool/bulkemail', 'd904c9c1-53b4-4b27-9248-4c9190af456e'),
(466, '5', 'tool/bulkemail/compose/10/0', '3c21671c-2e4d-4ff3-af1a-e26380ead367'),
(467, '5', 'tool/bulkemail', 'deffe15c-45d0-4312-b220-32da57752900'),
(468, '5', 'tool/bulkemail/compose/10/0', '01002e80-a587-42e9-aa2e-fa3ce84f4b7d'),
(469, '5', 'timesheets', '17e8d431-0e9e-453a-8729-0bacd9df7cf3'),
(470, '5', 'timesheet/g/520805', '87619ed6-1f2f-4274-beb4-c1d57e15a053'),
(471, '5', 'invoices/i/520805', '639827c5-20d8-4226-9c4f-c12605ae18b8'),
(472, '11', 'dashboard', ''),
(473, '11', 'candidates', '7c6a5a91-990b-4711-9971-ed3d14160487'),
(474, '11', 'candidates', 'b54d33c2-421e-4f78-9549-c37c62a5158d'),
(475, '11', 'candidates', '1f95da27-c2ed-4a89-9c88-c7a0da909fce'),
(476, '11', 'newcandidate', '6d8eab31-5a8e-463d-8589-bce228c30d22'),
(477, '11', 'candidates', 'e2955e8c-29e3-4a59-bc49-1de3abd930c3'),
(478, '11', 'candidates/?searched=1&first_name=pam&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '6b3e9b42-da99-48ff-a1ad-b65764edcaf7'),
(479, '11', 'candidates/?searched=1&first_name=&last_name=musah&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '4f8942bb-3e41-4474-bc5d-9f95677e3f08'),
(480, '11', 'candidates/?searched=1&first_name=&last_name=suara&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'abcc272b-d847-4e6a-9d08-4cc4f74b0ba6'),
(481, '11', 'newcandidate', 'ad0ca72c-125e-426e-82d9-e8239e30f659'),
(482, '11', 'candidates', '7b9950ed-2b05-4af1-81f0-c1aa68b2ce72'),
(483, '11', 'candidates/?searched=1&first_name=bukola&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'ed84a5c2-0025-4cde-86cb-1cdd36f6eaf6'),
(484, '11', 'newcandidate', 'aee9606a-be93-4d13-a7e7-879aa362b67a'),
(485, '11', 'newcandidate', '8b3d13df-fe01-46b7-ba00-29fe75b45d10'),
(486, '11', 'newcandidate', '8c83092e-c3b1-43e0-a4c4-5570bfef42a8'),
(487, '11', 'candidates', '20996b25-5f1c-4186-a87e-548cbd57ca41'),
(488, '11', 'candidates/?searched=1&first_name=bukola&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '2285b427-1169-47fa-8942-41ec2be99edf'),
(489, '11', 'newcandidate', 'f48e26ff-e117-4f86-9392-d6f0b6a29685'),
(490, '11', 'newcandidate', '444d360c-0799-413a-bebc-2d619e05e4bc'),
(491, '11', 'candidates', '989e745b-bdfe-41c2-ad0d-c0c6cb071298'),
(492, '11', 'candidates', 'db81b7db-6e35-449d-ab3e-8b57b7bb78dc'),
(493, '11', 'candidates/?searched=1&first_name=george&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '6736840d-1f07-4b54-a34e-07e842b585c8'),
(494, '11', 'newcandidate', '0d1c7fc9-2a42-405c-9d48-ea050223cdf5'),
(495, '11', 'candidates', 'fab3fb02-e431-4d3c-bce3-600cfff7a317'),
(496, '11', 'candidates/?searched=1&first_name=olayinka&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '634ab506-5b98-43a3-b5d2-32819b78863a'),
(497, '11', 'candidates/?searched=1&first_name=&last_name=towolawi&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'f0ce8130-b915-4cfd-a349-78c355ca8f8f'),
(498, '11', 'candidates', '1c2ca711-fbe6-49de-98ca-6699f8c57646'),
(499, '5', 'dashboard', ''),
(500, '5', 'tool/users', '4461aab3-fc7d-4c9c-9eac-332cb7516373'),
(501, '5', 'candidates', '10c266b6-5eaa-4d3a-a91c-4499698c8d0b'),
(502, '5', 'candidates/?searched=1&first_name=towolawi&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '2a3233e0-579c-4f94-843c-967906d7ae8e'),
(503, '5', 'candidates/?searched=1&first_name=&last_name=towolawi&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '274e3661-9c94-4509-89b1-6acface62063'),
(504, '5', 'candidates', 'e84606c6-7449-4636-97eb-844a3189dc1c'),
(505, '5', 'candidates', 'bc66ed09-f32d-4951-a48c-fc94a21db04c'),
(506, '5', 'dashboard', ''),
(507, '5', 'candidates', '72db0c7a-d665-4c68-a94c-f545b98c3ed2'),
(508, '5', 'candidates', 'fead24b7-064f-4739-8dc0-ef6fd5d57ff1'),
(509, '5', 'dashboard', 'e685c9db-4462-4ac2-a5df-260e0a4019be'),
(510, '5', 'candidates', '5b6bb2c4-cf18-44f8-9c59-7a5406d79d60'),
(511, '5', 'tool/bulkemail', '95545995-e05f-4d71-bc6e-ca792dd95b0a'),
(512, '5', 'tool/emails', '67f6a80d-9f3d-4a61-8af6-97a3819d7d79'),
(513, '5', 'tool/emails/EwB4A8l6BAAUs5+HQn0N+h2FxWzLS31ZgQVuHsYAAcXMdLyPoWVejQZiEaNeREWKafp0QFxyEGDdfjLxqYx8KKKa95SmPHz57VGgmPQ2Q6dpiOoQ6rHF/e4KPQsduD9HzvTp9jw6dhe+IuCsL+2uafd/HicrZs61bjycAaVgcMBWBQFfk0wIG0AN2G+/NSK5DPvNlPx4S2+37iOa8l1Xl9w7GZsV5lw9F5c6zLYq7Qr/DiXuFNWmRXIE3t1rYeFyxjktxfftb37nvvW8IWeBcXPOh48+OCqdxh2iyrj9KV0n2YaJtOY75bL4o2HXDH2gyR5Ly2bp3yTe88LbFsdn89N0Hz3eKCxlLi+vxpI1s8bW1aU2SLo87Ct1boqpZNkDZgAACN/vmIA5Ez9YSAJYBX5Z7k0I8m/+bSZJo1RE/hRnQGUn3Hm0zCovHpUQvHDTz7I5IjIXWY7wrc7jUvCEegBk/WDmBaVV76QI+v7pJeL44l+rKe+jI0eL9Wpn/iELVbDMbA/2LxqQOk5KKS8ng4gI+OyIVt5WfD65lvNZH0rzviukW5HIT+86LzSt4ZqwKy1H617lYiQ0ktuOC5N+vLQbkqIfJu1YX09V+g5174vtZtR6AUugxBRDtbC8BmDosEt4gbs5F774tY+7q3U+cNUgKRUIzscMkiPG71MQuBSEK2pl4wzNuhmhBcSI6c0u13bqfQcrjg8wZ6we5rfrzKB2GDvKx7QSXDRz3JjTtDWD7mY7MC7Rs1g3Nd8dFycpvb88tDkUG10MPPdsGqduqqqehL1Oq7Jjqko8mGCkTMb2DAgCvzSjVGZkOiYSTFsLsjXxC9GkbUEDVRrUvzphuom2MH7N5BBrX1iXf4+HEwzTZFhyxGSQhOiDn1ea0EMITl/BTLtveN3EULfZL9w4Y4DiTGbPFfcM4ph4pTQt5MO6xPEpToM8hR5JySFtZogg3hUatjvZ7n/twtFtS2aJpCuI4hdMJ/1Lkmwtt7/QgXyQo+p6/BbAQH/ECcmUlalwQso/0UA3WCoAY1LbOLGB4cnk5/jASeVFjECA9yKugmkOdpUqCUvocvbUJ0aR9ckLly87UBYBOY+YryHdWkLxH9VL2aPpWw40zfE5qZHsm8Otr43ckA843ayZxBoYFVza+Hub94STl8lBEDdSaRrtIArj/RL4/o4C/allmails', 'd52f8321-9869-456d-8c4b-4b972ac769ed'),
(514, '11', 'dashboard', ''),
(515, '11', 'candidates', 'fbd8e94c-6e01-4f03-8112-fe173083fd9a'),
(516, '11', 'candidates/?searched=1&first_name=olayinka&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'a9297de4-5877-4461-afe6-c5124407da3e'),
(517, '11', 'candidates/?searched=1&first_name=oliver&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '00dcfa35-4611-4619-80e8-41b2d647ead1'),
(518, '11', 'candidates/?searched=1&first_name=olusola&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'ed8e1da3-6e18-4235-8b38-cfd4a0238e44'),
(519, '11', 'candidates/?searched=1&first_name=rinsola&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '36cadab1-0863-4a9d-8f53-c3a48c367ed6'),
(520, '11', 'candidates/?searched=1&first_name=rosaline&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '0447819e-582b-4bdb-88e3-4d0db3a93a62'),
(521, '11', 'candidates/?searched=1&first_name=sussana&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '35c811af-6620-4b33-bea0-100e74ff712f'),
(522, '11', 'candidates/?searched=1&first_name=timileyin&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'cd81d7e0-4211-419b-8093-78bba4435ef5'),
(523, '11', 'candidates/?searched=1&first_name=valentina&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '245e2ab3-c802-47f9-8022-d3ec66305324'),
(524, '11', 'candidates/?searched=1&first_name=winny&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '599f60ad-b0f5-45f1-8c77-f49dbeea323a'),
(525, '11', 'newcandidate', '2e6e43b7-6850-4058-bbef-aaaf8fd70a8e'),
(526, '11', 'newcandidate', '85bc6d7c-b20e-4aad-920e-f4cd963a320a'),
(527, '11', 'newcandidate', '6bb1967e-4f56-46c2-abcb-521ed2fd6c31'),
(528, '11', 'newcandidate', 'd1759387-fcc0-4c18-8bb4-9db61a7b38e1'),
(529, '5', 'dashboard', ''),
(530, '3', 'dashboard', ''),
(531, '3', 'candidates', 'cf292fcf-1e45-4a6e-9459-1bf2af62f5c6'),
(532, '3', 'dashboard', '5c145669-1f92-4b1d-b6e3-21d492e7d23e'),
(533, '3', 'dashboard', ''),
(534, '3', 'dashboard', 'b7b64bf5-fc45-4ab6-bad0-e6916fc45d44'),
(535, '3', 'candidates', 'de12b4d1-f574-4996-b8d1-30620d5783f2'),
(536, '3', 'candidate/?id=64&page=home', '6c84d3e6-25ac-4bac-b7be-66c90b72b9d5'),
(537, '3', 'candidate/?id=64&page=log', '21628b82-bd23-4173-a6fb-f38ba5fc2dc9'),
(538, '3', 'candidate/?id=64&page=checklist', '49a8479c-1b67-4c7c-85fa-0731b2d45ec3'),
(539, '3', 'candidate/?id=64&page=skills', '1c2adb25-2358-40ba-a255-2c7e7dd09432'),
(540, '3', 'candidate/?id=64&page=Emergency', '8e07332c-7348-49e0-bb98-8b1e3ecc7cd5'),
(541, '3', 'candidate/?id=64&page=Vacancy', '01cd1460-7f38-4cec-8320-756e403b5af2'),
(542, '3', 'candidate/?id=64&page=Shifts', '16d662f8-c481-4fca-a877-1713cc9e8c30'),
(543, '3', 'candidate/?id=64&page=Timesheets', 'ded6aa55-081b-4738-b7c1-321b2f12a042'),
(544, '3', 'candidate/?id=64&page=Interviews', '6a9b3e99-f18e-4448-9cc5-9027662b0857'),
(545, '3', 'dashboard', 'f8a2e375-af88-4e03-8936-1ac542b35139'),
(546, '3', 'candidates', 'e7eef5f3-4ded-4dd8-a6bd-7c45ca6fb96b'),
(547, '3', 'candidates/?searched=1&first_name=Georgina&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'acc2fa49-77cb-4fa3-98ac-1f13d68cd26a'),
(548, '3', 'candidate/?id=33&page=home', '28280394-1a63-4550-9591-3b380e857a7a'),
(549, '3', 'candidate/?id=33&page=checklist', '85cb2fda-a51e-4542-9211-037d9539cb28'),
(550, '3', 'candidate/?id=33&page=home', '4260b405-bf8b-48c8-ab9f-d286179d6f19'),
(551, '3', 'candidate/?id=33&page=home', '8f3c8370-1a46-44e3-8189-cf368d4933f6'),
(552, '3', 'candidate/?id=33&page=log', 'f59b442d-b0e6-464b-96af-2c5cb5e32231'),
(553, '3', 'candidate/?id=33&page=home', '87ac9f6b-453c-4b88-9b5c-aaf396ef7ec7'),
(554, '3', 'candidates', 'bbc63d83-f349-4d78-a5d6-a463f5e95467'),
(555, '3', 'candidate/?id=191&page=home', 'bfff284a-4af3-4391-9323-2e2261b20d62'),
(556, '3', 'candidate/?id=191&page=checklist', '0535bda8-1300-4e5d-809d-28873f44ec4a'),
(557, '3', 'reports', '1e5b56a0-3845-4b87-9a45-018e5fabc7db'),
(558, '3', 'tool/emails', '31ba9acd-8c4d-4ae7-ae26-3a758950b252'),
(559, '3', 'tool/bulkemail', '58b786f0-890b-4177-90e6-4926c5d6543a'),
(560, '3', 'dashboard', 'ff763c95-1350-4a57-837a-83e9fff0f7b3'),
(561, '3', 'newcandidate', '057ca527-38cc-4427-a44b-3a312199b9a2'),
(562, '3', 'candidates', 'f11431dd-977c-4c81-b589-b1bf10208380'),
(563, '3', 'candidate/?id=106&page=home', '72c6d689-b12f-4703-a3bd-df71438565ba'),
(564, '3', 'candidates', '56eb86c0-644e-40df-9a5b-35638e0c5a56'),
(565, '3', 'candidate/?id=141&page=home', '13174d2b-978f-4c01-9360-6c444d336cd0'),
(566, '3', 'candidates', 'bd93e872-1060-4592-9e4e-75d3aee5ae24'),
(567, '3', 'candidate/?id=2&page=home', 'c34bfe24-56c4-4592-94e5-928a6f84b35e'),
(568, '3', 'candidate/?id=2&page=log', 'af682278-0d1b-4525-a687-839a8036c300'),
(569, '3', 'candidate/?id=2&page=checklist', 'dd7be8e5-da3b-401e-abeb-34f79edb43b0'),
(570, '11', 'dashboard', ''),
(571, '11', 'clients', '0aa424e0-1f40-4748-b719-abe468252973'),
(572, '11', 'clients/?searched=1&branch=meadows&client=&type=&phonenumber=&email=&address=&city=&postcode=&status=', 'f59634b2-4e8f-4896-aaa8-f798730f2f9d'),
(573, '11', 'clients', '72e03bb5-c1dd-48fa-a51b-f95cdf97aa54'),
(574, '11', 'clients/?searched=1&branch=&client=meadows&type=&phonenumber=&email=&address=&city=&postcode=&status=', '64968153-fd80-400f-ae58-01f182de1789'),
(575, '11', 'clients', '3aa204b9-5427-428e-ae33-6d4c369b6988'),
(576, '11', 'clients/?searched=1&branch=leyton&client=&type=&phonenumber=&email=&address=&city=&postcode=&status=', '4536fe87-7f51-4415-bc36-8eb3f1c1e0a8'),
(577, '11', 'newclient', 'e0bdf6f3-9fe0-4cac-9c41-c70276ef323f'),
(578, '11', 'dashboard', ''),
(579, '11', 'newclient', '2db6f019-2939-49f2-b361-41f81c8af809'),
(580, '11', 'clients', '7d3fde36-d8fc-4b68-96a2-6e6588a70952'),
(581, '11', 'clients/?searched=1&branch=alliston&client=&type=&phonenumber=&email=&address=&city=&postcode=&status=', '6bf70f3b-3040-49cb-b770-12a7379331d4'),
(582, '11', 'newclient', '91891035-2e06-4366-a3e8-68101e4793de'),
(583, '5', 'dashboard', ''),
(584, '5', 'tool/bulkemail', 'bc9384fb-43ab-4968-96b2-f7b8fa78b14d'),
(585, '5', 'tool/bulkemail?emailid=&compose=1', '7b0c2955-bd0e-4f7c-ac56-e17558489fb3'),
(586, '5', 'tool/bulkemail?emailid=&clients=1', '8a8f48df-32f3-4090-9afb-4a9197580158'),
(587, '5', 'tool/bulkemail?emailid=&candidates=1', '2ca83780-0354-4ac4-9e61-f51cb5e9e0ec'),
(588, '5', 'tool/bulkemail?emailid=&consultants=1', 'eaf58571-3e4c-4be7-9d5c-786cd4a18ee9'),
(589, '5', 'tool/bulkemail?emailid=&candidates=1', '28aea044-3b89-46ce-9841-034ddc88813d'),
(590, '5', 'candidates', 'b54d7ee2-3830-4e39-9abc-cc5a6ffe06ea'),
(591, '5', 'tool/bulkemail', 'c6c0ee13-33df-4c69-bb94-5ee3b70ff341'),
(592, '5', 'tool/bulkemail?emailid=&clients=1', '9296c390-8422-45f1-90ee-1b43bd12127a'),
(593, '5', 'tool/bulkemail?emailid=&candidates=1', '2e3477aa-89f2-48b1-af0f-5f7d00fdb60b'),
(594, '5', 'tool/bulkemail?emailid=&clients=1', '6eca66be-f596-4e68-812f-03548fd03cc4'),
(595, '5', 'tool/bulkemail?emailid=&candidates=1', '18822ab5-388c-4ccf-b423-bff6ed5a293f'),
(596, '5', 'tool/bulkemail?emailid=&clients=1', '4761d8c1-46fc-4a2b-b2d3-8d2248de939a'),
(597, '5', 'tool/bulkemail?emailid=&compose=1', '6b359d12-9832-49d7-9539-8edf4da66ce8'),
(598, '5', 'tool/bulkemail?emailid=&clients=1', 'e57ea4d4-bbd6-4974-93c9-5c10b0b25862'),
(599, '5', 'tool/bulkemail?emailid=&candidates=1', 'ebf0000d-1c55-4de1-9b9b-8ace20d98d6b'),
(600, '5', 'candidates', 'fb691957-32d6-4ca8-aa7a-d0a2c25af248'),
(601, '5', 'tool/bulkemail?emailid=c5ca89c9-9f4c-4330-81e9-171341b49d57', 'c5ca89c9-9f4c-4330-81e9-171341b49d57'),
(602, '5', 'tool/bulkemail?emailid=c5ca89c9-9f4c-4330-81e9-171341b49d57&compose=1', '5ae73385-9878-4437-ab9a-e2cf00c74478'),
(603, '5', 'dashboard', '0d4db854-cbf5-48c2-9d9a-714cce162d5c'),
(604, '5', 'tool/bulkemail?emailid=7032d218-0328-421f-aa2d-8089edd0529d&compose=1', '7032d218-0328-421f-aa2d-8089edd0529d'),
(605, '5', 'tool/bulkemail?emailid=7032d218-0328-421f-aa2d-8089edd0529d&candidates=1', '5ef37bd4-4e53-4418-b696-397ad65f2356'),
(606, '5', 'tool/bulkemail?emailid=7032d218-0328-421f-aa2d-8089edd0529d&candidates=1', 'ded7fb3e-d6e7-4161-a4d8-898af1625263'),
(607, '5', 'tool/bulkemail?emailid=7032d218-0328-421f-aa2d-8089edd0529d&candidates=1', 'a749dad2-2fcf-477f-bf9f-263808ebc29c'),
(608, '5', 'tool/bulkemail?emailid=7032d218-0328-421f-aa2d-8089edd0529d&candidates=1', 'a3de0720-d136-4e60-88c1-264b13c1f701'),
(609, '5', 'tool/bulkemail?emailid=7032d218-0328-421f-aa2d-8089edd0529d&candidates=1', '0cb6ca7d-04d8-4a2b-8774-e4e8bdd95b87'),
(610, '5', 'tool/bulkemail?emailid=7032d218-0328-421f-aa2d-8089edd0529d&consultants=1', '78f80327-709d-4163-8678-d06ce946b67a'),
(611, '5', 'tool/bulkemail?emailid=7032d218-0328-421f-aa2d-8089edd0529d&candidates=1', '9dfad57e-755c-4609-b942-fe261ff472e6'),
(612, '5', 'tool/bulkemail?emailid=7032d218-0328-421f-aa2d-8089edd0529d&clients=1', '752c194b-5676-459b-b6c7-ad6fd0725205'),
(613, '5', 'tool/bulkemail?emailid=7032d218-0328-421f-aa2d-8089edd0529d&clients=1', '4eb4c40f-d64c-4354-9fca-901fad124378'),
(614, '5', 'tool/bulkemail?emailid=7032d218-0328-421f-aa2d-8089edd0529d&clients=1', '800ec871-9bcb-4fc8-b1aa-044f982fd96d'),
(615, '5', 'tool/bulkemail?emailid=7032d218-0328-421f-aa2d-8089edd0529d&clients=1', 'ea98a7a4-a9c4-4c8e-ab6d-b8c048e76805'),
(616, '5', 'tool/bulkemail?emailid=7032d218-0328-421f-aa2d-8089edd0529d&candidates=1', 'cb91d261-77ac-49da-8ee4-95ebc8e9a4a8'),
(617, '5', 'tool/bulkemail?emailid=7032d218-0328-421f-aa2d-8089edd0529d&compose=1', 'cf500eb9-e211-4faa-9cc7-bde3209624f5'),
(618, '11', 'dashboard', ''),
(619, '11', 'clients', '60a503c3-e52d-48d1-8300-220848ef3b8d'),
(620, '11', 'clients/?searched=1&branch=alliston&client=&type=&phonenumber=&email=&address=&city=&postcode=&status=', '87f33a21-7b93-47fc-ad04-58ab15ef4b28'),
(621, '11', 'newclient', '9a4494a0-1923-4110-b672-30e4a309c40d'),
(622, '11', 'dashboard', ''),
(623, '11', 'clients', 'a44227b5-36de-45ca-936f-449c3add1aca'),
(624, '11', 'clients/?searched=1&branch=alliston&client=&type=&phonenumber=&email=&address=&city=&postcode=&status=', 'f3e5e240-06b0-4b08-87c4-954625975343'),
(625, '11', 'editclient/3350', 'd623bed4-49c5-4c30-a38d-91a5705d6a46'),
(626, '11', 'clients', '56969dac-12c9-44cd-a4da-397da5537fc9'),
(627, '11', 'clients/?searched=1&branch=leyton&client=&type=&phonenumber=&email=&address=&city=&postcode=&status=', '54e6de8b-beb3-42a1-abfb-2460ec43e49f'),
(628, '11', 'editclient/6256', '3bfcb0aa-64c9-4074-82c3-6c87d4fa16a3'),
(629, '11', 'clients', '1fc63b82-3d91-48e9-af05-bcb80771ef94'),
(630, '11', 'clients/?searched=1&branch=foundry&client=&type=&phonenumber=&email=&address=&city=&postcode=&status=', 'e4440380-f4ca-4f39-8367-784efb2695c0'),
(631, '11', 'clients', '41e9f9f1-f3ae-49c7-9f08-94ce78c12356'),
(632, '11', 'newclient', '0bb48b2e-8bf0-4e05-8278-42b0720c6c04'),
(633, '1', 'dashboard', '97d2e356-097f-40ec-acf9-68831b86f043'),
(634, '1', 'dashboard', '39369046-b25c-4374-ae56-0f0759e01176'),
(635, '1', 'timesheets', 'e95410d3-156e-47e8-9937-0d279129dfa1'),
(636, '1', 'shifts', '945e2ce5-d620-4bae-9eac-87edcf686401'),
(637, '3', 'dashboard', ''),
(638, '3', 'dashboard', 'cd33341d-59dd-4776-957d-d8a5616c79ea'),
(639, '3', 'dashboard', 'a470dc9b-ffb4-4ff5-9631-7a1363e434bc'),
(640, '3', 'candidates', '3b0bd0e5-dcc0-4f58-9709-41bb4b3a49e5'),
(641, '3', 'tool/profile', 'd8045dea-e707-4497-8d0f-67b7fac75fc5'),
(642, '3', 'reports', '8914a53a-499a-4219-9401-8b3ce644ccfe'),
(643, '3', 'shifts', '7ff09687-4bc7-4d91-9875-49cb9ac94ea1'),
(644, '3', 'candidates', 'a8bbf042-8c86-4373-8e94-c5592121dc90'),
(645, '3', 'candidates/?searched=1&first_name=Olusola&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '97f71694-1413-4cad-8a0e-535333a34f4d'),
(646, '3', 'candidate/?id=39&page=home', '962b1d74-d91d-49ec-bb70-17e3b3a14306'),
(647, '3', 'candidate/?id=39&page=log', 'c6d2fe13-1796-4803-a902-a98ce6dc3da3'),
(648, '3', 'candidate/?id=39&page=checklist', 'f4c32535-0f77-48d2-8b54-21f8b2dd90da'),
(649, '3', 'tool/users', '0b5f573d-8a93-4698-a981-5735ba3befa4'),
(650, '3', 'dashboard', '9490b7b5-454d-4079-8cda-b5746133ce7e'),
(651, '3', 'dashboard', 'cea693db-add8-4414-8509-9f3f48a2bd93'),
(652, '3', 'calendar', 'fba427c7-0a25-4385-8b20-6f7bf476dbf8'),
(653, '10', 'dashboard', ''),
(654, '10', 'tool/users', 'b17c0380-40ec-4b33-93a2-fc92626484b7'),
(655, '10', 'tool/profile', '53a117e6-ead8-438b-a742-b124b8d1fafb'),
(656, '11', 'dashboard', ''),
(657, '11', 'clients', '9bfaca6a-86f2-4681-805f-d79c34ed08f2'),
(658, '10', 'vacancy', 'f525799e-0194-415f-842c-f6bf20b5ed15'),
(659, '10', 'viewvacancy/?id=1&page=profile', '8c4546d4-3700-4ad1-8941-9e64774e40d5'),
(660, '11', 'newclient', '61c58b31-2cf4-4889-b4b8-7e9ec8ceb12f'),
(661, '11', 'newclient', '9ae8183a-7a5f-4502-b80c-1291617002c8'),
(662, '11', 'newclient', '201454ab-fa76-405a-8924-335da9e61b74'),
(663, '11', 'clients', '3010c935-651f-4918-ab1b-7a8e1e26430b'),
(664, '11', 'client/3350', 'bebe6954-39b8-48d9-b3d4-f02e0609cddb'),
(665, '11', 'clients', '2a4e5811-f0fe-4100-b578-b4d2973e9855'),
(666, '10', 'keyperformanceindicator', '5bd7fc23-fa55-40e3-a31d-d2a8ab540c5b'),
(667, '10', 'editkpi/20783368465444', '74fefe61-69b6-4684-ad2d-7881d46a3642'),
(668, '10', 'keyperformanceindicator', 'cde2214d-15e8-4b16-ad4e-df11e362b12a'),
(669, '11', 'newclient', '39d91c57-1f4b-4b19-a24e-1de434b297ac'),
(670, '11', 'clients', '641b81ee-fdbd-46dc-85ee-915dae72efb0'),
(671, '10', 'viewkpi/10909042654838', '00bf3cef-07ab-44b1-9732-4e645c73b86c'),
(672, '11', 'client/5753', '2e009519-7165-4617-9841-b51d3f59ccd6'),
(673, '11', 'newbranch/5753', '58376a56-53ed-47ca-ab4e-03011ce8a34a'),
(674, '10', 'keyperformanceindicator', 'd6e62e49-bed3-490e-87dc-64096c5560f6'),
(675, '10', 'viewkpi/20783368465444', 'f1a44d5a-50e6-4746-ad37-2319af1a3d8c'),
(676, '11', 'clients', 'ac162dc5-65ad-448b-bf5d-23f02789c70b'),
(677, '10', 'dashboard', ''),
(678, '11', 'client/5753', '151c6f01-bfaa-48fa-bebc-251bcfa063bf'),
(679, '10', 'keyperformanceindicator', '054cfc87-c3e3-42ce-a665-e70abbbd949f'),
(680, '11', 'branch/?branch=81&page=profile', 'b7fdb754-f684-4cbd-9bad-f745cdf3f46e'),
(681, '10', 'editkpi/20783368465444', '771bf6b3-fdd8-4c28-a449-a524103d583e'),
(682, '11', 'clients', 'feecb5d9-5e58-4767-b124-51ea2f685132'),
(683, '11', 'client/5753', 'b679ecd4-5a74-4289-87e5-5dbaea1274f7'),
(684, '11', 'keyjobareas', '6c33508c-fefc-43a0-93f1-869b209e866d'),
(685, '11', 'clients', '79289c66-25b9-46e8-873b-ff95720ab94b'),
(686, '10', 'newkpi/11020407501042', 'd3709aa7-702c-4e9f-ae6f-9fedbd4ce9d2'),
(687, '10', 'keyperformanceindicator', '285835ab-e659-45cd-8002-4e6ce15d94b2'),
(688, '11', 'clients/?searched=1&branch=woodvale&client=&type=&phonenumber=&email=&address=&city=&postcode=&status=', 'a163b407-597a-412e-a2fa-bd1cf5746292'),
(689, '11', 'dashboard', ''),
(690, '11', 'newclient', '8cdc7f58-5b34-4bf5-9878-fab470ad1a99'),
(691, '11', 'clients', '20f12ef6-a165-4151-88d4-7866e077a612'),
(692, '11', 'clients/?searched=1&branch=&client=woodvale&type=&phonenumber=&email=&address=&city=&postcode=&status=', '7991c701-8397-4dd3-a2c1-7561ef38d6c9'),
(693, '11', 'clients', '61ca8d35-3a14-41d9-ab78-7b736722209a'),
(694, '11', 'newclient', 'f438b4fd-97ae-4b56-aed5-d316166aece6'),
(695, '11', 'clients', '725e3dc9-5174-4c93-a96d-4202abc526e4'),
(696, '11', 'newclient', 'be2af077-e305-4abf-9f5d-1c4443863cd1'),
(697, '11', 'clients', '4f002ee9-ee10-4054-864b-c0fc836afbcb'),
(698, '11', 'newclient', '68642eea-6f1c-4573-b348-a1544a82e6fc'),
(699, '11', 'clients', '351bba30-ea36-470c-98e7-e127b501af6c'),
(700, '10', 'viewkpi/20783368465444', 'd5b92728-633f-4463-93e6-2a9d0fb2b522'),
(701, '10', 'shifts', 'e12e1028-aead-4685-94f5-0196c01f3f1c'),
(702, '10', 'shifts?from=2024-02-19&to=2024-02-25', '99cbca91-03c6-406f-b69e-e9ae9974d935'),
(703, '10', 'shifts?from=2024-02-12&to=2024-02-18', 'bc336712-d49e-4a7f-b5f1-846dddaf97ea'),
(704, '10', 'vacancy', '59edd842-edd2-4ca4-92fe-8e4e99861b7c'),
(705, '10', 'viewvacancy/?id=1&page=profile', '10b47f07-b134-4562-a32d-df7bafeb98a4'),
(706, '10', 'viewvacancy/?id=1&page=shifts', '426c26d6-a055-4f0b-8a11-d4a5f01fae46'),
(707, '10', 'viewvacancy/?id=1&page=timesheets', 'd79c8a6e-1bee-4ed5-8a56-f730b8a14448'),
(708, '10', 'shifts', '71cf012b-75a6-424b-b562-805723769474'),
(709, '10', 'shifts?from=2024-02-12&to=2024-02-18', '097e2e21-6250-4c45-82b1-f35ba55d1af1'),
(710, '10', 'shifts', '69eba5a9-8111-49ef-b951-1348fd1a4b65'),
(711, '10', 'timesheets', '6d24acb2-48dc-4160-b907-074db4a100f0'),
(712, '10', 'clients', 'a214c9f7-cc96-4fd0-9f7f-cf23379f4b86'),
(713, '10', 'editclient/10047', '042c44b0-8b3d-4364-b6af-18a367f31b0d'),
(714, '10', 'vacancy', 'b557d059-424b-4f71-9196-30631993f213'),
(715, '10', 'viewvacancy/?id=1&page=profile', 'a6c6031b-317c-49dd-becf-d7ed5545114d'),
(716, '10', 'viewvacancy/?id=1&page=shifts', 'c3520817-1f46-4f0a-af2f-adce3925b317'),
(717, '10', 'viewvacancy/?id=1&page=timesheets', '79a6c73e-2f16-496f-814d-a74dd1528b73'),
(718, '10', 'viewvacancy/?id=1&page=shifts', 'e97f13ff-c7f2-4e2d-8fa1-35df22a61562'),
(719, '10', 'timesheets', 'e0f70e3f-6e4c-460c-b2d7-d86ef6af0fcd'),
(720, '10', 'shifts', 'eeabb2d0-ca9f-4bdf-86e2-e9814ae21191'),
(721, '10', 'timesheets', '6c1bad86-3ddb-4853-b8c7-614602327c96'),
(722, '10', 'timesheets?from=2024-02-26&to=2024-03-03', '9ec7408f-f96e-4ed8-ab8a-21e63e1cfc28'),
(723, '10', 'shifts', '1f7d22f9-693f-4abd-aafe-9b9e251693c2'),
(724, '10', 'timesheets', '65796949-68df-40a5-b466-69c4cd062f03'),
(725, '10', 'shifts', 'f53a6f44-c8d3-4fec-bd8d-09652b6d2882'),
(726, '10', 'timesheets', 'af41e7d7-647b-4df0-855f-1aa83052d5fe'),
(727, '10', 'shifts', '7b3ad010-a738-4b5b-b62c-3b63b43cba1d'),
(728, '10', 'timesheets', '02fe281f-87a4-4af7-b23b-a4dc56d082bc'),
(729, '10', 'shifts', 'd0e029ab-f1b5-4278-95c9-472ef9972f78'),
(730, '10', 'timesheets', '989de472-7a76-4de5-a6a4-7f2c0166ec15'),
(731, '10', 'timesheet/g/749036', 'a9defe7b-520d-4b8e-9412-8bd2b38b290a'),
(732, '10', 'timesheet/g/800308', 'ceedae20-5f43-4054-ac44-96be3fe043a7'),
(733, '10', 'shifts', '92dffcba-4c5c-4098-9dfb-90d9c37bd087'),
(734, '10', 'timesheets', 'd4e62530-b04f-4188-b965-ab22e17f7664'),
(735, '10', 'timesheet/g/160328', 'd1529409-f1be-4145-b1e3-deac1ee9fe8f'),
(736, '10', 'shifts', 'f6e57afd-a4b5-4f1a-a8d3-30c993c9c355'),
(737, '10', 'vacancy', '6cb4c517-529e-4380-a7be-b2bb02573ddf'),
(738, '10', 'viewvacancy/?id=1&page=profile', 'a7737cf3-013e-4a98-9116-d56b094d3f0e'),
(739, '10', 'viewvacancy/?id=1&page=shifts', '7baae83c-cfe9-40ef-aa91-26a6a00217ee'),
(740, '10', 'timesheets', '28a86112-54ff-4b88-9d7a-961eabfcd152'),
(741, '10', 'shifts', '39ac6857-61fa-4c55-9d18-586aa57be954'),
(742, '10', 'vacancy', '4715b3c4-3327-413f-a0be-2552b05ced0c'),
(743, '10', 'viewvacancy/?id=1&page=profile', '78b99657-e50b-495e-9a7b-13775a8670ac'),
(744, '10', 'viewvacancy/?id=1&page=shifts', '72a07beb-4565-4620-92a9-89b9ce9e31d4'),
(745, '10', 'viewvacancy/?id=1&page=timesheets', '6162abec-dce1-4c10-ad80-9f5019a271dc'),
(746, '10', 'viewvacancy/?id=1&page=shifts', '567642b0-3a3f-438a-b043-4460a0c33fd7'),
(747, '10', 'viewvacancy/?id=1&page=timesheets', '1571eccc-152a-4008-957f-e47165255de4'),
(748, '10', 'timesheets', 'aeacbaee-6aa4-4b7f-a193-bc9aa03b464c'),
(749, '10', 'timesheet/g/387893', '2d37ad21-a2e4-4033-833b-37fb98a5d4e8'),
(750, '10', 'shifts', 'e0977f5c-cf61-4e1e-9eba-2a46ddbfdb9c'),
(751, '10', 'vacancy', 'ffbaa324-bdaa-4d39-bcfc-85c5568d4cbc'),
(752, '10', 'viewvacancy/?id=3&page=profile', '862e06f9-87ae-412f-9b2e-756e0e1013fb'),
(753, '10', 'viewvacancy/?id=3&page=shifts', 'e06b44c2-ec59-493e-98eb-ff8b11c5fa78'),
(754, '10', 'viewvacancy/?id=3&page=timesheets', '9188b187-4469-44ac-8768-6c4abf8f80b4'),
(755, '10', 'shifts', 'b4380d79-56ea-4362-9659-605841b21bb4'),
(756, '10', 'timesheets', 'f411cfea-e19a-4618-b08f-e9a7b6b65ff9'),
(757, '10', 'timesheet/g/954690', 'fea84f24-1de8-4d9c-9e20-a01a07b05283'),
(758, '10', 'shifts', 'b8d328bb-bc3d-4e6e-9649-97f51e4e8443'),
(759, '10', 'shifts', '2d3b6541-bddc-4b44-a230-5241a5fe722c'),
(760, '10', 'shifts', '8ee683e7-8d2d-4312-9909-fb76f2be7f99'),
(761, '10', 'vacancy', 'ea892304-1cdb-49db-8988-fd43b23c2476'),
(762, '10', 'shifts', 'e3bc2d13-e747-4dc3-84e1-922abf54124d'),
(763, '10', 'vacancy', 'ab6576ba-eb1c-42a2-b9b0-268bd111f113'),
(764, '10', 'viewvacancy/?id=1&page=profile', 'deb38935-d1e3-4c6b-954c-0a30b9557863'),
(765, '10', 'viewvacancy/?id=1&page=shifts', 'be8fad20-96ff-4cdd-9ea0-7a07618bd6f8'),
(766, '10', 'viewvacancy/?id=1&page=timesheets', 'b5220ba4-bf2e-4d40-9f32-13b8d484da7b'),
(767, '10', 'viewvacancy/?id=1&page=shifts', 'fb044cfb-270a-49f5-8b3a-862ef4124ea2'),
(768, '10', 'timesheets', '2c9af72e-fbd1-4343-a909-fa7dc67bd2e0'),
(769, '10', 'vacancy', '03b823cf-f05c-4b2b-bac5-4d949abe3001'),
(770, '10', 'viewvacancy/?id=1&page=profile', '47e4fa61-5316-4258-918e-7867865e65d9'),
(771, '10', 'viewvacancy/?id=1&page=timesheets', 'c4a281b0-4ca4-4360-9474-a282799fa421'),
(772, '10', 'timesheets', '3c8c52a2-7130-4313-b69a-a21539aefa84'),
(773, '3', 'dashboard', ''),
(774, '3', 'dashboard', 'f8ad61b0-522c-48a1-afb6-ce3a2ded39a9'),
(775, '3', 'tool/bulkemail?emailid=d197319f-3d8b-45c2-ad3c-9efc02e3d40d&compose=1', 'd197319f-3d8b-45c2-ad3c-9efc02e3d40d'),
(776, '3', 'tool/bulkemail?emailid=d197319f-3d8b-45c2-ad3c-9efc02e3d40d&candidates=1', '06e7ead7-482a-49e6-a5c2-a0c3cae9e193'),
(777, '3', 'dashboard', '496f1c93-852a-43f2-a50d-04323db0db79'),
(778, '3', 'candidates', '20f62dd9-0860-40f1-ab87-605fb2420fbd'),
(779, '3', 'candidate/?id=64&page=home', '85360ad9-3389-4918-86ac-4e7b941a3d98'),
(780, '3', 'candidate/?id=64&page=home', '1b5d0d0e-a1b9-4db2-882d-60b3858e0ff4'),
(781, '3', 'candidates', '5e6e4f2c-89f4-45f5-868e-b3d2d5919d86'),
(782, '3', 'candidate/?id=33&page=home', '4b8a933f-115f-4a9a-a4f4-cdfe4491d881'),
(783, '3', 'candidate/?id=33&page=log', 'bfb9af5d-97b7-4a1f-b9d9-c3c4ae6c0dbd'),
(784, '3', 'candidate/?id=33&page=checklist', 'c10b8990-2f9c-4f2b-a653-6f26c7d5d413'),
(785, '3', 'candidate/?id=33&page=skills', '07ee0db5-9663-444b-8435-6cd9552e4bc9'),
(786, '3', 'candidate/?id=33&page=home', 'e2b66bc9-cdc4-41e4-8ea0-e7b4655a45be'),
(787, '3', 'candidate/?id=33&page=log', 'b53e31bb-914e-40dc-a8dc-959389b5cad9'),
(788, '3', 'candidate/?id=33&page=checklist', 'b8ec0d84-405f-4d11-900f-3d4904c23afb'),
(789, '3', 'candidate/?id=33&page=checklist', '46451791-88e1-4380-a191-edb534e4f270'),
(790, '3', 'candidate/?id=33&page=log', 'e147a270-f3ee-45da-80d5-cc472e29097a'),
(791, '3', 'candidate/?id=33&page=checklist', 'c1b47778-54a1-46d9-a132-8bff3898e1cb'),
(792, '3', 'tool/emails', 'ec924e32-c4d5-4db9-aa63-b17ce6a5031e'),
(793, '3', 'timesheets', '9e908240-903c-4534-b6b6-abaa300d0a6d'),
(794, '3', 'dashboard', '9f9f1868-b073-4952-bd38-37c6ab458175'),
(795, '3', 'calendar', '37a1baa5-f03e-4b27-a0d8-5871ccb1f271'),
(796, '3', 'allevents', '1c10eb2d-39a6-4b88-b7d2-ea59b5e8760a'),
(797, '3', 'candidates', '818eba1c-21b2-4ba2-bacf-a9ef4c541671'),
(798, '3', 'newcandidate', 'd7ee9ba3-c73e-4a68-8195-bbf2ef2626d8'),
(799, '3', 'dashboard', ''),
(800, '3', 'candidates', '832fec5c-2187-4f2d-b3b9-494bc0c3fa45'),
(801, '3', 'candidate/?id=64&page=home', 'be347903-784c-4cde-a3f0-3d3ad012ef6d'),
(802, '3', 'candidate/?id=64&page=log', '46537195-ced2-4cbc-9377-0df20eac9ea0'),
(803, '3', 'candidate/?id=64&page=skills', '3993d6ed-f3c7-43f6-a1d1-5121aa3d29c9'),
(804, '3', 'candidate/?id=64&page=home', '96d00447-3bef-4f6c-a8a1-c2cd77a62c19'),
(805, '3', 'dashboard', ''),
(806, '3', 'newcandidate', '7ad6f5de-b7c7-44a4-8d27-0de8c3f9dd3e'),
(807, '3', 'candidates', 'e913bb30-da7b-4d06-ad82-73380aa33c03'),
(808, '3', 'candidate/?id=50&page=home', '094caa8a-04a2-4984-8b71-98c87fcafdee'),
(809, '3', 'candidates', '6c74ce78-9786-4626-bf2e-a46c2704bc77'),
(810, '3', 'dashboard', '5ee68526-a66d-4867-8af5-10a3e88f14d6'),
(811, '3', 'candidates', '5a3b62f1-e8d8-43d0-8069-d1a314cc2195'),
(812, '3', 'candidate/?id=64&page=home', 'b5b5018d-3566-4990-9f35-728fa95158e0'),
(813, '3', 'candidate/?id=64&page=Emergency', '43e732fb-dacb-4251-8a8e-c1a86db503c6'),
(814, '3', 'candidate/?id=64&page=Vacancy', '787599c6-2672-49be-b232-196a7880abc5'),
(815, '3', 'candidate/?id=64&page=checklist', '50cdba48-03e1-4679-9e3e-a0aad96ff07e'),
(816, '3', 'candidate/?id=64&page=home', '3c751f67-4650-4455-afb3-9e6599741465'),
(817, '3', 'candidate/?id=64&page=home', '86f67a95-fb48-48c2-abbf-ad6575aa4637'),
(818, '3', 'dashboard', 'ff008703-262a-4c02-bd13-e4f8ab5b0a5d'),
(819, '3', 'candidates', '766473d6-a027-46df-b91c-208b56d35ec3'),
(820, '3', 'candidate/?id=190&page=home', '4040c4c5-cbba-4f7f-aef9-7eba94a0d7ef'),
(821, '3', 'candidates', '051666d8-377a-4a11-86d1-4f69fdad8cae'),
(822, '3', 'candidate/?id=89&page=home', 'bc47792a-60a5-4876-8f2a-e9099b22eed6'),
(823, '3', 'calendar', '3e48ebe3-2a7d-448d-b418-088b792cd26d'),
(824, '3', 'candidates', '369795b6-8810-41ba-88a6-aeecee2971dd'),
(825, '3', 'newcandidate', '166f8834-4c5e-4502-b089-5a91aaf81f51'),
(826, '3', 'newcandidate', 'a1c20616-34c0-4c86-8a6f-971ee45025c5'),
(827, '3', 'candidates', '432ed526-7807-46cb-a1a7-55a21db8ffc1'),
(828, '3', 'candidate/?id=16&page=home', 'cb93c815-fb5e-48f4-8392-da3e30cafe07'),
(829, '3', 'candidate/?id=16&page=checklist', 'a063f215-8a8b-4f6b-9384-6dfc1c30bdac'),
(830, '3', 'tool/profile', 'c8774677-52e3-4abd-ac9d-a0820c9cce36'),
(831, '3', 'tool/emails', 'b1536f66-7c78-4d78-9983-3c0d4dfa47e4'),
(832, '3', 'candidates', '09a48d0a-ac0a-4b75-a91e-566adb5dad69'),
(833, '3', 'candidate/?id=55&page=home', 'abc0ed75-ac89-47c7-a25c-b3e0b7d29d67'),
(834, '3', 'candidate/?id=55&page=checklist', 'ec43afaf-7f07-40ce-9f35-8007301206d9'),
(835, '3', 'candidates', 'f7249d1c-d0fd-4f53-85e7-ea5dbe28fc12'),
(836, '3', 'candidate/?id=64&page=home', '173f62d3-aaef-42d2-bc66-de18c97fc26c'),
(837, '3', 'newcandidate', 'fb873d38-ab58-4bd0-b7df-ae79819e62ae'),
(838, '3', 'candidates', 'd30c33fb-8d59-4a60-b26c-433b7f2788c3'),
(839, '3', 'candidates', '3398116a-77e5-4cdc-856c-f1a367e7c698'),
(840, '13', 'dashboard', ''),
(841, '13', 'candidates', 'c07426f7-1d0f-45bc-8390-e981c226c948'),
(842, '9', 'dashboard', ''),
(843, '13', 'dashboard', '5943aeb8-b659-4a20-819d-28e6ccaf438f'),
(844, '9', 'clients', '83361ff6-718c-4a7d-8952-7eba43e884b1'),
(845, '13', 'clients', '4f805cc1-0085-4417-b626-5caa869dba54'),
(846, '9', 'calendar', 'a092100c-0e57-41aa-8992-d8e62557f2f5'),
(847, '9', 'newclient', '72e69fd2-489b-4a7f-bf66-46aedf170e6c'),
(848, '9', 'clients', '61655ae1-6eab-4764-b7dc-0fe955c9f8ec'),
(849, '13', 'clients', 'bf4e3a20-ed3d-4229-a3ef-036fae283631'),
(850, '9', 'client/10047', '1877cd85-81a4-4e45-aec4-58029b725e75'),
(851, '9', 'dashboard', ''),
(852, '9', 'keyjobareas', 'd8ec1106-cae4-4b80-b5d3-76b32bb044da');
INSERT INTO `pagelogs` (`id`, `userid`, `log`, `logid`) VALUES
(853, '13', 'dashboard', 'fdee55d5-eff9-4b55-a703-be3b8f09eeb2'),
(854, '13', 'keyjobareas', '5eaee466-df8d-4e96-8e82-05eff75e4243'),
(855, '13', 'keyjobarea/7', '2c2bc1c7-06f3-4945-96b9-1d4aaebae19a'),
(856, '13', 'candidates', 'a2f31f48-176d-4190-b581-8b0579ff8b45'),
(857, '13', 'vacancy', 'c33b0f14-797a-4ad5-b463-4ed4fb20747a'),
(858, '13', 'shifts', '49bdc206-4793-42d3-9929-4d5ca25aebd2'),
(859, '13', 'interviews', '8e759431-3676-4c2e-b875-2335cec80153'),
(860, '5', 'dashboard', ''),
(861, '5', 'newclient', 'd69e0ff2-4f8a-45ba-9282-450a10261a98'),
(862, '1', 'vacancy', '70503fc4-f814-49c4-ac65-4f5077035f88'),
(863, '3', 'dashboard', ''),
(864, '3', 'candidates', '1b03fef4-91cb-4e15-8ef3-239e39dd7a90'),
(865, '3', 'candidate/?id=64&page=home', '42b99bb6-5554-462f-a314-3f94d8b10eb6'),
(866, '3', 'candidate/?id=64&page=checklist', 'c071e344-3b9a-45c1-b483-5895a5cfd1db'),
(867, '13', 'dashboard', ''),
(868, '13', 'dashboard', ''),
(869, '13', 'dashboard', ''),
(870, '9', 'dashboard', ''),
(871, '9', 'newcandidate', '1095688a-cb96-4b8b-b777-69a28c8e5881'),
(872, '9', 'candidates', '7f3454fb-c51d-4684-823b-7b37fa8994e3'),
(873, '9', 'newcandidate', 'eba4aa9d-8d7a-4a5f-8fac-0d481e9c326c'),
(874, '9', 'newcandidate', '779e13e4-6322-4e6b-9c78-660da38ce91b'),
(875, '9', 'newcandidate', '9bacaac0-0caf-4762-997b-52cb264f51b9'),
(876, '9', 'newcandidate', 'da64e969-c383-4aea-8725-a72d8f715fe5'),
(877, '9', 'newcandidate', '76f4d783-bf75-4baa-b95c-26293ce69ba7'),
(878, '9', 'candidates', 'cde4311c-46a4-4666-88f2-ce08ec97cb61'),
(879, '9', 'newcandidate', '519d6e79-8e6e-41c4-b942-9550265004ac'),
(880, '9', 'newcandidate', 'a21ecd74-6765-4d19-a2b4-d64ae7c317e6'),
(881, '9', 'newcandidate', '890a8139-0b02-4772-9cd2-1f6a436bf1d5'),
(882, '13', 'dashboard', ''),
(883, '13', 'candidates', 'a8ee4a7f-06a7-4d6b-9182-c18b1c8a7488'),
(884, '13', 'candidate/?id=90&page=home', 'ed7e43b7-298d-4577-afb5-81d0afeb1766'),
(885, '13', 'dashboard', ''),
(886, '13', 'newcandidate', 'c76b724d-bafb-44fd-a56d-a886fc33334a'),
(887, '13', 'candidates', '1ccccd32-c1a0-43db-a0ab-fbf2eb5957ce'),
(888, '13', 'candidate/?id=204&page=home', '3aa9c34f-af61-414a-beaf-4e5dc04b93ed'),
(889, '13', 'candidate/?id=204&page=log', '60f1ed36-bf21-4095-968a-33c2c1a35306'),
(890, '13', 'candidate/?id=204&page=home', 'baa0f2ad-b2ba-4656-bca9-08fbe0a19652'),
(891, '13', 'candidates', '9f283a7b-0bb8-4685-b9ca-0e3f7a1bdb69'),
(892, '13', 'candidate/?id=204&page=home', '408b840f-2ace-4272-980b-21c563aada3d'),
(893, '13', 'newcandidate', '03f61f57-9b98-4279-b442-17af9a3496e6'),
(894, '13', 'candidates', 'dee4623e-96e7-4210-91aa-1e46dced3ec2'),
(895, '13', 'candidates/?searched=1&first_name=mercy&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'db714a5c-0e2e-4790-b25d-98466cf3d0b1'),
(896, '13', 'candidates/?searched=1&first_name=nduaya&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '9f0d0337-bcf3-4013-8e62-84c47662b03a'),
(897, '13', 'candidates/?searched=1&first_name=Abosede&last_name=Ashimi&job_title=&gender=&email=abosedeajoke@hotmail.com&mobilenumber=07956367367&status=&postcode=N4%202XG&address=17%20Theobalds%20Court,%20Kings%20Crescent%20Estate&city=United%20Kingdom&createdBy=', '1b40415c-336b-4328-a519-1448f013f452'),
(898, '13', 'candidates/?searched=1&first_name=abosede&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '3a01b236-1432-419b-9876-6eceeb87bcf7'),
(899, '13', 'candidates/?searched=1&first_name=olawale&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '43df6fe9-fd61-426d-bb08-0493c713ad29'),
(900, '13', 'candidates/?searched=1&first_name=olayinka&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'e0c17025-b7a6-4271-a1c1-4aa9f5b7eccb'),
(901, '13', 'candidates/?searched=1&first_name=patricia&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'c675f8ac-3d52-4e9e-b6ea-a03d0273eb3a'),
(902, '13', 'candidates/?searched=1&first_name=temisan&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '1b8c295f-5164-461e-a0cb-ba82550e13ce'),
(903, '13', 'newcandidate', '34e02aca-1a08-4040-955d-aa4ac85df0aa'),
(904, '13', 'dashboard', 'a2a28c0c-c721-49d0-9de9-68bd5526de85'),
(905, '13', 'calendar', 'b868a535-09e8-4b7b-99e1-27d2bbbf37ed'),
(906, '13', 'candidates', '8644c052-b9c0-407f-bac3-da789e580392'),
(907, '13', 'dashboard', ''),
(908, '13', 'candidates', '98c870ac-c468-4972-a797-0dfbee2efe65'),
(909, '13', 'newcandidate', '91489cd7-f0a6-4774-a8fa-70047081bccc'),
(910, '13', 'dashboard', 'd012059e-4bc3-4a30-9ad8-6675779f16f6'),
(911, '13', 'candidates', '8d9205c0-9a30-4770-92f7-6c7f095de572'),
(912, '13', 'dashboard', ''),
(913, '13', 'newcandidate', 'e1515d19-c1c3-4665-a790-edeec4ebaebf'),
(914, '1', 'dashboard', ''),
(915, '14', 'dashboard', ''),
(916, '15', 'dashboard', 'cb6c9a4d-c5f3-4887-a0e4-4ba154b11da5'),
(917, '15', 'dashboard', '1881f074-8d18-4754-b411-1eff5bb65a87'),
(918, '14', 'tool/users', '4f4ac1b9-df0b-46c2-9168-805c9ae8c7a4'),
(919, '14', 'reports', '60e12f80-d282-45e8-9ef8-796bd3d8af90'),
(920, '14', 'weeklyreport', '6a1b8f92-50b5-4f2b-8f5f-a3c9f0f3847c'),
(921, '14', 'weeklyreport?from=2024-03-04&to=2024-03-10', '164aea91-e744-414a-94a7-af919ef0292f'),
(922, '14', 'weeklyreport?from=2024-02-26&to=2024-03-03', '8af46934-50da-4849-919c-e3fea405b195'),
(923, '14', 'weeklyreport?from=2024-03-04&to=2024-03-10', '1292684a-d901-4f57-af12-5fda9aef9533'),
(924, '14', 'weeklyreport?from=2024-03-11&to=2024-03-17', '844f47ed-1397-4671-8dbb-ed67a54379d0'),
(925, '14', 'weeklyreport?from=2024-03-18&to=2024-03-24', '46cf5ccd-449a-4c44-806f-9ec6b9560bca'),
(926, '14', 'weeklyreport?from=2024-03-25&to=2024-03-31', '4da27a6e-92d5-4f24-a86f-508bb0046e9e'),
(927, '14', 'weeklyreport?from=2024-03-18&to=2024-03-24', '37c6db63-cd4a-4120-a39b-2b5cb04acc69'),
(928, '14', 'newkpi/3181996793819', '0843c762-53bb-4ff3-942d-0f24727570c4'),
(929, '14', 'keyjobareas', '41c0e296-c550-4673-9285-c9aa7a0fefa8'),
(930, '14', 'timesheets', '3f888203-b2b2-4670-a540-eecc2a469a4e'),
(931, '14', 'invoices', 'e8364603-f10d-4589-b5e3-71d0ea0a9f0d'),
(932, '14', 'invoices/i/897513983', 'feb5063a-cb6f-49f8-a17c-89a45565af1b'),
(933, '14', 'calendar', 'e752a333-2b2a-4d74-95e5-961ee54a4cca'),
(934, '14', 'candidates', '23c22cf6-a68d-4de5-a408-5d34cdad0b73'),
(935, '14', 'weeklyreport', 'ead80f6e-f5d8-4548-aaa2-b8c7f41f031d'),
(936, '14', 'weeklyreport?from=2024-03-04&to=2024-03-10', '59783edf-3082-4ee4-9109-ceaa9d903ce3'),
(937, '14', 'weeklyreport?from=2024-03-11&to=2024-03-17', '0359ed54-360f-418a-a4b2-371cdb2f8d3a'),
(938, '14', 'tool/users', '1e61ecb2-32ff-48ed-a6d2-a5bc6b2a3548'),
(939, '14', 'clients', 'ff024b82-3a88-4667-bb06-880947d1b131'),
(940, '14', 'dashboard', '4f39f630-111d-4c58-960d-10fc7b3e609c'),
(941, '14', 'tool/users', '69b6afe2-d9f4-405b-aaa7-2c076664e278'),
(942, '14', 'timesheets', '8a1d4798-252a-45e6-bbaa-6fad1347d30b'),
(943, '14', 'tool/users', '7d5e58f1-ad74-4c8e-907c-20079bdc99b2'),
(944, '14', 'newkeyjobarea', 'a5a76bde-15b6-424a-b808-b68ac44b9700'),
(945, '14', 'tool/users', 'b898db72-627d-4824-b866-8db541809a57'),
(946, '14', 'newclient', 'f5c6c0bf-3329-42d9-a124-0a6d6be87f67'),
(947, '14', 'tool/users', '0c425977-b123-436b-a0e1-80ac6fd7ef30'),
(948, '14', 'dashboard', '9741c3f7-3f4f-43a7-a165-e8c2709fdd87'),
(949, '1', 'keyperformanceindicator', 'b4d16598-4a25-4fee-bc75-e4ed7f4bd879'),
(950, '5', 'tool/users', '674415d4-1eee-4e99-87a4-9b7483545c7e'),
(951, '14', 'tool/users', '52dfa3f2-86bf-42d3-beff-b3470794e1b7'),
(952, '3', 'dashboard', '528d6bbe-907f-4172-ac32-43316e579075'),
(953, '3', 'dashboard', '0fea86b4-c7c3-4cff-a65d-2be03ffa8484'),
(954, '3', 'newcandidate', '047c0041-ed56-4e04-b085-f4ac00dc76da'),
(955, '3', 'candidates', '537536ad-c1c0-4cc4-a1c1-a1f2a82be32c'),
(956, '3', 'candidate/?id=64&page=home', '34c9ddac-1348-457c-9f71-9ef469e05c35'),
(957, '3', 'candidates', '239e5e09-fc25-4d51-bb7b-20613fd5223d'),
(958, '3', 'candidate/?id=64&page=home', 'f21ad7d3-93dd-4153-81e6-a9f3a27af199'),
(959, '3', 'candidate/?id=64&page=checklist', 'f5ead2a2-4a6b-44d9-af4f-1970f35f641a'),
(960, '14', 'candidates', 'a2fbb746-8bb7-43a9-b81a-80885667f921'),
(961, '14', 'candidate/?id=64&page=home', '7bb3ef3e-4f24-4a9d-a053-52c95246744b'),
(962, '14', 'candidate/?id=64&page=checklist', '64095b6d-51f1-4ea3-b186-82a2574d13bf'),
(963, '14', 'dashboard', '790df460-3133-4458-9fc2-65707b93f4b2'),
(964, '1', 'dashboard', '617c1d9a-14b8-4288-a5f7-dd387e9df7ba'),
(965, '1', 'clients', '37cbf952-366c-4e89-8126-60f5092fbcd4'),
(966, '1', 'clients', '061b7f44-a41b-4fc5-84aa-34afcf4611e3'),
(967, '1', 'clients', '2c1afb03-330f-4b09-9b32-26aa1704dd3d'),
(968, '1', 'dashboard', '47dbb3a1-f19d-43c8-b83d-35e3e0eac388'),
(969, '1', 'keyjobareas', '8d6bdb98-e853-4c72-9c4d-4fe51cceeb1f'),
(970, '14', 'clients', '9d906bcc-7231-475b-af52-49b127186b4e'),
(971, '14', 'clients', '281afe47-c47a-4fec-ba8e-487251b1cfe9'),
(972, '14', 'client/10047', 'ca599f36-ac0e-4b61-88aa-8a01c212ced7'),
(973, '14', 'branch/?branch=46&page=profile', '3a60698a-ce10-4adb-9bbe-b87ddcd9b701'),
(974, '14', 'newclient', '87e55493-55ec-4151-bf21-acd64d3013e5'),
(975, '14', 'clients', 'faf70bb2-48d5-4c1c-b1e2-a40de40122ff'),
(976, '14', 'dashboard', '10a56ffc-e42c-4daf-acc3-d890eee390de'),
(977, '14', 'clients', '614323ff-6ec8-442b-b85c-1db8a02d2675'),
(978, '14', 'calendar', 'a3b7e4ae-cd5d-4e4c-b7d2-6da32544f5c3'),
(979, '14', 'keyjobareas', 'b8973f9b-35cb-4897-94dc-129c88ca9154'),
(980, '14', 'tool/users', '5a3f5097-a130-4e57-b497-9774b26da68c'),
(981, '14', 'dashboard', '92356212-f425-40fc-b29b-aff0e43904b6'),
(982, '14', 'interviews', 'f3485715-2bc8-4ef9-8d7f-ee3bca7ccab2'),
(983, '14', 'interviews', '9e7a22d2-7aa7-43e5-b192-a0141c16efce'),
(984, '14', 'timesheets', '51099c6c-49b0-4f6d-ac74-25629128ec31'),
(985, '14', 'shifts', '63b6dd69-19af-4c42-97bb-297a541f3f1e'),
(986, '14', 'timesheets', '348d6329-bf43-4045-bc6f-daa4e18a4024'),
(987, '14', 'timesheets', '4a699fd7-c574-45da-a8ab-713152538f2a'),
(988, '14', 'keyjobareas', 'dbf2d3a4-c878-46d5-a851-85e2ccdeeb10'),
(989, '14', 'timesheets', 'bfa52fad-d5b3-4f95-aa7d-3a01ecea6e6b'),
(990, '14', 'dashboard', 'd72e7015-384c-4098-a94e-0862f843e176'),
(991, '16', 'dashboard', 'd2f5d752-64ab-4b32-bd04-e5a161e1184f'),
(992, '14', 'dashboard', '3957de9d-bec1-4270-b1dc-6cdbab6873e9'),
(993, '13', 'keyjobareas', 'c7a90277-ffac-4570-a697-fa122c7f619c'),
(994, '9', 'keyjobareas', 'e358e084-f5f5-46d7-a972-b3d4147930d7'),
(995, '9', 'dashboard', 'fe7ff1f3-b86b-4a23-96f0-157221544e35'),
(996, '16', 'dashboard', 'b52720a9-06b2-40c0-8bac-c43b8c0939ee'),
(997, '16', 'keyperformanceindicator', 'a31a2f08-b448-4c25-8c5c-4d760b6acbc6'),
(998, '16', 'viewkpi/10909042654838', 'c735d885-4cdf-4d42-bef9-7a8b0ff785a5'),
(999, '16', 'keyperformanceindicator', '85528455-54e9-4f2c-9862-76b83c709b8b'),
(1000, '16', 'viewkpi/20783368465444', '33ae25b4-23b7-41a7-893e-c109a70efea2'),
(1001, '16', 'shifts', 'dd096fd4-5fd9-44d4-a656-e28102c024bf'),
(1002, '16', 'vacancy', 'ad2e19b7-5942-40e3-8126-839aeef55c66'),
(1003, '16', 'viewvacancy/?id=3&page=profile', '8c0c7976-b2b1-4257-8c32-da55357be1fd'),
(1004, '16', 'viewvacancy/?id=3&page=shifts', 'f24bbc42-18ce-434d-98a3-d298bcc49ab3'),
(1005, '16', 'shifts', 'a7b37cbf-71ab-4ce6-986a-9a1b36c5aa92'),
(1006, '16', 'timesheets', 'f66862e8-14fc-4c77-85c1-7676e5022673'),
(1007, '16', 'invoices', '0d2402c4-1a3a-4a80-aa78-9390cf396f39'),
(1008, '16', 'weeklyreport', '470c526a-0b9c-4b67-9406-5903d7ff54bf'),
(1009, '16', 'weeklyreport?from=2024-03-04&to=2024-03-10', 'fae3b0cb-2e5e-4881-8498-7f3e75661b18'),
(1010, '16', 'weeklyreport?from=2024-02-26&to=2024-03-03', 'b59ae53d-7740-4fc4-9695-07e5e7e8b445'),
(1011, '16', 'weeklyreport?from=2024-02-19&to=2024-02-25', '5eb3f667-1c82-4832-8f47-86ab1be874b3'),
(1012, '16', 'weeklyreport?from=2024-02-26&to=2024-03-03', '7df4f0df-262e-47d7-a400-43ac4c4456e3'),
(1013, '16', 'weeklyreport?from=2024-03-04&to=2024-03-10', 'b1755aca-c233-46d4-9260-d95374f04646'),
(1014, '16', 'reports', 'eca16477-b061-4c05-a87b-38eab92d6923'),
(1015, '16', 'calendar', '6eef8207-83bb-4950-a7b7-b154f972781b'),
(1016, '16', 'allevents', '8ba553ed-51bd-4217-bb46-219589c13052'),
(1017, '16', 'calendar', '3503c62b-bb27-49af-997e-89a8bb51354f'),
(1018, '16', 'clients', 'a0e9e020-eb84-41ce-b0d7-5e183005198c'),
(1019, '16', 'calendar', '9ff1dece-c9cb-4712-b654-e78630633212'),
(1020, '16', 'calendar', '548ac365-9b27-4e9f-8411-a34b5fed20b6'),
(1021, '16', 'clients', '72732973-686b-4e5b-896f-009678db9bc5'),
(1022, '16', 'candidates', '3e3e0d76-3dd9-4945-8fe5-5dba006eafa1'),
(1023, '16', 'candidate/?id=64&page=home', '88e5d5af-df49-41f6-8d4a-e02fe188dcaa'),
(1024, '16', 'candidate/?id=64&page=home', 'c0b78dcb-7089-435e-aafe-58060793542d'),
(1025, '16', 'candidates', '83f8442a-f338-4efb-b21b-038884d5ccf8'),
(1026, '16', 'candidate/?id=50&page=home', 'da88ceb9-f92d-4b9d-819d-e34cf12609b3'),
(1027, '16', 'candidates', '55ea9530-974b-493f-8b24-5ba0488c83c0'),
(1028, '16', 'candidate/?id=64&page=home', '07dc4a4f-006c-4ab4-aa20-6b1403240e3d'),
(1029, '16', 'candidate/?id=64&page=log', '1d6fe27f-0a3c-4cbd-bbaa-d81c2433ff6f'),
(1030, '16', 'candidate/?id=64&page=home', '58734c2b-f7b7-4f41-935d-7040959b03ef'),
(1031, '16', 'candidates', 'a0717714-cdb9-4cf6-b880-18bde2322364'),
(1032, '16', 'candidate/?id=64&page=home', '96ebb5c8-40d3-4129-b56d-d47a37738edc'),
(1033, '16', 'candidates', '40d5b848-840c-42b6-9f92-8ccd82f00efe'),
(1034, '16', 'candidate/?id=191&page=home', 'd7edfa06-c225-4702-ad10-ddab36e8c1d3'),
(1035, '16', 'candidates', '1ab830b7-d4c0-4d85-8333-692248428c3f'),
(1036, '16', 'candidate/?id=204&page=home', 'c2e143b1-d912-40e8-b066-0e75dd1c0b06'),
(1037, '16', 'candidate/?id=204&page=checklist', '1509f890-13ac-4619-9422-0da8754f94a8'),
(1038, '16', 'candidates', '6020401f-7482-43c5-b3bd-6d04b3513081'),
(1039, '16', 'candidate/?id=32&page=home', '0e76acf4-cf44-42ef-b46a-2446ba59ab34'),
(1040, '16', 'candidate/?id=32&page=checklist', 'da204ba1-df3e-4ddf-926a-6ea971f06b52'),
(1041, '16', 'candidate/?id=32&page=home', 'ba63d838-1387-42d6-aed7-0ee44bc130dc'),
(1042, '16', 'candidate/?id=32&page=log', 'd65aed02-17f5-40e8-83bc-cc53056d59ce'),
(1043, '1', 'candidate/?id=32&page=log', '5e9c1896-db23-4370-b094-befcb90fd892'),
(1044, '1', 'candidate/?id=32&page=checklist', 'f6c86156-de81-4db8-9c3a-b5bcb81110b5'),
(1045, '1', 'candidate/?id=32&page=skills', 'aa3eb694-df5a-4276-8378-8a0ea1a26cc3'),
(1046, '1', 'candidate/?id=32&page=Emergency', '3ba2613f-4bf4-4562-9651-8e6711a405d3'),
(1047, '1', 'candidate/?id=32&page=Vacancy', '0fd1a31c-939b-4fbe-a66a-3591d062d7fe'),
(1048, '1', 'interviews', 'f1cead99-deaa-4ca6-a069-f6a782208546'),
(1049, '1', 'newcandidate', 'fd81c31a-7233-440c-b749-fd493d82c8b2'),
(1050, '1', 'dashboard', '1b50cea2-176b-4dc0-b1e2-ed8293381ef6'),
(1051, '1', 'newcandidate', 'd59bc540-c179-474c-83ca-d4191fe9155f'),
(1052, '1', 'candidates', '73cdc3c8-c8f4-4ed8-997d-2c401ba2b832'),
(1053, '1', 'candidates/?searched=1&first_name=louise&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'd683d326-3810-4e79-8760-42b4b0b8baef'),
(1054, '1', 'newcandidate', '8ab78199-3c21-427c-a345-959beefc70de'),
(1055, '13', 'calendar', '9765b83b-0c4d-46fc-8140-2c2497593f6b'),
(1056, '13', 'calendar', '3003d483-bcca-48e6-bf7d-f2d58067d37e'),
(1057, '13', 'candidates', 'c8b3b503-581d-4b43-8170-1239edda6bcf'),
(1058, '13', 'candidates/?searched=1&first_name=louise&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'b18df737-d860-46ec-b072-4cf100d57a52'),
(1059, '13', 'candidates/?searched=1&first_name=louise&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'a2cff7a4-0708-4b9c-9162-ab6052b09a54'),
(1060, '1', 'dashboard', '2436e88a-7e5e-4354-aca1-8bad6e367962'),
(1061, '1', 'candidates', 'd884ed0f-cf6c-4808-81a1-0b0a225e88ac'),
(1062, '1', 'dashboard', 'c436f07b-e19a-4673-a2c0-36f14eba61e2'),
(1063, '1', 'newcandidate', '2a3c4eb2-c525-4c8f-8ad8-3a6217d82c11'),
(1064, '1', 'candidates', '44d16dad-733d-4203-8640-21ee4b9e7a49'),
(1065, '1', 'newcandidate', 'f5506a82-44b3-44b4-900a-8ada3c086af0'),
(1066, '1', 'newclient', '3cc21a46-7782-44e2-ab04-c91a0be0d339'),
(1067, '1', 'dashboard', '0f270180-0bc6-42a5-9967-febb4e00015e'),
(1068, '16', 'candidates', '0f879c99-7105-488e-99c3-a1a85f2fe0ab'),
(1069, '16', 'clients', '8c9ba9c6-5aaa-49b4-84a9-af2a6b24d3dc'),
(1070, '16', 'candidates', '79dee886-7f91-4e55-9777-0ea8c3de3512'),
(1071, '16', 'newcandidate', 'a6d18798-1ba1-4ba9-8319-145cbd0e189d'),
(1072, '16', 'candidates', 'b5b09778-f491-48cd-a56b-c3105134ef83'),
(1073, '16', 'candidates/?searched=1&first_name=louise&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'b2b35372-c586-4475-b77d-0977cdd06779'),
(1074, '16', 'clients', 'b39d02ff-f0ab-4926-806d-600cda624053'),
(1075, '16', 'candidates', 'd85841c1-8263-49af-ab47-80b855b6041c'),
(1076, '16', 'candidates/?searched=1&first_name=&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=14', 'af2bdbcd-fcad-49f9-8800-7c753a07a46a'),
(1077, '16', 'candidates/?searched=1&first_name=&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '5308f708-bef2-458e-8e8e-d5d54f740e64'),
(1078, '16', 'candidates/?searched=1&first_name=&last_name=&job_title=Support%20Worker&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '49417b58-5355-4e7f-9e89-98a74d785535'),
(1079, '16', 'candidate/?id=50&page=home', '5f39cdf1-77d3-488e-a949-294063d1a0df'),
(1080, '16', 'dashboard', '8204fb4c-9056-4ee5-a799-868452c2a902'),
(1081, '16', 'candidates', '01f19d16-4041-44d7-a790-1d9d60d32bd7'),
(1082, '16', 'candidates/?searched=1&first_name=&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=LU1%205BG&address=&city=&createdBy=', '58d3e034-f1c2-4f85-b3a7-31869581e376'),
(1083, '16', 'dashboard', 'f0aa14e8-6f31-4191-a9bf-37d0b0affd43'),
(1084, '16', 'candidates', '158f1fb9-1d69-4418-b209-43076a74e8a3'),
(1085, '16', 'candidates/?searched=1&first_name=&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=lu&address=&city=&createdBy=', 'bb3b8b9e-9a59-4ff9-b4d2-f557f4fd6ced'),
(1086, '16', 'candidates/?searched=1&first_name=&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=13', 'ee70e1bb-5eb3-43ce-9dc0-ab927b294aeb'),
(1087, '16', 'candidates/?searched=1&first_name=&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=9', 'd6feaaf2-9f56-49d6-ab4a-d498640c90e2'),
(1088, '16', 'candidates/?searched=1&first_name=&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=14', '29187555-9b22-4d62-bcf9-e7ac5d7a1948'),
(1089, '16', 'candidates/?searched=1&first_name=&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=10', '8afd742c-c29c-4c34-a924-17a933e419d6'),
(1090, '16', 'candidates/?searched=1&first_name=&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=11', '460c467c-58bc-405b-a65f-211b2aaa069b'),
(1091, '16', 'candidates/?searched=1&first_name=aanuoluwapo&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '72ee8619-8132-41bc-a35e-57baa050c594'),
(1092, '16', 'candidates/?searched=1&first_name=adebola&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '4b5a0c7a-5120-416b-a29e-73ac7e6c7dd2'),
(1093, '16', 'candidates/?searched=1&first_name=adedoyin&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'cd021066-2a40-4617-8818-311b665a05ae'),
(1094, '16', 'candidates/?searched=1&first_name=adekoyejo&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '7760755c-7f3d-4891-ab03-5383cd475e37'),
(1095, '16', 'candidates/?searched=1&first_name=adekunle&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '08f9e813-d405-4db6-b25b-bb75fcb7eb82'),
(1096, '16', 'candidates/?searched=1&first_name=aderonke&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'b6b13f64-248f-4519-a513-ae3de2aa7f3f'),
(1097, '16', 'candidates/?searched=1&first_name=aderonke&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '9dcd5f98-b334-487f-a5fc-df74a915ce70'),
(1098, '16', 'dashboard', 'c70e9b63-2ba8-45af-84cc-cffc88fa26d6'),
(1099, '16', 'newcandidate', '0b82f918-1226-4207-8941-6c2d51ff06b6'),
(1100, '16', 'candidates', '7168fdcd-204f-4a49-92b6-42e9c98f9358'),
(1101, '16', 'candidates/?searched=1&first_name=ADETOLA&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '96110fb4-c3a7-4366-8937-e840e1fe57cc'),
(1102, '16', 'candidates/?searched=1&first_name=adetutu&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'b1154e32-306d-4bea-8fc2-577afd2b34fd'),
(1103, '16', 'candidates/?searched=1&first_name=anita&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '3a510443-3f82-4376-a7d4-0b91d46e7afa'),
(1104, '16', 'newcandidate', 'a838ebc7-b452-4c43-9b5a-babc0f6032fe'),
(1105, '16', 'candidates', '6d215179-925d-4cde-a5f9-8770011163a8'),
(1106, '16', 'candidates/?searched=1&first_name=anita&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'f707ec6c-ff47-4f12-bf08-9d9c302232c1'),
(1107, '16', 'candidates/?searched=1&first_name=babatunde&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'd2a6312b-dc94-4771-ac2f-ea7d83033ed2'),
(1108, '16', 'newcandidate', '1629226c-ef77-4294-bd8c-683eb457c130'),
(1109, '16', 'candidates', 'edcd0515-d82b-4f5d-9f98-00aced4d70e4'),
(1110, '16', 'candidates/?searched=1&first_name=babatunde&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'e638f1f1-4c05-4b51-a717-95351ff71c35'),
(1111, '16', 'candidate/?id=222&page=home', 'c2029141-3480-4bb1-8d55-d4cf836bf630'),
(1112, '16', 'candidates', 'dbef3b67-cf61-464f-962e-b61c053ce0b6'),
(1113, '16', 'candidates/?searched=1&first_name=basirat&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '5ec0b0f3-7d9d-4e2c-9a1f-847c41fd20e9'),
(1114, '16', 'candidates/?searched=1&first_name=blessing&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '7d952b1d-c575-4c9b-b4a5-b933f58c5d1d'),
(1115, '16', 'candidates/?searched=1&first_name=celine&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '415f7c76-8272-462a-af58-a71c366d2abd'),
(1116, '16', 'newcandidate', 'de6f164a-a9cd-4ffa-aba2-318f68fbcbf9'),
(1117, '16', 'candidates', 'c110ac07-c8f3-4f31-9302-8e23b42d30b0'),
(1118, '16', 'candidates/?searched=1&first_name=celine&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '09942042-1313-4032-89cd-baabaa15cb07'),
(1119, '16', 'candidates/?searched=1&first_name=chibuzoh&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'e7a5c8eb-e689-4731-b14a-de020157bdf0'),
(1120, '16', 'candidates/?searched=1&first_name=edikan&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '68caa462-5f18-4fdc-bddc-118f55229a19'),
(1121, '16', 'candidates/?searched=1&first_name=edikan&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '36bbc119-3c8c-47f2-9f34-1afc96c438d0'),
(1122, '16', 'candidates/?searched=1&first_name=ediwan&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'c5f8a120-b9e2-46b0-8e72-9e319050f84b'),
(1123, '16', 'candidates/?searched=1&first_name=edwidge&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '9bb4b0a5-2868-4a8c-9eec-0356f6918580'),
(1124, '16', 'newcandidate', 'c328a2d2-91a0-471a-9e3f-51193f430815'),
(1125, '16', 'candidates', '3f4c961e-d082-47cb-8767-a8b5599512cf'),
(1126, '16', 'candidates/?searched=1&first_name=edwidge&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'a330246f-a000-484f-822e-a28c15539384'),
(1127, '16', 'candidates/?searched=1&first_name=edwidge&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'b5257c1c-61ba-43f6-a093-6dedba066736'),
(1128, '16', 'newcandidate', '0d7ab3aa-8abd-4126-955e-dd9cc69ea6e8'),
(1129, '16', 'candidates', '277a8ff4-aa37-4c8c-97d6-1cd6894d8063'),
(1130, '16', 'candidates/?searched=1&first_name=&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=birmingham&createdBy=', '79523208-653f-4b10-90db-2129f8b6d3b9'),
(1131, '16', 'candidate/?id=79&page=home', '49a1dc0d-72d8-4be9-bcff-aa892ea2bac7'),
(1132, '16', 'candidates', '4c143715-87dd-4738-8944-a281360879eb'),
(1133, '16', 'candidates/?searched=1&first_name=&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=birmingham&createdBy=', '69a54cfc-8c7a-43d5-b4ce-b9ae13a4960b'),
(1134, '16', 'candidate/?id=79&page=home', '1816c9fd-f2fd-476a-805e-f46267e70c90'),
(1135, '16', 'candidate/?id=79&page=log', 'f3966423-0a8c-46a3-83aa-33897bb69b59'),
(1136, '16', 'candidate/?id=79&page=home', 'b364cd01-0116-4cc5-855f-76ca485feb6a'),
(1137, '16', 'candidates', 'c5c8c1d7-1676-47da-90ce-d393a227b45e'),
(1138, '16', 'candidates/?searched=1&first_name=&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=birmingham&createdBy=', 'e1856dc9-4670-45d7-b2d7-4eda4afe5fc5'),
(1139, '16', 'candidate/?id=79&page=home', '2918b6f5-c3cf-4631-9a09-1a4ba16b714e'),
(1140, '16', 'candidates', '43e2559e-0393-496e-ab20-4c40c363510d'),
(1141, '16', 'newcandidate', '4cabe01c-9706-4bca-8163-384d1171096f'),
(1142, '16', 'candidates', 'e1cc67ec-2b34-420d-b8a1-c84a7f7db1b8'),
(1143, '16', 'candidates/?searched=1&first_name=&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=birmingham&createdBy=', '57e4e6eb-82a4-44f8-91c7-2c1ce2100454'),
(1144, '16', 'candidate/?id=79&page=home', '3b33509d-2936-4950-aa46-9b61cf5609c0'),
(1145, '14', 'candidates', '84d8694f-2493-4b62-b64f-4e91abe8c3b1'),
(1146, '16', 'candidates', '7309bab8-5c0f-464d-8f82-757806be5423'),
(1147, '16', 'candidates/?searched=1&first_name=EDWIGE&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '10fe0796-b18b-47a0-9181-a2bf76472850'),
(1148, '16', 'candidates/?searched=1&first_name=edwidge&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'c5400043-029b-4505-8e7b-9eecb1d5284c'),
(1149, '16', 'candidates/?searched=1&first_name=EDWIGE&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'af11caa4-2430-43ba-a910-afbc29ba829f'),
(1150, '14', 'candidates/?searched=1&first_name=&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=16', 'd03a4744-b0fa-49c1-8a39-2f812060b55e'),
(1151, '14', 'candidates/?searched=1&first_name=&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=14', '97796fda-f17d-4913-8513-cc8d003b90a6'),
(1152, '14', 'candidates', '245357e9-cd78-4768-9f0d-ecb6b275e6b6'),
(1153, '14', 'candidates/?searched=1&first_name=&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=16', '04446bd9-98a3-42a4-8d45-2fef50f83486'),
(1154, '16', 'candidates/?searched=1&first_name=elizabeth&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '9c8e751c-2c4a-4fed-a9e6-72114fe6d6b9'),
(1155, '16', 'candidates/?searched=1&first_name=evelyn&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '6a9eb86d-9a1a-4af8-b0a3-255b11055036'),
(1156, '16', 'newcandidate', 'c72f662a-001c-4fdb-bb1e-2ee28a632a6a'),
(1157, '14', 'candidates', '72d6cb1e-4e06-4c50-bf16-9df1c2d910fc'),
(1158, '14', 'candidates/?searched=1&first_name=&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=13', '0cd9753b-5204-4e85-8b75-6003e2a06063'),
(1159, '16', 'candidates', '35b1e572-0436-49b2-a70d-098eff5d95be'),
(1160, '16', 'candidates/?searched=1&first_name=&last_name=aganoke&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '81cc8dce-016b-4cdd-b603-4d52eec5c89e'),
(1161, '16', 'candidates/?searched=1&first_name=friday&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '27918b25-b062-4997-8db8-d23fdba8404b'),
(1162, '16', 'candidates/?searched=1&first_name=haja&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'cb1005b2-a577-41fd-8c84-b9f95d44ef0e'),
(1163, '16', 'candidates/?searched=1&first_name=janet&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '88d81d38-0387-4ce2-8b95-bea4fe9d1da4'),
(1164, '14', 'candidates/?searched=1&first_name=&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=9', '27508ad1-1830-4c44-87df-e61a7544a09a'),
(1165, '16', 'candidates/?searched=1&first_name=joy&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'b5a7f11f-aaff-4c77-9adc-55716281ff4a'),
(1166, '16', 'candidates/?searched=1&first_name=kafayat&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '97503148-ded2-4020-8b94-8c2b8560828c'),
(1167, '14', 'candidates/?searched=1&first_name=&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=11', '9271b0e1-d8d6-4ac4-aa0c-14939462bf48'),
(1168, '1', 'candidates', '97dacb13-0aa8-4398-95fe-8790f3e8e506'),
(1169, '16', 'candidates', '6e288226-0c69-4994-9e67-e390d8b6ae56'),
(1170, '16', 'candidates/?searched=1&first_name=kenneth&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '061f1a8b-f6c3-430d-9c51-87bb2573355c'),
(1171, '16', 'candidates/?searched=1&first_name=kenneth&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'd6fc418c-13fd-4022-8831-efe75dbb9400'),
(1172, '16', 'candidates/?searched=1&first_name=khadijah&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'd55f7984-d4c1-4ef7-a5a1-774c73e1485a'),
(1173, '16', 'candidates/?searched=1&first_name=lois&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '2a0fb436-485c-45ae-8298-1cd29a94706b'),
(1174, '16', 'newcandidate', 'a52eed07-1e9d-4c4d-a5a2-d12c6c2c8463'),
(1175, '16', 'candidates', '62f968f4-b387-486c-a39a-55e04f6d8bab'),
(1176, '16', 'candidates/?searched=1&first_name=&last_name=antwi&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'ad133994-f6a8-40f2-8348-ce3e7a5e8025'),
(1177, '16', 'candidates/?searched=1&first_name=Lois&last_name=Antwi&job_title=Support%20Worker&gender=Female&email=loska002@gmail.com&mobilenumber=07543530495&status=&postcode=NW5%202SX&address=3%20Tanhouse%20Field,%20Torriano%20Avenue&city=London&createdBy=16', '739c9769-a2b9-4440-984f-8f046efbe2ac'),
(1178, '16', 'candidates/?searched=1&first_name=lois&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '54744541-4971-405f-9767-96140cc3b91d'),
(1179, '16', 'newcandidate', '4d442932-2d53-437a-a558-e59e2fa05fae'),
(1180, '16', 'candidates', '9d951ed7-3b24-476a-8178-c927478d43c7'),
(1181, '16', 'candidates/?searched=1&first_name=lois&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'cf4ce294-2c54-4402-8684-9294e70c91c5'),
(1182, '16', 'candidates/?searched=1&first_name=Lois&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '9e31d388-4512-4d9b-bd0f-86f5027c0d24'),
(1183, '16', 'candidates/?searched=1&first_name=&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=16', '43f8fdcb-3074-41ec-8480-7ee79f51be78'),
(1184, '16', 'candidates/?searched=1&first_name=&last_name=Antwi&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '6f0049d9-d848-4948-b009-a90258020753'),
(1185, '16', 'candidates/?searched=1&first_name=martins&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '048485a7-93ce-460a-b6a6-5653d52eb7ba'),
(1186, '16', 'candidates', 'c9bca029-f119-4b82-ae5a-47c6f9e5b5eb'),
(1187, '16', 'candidates/?searched=1&first_name=martins&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '907516f2-cba6-46b5-885c-5603fba72df9'),
(1188, '16', 'candidates/?searched=1&first_name=lois&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '61c9a768-9728-4f8c-8217-6938f0d27927'),
(1189, '16', 'newcandidate', '31259924-fe3e-4ada-998b-dee650f625f3'),
(1190, '16', 'candidates', 'd80a791d-7938-4e3f-be56-1f60e5bc0dee'),
(1191, '16', 'candidates/?searched=1&first_name=lois&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '467570af-1b21-47d8-ad07-eaa8d673ef6b'),
(1192, '16', 'newcandidate', '06f1e7af-fc4a-49c9-a913-2344fa840436'),
(1193, '16', 'candidates', '0d4e2354-943c-4eaa-96cb-017f20fff556'),
(1194, '16', 'candidates/?searched=1&first_name=martins&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '79e5bf30-d274-47a9-85c0-54bb207f6172'),
(1195, '16', 'newcandidate', 'a89b6997-5138-4a0d-b936-e9cf5e8397b0'),
(1196, '16', 'candidates', '8fb0cf41-b75b-4770-87f4-023ce6064da3'),
(1197, '16', 'candidates/?searched=1&first_name=mildred&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '3dc293d2-a3c4-46b3-86d2-e4801be7bb6c'),
(1198, '16', 'candidates/?searched=1&first_name=musibau&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'cf97e043-a2ff-4c2d-b87e-46becb3833e2'),
(1199, '16', 'newcandidate', '67c498dd-6f69-47b0-842c-fc494f5af8f6'),
(1200, '16', 'candidates', '95e1a271-e6cc-4fa7-8e04-504a61a37d99'),
(1201, '16', 'candidates/?searched=1&first_name=musibau&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'd9a58be4-7dbe-4693-bec4-d2f0d3a82b94'),
(1202, '16', 'candidates/?searched=1&first_name=musibau&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '973a831b-188b-417e-bcc0-a51941202f36'),
(1203, '16', 'candidates/?searched=1&first_name=lois&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'a90b790a-415d-4342-a6fc-e5d874bcd572'),
(1204, '16', 'candidates/?searched=1&first_name=musibau&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '1aeb3e2a-20b2-49ec-ad0d-e41702b2fe2e'),
(1205, '16', 'newcandidate', '03a469b4-cf18-4a18-b5e7-8262e28ad3ee'),
(1206, '16', 'candidates', '744c2582-4640-4ca7-94fe-28e7941fac1b'),
(1207, '16', 'candidates/?searched=1&first_name=musibau&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '5138c4a2-efb8-40b3-9b4e-394621a6fb5e'),
(1208, '16', 'candidates', 'bf75249f-f17b-48b3-82ed-82ef1a356538'),
(1209, '16', 'candidates/?searched=1&first_name=musibau&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '97677881-6cb7-4c55-8ce9-8a3e859098f2'),
(1210, '16', 'newcandidate', '79508565-e2d4-4934-8e58-f6b7bbf66578'),
(1211, '16', 'candidates', '4c096c6e-005d-42c3-8bac-85051ea992a3'),
(1212, '16', 'candidates/?searched=1&first_name=musibau&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'ee36f612-2ee9-470c-a036-c69d6f175a1d'),
(1213, '16', 'candidates/?searched=1&first_name=&last_name=adesina&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'a9c08c1a-e271-4291-a6c9-f7e3f50558e6'),
(1214, '16', 'candidate/?id=95&page=home', 'e190cf27-99bd-441a-97e5-94ce69d7cb42'),
(1215, '16', 'candidates', '7e665287-7ddd-4290-ac0f-fe9183f48587'),
(1216, '16', 'candidates', 'ea015acf-82db-4193-86a5-e7a462e249ac'),
(1217, '16', 'candidates/?searched=1&first_name=&last_name=kasongo&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '97004d4a-ae0d-4172-9b12-6221d7645d0f'),
(1218, '16', 'newcandidate', '5291eb4c-1181-4ae8-bddf-f2565a72a1e9'),
(1219, '16', 'candidates', 'df20d4da-1102-46b0-9efe-f0c7633e352a'),
(1220, '16', 'candidates/?searched=1&first_name=mutombo&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'ea3f5f97-8d59-4ae3-85e3-57b5273eab32'),
(1221, '16', 'candidates', 'a6edbc97-9684-464a-a9b2-eec577410cc3'),
(1222, '16', 'candidates/?searched=1&first_name=nadene&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '4a40266e-8c23-473e-a56b-99e1e532905f'),
(1223, '16', 'candidates/?searched=1&first_name=olubunmi&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '4d337216-6385-4d12-8416-c099ac5aedec'),
(1224, '16', 'candidates/?searched=1&first_name=olugbemiga&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '2ae60203-a473-41ef-ba32-e767a810aba0'),
(1225, '16', 'candidates/?searched=1&first_name=onyedikachi&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '24c9d546-4fdc-4879-8024-65499e327824'),
(1226, '16', 'newcandidate', '1b323cb0-e25c-444f-8f06-37b69701274c'),
(1227, '16', 'candidates', '8eb18ee6-8569-4f77-8fdf-f103986b48be'),
(1228, '16', 'candidates/?searched=1&first_name=&last_name=agu&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '9394bc25-038c-4319-8ed9-1c1f346ad167'),
(1229, '16', 'newcandidate', '8057ae1c-1b47-4065-94cc-b8b80e0a6737'),
(1230, '16', 'candidates', '03a9e52d-6a2b-4a4b-88b6-ae9ac4f435a9'),
(1231, '16', 'candidates/?searched=1&first_name=philip&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '79c6b3d2-e969-4723-af28-ddc81a070a25'),
(1232, '16', 'newcandidate', '01982d1b-932d-4d16-b233-8c7cbfca8253'),
(1233, '16', 'candidates', 'b7b82856-ee34-406f-a86a-f542916ee6ce'),
(1234, '16', 'candidates', 'b89fc753-fb37-4a15-848a-a5e3316bdd69'),
(1235, '16', 'candidates/?searched=1&first_name=&last_name=agu&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'b275ca73-d15f-41d4-bef7-f00c7c3859ff'),
(1236, '16', 'candidates/?searched=1&first_name=rasheedat&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '98b3bb5d-7d4b-470e-9491-3a0dd6cd0f47'),
(1237, '16', 'candidates/?searched=1&first_name=roberta&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '36378d04-d182-41b6-9952-e5981eec090e'),
(1238, '16', 'candidates/?searched=1&first_name=thando&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'c6858bb1-2cd8-4b89-b8be-8dc2f1cb9911'),
(1239, '16', 'candidates/?searched=1&first_name=victoria&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '1fbb5086-ad6d-470c-8b6e-1d3a7be9e2eb'),
(1240, '16', 'candidates/?searched=1&first_name=&last_name=ablorh&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '4eec6074-2290-4ed5-bc49-5e4afadb4346'),
(1241, '16', 'newcandidate', '39708ece-b25c-4b55-becc-9dcc27ade577'),
(1242, '16', 'candidates', 'f179be7f-c7e3-4938-8e74-80a493165a0e'),
(1243, '16', 'candidates/?searched=1&first_name=VICTORIA&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'bc028777-8e7f-4553-aa31-11bccaf201a0'),
(1244, '16', 'candidates/?searched=1&first_name=taofeek&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '0a300263-0959-4bd4-97f7-840de17465ed'),
(1245, '16', 'candidates/?searched=1&first_name=marcos&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '38bb7d6e-199d-430d-ab5e-19302957f77f'),
(1246, '16', 'newcandidate', 'e2da837f-a92c-402d-beee-e543da2380a5'),
(1247, '16', 'candidates', '6d6fe435-4f82-465b-9483-a9eb5483e69b'),
(1248, '16', 'candidates/?searched=1&first_name=JOSEPH&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'b0762d00-b085-455f-8cce-fc0e6cf24b76'),
(1249, '16', 'newcandidate', 'c863d2ab-9a7f-4807-983b-f17dad9e6e2c'),
(1250, '16', 'candidates', 'e18d1301-197b-440a-bbd7-e6d5ea2053b6'),
(1251, '16', 'candidates/?searched=1&first_name=OLUWAKEMI&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'a0cea5d8-1285-4080-b138-077f4c7843b1'),
(1252, '16', 'newcandidate', '3d2357f8-35de-44c8-8194-c8fac508c8db'),
(1253, '16', 'candidates', '36f01177-d8c2-41da-80ac-cd1c5551c48d'),
(1254, '16', 'candidates/?searched=1&first_name=OLUWAKEMI&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'ed67a101-ed3e-40df-bdef-236b7d69e527'),
(1255, '16', 'candidates/?searched=1&first_name=VERA&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '92a4a7eb-5627-4deb-b906-78d46b08c71b'),
(1256, '16', 'newcandidate', '16ff83e0-4a77-4782-aa04-cd34bd125ee0'),
(1257, '16', 'candidates', 'ed41d9f9-5a81-4fbc-aa8e-93503a9fe01f'),
(1258, '16', 'candidates/?searched=1&first_name=vera&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'e8f6c63b-a847-43de-9acf-6f271ea4beab'),
(1259, '16', 'candidates/?searched=1&first_name=endurance&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'f9a06cab-8ce9-4dfa-beb0-7646dd0a2f9b'),
(1260, '16', 'newcandidate', '03b26cd1-669b-4834-94a6-2f08e48cae48'),
(1261, '16', 'candidates', 'a942abff-fc74-405d-a32d-641f94081c94'),
(1262, '1', 'dashboard', '7c73c1da-454e-4c54-9b80-ae44142a6de9'),
(1263, '16', 'candidates', '2996c186-d880-4478-9e0b-8a31ce5f750f'),
(1264, '16', 'candidates/?searched=1&first_name=&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=16', '818bbc58-d61e-4472-8d66-35f6d1108724'),
(1265, '16', 'candidates/?searched=1&first_name=&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=14', 'e11e64cc-0096-476f-892d-f1563ccc2aef'),
(1266, '16', 'candidates/?searched=1&first_name=&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '47602ce4-999f-4cb6-9e9f-7063f97f45c7'),
(1267, '1', 'candidates', '5dfc9859-8d6e-4c3d-94ea-c8f0a43eb11b'),
(1268, '1', 'candidates', '3acc8a3f-f880-4ed6-af58-42b342679f56'),
(1269, '1', 'tool/bulkemail?emailid=39b86dbb-b30c-4a75-b845-aaefe659662c&compose=1', '39b86dbb-b30c-4a75-b845-aaefe659662c'),
(1270, '1', 'candidates', '4ae290fa-fef6-4da1-b1a8-2b97c4c66e90'),
(1271, '1', 'candidates', '560809d1-2fed-45e0-aaad-9ca447c379b0'),
(1272, '16', 'candidates/?searched=1&first_name=&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=e&address=&city=&createdBy=', '436649d5-49eb-468c-802c-27710a25d3ec'),
(1273, '16', 'candidates/?searched=1&first_name=&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=E&address=&city=&createdBy=', 'bf61b405-12e1-48b4-8ba3-678831827d09'),
(1274, '16', 'candidate/?id=171&page=home', 'ca482eae-938b-4069-a868-1a3b4178dd1a'),
(1275, '16', 'candidates', 'e2732dc4-20a1-49b7-9c85-8a6f04ab14e0'),
(1276, '16', 'candidates/?searched=1&first_name=&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=E&address=&city=&createdBy=', '87e16683-05f1-4d1d-b887-9de48ac82576'),
(1277, '16', 'candidate/?id=147&page=home', '4721fa17-f6ee-441f-9a9d-5e92511f434d'),
(1278, '16', 'candidates', 'c3e078eb-ccb8-454c-beb5-cc6c6a9e23b3'),
(1279, '16', 'candidates/?searched=1&first_name=&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=E&address=&city=&createdBy=', '7092df51-1f6c-4abc-86c1-12aa34b0e4bd'),
(1280, '16', 'candidate/?id=148&page=home', 'a379345a-7a03-4328-acbb-de85cf1bba24'),
(1281, '16', 'candidates', 'd465144f-f358-406c-8935-a1c40929af84'),
(1282, '16', 'candidates/?searched=1&first_name=&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=E&address=&city=&createdBy=', 'a05314d6-d262-4b01-b2c0-94695a014c5d'),
(1283, '16', 'candidate/?id=128&page=home', 'c8b66e88-35cf-44b4-9f47-46bc9c9508cd'),
(1284, '16', 'candidates', '47be434c-88f5-4ab7-ae05-170231087bea'),
(1285, '16', 'candidates/?searched=1&first_name=&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=E1&address=&city=&createdBy=', 'fc2c693a-b988-4373-a483-9e02e58fc3f6'),
(1286, '16', 'candidate/?id=160&page=home', '4fd8e00e-a254-4ccd-9153-3c80629686bb'),
(1287, '16', 'candidate/?id=160&page=checklist', '433bf069-6d75-435e-9e03-876f56cc8305'),
(1288, '16', 'candidate/?id=160&page=skills', '4e0c5a29-9d76-4210-b6e7-06cd4746054a'),
(1289, '16', 'candidate/?id=160&page=Emergency', '0a524f0f-1e3f-4885-aee2-ee7ad5374476'),
(1290, '16', 'candidate/?id=160&page=Shifts', '8c294cb5-bbcc-486c-8bbb-ce32403194e1'),
(1291, '16', 'candidate/?id=160&page=home', 'c38f869a-505c-41c2-ad3c-28e4041b48cf'),
(1292, '16', 'candidates', 'c8ffd6fb-279c-4552-9954-cb37d206a15f'),
(1293, '16', 'candidates/?searched=1&first_name=&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=E1&address=&city=&createdBy=', '654366cd-1ca4-4f81-93c3-251d74093b79'),
(1294, '16', 'candidate/?id=51&page=home', '79b80fe4-c61c-4f0a-9758-542e5c3487e8'),
(1295, '16', 'candidates', '34314be3-2f51-4a98-b52f-820bf10c3b43'),
(1296, '16', 'candidates/?searched=1&first_name=&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=E1&address=&city=&createdBy=', 'f6c24a15-9df2-43b4-bf01-1cead58de3ce'),
(1297, '16', 'candidate/?id=49&page=home', '6acc5118-8c32-46b4-8367-dde8e3499a78'),
(1298, '16', 'candidates', '4556b425-ef27-4078-b3f3-07ce78d16bf9'),
(1299, '16', 'candidates/?searched=1&first_name=&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=E1&address=&city=&createdBy=', '23236914-5e7e-4bfd-9bd2-916fe56a1063'),
(1300, '16', 'candidate/?id=199&page=home', '8de4597e-a256-4148-b962-9d8740dc6f86'),
(1301, '16', 'candidates', '8338d66d-031f-41a8-890f-e5f9b73a3c09'),
(1302, '16', 'candidates/?searched=1&first_name=&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=E1&address=&city=&createdBy=', '3186ddc1-6bd0-4941-9c3d-b70a59941726'),
(1303, '16', 'candidate/?id=169&page=home', '0fc58b8c-8c5b-49cc-a0eb-066bdd9b23a8'),
(1304, '11', 'clients', '697fc5b5-2074-43e8-b4a1-a487916294b7'),
(1305, '11', 'newclient', 'd700bd05-b6fa-41e9-a9be-be3c20a7e2f5'),
(1306, '11', 'clients', 'b0ed2357-6da6-4eeb-9e60-0a40d0c5af8b'),
(1307, '11', 'editclient/10047', 'd470cf11-a7a8-4a36-bf58-e9f110d31653'),
(1308, '11', 'clients', '80f9f93d-7d73-4a69-af0e-bc46c08fdfdd'),
(1309, '11', 'client/9619', 'e3911d43-3a9f-4f97-8457-cc12f95944d8'),
(1310, '11', 'clients', 'f6230550-96b9-422a-9ddb-f3d6e2371c0c'),
(1311, '11', 'client/2023', '6bd86700-b6f2-4777-9a3a-2a97a163e367'),
(1312, '11', 'dashboard', '2f97b600-2ad3-4061-884d-5945335dbbf0'),
(1313, '11', 'clients', 'fb72d2a0-7fe1-4094-b4ec-f249a6776ffe'),
(1314, '11', 'client/6826', '0df21b66-9dc8-4a42-859a-e5c19f668c72'),
(1315, '1', 'dashboard', '5bd36b01-93ce-4eb4-a56d-a145ba53ba8c'),
(1316, '1', 'candidates', '7bb81e8f-37cf-4181-ae7e-6b535505aac5'),
(1317, '11', 'dashboard', '1c84e823-9b36-450e-bc96-f15bb858acbd'),
(1318, '11', 'clients', '293b4051-e5d9-4a9a-896f-6aaccc042fb4'),
(1319, '1', 'timesheets', 'd0cf898d-bc33-4282-825e-41dc81e4699d'),
(1320, '1', 'shifts', '52d9988b-54df-47e2-a6fe-c37fa57f04bd'),
(1321, '1', 'vacancy', 'd18b4636-9149-4570-bd3f-c3f2371cb3bd'),
(1322, '1', 'vacancy', '4aad28f8-56f5-42d0-924d-6fe8a069a454'),
(1323, '1', 'newvacancy/0', 'ee0bc314-4f13-43cd-b320-655aac3281ab'),
(1324, '1', 'newclient', '1dae2323-d088-49ab-8de9-b21d4d20c612'),
(1325, '1', 'clients', '7df9fdb0-4560-438f-b16a-8bdaafec548d'),
(1326, '1', 'newclient', 'b09e1b85-f9e4-46a7-9f32-25f90f228742'),
(1327, '1', 'clients', '5fda3f2a-0e9f-43e3-bd0b-abd49fb58d72'),
(1328, '1', 'clients', '43ec4289-be50-40e3-b29f-ede09be08fed'),
(1329, '1', 'newclient', '8865fbdd-3ef2-482a-8618-d8932548ed8f'),
(1330, '1', 'clients', '11d82633-64e8-4b8d-8ec5-87cb6b61eaf6'),
(1331, '1', 'clients/?searched=1&branch=&client=breakaway&type=&phonenumber=&email=&address=&city=&postcode=&status=', '1e2454aa-c47a-462d-8b8f-cd89a027c6b6'),
(1332, '1', 'newclient', '50165e00-a5c0-49c1-8a44-b1144748b2f7'),
(1333, '1', 'vacancy', '44625e2c-1c67-4a31-b2e7-80fc92a77453'),
(1334, '10', 'shifts', '2078363d-bb90-4d8e-9384-1a34c5ef5001'),
(1335, '10', 'newvacancy/0', '39d944fa-03a9-447a-8f7b-abf514fd680b'),
(1336, '10', 'clients', 'a4c9ac61-60d7-4352-8618-f6fc686e25cd'),
(1337, '10', 'newvacancy/0', 'c4a9b35c-ea36-4b56-aec1-e9d9f4d2bcc9'),
(1338, '10', 'timesheets', '7347ef4d-53f8-401b-a05d-b2e3c5436664'),
(1339, '10', 'shifts', '485f9a55-8648-4620-8b6f-ddae40776aed'),
(1340, '10', 'vacancy', 'b6fb2499-3a4e-4894-b0e0-975262d61811'),
(1341, '10', 'viewvacancy/?id=3&page=profile', 'f537e68b-72f5-4236-981b-ef75a410c3b9'),
(1342, '10', 'shifts', '8616fa17-6f0a-4cc8-b8e1-1a24f8611bd8'),
(1343, '10', 'shifts?from=2024-04-01&to=2024-04-07', '7493bd94-17d4-4cac-9bc2-93d1c83441a1'),
(1344, '10', 'shifts?from=2024-04-08&to=2024-04-14', '759fb429-8ec7-4b6a-8919-0d45f06466d7'),
(1345, '10', 'shifts?from=2024-04-08&to=2024-04-14', '794649a6-f1a2-49fc-b095-11b2c13c41f8');
INSERT INTO `pagelogs` (`id`, `userid`, `log`, `logid`) VALUES
(1346, '10', 'shifts?from=2024-04-01&to=2024-04-07', '433efed6-c19d-439c-904a-320f6a3567ba'),
(1347, '10', 'vacancy', '05b6dea9-c09a-4d1a-917b-e12aeb371628'),
(1348, '10', 'viewvacancy/?id=3&page=profile', '461f089b-48d5-461c-8b03-dda808623fbf'),
(1349, '10', 'viewvacancy/?id=3&page=shifts', '315c61f0-bbda-4f1d-b285-02ecbc5d3712'),
(1350, '10', 'viewvacancy/?id=3&page=profile', '829a185e-f3fb-4600-bccb-239c84af09c6'),
(1351, '10', 'viewvacancy/?id=3&page=shifts', '45ef27e5-6aaa-430f-a13e-4b0e10458f12'),
(1352, '10', 'viewvacancy/?id=3&page=profile', '0c5640d9-8635-47dc-8945-c31db91495a0'),
(1353, '10', 'viewvacancy/?id=3&page=shifts', '95373500-97f9-4ca8-adc3-f98f64ffcdf0'),
(1354, '10', 'viewvacancy/?id=3&page=profile', 'fcd9499f-9020-46f9-83a4-776717210e6b'),
(1355, '10', 'viewvacancy/?id=3&page=shifts', '998774c7-e3ee-4968-8208-2734302370b9'),
(1356, '10', 'viewvacancy/?id=3&page=shifts&from=2024-04-08&to=2024-04-14', '5a714271-30c1-4523-9eec-5e89e03baf6a'),
(1357, '10', 'viewvacancy/?id=3&page=shifts&from=2024-04-01&to=2024-04-07', '62b59b16-c9fd-4f7f-86aa-0cae4c05452d'),
(1358, '10', 'viewvacancy/?id=3&page=timesheets', '0eba527d-73fc-4813-9dc4-ba817d1f95b6'),
(1359, '10', 'viewvacancy/?id=3&page=profile', '131f1ac4-3ce6-4a94-aac8-f79164bb7356'),
(1360, '10', 'viewvacancy/?id=3&page=shifts', '3846b350-1feb-4f3f-8bbe-7097f3986d09'),
(1361, '10', 'viewvacancy/?id=3&page=shifts&from=2024-04-01&to=2024-04-07', '19e0b8cf-1d19-46ed-8831-92f45462245e'),
(1362, '10', 'viewvacancy/?id=3&page=timesheets', 'abf104f1-d943-472a-b951-51db6de4026e'),
(1363, '10', 'tool/users', 'dc844fd9-8583-4c48-8442-3fe9b27589a6'),
(1364, '10', 'tool/emails', '2695fd41-2a24-4c74-addc-50bf240ea329'),
(1365, '10', 'tool/profile', 'ca7ab73d-81ab-41b5-90e3-e6d93d7792a1'),
(1366, '10', 'tool/users', 'b40b0405-7378-4066-bf74-8ca3a7d5105e'),
(1367, '13', 'candidates', '46f112d3-0935-40af-9c31-22e45d09243f'),
(1368, '13', 'newcandidate', '079d3afa-7fdd-47df-b608-4bfeb9fcd186'),
(1369, '14', 'keyjobareas', 'e1c0389e-ea19-4b3e-8956-188818353e07'),
(1370, '1', 'dashboard', '1cd98729-9ae5-4671-b291-c7ca298212a5'),
(1371, '3', 'candidate/?id=64&page=log', '21f32f94-115c-43a3-a8d5-3abd816aaf4e'),
(1372, '3', 'candidate/?id=64&page=home', '00c15fc1-2629-4cbe-ab0f-d3c03b1671fc'),
(1373, '3', 'candidate/?id=64&page=log', '1038dff6-acec-4575-8a42-91118d0144b6'),
(1374, '3', 'candidate/?id=64&page=checklist', '2d544087-5a04-4ce1-86af-18f5ccda02e4'),
(1375, '3', 'candidate/?id=64&page=skills', '8eb048e3-7731-49fe-8818-302d785871ad'),
(1376, '3', 'dashboard', '65f4c41e-1f23-4a99-87ea-9ba15570fbce'),
(1377, '3', 'candidates', '5ea01dfb-467e-48af-80c3-28bdafcad0ce'),
(1378, '3', 'candidate/?id=64&page=home', '0223c104-d99c-481c-8c07-e246d8d4ebd7'),
(1379, '3', 'candidate/?id=64&page=checklist', '0487e4b0-a92e-4d62-896e-cf5105698d44'),
(1380, '3', 'dashboard', '2a1c7299-f74e-40e1-90f2-7967dead160e'),
(1381, '3', 'candidates', 'd0f0b657-8e54-4e0b-b56e-d8548e392141'),
(1382, '3', 'candidate/?id=64&page=home', '277998dd-b3b5-459d-98bf-cdcbd1d0d516'),
(1383, '3', 'candidate/?id=64&page=checklist', '4a166069-61a4-4034-b332-953f3e7e8fe3'),
(1384, '3', 'candidate/?id=64&page=skills', 'fb9ab58b-443e-4f5f-baf7-de643ed6110e'),
(1385, '3', 'candidate/?id=64&page=Emergency', '9a6482bc-f823-49f0-945a-ec1f59aa878e'),
(1386, '3', 'candidate/?id=64&page=Vacancy', '04e4d9e0-5eea-40d7-a8b8-9b6f3501c9e5'),
(1387, '3', 'dashboard', '2e33ee5a-c6b7-412e-b017-6bade859a455'),
(1388, '3', 'candidates', '485b53aa-1db9-4b76-8ce3-e9c3629ed1a9'),
(1389, '3', 'candidate/?id=50&page=home', 'f9867fb7-f848-44c9-aa69-c7f6036a7225'),
(1390, '3', 'candidate/?id=50&page=log', 'b93aa1a9-b947-495d-b001-21855508e422'),
(1391, '3', 'candidate/?id=50&page=checklist', 'dd589d6d-a4e8-4941-8f8d-ba2a373f0a67'),
(1392, '3', 'dashboard', '1433fc49-36c7-4282-9495-6e776c39e777'),
(1393, '3', 'shifts', '1b06e7bf-823b-4182-a96f-e791497e1dec'),
(1394, '3', 'candidates', 'd7ef09e6-4ca4-4926-a24b-dbaf48051cc6'),
(1395, '3', 'candidate/?id=64&page=home', 'a750bb8e-6034-4a6a-87ad-f1ba237fdf52'),
(1396, '3', 'newcandidate', '0bb4ab3d-1e9c-43d5-806d-6fbde175a0cf'),
(1397, '3', 'newcandidate', '8460c99c-fd12-4c84-ab39-a1f1c6a79eb2'),
(1398, '3', 'candidates', '3ac3046d-f1ab-4727-87db-cbeeb016f7b5'),
(1399, '3', 'candidate/?id=64&page=home', '60cfc17c-b05c-49c7-bae4-b1ae7ec936b5'),
(1400, '3', 'candidate/?id=64&page=log', 'b030c59b-db59-4b63-988d-fe25ed43be5a'),
(1401, '3', 'candidate/?id=64&page=checklist', '8e14ab0d-a1c2-4c41-9501-e1f9da6c3ad2'),
(1402, '3', 'candidate/?id=64&page=skills', 'b36131da-fb62-4876-8c78-78fb7362cbf2'),
(1403, '3', 'candidate/?id=64&page=home', '7a1de665-3c84-4762-87e5-0017ef548aea'),
(1404, '3', 'candidates', 'de7c8c71-a188-4b6b-a188-e9b842a26127'),
(1405, '3', 'candidate/?id=55&page=home', 'ab5444a3-6991-41b4-aff0-83a93d374caa'),
(1406, '3', 'candidate/?id=55&page=log', '932b0748-73ef-4b75-8565-e36b13b7b7d6'),
(1407, '3', 'candidate/?id=55&page=checklist', '02fadd47-f2b5-4462-b3aa-c40207ec8c25'),
(1408, '3', 'candidate/?id=55&page=skills', '68109934-098f-4bef-bf17-ada4092bca94'),
(1409, '3', 'candidate/?id=55&page=Emergency', '7e4903c8-484e-4865-876b-eb2310918e24'),
(1410, '3', 'candidate/?id=55&page=Vacancy', '2b3bb653-f8e3-4525-a61e-81a24bfec1b8'),
(1411, '3', 'dashboard', 'd5fa599a-2875-4c82-978d-cabc83d51461'),
(1412, '3', 'calendar', 'dd94f4bc-c84f-4979-9995-cbcdf1a6cb74'),
(1413, '10', 'timesheets', 'a8498b9f-e4f6-4e78-84dc-01fe3619b707'),
(1414, '10', 'dashboard', '0bd543af-db29-4cc2-b158-1e9dc69c505c'),
(1415, '10', 'timesheets', 'dc83404b-cd2f-4ca2-af51-557cf89dbb90'),
(1416, '10', 'newvacancy/0', '1207089a-019b-4976-90bc-2d4ac2e5f228'),
(1417, '10', 'shifts', '5d882c70-a9e2-4fd7-9783-464d9502aa3c'),
(1418, '10', 'vacancy', 'f06a5469-503b-445a-806d-bbfed79d3df2'),
(1419, '10', 'viewvacancy/?id=5&page=profile', 'b7a3504b-9748-486a-8abb-d0fb91cd9b80'),
(1420, '10', 'viewvacancy/?id=5&page=shifts', '173adf62-8bc2-4e66-8842-e26a8a4a1b89'),
(1421, '10', 'viewvacancy/?id=5&page=timesheets', '25828a9a-9310-4804-a1f4-753764f585ca'),
(1422, '10', 'viewvacancy/?id=5&page=shifts', 'a7f7038a-5fa3-48c8-b8b0-b220b354eecc'),
(1423, '10', 'viewvacancy/?id=5&page=timesheets', '706ba804-55f8-4218-8354-bd446979ca30'),
(1424, '10', 'viewvacancy/?id=5&page=shifts', '74660151-428e-48d9-b57f-d59158ef1cb7'),
(1425, '10', 'viewvacancy/?id=5&page=timesheets', 'f94e0c6f-2dc8-4dc8-95c7-78bdcfc05498'),
(1426, '10', 'viewvacancy/?id=5&page=shifts', 'e3923e52-46b2-43f2-bc45-45007b59a8ae'),
(1427, '10', 'viewvacancy/?id=5&page=timesheets', 'ebbab679-04cb-479f-bd9f-f02412458e23'),
(1428, '10', 'timesheets', '3a0706d1-edbd-42c3-99b1-a32d80a6bfde'),
(1429, '10', 'timesheets?from=2024-04-29&to=2024-05-05', '39a51f92-081a-4bab-bb6b-9a55676d3854'),
(1430, '10', 'newvacancy/0', '5fefcd1c-e72f-4cf2-ae0d-5633b658c353'),
(1431, '10', 'vacancy', '91827ebc-fc65-45d7-b893-104395ade7f2'),
(1432, '10', 'viewvacancy/?id=1&page=profile', '91285612-11e5-4ed7-aae7-afa908e3fe2f'),
(1433, '10', 'viewvacancy/?id=1&page=shifts', 'afe8e545-6263-401f-bb62-f17de1c1ad4a'),
(1434, '10', 'viewvacancy/?id=1&page=timesheets', 'f6054c64-ec0e-4c1d-b7db-fd191550ca26'),
(1435, '10', 'viewvacancy/?id=1&page=profile', 'fc6e2127-8ecd-496a-bf1c-c59fe16252c2'),
(1436, '10', 'vacancy', 'a75de3a0-5be4-4ad0-8ae9-6390887c4c0e'),
(1437, '10', 'viewvacancy/?id=3&page=profile', 'a778e9cd-70af-4a97-ad94-af53da446434'),
(1438, '10', 'viewvacancy/?id=3&page=shifts', 'ff309f45-bd07-4990-9a21-aae4b8cdbe63'),
(1439, '10', 'viewvacancy/?id=3&page=timesheets', '9f86066f-5f49-4acb-863d-1cb808d57b40'),
(1440, '10', 'viewvacancy/?id=3&page=shifts', '1ff1c094-360a-4fa5-b3f4-9fe4e2ad2cc7'),
(1441, '10', 'viewvacancy/?id=3&page=timesheets', '153078cf-9a58-4007-87f5-f396e12a64ba'),
(1442, '10', 'viewvacancy/?id=3&page=shifts', '7b1007ef-1fb5-4de4-b607-19336a646179'),
(1443, '10', 'viewvacancy/?id=3&page=profile', '8450e548-f8b1-4fb9-9094-c43e904e178f'),
(1444, '10', 'viewvacancy/?id=3&page=shifts', 'cb74b865-ae11-4c4e-8571-3a6c3247dfc3'),
(1445, '10', 'viewvacancy/?id=3&page=profile', 'b79648b7-84a7-4675-a395-31f0695eb926'),
(1446, '10', 'viewvacancy/?id=3&page=shifts', '0e71d95d-4e6c-4748-a61a-1dd37af2cc8a'),
(1447, '10', 'viewvacancy/?id=3&page=profile', 'af316949-83fc-474b-aab4-5cbce29e8ae0'),
(1448, '10', 'viewvacancy/?id=3&page=shifts', 'cd85dce1-b876-4381-91ff-79ace856feb3'),
(1449, '10', 'viewvacancy/?id=3&page=profile', '00c475bd-7bf5-437e-b8b6-dca3bdb16c79'),
(1450, '10', 'tool/profile', 'ae03e954-ae31-4e08-8852-a26149bc42ce'),
(1451, '10', 'interviews', 'ba2bbb6f-6900-47a4-af20-88e02c85b76b'),
(1452, '10', 'tool/bulkemail?emailid=09512100-80e0-409b-891c-637a627b62ea&compose=1', '09512100-80e0-409b-891c-637a627b62ea'),
(1453, '10', 'tool/bulkemail?emailid=09512100-80e0-409b-891c-637a627b62ea&clients=1', 'fa246e56-3018-4ceb-970e-2f47a38733aa'),
(1454, '10', 'tool/bulkemail?emailid=09512100-80e0-409b-891c-637a627b62ea&consultants=1', '74c6e725-b7bc-4955-9252-d8e3ee2ef5eb'),
(1455, '10', 'tool/bulkemail?emailid=09512100-80e0-409b-891c-637a627b62ea&candidates=1', '7eeeb519-c778-4a79-8231-3e2111609213'),
(1456, '10', 'tool/bulkemail?emailid=09512100-80e0-409b-891c-637a627b62ea&compose=1', 'cf4494b4-2562-4200-9ff9-f577eaf1372f'),
(1457, '10', 'tool/bulkemail?emailid=09512100-80e0-409b-891c-637a627b62ea&clients=1', '4f5acab7-5d56-45bd-8df2-f3aa2a81067a'),
(1458, '10', 'tool/bulkemail?emailid=09512100-80e0-409b-891c-637a627b62ea&candidates=1', '95c14c00-4517-40b9-9f9e-0627f91be204'),
(1459, '10', 'tool/bulkemail?emailid=09512100-80e0-409b-891c-637a627b62ea&compose=1', '8b13a640-082c-4f46-8b43-54e071e9d71b'),
(1460, '10', 'tool/bulkemail?emailid=09512100-80e0-409b-891c-637a627b62ea&candidates=1', '2d927e8f-2f2c-40ed-9257-fc21beab52e1'),
(1461, '10', 'tool/bulkemail?emailid=09512100-80e0-409b-891c-637a627b62ea&compose=1', 'd94561a9-23c5-4bca-b46d-f42e620f0469'),
(1462, '10', 'tool/bulkemail?emailid=09512100-80e0-409b-891c-637a627b62ea&candidates=1', 'fdfc36f2-8d73-49d9-ada6-2bbb37b5d8ae'),
(1463, '10', 'tool/bulkemail?emailid=09512100-80e0-409b-891c-637a627b62ea&compose=1', 'd6c2da10-74a3-4713-8d9a-4038ff93929a'),
(1464, '10', 'tool/bulkemail?emailid=09512100-80e0-409b-891c-637a627b62ea&clients=1', 'd57ff45a-86eb-4ed0-9233-bf2e4e18f8a9'),
(1465, '10', 'tool/bulkemail?emailid=09512100-80e0-409b-891c-637a627b62ea&candidates=1', '626906d3-7636-446d-afd7-115eda96af9e'),
(1466, '10', 'tool/bulkemail?emailid=09512100-80e0-409b-891c-637a627b62ea&compose=1', '9e8dc896-9d73-4b96-864d-cc3e631dc777'),
(1467, '5', 'vacancy', 'c2769b22-4bbc-418c-a5b7-ddc67945be59'),
(1468, '5', 'viewvacancy/?id=5&page=profile', '605350bb-6053-4a0b-bc99-d4c5bd4be3f7'),
(1469, '5', 'viewvacancy/?id=5&page=timesheets', 'e4d05a6a-b6f3-4312-8551-89c9ec4e0872'),
(1470, '5', 'viewvacancy/?id=5&page=shifts', '4997e9fe-5850-4f4d-9a54-82dd0741833f'),
(1471, '5', 'viewvacancy/?id=5&page=shifts&from=2024-04-29&to=2024-05-05', 'ba417faf-3bde-4590-9402-a4f107e86df0'),
(1472, '5', 'viewvacancy/?id=5&page=shifts&from=2024-04-22&to=2024-04-28', 'd5117e64-2dc6-4d2d-9532-7dfb4a99695e'),
(1473, '5', 'tool/profile', '655c5510-787f-4f1f-a070-5f65d69094ef'),
(1474, '5', 'candidates', '4ef58728-d92d-4dad-9d70-07b2ba7f8d27'),
(1475, '5', 'candidate/?id=64&page=home', '99920027-e524-4366-9ed6-9bc5e2839e49'),
(1476, '5', 'candidate/?id=64&page=checklist', 'b695a3a3-6d6c-4ed2-8074-4a79ecd7287d'),
(1477, '14', 'dashboard', 'b9631734-316e-441e-807c-fc3a6b77b4d6'),
(1478, '14', 'keyperformanceindicator', '153d18e4-f390-4080-8fc1-b1cd3158e570'),
(1479, '14', 'shifts', 'c00dda2f-9a74-4b60-be52-9f634b5b4209'),
(1480, '14', 'candidates', '620f6a8a-2867-4ff9-9c64-b573daba47c6'),
(1481, '14', 'clients', '1c4a3fee-cfc0-44f7-bfbc-ee0c7e789f56'),
(1482, '14', 'clients', 'd99b13e5-1cc0-4d9d-85a3-2ef9443c2cb1'),
(1483, '13', 'clients', 'bf4e613e-d985-4aaa-94f4-21f38b0a2b89'),
(1484, '13', 'keyjobareas', '2b06cf44-26bd-4d4c-aa46-799ecc2c4155'),
(1485, '14', 'candidates', 'b951a634-9c46-41e1-badc-78d3f5296950'),
(1486, '13', 'dashboard', '98ef16d4-19b9-4616-8b09-0717722702ac'),
(1487, '14', 'clients', 'b386154c-bb1a-47f4-ac99-ca3eef18a925'),
(1488, '13', 'candidates', '102d4830-05a3-4978-acda-f398cc3cddeb'),
(1489, '13', 'clients', '7cff4c95-ad07-4b8b-9dd3-a4e919899c38'),
(1490, '14', 'candidates', 'bdffe6ae-d594-44bf-8d31-b831730e49d0'),
(1491, '13', 'candidates', 'c993af40-7b2c-4edb-bcb8-888d88149948'),
(1492, '13', 'keyjobareas', '8fcf6150-3f76-456b-84bc-75207386a0ab'),
(1493, '13', 'dashboard', '9009b70e-b570-4f68-9fc8-00e7cd2ae584'),
(1494, '14', 'candidates/?searched=1&first_name=&last_name=&job_title=&gender=&email=&mobilenumber=&status=ACTIVE&postcode=&address=&city=&createdBy=', '246d73f2-d76e-43d9-9dcd-a9f0382b48ae'),
(1495, '14', 'candidates/?searched=1&first_name=&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=LONDON&createdBy=', '45773886-8f96-44eb-b52a-d468a28f279c'),
(1496, '14', 'dashboard', 'cd7b54b3-f9cc-4ebf-9b56-a69c4ef67926'),
(1497, '5', 'clients', '67c05ff6-89a2-4368-8dfb-bc262b801936'),
(1498, '5', 'clients', '1bede950-689f-45e5-8dee-792e9ef4f674'),
(1499, '14', 'clients', 'a642a11e-7bab-4b77-ae66-bbad831c1ac6'),
(1500, '14', 'clients', '9226ac79-8d5b-4719-9b57-ddcb9dc6b818'),
(1501, '14', 'interviews', 'e235f6dc-78ce-42bb-8e03-b9acb4895fd3'),
(1502, '14', 'dashboard', '16b98fcc-8323-425b-a110-ef413db3bdf0'),
(1503, '14', 'clients', '8df9fb3e-f0f9-4303-9d08-cfee0de69f9a'),
(1504, '3', 'allevents', '95cb2e1a-84d6-488b-98fb-781d6840a867'),
(1505, '3', 'candidates', '6e549f8a-4fb5-4204-bbca-55114ab26bdf'),
(1506, '14', 'calendar', '21ef71be-d543-49eb-bef4-42d942adc5fd'),
(1507, '14', 'clients', '39ec1e7c-6e6c-4c97-921f-9026354079cd'),
(1508, '3', 'candidates', 'fed83785-7e9f-4547-a43e-ca69eeb737ef'),
(1509, '3', 'candidate/?id=64&page=home', '9da5c864-f0ba-4232-8c87-433dac471127'),
(1510, '3', 'candidates', '3ddf3593-92bb-48ee-9176-2c00d5d25577'),
(1511, '3', 'candidate/?id=64&page=home', '4b2946e0-de5f-43b5-b33e-62869d5ea81d'),
(1512, '3', 'candidate/?id=64&page=log', 'f055d075-ce58-497e-806c-9b40d95e58b2'),
(1513, '3', 'candidate/?id=64&page=checklist', '53862305-a325-40d6-af49-8cdb184dde77'),
(1514, '14', 'dashboard', 'd66cbe27-8f1a-4755-bda9-5753f4a5f7b4'),
(1515, '14', 'dashboard', 'dc21611f-4e71-4d4c-958e-d6a3e487e948'),
(1516, '14', 'clients', '3a8a9e9d-bdc2-44f8-ad5b-1136d349e9bb'),
(1517, '5', 'newclient', '7b11c507-5e30-40cd-a0be-129391325ed3'),
(1518, '14', 'newclient', '4145f195-1342-43df-90c8-a6a4973058f2'),
(1519, '5', 'clients', '04f1e2db-b9a7-4fb8-93a6-6e0f2986c0ae'),
(1520, '14', 'clients', '20d4d0a5-392f-4417-baa2-01b550893a7a'),
(1521, '14', 'newclient', '441ad14b-d1e6-4762-84ed-3e3296e64ba3'),
(1522, '14', 'clients', '057abbe0-49ec-46a8-a49d-f515ae48ccb0'),
(1523, '14', 'interviews', '39358020-4d84-4b21-822c-04c86efd0fdc'),
(1524, '14', 'newinterview', '534be5a3-c460-433d-8b44-cabc129cd892'),
(1525, '5', 'interviews', '27acf5e5-4b0c-42b4-9738-16ee1878fb47'),
(1526, '14', 'keyperformanceindicator', '296b2b0e-db10-4fda-be0a-ada94b8d854d'),
(1527, '14', 'keyperformanceindicator', 'ca538a19-a897-422d-9a2a-564cb56284e8'),
(1528, '5', 'clients', '779b6c34-5365-4e13-af70-4b2f18d0793b'),
(1529, '14', 'keyperformanceindicator', '3eabcedd-3d58-49cb-b64f-3ec8a574602e'),
(1530, '5', 'keyperformanceindicator', '20bb5a4b-f018-4f25-b22d-594076e5887b'),
(1531, '14', 'keyperformanceindicator', '30ea1a06-184c-4fdf-8581-42ea4e3f831b'),
(1532, '14', 'keyperformanceindicator', 'c6bb0c1c-094f-4355-ba55-1697ae3fdbe9'),
(1533, '13', 'clients', 'e5825b04-8390-47c1-9ce6-bfd3f1426871'),
(1534, '13', 'candidates', 'ea2babc9-a39d-45c5-8de9-141ec723e5e6'),
(1535, '13', 'keyperformanceindicator', 'c683b5e9-54af-4bcc-89ed-61c753b17e25'),
(1536, '5', 'clients', 'd8fbb747-c6b9-4fa3-8fb1-0cc761c5ce71'),
(1537, '14', 'keyperformanceindicator', '1fec2971-9609-4ffa-ad12-1ca05c170ff5'),
(1538, '14', 'dashboard', 'ffc7ac51-488d-44cb-b4c8-f4a3afa233d3'),
(1539, '14', 'clients', '033bc5bb-f78f-477b-859f-6235f1223cbe'),
(1540, '14', 'clients', 'd1df0ff1-a0f3-499f-95e4-1dbee67ac1aa'),
(1541, '14', 'clients', 'f876de71-486e-4e5f-816c-8b56b7a8bf38'),
(1542, '14', 'dashboard', 'cfd09027-bdb8-4467-87c1-4d58a5df4b11'),
(1543, '14', 'clients', '416ee615-5c49-41c5-b72d-b962263a1a36'),
(1544, '14', 'keyperformanceindicator', '865f548b-3ea6-4811-8318-8d27a884b5f9'),
(1545, '14', 'keyperformanceindicator', 'fbf7fb04-e662-4ad1-bec4-2f0dbb51db08'),
(1546, '13', 'calendar', '59be406d-e2dd-475c-b3c0-7a37247727f8'),
(1547, '14', 'candidates', '793f0e0b-788f-4297-ae9e-5a4de696dc9d'),
(1548, '13', 'clients', 'f9f02b4d-b2a6-412a-a62e-541fb9854f6a'),
(1549, '14', 'interviews', '5619a34b-6629-43d3-bf3d-ae4d9b8993c8'),
(1550, '13', 'keyperformanceindicator', '67ad7fa8-4dfa-445f-b3b7-722a821e4afb'),
(1551, '14', 'vacancy', 'c4d337bc-1374-4875-9a0f-dd221dc62690'),
(1552, '14', 'timesheets', '5e689296-2625-49bd-b38e-d41a6039c2ba'),
(1553, '14', 'timesheets', '91404852-7a97-4d7e-835d-b2d3b0b53519'),
(1554, '13', 'clients', '85f20cd7-24a1-496b-a39a-31332f90e28e'),
(1555, '14', 'calendar', '901f1d2a-37a7-4077-931a-9c50339515be'),
(1556, '3', 'dashboard', '8dfeccc7-ccf7-46db-b091-5676b8261557'),
(1557, '3', 'candidates', '5f7282bc-62ac-488d-970d-52562715b709'),
(1558, '3', 'candidate/?id=64&page=home', '2e29b41f-6d6e-4c6b-95bf-73c61871c1e9'),
(1559, '3', 'candidate/?id=64&page=log', 'ccba509f-b6c1-4da6-9f77-601ec7ed6408'),
(1560, '3', 'candidate/?id=64&page=checklist', '71f6587c-2546-4d57-9431-131788252950'),
(1561, '3', 'candidates', 'c75789fb-98b5-4973-9202-85a7937b0fd7'),
(1562, '3', 'candidate/?id=64&page=home', '11638a1c-cd5b-4087-b7af-1987ef95eb4f'),
(1563, '3', 'candidate/?id=64&page=checklist', 'b8a1861f-4295-4b17-9ab9-d50180801dba'),
(1564, '0', 'dashboard', 'c854771b-1d02-4512-899e-13887ca76de0'),
(1565, '0', 'dashboard', 'dcab66c8-7884-4ded-b71a-09efa7803ebc'),
(1566, '10', 'tool/bulkemail?emailid=09512100-80e0-409b-891c-637a627b62ea&clients=1', 'cc9bcba1-2890-4f0e-ae07-8e5bc2d8cf28'),
(1567, '5', 'dashboard', 'a763fd2e-08d2-42ab-a365-511f34bb720f'),
(1568, '5', 'shifts', 'a6e944da-6e6b-48d1-a273-3eece239e6cb'),
(1569, '5', 'shifts?from=2024-03-25&to=2024-03-31', 'c1a12809-2bcd-44b3-9456-1bc5e0d2655b'),
(1570, '5', 'shifts?from=2024-03-18&to=2024-03-24', '7f3ca99a-37f2-490a-bb6b-353ede6062fa'),
(1571, '5', 'shifts?from=2024-06-03&to=2024-06-09', '1add5d81-e66f-4227-b000-d6a2b08d627d'),
(1572, '5', 'shifts?from=2024-06-03&to=2024-06-09', 'f8381444-ba01-48c8-b867-24cfd76b1b9e'),
(1573, '5', 'timesheets', '370ee05e-28ec-42f5-9adf-93126e7b3c85'),
(1574, '5', 'shifts', '364e8721-8d03-4935-be60-5dd9d6df3669'),
(1575, '5', 'newvacancy/0', '7283b959-9466-4825-8014-aa2b300ef5de'),
(1576, '5', 'vacancy', '757ab5cf-8c1b-4d64-b299-cbb25fc08eed'),
(1577, '5', 'viewvacancy/?id=3&page=profile', '2c4203d9-7c4a-4dd1-851e-a961bd26a6f2'),
(1578, '5', 'viewvacancy/?id=3&page=shifts', 'c8930ee1-3a61-4958-9d42-cc2ec9a77163'),
(1579, '5', 'timesheets', '99e3ad12-467f-4ccb-a676-85cebaea4855'),
(1580, '5', 'shifts', 'df343981-37ee-4622-8d7c-0afdd3ff1a0d'),
(1581, '5', 'timesheets', '86d2b272-389c-49bb-b175-992473c5a158'),
(1582, '5', 'timesheet/g/435806', '32fe7c7c-a1bb-421e-b036-3a2c9efa28dd'),
(1583, '5', 'vacancy', '6e64c32a-2239-426b-8a29-1ba99a238847'),
(1584, '5', 'viewvacancy/?id=3&page=profile', '43ba30b8-f238-4f99-ad27-ff10ccc22c4d'),
(1585, '5', 'viewvacancy/?id=3&page=shifts', '23991c91-f182-4528-9e82-f027ca4751a2'),
(1586, '5', 'viewvacancy/?id=3&page=timesheets', '53d8e915-f986-4c27-9f76-7ea37b56e721'),
(1587, '5', 'viewvacancy/?id=3&page=timesheets', '733893b9-687e-4595-a49e-19925be00664'),
(1588, '5', 'timesheets', '6c79b5e7-5982-40e1-8ff9-ea947e1b2e00'),
(1589, '5', 'timesheet/g/560743', '4e6522c1-164b-4a91-aea6-c35abdc63f5f'),
(1590, '5', 'timesheets', '61019b00-530b-4ac7-8991-1c2a78c34b3c'),
(1591, '5', 'tool/profile', '47bb689b-0ad0-4ead-824f-4140ece97295'),
(1592, '10', 'tool/profile', '3f5f8f1b-d9d5-4dfe-be7c-0b7d5b9f7766'),
(1593, '10', 'tool/bulkemail?emailid=f6806dcb-4b7e-4135-a12e-33a26e006ebc&compose=1', 'f6806dcb-4b7e-4135-a12e-33a26e006ebc'),
(1594, '14', 'candidates', '99c48879-46a7-4813-8169-76661ed4844c'),
(1595, '14', 'candidate/?id=81&page=home', '7a5c38a9-2e7b-4400-b91e-cc3022ab9df9'),
(1596, '14', 'candidate/?id=81&page=log', '8229411b-4c60-45e0-9d59-03e97d0d5dbe'),
(1597, '14', 'candidate/?id=81&page=checklist', 'd5b86e7d-5101-4876-aee4-58e3eace9fa6'),
(1598, '14', 'candidate/?id=81&page=home', '49aaa99c-a206-4fbe-b36a-13972c05b359'),
(1599, '14', 'candidates', '5b2f58d8-cdc6-4fa2-8da9-e6382af33363'),
(1600, '14', 'candidate/?id=50&page=home', '0dad8fae-484d-429c-8af1-0b7f999cf16f'),
(1601, '14', 'candidates', '16e89041-ba0c-4a23-9827-94a964f1cf36'),
(1602, '14', 'candidate/?id=65&page=home', 'a2253825-3599-41dd-afab-8afb4098fee3'),
(1603, '14', 'candidates', '08835b90-de71-46a2-9d7d-7acb1cec7cca'),
(1604, '14', 'candidate/?id=16&page=home', '50ca41e9-1a77-40ba-96ba-e4459692c3ad'),
(1605, '14', 'candidates', '47c66770-e82a-4cca-b164-47912eba24b1'),
(1606, '14', 'candidate/?id=81&page=home', '2df2f04a-18a4-4190-b832-89bbb1920b1a'),
(1607, '14', 'candidates', '74dd480c-0af2-4eeb-a6fd-d4e79816f180'),
(1608, '14', 'candidate/?id=81&page=home', '784d70f2-bedc-4690-91de-49dc75624804'),
(1609, '14', 'candidate/?id=81&page=checklist', 'dff606da-e6e3-455a-a553-1b2e3bf52e6f'),
(1610, '14', 'candidates', '5b096c8e-4f62-40c1-9992-6f9988f4c93a'),
(1611, '14', 'candidate/?id=220&page=home', '36a5d626-5476-44f6-bd78-4c3d6e47fc6f'),
(1612, '14', 'candidate/?id=220&page=checklist', '7e14c240-ef27-4235-b628-f2ad77683267'),
(1613, '14', 'candidate/?id=220&page=home', 'f336103b-bc0a-4570-81ab-2b5bca5da673'),
(1614, '14', 'candidates', '1478be8b-ed88-458e-9896-2fb8b9f56000'),
(1615, '14', 'candidate/?id=166&page=home', '0882cb01-973d-49fe-a45d-e172c6b1676f'),
(1616, '14', 'candidate/?id=166&page=log', '2bc00cbe-f27d-433f-a6b7-a06b5c149a6e'),
(1617, '14', 'candidate/?id=166&page=checklist', '1eae2244-4df6-4c0e-a57a-a1cc905bc4ad'),
(1618, '14', 'candidates', '14c69edb-d256-42ac-a17c-fda4aa962059'),
(1619, '5', 'candidates', '01e348ed-7b14-467c-b0ef-56f48ee951fb'),
(1620, '14', 'candidate/?id=81&page=home', 'dd761951-43c2-4f56-882c-46ce84c036eb'),
(1621, '5', 'candidate/?id=81&page=home', '94c4cfd2-5184-409a-9120-1d1d566b5ae3'),
(1622, '14', 'tool/bulkemail?emailid=73d4873f-7ff5-43e4-9cd3-7d0a361379a4&compose=1', '73d4873f-7ff5-43e4-9cd3-7d0a361379a4'),
(1623, '14', 'dashboard', 'b35266d1-3943-4a51-a5b4-d52f607d44ad'),
(1624, '5', 'candidates', '2a36f597-631a-4f95-85bd-205d03ffd608'),
(1625, '5', 'candidate/?id=64&page=home', 'ff719400-e3ac-49e2-bbc3-042bcd25a2e1'),
(1626, '5', 'candidate/?id=64&page=log', '38063a77-1f60-4ebc-a01d-de5ae87247e1'),
(1627, '5', 'candidate/?id=64&page=home', '0f40852c-6428-439a-b325-3ac855c51e0b'),
(1628, '5', 'candidate/?id=64&page=home', '3e557c8e-5802-416b-8de3-20b5f67d042b'),
(1629, '5', 'candidates', 'dacc2a63-0877-4a02-8162-f9a6e113d321'),
(1630, '5', 'candidate/?id=11&page=home', '713f0b51-2261-4f51-9a6d-6401e2db8e13'),
(1631, '14', 'candidates', 'a6bc6318-c68d-4799-8c09-4257d5b908ec'),
(1632, '14', 'candidate/?id=81&page=home', '028a9d9c-13ab-4e12-a0e4-14f7cc8032d6'),
(1633, '14', 'candidates', '61d8ad53-19ad-41d8-8439-c7658373ec3d'),
(1634, '14', 'candidate/?id=32&page=home', '2e9ac9a6-4294-4b54-bd9d-07cb9aea6ce2'),
(1635, '14', 'candidates', '8b66f3a7-cc14-4640-ba3e-65eaf0bdaac3'),
(1636, '14', 'candidate/?id=50&page=home', '8a350c98-d5bf-4827-9fd1-8733e565003e'),
(1637, '14', 'candidates', '6e54ee9c-9c24-42a9-aa68-398b4c20d9c5'),
(1638, '14', 'candidate/?id=214&page=home', '200c3dc8-0dda-4580-8853-33d144a72e93'),
(1639, '14', 'candidates', 'c1d55ded-3e2c-4bcb-ace1-354724bcfaf1'),
(1640, '14', 'candidate/?id=109&page=home', '46f95277-7e89-407f-b90c-99ae1d412ee0'),
(1641, '14', 'candidates', '2b9ecc37-029d-4776-8c3d-521d752e5d59'),
(1642, '0', 'dashboard', '282475ac-fb07-4552-94e2-aa16cd92a904'),
(1643, '0', 'clients', 'ba6fb0c1-070f-4ff3-be19-691569397aaf'),
(1644, '0', 'clients', 'ff078f1a-5c8d-4b4d-adf7-e0f71108afea'),
(1645, '0', 'clients', '5718ff06-bb20-4b9d-9718-473e74c58bc2'),
(1646, '0', 'clients', 'd89427b6-d6eb-49ca-bb1d-96a1ca2fc3a7'),
(1647, '0', 'dashboard', '058b6a22-1cae-4140-8b5d-09ae284eba33'),
(1648, '0', 'clients', '83a64c08-68a4-44f1-befd-3af9cd2d12c7'),
(1649, '0', 'clients', 'faad70c3-e62c-4c98-95e8-020d77378368'),
(1650, '0', 'candidates', 'b0cad784-5b4e-4460-b26d-031bcb8c1540'),
(1651, '5', 'vacancy', '5794d303-5f0f-48ae-9478-1db52ed3d858'),
(1652, '5', 'newvacancy/0', '574b7fdd-3155-42de-a274-5d7d7b8f83d5'),
(1653, '14', 'candidate/?id=64&page=home', '2cb7280e-7090-4f10-bc36-0e778fd466b4'),
(1654, '14', 'candidates', '0d50a36e-974c-486e-b087-efba147c8d56'),
(1655, '14', 'clients', '44d1d6ab-6ee0-435d-82ca-fe94d89bb71e'),
(1656, '14', 'tool/users', 'b309a83a-89b6-4132-a43a-eff887dbebfc'),
(1657, '14', 'calendar', '69bcde67-ad7f-421f-bbe9-f2b97d40a8de'),
(1658, '14', 'calendar', '52265f92-834a-4952-9cda-05aca8e78904'),
(1659, '14', 'allevents', '93f6b6ff-8406-42bc-902a-e8fc53f71707'),
(1660, '3', 'candidate/?id=64&page=checklist', '0057052f-eefd-485d-bb93-b73cb3592cc6'),
(1661, '3', 'dashboard', '7812cd66-ce52-4481-b665-ea41b0e13c81'),
(1662, '3', 'dashboard', '29e1c80b-9e96-4986-a347-866d6f8b0406'),
(1663, '3', 'candidates', '79c1d821-bf2a-4ccf-82be-65dccc487b49'),
(1664, '3', 'candidate/?id=64&page=home', '67a31965-7938-4d36-a78a-ead09aab0083'),
(1665, '3', 'candidate/?id=64&page=checklist', 'dfdb8785-7602-4f3b-a696-85d06cf406ab'),
(1666, '3', 'weeklyreport', '79025ddd-7031-49d5-83c4-ad02380f204a'),
(1667, '3', 'dashboard', 'f64017e6-d84a-48f0-a179-043cd1aab570'),
(1668, '3', 'candidates', '79c65e60-cd2b-40b6-86b0-8d7f4f583f12'),
(1669, '3', 'candidate/?id=32&page=home', '33f8f537-8f15-4ec3-80b1-377473afc584'),
(1670, '3', 'candidate/?id=32&page=checklist', '6a1ae6ff-4f5d-4343-92ae-381b5013af7a'),
(1671, '13', 'newclient', '9c4155b7-f594-42ae-935a-8e49d2f0be53'),
(1672, '13', 'candidates', '7be5fa8b-c3b5-4ebc-acee-08dc40f71ba6'),
(1673, '13', 'candidate/?id=204&page=home', '397e5fa1-dba4-410d-8f8d-76803b6c2721'),
(1674, '13', 'candidates', '8846d1ba-8370-4a91-a8ee-8f36438cf018'),
(1675, '13', 'candidates/?searched=1&first_name=olugb&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '1c004016-a35e-4bd4-8d90-d265522486f6'),
(1676, '13', 'candidate/?id=167&page=home', 'b9519a94-f3d1-44ec-963e-ec1d9087c9fd'),
(1677, '13', 'candidate/?id=167&page=log', '73734b6b-e32f-4d57-9a4e-647e335b73fe'),
(1678, '13', 'candidates', '5fac197e-da03-4664-9301-23ce2c609748'),
(1679, '13', 'candidates/?searched=1&first_name=geoff&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '9f2d393b-93fc-4e04-98dc-8f9cc1d726da'),
(1680, '13', 'candidate/?id=218&page=home', 'c8e95d0e-ad42-4f60-a593-23c6e190a053'),
(1681, '13', 'candidate/?id=218&page=log', 'b7934bdf-875a-43ed-9e56-11db5caf4e16'),
(1682, '13', 'candidates', '1b2a57ec-c4e6-4d74-b6c0-7f89ac0c76b0'),
(1683, '13', 'candidates/?searched=1&first_name=lilian&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'd16ffd2f-d529-4d4a-ae82-1adb90ad4b4b'),
(1684, '13', 'candidates/?searched=1&first_name=lilian&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '47fe2ff8-f152-4ea2-a5fa-2eddb6fee11a'),
(1685, '13', 'candidates', '11f0dcee-af6c-40bc-8281-5a94b2e59979'),
(1686, '13', 'candidates/?searched=1&first_name=lilian&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '30c396ca-10dc-47e9-b839-da1e2fe3b04b'),
(1687, '13', 'candidates/?searched=1&first_name=olayinka&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '20ae3cc1-86a5-45f0-bc65-cbbe6935f50f'),
(1688, '13', 'candidates/?searched=1&first_name=abosede&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '6e55b19b-c5b0-4860-bc01-ebfafedcd501'),
(1689, '13', 'candidates/?searched=1&first_name=patricia&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '3b970064-4bb6-4c6b-b1c2-efec6c18071b'),
(1690, '13', 'candidates/?searched=1&first_name=mercy&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'e5b6bb47-314e-4f9e-a7ce-e75cbe9f1087'),
(1691, '13', 'candidates/?searched=1&first_name=abdulfatai&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '8834a3fe-c699-4ae3-bb38-365fe0c93a5a'),
(1692, '13', 'candidates/?searched=1&first_name=nduaya&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '644b055c-88f0-4a07-bcfa-de5651ceb230'),
(1693, '13', 'candidates/?searched=1&first_name=olawale&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '5c059427-f493-44c9-b2f0-15ea736bc439'),
(1694, '13', 'candidates/?searched=1&first_name=isioma&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '49592bf5-532d-439d-b07d-b71eab56f282'),
(1695, '13', 'candidates/?searched=1&first_name=louise&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'ad8b17a3-98f1-4f2e-9cbf-7cfbf581e311'),
(1696, '13', 'candidates/?searched=1&first_name=temisan&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '1e72e603-4bee-4c6d-a3b2-12d5db1a9524'),
(1697, '13', 'candidates/?searched=1&first_name=faith&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '10f9b051-b4ba-486b-88e7-afdbafeac00b'),
(1698, '13', 'candidates/?searched=1&first_name=geoffrey&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '58a9a432-1a75-4f5f-a617-9d5dc6bc936a'),
(1699, '13', 'candidates/?searched=1&first_name=greay&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '427e992d-52fa-4181-a4be-96a7edbc8fa7'),
(1700, '13', 'candidates/?searched=1&first_name=great&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '61386904-92ab-45c7-88b4-0ec1d0b93cee'),
(1701, '13', 'candidates/?searched=1&first_name=lami&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'c1df90d4-7a4d-4df9-a7b2-f2baebde4962'),
(1702, '13', 'candidates/?searched=1&first_name=nuria&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'a3c0d20b-4bcc-4339-a572-ffefbef8060d'),
(1703, '13', 'candidates/?searched=1&first_name=&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '003412f6-7641-4974-b828-a9c4598570ea'),
(1704, '13', 'candidates/?searched=1&first_name=adejoke&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '9630f667-f173-459d-b5f4-7e3bf340d285'),
(1705, '13', 'candidates/?searched=1&first_name=comfort&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'de6c5a44-4767-420e-9b8f-2003e8c48406'),
(1706, '13', 'candidates/?searched=1&first_name=Akachukwu&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'bedcc7ae-bc5f-4ff6-a6df-bba89a86b932'),
(1707, '13', 'candidates/?searched=1&first_name=adekoya&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'a366582d-fe37-4ea9-a434-36003977c9fd'),
(1708, '13', 'candidates/?searched=1&first_name=asha&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'f97dd77d-6b40-43ec-ad65-50315b0215ea'),
(1709, '13', 'candidates/?searched=1&first_name=oluwole&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '45b90b12-e2c9-466c-813e-9b47f8582897'),
(1710, '13', 'candidates/?searched=1&first_name=dorcas&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '5fcbb3a1-d61c-4164-8d41-5143c7a38f36'),
(1711, '13', 'candidates/?searched=1&first_name=menjoh%20]&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '18357e42-d078-49ea-bac2-c8448a1ca194'),
(1712, '13', 'candidates/?searched=1&first_name=emmanuel&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '3f38495a-4675-4ccd-b23f-9a9b78e54940'),
(1713, '13', 'candidates/?searched=1&first_name=adeola&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '931866ca-e6cf-49ed-8eb0-dfb01e649e9f'),
(1714, '13', 'candidates/?searched=1&first_name=timileyin&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '497704f2-21b6-4fe8-93fd-86fc984bcdce'),
(1715, '13', 'candidates/?searched=1&first_name=abisoye&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', 'a85672ba-6a11-4ae5-82e8-7d66424b555c'),
(1716, '13', 'newcandidate', '1d63c232-c6a9-4f09-8fbe-1fe524691229'),
(1717, '13', 'newcandidate', '2f1464d4-3541-472c-bb74-c0da4359ffb3'),
(1718, '13', 'newcandidate', 'd477ff4a-2de9-4481-9c85-ad2ffe387ec2'),
(1719, '13', 'newcandidate', '76e59251-701b-4c6f-aab4-1b03d616e053'),
(1720, '13', 'newcandidate', '4b6bf889-faca-40fa-a84c-e809429cbad5'),
(1721, '13', 'newcandidate', 'dd9b28a3-a5b1-4dc5-a7df-d6cfffaa0dc7'),
(1722, '13', 'newcandidate', 'deb40c65-303e-4575-9b20-f8ab1ca529e4'),
(1723, '13', 'newcandidate', '33308522-5d97-45b7-95f9-4a4e9e66d491'),
(1724, '13', 'newcandidate', '27cc2408-649b-4e8c-8e83-f6ec8cfb3355'),
(1725, '13', 'newcandidate', '7f4f5089-d144-421d-9380-ee826b088f47'),
(1726, '13', 'candidates', 'ea5b93f2-d96e-497a-bbe5-f0b3c7ed6a23'),
(1727, '13', 'candidates/?searched=1&first_name=adekoya&last_name=&job_title=&gender=&email=&mobilenumber=&status=&postcode=&address=&city=&createdBy=', '67f75be4-e6a6-4474-807a-85912edc9947'),
(1728, '13', 'candidate/?id=238&page=home', 'bb3eacca-142f-4091-9563-1a2759b6ac35'),
(1729, '13', 'candidate/?id=238&page=log', '21806c40-79fa-484d-9e31-3179648cd75b'),
(1730, '13', 'candidate/?id=238&page=home', 'c5b3f925-0ad1-4928-91df-6206d88815d0'),
(1731, '13', 'candidate/?id=238&page=log', '5dc37b5e-0962-4fa0-b5a3-e43677aab55a'),
(1732, '13', 'candidate/?id=238&page=home', 'a8e07f4b-80fd-4b99-8ef4-a083bb874774'),
(1733, '13', 'newcandidate', '89b4b9f7-9522-4583-8e10-cda8e2346bc9'),
(1734, '13', 'newcandidate', 'd8fd170f-4b4e-4b34-b459-f841b9af59ec'),
(1735, '13', 'newcandidate', '49cc6673-d612-4fbc-877a-adfb24dda741'),
(1736, '13', 'newcandidate', '9592a589-87cf-4ee2-912e-80fb8fb985c2'),
(1737, '13', 'newcandidate', '10d78f39-927a-453e-9220-7056328af9ba');

-- --------------------------------------------------------

--
-- Table structure for table `permission`
--

CREATE TABLE `permission` (
  `id` int(11) NOT NULL,
  `userid` text NOT NULL,
  `category` text NOT NULL,
  `permission` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `permission`
--

INSERT INTO `permission` (`id`, `userid`, `category`, `permission`) VALUES
(171, '5', 'Compliance Checklist', 'View Documents'),
(172, '5', 'Compliance Checklist', 'Delete Document'),
(173, '5', 'Communication Log', 'Delete Log'),
(174, '5', 'Communication Log', 'Add Log'),
(175, '5', 'Interviews', 'View Interviews'),
(176, '5', 'Interviews', 'Create an interview'),
(177, '5', 'Interviews', 'Create an interview'),
(178, '5', 'Files', 'Export Files');

-- --------------------------------------------------------

--
-- Table structure for table `planner`
--

CREATE TABLE `planner` (
  `id` int(11) NOT NULL,
  `app_id` text NOT NULL,
  `title` text NOT NULL,
  `date` text NOT NULL,
  `time` text NOT NULL,
  `backgroundColor` text NOT NULL,
  `borderColor` text NOT NULL,
  `createdBy` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `planner`
--

INSERT INTO `planner` (`id`, `app_id`, `title`, `date`, `time`, `backgroundColor`, `borderColor`, `createdBy`) VALUES
(1, '169325', 'Compliance Meeting ', '2023-07-18', '10:30', 'pink', 'pink', '15');

-- --------------------------------------------------------

--
-- Table structure for table `search`
--

CREATE TABLE `search` (
  `id` text NOT NULL,
  `object` text NOT NULL,
  `value` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `shifts`
--

CREATE TABLE `shifts` (
  `id` int(11) NOT NULL,
  `app_id` text NOT NULL,
  `shiftid` text NOT NULL,
  `client` text NOT NULL,
  `vacancyid` text NOT NULL,
  `candidateid` text NOT NULL,
  `date` text NOT NULL,
  `shifttype` text NOT NULL,
  `hours` varchar(200) NOT NULL DEFAULT '0',
  `margin` text NOT NULL,
  `totalmargin` text NOT NULL,
  `supplyrate` text NOT NULL,
  `payrate` text NOT NULL,
  `starttime` text NOT NULL,
  `endtime` text NOT NULL,
  `createdBy` text NOT NULL,
  `createdOn` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `shifts`
--

INSERT INTO `shifts` (`id`, `app_id`, `shiftid`, `client`, `vacancyid`, `candidateid`, `date`, `shifttype`, `hours`, `margin`, `totalmargin`, `supplyrate`, `payrate`, `starttime`, `endtime`, `createdBy`, `createdOn`) VALUES
(1, '169325', '79a6c73e-2f16-496f-814d-a74dd1528b73', '12', '1', '4', '2024-02-19', 'Regular', '13.77', '15', '206.55', '45', '30', '06:00', '19:46', '10', '2024-02-22 16:21:43'),
(2, '169325', '6162abec-dce1-4c10-ad80-9f5019a271dc', '12', '1', '1', '2024-02-19', 'Regular', '13.77', '15', '206.55', '45', '30', '06:00', '19:46', '10', '2024-02-22 16:29:20'),
(3, '169325', '9188b187-4469-44ac-8768-6c4abf8f80b4', '8', '3', '6', '2024-02-22', 'Regular', '20', '5', '100.00', '25', '20', '15:25', '15:25', '10', '2024-02-22 16:40:43'),
(4, '169325', 'a7b37cbf-71ab-4ce6-986a-9a1b36c5aa92', '8', '3', '1', '2024-03-17', 'Regular', '0.00', '5', '0.00', '25', '20', '15:25', '15:25', '16', '2024-03-14 14:08:58'),
(5, '169325', '5a714271-30c1-4523-9eec-5e89e03baf6a', '8', '3', '1', '2024-04-03', 'Regular', '0.00', '5', '0.00', '25', '20', '15:25', '15:25', '10', '2024-04-10 15:32:32'),
(6, '169325', '25828a9a-9310-4804-a1f4-753764f585ca', '24', '5', '1', '2024-05-09', 'Regular', '5', '0', '0.00', '45', '20', '12:59', '12:59', '10', '2024-05-09 15:00:45'),
(7, '169325', '780d7dfa-c678-4c4c-85c0-8e6fbfbe8c9a', '8', '3', '158', '2024-06-04', 'Regular', '7', '5', '35.00', '25', '20', '15:25', '15:25', '5', '2024-06-04 17:27:27'),
(8, '169325', '99e3ad12-467f-4ccb-a676-85cebaea4855', '8', '3', '159', '2024-06-06', 'Regular', '6', '5', '30.00', '25', '20', '15:25', '15:25', '5', '2024-06-04 17:28:27');

-- --------------------------------------------------------

--
-- Table structure for table `shifttype`
--

CREATE TABLE `shifttype` (
  `id` int(11) NOT NULL,
  `app_id` text NOT NULL,
  `name` text NOT NULL,
  `createdBy` text NOT NULL,
  `createdOn` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `shifttype`
--

INSERT INTO `shifttype` (`id`, `app_id`, `name`, `createdBy`, `createdOn`) VALUES
(1, '169326', 'Regular', 'Andie  ', '9th May 2023'),
(2, '169326', 'Night Shift', 'Andie  ', '9th May 2023'),
(3, '169326', 'Holiday Shift', 'Andie  ', '9th May 2023'),
(4, '169326', 'Holiday Night', 'Andie  ', '9th May 2023'),
(5, '169326', 'Chax Shift', 'Andie  ', '9th May 2023'),
(6, '169325', 'Regular', 'Chax  Shamwana', '19th May 2023'),
(7, '169325', 'Saturday Day ', 'Chax  Shamwana', '19th May 2023'),
(8, '169325', 'Sunday Day ', 'Chax  Shamwana', '19th May 2023'),
(9, '169325', 'Weekday Night', 'Chax  Shamwana', '19th May 2023'),
(10, '169325', 'Saturday Night', 'Chax  Shamwana', '19th May 2023'),
(11, '169325', 'Sunday Night', 'Chax  Shamwana', '19th May 2023'),
(12, '169325', 'Bank Holiday ', 'Chax  Shamwana', '19th May 2023'),
(13, '169325', 'Bank Holiday Night ', 'Chax  Shamwana', '19th May 2023'),
(14, '950862c44989d6795534f8415257b08a', 'Day Shift', 'J  Musenge', '20th May 2023'),
(15, '950862c44989d6795534f8415257b08a', 'Night Shift', 'J  Musenge', '20th May 2023');

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `id` int(11) NOT NULL,
  `app_id` text NOT NULL,
  `candidateid` text NOT NULL,
  `skill` text NOT NULL,
  `createdBy` text NOT NULL,
  `createdOn` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `timesheet`
--

CREATE TABLE `timesheet` (
  `id` int(11) NOT NULL,
  `app_id` text NOT NULL,
  `status` text NOT NULL,
  `timesheetid` text NOT NULL,
  `candidate` text NOT NULL,
  `client` text NOT NULL,
  `vacancyid` text NOT NULL,
  `consultant` text NOT NULL,
  `approvedBy` text NOT NULL,
  `approvedOn` text NOT NULL,
  `createdBy` text NOT NULL,
  `createdOn` text NOT NULL,
  `time` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `timesheet`
--

INSERT INTO `timesheet` (`id`, `app_id`, `status`, `timesheetid`, `candidate`, `client`, `vacancyid`, `consultant`, `approvedBy`, `approvedOn`, `createdBy`, `createdOn`, `time`) VALUES
(1, '169325', 'Saved', '897513983', '2', '8', '45818625362001226', '9', '5', '2023-11-19', '5', '2023-11-19', '08:04:32'),
(2, '169325', 'Saved', '472517207', '6', '8', '45818625362001226', '9', '', '', '5', '2023-11-20', '20:27:59'),
(4, '169325', 'unsaved', '937161190', '1', '1', '92311659980965653', '9', '', '', '1', '2024-01-15', '01:09:49'),
(5, '169325', 'unsaved', '862164567', '1', '1', '92311659980965653', '9', '', '', '1', '2024-01-15', '01:11:35'),
(6, '169325', 'unsaved', '80277173', '1', '1', '92311659980965653', '9', '', '', '1', '2024-01-15', '01:11:43'),
(7, '169325', 'unsaved', '1441863893', '1', '1', '92311659980965653', '9', '', '', '1', '2024-01-15', '01:14:24'),
(8, '169325', 'unsaved', '93139517', '1', '1', '92311659980965653', '9', '', '', '1', '2024-01-15', '01:15:02'),
(9, '169325', 'unsaved', '911169297', '1', '1', '92311659980965653', '9', '', '', '1', '2024-01-15', '01:15:15'),
(10, '169325', 'unsaved', '664927774', '1', '1', '92311659980965653', '9', '', '', '1', '2024-01-15', '01:15:20'),
(11, '', 'unsaved', '1591791947', '1', '1', '92311659980965653', '9', '', '', '', '2024-01-15', '12:24:08'),
(12, '', 'unsaved', '2038801998', '1', '1', '92311659980965653', '9', '', '', '', '2024-01-15', '12:24:44'),
(13, '169325', 'Saved', '520805', '1', '12', '39919306674362112', '9', '1', '2024-01-29', '1', '2024-01-29', '19:48'),
(14, '169325', 'unsaved', '749036', '4', '', '', '9', '', '', '10', '2024-02-22', '14:25'),
(15, '169325', 'unsaved', '800308', '4', '', '', '9', '', '', '10', '2024-02-22', '14:25'),
(16, '169325', 'unsaved', '160328', '4', '12', '39919306674362112', '9', '', '', '10', '2024-02-22', '14:26'),
(17, '169325', 'unsaved', '387893', '1', '12', '39919306674362112', '9', '', '', '10', '2024-02-22', '14:31'),
(18, '169325', 'unsaved', '954690', '2', '12', '39919306674362112', '9', '', '', '10', '2024-02-22', '14:42'),
(19, '169325', 'unsaved', '435806', '158', '8', '93517583589372535', '9', '', '', '5', '2024-06-04', '15:29'),
(20, '169325', 'unsaved', '560743', '159', '8', '93517583589372535', '9', '', '', '5', '2024-06-04', '15:33');

-- --------------------------------------------------------

--
-- Table structure for table `timesheetlist`
--

CREATE TABLE `timesheetlist` (
  `id` int(11) NOT NULL,
  `app_id` text NOT NULL,
  `timesheetid` text NOT NULL,
  `date` text NOT NULL,
  `starttime` text NOT NULL,
  `endtime` text NOT NULL,
  `breaktime` text NOT NULL,
  `ratetype` text NOT NULL,
  `hours` text NOT NULL,
  `payrate` text NOT NULL,
  `supplierrate` text NOT NULL,
  `margin` text NOT NULL,
  `totalpayrate` text NOT NULL,
  `totalsupplierrate` text NOT NULL,
  `totalmargin` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `timesheetlist`
--

INSERT INTO `timesheetlist` (`id`, `app_id`, `timesheetid`, `date`, `starttime`, `endtime`, `breaktime`, `ratetype`, `hours`, `payrate`, `supplierrate`, `margin`, `totalpayrate`, `totalsupplierrate`, `totalmargin`) VALUES
(2653414, '169325', '384267127', '2023-11-06', '', '', '', '', '', '', '', '', '', '', ''),
(2653415, '169325', '384267127', '2023-11-07', '', '', '', '', '', '', '', '', '', '', ''),
(2653416, '169325', '384267127', '2023-11-08', '', '', '', '', '', '', '', '', '', '', ''),
(2653417, '169325', '384267127', '2023-11-09', '', '', '', '', '', '', '', '', '', '', ''),
(2653418, '169325', '384267127', '2023-11-10', '', '', '', '', '', '', '', '', '', '', ''),
(2653419, '169325', '384267127', '2023-11-11', '', '', '', '', '', '', '', '', '', '', ''),
(2653420, '169325', '384267127', '2023-11-12', '', '', '', '', '', '', '', '', '', '', ''),
(2653424, '169325', '1027819568', '2023-11-06', '', '', '', '', '', '', '', '', '', '', ''),
(2653425, '169325', '1027819568', '2023-11-07', '', '', '', '', '', '', '', '', '', '', ''),
(2653426, '169325', '1027819568', '2023-11-08', '', '', '', '', '', '', '', '', '', '', ''),
(2653427, '169325', '1027819568', '2023-11-09', '', '', '', '', '', '', '', '', '', '', ''),
(2653428, '169325', '1027819568', '2023-11-10', '', '', '', '', '', '', '', '', '', '', ''),
(2653429, '169325', '1027819568', '2023-11-11', '', '', '', '', '', '', '', '', '', '', ''),
(2653430, '169325', '1027819568', '2023-11-12', '', '', '', '', '', '', '', '', '', '', ''),
(2653432, '169325', '1027819568', '2023-11-06', '00:53', '00:53', '0', 'Regular', '6', '100', '900', '800', '600', '5400', '4800'),
(2653433, '169325', '897513983', '2023-11-06', '', '', '', '', '', '', '', '', '', '', ''),
(2653434, '169325', '897513983', '2023-11-07', '', '', '', '', '', '', '', '', '', '', ''),
(2653435, '169325', '897513983', '2023-11-08', '', '', '', '', '', '', '', '', '', '', ''),
(2653436, '169325', '897513983', '2023-11-09', '', '', '', '', '', '', '', '', '', '', ''),
(2653437, '169325', '897513983', '2023-11-10', '', '', '', '', '', '', '', '', '', '', ''),
(2653438, '169325', '897513983', '2023-11-11', '', '', '', '', '', '', '', '', '', '', ''),
(2653439, '169325', '897513983', '2023-11-12', '', '', '', '', '', '', '', '', '', '', ''),
(2653442, '169325', '897513983', '2023-11-06', '07:30', '22:00', '0', 'Sunday Day', '4', '15', '34.91', '19.91', '60', '139.64', '79.64'),
(2653443, '169325', '472517207', '2023-11-06', '', '', '', '', '', '', '', '', '', '', ''),
(2653444, '169325', '472517207', '2023-11-07', '', '', '', '', '', '', '', '', '', '', ''),
(2653445, '169325', '472517207', '2023-11-08', '', '', '', '', '', '', '', '', '', '', ''),
(2653446, '169325', '472517207', '2023-11-09', '', '', '', '', '', '', '', '', '', '', ''),
(2653447, '169325', '472517207', '2023-11-10', '', '', '', '', '', '', '', '', '', '', ''),
(2653448, '169325', '472517207', '2023-11-11', '', '', '', '', '', '', '', '', '', '', ''),
(2653449, '169325', '472517207', '2023-11-12', '', '', '', '', '', '', '', '', '', '', ''),
(2653450, '169325', '472517207', '2023-11-06', '07:30', '22:00', '0', 'Bank Holiday', '4', '16.61', '32.2', '15.59', '66.44', '128.8', '62.36'),
(2653451, '169325', '472517207', '2023-11-08', '07:30', '22:00', '0', 'Saturday Day', '3', '15', '24.4', '9.4', '45', '73.2', '28.2'),
(2653452, '169325', '472517207', '2023-11-09', '07:30', '22:00', '0', 'Regular', '6', '14.67', '19.28', '4.61', '88.02', '115.68', '27.66'),
(2653453, '169325', '897513983', '2023-11-08', '07:30', '22:00', '0', 'Bank Holiday', '5', '16.61', '32.2', '15.59', '83.05', '161', '77.95'),
(2653454, '169325', '897513983', '2023-11-07', '07:30', '22:00', '0', 'Saturday Day', '4', '15', '24.4', '9.4', '60', '97.6', '37.6'),
(2653480, '169325', '937161190', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653481, '169325', '937161190', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653482, '169325', '937161190', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653483, '169325', '937161190', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653484, '169325', '937161190', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653485, '169325', '937161190', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653486, '169325', '937161190', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653487, '169325', '937161190', '2024-01-08', '', '', '', '', '', '', '', '', '', '', ''),
(2653488, '169325', '937161190', '2024-01-09', '', '', '', '', '', '', '', '', '', '', ''),
(2653489, '169325', '937161190', '2024-01-10', '', '', '', '', '', '', '', '', '', '', ''),
(2653490, '169325', '937161190', '2024-01-11', '', '', '', '', '', '', '', '', '', '', ''),
(2653491, '169325', '937161190', '2024-01-12', '', '', '', '', '', '', '', '', '', '', ''),
(2653492, '169325', '937161190', '2024-01-13', '', '', '', '', '', '', '', '', '', '', ''),
(2653493, '169325', '937161190', '2024-01-14', '', '', '', '', '', '', '', '', '', '', ''),
(2653494, '169325', '862164567', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653495, '169325', '862164567', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653496, '169325', '862164567', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653497, '169325', '862164567', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653498, '169325', '862164567', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653499, '169325', '862164567', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653500, '169325', '862164567', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653501, '169325', '862164567', '2024-01-08', '', '', '', '', '', '', '', '', '', '', ''),
(2653502, '169325', '862164567', '2024-01-09', '', '', '', '', '', '', '', '', '', '', ''),
(2653503, '169325', '862164567', '2024-01-10', '', '', '', '', '', '', '', '', '', '', ''),
(2653504, '169325', '862164567', '2024-01-11', '', '', '', '', '', '', '', '', '', '', ''),
(2653505, '169325', '862164567', '2024-01-12', '', '', '', '', '', '', '', '', '', '', ''),
(2653506, '169325', '862164567', '2024-01-13', '', '', '', '', '', '', '', '', '', '', ''),
(2653507, '169325', '862164567', '2024-01-14', '', '', '', '', '', '', '', '', '', '', ''),
(2653508, '169325', '80277173', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653509, '169325', '80277173', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653510, '169325', '80277173', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653511, '169325', '80277173', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653512, '169325', '80277173', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653513, '169325', '80277173', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653514, '169325', '80277173', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653515, '169325', '80277173', '2024-01-08', '', '', '', '', '', '', '', '', '', '', ''),
(2653516, '169325', '80277173', '2024-01-09', '', '', '', '', '', '', '', '', '', '', ''),
(2653517, '169325', '80277173', '2024-01-10', '', '', '', '', '', '', '', '', '', '', ''),
(2653518, '169325', '80277173', '2024-01-11', '', '', '', '', '', '', '', '', '', '', ''),
(2653519, '169325', '80277173', '2024-01-12', '', '', '', '', '', '', '', '', '', '', ''),
(2653520, '169325', '80277173', '2024-01-13', '', '', '', '', '', '', '', '', '', '', ''),
(2653521, '169325', '80277173', '2024-01-14', '', '', '', '', '', '', '', '', '', '', ''),
(2653522, '169325', '1441863893', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653523, '169325', '1441863893', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653524, '169325', '1441863893', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653525, '169325', '1441863893', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653526, '169325', '1441863893', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653527, '169325', '1441863893', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653528, '169325', '1441863893', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653529, '169325', '1441863893', '2024-01-08', '', '', '', '', '', '', '', '', '', '', ''),
(2653530, '169325', '1441863893', '2024-01-09', '', '', '', '', '', '', '', '', '', '', ''),
(2653531, '169325', '1441863893', '2024-01-10', '', '', '', '', '', '', '', '', '', '', ''),
(2653532, '169325', '1441863893', '2024-01-11', '', '', '', '', '', '', '', '', '', '', ''),
(2653533, '169325', '1441863893', '2024-01-12', '', '', '', '', '', '', '', '', '', '', ''),
(2653534, '169325', '1441863893', '2024-01-13', '', '', '', '', '', '', '', '', '', '', ''),
(2653535, '169325', '1441863893', '2024-01-14', '', '', '', '', '', '', '', '', '', '', ''),
(2653536, '169325', '93139517', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653537, '169325', '93139517', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653538, '169325', '93139517', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653539, '169325', '93139517', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653540, '169325', '93139517', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653541, '169325', '93139517', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653542, '169325', '93139517', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653543, '169325', '93139517', '2024-01-08', '', '', '', '', '', '', '', '', '', '', ''),
(2653544, '169325', '93139517', '2024-01-09', '', '', '', '', '', '', '', '', '', '', ''),
(2653545, '169325', '93139517', '2024-01-10', '', '', '', '', '', '', '', '', '', '', ''),
(2653546, '169325', '93139517', '2024-01-11', '', '', '', '', '', '', '', '', '', '', ''),
(2653547, '169325', '93139517', '2024-01-12', '', '', '', '', '', '', '', '', '', '', ''),
(2653548, '169325', '93139517', '2024-01-13', '', '', '', '', '', '', '', '', '', '', ''),
(2653549, '169325', '93139517', '2024-01-14', '', '', '', '', '', '', '', '', '', '', ''),
(2653550, '169325', '911169297', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653551, '169325', '911169297', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653552, '169325', '911169297', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653553, '169325', '911169297', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653554, '169325', '911169297', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653555, '169325', '911169297', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653556, '169325', '911169297', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653557, '169325', '911169297', '2024-01-08', '', '', '', '', '', '', '', '', '', '', ''),
(2653558, '169325', '911169297', '2024-01-09', '', '', '', '', '', '', '', '', '', '', ''),
(2653559, '169325', '911169297', '2024-01-10', '', '', '', '', '', '', '', '', '', '', ''),
(2653560, '169325', '911169297', '2024-01-11', '', '', '', '', '', '', '', '', '', '', ''),
(2653561, '169325', '911169297', '2024-01-12', '', '', '', '', '', '', '', '', '', '', ''),
(2653562, '169325', '911169297', '2024-01-13', '', '', '', '', '', '', '', '', '', '', ''),
(2653563, '169325', '911169297', '2024-01-14', '', '', '', '', '', '', '', '', '', '', ''),
(2653564, '169325', '664927774', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653565, '169325', '664927774', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653566, '169325', '664927774', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653567, '169325', '664927774', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653568, '169325', '664927774', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653569, '169325', '664927774', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653570, '169325', '664927774', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653571, '169325', '664927774', '2024-01-08', '', '', '', '', '', '', '', '', '', '', ''),
(2653572, '169325', '664927774', '2024-01-09', '', '', '', '', '', '', '', '', '', '', ''),
(2653573, '169325', '664927774', '2024-01-10', '', '', '', '', '', '', '', '', '', '', ''),
(2653574, '169325', '664927774', '2024-01-11', '', '', '', '', '', '', '', '', '', '', ''),
(2653575, '169325', '664927774', '2024-01-12', '', '', '', '', '', '', '', '', '', '', ''),
(2653576, '169325', '664927774', '2024-01-13', '', '', '', '', '', '', '', '', '', '', ''),
(2653577, '169325', '664927774', '2024-01-14', '', '', '', '', '', '', '', '', '', '', ''),
(2653578, '', '1591791947', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653579, '', '1591791947', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653580, '', '1591791947', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653581, '', '1591791947', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653582, '', '1591791947', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653583, '', '1591791947', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653584, '', '1591791947', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653585, '', '1591791947', '2024-01-08', '', '', '', '', '', '', '', '', '', '', ''),
(2653586, '', '1591791947', '2024-01-09', '', '', '', '', '', '', '', '', '', '', ''),
(2653587, '', '1591791947', '2024-01-10', '', '', '', '', '', '', '', '', '', '', ''),
(2653588, '', '1591791947', '2024-01-11', '', '', '', '', '', '', '', '', '', '', ''),
(2653589, '', '1591791947', '2024-01-12', '', '', '', '', '', '', '', '', '', '', ''),
(2653590, '', '1591791947', '2024-01-13', '', '', '', '', '', '', '', '', '', '', ''),
(2653591, '', '1591791947', '2024-01-14', '', '', '', '', '', '', '', '', '', '', ''),
(2653592, '', '2038801998', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653593, '', '2038801998', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653594, '', '2038801998', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653595, '', '2038801998', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653596, '', '2038801998', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653597, '', '2038801998', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653598, '', '2038801998', '2024-01-15', '07:00', '17:00', '0', 'Regular', '0', '14', '18', '4', '0', '0', '0'),
(2653599, '', '2038801998', '2024-01-08', '', '', '', '', '', '', '', '', '', '', ''),
(2653600, '', '2038801998', '2024-01-09', '', '', '', '', '', '', '', '', '', '', ''),
(2653601, '', '2038801998', '2024-01-10', '', '', '', '', '', '', '', '', '', '', ''),
(2653602, '', '2038801998', '2024-01-11', '', '', '', '', '', '', '', '', '', '', ''),
(2653603, '', '2038801998', '2024-01-12', '', '', '', '', '', '', '', '', '', '', ''),
(2653604, '', '2038801998', '2024-01-13', '', '', '', '', '', '', '', '', '', '', ''),
(2653605, '', '2038801998', '2024-01-14', '', '', '', '', '', '', '', '', '', '', ''),
(2653606, '169325', '520805', '2024-01-22', '', '', '', '', '', '', '', '', '', '', ''),
(2653607, '169325', '520805', '2024-01-23', '', '', '', '', '', '', '', '', '', '', ''),
(2653608, '169325', '520805', '2024-01-24', '', '', '', '', '', '', '', '', '', '', ''),
(2653609, '169325', '520805', '2024-01-25', '', '', '', '', '', '', '', '', '', '', ''),
(2653610, '169325', '520805', '2024-01-26', '', '', '', '', '', '', '', '', '', '', ''),
(2653611, '169325', '520805', '2024-01-27', '', '', '', '', '', '', '', '', '', '', ''),
(2653612, '169325', '520805', '2024-01-28', '', '', '', '', '', '', '', '', '', '', ''),
(2653613, '169325', '520805', '2024-01-22', '15:21', '15:21', '0', 'Regular', '7', '5', '7', '2', '35', '49', '14'),
(2653614, '169325', '520805', '2024-01-23', '15:21', '15:21', '0', 'Regular', '6', '5', '7', '2', '30', '42', '12'),
(2653615, '169325', '520805', '2024-01-24', '15:21', '15:21', '0', 'Regular', '8', '5', '7', '2', '40', '56', '16'),
(2653616, '169325', '520805', '2024-01-25', '15:21', '15:21', '0', 'Regular', '8', '5', '7', '2', '40', '56', '16'),
(2653617, '169325', '520805', '2024-01-26', '15:21', '15:21', '0', 'Regular', '9', '5', '7', '2', '45', '63', '18'),
(2653618, '169325', '520805', '2024-01-27', '15:21', '15:21', '0', 'Regular', '9', '5', '7', '2', '45', '63', '18'),
(2653619, '169325', '520805', '2024-01-28', '15:21', '15:21', '0', 'Regular', '9', '5', '7', '2', '45', '63', '18'),
(2653620, '169325', '749036', '2024-02-22', '06:00', '19:46', '0', 'Regular', '13.77', '30', '45', '15', '413.1', '619.65', '206.55'),
(2653621, '169325', '749036', '2024-02-16', '', '', '', '', '', '', '', '', '', '', ''),
(2653622, '169325', '749036', '2024-02-17', '', '', '', '', '', '', '', '', '', '', ''),
(2653623, '169325', '749036', '2024-02-18', '', '', '', '', '', '', '', '', '', '', ''),
(2653624, '169325', '749036', '2024-02-19', '', '', '', '', '', '', '', '', '', '', ''),
(2653625, '169325', '749036', '2024-02-20', '', '', '', '', '', '', '', '', '', '', ''),
(2653626, '169325', '749036', '2024-02-21', '', '', '', '', '', '', '', '', '', '', ''),
(2653627, '169325', '749036', '2024-02-22', '', '', '', '', '', '', '', '', '', '', ''),
(2653628, '169325', '800308', '2024-02-22', '06:00', '19:46', '0', 'Regular', '13.77', '30', '45', '15', '413.1', '619.65', '206.55'),
(2653629, '169325', '800308', '2024-02-16', '', '', '', '', '', '', '', '', '', '', ''),
(2653630, '169325', '800308', '2024-02-17', '', '', '', '', '', '', '', '', '', '', ''),
(2653631, '169325', '800308', '2024-02-18', '', '', '', '', '', '', '', '', '', '', ''),
(2653632, '169325', '800308', '2024-02-19', '', '', '', '', '', '', '', '', '', '', ''),
(2653633, '169325', '800308', '2024-02-20', '', '', '', '', '', '', '', '', '', '', ''),
(2653634, '169325', '800308', '2024-02-21', '', '', '', '', '', '', '', '', '', '', ''),
(2653635, '169325', '800308', '2024-02-22', '', '', '', '', '', '', '', '', '', '', ''),
(2653636, '169325', '160328', '2024-02-22', '06:00', '19:46', '0', 'Regular', '13.77', '30', '45', '15', '413.1', '619.65', '206.55'),
(2653637, '169325', '160328', '2024-02-16', '', '', '', '', '', '', '', '', '', '', ''),
(2653638, '169325', '160328', '2024-02-17', '', '', '', '', '', '', '', '', '', '', ''),
(2653639, '169325', '160328', '2024-02-18', '', '', '', '', '', '', '', '', '', '', ''),
(2653640, '169325', '160328', '2024-02-19', '', '', '', '', '', '', '', '', '', '', ''),
(2653641, '169325', '160328', '2024-02-20', '', '', '', '', '', '', '', '', '', '', ''),
(2653642, '169325', '160328', '2024-02-21', '', '', '', '', '', '', '', '', '', '', ''),
(2653643, '169325', '160328', '2024-02-22', '', '', '', '', '', '', '', '', '', '', ''),
(2653644, '169325', '387893', '2024-02-19', '06:00', '19:46', '0', 'Regular', '13.77', '30', '45', '15', '413.1', '619.65', '206.55'),
(2653645, '169325', '387893', '2024-02-12', '', '', '', '', '', '', '', '', '', '', ''),
(2653646, '169325', '387893', '2024-02-13', '', '', '', '', '', '', '', '', '', '', ''),
(2653647, '169325', '387893', '2024-02-14', '', '', '', '', '', '', '', '', '', '', ''),
(2653648, '169325', '387893', '2024-02-15', '', '', '', '', '', '', '', '', '', '', ''),
(2653649, '169325', '387893', '2024-02-16', '', '', '', '', '', '', '', '', '', '', ''),
(2653650, '169325', '387893', '2024-02-17', '', '', '', '', '', '', '', '', '', '', ''),
(2653651, '169325', '387893', '2024-02-18', '', '', '', '', '', '', '', '', '', '', ''),
(2653652, '169325', '954690', '2024-02-12', '', '', '', '', '', '', '', '', '', '', ''),
(2653653, '169325', '954690', '2024-02-13', '', '', '', '', '', '', '', '', '', '', ''),
(2653654, '169325', '954690', '2024-02-14', '', '', '', '', '', '', '', '', '', '', ''),
(2653655, '169325', '954690', '2024-02-15', '', '', '', '', '', '', '', '', '', '', ''),
(2653656, '169325', '954690', '2024-02-16', '', '', '', '', '', '', '', '', '', '', ''),
(2653657, '169325', '954690', '2024-02-17', '', '', '', '', '', '', '', '', '', '', ''),
(2653658, '169325', '954690', '2024-02-18', '', '', '', '', '', '', '', '', '', '', ''),
(2653660, '169325', '435806', '2024-06-03', '', '', '', '', '', '', '', '', '', '', ''),
(2653661, '169325', '435806', '2024-06-04', '', '', '', '', '', '', '', '', '', '', ''),
(2653662, '169325', '435806', '2024-06-05', '', '', '', '', '', '', '', '', '', '', ''),
(2653663, '169325', '435806', '2024-06-06', '', '', '', '', '', '', '', '', '', '', ''),
(2653664, '169325', '435806', '2024-06-07', '', '', '', '', '', '', '', '', '', '', ''),
(2653665, '169325', '435806', '2024-06-08', '', '', '', '', '', '', '', '', '', '', ''),
(2653666, '169325', '435806', '2024-06-09', '', '', '', '', '', '', '', '', '', '', ''),
(2653667, '169325', '435806', '2024-06-04', '15:25', '15:25', '0', 'Regular', '6', '20', '25', '5', '120', '150', '30'),
(2653671, '169325', '435806', '2024-06-05', '15:25', '15:25', '0', 'Regular', '6', '20', '25', '5', '120', '150', '30'),
(2653672, '169325', '560743', '2024-06-06', '15:25', '15:25', '0', 'Regular', '6', '20', '25', '5', '120', '150', '30'),
(2653673, '169325', '560743', '2024-06-03', '', '', '', '', '', '', '', '', '', '', ''),
(2653674, '169325', '560743', '2024-06-04', '', '', '', '', '', '', '', '', '', '', ''),
(2653675, '169325', '560743', '2024-06-05', '', '', '', '', '', '', '', '', '', '', ''),
(2653676, '169325', '560743', '2024-06-06', '', '', '', '', '', '', '', '', '', '', ''),
(2653677, '169325', '560743', '2024-06-07', '', '', '', '', '', '', '', '', '', '', ''),
(2653678, '169325', '560743', '2024-06-08', '', '', '', '', '', '', '', '', '', '', ''),
(2653679, '169325', '560743', '2024-06-09', '', '', '', '', '', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `tokens`
--

CREATE TABLE `tokens` (
  `id` int(11) NOT NULL,
  `app_id` text NOT NULL,
  `email` text NOT NULL,
  `token` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tokens`
--

INSERT INTO `tokens` (`id`, `app_id`, `email`, `token`) VALUES
(30, '', 'musengemichaeljr@gmail.com', '08HMDE'),
(32, '', 'millie@nocturnalrecruitment.co.uk', 'DC5PMP'),
(33, '', 'chax@nocturnalrecruitment.co.uk', '839CLC'),
(34, '', 'chax@nocturnalrecruitment.co.uk', 'RNP9DE'),
(35, '', 'chax@nocturnalrecruitment.co.uk', 'RF6EC2'),
(36, '', 'musengemichaeljr@gmail.com', 'DL1M9L'),
(37, '', 'musengemichaeljr@gmail.com', 'NAK1FB'),
(38, '', 'millie@nocturnalrecruitment.co.uk', 'GEDE9R'),
(39, '', 'compliance@nocturnalrecruitment.co.uk', 'DFF2GJ'),
(40, '', 'compliance@nocturnalrecruitment.co.uk', '348CC0'),
(41, '', 'chax@nocturnalrecruitment.co.uk', '1CG2CD'),
(42, '', 'compliance@nocturnalrecruitment.co.uk', 'P4750E'),
(43, '', 'chax@nocturnalrecruitment.co.uk', 'B2HJHQ'),
(44, '', 'chax@nocturnalrecruitment.co.uk', 'B1FP67'),
(45, '', 'musengemichaeljr@gmail.com', 'B145BQ');

-- --------------------------------------------------------

--
-- Table structure for table `usages`
--

CREATE TABLE `usages` (
  `id` int(11) NOT NULL,
  `app_id` text NOT NULL,
  `page` text NOT NULL,
  `person` text NOT NULL,
  `date` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `app_id` text NOT NULL,
  `first_name` text NOT NULL,
  `middle_name` text NOT NULL,
  `last_name` text NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `role` text NOT NULL,
  `profile` text NOT NULL,
  `department` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `app_id`, `first_name`, `middle_name`, `last_name`, `email`, `password`, `role`, `profile`, `department`) VALUES
(3, '169325', 'Samantha ', '', 'Sunduza-Heath', 'compliance@nocturnalrecruitment.co.uk', 'Samantha', 'Admin', 'placeholder.png', 'Social Care'),
(5, '169325', 'Michael', '', 'Musenge', 'musengemichaeljr@gmail.com', '123456', 'Admin', '14.jpg', 'Administrative'),
(9, '169325', 'Jay', '', 'Fuller', 'jayden@nocturnalrecruitment.co.uk', 'Raiders_1', 'Consultant', 'placeholder.png', 'Social Care'),
(10, '169325', 'Alex', '', 'Lapompe', 'Alex@nocturnalrecruitment.co.uk', 'Skillibeng66!', 'Admin', '14.jpg', 'Social Care'),
(11, '169325', 'Jack ', '', 'Dowler ', 'j.dowler@nocturnalrecruitment.co.uk', 'Skillibeng88!', 'Admin', 'placeholder.png', 'Social Care'),
(13, '169325', 'Millie', '', 'Brown', 'millie@nocturnalrecruitment.co.uk', 'MissBrown', 'Consultant', 'placeholder.png', 'Administrative'),
(14, '169325', 'Chax', '', 'Shamwana', 'Chax@nocturnalrecruitment.co.uk', '123456', 'Admin', 'placeholder.png', 'Social Care'),
(15, '169325', 'Andie', '', 'Cognation', 'info@cog-nation.com', '123456', 'Admin', 'placeholder.png', 'Social Care'),
(16, '169325', 'Jourdain', '', 'Bryan', 'jourdain@nocturnalrecruitment.co.uk', 'Raiders_1', 'Consultant', '', 'Social Care');

-- --------------------------------------------------------

--
-- Table structure for table `usertype`
--

CREATE TABLE `usertype` (
  `id` int(11) NOT NULL,
  `app_id` text NOT NULL,
  `name` text NOT NULL,
  `createdBy` text NOT NULL,
  `createdOn` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `usertype`
--

INSERT INTO `usertype` (`id`, `app_id`, `name`, `createdBy`, `createdOn`) VALUES
(1, '169325', 'Consultant', 'Michael Jr Musenge', '10th April 2023'),
(3, '169325', 'Accountant', 'Michael Jr Musenge', '10th April 2023'),
(6, '169325', 'Admin', 'Michael Jr Musenge', '10th April 2023');

-- --------------------------------------------------------

--
-- Table structure for table `vacancy`
--

CREATE TABLE `vacancy` (
  `id` int(11) NOT NULL,
  `app_id` text NOT NULL,
  `vacancyid` text NOT NULL,
  `status` varchar(200) NOT NULL DEFAULT 'Active',
  `job_title` text NOT NULL,
  `job_type` text NOT NULL,
  `client` text NOT NULL,
  `branchname` text NOT NULL,
  `startdate` text NOT NULL,
  `enddate` text NOT NULL,
  `numberrole` text NOT NULL,
  `specification` text NOT NULL,
  `createdBy` text NOT NULL,
  `createdOn` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `vacancy`
--

INSERT INTO `vacancy` (`id`, `app_id`, `vacancyid`, `status`, `job_title`, `job_type`, `client`, `branchname`, `startdate`, `enddate`, `numberrole`, `specification`, `createdBy`, `createdOn`) VALUES
(1, '169325', '39919306674362112', 'Active', 'Support Worker', 'Shift', '12', '12', '2024-01-07', '2024-01-07', '10', '', '5', '2024-01-07'),
(3, '169325', '93517583589372535', 'Active', 'Home Care Assistant #2', 'Shift', '8', '8', '2024-01-08', '2024-01-14', '3', '', '5', '2024-01-07'),
(4, '169325', '92311659980965653', 'Active', 'Support Worker', 'Shift', '1', '1', '2024-01-15', '2024-07-12', '5', '', '1', '2024-01-15'),
(5, '169325', '679703929905902', 'Active', 'Support Worker', 'Shift', '24', '24', '2024-05-09', '2024-05-09', '5', '', '10', '2024-05-09 12:59:28');

-- --------------------------------------------------------

--
-- Table structure for table `vacancylist`
--

CREATE TABLE `vacancylist` (
  `id` int(11) NOT NULL,
  `app_id` text NOT NULL,
  `vacancyid` text NOT NULL,
  `shiftype` text NOT NULL,
  `starttime` text NOT NULL,
  `endtime` text NOT NULL,
  `payrate` text NOT NULL,
  `supplierrate` text NOT NULL,
  `totalmargin` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `vacancylist`
--

INSERT INTO `vacancylist` (`id`, `app_id`, `vacancyid`, `shiftype`, `starttime`, `endtime`, `payrate`, `supplierrate`, `totalmargin`) VALUES
(1, '169326', '4151376480542620', 'Regular', '07:20', '17:30', '12.5', '19', '6.5'),
(2, '169325', '84621361992603730', 'Regular', '23:37', '23:37', '0', '0', '0'),
(5, '169325', '45818625362001226', 'Regular', '07:30', '22:00', '14.67', '19.28', '4.61'),
(6, '169325', '45818625362001226', 'Saturday Day', '07:30', '22:00', '15', '24.4', '9.4'),
(7, '169325', '45818625362001226', 'Sunday Day', '07:30', '22:00', '15', '34.91', '19.91'),
(9, '169325', '45818625362001226', 'Saturday Night', '22:00', '07:00', '16', '24.84', '8.84'),
(10, '169325', '45818625362001226', 'Sunday Day', '22:00', '07:00', '16', '32.2', '16.2'),
(11, '169325', '45818625362001226', 'Bank Holiday', '07:30', '22:00', '16.61', '32.2', '15.59'),
(12, '169325', '2462272422104641', 'Permanent', '07:30', '17:30', '2000', '2320', '2320'),
(13, '169325', '2462272422104641', 'Permanent', '07:30', '17:30', '2000', '2320', '2320'),
(14, '169325', '2462272422104641', 'Permanent', '07:30', '17:30', '2000', '2320', '2320'),
(15, '169325', '2462272422104641', 'Permanent', '07:30', '17:30', '2000', '2320', '2320'),
(18, '169325', '82219894575524147', 'Regular', '06:40', '23:40', '10', '110', '100'),
(19, '169325', '313943561285007', 'Regular', '00:00', '05:00', '10', '100', '90'),
(20, '169325', '976671931925323', 'Regular', '00:53', '00:53', '100', '900', '800'),
(21, '169325', '5082708682001009', 'Regular', '10:10', '10:10', '100', '900', '800'),
(22, '169325', '8827506473550317', '', '16:03', '16:03', '0', '0', '0'),
(23, '169325', '8277977276811301', 'Regular', '15:13', '15:13', '10', '40', '30'),
(25, '169325', '93517583589372535', 'Regular', '15:25', '15:25', '20', '25', '5'),
(26, '169325', '92311659980965653', 'Regular', '07:00', '17:00', '14', '18', '4'),
(27, '169325', '92311659980965653', 'Weekday Night', '20:00', '07:00', '15', '18', '3'),
(28, '169325', '92311659980965653', 'Regular', '12:58', '12:58', '0', '0', '0'),
(29, '169325', '39919306674362112', 'Regular', '06:00', '19:46', '30', '45', '15'),
(30, '169325', '93517583589372535', 'Regular', '14:24', '14:24', '0', '0', '0'),
(31, '169325', '93517583589372535', 'Weekday Night', '14:24', '14:24', '0', '0', '0'),
(32, '169325', '679703929905902', 'Regular', '12:59', '12:59', '0', '0', '0');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activeusers`
--
ALTER TABLE `activeusers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bank_info`
--
ALTER TABLE `bank_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `calllog`
--
ALTER TABLE `calllog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `campaigns`
--
ALTER TABLE `campaigns`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `candidates`
--
ALTER TABLE `candidates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `candidatesfiles`
--
ALTER TABLE `candidatesfiles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `clientype`
--
ALTER TABLE `clientype`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_call_logs`
--
ALTER TABLE `client_call_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `communicationlogs`
--
ALTER TABLE `communicationlogs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `connected_app`
--
ALTER TABLE `connected_app`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `currency`
--
ALTER TABLE `currency`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `date`
--
ALTER TABLE `date`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `documenttype`
--
ALTER TABLE `documenttype`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emailconfigs`
--
ALTER TABLE `emailconfigs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emaillist`
--
ALTER TABLE `emaillist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emails`
--
ALTER TABLE `emails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `emergency`
--
ALTER TABLE `emergency`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `interviews`
--
ALTER TABLE `interviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoiceitems`
--
ALTER TABLE `invoiceitems`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobtype`
--
ALTER TABLE `jobtype`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `keyjobarea`
--
ALTER TABLE `keyjobarea`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kpi`
--
ALTER TABLE `kpi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kpiachieved`
--
ALTER TABLE `kpiachieved`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loginstatus`
--
ALTER TABLE `loginstatus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauthtokens`
--
ALTER TABLE `oauthtokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organization`
--
ALTER TABLE `organization`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pagelogs`
--
ALTER TABLE `pagelogs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permission`
--
ALTER TABLE `permission`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `planner`
--
ALTER TABLE `planner`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shifts`
--
ALTER TABLE `shifts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shifttype`
--
ALTER TABLE `shifttype`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timesheet`
--
ALTER TABLE `timesheet`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timesheetlist`
--
ALTER TABLE `timesheetlist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tokens`
--
ALTER TABLE `tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usertype`
--
ALTER TABLE `usertype`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vacancy`
--
ALTER TABLE `vacancy`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `vacancylist`
--
ALTER TABLE `vacancylist`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activeusers`
--
ALTER TABLE `activeusers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `bank_info`
--
ALTER TABLE `bank_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `calllog`
--
ALTER TABLE `calllog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `campaigns`
--
ALTER TABLE `campaigns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `candidates`
--
ALTER TABLE `candidates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=245;

--
-- AUTO_INCREMENT for table `candidatesfiles`
--
ALTER TABLE `candidatesfiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=730;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `clientype`
--
ALTER TABLE `clientype`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `client_call_logs`
--
ALTER TABLE `client_call_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `communicationlogs`
--
ALTER TABLE `communicationlogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `connected_app`
--
ALTER TABLE `connected_app`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `currency`
--
ALTER TABLE `currency`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `date`
--
ALTER TABLE `date`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `documenttype`
--
ALTER TABLE `documenttype`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `emailconfigs`
--
ALTER TABLE `emailconfigs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `emaillist`
--
ALTER TABLE `emaillist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=189;

--
-- AUTO_INCREMENT for table `emails`
--
ALTER TABLE `emails`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `emergency`
--
ALTER TABLE `emergency`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `interviews`
--
ALTER TABLE `interviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `invoiceitems`
--
ALTER TABLE `invoiceitems`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jobtype`
--
ALTER TABLE `jobtype`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `keyjobarea`
--
ALTER TABLE `keyjobarea`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `kpi`
--
ALTER TABLE `kpi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `kpiachieved`
--
ALTER TABLE `kpiachieved`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `loginstatus`
--
ALTER TABLE `loginstatus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `oauthtokens`
--
ALTER TABLE `oauthtokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `organization`
--
ALTER TABLE `organization`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=160;

--
-- AUTO_INCREMENT for table `pagelogs`
--
ALTER TABLE `pagelogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1738;

--
-- AUTO_INCREMENT for table `permission`
--
ALTER TABLE `permission`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=179;

--
-- AUTO_INCREMENT for table `planner`
--
ALTER TABLE `planner`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `shifts`
--
ALTER TABLE `shifts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `shifttype`
--
ALTER TABLE `shifttype`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `timesheet`
--
ALTER TABLE `timesheet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `timesheetlist`
--
ALTER TABLE `timesheetlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2653680;

--
-- AUTO_INCREMENT for table `tokens`
--
ALTER TABLE `tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `vacancy`
--
ALTER TABLE `vacancy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `vacancylist`
--
ALTER TABLE `vacancylist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
